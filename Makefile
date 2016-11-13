.PHONY: cake composer deploy down install migrate test up

MAKEPATH := $(abspath $(lastword $(MAKEFILE_LIST)))
PWD := $(dir $(MAKEPATH))

CMD=""

cake:
	docker run -it --rm \
		-v $(PWD):/opt \
		-w /opt \
		--network=bandit_network \
		cjquinn/cakephp-fpm:latest \
		bin/cake $(CMD)

composer:
	docker run -it --rm \
		-v $(PWD):/opt \
		-w /opt \
		--network=bandit_network \
		cjquinn/cakephp-composer:latest \
		composer $(CMD)

deploy:
	make install
	make migrate

down:
	docker-compose down

install:
	make composer CMD=install

migrate:
	make cake CMD="migrations migrate"

test:
	docker run -it --rm \
		-v $(PWD):/opt \
		-w /opt \
		--network=bandit_network \
		cjquinn/cakephp-fpm:latest \
		vendor/bin/phpunit

up:
	docker-compose up -d
