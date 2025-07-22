# Use an official PHP image with Apache pre-installed.
# This simplifies things as Apache is already configured to serve PHP.
# You can choose a specific PHP version, e.g., php:8.2-apache or php:8.3-apache.
FROM php:8.2-apache

# Set the working directory inside the container to Apache's default web root.
WORKDIR /var/www/html

# Copy all your project files into the container's web root.
# The '.' means "copy everything from the current directory (your repo root)
# to the WORKDIR in the container".
COPY . .

# Enable Apache's mod_rewrite module.
# This is crucial for your .htaccess rules to work.
RUN a2enmod rewrite

# Override Apache's default DirectoryIndex to prioritize index.php.
# This ensures that when a directory is requested (like '/'), index.php is used.
# This is often done in Apache config, but can be done via .htaccess or this command.
# For your setup with .htaccess, this might be redundant if .htaccess is perfect,
# but it's a good explicit step for clarity and robustness.
RUN echo 'DirectoryIndex index.php index.html index.htm' > /etc/apache2/mods-enabled/dir.conf

# Expose port 80, which is where Apache listens for HTTP traffic.
# Render will map this to a public port.
EXPOSE 80

# The default command for php:apache images starts Apache, so no CMD instruction is strictly needed.
# If you needed to run custom commands, you'd put them here, e.g., CMD ["apache2ctl", "-D", "FOREGROUND"]
