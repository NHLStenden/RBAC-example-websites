FROM mariadb:10.10-jammy

RUN apt update && apt install -y tzdata locales \
    && ln -fs /usr/share/zoneinfo/Europe/Amsterdam /etc/localtime \
    && dpkg-reconfigure -f noninteractive tzdata \
    && echo "nl_NL.UTF-8 UTF-8" >> /etc/locale.gen \
    && locale-gen \
    && update-locale LANG=nl_NL.UTF-8 LC_ALL=nl_NL.UTF-8

RUN ln -sf /usr/share/zoneinfo/Europe/Amsterdam /etc/localtime

RUN mkdir /app

# https://hub.docker.com/_/mariadb see "Initializing the database contents"
COPY --chown=root:root  CreateDatabase-and-seed.sql /docker-entrypoint-initdb.d
