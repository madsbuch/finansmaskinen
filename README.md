Finansmaskinen - Application framework
======================================

Et framework til at køre applikationssystemer

.. contents::

Setup
-----
webServer:
needed domains to point the system:
	www.-
	static.-
	and probably the main domain ( "-" ), wich is redirected to www.-

install the proper locales for the languages used.

for apache2
enable rewrite module (debian: a2enmod rewrite)
install mysql pdo driver (debian: php5-mysql)

following settings in php.ini:
	memory_limit 256MB or so?
	max_input_time: 3600 on hour should be enough?
	upload_max_filesize 64MB
	post_max_size 128MB
	max_file_uploads 100
	

let the cli/php.ini be a symlink to apache2/php.ini for the test cases

install mongoDB -> installed via php-pear

install wkhtmltox (https://github.com/mreiferson/php-wkhtmltox) (better guide: http://roundhere.net/journal/install-wkhtmltopdf-php-bindings/)



edit the config/config.php

mysql:

mongoDB:

wkhtmltox
it didn't work on centos for me, untill i installed qt-devel (yum install qt qt-devel)

Add new site / domain
---------------------

to add domain:

 - go to config/router.php
 - add the domain to the $domains and choose wich profile it should point at.
 
To add new site:

 - go to config/router.php
 - add new profile
 - if needed, create start folder and system
 
 
Tmps
----

session_start i index.php slettes. mongoDB bruges i stedet til at håndtere caching og sessions

Conventions
-----------

For core files:
	the function cron, is a reserved callback. I has to be defined statically.
	
Future feature
--------------

Push replication
	Every app must have an API, from where users can access, modify and add
	data. This feature should apply the ability to have an url, where additions
	and changes are pushed to.
filedispatcher
	Der skal være mulighed for at uploade en vilkårlig fil. hver app skal have
	en handler der kan håndtere filer, og "start" skal have en dispatcher, 
	der sender filen ud.
	
Security
--------

expose_php = false, no reason to tell that we use php
keep http headers to a minimum.

feature suggestions
-------------------
(this is probably deprecated, as this is done on business level, and not in this readme ;))
let http://piwik.org/ about doin the stats

Update an installation
----------------------

checklist for updating an installation:
1. make sure all models have code, for updating from older versions (forward migration)
2. refactor the MySQL database on the host (from the migration file)
3. create a app.js (combine all javascript in a single js file, the clousure compiler does it)
4. upload the new code, and overwrite the old one (effectivly: push or let be pulled)

Create localization
-------------------
To create a new localization, som file needs to be created:

static/js/language/ copy one of the existing, and translate all entries
/localization/      copy one of the existing, and translate all entries
/localization/[localID]-settings.lan change the settings for this


Databases
---------
MySQL is pretty self explainatory as of the schema design

mongo should have following index:
db          collection      field
finance     contacts        contactID
finance     products        productID

Cron
----

Cron is to be handled by the virtual machines, that hosts the system:
system crontab may be used.

following commands is to run in following intervals

php appRoot/cli/cli.php cron fast -> every 5 minutes
php appRoot/cli/cli.php cron slow -> every 24th hour
php appRoot/cli/cli.php cron concurrencySafe -> every 5 minutes. ONLY ON ONE MACHINE!

example crontab:

#run every 5 minues
*/5 * * * * php /var/www/cli/cli.php cron fast
*/5 * * * * php /var/www/cli/cli.php cron concurrencySafe

#run every night at 3am
* 3 * * * php /var/www/cli/cli.php cron slow

