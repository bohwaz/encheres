.PHONY: tests

all:

tests:
	php run_tests.php

dev-server:
	php -S localhost:8080 -t www