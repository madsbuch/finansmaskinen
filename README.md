Finansmaskinen - Application framework
======================================

Et framework til at køre applikationssystemer

1. setup
2. add new site/domain
3. tmps
4. conventions
5. future feature
6. security
7. feature suggestion
8. update an installation
9. create new localization
10. Databases

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

::::::::::::: 7 :::::::::::::
let http://piwik.org/ about doin the stats

::::::::::::: 8 :::::::::::::

checklist for updating an installation:
1. make sure all models have code, for updating from older versions
2. refactor the MySQL database on the host
3. create a app.js (combine all javascript in a single js file, the clousure compiler does it)
4. upload the new code, and overwrite the old one

::::::::::::: 9 :::::::::::::
To create a new localization, som file needs to be created:

static/js/language/ copy one of the existing, and translate all entries
/localization/      copy one of the existing, and translate all entries
/localization/[localID]-settings.lan change the settings for this


::::::::::::: 10 :::::::::::::
MySQL is pretty self explainatory as of the schema design

mongo should have following index:
db          collection      field
finance     contacts        contactID
finance     products        productID
