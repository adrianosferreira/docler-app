build:
	@echo "==================================================="
	@echo "== Building the production version of the app =="
	@echo "==================================================="
	mkdir build
	cp -r src build/
	cp -r composer.json build/
	composer install --no-dev --optimize-autoloader -d build/
	rm -rf build/composer.json

dev:
	@echo "============================================="
	@echo "== Installing all the project dependencies =="
	@echo "============================================="
	composer install
	docker-compose up

test:
	@echo "========================"
	@echo "== Running unit tests =="
	@echo "========================"
	vendor/bin/phpunit
	@echo "=============================="
	@echo "== Running code style check =="
	@echo "=============================="
	vendor/bin/phpcs
	@echo "============================"
	@echo "== Running static checker =="
	@echo "============================"
	vendor/bin/phpstan analyze -c phpstan.neon