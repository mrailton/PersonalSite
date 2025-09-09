# Nuxt Minimal Starter

Look at the [Nuxt documentation](https://nuxt.com/docs/getting-started/introduction) to learn more.

## Setup

Make sure to install dependencies:

```bash
# npm
npm install

# pnpm
pnpm install

# yarn
yarn install

# bun
bun install
```

## Development Server

Start the development server on `http://localhost:3000`:

```bash
# npm
npm run dev

# pnpm
pnpm dev

# yarn
yarn dev

# bun
bun run dev
```

## Production

Build the application for production:

```bash
# npm
npm run build

# pnpm
pnpm build

# yarn
yarn build

# bun
bun run build
```

Locally preview production build:

```bash
# npm
npm run preview

# pnpm
pnpm preview

# yarn
yarn preview

# bun
bun run preview
```

Check out the [deployment documentation](https://nuxt.com/docs/getting-started/deployment) for more information.

---

## Docker

This project includes a production-ready Docker setup.

### Prerequisites
- Docker 24+
- (Optional) Docker Compose v2

### Build the image
```bash
# from the project root
docker build -t personal-site-nuxt:latest .
```

### Run the container
```bash
docker run --rm -p 3000:3000 \
  -e API_URL="https://your-api.example.com" \
  --name personal-site personal-site-nuxt:latest
```

Open http://localhost:3000

Notes:
- API_URL is injected at runtime and is used via `runtimeConfig.public.apiUrl` in `nuxt.config.ts`.
- The server listens on port 3000 inside the container.

### Development vs Production
- The provided Dockerfile is optimized for production (Nitro server).
- For local hot-reload development, prefer running `npm run dev` on your host machine. If you need dev inside Docker, a separate dev-focused Dockerfile and volume mounts would be recommended.

### Passing Nuxt UI Pro license in Docker (Coolify)
Nuxt UI Pro requires a license key at build time. This project reads it from the environment variable `NUXT_UI_PRO_LICENSE` (see `nuxt.config.ts`).

With `docker build`:
```bash
docker build \
  --build-arg NUXT_UI_PRO_LICENSE="<your-license>" \
  -t personal-site-nuxt:latest .
```

With Coolify:
- In your Service > Build section, add a Build Variable named `NUXT_UI_PRO_LICENSE` with your key.
- No runtime env var is required for this license (it’s only needed during build).

### Node version
This app requires Node >= 22.18 (see `package.json`). The Dockerfile uses a Node 22 slim image for build and runtime.
