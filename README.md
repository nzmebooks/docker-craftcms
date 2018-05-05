# What is this?

This is a docker-compose project to run up a LEMP stack comprising of:

* Traefik
* Portainer
* Nginx
* MySQL
* PHP
* Redis

You'll want to ensure DNS is set for the following routes pointing to 127.0.0.1, either in the hosts file or possibly using [Dnsmasq](https://github.com/elalemanyo/docker-localhost#hosts-file---wildcard-dns-domain-on-mac-os-x):

* localhost.portainer
* localhost.web

# Start

    docker-compose up -d --build
