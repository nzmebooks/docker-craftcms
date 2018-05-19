# What is this?

This is a docker-compose project to run up a Craft stack comprising of:

* [Traefik](https://docs.traefik.io/)
* [Portainer](https://portainer.readthedocs.io/en/stable/)
* Nginx
* MySQL
* PHP
* Redis

The intention is to setup a stack that can be used for local development, but that can also be deployed to a production machine.

Note that, unlike [craftcms-docker](https://github.com/wyveo/craftcms-docker/blob/craft2), we install Craft locally, and then share this local folder into the web container.

We use basic auth on the traefik and portainer services, to allow a semblance of security in a production setting.


## Versioning
| Docker Tag | Git Branch | Craft Release | Database | Caching |
|-----|-------|-----|--------|--------|
| latest | craft3 | 3.0.4 | PostgreSQL 10.3 | Redis 4.0.9 |
| craft2 | craft2 | 2.6.3015 | MariaDB 10.3.6 | Redis 4.0.9 |

Features:

 - Nginx 1.13.x, PHP-FPM 7.2.x / 7.1.x, Git 2.11.0
 - imageMagick image manipulation library


### Craft 3

#### Prerequisites

Ensure you have composer 1.3.0+

    # on a Mac, using HomeBrew:
    brew install composer
    composer -V

Ensure you have the `psql` client if you want to connect to the database from outside the container

    # https://stackoverflow.com/a/46703723/2238105
    brew install postgres

Clone the Craft 3 branch of this repo:

    git clone https://github.com/nzmebooks/docker-craftcms.git

### Craft 2

Clone the Craft 2 branch of this repo:

    git clone b craft2 --single-branch https://github.com/nzmebooks/docker-craftcms.git


## Setup

Copy `.env.example` to `.env`, and amend accordingly.

The value of `DOMAIN` specifies the domain that the services will be exposed as. Given the default of `local.host`, we'll endup with:

* https://traefik.local.host
* https://portainer.local.host
* https://local.host

If you're developing locally, you'll want to ensure DNS is set in `/etc/hosts` for the above routes pointing to 127.0.0.1:

```
# Adds:
# 127.0.0.1	traefik.local.host
# 127.0.0.1	portainer.local.host
# 127.0.0.1	local.host

DOMAIN=local.host
sudo -- sh -c -e "echo '127.0.0.1\ttraefik.$DOMAIN' >> /etc/hosts";
sudo -- sh -c -e "echo '127.0.0.1\tportainer.$DOMAIN' >> /etc/hosts";
sudo -- sh -c -e "echo '127.0.0.1\t$DOMAIN' >> /etc/hosts";
```

Alternatively, you could use [Dnsmasq](https://github.com/elalemanyo/docker-localhost#hosts-file---wildcard-dns-domain-on-mac-os-x).


## Certs

Traefik will use Lets Encrypt to generate certs for TLS (i.e. https) when it starts up.
However, for this to work, the server it's deployed to has have DNS pointing to it in order for Lets Encrypt acme challenge to work.

If this is a problem (say, because you're deploying to a server for which you haven't yet set up proper DNS but are relying on host file entries, or are developing on localhost and want to use a domain other than the default `local.host` domain), then you'll want to create a cert -- we suggest using the [wildcard](https://github.com/jcdarwin/wildcard) script, but that's up to you.

Once the cert and associated private key are generated, place them in `/etc/traefik/` and ensure the `command` for the traefik service in `docker-compose.yml` is configured to use the certs, e.g.:

        command: -c /dev/null --api --docker --logLevel=DEBUG --acme.email=$EMAIL --configFile=/etc/traefik/traefik.toml --entryPoints='Name:https Address::443 TLS:/etc/traefik/${DOMAIN}.crt,/etc/traefik/${DOMAIN}.key'

Presuming you're on a Mac, you'll also want to register the cert as trusted so the browser doesn't complain -- this can be done at the command line using a command such as the following:

    sudo security add-trusted-cert -d  -k /Library/Keychains/System.keychain ./etc/traefik/local.host.crt

Note: originally we did try to use `localhost` as our domain, however Chrome doesn't seem to like a domain with only a single step.

If you are relying on Traefik to use Lets Encrypt to generate certs, then the `command` for the traefik service in `docker-compose.yml` should not be configured to use the certs, e.g.:

        command: -c /dev/null --api --docker --logLevel=DEBUG --acme.email=$EMAIL --configFile=/etc/traefik/traefik.toml --entryPoints='Name:https Address::443 TLS'


## Usage

Download Craft and extract locally:

    ./download.sh

Use `docker-compose` to start, stop and destroy the stack:

    # starting
    docker-compose up -d --build

    # stopping
    docker-compose stop

    # destroy
    docker-compose down

    # view service bindings
    docker-compose ps

    # run a shell in the container
    # note that some images (redis, traefik) use alpine, therefore bin/ash
    docker exec -it local.host /bin/bash
    docker exec -it redis.local.host /bin/ash

    # view logs (also available via portainer)
    docker logs local.host
    docker exec -it local.host ls -la /var/log/

Once the stack is up, you should be able to visit the following in your browser:

* https://traefik.local.host
* https://portainer.local.host
* https://local.host/info.php

Navigate to https://<HOSTNAME>/admin to begin installing Craft.

    https://local.host/admin

You may find that sometimes it takes a minute or so for mariab to have created the database, and until then you'll get an error saying that it can't connect to the database -- just try again after a little while (should be no longer than 1 minute).

If you get an internal server error, and find something like the following in `./craft/storage/logs/web.log`

    [error][yii\base\ErrorException:8] yii\base\ErrorException: Undefined variable: currentUser in /usr/share/nginx/vendor/craftcms/cms/src/web/Request.php:871

Then a way to hack around this during install is to amend `./craft/vendor/craftcms/cms/src/web/Request.php` as follows:

    protected function csrfTokenValidForCurrentUser(string $token): bool
    {
        // if (Craft::$app->getIsInstalled()) {
        //     try {
        //         if (($currentUser = Craft::$app->getUser()->getIdentity()) === null) {
        //             return true;
        //         }
        //     } catch (DbException $e) {
        //         // Craft is probably not installed or updating
                Craft::$app->getUser()->switchIdentity(null);
                return true;
        //     }
        // }

Be sure to change this back once the install is complete

TODO: figure out why we need the above hack


## Rebuilding

As per the [Docker compose docs](https://docs.docker.com/compose/production/#deploying-changes):

When you make changes to your app code, remember to rebuild your image and recreate your app’s containers. To redeploy a service called `web`, use:

    docker-compose build web
    docker-compose up --no-deps -d web

This first rebuilds the image for `web` and then stop, destroy, and recreate just the web service. The `--no-deps` flag prevents Compose from also recreating any services which `web` depends on.


## Postgres

To connect to Postgres via, you'll need to do something like the following:

    # Directly
    psql -h 0.0.0.0 <DB_DATABASE> <DB_USER>

    # via an ephemeral docker container
    docker run -it --rm --network=docker-craftcms_default --link postgres.local.host postgres psql -h postgres -U <DB_USER> -d <DB_DATABASE>

NOTE:
Postgres creates the database on the first run of the container, so if you need to destroy it, you'll need to:

    rm -rf ./volumes/var/lib/postgresql/data/*


## Ansible

Notes about installing our docker-compose stack onto a Ubuntu server using ansible can be found in the [README in the ansible directory](./ansible/README.md).


## Further reading

* https://github.com/wyveo/craftcms-docker/blob/craft2/docker-compose.yml
* http://tech.osteel.me/posts/2017/01/15/how-to-use-docker-for-local-web-development-an-update.html
* https://deliciousbrains.com/https-locally-without-browser-privacy-errors/
* [Craft CMS docker-compose dev setup](https://gist.github.com/jackmcpickle/59efc98a99c067b08020)
* https://github.com/pnglabz/docker-compose-lamp
* https://github.com/elalemanyo/docker-localhost
* https://github.com/urbantrout/craftcms
