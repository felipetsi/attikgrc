# @ttik GRC

This project was created with intended of enable the Organizations to adopt Risk Management and Compliance practices that are each more 
needed in several environment.

If you can help us, it'll be very good, if can't, ok.

PT_br: Este projeto é uma inciativa de facilitar a adoção de práticas de Gestão de Riscos e de Compliance nas mais diversas Organizações, 
que vivenciam cada vez mais grandes desafios inseridos não só pelo cada vez mais elevado nível de competitividade mas também pela
crescente quantidade de regulamentações inseridas nos mais diversos setores.

Se puder contribuir com o projeto, será excelente, se não puder, ok.

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
# apt-get install apache2 php git postgresql-client php-pgsql

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
# tar -xzvf attikgrc-xxx.tar.gz
# mv attikgrc /var/www/
# touch /var/log/attikgrc/scheduling.log
# chown www-data.www-data -R /var/log/attikgrc/scheduling.log
# chown www-data.www-data -R /var/www/attikgrc/
# vi /var/www/attikgrc/include/conn_db.php
#### Configure the \$userDB and \$passwd parameters, according to you put in your PostgreSQL instalation.
# ./create_exclusive_instance.sh
##### if you alread have one @ttik GRC instaled and need create anouther instance, you can run "create_share_instance.sh" script.

# cp daily.sh /etc/cron.d/daily

```

## The next step is optional, but recommended If you want, now you can remove the directory /install

```
# rm /var/www/attikgrc/install/  

```
## Running the tests
## Really recommend you consider setting https to access @ttik GRC

Now you can access access @ttik GRC with http://YOUR_IP/attikgrc 

## Deployment

For hour, new deployments will just replace the files and directories within attikgrc/, keeping only the conn_db.php file

## Built With

* [@ttik GRC](http://www.attik.com.br) - The web of maintainer

## Contributing

//Please read [CONTRIBUTING.md](https://www.attik.com.br/attik_grc_donations.php)

## Authors

* **Felipe Pereira da Silva** - *Main Sponsor and firt creator of this project* - [-](https://www.linkedin.com/in/felipe-pereira-da-silva-57566822/)

## License

This project is licensed under the Apache-2.0 License - see the [LICENSE.md](LICENSE.md) file for details

##### A little about @ttik GRC funcitons in portuguese

