-- START GENERAL SESSION
-- Instance table
CREATE TABLE IF NOT EXISTS tinstance (
	id serial PRIMARY KEY,
	name varchar(255) NOT NULL,
	detail varchar(1000) NULL,
	status char(1) NOT NULL,
	limit_user integer NOT NULL,
	language_default char(2) NOT NULL,
	acceptance_risk_level real NOT NULL,
	limit_error_login integer NOT NULL,
	max_password_lifetime integer NOT NULL,
	min_password_lifetime integer NOT NULL,
	time_change_temp_password integer NOT NULL,
	close_system char(1) NOT NULL,
	last_update date NULL DEFAULT CURRENT_DATE,
	enable_delete_cascade char(1) NOT NULL,
	logo_instance varchar(1000)
);

-- Instance impact money table
CREATE TABLE IF NOT EXISTS tinstance_impact_money (
	id_instance integer NOT NULL REFERENCES tinstance(id),
	impact_level char(1) NOT NULL,
	value_start real NOT NULL,
	value_end real NOT NULL,
	PRIMARY KEY(id_instance,impact_level)
);

-- Operation history table
CREATE TABLE IF NOT EXISTS thistory (
	id serial PRIMARY KEY,
	id_instance integer NOT NULL REFERENCES tinstance(id),
	code varchar(10),
	detail varchar(500),
	execution_time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	name_person varchar(255) NOT NULL -- When have procurator, thouth the name with procurator
);

-- END GENERAL SESSION

-- START PERSON SESSION

-- Group of item Profile table
CREATE TABLE IF NOT EXISTS tgroup_itemprofile (
	id serial PRIMARY KEY,
	name varchar(255) NOT NULL
);

-- Item Profile table
CREATE TABLE IF NOT EXISTS titemprofile (
	id serial PRIMARY KEY,
	id_group integer NOT NULL REFERENCES tgroup_itemprofile(id),
	name varchar(255) NOT NULL
);

-- Profile table
CREATE TABLE IF NOT EXISTS tprofile (
	id serial PRIMARY KEY,
	id_instance integer NOT NULL REFERENCES tinstance(id),
	name varchar(255) NOT NULL
);

-- Associate table between Item Profile and Profile
CREATE TABLE IF NOT EXISTS taprofile_itemprofile (
	id_profile integer REFERENCES tprofile(id),
	id_itemprofile integer REFERENCES titemprofile(id),
	PRIMARY KEY(id_profile,id_itemprofile)
);


-- Person table
CREATE TABLE IF NOT EXISTS tperson (
	id serial PRIMARY KEY,
	id_profile integer NULL REFERENCES tprofile(id),
	id_instance integer NOT NULL REFERENCES tinstance(id),
	language_default char(2) NOT NULL,
	name varchar(255) NOT NULL,
	detail varchar(500) NULL,
	email varchar(100) NOT NULL,
	change_password_next_login char(1) NOT NULL,
	erro_access_login integer NOT NULL,
	date_last_change_password date NOT NULL,
	login varchar(30) NOT NULL,
	password varchar(41) NOT NULL,
	status char(1) NOT NULL
);

-- Especial system users
CREATE TABLE IF NOT EXISTS tespecial_person (
	id_instance integer NOT NULL REFERENCES tinstance(id),
	id_person integer NOT NULL REFERENCES tperson(id),
	name varchar(10) NOT NULL,
	PRIMARY KEY(id_instance,id_person,name)
);

-- Procurator table
CREATE TABLE IF NOT EXISTS tprocurator (
	id_person integer NOT NULL REFERENCES tperson(id),
	id_procurator integer NOT NULL REFERENCES tperson(id),
	date_start date NOT NULL,
	date_end date NOT NULL,
	status char(1) NOT NULL
);

-- Task Workflow table
CREATE TABLE IF NOT EXISTS ttask_workflow (
	id serial PRIMARY KEY,
	name varchar(255) NOT NULL,
	detail varchar(1000) NULL,
	action varchar(1000) NULL,
	id_instance integer NOT NULL REFERENCES tinstance(id),
	id_creator integer NULL REFERENCES tperson(id),
	id_responsible integer NOT NULL REFERENCES tperson(id),
	id_approver integer NOT NULL REFERENCES tperson(id),
	source varchar(20) NULL,
	status char(1) NOT NULL,
	prevision_date date NULL,
	execution_date date NULL,
	creation_date date NOT NULL DEFAULT CURRENT_DATE,
	attachment_file varchar(1000) NULL
);

-- Table Task's files
CREATE TABLE IF NOT EXISTS ttask_workflow_file (
	id serial PRIMARY KEY,
	id_task integer NOT NULL REFERENCES ttask_workflow(id),
	name varchar(50) NOT NULL,
	content text NOT NULL
);

-- END PERSON SESSION

-- START RISK SESSION

-- Area table
CREATE TABLE IF NOT EXISTS tarea (
	id serial PRIMARY KEY,
	id_responsible integer NULL REFERENCES tperson(id),
	id_instance integer NOT NULL REFERENCES tinstance(id),
	name varchar(255) NOT NULL,
	detail varchar(500) NULL,
	relevancy integer NULL,
	status char(1) NOT NULL
);

-- Process table
CREATE TABLE IF NOT EXISTS tprocess (
	id serial PRIMARY KEY,
	id_responsible integer NOT NULL REFERENCES tperson(id),
	id_risk_responsible integer NOT NULL REFERENCES tperson(id),
	id_area integer NOT NULL REFERENCES tarea(id),
	name varchar(255) NOT NULL,
	detail varchar(500) NULL,
	relevancy integer NOT NULL,
	status char(1) NOT NULL
);

-- Impact table
CREATE TABLE IF NOT EXISTS timpact_type (
	id serial PRIMARY KEY,
	id_instance integer NOT NULL REFERENCES tinstance(id),
	name varchar(255) NOT NULL,
	default_type char(1) NULL,
	status char(1) NOT NULL
);

-- Impact table
CREATE TABLE IF NOT EXISTS timpact (
	id serial PRIMARY KEY,
	id_impact_type integer NOT NULL REFERENCES timpact_type(id),
	name varchar(255) NOT NULL,
	personal_name varchar(255) NULL,
	weight integer NOT NULL,
	status char(1) NOT NULL
);

-- Asset table
CREATE TABLE IF NOT EXISTS tasset (
	id serial PRIMARY KEY,
	id_process integer NOT NULL REFERENCES tprocess(id),
	name varchar(255) NOT NULL,
	detail varchar(500) NULL,
	status char(1) NOT NULL
);

-- Associate table between Asset and Impact
CREATE TABLE IF NOT EXISTS taasset_impact (
	id_asset integer REFERENCES tasset(id),
	id_impact integer REFERENCES timpact(id),
	value real NOT NULL,
	PRIMARY KEY(id_asset,id_impact)
);

-- Associate table between Asset and Process
CREATE TABLE IF NOT EXISTS taasset_process (
	id_asset integer REFERENCES tasset(id),
	id_process integer REFERENCES tprocess(id),
	PRIMARY KEY(id_asset,id_process)
);

-- Risk table
CREATE TABLE IF NOT EXISTS trisk (
	id serial PRIMARY KEY,
	id_process integer NOT NULL REFERENCES tprocess(id),
	id_impact_type integer NOT NULL REFERENCES timpact_type(id),
	name varchar(255) NOT NULL,
	detail varchar(500) NULL,
	impact varchar(500) NULL,
	rlabel char(1) NULL,
	risk_factor real NULL,
	risk_residual real NULL,
	probability char(1) NULL,
	probability_justification varchar(500) NULL,
	status char(1) NOT NULL,
	creation_time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Control table
CREATE TABLE IF NOT EXISTS tcontrol (
	id serial PRIMARY KEY,
	name varchar(255) NOT NULL,
	detail varchar(500) NULL,
	id_process integer NOT NULL REFERENCES tprocess(id),
	goal real NULL,
	metric varchar(300) NULL,
	metric_detail varchar(500) NULL,
	implementation_date date NULL,
	enable_revision char(1) NULL,
	apply_revision_from date NULL,
	scheduling_day integer NULL,
	scheduling_month integer NULL,
	scheduling_weekday integer NULL,
	deadline_revision integer NULL,
	status char(1) NOT NULL
);

-- Best Pratice table
CREATE TABLE IF NOT EXISTS tbest_pratice (
	id serial PRIMARY KEY,
	id_instance integer NOT NULL REFERENCES tinstance(id),
	name varchar(255) NOT NULL,
	detail varchar(1000) NULL,
	status char(1) NOT NULL,
	UNIQUE(id_instance,name)
);

-- Section Best Pratice table
CREATE TABLE IF NOT EXISTS tsection_best_pratice (
	id serial PRIMARY KEY,
	item varchar(20) NOT NULL,
	id_best_pratice integer NOT NULL REFERENCES tbest_pratice(id),
	name varchar(1000) NOT NULL,
	UNIQUE(item,id_best_pratice)
);

-- Section Best Pratice table
CREATE TABLE IF NOT EXISTS tcategory_best_pratice (
	id serial PRIMARY KEY,
	item varchar(20) NOT NULL,
	id_section integer NOT NULL REFERENCES tsection_best_pratice(id),
	name varchar(1000) NOT NULL,
	UNIQUE(item,id_section)
);

-- Item Best Pratice table
CREATE TABLE IF NOT EXISTS tcontrol_best_pratice (
	id serial PRIMARY KEY,
	item varchar(20) NOT NULL,
	id_category integer NOT NULL REFERENCES tcategory_best_pratice(id),
	name varchar(200) NOT NULL,
	detail varchar(9000) NULL,
	UNIQUE(item,id_category)
);

-- Associate table between Control and Item Best Pratice
CREATE TABLE IF NOT EXISTS tacontrol_best_pratice (
	id_control integer REFERENCES tcontrol(id),
	id_control_best_pratice integer REFERENCES tcontrol_best_pratice(id),
	PRIMARY KEY(id_control,id_control_best_pratice)
);

-- Associate table between Risk and Impact
CREATE TABLE IF NOT EXISTS tarisk_impact (
	id_risk integer REFERENCES trisk(id),
	id_impact integer REFERENCES timpact(id),
	value real NOT NULL,
	justification varchar(255) NULL,
	PRIMARY KEY(id_risk,id_impact)
);

-- Associate table between Risk and Control
CREATE TABLE IF NOT EXISTS tarisk_control (
	id_risk integer REFERENCES trisk(id),
	id_control integer REFERENCES tcontrol(id),
	probability integer NULL,
	probability_justification varchar(255) NULL,
	PRIMARY KEY(id_risk,id_control)
);

-- Associate table between Risk, Control and Impact
CREATE TABLE IF NOT EXISTS tarisk_control_impact (
	id_risk integer REFERENCES trisk(id),
	id_control integer REFERENCES tcontrol(id),
	id_impact integer REFERENCES timpact(id),
	value real NULL,
	justification varchar(255) NULL,
	PRIMARY KEY(id_risk,id_control,id_impact)
);

-- Revision Control table
CREATE TABLE IF NOT EXISTS trevision_control (
	id_control integer REFERENCES tcontrol(id),
	prevision_date date NOT NULL,
	execution_date date NULL,
	id_responsible integer NOT NULL REFERENCES tperson(id),
	result real NULL,
	justification varchar(500) NULL,
	PRIMARY KEY(id_control,prevision_date)
);

-- Associate table between Risk and Task
CREATE TABLE IF NOT EXISTS tarisk_task (
	id_risk integer REFERENCES trisk(id),
	id_task integer REFERENCES ttask_workflow(id),
	PRIMARY KEY(id_risk,id_task)
);

-- Associate table between Risk and Task
CREATE TABLE IF NOT EXISTS tacontrol_task (
	id_control integer REFERENCES tcontrol(id),
	id_task integer REFERENCES ttask_workflow(id),
	PRIMARY KEY(id_control,id_task)
);

-- END RISK SESSION

-- START CONTINOUS IMPROVIMENT SESSION

-- Control table
CREATE TABLE IF NOT EXISTS tincident (
	id serial PRIMARY KEY,
	id_instance integer NOT NULL REFERENCES tinstance(id),
	id_responsible integer NULL REFERENCES tperson(id),
	id_person_register integer NULL REFERENCES tperson(id),
	name varchar(255) NOT NULL,
	detail varchar(1000) NULL,
	root_cause varchar(500) NULL,
	evidence varchar(500) NULL,
	creation_date date NOT NULL DEFAULT CURRENT_DATE,
	execution_date date NULL,
	status char(1) NOT NULL
);

-- Associate table between Incident and Task according response type (initial response or solution response)
CREATE TABLE IF NOT EXISTS tainicident_response_task (
	id_incident integer REFERENCES tincident(id),
	id_task integer REFERENCES ttask_workflow(id),
    response_type char(1) NOT NULL,
	PRIMARY KEY(id_incident,id_task)
);

-- Associate table between Incident and Risk
CREATE TABLE IF NOT EXISTS taincident_risk (
	id_incident integer REFERENCES tincident(id),
	id_risk integer REFERENCES trisk(id),
	PRIMARY KEY(id_risk,id_incident)
);

-- Table Incident's files
CREATE TABLE IF NOT EXISTS tincident_file (
	id serial PRIMARY KEY,
	id_incident integer NOT NULL REFERENCES tincident(id),
	name varchar(50) NOT NULL,
	content text NOT NULL
);

-- Nonconformity
CREATE TABLE IF NOT EXISTS tnonconformity (
	id serial PRIMARY KEY,
	id_instance integer NOT NULL REFERENCES tinstance(id),
	id_responsible integer NULL REFERENCES tperson(id),
	id_person_register integer NULL REFERENCES tperson(id),
	name varchar(255) NOT NULL,
	detail varchar(1000) NULL,
	root_cause varchar(500) NULL,
	creation_date date NOT NULL DEFAULT CURRENT_DATE,
	execution_date date NULL,
	status char(1) NOT NULL
);


-- Table Nonconformity's files
CREATE TABLE IF NOT EXISTS tnonconformity_file (
	id serial PRIMARY KEY,
	id_nonconformity integer NOT NULL REFERENCES tnonconformity(id),
	name varchar(50) NOT NULL,
	content text NOT NULL
);

-- Associate table between Nonconformity and Task according response type (initial response or solution response)
CREATE TABLE IF NOT EXISTS tanonconformity_response_task (
	id_nonconformity integer REFERENCES tnonconformity(id),
	id_task integer REFERENCES ttask_workflow(id),
    response_type char(1) NOT NULL,
	PRIMARY KEY(id_nonconformity,id_task)
);

-- Associate table between Nonconformity and Control
CREATE TABLE IF NOT EXISTS tanonconformity_control (
	id_nonconformity integer REFERENCES tnonconformity(id),
	id_control integer REFERENCES tcontrol(id),
	PRIMARY KEY(id_nonconformity,id_control)
);

-- Associate table between Nonconformity and Process
CREATE TABLE IF NOT EXISTS tanonconformity_process (
	id_nonconformity integer REFERENCES tnonconformity(id),
	id_process integer REFERENCES tprocess(id),
	PRIMARY KEY(id_nonconformity,id_process)
);

-- END CONTINOUS IMPROVIMENT SESSION

-- START PROJECT SESSION

-- Project table
CREATE TABLE IF NOT EXISTS tproject (
	id serial PRIMARY KEY,
	id_instance integer NOT NULL REFERENCES tinstance(id),
	name varchar(255) NOT NULL,
	detail varchar(1000) NULL,
	id_sponsor integer NULL REFERENCES tperson(id),
	id_manager integer NULL REFERENCES tperson(id),
	budget real NULL,
	deadline date NULL,
	id_creator integer NOT NULL REFERENCES tperson(id),
	creation_date date NOT NULL,
	id_best_pratices integer NOT NULL REFERENCES tbest_pratice(id),
	status char(1) NOT NULL
);

-- Associate table between Project, Item Best Pratice and Task
CREATE TABLE IF NOT EXISTS taproject_control_best_pratice_task (
	id_project integer REFERENCES tproject(id),
	id_control_best_pratice integer REFERENCES tcontrol_best_pratice(id),
	id_task integer REFERENCES ttask_workflow(id),
	id_instance integer NOT NULL REFERENCES tinstance(id),
	PRIMARY KEY(id_project,id_control_best_pratice,id_task,id_instance)
);
-- END PROJECT SESSION
-- START REPORT SESSION
-- Report table
CREATE TABLE IF NOT EXISTS treport (
	id serial PRIMARY KEY,
	id_instance integer NOT NULL REFERENCES tinstance(id),
	name varchar(50) NOT NULL,
	version integer NOT NULL,
	created_by integer NOT NULL REFERENCES tperson(id),
	creation_date date NOT NULL,
	history varchar(1000) NOT NULL,
	status char(1) NOT NULL
);
-- Item Report table
CREATE TABLE IF NOT EXISTS titem_report (
	id serial PRIMARY KEY,
	id_report integer NOT NULL REFERENCES treport(id),
	content text NULL,
	justification varchar(500) NULL,
	status char(1) NOT NULL
);
-- END REPORT SESSION

-- Statistic table
CREATE TABLE IF NOT EXISTS tstatistic (
	id serial PRIMARY KEY,
	id_instance integer NOT NULL REFERENCES tinstance(id),
	risks integer NULL,
	controls integer NULL,
	incidents integer NULL,
	nonconformity integer NULL,
	rincident_with_risk integer NULL,
	reference_date date NOT NULL
);
-- END STATISTIC SESSION
-- --------------------------------------------------------

-- Add adition field   - BE IN SCRIPT CREATE SESSION
-- ALTER TABLE tinstance ADD COLUMN default_approver integer NOT NULL REFERENCES tperson(id);


/*
GRANT ALL ON TABLE public.tacontrol_best_pratice TO arm_user;
GRANT ALL ON TABLE public.taprofile_itemprofile TO arm_user;
GRANT ALL ON TABLE public.tarisk_control TO arm_user;
GRANT ALL ON TABLE public.tarisk_control_impact TO arm_user;
GRANT ALL ON TABLE public.tarisk_impact TO arm_user;
GRANT ALL ON TABLE public.tbest_pratice TO arm_user;
GRANT ALL ON TABLE public.tcontrol_best_pratice TO arm_user;
GRANT ALL ON TABLE public.tcategory_best_pratice TO arm_user;
GRANT ALL ON TABLE public.tsection_best_pratice TO arm_user;
GRANT ALL ON TABLE public.tcontrol TO arm_user;
GRANT ALL ON TABLE public.tarea TO arm_user;
GRANT ALL ON TABLE public.thistory TO arm_user;
GRANT ALL ON TABLE public.timpact TO arm_user;
GRANT ALL ON TABLE public.tinstance TO arm_user;
GRANT ALL ON TABLE public.tinstance_impact_money TO arm_user;
GRANT ALL ON TABLE public.titemprofile TO arm_user;
GRANT ALL ON TABLE public.tnonconformity TO arm_user;
GRANT ALL ON TABLE public.tperson TO arm_user;
GRANT ALL ON TABLE public.tprocess TO arm_user;
GRANT ALL ON TABLE public.tprocurator TO arm_user;
GRANT ALL ON TABLE public.tgroup_itemprofile TO arm_user;
GRANT ALL ON TABLE public.tprofile TO arm_user;
GRANT ALL ON TABLE public.trevision_control TO arm_user;
GRANT ALL ON TABLE public.trisk TO arm_user;
GRANT ALL ON TABLE public.ttask_workflow TO arm_user;
GRANT ALL ON TABLE public.tproject TO arm_user;
GRANT ALL ON TABLE public.taproject_control_best_pratice_task TO arm_user;
GRANT ALL ON TABLE public.tincident TO arm_user;
GRANT ALL ON TABLE public.taincident_risk TO arm_user;
GRANT ALL ON TABLE public.tarisk_task TO arm_user;
GRANT ALL ON TABLE public.tacontrol_task TO arm_user;
GRANT ALL ON TABLE public.tainicident_response_task TO arm_user;
GRANT ALL ON TABLE public.tanonconformity_response_task TO arm_user;
GRANT ALL ON TABLE public.tanonconformity_control TO arm_user;
GRANT ALL ON TABLE public.tanonconformity_process TO arm_user;
GRANT ALL ON TABLE public.tasset TO arm_user;
GRANT ALL ON TABLE public.taasset_impact TO arm_user;
GRANT ALL ON TABLE public.taasset_process TO arm_user;
GRANT ALL ON TABLE public.treport TO arm_user;
GRANT ALL ON TABLE public.titem_report TO arm_user;
GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO arm_user;
GRANT ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA public TO arm_user;

GRANT CONNECT ON DATABASE portal TO arm_user;
ALTER ROLE arm_user WITH LOGIN;
GRANT USAGE ON SCHEMA public TO arm_user WITH GRANT OPTION;

ALTER DEFAULT PRIVILEGES IN SCHEMA public
GRANT INSERT, SELECT, UPDATE, DELETE, REFERENCES ON TABLES TO arm_user;

GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA public TO arm_user;
ALTER DEFAULT PRIVILEGES IN SCHEMA public GRANT USAGE, SELECT ON SEQUENCES TO arm_user;
GRANT USAGE ON SCHEMA public TO arm_user WITH GRANT OPTION;

ALTER DEFAULT PRIVILEGES IN SCHEMA public
GRANT INSERT, SELECT, UPDATE, DELETE, REFERENCES ON TABLES TO arm_user;*/

GRANT ALL ON SEQUENCE public.tarea_id_seq TO arm_user;

GRANT ALL ON SEQUENCE public.tasset_id_seq TO arm_user;

GRANT ALL ON SEQUENCE public.tbest_pratice_id_seq TO arm_user;

GRANT ALL ON SEQUENCE public.tcategory_best_pratice_id_seq TO arm_user;

GRANT ALL ON SEQUENCE public.tcontrol_best_pratice_id_seq TO arm_user;

GRANT ALL ON SEQUENCE public.tcontrol_id_seq TO arm_user;

GRANT ALL ON SEQUENCE public.tgroup_itemprofile_id_seq TO arm_user;

GRANT ALL ON SEQUENCE public.thistory_id_seq TO arm_user;

GRANT ALL ON SEQUENCE public.timpact_type_id_seq TO arm_user;

GRANT ALL ON SEQUENCE public.timpact_id_seq TO arm_user;

GRANT ALL ON SEQUENCE public.tincident_id_seq TO arm_user;

GRANT ALL ON SEQUENCE public.tinstance_id_seq TO arm_user;

GRANT ALL ON SEQUENCE public.titem_report_id_seq TO arm_user;

GRANT ALL ON SEQUENCE public.titemprofile_id_seq TO arm_user;

GRANT ALL ON SEQUENCE public.tnonconformity_id_seq TO arm_user;

GRANT ALL ON SEQUENCE public.tperson_id_seq TO arm_user;

GRANT ALL ON SEQUENCE public.tprocess_id_seq TO arm_user;

GRANT ALL ON SEQUENCE public.tprofile_id_seq TO arm_user;

GRANT ALL ON SEQUENCE public.tproject_id_seq TO arm_user;

GRANT ALL ON SEQUENCE public.treport_id_seq TO arm_user;

GRANT ALL ON SEQUENCE public.trisk_id_seq TO arm_user;

GRANT ALL ON SEQUENCE public.tsection_best_pratice_id_seq TO arm_user;

GRANT ALL ON SEQUENCE public.ttask_workflow_id_seq TO arm_user;

GRANT ALL ON SEQUENCE public.tstatistic_id_seq TO arm_user;

GRANT ALL ON SEQUENCE public.tincident_file_id_seq TO arm_user;

GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES ON TABLE public.taasset_impact TO arm_user;

GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES ON TABLE public.taasset_process TO arm_user;

GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES ON TABLE public.tacontrol_best_pratice TO arm_user;

GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES ON TABLE public.tacontrol_task TO arm_user;

GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES ON TABLE public.taincident_risk TO arm_user;

GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES ON TABLE public.tainicident_response_task TO arm_user;

GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES ON TABLE public.tanonconformity_control TO arm_user;

GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES ON TABLE public.tanonconformity_process TO arm_user;

GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES ON TABLE public.tanonconformity_response_task TO arm_user;

GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES ON TABLE public.taprofile_itemprofile TO arm_user;

GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES ON TABLE public.taproject_control_best_pratice_task TO arm_user;

GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES ON TABLE public.tarea TO arm_user;

GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES ON TABLE public.tarisk_control TO arm_user;

GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES ON TABLE public.tarisk_control_impact TO arm_user;

GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES ON TABLE public.tarisk_impact TO arm_user;

GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES ON TABLE public.tarisk_task TO arm_user;

GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES ON TABLE public.tasset TO arm_user;

GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES ON TABLE public.tbest_pratice TO arm_user;

GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES ON TABLE public.tcategory_best_pratice TO arm_user;

GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES ON TABLE public.tcontrol TO arm_user;

GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES ON TABLE public.tcontrol_best_pratice TO arm_user;

GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES ON TABLE public.tgroup_itemprofile TO arm_user;

GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES ON TABLE public.thistory TO arm_user;

GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES ON TABLE public.timpact_type TO arm_user;

GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES ON TABLE public.timpact TO arm_user;

GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES ON TABLE public.tincident TO arm_user;

GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES ON TABLE public.tinstance TO arm_user;

GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES ON TABLE public.tinstance_impact_money TO arm_user;

GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES ON TABLE public.titem_report TO arm_user;

GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES ON TABLE public.titemprofile TO arm_user;

GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES ON TABLE public.tnonconformity TO arm_user;

GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES ON TABLE public.tperson TO arm_user;

GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES ON TABLE public.tprocess TO arm_user;

GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES ON TABLE public.tespecial_person TO arm_user;

GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES ON TABLE public.tprocurator TO arm_user;

GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES ON TABLE public.tprofile TO arm_user;

GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES ON TABLE public.tproject TO arm_user;

GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES ON TABLE public.treport TO arm_user;

GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES ON TABLE public.trevision_control TO arm_user;

GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES ON TABLE public.trisk TO arm_user;

GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES ON TABLE public.tsection_best_pratice TO arm_user;

GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES ON TABLE public.ttask_workflow TO arm_user;

GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES ON TABLE public.tstatistic TO arm_user;

GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES ON TABLE public.tincident_file TO arm_user;

GRANT ALL ON SEQUENCE public.tincident_file_id_seq TO arm_user;
GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES ON TABLE public.tincident_file TO arm_user;

GRANT ALL ON SEQUENCE public.tnonconformity_file_id_seq TO arm_user;
GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES ON TABLE public.tnonconformity_file TO arm_user;

GRANT ALL ON SEQUENCE public.ttask_workflow_file_id_seq TO arm_user;
GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES ON TABLE public.ttask_workflow_file TO arm_user;
