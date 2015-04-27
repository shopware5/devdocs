#!/usr/bin/env bash
set -o nounset
set -o errexit
set -o pipefail

# Set magic variables for current FILE & DIR
declare -r __FILE__=$(readlink -f ${BASH_SOURCE[0]})
declare -r __DIR__=$(dirname $__FILE__)

outputdir="${__DIR__}/../output_prod/exampleplugins/"
tmpdir="${__DIR__}/../tmp/"

rm -rf "${outputdir}"
mkdir "${outputdir}"

for plugindir in $(find "${__DIR__}/../exampleplugins/" -mindepth 2 -maxdepth 2 -type d); do
    rm -rf "${tmpdir}"

    pluginname="$(basename "$plugindir")"
    parentdir="$(dirname "$plugindir")"
    namespace="$(basename "$parentdir")"

    echo "Plugin: $pluginname "

    mkdir -p "${tmpdir}/${namespace}/";
    cp -r "${plugindir}" "${tmpdir}/${namespace}/"

    cd "${tmpdir}"
    zip -rq "${outputdir}/${pluginname}.zip" .
done
