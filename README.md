# Olybet personal code test/generate api

To set up this api do the following:

#### Clone this repo

```bash
git clone https://github.com/tomdgt/olybet-lumen.git
cd olybet-lumen
```

#### Build and run docker images

```bash
docker-compose up --build -d
```

#### Install composer dependencies

```bash
cd images/php/app
docker run --rm -it -v $(pwd):/app saada/lumen-cli install
```

#### Run tests

```bash
./run-tests.sh
```

#### Call the endpoints

[Generate](http://localhost:8088/api/generate/1990-04-15/0)

[Validate](http://localhost:8088/api/validate/48904240128)

#### Stop Everything

```bash
docker-compose down
```
