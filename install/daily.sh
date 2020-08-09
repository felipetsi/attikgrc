#!/bin/bash
#################################################################
# 		Daily rotine of Attik GRC								#
#		Created in 14/01/2018								   	#
#		Last update 14/01/2018								   	#
#		Version: 1.0										   	#
#################################################################

### General parameters
	server="localhost";
	userDB="arm_user";
	nameDB="attikgrc";
	portDB="5432";
	LOGFILE="/var/log/attikgrc/scheduling.log";
	export PGPASSWORD="123456";
	EN_MSG="Review the effectiveness of the control:";
	PT_MSG="É necessário fazer a revisão de eficácia do controle:";
	ES_MSG="Haga una revisión de la eficacia del control:";
	

	#Load date of today
	DAY=$(date +%d)
	WEEKDAY=$(date +%w)
	MONTH=$(date +%m)
	YEAR=$(date +%Y)
	FULLDATE=$(date +%Y-%m-%d)
	echo "###### $FULLDATE: " >> $LOGFILE;
### General parameters

# Load the instances
SQL="SELECT i.id, i.name, i.language_default, e.id_person FROM tinstance i, tespecial_person e WHERE i.status = 'a' AND \
	e.name LIKE 'defau_appr' AND e.id_instance = i.id";
psql -U $userDB -d $nameDB -h $server -p $portDB -t -c "$SQL" | 
while read INSTANCE 
do
	ID_INST=`echo $INSTANCE | awk -F'|' '{print $1}'`;
	NAME_INST=`echo $INSTANCE | awk -F'|' '{print $2}'`;
	LANG_INST=`echo $INSTANCE | awk -F'|' '{print $3}'`;
	APPR_INST=`echo $INSTANCE | awk -F'|' '{print $4}'`;
	if [ ! -z $ID_INST ]; 
	then
		## Create task to efficacy revision of controls that have schedule to today
		if [ $LANG_INST == "pt" ]; then TASK_MSG=$PT_MSG;
		elif [ $LANG_INST == "es" ]; then TASK_MSG=$ES_MSG;
		else TASK_MSG=$EN_MSG; fi

		echo "--- Start verifing instance: $NAME_INST" >> $LOGFILE;

		# Filter control with revision to today
		SQL="SELECT c.id,c.name,c.deadline_revision,p.id_risk_responsible \
		FROM tcontrol c, tprocess p \
		WHERE p.id_area IN(SELECT id FROM tarea WHERE id_instance = $ID_INST) AND c.status != 'd' AND \
		c.id_process = p.id AND c.enable_revision = 'e' AND c.apply_revision_from <= '$FULLDATE' AND \
		(scheduling_day = $DAY OR scheduling_day = 32) AND \
		(scheduling_month LIKE '%$MONTH,%' OR scheduling_month LIKE '$MONTH' OR scheduling_month = '13') AND \
		(scheduling_weekday = $WEEKDAY OR scheduling_weekday = 7) ";
		psql -U $userDB -d $nameDB -h $server -p $portDB -t -c "$SQL" | 
		while read CONTROL 
		do
			ID_CONT=`echo $CONTROL | awk -F'|' '{print $1}'`;
			NAME_CONT=`echo $CONTROL | awk -F'|' '{print $2}'`;
			DEADLINE=`echo $CONTROL | awk -F'|' '{print $3}'`;
			DEADLINE=`echo $DEADLINE | sed 's/ //g'`;
			RESP_CONT=`echo $CONTROL | awk -F'|' '{print $4}'`;
			PREVISION=`date +%Y-%m-%d --date=''$DEADLINE' day'`;
			TODAY=`date +%Y-%m-%d `;
			if [ ! -z $ID_CONT ]; 
			then
			
				# Insert workflow task
				SQL="INSERT INTO ttask_workflow(name, detail, id_instance, id_responsible, id_approver, source, status, \
				prevision_date, creation_date) \
				VALUES ('$TASK_MSG $NAME_CONT', '$TASK_MSG $NAME_CONT', $ID_INST, $RESP_CONT, $APPR_INST, 'riskmanager', 'o', '$PREVISION','$FULLDATE')";
				psql -U $userDB -d $nameDB -h $server -p $portDB -c "$SQL" >> $LOGFILE;
				
				# Insert revision in control record
				SQL="INSERT INTO trevision_control(id_control, prevision_date, id_responsible) \
				VALUES ($ID_CONT, '$TODAY', $RESP_CONT)";
				psql -U $userDB -d $nameDB -h $server -p $portDB -c "$SQL" >> $LOGFILE;
				# Insert relationship between Workflow task and Control
				#SQL="SELECT id FROM ttask_workflow ORDER BY id DESC LIMIT 1";
				#LAST_ID=$(psql -U $userDB -d $nameDB -A -h $server -p $portDB -t -c "$SQL")
				#SQL="INSERT INTO tacontrol_task(id_control, id_task) VALUES ($ID_CONT,$LAST_ID)";
				#psql -U $userDB -d $nameDB -A -h $server -p $portDB -c "$SQL" >> $LOGFILE;

				echo "Created task and revision pendding associated with control: $NAME_CONT" >> $LOGFILE;
			fi
		done

		echo "--- End verifing instance: $NAME_INST" >> $LOGFILE;
	fi
	
done

export -n PGPASSWORD


		## END Create task to efficacy revision of controls that have schedule to today
		## Create nonconformity to controls that not have did revision efficacy
		# Filter control with revision to today
		#SQL="SELECT c.id,c.name,c.deadline_revision,p.id_risk_responsible \
		#FROM tcontrol c, tprocess p \
		#WHERE p.id_area IN(SELECT id FROM tarea WHERE id_instance = $ID_INST) AND c.status != 'd' AND \
		#c.id_process = p.id AND c.enable_revision = 'e' AND c.apply_revision_from <= '$FULLDATE'";
		#psql -U $userDB -d $nameDB -A -h $server -p $portDB -t -c "$SQL" |
		#while read CONTROL 
		#do
			#ID_CONT=`echo $CONTROL | awk -F'|' '{print $1}'`;
			#NAME_CONT=`echo $CONTROL | awk -F'|' '{print $2}'`;
			#DEADLINE=`echo $CONTROL | awk -F'|' '{print $3}'`;
			#RESP_CONT=`echo $CONTROL | awk -F'|' '{print $4}'`;
			#PREVISION=`date +%Y-%m-%d --date=''$DEADLINE' day'`;
			# Insert workflow task
			#SQL="INSERT INTO ttask_workflow(name, detail, id_instance, id_responsible, id_approver, source, status, \
			#prevision_date, creation_date) \
			#VALUES ('$TASK_MSG $NAME_CONT', '$TASK_MSG $NAME_CONT', $ID_INST, $RESP_CONT, $APPR_INST, 'riskmanager', 'o', \
			#'$PREVISION','$FULLDATE')";
			#psql -U $userDB -d $nameDB -A -h $server -p $portDB -c "$SQL" >> $LOGFILE;

			#echo "Created task do control: $NAME_CONT" >> $LOGFILE;
		#done
		## END Create nonconformity to controls that not have did revision efficacy