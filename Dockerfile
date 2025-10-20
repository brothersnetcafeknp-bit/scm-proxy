# Base PHP image
FROM php:8.2-cli

# Copy app files
COPY . /app
WORKDIR /app

# Expose port for Render
EXPOSE 10000

# Start PHP built-in server
CMD ["php", "-S", "0.0.0.0:10000", "fetch.php"]
