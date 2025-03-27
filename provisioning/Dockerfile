FROM python:3.11
WORKDIR /app
COPY requirements.txt .
COPY src/* /app

RUN pip install -r requirements.txt \
    && apt update && apt install -y cron \
    && chmod a+x /app/*.sh \
    && mkdir /app/logs \
    service cron start

COPY crontab /etc/cron.d/run-sync

RUN chmod 0644 /etc/cron.d/run-sync \
    && crontab /etc/cron.d/run-sync


CMD ["tail", "-f", "/dev/null"]
