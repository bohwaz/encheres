.PHONY: tests all dev-server deps install

all:

tests:
	php run_tests.php

dev-server:
	php -S localhost:8080 -t www

deps:
	wget https://fossil.kd2.org/kd2fw/uv/KD2-5.6.zip
	unzip "KD2-5.6.zip" -d vendor
	rm -f KD2-5.6.zip

install:
	php install.php