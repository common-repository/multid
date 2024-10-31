<?php
function Multid_CreateDB($ajax=0,$Collation='utf8mb4',$Drop=0) { 

$query='
SET AUTOCOMMIT = 0;
START TRANSACTION;
DROP TABLE IF EXISTS `multid_keyword`;
CREATE TABLE IF NOT EXISTS `multid_keyword` (
  `id` varchar(190) CHARACTER SET '.$Collation.' NOT NULL,
  `multid` tinyint  NOT NULL default 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ;

DROP TABLE IF EXISTS `multid_value`;
CREATE TABLE IF NOT EXISTS `multid_value` (
  `lan` varchar(5) CHARACTER SET '.$Collation.' NOT NULL,
  `keyword` varchar(190) CHARACTER SET '.$Collation.' NOT NULL,
  `label` text CHARACTER SET '.$Collation.' NOT NULL,
  PRIMARY KEY (`lan`,`keyword`),
  KEY `multid_dictionary_keyword` (`keyword`)
) ;


ALTER TABLE `multid_value`
  ADD KEY `dom_lan_index` (`keyword`,`lan`);
COMMIT;
  COMMIT;';

global $wpdb;
$VarTable=MultiD_CheckDB('multid_keyword');
if (!$VarTable||!$Drop) {
	$err=array();
	$query=explode(';',$query);
	foreach($query as $item) {
	
		$result=$wpdb->query($item,OBJECT);
			if (is_wp_error($result)) { array_push($result); }
	}
	if (current($err)) { var_dump($err); exit("ERROR DB UPDATE");}
	//require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	//var_dump(dbDelta($query)); 
	
	
	}
if ($ajax) die("");	
}

function Multid_AjaxCreateDB() {

	$ajax=1;
	$collation=MultiD_PostVar('collation','utf8mb4');
	$drop=MultiD_PostVar('drop',0);
	
	Multid_CreateDB($ajax,$collation,$drop);
	
}
add_action( 'wp_ajax_Multid_AjaxCreateDB', 'Multid_AjaxCreateDB' );	

function MultiD_CheckDB($Table='multid_keyword') {
	
	global $wpdb;
	return ($wpdb->get_var("SHOW TABLES LIKE '$Table'") == $Table);
	
}

?>