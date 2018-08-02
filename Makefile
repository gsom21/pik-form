up :
	@ docker-compose -f docker-compose.yml up -d \
#	&& sensible-browser $$(docker inspect -f '{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' cont_tomcat):8080

start :
	@ docker-compose -f docker-compose.yml start \
#    && sensible-browser $$(docker inspect -f '{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' cont_tomcat):8080

stop :
	@ docker-compose -f docker-compose.yml stop

restart :
	@ docker-compose -f docker-compose.yml stop && docker-compose -f docker-compose.yml start

down :
	@ docker-compose -f docker-compose.yml down

startup : up
	@ cd app/ && cp .env.dist .env \
    && composer install \
    && bin/console doctrine:migrations:migrate \
    && bin/console server:run -n\
    && sensible-browser http://localhost:8000 \
