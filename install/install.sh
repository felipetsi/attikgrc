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
	server="localhost";
	userDB="postgres";
	portDB="5432";
	LOGFILE="/var/log/attikgrc/instance_created.log";
	export PGPASSWORD="postgres";

echo "###################################################################"
echo "============================ ATTENTION ============================"
echo ""
echo "CERTIFY THAT YOU ALREADY EXECUTED THE PROCEDURES SHOWN IN README.md"
echo "==================================================================="
echo "THIS SCRIPT WAS CREATED TO:"
echo "	- OPERATION SYSTEM DEBIAN;"
echo "	- WITH DEFAULT PASSWORD OF postgres USER OF DB POSTGRESQL;"
echo "	- THE DEFAULT PORT OF POSTGRESQL IS 5432;"
echo "	- THE DIRECTORY OF APACHE2 USER IS /var/www/ ;"
echo ""
echo "CONSIDER ADAPT SOMETHING IN YOUR ENVIRONMENT IF YOU USE ANOTHER CONFIGURATION"
echo "==================================================================="

echo "#### Enter the instance data:"
#echo "DB name:"
#read INSTANCE_DB_NAME
INSTANCE_DB_NAME="attikgrc"
#INSTANCE_DB_NAME=`echo attikgrc_$INSTANCE_DB_NAME`
#INSTANCE_DB_NAME=`echo $INSTANCE_DB_NAME`
echo "Name:"
read INSTANCE_NAME
echo "Detail:"
read INSTANCE_DETAIL
echo "login main:"
read INSTANCE_LOGIN
echo "email main:"
read INSTANCE_EMAIL
echo "Limit users:"
read INSTANCE_LIMIT_USER
echo "Default language:"
read INSTANCE_LANG
echo "#### End the instance data:"

SQL="CREATE DATABASE $INSTANCE_DB_NAME WITH OWNER = arm_user TABLESPACE = pg_default CONNECTION LIMIT = -1;"
psql -U $userDB -h $server -p $portDB -c "$SQL" >> $LOGFILE;
SQL="COMMENT ON DATABASE $INSTANCE_DB_NAME \
  IS 'Data base of @ttik GRC Application - client $INSTANCE_NAME.';"
psql -U $userDB -h $server -p $portDB -c "$SQL" >> $LOGFILE;


cat attik-grc.sql | psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB

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
		SQL="INSERT INTO tgroup_itemprofile (name) VALUES('configuration');\
		INSERT INTO tgroup_itemprofile (name) VALUES('improvement');\
		INSERT INTO tgroup_itemprofile (name) VALUES('riskmanager');\
			\
		INSERT INTO tprofile(id_instance, name)\
			VALUES ($ID_INST , 'Security Office');	\
		INSERT INTO titemprofile(name,id_group) \
			VALUES ('instance_conf',1); \
		INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
			VALUES (1, 1); \
		INSERT INTO titemprofile(name,id_group) \
			VALUES ('view_history',1); \
		INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
			VALUES (1, 2); \
		INSERT INTO titemprofile(name,id_group) \
			VALUES ('profile_manager',1); \
		INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
			VALUES (1, 3); \
		INSERT INTO titemprofile(name,id_group) \
			VALUES ('user_manager',1); \
		INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
			VALUES (1, 4); \
		INSERT INTO titemprofile(name,id_group) \
			VALUES ('create_task',2); \
		INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
			VALUES (1, 5); \
		INSERT INTO titemprofile(name,id_group) \
			VALUES ('read_own_task',2); \
		INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
			VALUES (1, 6); \
		INSERT INTO titemprofile(name,id_group) \
			VALUES ('read_all_task',2); \
		INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
			VALUES (1, 7); \
		INSERT INTO titemprofile(name,id_group) \
			VALUES ('approver_task',2); \
		INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
			VALUES (1, 8); \
		INSERT INTO titemprofile(name,id_group) \
			VALUES ('treatment_task',2); \
		INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
			VALUES (1, 9); \
		INSERT INTO titemprofile(name,id_group) \
			VALUES ('create_project',2); \
		INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
			VALUES (1, 10); \
		INSERT INTO titemprofile(name,id_group) \
			VALUES ('read_project',2); \
		INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
			VALUES (1, 11); \
		INSERT INTO titemprofile(name,id_group) \
			VALUES ('create_area',3); \
		INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
			VALUES (1, 12); \
		INSERT INTO titemprofile(name,id_group) \
			VALUES ('read_area',3); \
		INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
			VALUES (1, 13); \
		INSERT INTO titemprofile(name,id_group) \
			VALUES ('create_process',3); \
		INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
			VALUES (1, 14); \
		INSERT INTO titemprofile(name,id_group) \
			VALUES ('read_process',3); \
		INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
			VALUES (1, 15); \
		INSERT INTO titemprofile(name,id_group) \
			VALUES ('create_risk',3); \
		INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
			VALUES (1, 16); \
		INSERT INTO titemprofile(name,id_group) \
			VALUES ('read_own_risk',3); \
		INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
			VALUES (1, 17); \
		INSERT INTO titemprofile(name,id_group) \
			VALUES ('read_all_risk',3); \
		INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
			VALUES (1, 18); \
		INSERT INTO titemprofile(name,id_group) \
			VALUES ('treatment_risk',3); \
		INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
			VALUES (1, 19); \
		INSERT INTO titemprofile(name,id_group) \
			VALUES ('create_control',3); \
		INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
			VALUES (1, 20); \
		INSERT INTO titemprofile(name,id_group) \
			VALUES ('read_own_control',3); \
		INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
			VALUES (1, 21); \
		INSERT INTO titemprofile(name,id_group) \
			VALUES ('read_all_control',3); \
		INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
			VALUES (1, 22); \
		INSERT INTO titemprofile(name,id_group) \
			VALUES ('revision_efficacy',3); \
		INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
			VALUES (1, 23); \
		INSERT INTO titemprofile(name,id_group) \
			VALUES ('create_incident',2); \
		INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
			VALUES (1, 24); \
		INSERT INTO titemprofile(name,id_group) \
			VALUES ('read_own_incident',2); \
		INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
			VALUES (1, 25); \
		INSERT INTO titemprofile(name,id_group) \
			VALUES ('read_all_incident',2); \
		INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
			VALUES (1, 26); \
		INSERT INTO titemprofile(name,id_group) \
			VALUES ('create_nonconformity',2); \
		INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
			VALUES (1, 27); \
		INSERT INTO titemprofile(name,id_group) \
			VALUES ('read_own_nonconformity',2); \
		INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
			VALUES (1, 28); \
		INSERT INTO titemprofile(name,id_group) \
			VALUES ('read_all_nonconformity',2); \
		INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
			VALUES (1, 29); \
		INSERT INTO titemprofile(name,id_group) \
			VALUES ('create_asset',3); \
		INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
			VALUES (1, 30); \
		INSERT INTO titemprofile(name,id_group) \
			VALUES ('read_own_asset',3); \
		INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
			VALUES (1, 31); \
		INSERT INTO titemprofile(name,id_group) \
			VALUES ('read_all_asset',3); \
		INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
			VALUES (1, 32); \
		INSERT INTO titemprofile(name,id_group) \
			VALUES ('create_report',2); \
		INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
			VALUES (1, 33); \
		INSERT INTO titemprofile(name,id_group) \
			VALUES ('read_report',2); \
		INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
			VALUES (1, 34); \
			\
		INSERT INTO tinstance_impact_money( \
			id_instance, impact_level, value_start, value_end) \
			VALUES (1, '1', 0 , 2000); \
		INSERT INTO tinstance_impact_money( \
			id_instance, impact_level, value_start, value_end) \
			VALUES (1, '2', 2001, 10000); \
		INSERT INTO tinstance_impact_money( \
			id_instance, impact_level, value_start, value_end) \
			VALUES (1, '3', 10001,0);
		INSERT INTO tperson(id_profile, id_instance, language_default, name, \
				detail, email, change_password_next_login, erro_access_login, \
				date_last_change_password, login, password, status) \
			VALUES (1, $ID_INST, 'en', 'Security Office', 'The information security responsible', \
				'$INSTANCE_EMAIL', 'n', 0, '2018-01-16', '$INSTANCE_LOGIN', '7a3f6a974ba195864885b9bc97cb65db3f0974c0', 'a');";

		psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB -c "$SQL" >> $LOGFILE;
		

		SQL="INSERT INTO timpact_type( \
			id_instance, name, default_type, status) VALUES ($ID_INST, 'security', 'y', 'a'); \
		INSERT INTO timpact_type( \
			id_instance, name, default_type, status) VALUES ($ID_INST, 'quality', 'y', 'a'); \
		INSERT INTO timpact_type( \
			id_instance, name, default_type, status) VALUES ($ID_INST, 'city', 'y', 'a');"

		psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB -c "$SQL" >> $LOGFILE;

		# Create impacts
		SQL="SELECT id FROM timpact_type WHERE name LIKE 'security' AND id_instance = $ID_INST";
		psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB -t -c "$SQL" |
		while read IMP_TYPE 
		do
			ID_IMP_TYPE=`echo $IMP_TYPE | awk -F'|' '{print $1}'| sed 's/ //g'`;
	
			if [ ! -z $ID_IMP_TYPE ]; 
			then 
				SQL="	INSERT INTO timpact( \
					id_impact_type, name, weight, status) VALUES ($ID_IMP_TYPE,\
				       	'confidentiality', 1, 'a'); \
					INSERT INTO timpact( \
					id_impact_type, name, weight, status) VALUES ($ID_IMP_TYPE,\
				       	'integrity', 1, 'a'); \
					INSERT INTO timpact( \
					id_impact_type, name, weight, status) VALUES ($ID_IMP_TYPE,\
				       	'availability', 1, 'a'); \
					INSERT INTO timpact( \
					id_impact_type, name, weight, status) VALUES ($ID_IMP_TYPE,\
				       	'financial', 1, 'a');"
				psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB -t -c "$SQL"
			fi
		done

		SQL="SELECT id FROM timpact_type WHERE name LIKE 'quality' AND id_instance = $ID_INST";
		psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB -t -c "$SQL" |
		while read IMP_TYPE 
		do
			ID_IMP_TYPE=`echo $IMP_TYPE | awk -F'|' '{print $1}'| sed 's/ //g'`;
	
			if [ ! -z $ID_IMP_TYPE ]; 
			then 
				SQL="	INSERT INTO timpact( \
					id_impact_type, name, weight, status) VALUES ($ID_IMP_TYPE,\
				       	'customer_satisfaction', 1, 'a'); \
					INSERT INTO timpact( \
					id_impact_type, name, weight, status) VALUES ($ID_IMP_TYPE,\
				       	'requirement', 1, 'a'); \
					INSERT INTO timpact( \
					id_impact_type, name, weight, status) VALUES ($ID_IMP_TYPE,\
				       	'budget', 1, 'a'); \
					INSERT INTO timpact( \
					id_impact_type, name, weight, status) VALUES ($ID_IMP_TYPE,\
				       	'financial', 1, 'a');"
				psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB -t -c "$SQL"
			fi
		done

		SQL="SELECT id FROM timpact_type WHERE name LIKE 'city' AND id_instance = $ID_INST";
		psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB -t -c "$SQL" |
		while read IMP_TYPE 
		do
			ID_IMP_TYPE=`echo $IMP_TYPE | awk -F'|' '{print $1}'| sed 's/ //g'`;
	
			if [ ! -z $ID_IMP_TYPE ]; 
			then 
				SQL="	INSERT INTO timpact( \
					id_impact_type, name, weight, status) VALUES ($ID_IMP_TYPE,\
				       	'social', 1, 'a'); \
					INSERT INTO timpact( \
					id_impact_type, name, weight, status) VALUES ($ID_IMP_TYPE,\
				       	'environment', 1, 'a'); \
					INSERT INTO timpact( \
					id_impact_type, name, weight, status) VALUES ($ID_IMP_TYPE,\
				       	'economic', 1, 'a'); \
					INSERT INTO timpact( \
					id_impact_type, name, weight, status) VALUES ($ID_IMP_TYPE,\
				       	'financial', 1, 'a');"
				psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB -t -c "$SQL"
			fi
		done

		## 	IMPORT BEST PRATICES  - ISO/IEC 27001
		# sections or clauses
		psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB -c " 
		CREATE TABLE tmp_section( \
		  id serial primary key, \
		  section varchar(10000), \
		  control varchar (10000) \
		); "
		
		psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB -c " 
		COPY tmp_section \
		( \
		  section, \
		  control \
		) \
		FROM STDIN \
		DELIMITER ';' \
		CSV HEADER;" < /var/www/attikgrc/install/27001/14_nbr_sections.csv >> $LOGFILE;
		
		psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB -c "  
		CREATE TABLE tmp_category( \
		  id serial primary key, \
		  section varchar(10000), \
		  control varchar (10000) \
		); "
		
		psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB -c " 
		COPY tmp_category \
		( \
		  section, \
		  control \
		) \
		FROM STDIN \
		DELIMITER ';' \
		CSV HEADER; " < /var/www/attikgrc/install/27001/35_nbr_category.csv >> $LOGFILE;
		
		psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB -c "  
		CREATE TABLE tmp_control( \
		  id serial primary key, \
		  section varchar(10000), \
		  control varchar (10000) \
		); "
		
		psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB -c " 
		COPY tmp_control \
		( \
		  section, \
		  control \
		) \
		FROM STDIN  \
		DELIMITER ';' \
		CSV HEADER;" < /var/www/attikgrc/install/27001/114_nbr_control.csv >> $LOGFILE;
		
		SQL=" \
		INSERT INTO tbest_pratice( \
			id_instance, name, detail, status) \
			VALUES (1, 'NBR ISO/IEC 27001', 'ABNT NBR ISO/IEC 27001', 'a'); \
			 \
		INSERT INTO tsection_best_pratice( \
			item, id_best_pratice, name) \
			SELECT s.section,1,s.control FROM tmp_section s;  \
		 \
		INSERT INTO tcategory_best_pratice( \
			item, id_section, name) \
			SELECT c.section,s.id,c.control FROM tmp_category c, tsection_best_pratice s WHERE  \
				s.item = (REPLACE(SUBSTRING(c.section FROM 1 FOR 3),'.','')); \
		 \
		INSERT INTO tcontrol_best_pratice( \
			item, id_category, name) \
			SELECT t.section,c.id,t.control FROM tmp_control t, tcategory_best_pratice c WHERE  \
				(REPLACE(SUBSTRING(c.item FROM 1 FOR 5),'.','')) = (REPLACE(SUBSTRING(t.section FROM 1 FOR 5),'.','')); \
		 \
		DROP table tmp_section; \
		DROP table tmp_category; \
		DROP table tmp_control;"
		psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB -c "$SQL" >> $LOGFILE;
		
		## 	IMPORT BEST PRATICES  - ISO/IEC 20000
		# sections or clauses
		psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB -c " 
		CREATE TABLE tmp_section( \
		  id serial primary key, \
		  section varchar(10000), \
		  control varchar (10000) \
		); "
		
		psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB -c " 
		COPY tmp_section \
		( \
		  section, \
		  control \
		) \
		FROM STDIN \
		DELIMITER ';' \
		CSV HEADER; " < /var/www/attikgrc/install/20000/6_nbr_sections.csv >> $LOGFILE;
		
		psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB -c " 
		CREATE TABLE tmp_category( \
		  id serial primary key, \
		  section varchar(10000), \
		  control varchar (10000) \
		); "
		
		psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB -c " 
		COPY tmp_category \
		( \
		  section, \
		  control \
		) \
		FROM STDIN  \
		DELIMITER ';' \
		CSV HEADER;" < /var/www/attikgrc/install/20000/22_nbr_category.csv >> $LOGFILE;
		
		psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB -c " 
		CREATE TABLE tmp_control( \
		  id serial primary key, \
		  section varchar(10000), \
		  control varchar (10000) \
		); "
		
		psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB -c " 
		COPY tmp_control \
		( \
		  section, \
		  control \
		) \
		FROM STDIN \
		DELIMITER ';' \
		CSV HEADER; " < /var/www/attikgrc/install/20000/37_nbr_control.csv >> $LOGFILE;
		
		SQL=" \
		INSERT INTO tbest_pratice( \
			id_instance, name, detail, status) \
			VALUES (1, 'NBR ISO/IEC 20000', 'ABNT NBR ISO/IEC 20000', 'a'); \
			 \
		INSERT INTO tsection_best_pratice( \
			item, id_best_pratice, name) \
			SELECT s.section,2,s.control FROM tmp_section s;  \
		 \
		INSERT INTO tcategory_best_pratice( \
			item, id_section, name) \
			SELECT c.section,s.id,c.control FROM tmp_category c, tsection_best_pratice s WHERE  \
				s.item = (REPLACE(SUBSTRING(c.section FROM 1 FOR 1),'.','')) AND \
				s.id_best_pratice = 2; \
		 \
		INSERT INTO tcontrol_best_pratice( \
			item, id_category, name) \
			SELECT t.section,c.id,SUBSTRING(t.control,1,200) FROM tmp_control t, tcategory_best_pratice c, tsection_best_pratice s WHERE  \
				(REPLACE(SUBSTRING(c.item FROM 1 FOR 3),'.','')) = (REPLACE(SUBSTRING(t.section FROM 1 FOR 3),'.','')) \
				AND c.id_section = s.id AND s.id_best_pratice = 2;
		 \
		DROP table tmp_section; \
		DROP table tmp_category; \
		DROP table tmp_control;"
		psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB -c "$SQL" >> $LOGFILE;
		
		## 	IMPORT BEST PRATICES  - ISO/IEC 9001
		# sections or clauses
		psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB -c " 
		CREATE TABLE tmp_section( \
		  id serial primary key, \
		  section varchar(10000), \
		  control varchar (10000) \
		); "
		
		psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB -c " 
		COPY tmp_section \
		( \
		  section, \
		  control \
		) \
		FROM STDIN  \
		DELIMITER ';' \
		CSV HEADER;" < /var/www/attikgrc/install/9001/7_9001_sections.csv >> $LOGFILE;
		
		psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB -c " 
		CREATE TABLE tmp_category( \
		  id serial primary key, \
		  section varchar(10000), \
		  control varchar (10000) \
		); "
		
		psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB -c " 
		COPY tmp_category \
		( \
		  section, \
		  control \
		) \
		FROM STDIN \
		DELIMITER ';' \
		CSV HEADER; " < /var/www/attikgrc/install/9001/28_9001_category.csv >> $LOGFILE;
		
		psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB -c " 
		CREATE TABLE tmp_control( \
		  id serial primary key, \
		  section varchar(10000), \
		  control varchar (10000) \
		); "
		
		psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB -c " 
		COPY tmp_control \
		( \
		  section, \
		  control \
		) \
		FROM STDIN \
		DELIMITER ';' \
		CSV HEADER;" < /var/www/attikgrc/install/9001/61_9001_control.csv >> $LOGFILE;
		
		SQL=" \
		INSERT INTO tbest_pratice( \
			id_instance, name, detail, status) \
			VALUES (1, 'NBR ISO 9001', 'ABNT NBR ISO 9001', 'a'); \
			 \
		INSERT INTO tsection_best_pratice( \
			item, id_best_pratice, name) \
			SELECT s.section,3,s.control FROM tmp_section s;  \
		 \
		INSERT INTO tcategory_best_pratice( \
			item, id_section, name) \
			SELECT c.section,s.id,c.control FROM tmp_category c, tsection_best_pratice s WHERE  \
				s.item = (REPLACE(SUBSTRING(c.section FROM 1 FOR 2),'.','')) AND \
				s.id_best_pratice = 3; \
		 \
		INSERT INTO tcontrol_best_pratice( \
			item, id_category, name) \
			SELECT t.section,c.id,SUBSTRING(t.control,1,200) FROM tmp_control t, tcategory_best_pratice c, tsection_best_pratice s WHERE  \
				(REPLACE(SUBSTRING(c.item FROM 1 FOR 4),'.','')) = (REPLACE(SUBSTRING(t.section FROM 1 FOR 4),'.','')) \
				AND c.id_section = s.id AND s.id_best_pratice = 3;
		 \
		DROP table tmp_section; \
		DROP table tmp_category; \
		DROP table tmp_control;"
		psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB -c "$SQL" >> $LOGFILE;
		
		## 	IMPORT BEST PRATICES  - BACEN 4.658
		# sections or clauses
		psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB -c " 
		CREATE TABLE tmp_section( \
		  id serial primary key, \
		  section varchar(10000), \
		  control varchar (10000) \
		); "
		
		psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB -c " 
		COPY tmp_section \
		( \
		  section, \
		  control \
		) \
		FROM STDIN \
		DELIMITER '@' \
		CSV HEADER; " < /var/www/attikgrc/install/BACEN/4_sections.csv >> $LOGFILE;
		
		psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB -c " 
		CREATE TABLE tmp_category( \
		  id serial primary key, \
		  section varchar(10000), \
		  control varchar (10000) \
		); "
		
		psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB -c " 
		COPY tmp_category \
		( \
		  section, \
		  control \
		) \
		FROM STDIN \
		DELIMITER '@' \
		CSV HEADER; " < /var/www/attikgrc/install/BACEN/5_category.csv >> $LOGFILE;
		
		psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB -c " 
		CREATE TABLE tmp_control( \
		  id serial primary key, \
		  section varchar(10000), \
		  control varchar (10000) \
		); "
		
		psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB -c " 
		COPY tmp_control \
		( \
		  section, \
		  control \
		) \
		FROM STDIN \
		DELIMITER '@' \
		CSV HEADER; " < /var/www/attikgrc/install/BACEN/10_control.csv >> $LOGFILE;
		
		SQL=" \
		INSERT INTO tbest_pratice( \
			id_instance, name, detail, status) \
			VALUES (1, 'BACEN 4.658', 'BACEN Resolução 4.658', 'a'); \
			 \
		INSERT INTO tsection_best_pratice( \
			item, id_best_pratice, name) \
			SELECT s.section,4,s.control FROM tmp_section s;  \
		 \
		INSERT INTO tcategory_best_pratice( \
			item, id_section, name) \
			SELECT c.section,s.id,c.control FROM tmp_category c, tsection_best_pratice s WHERE  \
				s.item = (REPLACE(SUBSTRING(c.section FROM 1 FOR 1),'.','')) AND \
				s.id_best_pratice = 4; \
		 \
		INSERT INTO tcontrol_best_pratice( \
			item, id_category, name) \
			SELECT t.section,c.id,SUBSTRING(t.control,1,200) FROM tmp_control t, tcategory_best_pratice c, tsection_best_pratice s WHERE  \
				(REPLACE(SUBSTRING(c.item FROM 1 FOR 4),'.','')) = (REPLACE(SUBSTRING(t.section FROM 1 FOR 4),'.','')) \
				AND c.id_section = s.id AND s.id_best_pratice = 4;
		 \
		DROP table tmp_section; \
		DROP table tmp_category; \
		DROP table tmp_control;"
		psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB -c "$SQL" >> $LOGFILE;

		## 	IMPORT BEST PRATICES  - Lei 13.709 - LGPD
		# sections or clauses
		psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB -c " 
		CREATE TABLE tmp_section( \
		  id serial primary key, \
		  section varchar(10000), \
		  control varchar (10000) \
		); "
		
		psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB -c " 
		COPY tmp_section \
		( \
		  section, \
		  control \
		) \
		FROM STDIN \
		DELIMITER '@' \
		CSV HEADER; " < /var/www/attikgrc/install/13709/sections.csv >> $LOGFILE;
		
		psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB -c " 
		CREATE TABLE tmp_category( \
		  id serial primary key, \
		  section varchar(10000), \
		  control varchar (10000) \
		); "
		
		psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB -c " 
		COPY tmp_category \
		( \
		  section, \
		  control \
		) \
		FROM STDIN \
		DELIMITER '@' \
		CSV HEADER;" < /var/www/attikgrc/install/13709/category.csv >> $LOGFILE;
		 
		psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB -c " 
		CREATE TABLE tmp_control( \
		  id serial primary key, \
		  section varchar(10000), \
		  control varchar (10000) \
		); "
		
		psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB -c " 
		COPY tmp_control \
		( \
		  section, \
		  control \
		) \
		FROM STDIN \
		DELIMITER '@' \
		CSV HEADER; " < /var/www/attikgrc/install/13709/control.csv >> $LOGFILE;
		
		SQL=" \
		INSERT INTO tbest_pratice( \
			id_instance, name, detail, status) \
			VALUES (1, 'Lei 13.709:2018', 'Lei Geral de Proteção de Dados Pessoais (LGPD)', 'a'); \
			 \
		INSERT INTO tsection_best_pratice( \
			item, id_best_pratice, name) \
			SELECT s.section,5,s.control FROM tmp_section s;  \
		 \
		INSERT INTO tcategory_best_pratice( \
			item, id_section, name) \
			SELECT c.section,s.id,c.control FROM tmp_category c, tsection_best_pratice s WHERE  \
				s.item = (REPLACE(SUBSTRING(c.section FROM 1 FOR 1),'.','')) AND \
				s.id_best_pratice = 5; \
		 \
		INSERT INTO tcontrol_best_pratice( \
			item, id_category, name) \
			SELECT t.section,c.id,SUBSTRING(t.control,1,200) FROM tmp_control t, tcategory_best_pratice c, tsection_best_pratice s WHERE  \
				(REPLACE(SUBSTRING(c.item FROM 1 FOR 4),'.','')) = (REPLACE(SUBSTRING(t.section FROM 1 FOR 4),'.','')) \
				AND c.id_section = s.id AND s.id_best_pratice = 5;
		 \
		DROP table tmp_section; \
		DROP table tmp_category; \
		DROP table tmp_control;"
		psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB -c "$SQL" >> $LOGFILE;
		
	fi
done

export -n PGPASSWORD
