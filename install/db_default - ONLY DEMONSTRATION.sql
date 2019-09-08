/*   This script is only to create a demonstration base manually if necessary, SHOULDN'T be use to create a production environment. USE INSTALATION'S SCRIPT.

Before run this script you need create the base and tables with attik-grc.sql.*/

INSERT INTO tinstance(name, detail, status, limit_user, language_default, acceptance_risk_level, 
                             limit_error_login, max_password_lifetime, min_password_lifetime, time_change_temp_password,
							 close_system, enable_delete_cascade) 
	VALUES ('attik', 'attik', 'a', 0, 'en', 2, 5, 90, 0, 1,'n','n');
	
	
INSERT INTO tgroup_itemprofile (name) VALUES('configuration'); 
INSERT INTO tgroup_itemprofile (name) VALUES('improvement'); 
INSERT INTO tgroup_itemprofile (name) VALUES('riskmanager'); 
	
INSERT INTO tprofile(id_instance, name) 
	VALUES (1 , 'Security Office');	
INSERT INTO titemprofile(name,id_group) 
	VALUES ('instance_conf',1); 
INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) 
	VALUES (1, 1); 
INSERT INTO titemprofile(name,id_group) 
	VALUES ('view_history',1); 
INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) 
	VALUES (1, 2); 
INSERT INTO titemprofile(name,id_group) 
	VALUES ('profile_manager',1); 
INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) 
	VALUES (1, 3); 
INSERT INTO titemprofile(name,id_group) 
	VALUES ('user_manager',1); 
INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) 
	VALUES (1, 4); 
INSERT INTO titemprofile(name,id_group) 
	VALUES ('create_task',2); 
INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) 
	VALUES (1, 5); 
INSERT INTO titemprofile(name,id_group) 
	VALUES ('read_own_task',2); 
INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) 
	VALUES (1, 6); 
INSERT INTO titemprofile(name,id_group) 
	VALUES ('read_all_task',2); 
INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) 
	VALUES (1, 7); 
INSERT INTO titemprofile(name,id_group) 
	VALUES ('approver_task',2); 
INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) 
	VALUES (1, 8); 
INSERT INTO titemprofile(name,id_group) 
	VALUES ('treatment_task',2); 
INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) 
	VALUES (1, 9); 
INSERT INTO titemprofile(name,id_group) 
	VALUES ('create_project',2); 
INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) 
	VALUES (1, 10); 
INSERT INTO titemprofile(name,id_group) 
	VALUES ('read_project',2); 
INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) 
	VALUES (1, 11); 
INSERT INTO titemprofile(name,id_group) 
	VALUES ('create_area',3); 
INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) 
	VALUES (1, 12); 
INSERT INTO titemprofile(name,id_group) 
	VALUES ('read_area',3); 
INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) 
	VALUES (1, 13); 
INSERT INTO titemprofile(name,id_group) 
	VALUES ('create_process',3); 
INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) 
	VALUES (1, 14); 
INSERT INTO titemprofile(name,id_group) 
	VALUES ('read_process',3); 
INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) 
	VALUES (1, 15); 
INSERT INTO titemprofile(name,id_group) 
	VALUES ('create_risk',3); 
INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) 
	VALUES (1, 16); 
INSERT INTO titemprofile(name,id_group) 
	VALUES ('read_own_risk',3); 
INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) 
	VALUES (1, 17); 
INSERT INTO titemprofile(name,id_group) 
	VALUES ('read_all_risk',3); 
INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) 
	VALUES (1, 18); 
INSERT INTO titemprofile(name,id_group) 
	VALUES ('treatment_risk',3); 
INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) 
	VALUES (1, 19); 
INSERT INTO titemprofile(name,id_group) 
	VALUES ('create_control',3); 
INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) 
	VALUES (1, 20); 
INSERT INTO titemprofile(name,id_group) 
	VALUES ('read_own_control',3); 
INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) 
	VALUES (1, 21); 
INSERT INTO titemprofile(name,id_group) 
	VALUES ('read_all_control',3); 
INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) 
	VALUES (1, 22); 
INSERT INTO titemprofile(name,id_group) 
	VALUES ('revision_efficacy',3); 
INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) 
	VALUES (1, 23); 
INSERT INTO titemprofile(name,id_group) 
	VALUES ('create_incident',2); 
INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) 
	VALUES (1, 24); 
INSERT INTO titemprofile(name,id_group) 
	VALUES ('read_own_incident',2); 
INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) 
	VALUES (1, 25); 
INSERT INTO titemprofile(name,id_group) 
	VALUES ('read_all_incident',2); 
INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) 
	VALUES (1, 26); 
INSERT INTO titemprofile(name,id_group) 
	VALUES ('create_nonconformity',2); 
INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) 
	VALUES (1, 27); 
INSERT INTO titemprofile(name,id_group) 
	VALUES ('read_own_nonconformity',2); 
INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) 
	VALUES (1, 28); 
INSERT INTO titemprofile(name,id_group) 
	VALUES ('read_all_nonconformity',2); 
INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) 
	VALUES (1, 29); 
INSERT INTO titemprofile(name,id_group) 
	VALUES ('create_asset',3); 
INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) 
	VALUES (1, 30); 
INSERT INTO titemprofile(name,id_group) 
	VALUES ('read_own_asset',3); 
INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) 
	VALUES (1, 31); 
INSERT INTO titemprofile(name,id_group) 
	VALUES ('read_all_asset',3); 
INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) 
	VALUES (1, 32); 
INSERT INTO titemprofile(name,id_group) 
	VALUES ('create_report',2); 
INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) 
	VALUES (1, 33); 
INSERT INTO titemprofile(name,id_group) 
	VALUES ('read_report',2); 
INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) 
	VALUES (1, 34); 
	
INSERT INTO tinstance_impact_money( 
	id_instance, impact_level, value_start, value_end) 
	VALUES (1, '1', 0 , 2000); 
INSERT INTO tinstance_impact_money( 
	id_instance, impact_level, value_start, value_end) 
	VALUES (1, '2', 2001, 10000); 
INSERT INTO tinstance_impact_money( 
	id_instance, impact_level, value_start, value_end) 
	VALUES (1, '3', 10001,0);
INSERT INTO tperson(id_profile, id_instance, language_default, name, 
		detail, email, change_password_next_login, erro_access_login, 
		date_last_change_password, login, password, status) 
	VALUES (1, 1, 'en', 'Security Office', 'The information security responsible', 
		'attik@attik.com.br', 'n', 0, '2018-01-16', 'attik', '7a3f6a974ba195864885b9bc97cb65db3f0974c0', 'a');
		
		
INSERT INTO timpact_type(  
	id_instance, name, default_type, status) VALUES (1, 'security', 'y', 'a');  
INSERT INTO timpact_type(  
	id_instance, name, default_type, status) VALUES (1, 'quality', 'y', 'd');  
INSERT INTO timpact_type(  
	id_instance, name, default_type, status) VALUES (1, 'city', 'y', 'd');
			
			

INSERT INTO timpact(id_impact_type, name, weight, status) VALUES (1,'confidentiality', 1, 'a'); 
INSERT INTO timpact(id_impact_type, name, weight, status) VALUES (1,'integrity', 1, 'a'); 
INSERT INTO timpact(id_impact_type, name, weight, status) VALUES (1,'availability', 1, 'a');
INSERT INTO timpact(id_impact_type, name, weight, status) VALUES (1,'financial', 1, 'a');

INSERT INTO timpact(id_impact_type, name, weight, status) VALUES (2,'customer_satisfaction', 1, 'a'); 
INSERT INTO timpact(id_impact_type, name, weight, status) VALUES (2,'requirement', 1, 'a'); 
INSERT INTO timpact(id_impact_type, name, weight, status) VALUES (2,'budget', 1, 'a');
INSERT INTO timpact(id_impact_type, name, weight, status) VALUES (2,'financial', 1, 'a');

INSERT INTO timpact(id_impact_type, name, weight, status) VALUES (3,'social', 1, 'a'); 
INSERT INTO timpact(id_impact_type, name, weight, status) VALUES (3,'environment', 1, 'a'); 
INSERT INTO timpact(id_impact_type, name, weight, status) VALUES (3,'economic', 1, 'a');
INSERT INTO timpact(id_impact_type, name, weight, status) VALUES (3,'financial', 1, 'a');

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
		DROP table tmp_control;
		
		
		
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
		DROP table tmp_control;
		
		
		
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
			SELECT t.section,c.id,SUBSTRING(t.control,1,200) FROM tmp_control t, tcategory_best_pratice c, tsection_best_pratice s WHERE  
				(REPLACE(SUBSTRING(c.item FROM 1 FOR 4),'.','')) = (REPLACE(SUBSTRING(t.section FROM 1 FOR 4),'.','')) 
				AND c.id_section = s.id AND s.id_best_pratice = 3;
		 
		DROP table tmp_section; 
		DROP table tmp_category; 
		DROP table tmp_control;
		
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
			SELECT t.section,c.id,SUBSTRING(t.control,1,200) FROM tmp_control t, tcategory_best_pratice c, tsection_best_pratice s WHERE  
				(REPLACE(SUBSTRING(c.item FROM 1 FOR 4),'.','')) = (REPLACE(SUBSTRING(t.section FROM 1 FOR 4),'.','')) 
				AND c.id_section = s.id AND s.id_best_pratice = 4;
		 
		DROP table tmp_section; 
		DROP table tmp_category; 
		DROP table tmp_control;

		CREATE TABLE tmp_section( 
		  id serial primary key, 
		  section varchar(10000), 
		  control varchar (10000) 
		); 
		 
		COPY tmp_section 
		( 
		  section, 
		  control 
		) 
		FROM '/var/www/attikgrc/install/13709/sections.csv' 
		DELIMITER '@' 
		CSV HEADER; 
		 
		CREATE TABLE tmp_category( 
		  id serial primary key, 
		  section varchar(10000), 
		  control varchar (10000) 
		); 
		 
		COPY tmp_category 
		( 
		  section, 
		  control 
		) 
		FROM '/var/www/attikgrc/install/13709/category.csv' 
		DELIMITER '@' 
		CSV HEADER; 
		 
		CREATE TABLE tmp_control( 
		  id serial primary key, 
		  section varchar(10000), 
		  control varchar (10000) 
		); 
		 
		COPY tmp_control 
		( 
		  section, 
		  control 
		) 
		FROM '/var/www/attikgrc/install/13709/control.csv' 
		DELIMITER '@' 
		CSV HEADER; 
		 
		INSERT INTO tbest_pratice( 
			id_instance, name, detail, status) 
			VALUES (1, 'Lei 13.709:2018', 'Lei Geral de Proteção de Dados Pessoais (LGPD)', 'a'); 
			 
		INSERT INTO tsection_best_pratice( 
			item, id_best_pratice, name) 
			SELECT s.section,5,s.control FROM tmp_section s;  
		 
		INSERT INTO tcategory_best_pratice( 
			item, id_section, name) 
			SELECT c.section,s.id,c.control FROM tmp_category c, tsection_best_pratice s WHERE  
				s.item = (REPLACE(SUBSTRING(c.section FROM 1 FOR 2),'.','')) AND 
				s.id_best_pratice = 5; 
		 
		INSERT INTO tcontrol_best_pratice( 
			item, id_category, name) 
			SELECT t.section,c.id,SUBSTRING(t.control,1,200) FROM tmp_control t, tcategory_best_pratice c, tsection_best_pratice s WHERE  
				(REPLACE(SUBSTRING(c.item FROM 1 FOR 4),'.','')) = (REPLACE(SUBSTRING(t.section FROM 1 FOR 4),'.','')) 
				AND c.id_section = s.id AND s.id_best_pratice = 5;
		 
		DROP table tmp_section; 
		DROP table tmp_category; 
		DROP table tmp_control;
		