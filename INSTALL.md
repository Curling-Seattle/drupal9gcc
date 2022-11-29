# Installing the Docker images and Initializing the Drupal Next.js system

To make the development environment work, three systems need to run:

	- A mysql database server (running in a Docker container)
	- A Drupal web server (running in a second Docker container)
	- A next.js web server running directly on your development machine

This guide provides step by step instructions to set up all three systems.

1. If you have not already done so, clone this `drupal9gcc` repository
to your development machine.

`git clone git@github.com:Curling-Seattle/drupal9gcc` (if you're using SSH)

2. Install `node.js` on your development machine. This is needed to run the
next.js web server.

See https://nodejs.org/en/download/

3. Install Docker Desktop on your development machine. Docker runs
containers for Drupal and the database. The Docker Desktop provides a GUI
for managing the containers, images, and volumes. There are also command
line tools for performing those operations.

https://www.docker.com/

4. Change directory into the cloned repo: `cd drupal9gcc`

5. Copy the sample Drupal environment file: `cp env.sample .env`

You may edit this file later.

6. Start the docker envrionment for the development website

`docker-compose up`

This command fetches Docker images from the Internet (if needed),
caches local copies on your machine, and then runs them inside
containers.  It will take significantly more time the first time it is
run.  The database is run in a separate container from the Drupal web
server and stores its content in locally cached volume.  The first
time you run `docker-compose`, the database will be initialized with a
nearly empty Drupal 9 structure.  The database will have a `root` and
`gccsqluser` account created.  The passwords for those accounts are
defined in the `.env` file. The values must match what was stored in
the database snapshot stored in the `.docker/db-load/drupal9gcc-dump.sql`
file.

For security, you should change the passwords for those accounts on your
develpment machine.

7. Browse the Drupal website, http://localhost:8999

You should see the initial adminstrative screen for a Drupal 9 instance.
(Note you can change the port number used on your local machine by
editing the `GCC_WEBSITE_PORT` variable in the `.env` file before running
`docker-compose up`).

8. Set up the Drupal admin user password

Select Menu > Login, and login as the Drupal admin user.  The initial
password for the admin account was set to `slowplaysucks`.  To update
the password, select My Account > Edit or browse
http://localhost:8999/user/1/edit . In the form, you can change the
admin account's password to something more convenient.  Press the Save
button at the bottom of the form.

9. The Drupal instance has already been configured with the Next.js
and Next.js JSON:API modules. You can verify this by visiting
http://localhost:8999/admin/modules and entering 'next' in the Filter box.
An `Article` content type has been configured in Drupal so that the
next.js app can find it. You can verify its settings by visiting
http://localhost:8888/admin/config/search/path/patterns .

10. Start up the local webserver running the next.js app

`cd nextjs-gcc`
`npm run dev`

The development web server should start up and produce a bunch of
messages about compiling the client and server javascript code.
When you see the `compiled client and server successfully` message,
try visiting http://localhost:3000 .
You should see a basic page with at least one article. Clicking on
that article title should show the content.

You can find additional information about using next.js with Drual at
https://next-drupal.org/docs












