CREATE DATABASE attik_grc
  WITH OWNER = arm_user
--       ENCODING = 'UTF8'
		 TABLESPACE = pg_default
--       LC_COLLATE = 'pt_BR.UTF-8'
--       LC_CTYPE = 'pt_BR.UTF-8'
       CONNECTION LIMIT = -1;
--GRANT ALL ON DATABASE attik_grc TO arm_user;

COMMENT ON DATABASE attik_grc
  IS 'Data base of @ttik GRC Application.';
-- --------------------------------------------------------