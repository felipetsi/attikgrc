# @ttik GRC

## Let's start

These instructions will go you to install @ttik GRC in your server. 

### Prereqs

* Apache2
* PHP 7 or more
* PostgreSQL, include client
* Compose
* Git

This examples will show how to install @ttik GRC in OS Debian. If you use anouther OS is necessary adapt each step.

```
# apt-get install apache2 php git postgresql postgresql-client php-pgsql

# git clone https://github.com/felipetsi/attikgrc.git

# mv attikgrc /var/www/

# mkdir /var/log/attikgrc/

# touch -c /var/log/attikgrc/scheduling.log

# chown www-data.www-data -R /var/log/attikgrc/

# chown www-data.www-data -R /var/www/attikgrc/

```
## Before advance, you need to prepare the PostgreSQL 
 * Create the attigrc(arm_user) user in PostgreSQL.
   * A example how to do this is: # sudo -u postgres psql -c "CREATE USER arm_user WITH PASSWORD 'YOUR_PASSWORD';"
 * After create the user is necessary create now "attikgrc" Database
   * A example how to do this is: # sudo -u postgres psql -c "CREATE DATABASE attikgrc WITH OWNER = arm_user;"

Set the password of arm_user in database connecntion file

```
# vi /var/www/attikgrc/include/conn_db.php
```

Next:

```
# cd /var/www/attikgrc/install/

# chmod 500 install.sh

# ./install.sh

# cp daily.sh /etc/cron.d/daily

```
#### Make sure that running "install.sh" didn't give any errors.

#### If you alread have one @ttik GRC instaled and need create anouther instance, you can run "create_share_instance.sh" script.

## Now you need configure attikgrc as virtual site in apache.

```
Configure attikgrc as virtual site.

```

### The next step is optional, but recommended.

```
# rm /var/www/attikgrc/install/  

```
## Running the tests
## Really recommend you consider setting https to access @ttik GRC

Now you can access access @ttik GRC with http://YOUR_IP/attikgrc?instance=NAME_PLACE_IN_INSTALLATION
Change:
 * YOUR_IP to the address IP of your server or DNS name created by youself
 * NAME_PLACE_IN_INSTALLATION 

   * The parameter "?instance" is obligatory

## Deployment

For new deployments you should just replace the files and directories inside attikgrc/ directory, keeping only the conn_db.php file.

## CAUTION
####                                                                               ####
#### We really recommend you apply hardening procedures in you Operation System,   ####
#### in special in Apache and PostgreSQL configurations, including change the user ####
#### and directory default. Here we are introducing the installation considering   ####
#### the default setting.                                                          ####
####                                                                               ####

