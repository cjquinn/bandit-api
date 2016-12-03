.PHONY: cake composer deploy down install migrate seed test up

MAKEPATH := $(abspath $(lastword $(MAKEFILE_LIST)))
PWD := $(dir $(MAKEPATH))
PROJECT := $(shell basename $(CURDIR))

CMD=""

bash:
	docker run -it --rm \
		-v $(PWD):/opt \
		-w /opt \
		--network=$(PROJECT)_network \
		cjquinn/cakephp-fpm:latest \
		bash

cake:
	docker run -it --rm \
		-v $(PWD):/opt \
		-w /opt \
		--network=$(PROJECT)_network \
		cjquinn/cakephp-fpm:latest \
		bin/cake $(CMD)

composer:
	docker run -it --rm \
		-v $(PWD):/opt \
		-w /opt \
		--network=$(PROJECT)_network \
		cjquinn/cakephp-fpm:latest \
		composer $(CMD)

deploy:
	make install
	make migrate
	make cake CMD="orm_cache clear"
	make seed
	npm run build

down:
	docker-compose down

install:
	make composer CMD=install

migrate:
	make cake CMD="migrations migrate"

seed:
	make cake CMD="migrations seed"

test:
	docker run -it --rm \
		-v $(PWD):/opt \
		-w /opt \
		--network=$(PROJECT)_network \
		cjquinn/cakephp-fpm:latest \
		vendor/bin/phpunit

test-group:
	docker run -it --rm \
		-v $(PWD):/opt \
		-w /opt \
		--network=$(PROJECT)_network \
		cjquinn/cakephp-fpm:latest \
		vendor/bin/phpunit --group testing

up:
	docker-compose up -d
