PROJECT_NAME="soundvibe"

containers=$(docker ps -a --filter "name=${PROJECT_NAME}" --format "{{.ID}}")

docker stop $containers
docker rm $containers

docker-compose -f docker-compose.yml up -d --build

read