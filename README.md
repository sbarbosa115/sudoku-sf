# Sudoku Plus
This game allow you to retrieve a Sudoku grid, complete it using any CSV reader and submit it to the server to check if it's correct.

## Getting Started 
1. If not already done, [install Docker Compose](https://docs.docker.com/compose/install/) (v2.10+)
2. Run `docker compose build --no-cache` to build fresh images
3. Run `docker compose up --pull --wait` to start the project
4. Open `https://localhost` in your favorite web browser and [accept the auto-generated TLS certificate](https://stackoverflow.com/a/15076602/1352334)
5. Run `docker compose down --remove-orphans` to stop the Docker containers.

## How To Play
1. Generate a new game sending a request like:

```curl -X POST --location "http://localhost/create" -H "Content-Type: application/json" \-d "{\"gridSize\": 9}"```

Which will return a CSV response like, replace 0 by the correct number:
<p>
0,1,2,3,4,5,6,7,8<br/>
7,5,0,9,6,4,2,0,0<br/> 
8,3,6,0,0,7,4,9,0<br/>
0,2,0,5,3,0,1,7,6<br/>
5,1,4,0,8,0,9,2,0<br/>
9,7,0,4,0,0,6,8,1<br/>
2,0,0,3,9,0,7,5,4<br/>
0,4,0,8,0,3,5,1,9<br/>
0,8,0,6,7,9,3,4,0<br/>
3,9,2,0,4,5,8,0,0<br/>
</p>

2. Once complete you can upload it to the server using a request like:

```curl -X POST --location "http://localhost/submit" -H "Content-Type: text/csv" --data-binary "@<path_to_csv_file>"```

## Features
* Production, development and CI ready
* [Installation of extra Docker Compose services](docs/extra-services.md) with Symfony Flex
* Automatic HTTPS (in dev and in prod!)
* HTTP/2, HTTP/3 and [Preload](https://symfony.com/doc/current/web_link.html) support
* Built-in [Mercure](https://symfony.com/doc/current/mercure.html) hub
* [Vulcain](https://vulcain.rocks) support
* Native [XDebug](docs/xdebug.md) integration
* Just 2 services (PHP FPM and Caddy server)
* Super-readable configuration

## Tests
For running the whole suite execute the following command:

```docker compose exec php vendor/phpunit/phpunit/phpunit --configuration /srv/app/phpunit.xml /srv/app/tests -```




