FROM php:7.4-cli

RUN for i in 1 2 3 4 5; do \
      apt-get update && \
      apt-get install -y --no-install-recommends curl ca-certificates unzip && break || \
      (echo "apt failed, retry $i" && sleep 10); \
    done && rm -rf /var/lib/apt/lists/*

COPY entrypoint-ci2.sh /usr/local/bin/entrypoint-ci2.sh
RUN chmod +x /usr/local/bin/entrypoint-ci2.sh

WORKDIR /var/www/html
