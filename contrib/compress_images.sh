#!/usr/bin/env bash

# Set magic variables for current FILE & DIR
declare -r __FILE__=$(readlink -f ${BASH_SOURCE[0]})
declare -r __DIR__=$(dirname $__FILE__)

find ${__DIR__}/../source -iname "*.jpg" -print0 | xargs -0 -P4 -I filename /opt/mozjpeg/bin/jpegtran -copy none -optimise -outfile filename filename
find ${__DIR__}/../source -iname "*.jpeg" -print0 | xargs -0 -P4 -I filename /opt/mozjpeg/bin/jpegtran -copy none -optimise -outfile filename filename
find ${__DIR__}/../source -name '*.png' -print0 | xargs -P4 -0 -n1 pngout
