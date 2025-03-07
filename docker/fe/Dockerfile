FROM node:latest

WORKDIR /var/www/soundvibe/fe

COPY package*.json ./

RUN npm install

RUN npm cache clean -f
RUN npm cache verify
RUN npm install -g react-scripts

COPY docker_entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker_entrypoint.sh
ENTRYPOINT ["docker_entrypoint.sh"]