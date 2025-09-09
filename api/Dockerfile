# Production-ready Dockerfile for the Flask API
# - Uses Python slim base image
# - Installs dependencies and gunicorn
# - Runs DB migrations (if DATABASE_URL is provided) before starting
# - Runs as non-root user

FROM python:3.13-slim AS runtime

ENV PYTHONDONTWRITEBYTECODE=1 \
    PYTHONUNBUFFERED=1 \
    PIP_NO_CACHE_DIR=1

# Set workdir
WORKDIR /app

# System deps (optional minimal CA certificates and net utilities for healthcheck)
# python:slim already includes certificates; curl is installed for HEALTHCHECK
RUN apt-get update && apt-get install -y --no-install-recommends \
    curl \
 && rm -rf /var/lib/apt/lists/*

# Copy requirements and install first (better layer caching)
COPY requirements.txt ./
RUN pip install --upgrade pip && \
    pip install -r requirements.txt

# Copy the rest of the application
COPY . .

# Create non-root user and adjust permissions
RUN useradd -m appuser && chown -R appuser:appuser /app && \
    chmod +x /app/entrypoint.sh
USER appuser

# Default environment
ENV PORT=8000

EXPOSE 8000

# Basic healthcheck that calls the health endpoint
HEALTHCHECK --interval=30s --timeout=5s --start-period=20s --retries=3 \
  CMD curl --fail http://localhost:${PORT}/health || exit 1

ENTRYPOINT ["/app/entrypoint.sh"]
