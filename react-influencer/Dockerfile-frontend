FROM node:lts-alpine

WORKDIR /usr/src/app

COPY entrypoint.sh /entrypoint
RUN chmod +x /entrypoint

ENTRYPOINT ["/entrypoint"]