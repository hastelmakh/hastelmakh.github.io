PROJECT_DIR:=$(dir $(realpath $(lastword $(MAKEFILE_LIST))))

server:
	docker run -it --rm -p 80:80 -v "${PROJECT_DIR}:/usr/share/nginx/html" nginx
