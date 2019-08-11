<?php
// Configuration version
$CONF_VERSION = "1.0";
// Configuration session
$CONF_AVAILABLE_LANGUAGE = array("English@en@Welcome","Português@pt@Bem-vindo","Español@es@Bienvenido");
$CONF_ERROR_LOGIN_SCALE = array("0","3","4","5","6","7","8","9","10");
$CONF_PASSWORD_LIFETIME = array("0","1","2","30","45","60","90","120","180");
$CONF_BOOLEAN_OP = array("LANG_YES@y","LANG_NO@n");
$CONF_DEFAULT_SYSTEM_LANG = "en";
$CONF_SOURCE_TASK = array("riskmanager","project","control","incident","nonconformity");
$CONF_LENGTH_NUM = 8; //Length of number of action, project and others.
$CONF_CLASS_UNDEFINED = "table-info";
$CONF_CLASS_DELAYED = "table-danger";
$CONF_CLASS_FINISHED = "table-Warning";
$CONF_CLASS_SUCCESS = "table-success";
$CONF_RELEVANCY_SCALE = array("1","2","3");
$CONF_IMPACT_LEVELS = array("1@low","2@middle","3@high");
$CONF_IMPACT_MITIGATE_LEVELS = array("0@notapply", "1@little","2@median","3@greatly");
$CONF_CONTROL_STATUS = array("i","e","p","d","n"); // i=implemented; e=effective; p=planned; n=not effective
$CONF_DAYS = array("",1, 2 , 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31,"all");
$CONF_MONTHS = array("","jan","feb","apr","mar","may","jun","jul","aug","set","oct","nov","dec","all");
$CONF_WEEKDAYS = array("","sunday","monday","tuesday","wednesday","thursday","friday","saturday","all");
$CONF_RISK_LABEL = array("1","2","3");
/* Name of especial users
	-defau_appr = "Default approver";*/
?>