# Use the official Nginx base image
FROM nginx:latest

# Change the UID of the www-data user to 1000
RUN usermod -u 1000 www-data

# Remove the default Nginx configuration
RUN rm /etc/nginx/conf.d/default.conf

# Copy your custom Nginx configuration
COPY nginx/default.conf /etc/nginx/conf.d/

# Copy your web application files to the container
COPY . /usr/share/nginx/html

# Expose the port Nginx will run on
EXPOSE 80

# Start Nginx in the foreground
CMD ["nginx", "-g", "daemon off;"]
