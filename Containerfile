# Stage 1: export the committed tree. git archive honours the
# export-ignore rules in .gitattributes, so tests, docs and dev
# config stay out of the image.
FROM alpine/git:latest AS source

ARG GIT_REF=HEAD

WORKDIR /repo
COPY .git ./.git
RUN mkdir /app \
 && git archive --format=tar "${GIT_REF}" | tar -x -C /app

# Stage 2: install production dependencies on the exported tree.
# Uses the same PHP image as the runtime so platform checks match.
FROM php:8.5-alpine AS vendor

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN apk add --no-cache unzip

WORKDIR /app
COPY --from=source /app /app
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --prefer-dist \
    --optimize-autoloader

# Stage 3: the image to deploy.
FROM php:8.5-fpm-alpine

LABEL authors="heiglandreas"

WORKDIR /app
COPY --from=vendor /app /app
