FROM php:7.4-cli
COPY . /usr/src/server
WORKDIR /usr/src/server/web
CMD [ "php", "-S localhost:8000" ]