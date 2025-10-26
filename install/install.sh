#!/bin/bash
######################################################################################
#                                                                                    #
# This script was created to run in OS DEBIAN. 										 #
# Consider adapt something in your environment if you use another Operation System.  #
#                                                                                    #
######################################################################################

clear
# Create new instance
### General parameters
	userDB="arm_user";
	portDB="5432";
	LOGFILE="/var/log/labsec/instance_created.log";
	LABSEC_DIR="/var/www/labsec"

echo "###################################################################"
echo "============================ ATTENTION ============================"
echo ""
echo " THIS IS VULNERABLE LAB VERSION TO EXECUTE SQL INJECTION PRACTICES "
echo "==================================================================="
echo "THIS SCRIPT WAS CREATED TO:"
echo "	- OPERATION SYSTEM DEBIAN;"
echo "	- YOU NEED SET AND PUT THE PASSWORD OF postgres USER OF DB POSTGRESQL BEFORE;"
echo "	- THE DEFAULT PORT OF POSTGRESQL IS 5432;"
echo "	- THE DIRECTORY OF APACHE2 USER IS /var/www/ ;"
echo ""
echo "==================================================================="


### Environment
sudo apt update -y
sudo apt upgrade -y
sudo apt install apache2 php postgresql postgresql-client php-pgsql postgresql-contrib -y
sudo systemctl start postgresql
sudo systemctl enable postgresql
sudo mkdir /var/log/labsec/
sudo touch -c /var/log/labsec/scheduling.log
sudo chown www-data:www-data -R /var/log/labsec/
sudo chown www-data:www-data -R /var/www/labsec/
sudo chmod 666 /var/www/labsec/include/conn_db.php
sudo echo '<?php
//ini_set('display_errors', true); 
//error_reporting(E_ALL);
$server = "localhost";
$userDB = "arm_user";
$passwd ="senha_facil123";
$LANG_NAMEDB="labsec";
$port="5432";
$conn = pg_connect("dbname=$LANG_NAMEDB port=$port host=$server user=$userDB password=$passwd");
?>' > /var/www/labsec/include/conn_db.php
sudo chmod 666 /var/www/labsec/include/conn_db.php
sudo chown www-data:www-data /var/www/labsec/include/conn_db.php
### END - Environment

### Default site
sudo cp /etc/apache2/sites-available/000-default.conf /etc/apache2/sites-available/000-default.conf_default
sed -i 's\DocumentRoot /var/www/html\DocumentRoot /var/www/labsec\' /etc/apache2/sites-available/000-default.conf
sudo service apache2 stop
sudo service apache2 start
sudo service apache2 restart
### END - Default site

### BD
INSTANCE_DB_NAME="labsec"
INSTANCE_NAME="labsec"
INSTANCE_DETAIL="SQL Injection Labsec"
INSTANCE_LOGIN="labsec"
INSTANCE_EMAIL="labsec@labsec.com.br"
INSTANCE_LIMIT_USER="999"
INSTANCE_LANG="en"
server="localhost"
export PGPASSWORD="senha_facil123";

echo "#### End the instance data:"

#sudo -u postgres psql -c "REASSIGN OWNED BY arm_user TO postgres;"
#sudo -u postgres psql -c "DROP USER IF EXISTS arm_user";
sudo -u postgres psql -c "CREATE USER $userDB WITH PASSWORD 'senha_facil123' SUPERUSER;"
sudo -u postgres psql -c "CREATE DATABASE $INSTANCE_DB_NAME WITH OWNER = $userDB;"

cat labsec.sql | psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB

SQL="INSERT INTO tinstance(name, detail, status, limit_user, language_default, acceptance_risk_level, \
                             limit_error_login, max_password_lifetime, min_password_lifetime, time_change_temp_password,\
							 close_system, enable_delete_cascade) \
	VALUES ('$INSTANCE_NAME', '$INSTANCE_DETAIL', 'a', $INSTANCE_LIMIT_USER, '$INSTANCE_LANG', 2, 5, 90, 0, 1,'n','n');"
psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB -c "$SQL" >> $LOGFILE;

SQL="SELECT id FROM tinstance WHERE name LIKE '$INSTANCE_NAME'";
psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB -t -c "$SQL" |
while read INSTANCE 
do
	ID_INST=`echo $INSTANCE | awk -F'|' '{print $1}'| sed 's/ //g'`;
	
	if [ ! -z $ID_INST ]; 
		then 
		SQL="INSERT INTO tperson(id_instance, language_default, name, \
				detail, email, change_password_next_login, erro_access_login, \
				date_last_change_password, login, password, status) \
			VALUES ($ID_INST,'en', 'Labsec', 'Labsec User', \
				'$INSTANCE_EMAIL', 'n', 0, '2024-01-01', '$INSTANCE_LOGIN', '77ed1c1d1a178ec8a4b94a4bfbd34b1b41fe9929', 'a');";
		
		psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB -c "$SQL" >> $LOGFILE;
	fi
done

# Create radon users and database for security analysis
sudo -u postgres psql -c "CREATE USER usuario_de_aula WITH PASSWORD '123' SUPERUSER;"
sudo -u postgres psql -c "CREATE DATABASE db_aula WITH OWNER = usuario_de_aula;"

sudo -u postgres psql -c "CREATE USER usuario_sql WITH PASSWORD '1234' SUPERUSER;"
sudo -u postgres psql -c "CREATE DATABASE db_injection WITH OWNER = usuario_sql;"

# Create a vulnerable CGI-BIN code
sudo echo '#!/bin/bash
echo "Content-type: text/html"
echo
echo "hello", $HTTP_USER_AGENT' > /var/www/cgi-bin/hello.sh
sudo chmod 777 /var/www/cgi-bin/hello.sh
sudo chown www-data:www-data /var/www/cgi-bin/hello.sh

export -n PGPASSWORD
### END - BD