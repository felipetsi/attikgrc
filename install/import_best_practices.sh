#!/bin/bash
clear
# Create new instance
### General parameters
	server="localhost";
	userDB="postgres";
	portDB="5432";
	LOGFILE="/var/log/attikgrc/instance_created.log";
	export PGPASSWORD="postgres";
	

echo "#### Enter the instance data:"
INSTANCE_DB_NAME=`echo attikgrc_test`
#INSTANCE_DB_NAME=`echo $INSTANCE_DB_NAME`

		## 	IMPORT BEST PRATICES  - ISO/IEC 27001
		# sections or clauses
		SQL=" 
		CREATE TABLE tmp_section( 
		  id serial primary key, 
		  section varchar(1000), 
		  control varchar (1000) 
		); 
		 
		COPY tmp_section 
		( 
		  section, 
		  control 
		) 
		FROM '/var/www/attikgrc/install/27001/14_nbr_sections.csv' 
		DELIMITER ';' 
		CSV HEADER; 
		 
		CREATE TABLE tmp_category( 
		  id serial primary key, 
		  section varchar(1000), 
		  control varchar (1000) 
		); 
		 
		COPY tmp_category 
		( 
		  section, 
		  control 
		) 
		FROM '/var/www/attikgrc/install/27001/35_nbr_category.csv' 
		DELIMITER ';' 
		CSV HEADER; 
		 
		CREATE TABLE tmp_control( 
		  id serial primary key, 
		  section varchar(1000), 
		  control varchar (1000) 
		); 
		 
		COPY tmp_control 
		( 
		  section, 
		  control 
		) 
		FROM '/var/www/attikgrc/install/27001/114_nbr_control.csv' 
		DELIMITER ';' 
		CSV HEADER; 
		 
		INSERT INTO tbest_pratice( 
			id_instance, name, detail, status) 
			VALUES (1, 'NBR ISO/IEC 27001', 'ABNT NBR ISO/IEC 27001', 'a'); 
			 
		INSERT INTO tsection_best_pratice( 
			item, id_best_pratice, name) 
			SELECT s.section,1,s.control FROM tmp_section s;  
		 
		INSERT INTO tcategory_best_pratice( 
			item, id_section, name) 
			SELECT c.section,s.id,c.control FROM tmp_category c, tsection_best_pratice s WHERE  
				s.item = (REPLACE(SUBSTRING(c.section FROM 1 FOR 3),'.','')); 
		 
		INSERT INTO tcontrol_best_pratice( 
			item, id_category, name) 
			SELECT t.section,c.id,t.control FROM tmp_control t, tcategory_best_pratice c WHERE  
				(REPLACE(SUBSTRING(c.item FROM 1 FOR 5),'.','')) = (REPLACE(SUBSTRING(t.section FROM 1 FOR 5),'.','')); 
		 
		DROP table tmp_section; 
		DROP table tmp_category; 
		DROP table tmp_control;"
		psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB -c "$SQL" >> $LOGFILE;
		
		## 	IMPORT BEST PRATICES  - ISO/IEC 20000
		# sections or clauses
		SQL=" 
		CREATE TABLE tmp_section( 
		  id serial primary key, 
		  section varchar(1000), 
		  control varchar (1000) 
		); 
		 
		COPY tmp_section 
		( 
		  section, 
		  control 
		) 
		FROM '/var/www/attikgrc/install/20000/6_nbr_sections.csv' 
		DELIMITER ';' 
		CSV HEADER; 
		 
		CREATE TABLE tmp_category( 
		  id serial primary key, 
		  section varchar(1000), 
		  control varchar (1000) 
		); 
		 
		COPY tmp_category 
		( 
		  section, 
		  control 
		) 
		FROM '/var/www/attikgrc/install/20000/22_nbr_category.csv' 
		DELIMITER ';' 
		CSV HEADER; 
		 
		CREATE TABLE tmp_control( 
		  id serial primary key, 
		  section varchar(1000), 
		  control varchar (1000) 
		); 
		 
		COPY tmp_control 
		( 
		  section, 
		  control 
		) 
		FROM '/var/www/attikgrc/install/20000/37_nbr_control.csv' 
		DELIMITER ';' 
		CSV HEADER; 
		 
		INSERT INTO tbest_pratice( 
			id_instance, name, detail, status) 
			VALUES (1, 'NBR ISO/IEC 20000', 'ABNT NBR ISO/IEC 20000', 'a'); 
			 
		INSERT INTO tsection_best_pratice( 
			item, id_best_pratice, name) 
			SELECT s.section,2,s.control FROM tmp_section s;  
		 
		INSERT INTO tcategory_best_pratice( 
			item, id_section, name) 
			SELECT c.section,s.id,c.control FROM tmp_category c, tsection_best_pratice s WHERE  
				s.item = (REPLACE(SUBSTRING(c.section FROM 1 FOR 3),'.','')) AND 
				s.id_best_pratice = 2; 
		 
		INSERT INTO tcontrol_best_pratice( 
			item, id_category, name) 
			SELECT t.section,c.id,t.control FROM tmp_control t, tcategory_best_pratice c, tsection_best_pratice s WHERE  
				(REPLACE(SUBSTRING(c.item FROM 1 FOR 5),'.','')) = (REPLACE(SUBSTRING(t.section FROM 1 FOR 5),'.','')) 
				AND c.id_section = s.id AND s.id_best_pratice = 2;
		 
		DROP table tmp_section; 
		DROP table tmp_category; 
		DROP table tmp_control;"
		psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB -c "$SQL" >> $LOGFILE;
		
		## 	IMPORT BEST PRATICES  - ISO/IEC 9001
		# sections or clauses
		SQL=" 
		CREATE TABLE tmp_section( 
		  id serial primary key, 
		  section varchar(1000), 
		  control varchar (1000) 
		); 
		 
		COPY tmp_section 
		( 
		  section, 
		  control 
		) 
		FROM '/var/www/attikgrc/install/9001/7_9001_sections.csv' 
		DELIMITER ';' 
		CSV HEADER; 
		 
		CREATE TABLE tmp_category( 
		  id serial primary key, 
		  section varchar(1000), 
		  control varchar (1000) 
		); 
		 
		COPY tmp_category 
		( 
		  section, 
		  control 
		) 
		FROM '/var/www/attikgrc/install/9001/28_9001_category.csv' 
		DELIMITER ';' 
		CSV HEADER; 
		 
		CREATE TABLE tmp_control( 
		  id serial primary key, 
		  section varchar(1000), 
		  control varchar (1000) 
		); 
		 
		COPY tmp_control 
		( 
		  section, 
		  control 
		) 
		FROM '/var/www/attikgrc/install/9001/61_9001_control.csv' 
		DELIMITER ';' 
		CSV HEADER; 
		 
		INSERT INTO tbest_pratice( 
			id_instance, name, detail, status) 
			VALUES (1, 'NBR ISO 9001', 'ABNT NBR ISO 9001', 'a'); 
			 
		INSERT INTO tsection_best_pratice( 
			item, id_best_pratice, name) 
			SELECT s.section,3,s.control FROM tmp_section s;  
		 
		INSERT INTO tcategory_best_pratice( 
			item, id_section, name) 
			SELECT c.section,s.id,c.control FROM tmp_category c, tsection_best_pratice s WHERE  
				s.item = (REPLACE(SUBSTRING(c.section FROM 1 FOR 2),'.','')) AND 
				s.id_best_pratice = 3; 
		 
		INSERT INTO tcontrol_best_pratice( 
			item, id_category, name) 
			SELECT t.section,c.id,t.control FROM tmp_control t, tcategory_best_pratice c, tsection_best_pratice s WHERE  
				(REPLACE(SUBSTRING(c.item FROM 1 FOR 4),'.','')) = (REPLACE(SUBSTRING(t.section FROM 1 FOR 4),'.','')) 
				AND c.id_section = s.id AND s.id_best_pratice = 3;
		 
		DROP table tmp_section; 
		DROP table tmp_category; 
		DROP table tmp_control;"
		psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB -c "$SQL" >> $LOGFILE;
		
		## 	IMPORT BEST PRATICES  - BACEN 4.658
		# sections or clauses
		SQL=" 
		CREATE TABLE tmp_section( 
		  id serial primary key, 
		  section varchar(1000), 
		  control varchar (1000) 
		); 
		 
		COPY tmp_section 
		( 
		  section, 
		  control 
		) 
		FROM '/var/www/attikgrc/install/BACEN/4_sections.csv' 
		DELIMITER '@' 
		CSV HEADER; 
		 
		CREATE TABLE tmp_category( 
		  id serial primary key, 
		  section varchar(1000), 
		  control varchar (1000) 
		); 
		 
		COPY tmp_category 
		( 
		  section, 
		  control 
		) 
		FROM '/var/www/attikgrc/install/BACEN/5_category.csv' 
		DELIMITER '@' 
		CSV HEADER; 
		 
		CREATE TABLE tmp_control( 
		  id serial primary key, 
		  section varchar(1000), 
		  control varchar (1000) 
		); 
		 
		COPY tmp_control 
		( 
		  section, 
		  control 
		) 
		FROM '/var/www/attikgrc/install/BACEN/10_control.csv' 
		DELIMITER '@' 
		CSV HEADER; 
		 
		INSERT INTO tbest_pratice( 
			id_instance, name, detail, status) 
			VALUES (1, 'BACEN 4.658', 'BACEN Resolução 4.658', 'a'); 
			 
		INSERT INTO tsection_best_pratice( 
			item, id_best_pratice, name) 
			SELECT s.section,4,s.control FROM tmp_section s;  
		 
		INSERT INTO tcategory_best_pratice( 
			item, id_section, name) 
			SELECT c.section,s.id,c.control FROM tmp_category c, tsection_best_pratice s WHERE  
				s.item = (REPLACE(SUBSTRING(c.section FROM 1 FOR 1),'.','')) AND 
				s.id_best_pratice = 4; 
		 
		INSERT INTO tcontrol_best_pratice( 
			item, id_category, name) 
			SELECT t.section,c.id,t.control FROM tmp_control t, tcategory_best_pratice c, tsection_best_pratice s WHERE  
				(REPLACE(SUBSTRING(c.item FROM 1 FOR 4),'.','')) = (REPLACE(SUBSTRING(t.section FROM 1 FOR 4),'.','')) 
				AND c.id_section = s.id AND s.id_best_pratice = 4;
		 
		DROP table tmp_section; 
		DROP table tmp_category; 
		DROP table tmp_control;"
		psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB -c "$SQL" >> $LOGFILE;
		
export -n PGPASSWORD
