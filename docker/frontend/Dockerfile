# Build
FROM node:20-alpine AS build

WORKDIR /app

COPY ../../frontend/package*.json ./
RUN npm ci

COPY ../../frontend/ .

ARG VITE_API_BASE_URL
ENV VITE_API_BASE_URL=$VITE_API_BASE_URL

RUN npm run build

# Servir web compilada
FROM httpd:2.4-alpine

RUN sed -i 's/#LoadModule rewrite_module/LoadModule rewrite_module/' /usr/local/apache2/conf/httpd.conf

COPY --from=build /app/dist /usr/local/apache2/htdocs/
COPY docker/frontend/custom-vhost.conf /usr/local/apache2/conf.d/custom-vhost.conf

EXPOSE 80
