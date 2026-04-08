#!/bin/bash
set -e

export GOMAXPROCS=1
export NODE_OPTIONS="--max-old-space-size=512"
export UV_THREADPOOL_SIZE=1

rm -rf node_modules
npm install

npm run build
