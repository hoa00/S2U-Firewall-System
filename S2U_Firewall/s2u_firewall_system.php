<?php
###########################################################
### S.2.U Application - http://app.s2u.vn				###
### S.2.U Firewall System by Mr.Won						###
### Phiên bản 2.7 - 30/06/2013							###
### Không xóa bản quyền nhé mấy đại ca!!				###
###########################################################

###########################################################
### Chú ý: Nội dung file này không được chỉnh sửa.		###
###########################################################
$time=microtime();$time=explode(" ", $time);$time=$time[1]+$time[0];$time_start=$time;unset($time);
//////////////////////////////Int////////////////////////////
error_reporting(E_ALL ^ E_NOTICE);date_default_timezone_set('Asia/Saigon');$now=time();
define('S2UFW_BASE',dirname(__FILE__));define('S2UFWDS',DIRECTORY_SEPARATOR );
$logs="\n\nStart (".date("d/m/y h:i:s A", mktime()).") -> ";$nx=true;
if(file_exists(S2UFW_BASE.S2UFWDS."s2u_firewall_config.php")){
	include_once(S2UFW_BASE.S2UFWDS."s2u_firewall_config.php");
	include_once(S2UFW_BASE.S2UFWDS."s2u_firewall_func.php");
	define('S2UFW_URL',$config['s2u_fw_url']);$ip=getipFW();$logs.="Get IP ($ip) -> ";
	if(!file_exists(S2UFW_BASE.S2UFWDS."s2u_firewall_logs".S2UFWDS.$ip)){
		$logs.="add IP ($ip) -> ";configSFS('set', $ip, "$now|0");
	}$ips=getConfigFW($ip);
	if($config["s2u_fw2_protect"]>1&&$_SESSION['protect']==$config["s2u_fw2_protect_name"]&&$ips[3]<$config["s2u_fw2_max_lockcount"]){$logs.="Login Page -> ";
		if($_SERVER['PHP_AUTH_USER']!=$config["s2u_fw2_protect_name"]||$_SERVER['PHP_AUTH_PW']!=$config["s2u_fw2_protect_pass"]){
			header('WWW-Authenticate: Basic realm="Ban vui long nhap: Ten: '.$config["s2u_fw2_protect_name"].' va Mat khau: '.$config["s2u_fw2_protect_pass"].'"');
			header('HTTP/1.0 401 Unauthorized');$lockCount=($ips[3])?($ips[3]):0;if($lockCount>=$config["s2u_fw2_max_lockcount"]){$_SESSION['protect']=$config["s2u_fw2_protect_name"];$end="|$now";}
			configSFS('set', $ip,"$now|".$config["s2u_fw2_penalty_allow"]."|$now|".($lockCount+1).$end);
			showHTML("Bạn còn ".($config["s2u_fw2_max_lockcount"]-$lockCount)." lần để đăng nhập lại !!!", 0, 0, false);
		}else{$logs.="Login OK -> ";$_SESSION['protect']='ok';}
	}
	$cfnDA=explode('|',$config['s2u_fw2_domain_allow']);
	if($config['s2u_fw_ipw']!=0||$config['s2u_fw_ipw']!=''){$logs.='Check IP white list -> ';if(strpos($config['s2u_fw_ipw'],$ip)!==false){$logs.="Ingone IP ($ip) !";ghiLog($logs);unset($logs,$config,$ip);return;}}
	if($config['s2u_fw_country']!=0||$config['s2u_fw_country']!=''){include_once(S2UFW_BASE.S2UFWDS."s2u_firewall_geoiploc.php");
		$logs.='Check Country -> ';$country=getCountryFromIP($ip);
		unset($GLOBALS['geoipaddrfrom'],$GLOBALS['geoipaddrupto'],$GLOBALS['geoipctry'],$GLOBALS['geoipcntry'],$GLOBALS['geoipcountry']);
		if(strpos(strtolower($config['s2u_fw_country']),strtolower($country))!==false){
			$logs.="Block Contry ($country) !";ghiLog($logs);
			showHTML("Xin lỗi, hệ thống tường lửa đã chặn địa chỉ IP của quốc gia bạn!!<br><br>Sorry, Your IP address country has been blocked by Firewall!!", 0,$cfnDA[0]);unset($logs,$config,$ip,$country,$cfnDA);
		}$logs.="OK Contry ($country) -> ";
	}
	if($config['s2u_fw2_domain_ref']==''){autoConfig('s2u_fw2_domain_ref', 2);}
	$ref=$config['s2u_fw2_domain_ref'];unset($_SESSION['ref']);
	if(strpos($config['s2u_fw_htaccess'], '.htaccess')===false){
		$logs.='Htaccess false -> ';$config['s2u_fw_htaccess']=false;
	}
	if($config['s2u_fw_type']==0){
		$logs.='FW is Off !!';ghiLog($logs);unset($logs,$ref,$config);return;
	}else if($config['s2u_fw_type']==2){
		$config['s2u_fw2_domain_ref']=(!$config['s2u_fw2_domain_ref'])?$_SERVER['SERVER_NAME']:$config['s2u_fw2_domain_ref'];
		$config['s2u_fw2_domain_allow']=(strpos($cfnDA[0],$config['s2u_fw2_domain_ref'])===false)?'http://'.$config['s2u_fw2_domain_ref'].'|http://www.'.$config['s2u_fw2_domain_ref']:$config['s2u_fw2_domain_allow'];
		if($config['s2u_fw2_protect']==1){
			$status=@getUrl(base64_decode('aHR0cDovL2FwcC5zMnUudm4vY2hlY2t3ZWIucGhwP2RvbWFpbj0=').$config['s2u_fw2_domain_ref']);
			$logs.="Auto Protect (site: $status) -> ";
			if($status=='off'){
				autoConfig('s2u_fw2_protect', 2);
			}else if($status=='on'){
				unset($_SERVER['PHP_AUTH_USER'],$_SERVER['PHP_AUTH_PW']);activeProtect(false);
			}
		}else if($config['s2u_fw2_protect']==3){$logs.='Always Protect -> ';
			checkFileConfig('autoProtect');activeProtect(true);
		}else if($config['s2u_fw2_protect']==2){$logs.='Active Protect -> ';
			checkFileConfig('autoProtect');$timeProtect=getConfigFW("autoProtect");
			$tend=timeEnd($config['s2u_fw2_protect_time'],$timeProtect[0],60);
			if($tend<=0){closeSP();$logs.="Protect timeout ($tend)-> Stop Protect -> ";
			}else {if($_SESSION['protect']!='ok'){$logs.='Protect ready -> ';activeProtect(true);}}
		}
		if($config["s2u_fw2_lock_ref"]>0&&!empty($_SERVER['HTTP_REFERER'])){$url=$_SERVER['HTTP_REFERER'];$logs.="Ref: $url -> ";
			$swf = getCT($url);$f=pathinfo($url);
			if($swf == 'application/x-shockwave-flash'||$f['extension'] == 'swf'){
				$dm=parse_url($url, PHP_URL_HOST);$iph=gethostbyname($dm);
				$tb=($now-$config['s2u_fw2_time_unlock']*60)+1440*60;setcookie("timeout", $config["s2u_fw2_time_unlock"]*60, 0, '/');
				configSFS('set', $dm, "$now|".$config['s2u_fw2_penalty_allow']."|$now|".$config['s2u_fw2_max_lockcount']."|$tb");blockDomain($dm);
				configSFS('set', $iph, "$now|".$config['s2u_fw2_penalty_allow']."|$now|".$config['s2u_fw2_max_lockcount']."|$tb");blockIP($iph);
				configSFS('set', $ip, "$now|".$config['s2u_fw2_penalty_allow']."|$now|".$config['s2u_fw2_max_lockcount']."|$now");blockIP($ip);
				$_SESSION['protect']=$config["s2u_fw2_protect_name"];$logs.="Ref Flash (IP: $iph| Domain: $dm) -> Deny Ddos Flash !!!";ghiLog($logs);exit;
			}
			unset($_SESSION['ref']);
			if(strpos($url,$ref)===false&&isset($url)){
				$logs.="Ref !!! -> ";$_SESSION['ref']=1;
			}
		}
		if($_SESSION['ref']==1&&$config['s2u_fw2_lock_ref']==2){
			$_SESSION['temp']=1;fireWallTwo();return;
		}
	}
}else{return;}$ips=getConfigFW($ip);

if($config['s2u_fw_time_clear']>0){
	checkFileConfig('autoClear');$ac=getConfigFW("autoClear");
	$ac=timeEnd($config['s2u_fw_time_clear'], $ac[0], 60);
	if($ac<=0){
		configSFS('set','autoClear',$now);
		delLogs();deleteAll();checkIPDeny();$logs.='Clear Logs -> ';
		($config['s2u_fw_send_mail']>=2)?sendMail("Đã làm sạch các ip theo dõi! Lúc: ".date("H:i:s-d/m/Y")):null;
	}
}
if($config['s2u_fw_type']==2){$logs.='FW type: step by step -> ';$ss=getConfigFW('scoreSystem');
	if(isset($_SESSION['captcha'])&&$_SESSION['captcha']==$ip){include_once(S2UFW_BASE.S2UFWDS.'s2u_firewall_recaptchalib.php');$logs.='Show Captcha -> ';
		$timeUnlock=floor($config['s2u_fw2_time_unlock']-($now-$ips[4])/60);$_SESSION['timeout']=$ips[4];
		if(timeEnd($config['s2u_fw2_time_captcha'], $_COOKIE['timeout'], 60)<=0&&$_POST['recaptcha_challenge_field']){
			$logs.='Unlock with Captcha -> ';
			if(!$ips[4]){
				$logs.='IP Deny not found -> ';unlockIP($ip);ghiLog($logs);setcookie("timeout", "", 0, '/');
				showHTML("IP của bạn đã được mở khóa!!!", timeEnd(5,$now),$cfnDA[0]);
			}else{
				$resp=recaptcha_check_answer($config['s2u_fw2_captcha_private_key'],$_SERVER['REMOTE_ADDR'],$_POST['recaptcha_challenge_field'],$_POST['recaptcha_response_field']);
				if(!$resp -> is_valid){setcookie("timeout", $timeUnlock*60, 0, '/');blockIP($ip);ghiLog($logs);
					showHTML("Bạn đã bị khóa IP trong $timeUnlock phút.", timeEnd($config['s2u_fw2_time_unlock']*60,$now),$cfnDA[0]);
				}else{unlockIP($ip);ghiLog($logs);
					showHTML("IP của bạn đã được mở khóa!!!", timeEnd(5,$now),$cfnDA[0]);
				}
			}unset($_SESSION['captcha']);
		}else if($ips[4]) {
			setcookie("timeout", $timeUnlock*60, 0, '/');blockIP($ip);ghiLog($logs);
			showHTML("Bạn đã bị khóa IP trong $timeUnlock phút.", $_COOKIE['timeout'],$cfnDA[0]);
		} else {unset($_SESSION['captcha']);}
	}
	if($ss[0]<=0&&$config['s2u_fw2_super']==0&&$config['s2u_fw_type']==2){
		$logs.='Start Anti Super -> ';autoConfig('s2u_fw2_super', 1);configSFS('set', 'scoreSystem', "0|".time()."|".$ss[2]);
		($config['s2u_fw_send_mail']>=1)?sendMail("Phát hiện dấu hiệu DDoS!!! Hệ thống tường lửa đã chuyển sang chế độ Super anti trong {$config['s2u_fw2_super_time']} phút! Lúc: ".date("H:i:s-d/m/Y")." Tại: ".fullAddress()):null;
	}
	if($config['s2u_fw2_super']==0&&$config['s2u_fw2_isbot']==0&&isBot()==1){
		$logs.="igone IP ($ip) BOT !!!";ghiLog($logs);return;
	}
	if($config['s2u_fw2_super']==1){
		$as=floor(timeEnd($config['s2u_fw2_super_time'], $ss[1], 60));
		$logs.="Check timeout Anti Super ($as) -> ";
        if($as<=0){
			$logs.='Anti Normal -> ';setcookie("user", "", 0, '/');closeAS();
            ($config['s2u_fw_send_mail']>=1)?sendMail("Website đã trở lại bình thường! Lúc: ".date("H:i:s-d/m/Y")):null;
        }
    }
	if($config['s2u_fw2_super']==1&&!$ips[4]){
		if($_COOKIE['user']=='ok'||$as<=0){$logs.="Ingone Anti Super -> ";
			fireWallOne();
		}else {$logs.="Starting Anti Super -> ";
			$_SESSION['temp']=1;
			fireWallTwo();
		}
	}else{fireWallOne();}
}else if($config['s2u_fw_type']==1){$logs.='FW type: one kill -> ';
	if($ips[4]){
		(($now-$ips[4])/60>=$config['s2u_fw2_time_unlock'])?unlockIP($ip):null;
		fireWallOne();
	} else {
		anti();
	}
}
////////////////////////////////FireWall//////////////////////////
function fireWallOne(){
    global $config,$ip,$cfnDA,$now,$logs;$logs.='FW: fireWallOne -> ';
    if(!file_exists(S2UFW_BASE.S2UFWDS."s2u_firewall_logs".S2UFWDS.$ip)){$logs.="add IP ($ip) -> ";
		configSFS('set', $ip, "$now|0");
	}
	$ips=getConfigFW($ip);
	if(isset($ips[4])){$logs.="check IP ($ip) Deny -> ";
        if($config['s2u_fw2_captcha_public_key']!=''&&$config['s2u_fw2_captcha_private_key']!=''){$logs.='Begin deny IP -> ';
			$_SESSION['captcha']=$ip;include_once(S2UFW_BASE.S2UFWDS."s2u_firewall_recaptchalib.php");
			$captcha="<br><center>Nhập mã dưới để bạn tự Unlock IP (Chỉ nhập một lần)<br><br><form method='post' action='{$config["s2u_fw_url"]}s2u_firewall_system.php'>".recaptcha_get_html($config['s2u_fw2_captcha_public_key'])."</form>Nhấn Enter để hoàn tất trước khi hết thời gian.</center>";
			$t1="Bạn sẽ phải nhập đoạn mã bên dưới để tự mở khóa<br />Nếu không,";
			$timeUnlock=timeEnd($config['s2u_fw2_time_captcha'],$ips[4]);
		}
		$timeUnlock=timeEnd($config['s2u_fw2_time_unlock'],$ips[4],60);		
		if(($now-$ips[4])/60>=$config['s2u_fw2_time_unlock']){
			unlockIP($ip);header("location: ".fullAddress());ghiLog($logs);exit;
		}
		setcookie("timeout", $config["s2u_fw2_time_unlock"]*60, 0, '/');setcookie("user", "", 0, '/');
		(isset($_SESSION['captcha'])||!isset($_COOKIE['user']))?blockIP($ip):null;
		$logs.='IP this Deny !!';ghiLog($logs);
		showHTML("IP của bạn <font color='red'>$ip</font> đã bị khóa truy cập để đảm bảo an toàn<br/>(Do hệ thông phát hiện dấu hiệu Flood nhiều lần từ IP của bạn).<br />$t1 IP sẽ được mở khóa sau <font color='red'>{$timeUnlock} phút</font>$captcha",$timeUnlock*60,$cfnDA[0]);
	}else if(isset($ips[2])){$logs.="check IP Lock -> ";
		if($_SESSION['ref']==1&&$config['s2u_fw2_lock_ref']>=1){$logs.='Deny ip with ref -> ';
			unset($_SESSION['ref']);
			($config['s2u_fw_send_mail']>=2)?sendMail("Địa chỉ IP: $ip đã bị khóa vĩnh viển bởi Ref từ: $ref Tại: ".fullAddress()):null;
			configSFS('set', $ip,"$now|".$config['s2u_fw2_penalty_allow']."|$now|".$config['s2u_fw2_max_lockcount']."|$now");
			$ss=getConfigFW('scoreSystem');configSFS('set', 'scoreSystem', ($ss[0]-10)."|$now|".$ss[2]);
		}
		if(empty($_SESSION['temp'])){$_SESSION['autoScore']=1;}
		$_SESSION['temp']=1;setcookie("user", "", 0, '/');
		if($config["s2u_fw2_protect"]>1){session_destroy();unset($_SESSION['protect']);}fireWallTwo();
	}else{anti();}
}
function anti(){global $config,$ip,$now,$logs;$logs.='FW: anti -> ';
	if(!file_exists(S2UFW_BASE.S2UFWDS."s2u_firewall_logs".S2UFWDS.$ip)){$logs.="add IP ($ip) -> ";
		configSFS('set', $ip, "$now|0");
	}
	$ips=getConfigFW($ip);
	$penalty=$config['s2u_fw2_penalty_allow'];
	$maxLockCount=$config['s2u_fw2_max_lockcount'];
	if($config['s2u_fw_type']==1){
		$penalty=$config['s2u_fw1_penalty_allow'];$maxLockCount=0;
	}
	$lockCount=($ips[3])?($ips[3]):0;
	$logs.="Check times of IP ({$ips[1]}|$penalty) + ($lockCount|$maxLockCount) -> ";
    if($ips[1]>=$penalty&&($now-$ips[0])<=1){
		$logs.="IP>penalty ($penalty) -> ";setcookie("user", "", 0, '/');
        if($lockCount>=$maxLockCount){$logs.='Block IP -> ';
			configSFS('set', $ip,"$now|".$config['s2u_fw2_penalty_allow']."|$now|$maxLockCount|$now");
			if($config['s2u_fw_type']==2){$logs.='scoreSystem -10 -> ';
				$ss=getConfigFW('scoreSystem');configSFS('set', 'scoreSystem', ($ss[0]-10)."|$now|".$ss[2]);
			}
        }else{$_SESSION['temp']=1;$logs.="Lock IP -> ";
			$np=($lockCount+1==$maxLockCount)?$penalty:0;
			configSFS('set', $ip,"$now|$np|$now|".($lockCount+1));
			showHTML("IP của bạn đã bị chặn tạm thời!", 3);
        }
    }else if(($now-$ips[0])>1){$logs.='IP normal !!';
		if($config['s2u_fw2_super']==0){setcookie("user", "", 0, '/');}
		($ips[3])?configSFS('set',$ip,"$now|".$ips[1]."|$now|$lockCount"):configSFS('set',$ip, "$now|0");
    }else{$logs.="add times IP !!";$c=$ips[1]+1;
		($ips[3])?configSFS('set',$ip,"$now|$c|$now|$lockCount"):configSFS('set',$ip, "$now|$c");
	}
}
function fireWallTwo(){
    global $config,$ip,$cfnDA,$logs;
	$logs.='FW: fireWallTwo -> ';
    if(isset($_POST['s2u_accepted_redirect'])&&$_SESSION['temp']==1){
		$logs.="Clicker Banner -> ";$domainAllowed=0;
		(empty($_SERVER['HTTP_REFERER']))?$domainAllowed=1:null;
        foreach($cfnDA as $Domain){
			(@strpos($_SERVER['HTTP_REFERER'],$Domain)!==false)?$domainAllowed++:null;
		}
        if($domainAllowed>0){$logs.="Unlock IP with Click !!!";ghiLog($logs);
			setcookie("user", "ok", 0, '/');
			unset($_SESSION['temp']);
			configSFS('set', $ip, "$now|0");
		}
		header("location: ".$_SERVER['HTTP_REFERER']);exit;
    }
	if($config['s2u_fw2_two_layer']==1){$logs.='Anti layer 2 -> ';
		anti();
	}
    if(isset($_SESSION['autoScore'])&&$_SESSION['autoScore']==1){$logs.=' -> scoreSystem -5 -> ';
		unset($_SESSION['autoScore']);
		$ss=getConfigFW('scoreSystem');configSFS('set', 'scoreSystem', ($ss[0]-10)."|$now|".$ss[2]);
       ($config['s2u_fw_send_mail']>=3)?sendMail("Địa chỉ IP: $ip đã bị khóa tạm thời lúc: ".date("H:i:s-d/m/Y")."  Tại: ".fullAddress()):null;
    }$logs.=' Show banner ';
    $img=getBanner();$pop='';
	$url=rawurlencode($config['s2u_fw2_domain_ref']);
	(checkDomain($img[3])==true)?$pop="onclick='popup(\"http://{$img[3]}\");'":null;
	$clickBG="Nhấp vào hình để tiếp tục<br><center><form action='{$config["s2u_fw_url"]}s2u_firewall_system.php' method='POST'><input type='hidden' value='$url' name='s2u_accepted_redirect' /><input type='submit' $pop value=' ' style='background-image:url({$img[0]});width:{$img[1]};height:{$img[2]};cursor:pointer;border-width:0px;background-color:transparent;' /></form></center>";
	ghiLog($logs);showHTML($clickBG, 0);
}ghiLog($logs);
?>