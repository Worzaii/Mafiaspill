FROM phpdockerio/php:8.4-fpm
WORKDIR "/application"

RUN apt-get update \
    && apt-get -y --no-install-recommends install \
        php8.4-mysql

RUN apt-get update \
    && apt-get install -y msmtp

RUN ln -s /usr/bin/msmtp /usr/sbin/sendmail

COPY msmtprc /etc/msmtprc

# Clean up
RUN apt-get clean \
        && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*
