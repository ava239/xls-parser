FROM php:7.4-cli

RUN apt-get update && apt-get install -y \
      libzip-dev \
      sqlite3 \
      git \
      cron \
      libpng-dev \
      libjpeg-dev \
      && docker-php-ext-install zip pdo pdo_mysql bcmath exif sockets gd

RUN curl -sL https://deb.nodesource.com/setup_14.x | bash -
RUN apt-get install -y nodejs

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN curl https://cli-assets.heroku.com/install.sh | sh

ADD scheduler/crontab /etc/cron.d/cron

RUN chmod 0644 /etc/cron.d/cron

RUN touch /var/log/cron.log

WORKDIR /app

CMD make compose
