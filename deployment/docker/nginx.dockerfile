# Use the official Nginx base image
FROM nginx:latest

ARG HOST_UID=1000
ARG HOST_GID=1000

# Change the UID of the www-data user to 1000
RUN usermod -u $HOST_UID www-data \
  && groupmod --gid $HOST_GID www-data

# Remove the default Nginx configuration
RUN rm /etc/nginx/conf.d/default.conf
RUN rm /etc/nginx/nginx.conf

# Copy your custom Nginx configuration
COPY ./deployment/conf.d/nginx/default.conf /etc/nginx/conf.d/
COPY ./deployment/conf.d/nginx/nginx.conf /etc/nginx/

# Copy your web application files to the container
COPY . /var/www

# Expose the port Nginx will run on
EXPOSE 80

# Start Nginx in the foreground
CMD ["nginx", "-g", "daemon off;"]
