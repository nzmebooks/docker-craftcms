# https://github.com/wyveo/nginx-php-fpm/blob/php71/Dockerfile
# https://github.com/wyveo/craftcms-docker/blob/craft2/Dockerfile
FROM wyveo/nginx-php-fpm:php71

# Remove default webroot files & set PHP session handler to Redis
RUN rm -rf /usr/share/nginx/html/* && \
sed -i -e "s/session.save_handler\s*=\s*.*/session.save_handler = redis/g" ${php_conf} && \
sed -i -e "s/;session.save_path\s*=\s*.*/session.save_path = \"\${REDIS_PORT_6379_TCP}\"/g" ${php_conf}

# Add default craft cms nginx config
# ADD ./default.conf /etc/nginx/conf.d/default.conf

# Add default config
# ADD ./craft/config /usr/share/nginx/craft/config

# Create logs directory
RUN mkdir -p /var/log/nginx

# Cleanup
RUN chown -Rf nginx:nginx /usr/share/nginx/

EXPOSE 80
