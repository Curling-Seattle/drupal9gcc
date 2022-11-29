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

7. Initialize the Drupal system

In the Docker Desktop, select Containers in the left-hand panel and
find the `drupal9gcc` container. This should be a 'folder' that holds
the two containers: `curlingseattle9-db` and `drupal9-next`. Selecting
the small arrow ˃ next to the name opens and closes the folder. In the row
for the  `drupal9-next` container, select the 3-dot icon in the 'ACTIONS'
column to open a menu of operations. Select the "Open in terminal" action.
This should change the display to a command line terminal. The prompt 
should be `# ` to indicate you are the root user. If you like a bit more
context, you can enter `bash` which changes the prompt to something like:
`root@e45652ec6bcf:/opt/drupal/web# `. That shows that you are the root
user on the `e45652ec6bcf` container and your directory is
`/opt/drupal/web`. Regardless of the prompt enter the following commands:

`cd /opt/drupal`

`composer update "drupal/core-*" --with-all-dependencies`

This will take a few minutes to load a bunch of Drupal code into the
docker image and verify it (especially the drupal/core stuff). You can
safely ignore the warnings about not finding the unzip or 7z commands.
If you get timeout errors, you can try repeating the command at a later
time to finish the process.
The last line should be: `No security vulnerability advisories found`
Open a browswer window to see the Drupal website,
http://localhost:8999.

8. Connect the database

On the localhost:8999 web page, you should see the initial
adminstrative screen for a Drupal 9 instance.  The webpage walks you
through a series of steps to configure the site. Configure the
language as English and the profile as Standard. If it asks on the 'Set up
database' step, leave the database type as MySQL. Fill in the databse
name, username, and password from the values in the `.env` file that
you edited in an earlier step. These come from the MYSQL_DATABASE,
MYSQL_USER, and MYSQL_PASSWORD variables.  Select the 'ADVANCED
OPTIONS` section and fill in the host from the MYSQL_HOST variable
(don't use 'localhost'). Select 'Save and Continue'.

At this point, you'll either get a) an error that Drupal could not
connect to the database, b) the Configure site dialog, or c) a message
that says 'Drupal already installed'. The latter means you are
successful and can select 'View your existing site' to continue.  If
you get the database error, check the values you entered for the
database connection carefully. Any errors here will prevent using the
pre-configured database. After correcting, try again.
If you get the Configure site dialog, go ahead and fill in these values:

  - Site name: test.curlingseattle
  - Site email address: info@test.curlingseattle
  - Username: admin
  - Password: _some password that you will remember_
  - User email address: info@test.curlingseattle

9. Install the next.js modules

Go back to the Docker Desktop and specifically to the command line
terminal for the `drupal9-next` container. Run the following command:

`composer require drupal/next`

It will ask if you trust "cweagans/composer-patches" to execute code.
Answer yes. It may warn about some abandoned packages, which you can
safely ignore. It should end with the message 
`No security vulnerability advisories found`
like the previous composer command did.

10. Set up the Drupal admin user password

Return to the localhost:8999 web page.
Select Menu > Login, and login as the Drupal admin user.  The initial
password for the admin account was set to `slowplaysucks`.  To update
the password, select My Account > Edit or browse
http://localhost:8999/user/1/edit . In the form, you can change the
admin account's password to something more convenient.  Press the Save
button at the bottom of the form.

11. Enable the Next.js modules

Visit the page the Drupal modules page at
http://localhost:8999/admin/modules (or Go to Extend > List in the
menus). If the Filter box appears near the top, entering 'next' in it.
Otherwise, scroll down to find the "Next.js" and "Next.js JSON:API"
modules.  Select the checkboxes next to them, scroll all the way to
the bottom of the page, and select "Install". The system should prompt
you that some required modules must be enabled. Select
"Continue". This can take a few minutes, but you should get a message
saying the 2 Next.js modules plus some others were enabled.

12. Configure an Article content type

Visit http://localhost:8999/admin/config/search/path/patterns (or use
the menus Configuration > Search and metadata > URL aliases > Patterns).
Select '+ Add Pathauto pattern'. Add the following patern:

  - Pattern type: Content
  - Path pattern: blog/[node:title]
  - Content type: Article
  - Label: Article
  
13. Start up the local webserver running the next.js app

Return to a command line terminal on your local machine (*NOT* the
drupal9-next container terminal). This is where you ran
`docker-compose up` earlier. You will need a second terminal window to
run the next commands (assuming you are still in the `drupal9gcc` top
level directory.

`cd nextjs-gcc`

`npm run dev`

The development web server should start up and produce a bunch of
messages about compiling the client and server javascript code.
If you get an error about `next: command not found`, you probably have not
installed all the node packages used by next.js. Run this command:

`npm install react react-dom next typescript @types/react @types/node @types/react-dom eslint eslint-config-next`

When it finishes, retry the `npm run dev` command.  When you see the
`compiled client and server successfully` message, try visiting
http://localhost:3000 .  You should see a basic page with the title
"Latest Articles".  If it shows an article, you can select the article
title to show its content. If you want to create an article, either follow
"Drupal administration" link or visit http://localhost:8999 to return to
the Drupal website. Select Content > + Add content > Article.
Fill in the Title and Body of the Article. If you want to add an image,
make the Text format "Full HTML" and drag and drop a PNG or JPG file in
the Body. When you're done, select "Save". If you refresh the view in
the next.js app browser (http://localhost:3000), you should see the
article and be able to see any images added to it.

You can stop the development server by entering Control-C on the
terminal where you ran `npm run dev`.

You can find additional information about using next.js with Drupal at
https://next-drupal.org/docs












