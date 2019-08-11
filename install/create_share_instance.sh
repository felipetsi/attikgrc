#!/bin/bash
clear
# Create new instance
### General parameters
	server="localhost";
	userDB="postgres";
	portDB="5432";
	LOGFILE="/var/log/attikgrc/instance_created.log";
	export PGPASSWORD="postgres";
	INSTANCE_DB_NAME="attikgrc";	

echo "#### Enter the instance data:"
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
			
		SQL=" INSERT INTO tprofile(id_instance, name) VALUES ($ID_INST, 'Security Office');"
		psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB -c "$SQL" >> $LOGFILE;

		SQL="SELECT id FROM tprofile WHERE id_instance = $ID_INST AND name LIKE 'Security Office';"
		psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB -t -c "$SQL" |
		while read PROFILE 
		do
			ID_PROF=`echo $PROFILE | awk -F'|' '{print $1}'| sed 's/ //g'`;
			
			if [ ! -z $ID_PROF ]; 
				then
				SQL=" \
				INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
					VALUES ($ID_PROF, 1); \
				INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
					VALUES ($ID_PROF, 2); \
				INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
					VALUES ($ID_PROF, 3); \
				INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
					VALUES ($ID_PROF, 4); \
				INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
					VALUES ($ID_PROF, 5); \
				INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
					VALUES ($ID_PROF, 6); \
				INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
					VALUES ($ID_PROF, 7); \
				INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
					VALUES ($ID_PROF, 8); \
				INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
					VALUES ($ID_PROF, 9); \
				INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
					VALUES ($ID_PROF, 10); \
				INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
					VALUES ($ID_PROF, 11); \
				INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
					VALUES ($ID_PROF, 12); \
				INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
					VALUES ($ID_PROF, 13); \
				INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
					VALUES ($ID_PROF, 14); \
				INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
					VALUES ($ID_PROF, 15); \
				INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
					VALUES ($ID_PROF, 16); \
				INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
					VALUES ($ID_PROF, 17); \
				INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
					VALUES ($ID_PROF, 18); \
				INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
					VALUES ($ID_PROF, 19); \
				INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
					VALUES ($ID_PROF, 20); \
				INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
					VALUES ($ID_PROF, 21); \
				INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
					VALUES ($ID_PROF, 22); \
				INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
					VALUES ($ID_PROF, 23); \
				INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
					VALUES ($ID_PROF, 24); \
				INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
					VALUES ($ID_PROF, 25); \
				INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
					VALUES ($ID_PROF, 26); \
				INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
					VALUES ($ID_PROF, 27); \
				INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
					VALUES ($ID_PROF, 28); \
				INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
					VALUES ($ID_PROF, 29); \
				INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
					VALUES ($ID_PROF, 30); \
				INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
					VALUES ($ID_PROF, 31); \
				INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
					VALUES ($ID_PROF, 32); \
				INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
					VALUES ($ID_PROF, 33); \
				INSERT INTO taprofile_itemprofile(id_profile, id_itemprofile) \
					VALUES ($ID_PROF, 34); \
					\
				INSERT INTO tinstance_impact_money( \
					id_instance, impact_level, value_start, value_end) \
					VALUES ($ID_INST, '1', 0 , 2000); \
				INSERT INTO tinstance_impact_money( \
					id_instance, impact_level, value_start, value_end) \
					VALUES ($ID_INST, '2', 2001, 10000); \
				INSERT INTO tinstance_impact_money( \
					id_instance, impact_level, value_start, value_end) \
					VALUES ($ID_INST, '3', 10001,0); \
				INSERT INTO tperson(id_profile, id_instance, language_default, name, \
                           detail, email, change_password_next_login, erro_access_login, \
                           date_last_change_password, login, password, status) \
				VALUES ($ID_PROF, $ID_INST, 'en', 'Security Office', 'The information security responsible', \
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

				## 	IMPORT BEST PRATICES
				SQL="INSERT INTO tbest_pratice(id_instance,name,detail,status) \
				SELECT $ID_INST,name,detail,status FROM tbest_pratice WHERE id_instance = 1;";
				psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB -c "$SQL" >> $LOGFILE;

				SQL="SELECT id FROM tbest_pratice WHERE id_instance = $ID_INST";
				psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB -t -c "$SQL" |
				while read BP 
				do
					ID_BP=`echo $BP | awk -F'|' '{print $1}'| sed 's/ //g'`;
					if [ ! -z $ID_BP ]; 
					then
						SQL="INSERT INTO tsection_best_pratice(item,id_best_pratice,name) \
						SELECT item,$ID_BP,name FROM tsection_best_pratice WHERE id_best_pratice IN \
						(SELECT id FROM tbest_pratice WHERE id_instance = 1 AND name IN \
						(SELECT name FROM tbest_pratice WHERE id_instance = $ID_INST AND id = $ID_BP ))  \
						ORDER BY CAST(substring(replace(replace(replace(item,'-','1'),'A',''),'.',''),1,6) AS integer);";
						psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB -c "$SQL" >> $LOGFILE;
						
						SQL="SELECT id,item FROM tsection_best_pratice WHERE id_best_pratice = $ID_BP \
						ORDER BY CAST(substring(replace(replace(replace(item,'-','1'),'A',''),'.',''),1,6) AS integer);";
						psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB -t -c "$SQL" |
						while read SECTION
						do
							ID_SEC=`echo $SECTION | awk -F'|' '{print $1}' | sed 's/ //g'`;
							ITEM_SEC=`echo $SECTION | awk -F'|' '{print $2}' | sed 's/ //g' `;
							if [ ! -z $ID_SEC ]; 
							then 
								SQL="INSERT INTO tcategory_best_pratice(item,id_section,name) \
								SELECT item,$ID_SEC,name FROM tcategory_best_pratice WHERE id_section IN \
								(SELECT id FROM tsection_best_pratice WHERE item = '$ITEM_SEC' AND id_best_pratice IN \
								(SELECT id FROM tbest_pratice WHERE id_instance = 1 AND name IN \
								(SELECT name FROM tbest_pratice WHERE id_instance = $ID_INST AND id = $ID_BP ))) \
								ORDER BY CAST(substring(replace(replace(replace(item,'-','1'),'A',''),'.',''),1,6) AS integer);";
								psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB -c "$SQL" >> $LOGFILE;
								SQL="SELECT id,item FROM tcategory_best_pratice WHERE id_section = $ID_SEC \
								ORDER BY CAST(substring(replace(replace(replace(item,'-','1'),'A',''),'.',''),1,6) AS integer);";

								psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB -t -c "$SQL" |
								while read CATEGORY
								do
									ID_CAT=`echo $CATEGORY | awk -F'|' '{print $1}' | sed 's/ //g'`;
									ITEM_CAT=`echo $CATEGORY | awk -F'|' '{print $2}' | sed 's/ //g'`;
									if [ ! -z $ID_CAT ]; 
									then 
										SQL="INSERT INTO tcontrol_best_pratice(item,id_category,name,detail) \
										SELECT item,$ID_CAT,name,detail FROM tcontrol_best_pratice WHERE id_category IN \
										(SELECT id FROM tcategory_best_pratice WHERE item = '$ITEM_CAT' AND id_section IN \
										(SELECT id FROM tsection_best_pratice WHERE id_best_pratice IN \
										(SELECT id FROM tbest_pratice WHERE id_instance = 1 AND name IN \
										(SELECT name FROM tbest_pratice WHERE id_instance = $ID_INST AND \
										id = $ID_BP )))) \
										ORDER BY CAST(substring(replace(replace(replace(item,'-','1'),'A',''),'.',''),1,6) AS integer);";
										psql -U $userDB -d $INSTANCE_DB_NAME -h $server -p $portDB -c "$SQL" >> $LOGFILE;
									fi
								done
							fi
						done
					fi
				done
			fi
		done
	fi
done
export -n PGPASSWORD