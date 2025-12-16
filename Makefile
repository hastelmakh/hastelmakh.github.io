.SILENT:
.PHONY: $(MAKECMDGOALS)

PROJECT_DIR:=$(dir $(realpath $(lastword $(MAKEFILE_LIST))))
DOCKER_RUN:=docker run --rm -it -v "$(PROJECT_DIR):/app" -w "/app"
IMAGE_NODE:=node:25-alpine

T_RESET:=\033[0m
T_FG_BOLD:=\033[1m
T_BG_INVERT:=\033[7m

setup:
	echo "$(T_BG_INVERT) # $(T_RESET) $(T_FG_BOLD)Composer$(T_RESET)"
	composer install
	echo ""
	echo "$(T_BG_INVERT) # $(T_RESET) $(T_FG_BOLD)Yarn$(T_RESET)"
	$(DOCKER_RUN) $(IMAGE_NODE) yarn install

generate:
	echo "$(T_BG_INVERT) # $(T_RESET) $(T_FG_BOLD)Vite$(T_RESET)"
	$(MAKE) vite-build
	echo ""
	echo "$(T_BG_INVERT) # $(T_RESET) $(T_FG_BOLD)Stasis$(T_RESET)"
	$(MAKE) stasis-generate

stasis-server:
	- vendor/bin/stasis server

stasis-generate:
	vendor/bin/stasis generate

stasis-generate-symlink:
	vendor/bin/stasis generate --symlink

vite-dev:
	$(DOCKER_RUN) -p 5173:5173 $(IMAGE_NODE) yarn vite dev

vite-build:
	$(DOCKER_RUN) $(IMAGE_NODE) yarn vite build
