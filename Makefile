.SILENT:
.PHONY: $(MAKECMDGOALS)

PROJECT_DIR:=$(dir $(realpath $(lastword $(MAKEFILE_LIST))))
DOCKER_RUN:=docker run --rm -it -v "$(PROJECT_DIR):/app" -w "/app"
IMAGE_NODE:=node:25-alpine
IMAGE_PHP:=hastelmakh_php

T_RESET:=\033[0m
T_FG_BOLD:=\033[1m
T_BG_INVERT:=\033[7m

setup:
	echo "$(T_BG_INVERT) # $(T_RESET) $(T_FG_BOLD)Docker$(T_RESET)"
	docker build . --file "docker/php/Dockerfile" --tag "$(IMAGE_PHP)" --build-arg "PHP_VERSION=8.5.0" --build-arg "COMPOSER_VERSION=2.9.2"
	echo ""
	echo "$(T_BG_INVERT) # $(T_RESET) $(T_FG_BOLD)Composer$(T_RESET)"
	$(DOCKER_RUN) $(IMAGE_PHP) composer install
	echo ""
	echo "$(T_BG_INVERT) # $(T_RESET) $(T_FG_BOLD)Yarn$(T_RESET)"
	$(DOCKER_RUN) $(IMAGE_NODE) yarn install

generate:
	echo "$(T_BG_INVERT) # $(T_RESET) $(T_FG_BOLD)Vite$(T_RESET)"
	$(MAKE) vite-build
	echo ""
	echo "$(T_BG_INVERT) # $(T_RESET) $(T_FG_BOLD)Stasis$(T_RESET)"
	$(MAKE) stasis-generate

docker-php:
	$(DOCKER_RUN) $(IMAGE_PHP) sh

docker-node:
	$(DOCKER_RUN) $(IMAGE_NODE) sh

stasis-generate:
	$(DOCKER_RUN) $(IMAGE_PHP) vendor/bin/stasis generate

stasis-generate-dev:
	$(DOCKER_RUN) -e APP_ENV=dev $(IMAGE_PHP) vendor/bin/stasis generate --symlink

stasis-server:
	- $(DOCKER_RUN) -p 8000:8000 $(IMAGE_PHP) vendor/bin/stasis server --host 0.0.0.0

vite-dev:
	$(DOCKER_RUN) -p 5173:5173 $(IMAGE_NODE) yarn vite dev

vite-build:
	$(DOCKER_RUN) $(IMAGE_NODE) yarn vite build
