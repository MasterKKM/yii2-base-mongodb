FROM ubuntu:18.04

RUN apt-get update && apt-get update
RUN apt-get install -y curl

# Устанавливаем русскую локаль.
RUN echo "LANG=ru_RU.UTF-8" > /etc/default/locale
RUN echo "Europe/Moscow" > /etc/timezone
RUN apt-get install -y language-pack-ru

# Ставим апачь.
RUN apt-get -y install apache2 
RUN echo "AddDefaultCharset UTF-8" >> /etc/apache2/conf-available/charset.conf
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
RUN a2enmod rewrite
ADD 000-default.conf /etc/apache2/sites-enabled/

ARG DEBIAN_FRONTEND=noninteractive

# Установка php7.2
RUN echo 8 | apt-get update && apt-get -y install php php-mbstring php-xml php-mysql php-intl && service apache2 restart
RUN echo 8 | apt-get -y install php-cli php-common php-mysql php-curl php-gd php-cgi php-phpdbg php-fpm libphp-embed php-enchant php-gmp php-imap php-interbase php-intl php-ldap php-readline php-odbc php-pgsql php-pspell php-recode php-snmp  php-tidy php-xmlrpc php-json php-sybase php-sqlite3 php-bz2 imagemagick php-imagick php-memcached php-mongodb

# Включаем показ ошибок.
RUN sed -i 's/error_reporting.*/error_reporting = E_ALL/' /etc/php/7.2/apache2/php.ini
RUN sed -i 's/display_errors.*/display_errors = On/' /etc/php/7.2/apache2/php.ini

# Установка композера.
RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer

# Симлинк на сайт.
RUN rm -R /var/www/*
RUN ln -s /app/web /var/www/html

# Ставим mongodb.
RUN apt-get install -y dirmngr
RUN apt-key adv --keyserver hkp://keyserver.ubuntu.com:80 --recv 58712A2291FA4AD5
RUN echo "deb http://repo.mongodb.org/apt/ubuntu xenial/mongodb-org/3.6 multiverse" | tee /etc/apt/sources.list.d/mongodb-org-3.6.list
RUN apt-get update
RUN apt-get install -y mongodb-org

# -= Устанавливаем nodeJS. =-
#RUN apt-get install curl
# Подключаем репозиторий.
#RUN curl https://deb.Nodesource.com/setup_8.x | bash
#RUN apt-get -y install nodejs
# Установка менеджера npm
#RUN apt-get install -y npm

# Ставим X-Debug.
RUN apt-get install -y php-pear php7.2-dev
RUN pecl install xdebug
# && docker-php-ext-enable xdebug
RUN echo [xdebug] >> /etc/php/7.2/apache2/php.ini \
    && echo zend_extension="/usr/lib/php/20170718/xdebug.so" >> /etc/php/7.2/apache2/php.ini \
    && echo xdebug.remote_enable=1 >> /etc/php/7.2/apache2/php.ini \
    && echo xdebug.remote_port=9000 >> /etc/php/7.2/apache2/php.ini \
    && echo xdebug.remote_host=192.168.100.2 >> /etc/php/7.2/apache2/php.ini \
    && echo xdebug.idekey=PHP_STORM >> /etc/php/7.2/apache2/php.ini \
    && echo xdebug.remote_autostart=1 >> /etc/php/7.2/apache2/php.ini \
    && echo xdebug.remote_connect_back=0 >> /etc/php/7.2/apache2/php.ini
RUN service apache2 restart

# На всякий случай ставим mc
RUN apt-get install -y mc
RUN export DEBUG="true"

EXPOSE 80
EXPOSE 27017

CMD service apache2 restart && mongod --auth --port 27017 --dbpath /var/mongo/base --bind_ip 0.0.0.0 && tail -f /dev/null

