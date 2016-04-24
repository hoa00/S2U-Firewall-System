<?php
#################################
### S.2.U Firewall System by Mr.Won         ###
### Phiên bản 3.0 - 24/03/2016                ###
#################################
include_once(S2UFW_BASE.'/fw_config.php');
session_start();
function checkClient($ip, $now, $url){
	global $config;
	$timeDelay = 3;
	$fileips = S2UFW_BASE.'/'.$config['s2u_fw_ips'];
	$status = 'Live';
	unset($_COOKIE['check']);
	
	if (!file_exists($fileips)) {
	    file_put_contents($fileips, "");
	}
	
	$content=file_get_contents($fileips);
	$js=json_decode($content, true);
	if($js[$ip]==null){
		$js[$ip]['Time'] = $now;
		$js[$ip]['Count'] = 0;
		$js[$ip]['Wait'] = 0;
		$js[$ip]['Status'] = $status;
		$js[$ip]['Hash'] = generateRandomString('s2u');
		file_put_contents($fileips, json_encode($js));
	} else {
		if(($timeDelay+$js[$ip]['Wait']) - ($now-$js[$ip]['Time']) < 0){
			$js[$ip]['Time'] = $now;
			$js[$ip]['Count'] = 0;
			$js[$ip]['Wait'] = 0;
			unset($_SESSION['sm']);
		} else {
			$js[$ip]['Count'] = $js[$ip]['Count']+1;
		}
		$wait = $js[$ip]['Wait'] - ($now-$js[$ip]['Time']);
		if($wait <= 0 && $js[$ip]['Status'] != 'Live'){
			$js[$ip]['Count'] = 0;
			$js[$ip]['Wait'] = 0;
			unset($_SESSION['sm']);
			unlockIP($ip);
		}
		setcookie('check', $status, time()+1);
		if($js[$ip]['Count'] > $config['s2u_fw_penalty_allow']){
			$status = 'Deny';
			setcookie('check', $status, time()+1);
			$js[$ip]['Wait'] = $config['s2u_fw_time_unlock'];
			if($config['s2u_fw_send_mail']>0 && $_SESSION['sm']==1){
				$_SESSION['sm']=2;
				sendMail('<b>Thông báo của S2U Firewall System.</b><br/>Địa chỉ IP: '.$ip.'<br/>Tình trạng: Bị khóa<br/>Vào lúc: '.date('H:i:s d/n/Y').'<br/>Từ địa chỉ: '.$url.'<br/><u><a href="http://'.$config['s2u_fw_url_fw'].'fw_admin.php?h='.$js[$ip]['Hash'].'">Nhấp vào đây để mở khóa.</a></u>');
			}
			$note = 'Bạn đã bị chặn! Sự truy cập của bạn làm quá tải máy chủ.<br/>Vui lòng đợi trong '.cSec($wait);
			blockIP($ip);
		} else if($js[$ip]['Count'] > $config['s2u_fw_medium_allow']){
			$status = 'Limit';
			setcookie('check', $status, time()+1);
			$js[$ip]['Wait'] = $config['s2u_fw_time_wait'];
			if($config['s2u_fw_send_mail']>1 && $_SESSION['sm']==null){
				$_SESSION['sm']=1;
				sendMail('<b>Thông báo của S2U Firewall System.</b><br/>Địa chỉ IP: '.$ip.'<br/>Tình trạng: Cảnh báo<br/>Vào lúc: '.date('H:i:s d/n/Y').'<br/>Từ địa chỉ: '.$url);
			}
			$note = 'Hãy cẩn thận! Bạn đang truy cập sắp quá số lượng được cho phép.<br/>Vui lòng đợi trong '.cSec($wait);
		}
		
		if($note!=""){
			showHTML($note);
		}
		
		$js[$ip]['Status'] = $status;
		file_put_contents($fileips, json_encode($js));
	}
	return $status;
}
function blockIP($ip){
	global $config;
	$data=file_get_contents($config['s2u_fw_htaccess']);
    if(strpos($data,$ip)===false){
		$data .= '\ndeny from '.$ip;
		file_put_contents($config['s2u_fw_htaccess'], $data);
	}
}
function unlockIP($ip){
	global $config;
	$data=file_get_contents($config['s2u_fw_htaccess']);
    if(strpos($data,$ip)!==false){
		$data=str_replace('\ndeny from '.$ip, "",$data);
		file_put_contents($config['s2u_fw_htaccess'], $data);
	}
}
function URLCache($f=0, $url='', $now='', $stt=''){
	global $config;
	$fileurls = S2UFW_BASE.'/'.$config['s2u_fw_urls'];
	
	if (!file_exists($fileurls)) {
		unset($_SESSION['urls']);
	    file_put_contents($fileurls, "");
	}
	if(isset($_SESSION['urls'])){
		$con=$_SESSION['urls'];
	} else {
		$con=file_get_contents($fileurls);
		$_SESSION['urls']=$con;
	}
	$us=json_decode($con, true);
	if($f==3){
		return $us;
	} elseif($f==2){
		return $us[$url];
	}
	if($us[$url]==null||$us[$url]['Last']==''||$f==1){
		$us[$url]['Last'] = $now;
		$us[$url]['Status'] = $stt;
	}
	unset($_SESSION['urls']);
	file_put_contents($fileurls, json_encode($us));
	return $us[$url];
}
function showHTML($msg){
	global $config;
	print '<html><head>
	<title>S.2.U Firewall System</title>
	<link href="http://'.$config['s2u_fw_url_fw'].'fw_style.css" rel="stylesheet" type="text/css">
	</head><body>
	<p class="welcome">S.2.U Firewall System</p>
	<div class="contentSection">
		<div class="ar"><div class="statusnob">[ Hệ thống tường lửa ]</div></div>
		<div class="alert"><p>'.$msg.'</p></div>
	</div>
	</body></html>';
}
function checkFileAllow($files){
	global $config;
	if(strpos($files,'|')!==false){
		$file=explode('|',$files);
	} elseif($files!=""){
		$file[0]=$files;
	}
	$data = file_get_contents($config['s2u_fw_htaccess']);
	$dataN = preg_replace("/live.*%{T/s", "live [NC]\n\tRewriteCond %{T", $data);

	if($file!=""){
		foreach($file as $f){
			$f = str_replace('.', '\.', $f);
			if(strpos($dataN,$f)===false){			
				$dataN = str_replace('live [NC]', "live [NC]\n\tRewriteCond %{REQUEST_URI} !$f [NC]", $dataN);
			}
		}
	}
	if($dataN != $data){
		file_put_contents($config['s2u_fw_htaccess'], $dataN);
	}
}
function getipFW(){
	$ip=(isset($_SERVER['HTTP_CLIENT_IP']))?$_SERVER['HTTP_CLIENT_IP']:$_SERVER['HTTP_X_FORWARDED_FOR'];
	$ip=(!$ip&&isset($_SERVER['HTTP_X_FORWARDED']))?$_SERVER['HTTP_X_FORWARDED']:$_SERVER['HTTP_FORWARDED'];
	$ip=(!$ip&&isset($_SERVER['REMOTE_ADDR']))?$_SERVER['REMOTE_ADDR']:null;
	$ip=($ip=='::1')?'127.0.0.1':$ip;
    return(!preg_match("/^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/",$ip))?
	showHTML('Sự truy cập của bạn bị cấm vì IP của bạn ko hợp lệ.'):$ip;
}
function sendMail($msg){
	global $config;
	$mail=$config['s2u_fw_email_admin'];
	if($mail!=""){
		$headers = "MIME-Version: 1.0\r\nContent-Type: text/html; charset=utf-8\r\n";
		@mail($mail, 'Thông báo của S.2.U Firewall System!!!',$msg,$headers);
	}
}
function getStr($string,$start,$end){
	$str=explode($start,$string);
	$str=explode($end,$str[1]);
	return $str[0];
}
function removeLinkDie($data, $link) {
	global $config;
	$now=time();
	$arExt = '.png|.jpg|.jpeg|.js|.css|.json|.xml|.bmp|.ico|.gif';
	preg_match_all('/["|\']([.\/]|http|www).*?["|\']/i', $data, $url);
	$url = array_unique($url[0]);
	if(!empty($url[0])){
		$arr = array();
		foreach($url as $u){
			$cu = URLCache(2,md5(substr($u, 1, -1)));
			if($cu['Last']==''||$cu['Status']=='404 Fail'||$now-$cu['Last']>$config['s2u_fw_check_timeout']){
				$dir = dirname($link);
				$uc = str_replace('//',"http://", $u);
				$uc = preg_replace('/https?:h/',"h", $uc);
				$uc = str_replace('\\',"", $uc);
				$uc = preg_replace('/["|\']([.\/]+)/','"'.$dir.'/', $uc);
				$uc = substr($uc, 1, -1);
				$u = substr($u, 1, -1);
				if(filter_var($uc, FILTER_VALIDATE_URL) !== false){
					preg_match('/\/\/.*?\/.*(\.[a-z]{2,3})/', $uc, $ext);
					//print $ext[1]."|".$uc."<br>";
					$pos = strpos($arExt, $ext[1]);
					if ($pos !== false) {
						if($cu['Status']=='404 Fail'){
							array_push($arr, array($u, $uc, 1));
						} else {
							array_push($arr, array($u, $uc, 0));
						}
					}
				}
			}
		}
		//print_r($arr);
		$hrr = multiHeader($arr);
		//print_r($hrr);
		if($hrr!=null){
			foreach($hrr as $k => $h){
				$pos = strpos($h, '200 OK');
				if($pos === false) {
					//print $arr[$k][0]."<br>";
					URLCache(1, md5($arr[$k][0]), time(), '404 Fail');
					$data = str_replace($arr[$k][0],"", $data);
				} else {
					URLCache(1, md5($arr[$k][0]), time(), '200 OK');
				}
			}
		}
	}
	return $data; 
} 
function multiHeader($data) {
	global $config;
	$now=time();
	$h = array();
	$curly = array();
	$head = array();
	$mh = curl_multi_init();
  
	foreach ($data as $id => $url) {
		$h[$id]=$url[2];
		if($h[$id]==0){
			$curly[$id] = curl_init();
			curl_setopt($curly[$id], CURLOPT_URL, $url[1]);
			curl_setopt($curly[$id], CURLOPT_HEADER, TRUE);
			curl_setopt($curly[$id], CURLOPT_NOBODY, TRUE);
			curl_setopt($curly[$id], CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($curly[$id], CURLOPT_TIMEOUT, 1);
			curl_multi_add_handle($mh, $curly[$id]);
		}
	}
	$running = null;
	do {
		curl_multi_exec($mh, $running);
	} while($running > 0);

	foreach($h as $id => $c) {
		if($c==1){
			$head[$id] = '404 Fail';
		} else {
			$head[$id] = curl_multi_getcontent($curly[$id]);
			curl_multi_remove_handle($mh, $curly[$id]);
		}
	}
	curl_multi_close($mh);
	return $head;
}
function generateRandomString($n,$l=9) {
    $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
    $charactersLength = strlen($characters);
    $randomString = "";
    for ($i = 0; $i < $l; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $n.$randomString;
}
function cSec($time) {
	$time += $time > 60 ? 30 : 0;
	$days = floor($time / 86400);
	$time %= 86400;
	$hours = floor($time / 3600);
	$time %= 3600;
	$minutes = floor($time / 60);
	$seconds = floor($time % 60);
	$return = array();
	($days>0)?$return[]=$days.' ngày':null;
	($hours>0)?$return[]=$hours.' tiếng':null;
	($minutes>0)?$return[]=$minutes.' phút':null;
	($seconds>0)?$return[]=$seconds.(date('m/d')=='06/03' ? ' sex' : 's'):null;
	return implode(', ', $return);
}
function cByte($s,$p=2){
	if(!is_numeric($s))return'?';$n=1024;
	$types = array('B', 'KB', 'MB', 'GB', 'TB');$cc=count($types)-1;
	for($i=0;$s>=$n&&$i<$cc;$s/=$n,$i++);
	return(round($s, $p).' '.$types[$i]);
}
function getHeader($url){
	$ch = curl_init();
	
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, TRUE);
	curl_setopt($ch, CURLOPT_NOBODY, TRUE);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_TIMEOUT, 1);
	curl_exec($ch);
	if(!curl_errno($ch)){
		$head = curl_getinfo($ch);
	}
	curl_close($ch);
	return $head;
}
function showMemory(){global $mus;
	return cByte(memory_get_usage()-$mus);
}
?>
