# So we can use /bin/ash to shell into the container
FROM traefik:alpine

ARG BASIC_AUTH_USERNAME
ARG BASIC_AUTH_PASSWORD

ENV BASIC_AUTH_USERNAME $BASIC_AUTH_USERNAME
ENV BASIC_AUTH_PASSWORD $BASIC_AUTH_PASSWORD

RUN apk add --update apache2-utils \
    && rm -rf /var/cache/apk/* \
    && mkdir -p /var/log/traefik \
    && htpasswd -cBb /usr/local/share/.auth $BASIC_AUTH_USERNAME $BASIC_AUTH_PASSWORD
