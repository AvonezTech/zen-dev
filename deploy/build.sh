#!/bin/bash
set -e

DIR="$(cd "$(dirname "$0")" && pwd)"

bash $DIR/php-build.sh
bash $DIR/npm-build.sh
bash $DIR/cache-build.sh