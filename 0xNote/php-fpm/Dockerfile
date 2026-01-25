FROM php:8.3.2-fpm

WORKDIR /var/www/html

RUN apt update && apt install gcc

COPY ./src .
COPY flag.txt /flag.txt
COPY readflag.c /tmp/readflag.c
COPY ./php.ini /usr/local/etc/php/php.ini-development

RUN chmod 700 /flag.txt
RUN gcc /tmp/readflag.c -o /readflag
RUN rm /tmp/readflag.c
RUN chmod u+s /readflag


RUN chown -R root:root /var/www/html \
    && chmod -R 755 /var/www/html
    
USER nobody

EXPOSE 9000

CMD ["php-fpm"]