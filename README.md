# drupal9gcc
Basic Drupal 9 website with docker for GCC

# Introduction
This repo contains the files needed to run a pre-configured Drupal 9 website.
It is intended to help prototype a new website for the Granite Curling Club of Seattle.

# Getting started
You will need to have Docker installed on your system.
The download page is https://docs.docker.com/get-docker/.  You can read about Docker at https://docs.docker.com/get-started/

After cloning this repo

1. Change directory into the cloned repo, drupal9gcc: `cd drupal9gcc`
2. Copy the sample environment file:

`cp env.sample .env`

3. Start the docker envrionment

`docker-compose up`

This will take significantly more time the first time it is run.
The database will be initialized with an empty Drupal 9 structure.  The database will have a `root` and `gccsqluser` account created.
The `gccsqluser` password is defined in the `.env` file.
The `root` account gets a random password. In the stream of messages that docker prints out, it will show you the root password.  Look for
a sequence of lines like these:

```
curlingseattle9-db         | 2022-09-18 21:51:35+00:00 [Note] [Entrypoint]: GENERATED ROOT PASSWORD: /8ICe0JWKczLahqbUSD9vpUqUo/FudK8
curlingseattle9-db         | 2022-09-18 21:51:35+00:00 [Note] [Entrypoint]: Creating database drupal9gcc
curlingseattle9-db         | 2022-09-18 21:51:35+00:00 [Note] [Entrypoint]: Creating user gccsqluser
curlingseattle9-db         | 2022-09-18 21:51:35+00:00 [Note] [Entrypoint]: Giving user gccsqluser access to schema drupal9gcc
```

4. Browse http://localhost:8999

The Drupal initialization screens should appear and let you name the site and create an admin user account.
