<?php
#################################
### S.2.U Firewall System by Mr.Won         ###
### Phiên bản 3.0 - 24/03/2016                ###
#################################
error_reporting(E_ALL ^ E_NOTICE);
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set('Asia/Saigon');
define('S2UFW_BASE',dirname(__FILE__));
include_once(S2UFW_BASE.'/fw_function.php');

checkFileAllow($config['s2u_fw_file_allow']);

$hash = $_GET['h'];
if($hash!=''){
	$fileips = S2UFW_BASE.'/'.$config['s2u_fw_ips'];
	$content=file_get_contents($fileips);
	$js=json_decode($content, true);
	foreach($js as $ip => $h){
		if($h['Hash']==$hash&&$h['Status']=='Deny'){
			$js[$ip]['Count'] = 0;
			$js[$ip]['Wait'] = 0;
			$js[$ip]['Status'] = 'Live';
			unset($_SESSION['sm']);
			unlockIP($ip);
			file_put_contents($fileips, json_encode($js));
			showHTML('IP '.$ip.' đã được mở khóa!');
			exit;
		}
	}
}
?>
