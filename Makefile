.PHONY: cake composer down install migrate seed test up update

PROJECT=banditapi

CMD=""

bash:
	docker run -it --rm \
		-v $(PWD):/opt \
		-w /opt \
		--network=$(PROJECT)_network \
		wearelighthouse/php-fpm:latest \
		bash

cake:
	docker run -it --rm \
		-v $(PWD):/opt \
		-w /opt \
		--network=$(PROJECT)_network \
		wearelighthouse/php-fpm:latest \
		bin/cake $(CMD)

composer:
	docker run -it --rm \
		-v $(PWD):/opt \
		-w /opt \
		--network=$(PROJECT)_network \
		wearelighthouse/php-fpm:latest \
		composer $(CMD)

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
		wearelighthouse/php-fpm:latest \
		vendor/bin/phpunit

test-group:
	docker run -it --rm \
		-v $(PWD):/opt \
		-w /opt \
		--network=$(PROJECT)_network \
		wearelighthouse/php-fpm:latest \
		vendor/bin/phpunit --group testing

up:
	docker-compose up -d

update:
	make install
	make migrate
	make cake CMD="orm_cache clear"
	make seed
	npm install
	npm run build
