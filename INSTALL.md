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

It's a good idea to copy the generated root password into your `.env`
file for later use.  There's a placeholder for it in the
`GCC_MYSQL_ROOT_PASSWORD` variable.  The `.env` is ignored by git and
will not be stored in the public repository.

4. Browse http://localhost:8999

You should see the initial adminstrative screen for a Drupal 9 instance.
(Note you can change the port number used on your local machine by
editing the `GCC_WEBSITE_PORT` variable in the `.env` file before running
`docker-compose up`).

5. Set up the admin user password

You can either select Menu > My Account > Edit or browse
http://localhost:8999/user/1/edit In the form, you can change the
admin account's password to something you'll remember.  The password
was initialized to `slowplaysucks` and you'll need that to make your
update.  Press the Save button at the bottom of the form.

6. Enable the Next.js modules

Browse http://localhost:8999/admin/modules to find all the current
modules and whether they are enabled or not.  Scroll down near the
bottom to find the Web services section and enable the `Next.js` and
`Next.js JSON:API` services.  Press the Install button at the bottom
of the form. It will ask you to confirm enabling some dependencies.
Approve those by pressing the Continue button. This will take a
little while to process.  You should get the same page of modules
listed with the Next.js and dependencies now enabled.

7. Configure a path alias for Next.js 

Follow the instructions starting at
https://next-drupal.org/learn/quick-start/configure-path-aliases
(we've already done the preceding steps in the Docker image).
Where it says "Visit /admin/config/search/path/patterns/add", browse
http://localhost:8999/admin/config/search/path/patterns/add

8. Create the Next.js project

This is the next step after configuring a path alias,
https://next-drupal.org/learn/quick-start/create-nexts-project.  You
need to execute this command in the running Docker image.  If you use
Docker Desktop, find the `drupal9gcc` parent container and the
`curlingseattle9-webserver` within it.  In the ACTIONS column, click
on the vertical ellipsis symbol (looks a bit like ☰) and use the Open
in terminal function to bring up a command line interpreter.  When you
run `npx create-next-app -e
https://github.com/chapter-three/next-drupal-basic-starter`, it will
ask you to confirm the installation of the create-next-app package.












