FROM composer:latest

WORKDIR /var/www/test-rest

ENTRYPOINT ["composer", "--ignore-platform-reqs"]