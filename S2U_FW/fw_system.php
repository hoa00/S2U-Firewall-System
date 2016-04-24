<?php
#################################
### S.2.U Firewall System by Mr.Won         ###
### Phiên bản 3.0 - 24/03/2016                ###
#################################
header('Content-Type: text/html; charset=utf-8');
$ts = microtime();
error_reporting(E_ALL ^ E_NOTICE);
date_default_timezone_set('Asia/Saigon');
define('S2UFW_BASE',dirname(__FILE__));
include_once(S2UFW_BASE.'/fw_function.php');
///////////////////////////////////////////////////////////
$now=time();
if(extension_loaded( 'zlib' )){
	ob_start( 'ob_gzhandler' );
}

$m = $_GET['s2m'];
$url = $_GET['s2u'];

//print_r($_SERVER); exit;
//print_r($_REQUEST); exit;

$url_c = str_replace(' ', '%20', 'http://'.$_SERVER['HTTP_HOST'].$url);
if (filter_var($url_c, FILTER_VALIDATE_URL) === false){exit;}

if($_SERVER['REQUEST_URI']==""){
	$url_r = 'http://'.$_SERVER['HTTP_HOST'];
} else {
	$url_r = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
}

if($m=='POST'){$mt = $_POST;}
else {$mt = $_GET;} 

foreach($mt as $key => $val){
	if($key!='s2m'&&$key!='s2u'&&$key!='s2ip'&&$key!='s2s'){
		$data[$key] = trim($val);
	}
}

if($data!=""){$dataQ = http_build_query($data);}
else {$dataQ = $data;}

if($m=='GET'&&$dataQ!=""){$url_q = $url_c.'?'.$dataQ;}
else {$url_q = $url_r;}

if($config['s2u_fw_active']==0){
	setcookie('check', 'Live', time()+1);
	header('Location: '.$url_q); exit;
}

$header = getHeader($url_q);
//print_r($header); exit;
if($header['http_code']!=200&&$header['http_code']!=''){
	http_response_code($header['http_code']);
	showHTML('<font size="90px" color="red"><b>'.$header['http_code'].'</b></font><br>Trang web bị lỗi!');exit;
}

$ip = getipFW();
if($config['s2u_fw_ipw']!=""){
	if(strpos($config['s2u_fw_ipw'], $ip) != 0){
		header('Location: '.$url_r); exit;
	}
}

if($config['s2u_fw_country']!=""){
	include_once(S2UFW_BASE.'/fw_geoiploc.php');
	$country=getCountryFromIP($ip);
	if($country!=""){
		if(strpos($config['s2u_fw_country'], $country) != 0){
			showHTML('Deny from '.$country.' country!'); exit;
		}
	}
}

$options = array(
	'http' => array(
		'header'  => 'Cookie: check=live\r\n'.
						 'Referer: '.$url_r.
						 'Connection: close\r\n',
		'method'  => $m,
		'content' => $dataQ
	)
);
$context = stream_context_create($options);

if($config['s2u_fw_cache']==1){
	$tc = URLCache(0, md5($url_r.'?'.$dataQ), $now, '200 OK');
	header('Last-Modified: '.gmdate('D, d M Y H:i:s', $tc['Last']).' GMT');
	header('Expires: '.gmdate('D, d M Y H:i:s', $now+$config['s2u_fw_cache_timeout']).' GMT');
	header('Cache-Control: max-age='.$config['s2u_fw_cache_timeout'].', public');
	header('Pragma: cache');
	header('Accept-Encoding: gzip, deflate');
	header('Content-Encoding: gzip');
	
	if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])){
		if($now-$tc['Last']<$config['s2u_fw_cache_timeout']){
			header("HTTP/1.1 304 Not modified"); exit;
		} else {
			URLCache(1, md5($url_r.'?'.$dataQ), '', '200 OK');
		}
	}
	
	$add = '<meta http-equiv="Cache-Control" content="max-age='.$config['s2u_fw_cache_timeout'].'" />'.
	'<meta http-equiv="Cache-Control" content="public" />'.
	'<meta http-equiv="Expires" content="'.gmdate('D, d M Y H:i:s', $now+$config['s2u_fw_cache_timeout']).' GMT" />'.
	'<meta http-equiv="Pragma" content="cache" />';
}

include_once(S2UFW_BASE.'/fw_minisize.php');
if($config['s2u_fw_active']==1){
	$status = checkClient($ip, $now, $url_q);
	if($status != 'Live'){exit;}
	$te = microtime() - $ts;
	usleep($te + 5000);
	if($m=='POST'||$m=='GET'){
		$content = file_get_contents($url_q,false,$context);
		if(!$content){die('Lỗi tải liên kết!');}
		$content = preg_replace("#<head>(.*)<\/head>#s", "<head>$add$1</head>", $content);
		if($config['s2u_fw_fix_url']==1){
			$content = removeLinkDie($content, $url_c);
		}
		$content = str_replace('</body>','<p align="center" style="color:#8C8C8C;font-style:italic;font-size:10px;">
		<a href="https://www.facebook.com/groups/s2ufw/" rel="author" target="_blank" style="text-decoration:none;color:currentColor;">Protection and optimization by S.2.U Firewall System</a>
		</p></body>', $content);
		print wp_html_compression_finish($content);
		//print $content;
		//print minify_html($content);
	} else {
		header('Location: '.$url_q);
	}
} else if($config['s2u_fw_active']==2){	
	$hash = md5($dataQ);
	$te = URLCache(0, md5($url_q.$hash), $now);
	$fc = substr(basename($url_q), 0, -4).$hash;
	$fc = preg_replace('/(\W+)/',"", $fc).'.htm';
	$cache_file = 'cache/'.$fc;
	if(file_exists($url_q.$cache_file)) {
		if(($now-$te)<$config['s2u_fw_cache_clear']){
			unlink($cache_file);
		} else {
			header('Location: '.dirname($url_q).'/'.$fc); exit;
		}
	}
	
	$content = file_get_contents($url_q,false,$context);
	if(!$content){	die('Lỗi cache!');}
	$content = preg_replace("#<head>(.*)<\/head>#s", "<head>$add$1</head>", $content);
	if($config['s2u_fw_fix_url']==1){
		$content = removeLinkDie($content, $url_c);
	}
	$content = str_replace('</body>','<p align="center" style="color:#8C8C8C;font-style:italic;font-size:10px;">
	<a href="https://www.facebook.com/groups/s2ufw/" rel="author" target="_blank" style="text-decoration:none;color:currentColor;">Protection and optimization by S.2.U Firewall System</a>
	</p></body>', $content);
	$content =  wp_html_compression_finish($content);
	$fp = fopen($cache_file, 'w');
	fwrite($fp, $content);
	fclose($fp);
	header('Location: '.$fc); exit;
}
if(extension_loaded( 'zlib' )){ob_end_flush();}
?>
