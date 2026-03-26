#!/bin/bash
vendor/bin/phpunit \
  -c ./test/phpunit.xml \
  --testdox \
  --colors=always \
  "$@"