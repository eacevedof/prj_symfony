FROM nginx:latest

ARG UID
EXPOSE $UID

RUN adduser -u ${UID} --disabled-password --gecos "" appuser

# se copia la configuración de nginx
COPY default.conf /etc/nginx/conf.d/