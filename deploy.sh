#!/usr/bin/env bash
set -o nounset
set -o errexit
set -o pipefail

chmod 600 deploy_key
echo -e "Host developers.shopware.com\n\tStrictHostKeyChecking no\n" >> ~/.ssh/config
eval `ssh-agent -s`
ssh-add deploy_key
rsync -avze ssh --rsync-path="sudo rsync" --exclude=".git" output_prod/ shopware@developers.shopware.com:/var/www/developers.shopware.com
