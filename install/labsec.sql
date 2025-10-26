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
-- Login table
CREATE TABLE IF NOT EXISTS tperson (
	id serial PRIMARY KEY,
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