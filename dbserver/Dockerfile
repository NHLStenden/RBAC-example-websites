FROM mariadb:10.10-jammy

RUN ln -sf /usr/share/zoneinfo/Europe/Amsterdam /etc/localtime

RUN mkdir /app

# https://hub.docker.com/_/mariadb see "Initializing the database contents"
COPY --chown=root:root  CreateDatabase-and-seed.sql /docker-entrypoint-initdb.d
