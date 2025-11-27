#!/bin/bash

# Trakly Quick Start Script

echo "🚀 Starting Trakly Development Server..."
echo ""

# Check if PHP is installed
if ! command -v php &> /dev/null
then
    echo "❌ PHP is not installed. Please install PHP 8.0 or higher."
    exit 1
fi

# Check PHP version
PHP_VERSION=$(php -r "echo PHP_VERSION;" | cut -d '.' -f 1)
if [ "$PHP_VERSION" -lt 8 ]; then
    echo "❌ PHP version 8.0 or higher is required. Current version: $(php -r 'echo PHP_VERSION;')"
    exit 1
fi

echo "✅ PHP version: $(php -r 'echo PHP_VERSION;')"
echo ""

# Check if database is configured
if grep -q "DB_PASS', ''" config/config.php; then
    echo "⚠️  Warning: Database password is empty in config.php"
    echo "   Make sure this is intentional for your setup."
    echo ""
fi

# Create uploads directory if it doesn't exist
if [ ! -d "public/uploads" ]; then
    mkdir -p public/uploads
    echo "✅ Created uploads directory"
fi

# Make uploads writable
chmod 755 public/uploads
echo "✅ Set permissions for uploads directory"
echo ""

# Start PHP development server
echo "🌐 Starting server at http://localhost:8000"
echo "📋 Default database: trakly (make sure it's created and schema is imported)"
echo ""
echo "Press Ctrl+C to stop the server"
echo ""

cd public && php -S localhost:8000
