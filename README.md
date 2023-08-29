# Sudoku Plus
This game allow you to retrieve a Sudoku grid, complete it using any CSV reader and submit it to the server to check if it's correct.

## Getting Started 
1. If not already done, [install Docker Compose](https://docs.docker.com/compose/install/) (v2.10+)
2. Run `docker compose build --no-cache` to build fresh images
3. Run `docker compose up --pull --wait` to start the project
4. Open `https://localhost` in your favorite web browser and [accept the auto-generated TLS certificate](https://stackoverflow.com/a/15076602/1352334)
5. Run `docker compose down --remove-orphans` to stop the Docker containers.

## API Documentation

### Create a new Sudoku grid

`POST /create`

    curl -X POST --location "https://localhost/create" -H "Content-Type: application/json" \-d "{\"gridSize\": 9}"

#### Ok Response

    HTTP/1.1 200 OK
    Alt-Svc: h3=":443"; ma=2592000
    Cache-Control: no-cache, private
    Content-Disposition: attachment; filename="sudoku-plus.csv"
    Content-Type: text/csv; charset=UTF-8
    Date: Tue, 29 Aug 2023 16:29:54 GMT
    Server: Caddy
    X-Robots-Tag: noindex
    Content-Length: 162

    0,0,5,0,1,0,0,8,0
    2,0,6,0,0,0,0,0,9
    1,0,0,3,0,9,0,0,0
    0,0,9,8,0,0,0,4,0
    0,4,1,0,6,0,0,0,0
    7,0,0,0,0,4,8,0,0
    0,3,2,0,8,0,0,0,0
    0,6,0,7,0,0,4,0,0
    0,1,0,0,0,6,0,0,8

#### Wrong Response

    HTTP/1.1 400 Bad Request
    Alt-Svc: h3=":443"; ma=2592000
    Cache-Control: no-cache, private
    Content-Type: text/html; charset=UTF-8
    Date: Tue, 29 Aug 2023 16:32:18 GMT
    Server: Caddy
    Status: 400 Bad Request
    X-Robots-Tag: noindex
    Content-Length: 43

    {"message":"Invalid JSON request","code":0}

#### Wrong Response Invalid data

    HTTP/1.1 400 Bad Request
    Alt-Svc: h3=":443"; ma=2592000
    Cache-Control: no-cache, private
    Content-Type: text/html; charset=UTF-8
    Date: Tue, 29 Aug 2023 16:41:21 GMT
    Server: Caddy
    Status: 400 Bad Request
    X-Robots-Tag: noindex
    Content-Length: 134

    {"message":"Object(App\\Controller\\Request\\CreateSudokuPlusRequest):\n    Number provider is not valid for a grid size!\n","code":0}


### Submit a solution to be validated by the server

`POST /submit`

    curl -X POST --location "https://localhost/submit" -H "Content-Type: multipart/form-data; boundary=boundary" -F "csv_files=@full_path_to_csv_file_including_extension;type=*/*

#### Ok Response

    HTTP/1.1 200 OK
    Alt-Svc: h3=":443"; ma=2592000
    Cache-Control: no-cache, private
    Content-Disposition: attachment; filename="sudoku-plus.csv"
    Content-Type: text/csv; charset=UTF-8
    Date: Tue, 29 Aug 2023 16:29:54 GMT
    Server: Caddy
    X-Robots-Tag: noindex
    Content-Length: 162

    { "message": "Congratulations, you completed the game!" }

#### Wrong Response

    HTTP/1.1 400 Bad Request
    Alt-Svc: h3=":443"; ma=2592000
    Cache-Control: no-cache, private
    Content-Type: text/html; charset=UTF-8
    Date: Tue, 29 Aug 2023 16:38:06 GMT
    Server: Caddy
    Status: 400 Bad Request
    X-Robots-Tag: noindex
    Content-Length: 39

    {"message":"No file provided","code":0}


## How To Play
1. Generate a new game sending a request like, [Require Curl installed](https://everything.curl.dev/get).
The parameter gridSize is optional and will default to 9 if not provided. It represents the size of the grid to be generated.

```curl -X POST --location "http://localhost/create" -H "Content-Type: application/json" \-d "{\"gridSize\": 9}"```

Which will return a CSV response to play in any file text editor or excel. For playing you need to 0 by the correct number:
<p>
1,2,4,0,6,0,8,0,3<br />
6,0,8,5,2,0,4,0,7<br />
0,5,0,3,8,0,1,6,2<br />
7,0,5,6,0,2,9,1,0<br />
0,6,0,8,0,5,7,2,4<br />
0,8,1,9,0,7,6,3,0<br />
0,1,2,4,7,6,0,8,0<br />
4,0,3,0,5,8,0,7,6<br />
8,7,0,0,9,3,5,0,1<br />
</p>

2. Once completed you can upload it to the server using a request like:

```curl -X POST --location "https://localhost/submit" -H "Content-Type: multipart/form-data; boundary=boundary" -F "csv_files=@full_path_to_csv_file_including_extension;type=*/*"```

Which fill return if your answer is correct or not.

## How it works
Once every request is sent, there is a layer of dto-conversion then validation before reaching any controller. Any validation logic will be place
at the DTO level using Symfony Validation. Once the request is validated, the controller will call the service layer to perform the business logic.
in this case a service that creates or validates a Sudoku grid.

## Tests
For running the whole suite execute the following command:

```docker compose exec php vendor/phpunit/phpunit/phpunit --configuration /srv/app/phpunit.xml /srv/app/tests -```




