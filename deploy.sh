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

# Wait for MySQL
echo "⏳ Waiting for MySQL..."
sleep 15

# Run migrations
echo "📊 Running database migrations..."
docker-compose exec -T app php spark migrate

# Set permissions
echo "🔐 Setting permissions..."
docker-compose exec -T app chown -R apache:apache /var/www/html/writable

echo "✅ Deployment complete!"
echo "📍 Application: http://localhost:8888"
echo "📍 MySQL: localhost:3307"
echo ""
echo "📝 Default credentials:"
echo "   MySQL: tz_results_user / SecurePass2025!"
echo "   MySQL Root: root / RootPass2025!"
