#!/usr/bin/env bash
set -o nounset
set -o errexit
set -o pipefail

composerBin="./composer.phar"

if [[ ! -x "$composerBin" ]]; then
    echo "Downloading composer..."
    curl -s https://getcomposer.org/installer | php
    chmod +x $composerBin
else
    $composerBin self-update
fi

echo "Installing Sculpin dependencies"
$composerBin install --no-dev
