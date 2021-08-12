FROM php:8.0-cli
COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY . /usr/src/app

WORKDIR /usr/src/app
RUN apt update; \
    apt upgrade -y; \
    apt install git -y; \
    composer update;

ENV PORT 80
ENV ENV production

CMD [ "php", "entrypoint.php" ]
