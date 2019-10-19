# @ttik GRC

## Getting Started

These instructions will get you a copy of the project up and running on your server for use. 

### Prerequisites

What things you need to install the software and how to install them:
#### Download the last version of @ttik GRC
#### Apache2
#### PHP 7 or more
#### PostgreSQL, include client
#### Compose
#### Git

```
Examples Debian instalation packages
# apt-get install apache2 php git postgresql postgresql-client php-pgsql

# git clone https://github.com/felipetsi/attikgrc.git

```

### Installing
###############################----- CAUTION -----#####################################
####                                                                               ####
#### We really recommended you apply hardening procedures in you Operation System, ####
#### in special in Apache and PostgreSQL configurations, including change the user ####
#### and directory default. Here we are introducing the installation considering   ####
#### the default setting of the environment, so adapt as the settings exist in     ####
#### your settings.                                                                ####
####                                                                               ####
#######################################################################################

After you instaled prereqs, you can advanced

```
# mv attikgrc /var/www/
# mkdir /var/log/attikgrc/
# touch -c /var/log/attikgrc/scheduling.log
# chown www-data.www-data -R /var/log/attikgrc/scheduling.log
# chown www-data.www-data -R /var/www/attikgrc/
# vi /var/www/attikgrc/include/conn_db.php
```
## Before advance, you need to prepare the PostgreSQL 
#### create the attigrc(arm_user) user in PostgreSQL.
#### A example how to do this is in shell: # sudo -u postgres psql -c "CREATE USER arm_user WITH PASSWORD 'YOUR_PASSWORD';"
#### After create the user is necessary create now "attikgrc" Database
#### A example how to do this is in shell: # sudo -u postgres psql -c "CREATE DATABASE attikgrc WITH OWNER = arm_user;"

```
# cd /var/www/attikgrc/install/
# chmod 500 install.sh
# ./install.sh
##### if you alread have one @ttik GRC instaled and need create anouther instance, you can run "create_share_instance.sh" script.

# cp daily.sh /etc/cron.d/daily

```

## Now you need configure attikgrc as virtual site in apache.

```
Configure attikgrc as virtual site.

```

### The next step is optional, but recommended If you want, now you can remove the directory /install

```
# rm /var/www/attikgrc/install/  

```
## Running the tests
## Really recommend you consider setting https to access @ttik GRC

Now you can access access @ttik GRC with http://YOUR_IP/attikgrc 

## Deployment

For new deployments you'll just replace the files and directories inside attikgrc/ directory, keeping only the conn_db.php file.

## Built With

* [@ttik GRC](http://www.attik.com.br) - The web of maintainer

## Contributing

Please read [CONTRIBUTING.md](https://www.attik.com.br/attik_grc_donations.php)

## Authors

* **Felipe Pereira da Silva** - *Main Sponsor * - [-](https://www.linkedin.com/in/felipe-pereira-da-silva-57566822/)

## License

This project is licensed under the Apache-2.0 License - see the [LICENSE.md](LICENSE.md) file for details

##### A little about @ttik GRC funcitons in portuguese

