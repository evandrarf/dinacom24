# Use the official Nginx base image
FROM nginx:latest

# Change the UID of the www-data user to 1000
RUN usermod -u 1000 www-data \
  && groupmod --gid 1000 www-data

# Remove the default Nginx configuration
RUN rm /etc/nginx/conf.d/default.conf

# Copy your custom Nginx configuration
COPY ./deployment/conf.d/nginx/default.conf /etc/nginx/conf.d/

# Copy your web application files to the container
COPY . /var/www

# Expose the port Nginx will run on
EXPOSE 80

# Start Nginx in the foreground
CMD ["nginx", "-g", "daemon off;"]
