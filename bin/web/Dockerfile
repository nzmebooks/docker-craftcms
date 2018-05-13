# https://github.com/wyveo/nginx-php-fpm/blob/php72/Dockerfile
# https://github.com/wyveo/craftcms-docker/blob/craft3/Dockerfile
FROM wyveo/nginx-php-fpm:latest

# We should figure out how to interpolate REDIS_PORT in the sed below
ENV REDIS_PORT tcp://redis:6379

# Remove default webroot files & set PHP session handler to Redis
# & ensure Redis errors go to logs
# & install postgresql-client (pg_dump)
RUN rm -rf /usr/share/nginx/* && \
sed -i -e "s/pm.max_children\s*=\s*.*/pm.max_children = 25/g" ${fpm_conf} && \
sed -i -e "s/;php_admin_flag\[log_errors\]\s=\son/php_admin_flag\[log_errors\] = on/g" ${fpm_conf} && \
sed -i -e "s/max_execution_time\s*=\s*.*/max_execution_time = 300/g" ${php_conf} && \
sed -i -e "s/memory_limit\s*=\s*.*/memory_limit = 256M/g" ${php_conf} && \
sed -i -e "s/session.save_handler\s*=\s*.*/session.save_handler = redis/g" ${php_conf} && \
sed -i -e "s/;session.save_path\s*=\s*.*/session.save_path = \"tcp:\/\/redis:6379\"/g" ${php_conf} && \
sed -i -e "s/;error_log\s=\sphp_errors.log/error_log = \/var\/log\/php_errors.log/g" ${php_conf} && \
wget -q -O - https://www.postgresql.org/media/keys/ACCC4CF8.asc | apt-key add - && \
sh -c 'echo "deb http://apt.postgresql.org/pub/repos/apt/ `lsb_release -cs`-pgdg main" >> /etc/apt/sources.list.d/pgdg.list' && \
apt-get update && \
apt-get install -y postgresql-client-10

# Add default craft cms nginx config
ADD ./default.conf /etc/nginx/conf.d/default.conf

EXPOSE 80
