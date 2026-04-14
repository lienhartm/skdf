#!/bin/bash

docker rm -f $(docker ps -a -q)

docker rmi -f $(docker images -q)

docker volume rm $(docker volume ls -q)

docker compose down -v

docker network prune -f

docker system prune -a --volumes -f

docker system df

