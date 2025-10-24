#!/bin/bash

echo "🚀 Tanzania Results Management - Docker Deployment"

# Stop existing containers
echo "📦 Stopping existing containers..."
docker-compose down

# Build and start
echo "🔨 Building containers..."
docker-compose build --no-cache

echo "🚀 Starting services..."
docker-compose up -d

# Wait for PostgreSQL
echo "⏳ Waiting for PostgreSQL..."
sleep 15

# Run migrations
echo "📊 Running database migrations..."
docker-compose exec -T app php spark migrate

# Set permissions
echo "🔐 Setting permissions..."
docker-compose exec -T app chown -R apache:apache /var/www/html/writable

echo "✅ Deployment complete!"
echo "📍 Application: http://localhost:8080"
echo "📍 MinIO Console: http://localhost:9001"
echo "📍 PostgreSQL: localhost:5433"
echo ""
echo "📝 Default credentials:"
echo "   PostgreSQL: tz_results_user / SecurePass2025!"
echo "   MinIO: 91Z059h0qV3GV3LNN2E3 / ChangeMeInProduction"
