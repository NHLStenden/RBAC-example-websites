FROM python:3.11
WORKDIR /app
COPY requirements.txt .
COPY src/* /app

RUN pip install -r requirements.txt \
    && apt update && apt install -y cron tzdata locales \
    && ln -fs /usr/share/zoneinfo/Europe/Amsterdam /etc/localtime \
    && dpkg-reconfigure -f noninteractive tzdata \
    && echo "nl_NL.UTF-8 UTF-8" >> /etc/locale.gen \
    && locale-gen \
    && update-locale LANG=nl_NL.UTF-8 LC_ALL=nl_NL.UTF-8 \
    && chmod a+x /app/*.sh \
    && mkdir /app/logs

ENV LANG=nl_NL.UTF-8
ENV LC_ALL=nl_NL.UTF-8

COPY crontab /etc/cron.d/run-sync

RUN chmod 0644 /etc/cron.d/run-sync \
    && crontab /etc/cron.d/run-sync \
    && service cron start \
    && touch /var/log/cron.log

CMD cron && tail -f /var/log/cron.log

