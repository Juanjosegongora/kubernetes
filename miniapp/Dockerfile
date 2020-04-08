FROM ubuntu

LABEL title="miniapp" \
  author="Juan Jose gongora"

ENV DEBIAN_FRONTEND=noninteractive 
ENV TZ=Europe/Madrid

RUN apt-get update \
    && apt-get install -y apache2 \
    && apt-get install -y php \
    && apt-get install -y libapache2-mod-php \
    && apt-get install -y git

ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data
ENV APACHE_LOG_DIR /var/log/apache2

RUN git clone https://github.com/juanjosegongora/kubernetes

RUN mv kubernetes/miniapp/src/* /var/www/html

RUN rm /var/www/html/index.html

EXPOSE 80

ENTRYPOINT ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]