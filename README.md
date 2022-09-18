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

4. Browse http://localhost:8999
