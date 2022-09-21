# Installing the Docker images and Initializing the Drupal Next.js system

After cloning the repository

1. Change directory into the cloned repo: `cd drupal9gcc`

2. Copy the sample environment file:

`cp env.sample .env`

You may edit this file later.

3. Build the docker image with Drupal and Next.js

`docker build -t drupalnextjs:latest -f drupalnextjs.dockerfile .`

This will construct a Docker image on your machine using the latest version
of Drupal and a version of Next.js.  The image is called drupalnextjs.

4. Start the docker envrionment for the development website

`docker-compose up`

This command fetches Docker images from the Internet (if needed),
caches local copies on your machine, and then runs them inside
containers.  It will take significantly more time the first time it is
run.  The database is run in a separate container from the web server
and stores its content in locally cached volume.  The first time you
run `docker-compose`, the database will be initialized with an empty
Drupal 9 structure.  The database will have a `root` and `gccsqluser`
account created.  The `gccsqluser` password is defined in the `.env`
file.  The `root` account gets a random password. In the stream of
messages that docker prints out, it will show you the root password.
Look for a sequence of lines like these:

```
curlingseattle9-db         | 2022-09-18 21:51:35+00:00 [Note] [Entrypoint]: GENERATED ROOT PASSWORD: /8ICe0JWKczLahqbUSD9vpUqUo/FudK8
curlingseattle9-db         | 2022-09-18 21:51:35+00:00 [Note] [Entrypoint]: Creating database drupal9gcc
curlingseattle9-db         | 2022-09-18 21:51:35+00:00 [Note] [Entrypoint]: Creating user gccsqluser
curlingseattle9-db         | 2022-09-18 21:51:35+00:00 [Note] [Entrypoint]: Giving user gccsqluser access to schema drupal9gcc
```

It's a good idea to copy the generated root password into your `.env` file for
later use.  The `.env` is ignored by git and will not be stored in the
public repository.

4. Browse http://localhost:8999

You should see the initial adminstrative screen for a Drupal 9 instance.


