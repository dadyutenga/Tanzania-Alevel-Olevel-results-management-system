# Tanzania Results Management System - Docker Deployment

## Quick Start

1. **Make deployment script executable (Linux/Mac):**
   ```bash
   chmod +x deploy.sh
   ```

2. **Deploy the application:**
   ```bash
   ./deploy.sh
   ```

   Or manually:
   ```bash
   docker-compose up -d
   ```

3. **Access the application:**
   - Application: http://localhost:8080
   - MinIO Console: http://localhost:9001
   - PostgreSQL: localhost:5433

## Default Credentials

- **PostgreSQL:**
  - User: `tz_results_user`
  - Password: `SecurePass2025!`
  - Database: `school_result`

- **MinIO:**
  - Access Key: `91Z059h0qV3GV3LNN2E3`
  - Secret Key: `ChangeMeInProduction`

## Services

- **app**: AlmaLinux 9 with PHP 8.2 and PHP-FPM
- **nginx**: Nginx web server
- **postgres**: PostgreSQL 16 database
- **minio**: MinIO object storage

## Useful Commands

### View logs
```bash
docker-compose logs -f app
docker-compose logs -f postgres
docker-compose logs -f nginx
```

### Access container shell
```bash
docker-compose exec app bash
```

### Run migrations
```bash
docker-compose exec app php spark migrate
```

### Restart services
```bash
docker-compose restart
```

### Stop all services
```bash
docker-compose down
```

### Stop and remove volumes
```bash
docker-compose down -v
```

## Production Deployment

1. **Update environment variables** in `.env.docker`:
   - Change database password
   - Change MinIO secret key
   - Update base URL

2. **Set MinIO secret key before deployment:**
   ```bash
   export MINIO_SECRET_KEY="YourSecureSecretKey"
   ```

3. **Enable SSL** by adding certificates to `docker/nginx/ssl/`

4. **Update nginx config** for SSL in `docker/nginx/default.conf`

## Troubleshooting

### Database connection issues
```bash
docker-compose exec postgres psql -U tz_results_user -d school_result
```

### Clear cache
```bash
docker-compose exec app rm -rf writable/cache/*
```

### Rebuild containers
```bash
docker-compose down
docker-compose build --no-cache
docker-compose up -d
```

## System Requirements

- Docker 20.10+
- Docker Compose 2.0+
- 4GB RAM minimum
- 10GB disk space
