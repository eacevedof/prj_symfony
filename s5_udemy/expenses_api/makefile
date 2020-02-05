#!/bin/bash

OS := $(shell uname)
DOCKER_BE = expenses_api

ifeq ($(OS),Darwin)
  UID  = $(shell id -u)
else ifeq ($(OS),Linux)
  UID = $(shell id -u)
else
  UID = 1000
endif

help: ## Show this help message
  @echo "usage: make [target]"
  @echo
  @echo "targets:"
  @egrep "^(.+)\:\ ##\ (.+)" ${MAKEFILE_LIST} | column -t -c 2 -s ":#"

run: ## Start the containers
ifeq ($(OS),Darwin)
  docker volume create --name=sf5-expenses-api-be-sync
  U_ID=${UID} docker-compose -f docker-compose.macos.yml up -d
  U_ID=${UID} docker-sync start
else
  U_ID = $(UID} docker-compose -f docker-compose.linux.yml up -d
endif

stop: