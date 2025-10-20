# Base PHP image
FROM php:8.2-cli

# Copy all files
COPY . /app
WORKDIR /app

# Install required extensions
RUN docker-php-ext-install curl

# Expose port for Render
EXPOSE 10000

# Start PHP built-in web server
CMD ["php", "-S", "0.0.0.0:10000", "fetch.php"]
