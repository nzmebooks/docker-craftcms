# https://github.com/wyveo/nginx-php-fpm/blob/php71/Dockerfile
# https://github.com/wyveo/craftcms-docker/blob/craft2/Dockerfile
FROM wyveo/nginx-php-fpm:php71

# Remove default webroot files & set PHP session handler to Redis
RUN rm -rf /usr/share/nginx/html/* && \
sed -i -e "s/pm.max_children\s*=\s*.*/pm.max_children = 25/g" ${fpm_conf} && \
sed -i -e "s/max_execution_time\s*=\s*.*/max_execution_time = 300/g" ${php_conf} && \
sed -i -e "s/memory_limit\s*=\s*.*/memory_limit = 256M/g" ${php_conf} && \
sed -i -e "s/session.save_handler\s*=\s*.*/session.save_handler = redis/g" ${php_conf} && \
sed -i -e "s/;session.save_path\s*=\s*.*/session.save_path = \"\${REDIS_PORT_6379_TCP}\"/g" ${php_conf}

# Add default craft cms nginx config
ADD ./default.conf /etc/nginx/conf.d/default.conf

# Cleanup
RUN chown -Rf nginx:nginx /usr/share/nginx/

EXPOSE 80
