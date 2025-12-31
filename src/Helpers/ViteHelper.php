<?php

declare(strict_types=1);

namespace App\Helpers;

class ViteHelper
{
    private bool $isDev;
    private string $devServerUrl;
    private ?array $manifest = null;
    private array $renderedEntries = [];

    public function __construct(bool $isDev = false, string $devServerUrl = 'http://localhost:5173')
    {
        $this->isDev = $isDev;
        $this->devServerUrl = $devServerUrl;

        if (!$this->isDev) {
            $manifestPath = __DIR__ . '/../../public/build/manifest.json';
            if (file_exists($manifestPath)) {
                $this->manifest = json_decode(file_get_contents($manifestPath), true);
            }
        }
    }

    public function renderTags(string $entry): string
    {
        // Prevent duplicate renders
        if (in_array($entry, $this->renderedEntries)) {
            return '';
        }
        $this->renderedEntries[] = $entry;

        $html = '';

        if ($this->isDev) {
            // Development mode
            $html .= sprintf(
                '<script type="module" src="%s/@vite/client"></script>' . "\n",
                $this->devServerUrl
            );
            $html .= sprintf(
                '<script type="module" src="%s/%s"></script>',
                $this->devServerUrl,
                $entry
            );
        } else {
            // Production mode
            if ($this->manifest && isset($this->manifest[$entry])) {
                $manifestEntry = $this->manifest[$entry];

                // Add CSS files
                if (isset($manifestEntry['css'])) {
                    foreach ($manifestEntry['css'] as $cssFile) {
                        $html .= sprintf(
                            '<link rel="stylesheet" href="/build/%s">' . "\n",
                            $cssFile
                        );
                    }
                }

                // Add JS file
                $html .= sprintf(
                    '<script type="module" src="/build/%s"></script>',
                    $manifestEntry['file']
                );
            }
        }

        return $html;
    }
}