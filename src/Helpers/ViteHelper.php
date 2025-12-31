<?php

declare(strict_types=1);

namespace App\Helpers;

class ViteHelper
{
    private bool $isDev;
    private string $devServerUrl;
    private bool $useViteDevServer;
    private ?array $manifest = null;
    private array $renderedEntries = [];

    public function __construct(bool $isDev = false, string $devServerUrl = 'http://localhost:5173', bool $useViteDevServer = true)
    {
        $this->isDev = $isDev;
        $this->devServerUrl = $devServerUrl;
        $this->useViteDevServer = $useViteDevServer;

        if (!$this->isDev || !$this->useViteDevServer) {
            $manifestPath = __DIR__ . '/../../public/build/.vite/manifest.json'; // Updated manifest path
            if (file_exists($manifestPath)) {
                $manifestContent = file_get_contents($manifestPath);
                if ($manifestContent !== false) {
                    $manifestData = json_decode($manifestContent, true);
                    if (is_array($manifestData)) {
                        $this->manifest = $manifestData;
                    }
                }
            }
        }
    }

    public function getHmrClient(): string
    {
        if ($this->isDev && $this->useViteDevServer) {
            return sprintf(
                '<script type="module" src="%s/@vite/client"></script>',
                $this->devServerUrl
            );
        }
        return '';
    }

    public function getAssetPath(string $asset): string
    {
        if ($this->isDev && $this->useViteDevServer) {
            return sprintf('%s/%s', $this->devServerUrl, $asset);
        }

        if (!$this->manifest) {
            // Fallback for when manifest is not found
            return '/build/' . $asset; // Assuming /build is the static root
        }

        $assetKey = ltrim($asset, '/');

        if (isset($this->manifest[$assetKey])) {
            return '/build/' . $this->manifest[$assetKey]['file'];
        }

        foreach ($this->manifest as $key => $value) {
            if (str_ends_with($key, $assetKey)) {
                return '/build/' . $value['file'];
            }
        }

        return '/build/' . $asset; // Fallback
    }

    public function getCssTags(string $entry): string
    {
        $html = '';
        if ($this->isDev && $this->useViteDevServer) {
            // Explicitly add CSS link in dev mode to prevent FOUC
            if (str_ends_with($entry, '.js')) {
                $cssEntry = str_replace('.js', '.css', $entry);
                $html .= sprintf(
                    '<link rel="stylesheet" href="%s/%s"></link>' . "\n",
                    $this->devServerUrl,
                    $cssEntry
                );
            }
            return $html;
        }

        if (!$this->manifest) {
            return '';
        }

        $assetKey = ltrim($entry, '/');
        $manifestEntry = null;

        if (isset($this->manifest[$assetKey])) {
            $manifestEntry = $this->manifest[$assetKey];
        } else {
            foreach ($this->manifest as $key => $value) {
                if (str_ends_with($key, $assetKey)) {
                    $manifestEntry = $value;
                    break;
                }
            }
        }

        if ($manifestEntry && isset($manifestEntry['css'])) {
            foreach ($manifestEntry['css'] as $cssFile) {
                $html .= sprintf(
                    '<link rel="stylesheet" href="/build/%s">' . "\n",
                    $cssFile
                );
            }
        }

        return $html;
    }



    public function renderTags(string $entry): string
    {
        // Prevent duplicate renders
        if (in_array($entry, $this->renderedEntries)) {
            return '';
        }
        $this->renderedEntries[] = $entry;

        $html = '';

        $html .= $this->getHmrClient();
        $html .= $this->getCssTags($entry);
        $html .= sprintf(
            '<script type="module" src="%s"></script>',
            $this->getAssetPath($entry)
        );

        return $html;
    }
}