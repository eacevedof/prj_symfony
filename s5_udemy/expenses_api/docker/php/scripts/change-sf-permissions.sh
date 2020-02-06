#!/bin/sh

APP_FOLDER="/appdata/www"
CACHE_FOLDER="${APP_FOLDER}/var/cache"
LOGS_FOLDER="${APP_FOLDER}/var/log"

rm -rf ${CACHE_FOLDER:?}/* ${LOGS_FOLDER:?}/*

chown -R appuser:appuser ${APP_FOLDER}
