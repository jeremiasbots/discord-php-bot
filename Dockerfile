# Use PHP CLI as base image
FROM php:8.3-cli

# Set working directory
WORKDIR /home/app

# Install required system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    && rm -rf /var/lib/apt/lists/*

# Copy project files
COPY . .

# Install Composer (multi-stage build)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create a non-root user for security
RUN useradd -m appuser && chown -R appuser:appuser /home/app

# Switch to non-root user
USER appuser

# Install PHP dependencies
RUN composer install --no-dev --prefer-dist --optimize-autoloader

# Run the application
CMD ["php", "index.php"]
