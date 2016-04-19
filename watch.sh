#!/usr/bin/env bash
set -o nounset
set -o errexit
set -o pipefail

sculpinBin="./vendor/bin/sculpin"

PORT=${1:-8000}

if [[ ! -x "$sculpinBin" ]]; then
    echo "File '$sculpinBin' is not executable or found."
    echo "Please run ./init.sh first."
    exit 1
fi

$sculpinBin generate --watch --server --port $PORT
