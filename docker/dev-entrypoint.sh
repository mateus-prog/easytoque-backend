#!/bin/sh
# set -e

test ! -d vendor && composer install;
#test ! -d build && npm run build;

exec "$@"