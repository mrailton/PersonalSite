# Deployment Configuration

This document describes the GitHub Actions workflows and required secrets for CI/CD.

## Workflows

### 1. `test-only.yml` - Basic Testing
- **Trigger**: All pushes and pull requests to any branch
- **Purpose**: Run tests to verify code quality
- **Requirements**: None (no secrets needed)

### 2. `ci-cd.yml` - Full CI/CD Pipeline
- **Trigger**: Pushes to `main` and `develop` branches, PRs to `main`
- **Purpose**: Full testing, security scanning, building, and deployment
- **Requirements**: Several secrets (see below)

## Required GitHub Secrets

To enable the full CI/CD pipeline, configure these secrets in your GitHub repository settings:

### Docker Hub Integration
```
DOCKER_USERNAME=your_docker_hub_username
DOCKER_PASSWORD=your_docker_hub_password_or_token
```

### Coolify Integration
```
COOLIFY_WEBHOOK_URL=https://your-coolify-instance.com/api/v1/deploy/webhook
COOLIFY_API_TOKEN=your_coolify_api_token
COOLIFY_APP_URL=https://your-deployed-app.com
```

### Optional: Code Coverage
```
CODECOV_TOKEN=your_codecov_token  # Optional, for code coverage reports
```

## Setting Up Secrets

1. Go to your GitHub repository
2. Click **Settings** → **Secrets and variables** → **Actions**
3. Click **New repository secret**
4. Add each secret with its corresponding value

## Coolify Configuration

### Prerequisites
1. Have a Coolify instance running
2. Create an application in Coolify for your API
3. Configure the application to use Docker images
4. Generate an API token in Coolify settings
5. Set up a webhook URL for deployment triggers

### Deployment Process
1. Tests pass on the `main` branch
2. Docker image is built and pushed to Docker Hub
3. Coolify webhook is triggered with the new image
4. Application is deployed automatically
5. Health check verifies the deployment

## Local Testing

To test the workflows locally before pushing:

```bash
# Run tests
python -m pytest tests/ -v

# Test application startup
python -c "from app import create_app; app = create_app(); print('App starts successfully')"

# Run security checks (optional)
pip install bandit safety
bandit -r app/
safety check
```

## Environment Variables

The following environment variables are set automatically in GitHub Actions:

### Testing Environment
- `DATABASE_URL`: MySQL database for testing (mysql+pymysql://testuser:testpassword@localhost:3306/testdb)
- `SECRET_KEY`: Test secret key for JWT tokens

### Production Environment
These should be configured in your Coolify application:
- `DATABASE_URL`: Production MySQL database URL (mysql+pymysql://user:password@mysql-host:3306/dbname)
- `SECRET_KEY`: Strong secret key for JWT tokens
- `API_TITLE`: Production API title (optional, defaults to "Mark Railton API")
- `API_VERSION`: Current API version (optional, defaults to "1.0.0")

## Troubleshooting

### Common Issues

1. **Tests failing**: Check that all dependencies are in `requirements.txt` and PyMySQL is installed
2. **MySQL connection failing**: Verify DATABASE_URL format and credentials
3. **Docker build failing**: Verify Dockerfile is correct and builds locally
4. **Coolify deployment failing**: Check webhook URL and API token
5. **Health check failing**: Ensure `/health` endpoint is accessible

### Debugging Steps

1. Check GitHub Actions logs for specific error messages
2. Test Docker build locally: `docker build -t test-image .`
3. Verify Coolify webhook manually with curl
4. Check Coolify application logs for deployment issues

## Security Considerations

- Never commit secrets to the repository
- Use environment-specific secrets (test vs production)
- Regularly rotate API tokens and passwords
- Monitor security scan results and address issues promptly