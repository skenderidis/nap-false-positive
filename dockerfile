FROM php:7.4-apache
RUN apt-get update && apt-get install -y apt-transport-https lsb-release ca-certificates nano wget gnupg2 debian-archive-keyring

# Install NGINX NAP compiler.
RUN mkdir -p /etc/ssl/nginx
COPY nginx-repo.crt /etc/ssl/nginx/nginx-repo.crt
COPY nginx-repo.key /etc/ssl/nginx/nginx-repo.key


RUN chmod 644 /etc/ssl/nginx/*
RUN wget -qO - https://cs.nginx.com/static/keys/nginx_signing.key | gpg --dearmor | tee /usr/share/keyrings/nginx-archive-keyring.gpg >/dev/null
RUN wget -qO - https://cs.nginx.com/static/keys/app-protect-security-updates.key | gpg --dearmor | tee /usr/share/keyrings/app-protect-security-updates.gpg >/dev/null

RUN printf "deb [signed-by=/usr/share/keyrings/nginx-archive-keyring.gpg] https://pkgs.nginx.com/plus/debian `lsb_release -cs` nginx-plus\n" | tee /etc/apt/sources.list.d/nginx-plus.list
RUN printf "deb [signed-by=/usr/share/keyrings/nginx-archive-keyring.gpg] https://pkgs.nginx.com/app-protect/debian `lsb_release -cs` nginx-plus\n" | tee /etc/apt/sources.list.d/nginx-app-protect.list
RUN printf "deb [signed-by=/usr/share/keyrings/app-protect-security-updates.gpg] https://pkgs.nginx.com/app-protect-security-updates/debian `lsb_release -cs` nginx-plus\n" | tee -a /etc/apt/sources.list.d/nginx-app-protect.list

RUN wget -P /etc/apt/apt.conf.d https://cs.nginx.com/static/files/90pkgs-nginx
RUN apt-get update
RUN apt-get install -y app-protect-compiler

RUN chmod 777 -R /opt/app_protect/
RUN chmod 777 -R /etc/app_protect/


# Install python 3.9
RUN apt install software-properties-common -y
RUN add-apt-repository ppa:deadsnakes/ppa
RUN apt install python3.9 -y

# Install pip
RUN apt install python3-pip -y
RUN python3 -m pip install pyyaml

RUN mkdir /etc/fpm/
RUN chgrp -R www-data /etc/fpm
RUN chown -R www-data /etc/fpm
RUN chown -R www-data /etc/fpm

RUN rm /etc/ssl/nginx/nginx-repo.crt
RUN rm /etc/ssl/nginx/nginx-repo.key

RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/conf.d/php.ini

WORKDIR /var/www/html

COPY html/ .
RUN chmod 777 -R /var/www/html/config_files/
EXPOSE 80


