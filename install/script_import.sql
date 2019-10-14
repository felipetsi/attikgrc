INSERT INTO tinstance(name, detail, status, limit_user, language_default, acceptance_risk_level, 
                             limit_error_login, max_password_lifetime, min_password_lifetime,
					  time_change_temp_password,close_system,enable_delete_cascade)
	VALUES ('attik', '@ttik - CNPJ: 29.400.816/0001-94', 'a', 10, 'en', 3, 5, 90, 0, 1,'n','n');


INSERT INTO tgroup_itemprofile (name) VALUES('configuration');
INSERT INTO tgroup_itemprofile (name) VALUES('improvement');
INSERT INTO tgroup_itemprofile (name) VALUES('riskmanager');
	
INSERT INTO tprofile(id_instance, name)
	VALUES (1, 'Security Office');
	
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


INSERT INTO timpact(
	id_instance, name, status) VALUES (1, 'confidentiality', 'a');
    
INSERT INTO timpact(
	id_instance, name, status) VALUES (1, 'integrity', 'a');
    
INSERT INTO timpact(
	id_instance, name, status) VALUES (1, 'availability', 'a');
	
INSERT INTO timpact(
	id_instance, name, status) VALUES (1, 'financial', 'a');


INSERT INTO tperson(id_profile, id_instance, language_default, name,
                           detail, email, change_password_next_login, erro_access_login, 
                           date_last_change_password, login, password, status)
	VALUES (1, 1, 'en', 'Security Office', 'The information security responsible', 
            'attik@attik.com.br', 'n', 0, '2018-01-16', 'admin', '7a3f6a974ba195864885b9bc97cb65db3f0974c0', 'a');

-- Add adition field
ALTER TABLE tinstance ADD COLUMN default_approver integer NOT NULL REFERENCES tperson(id) DEFAULT 1;


INSERT INTO tinstance_impact_money(
	id_instance, impact_level, value_start, value_end)
	VALUES (1, '1', 0 , 2000);
INSERT INTO tinstance_impact_money(
	id_instance, impact_level, value_start, value_end)
	VALUES (1, '2', 2001, 10000);
INSERT INTO tinstance_impact_money(
	id_instance, impact_level, value_start, value_end)
	VALUES (1, '3', 10001,0);
    
    

-- 	IMPORT BEST PRATICES
-- sections or clauses
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
FROM '/home/felipetsi/bp/9001/7_9001_sections.csv'
DELIMITER ';'
CSV HEADER;

-- categories
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
FROM '/home/felipetsi/bp/9001/28_9001_category.csv'
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
FROM '/home/felipetsi/bp/9001/61_9001_control.csv'
DELIMITER ';'
CSV HEADER;




/*UPDATE tmp_category
	SET section='A12.4'
	WHERE control like 'Logging and monitoring'
	
	 
UPDATE tcategory_best_pratice
	SET id_section=14
	WHERE(REPLACE(SUBSTRING(item FROM 1 FOR 3),'.','')) = 'A18'*/
	 
INSERT INTO tbest_pratice(
	id_instance, name, detail, status)
	VALUES (1, 'NBR ISO/IEC 27001', 'ABNT NBR ISO/IEC 27001', 'a');
	
-- Import from tables tmp
INSERT INTO tsection_best_pratice(
	item, id_best_pratice, name)
	SELECT s.section,1,s.control FROM tmp_section s 

INSERT INTO tcategory_best_pratice(
	item, id_section, name)
	SELECT c.section,s.id,c.control FROM tmp_category c, tsection_best_pratice s WHERE 
    	s.item = (REPLACE(SUBSTRING(c.section FROM 1 FOR 3),'.','')) AND 
		s.id_best_pratice = 1
        
INSERT INTO tcontrol_best_pratice(
	item, id_category, name,detail)
	SELECT t.section,c.id,substring(t.control,0,100),t.control FROM
	tmp_control t, tcategory_best_pratice c, tsection_best_pratice s WHERE 
    	(REPLACE(SUBSTRING(c.item FROM 1 FOR 4),'.','')) = 
		(REPLACE(SUBSTRING(t.section FROM 1 FOR 4),'.','')) AND
		c.id_section = s.id AND
		s.id_best_pratice = 1
	 