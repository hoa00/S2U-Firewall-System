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

////////////////////////////////Until///////////////////////////////
$ver=2.7;date_default_timezone_set("Asia/Saigon");
if(session_id()==''&&!isset($_SESSION)){session_start();}
function getBanner(){global $config;
	$url=base64_decode("aHR0cDovL2FwcC5zMnUudm4vYmFubmVyLnBocD9kb21haW49").$config["s2u_fw2_domain_ref"];
	$img=getUrl($url,$config["s2u_fw2_domain_ref"]);
	(!$img)?$img="{$config["s2u_fw_url"]}s2u_firewall_image/quangcao.png|400|300|app.s2u.vn":null;
	$con=explode("|",$img);unset($img,$url);
	return array($con[0],$con[1],$con[2],$con[3]);
}
function configSFS($type,$name,$con=""){
	if($type=="del"){unlink(S2UFW_BASE.S2UFWDS."s2u_firewall_logs".S2UFWDS.$name);return;}
	$ft=fopen(S2UFW_BASE.S2UFWDS."s2u_firewall_logs".S2UFWDS.$name,"w");
	if(!$ft){
		($config["s2u_fw_send_mail"]>=1)?sendMail("Có lỗi khi ghi quá trình hoạt động của IP, firewall bị vô hiệu! Tại: ".fullAddress()):null;
		autoConfig("s2u_fw_type", 0);return;
	}
	@chmod(S2UFW_BASE.S2UFWDS."s2u_firewall_logs".S2UFWDS.$name, 0666);
	fwrite($ft,$con);
	fclose($ft);unset($ft);
}
function goChmod($f,$t){
	$f=str_replace("]","",$f);
	$f=str_replace("CHMOD file [","",$f);
	$f=str_replace("CHMOD thư mục [","",$f);
	$c=@chmod($f,$t);unset($f);
	return($c)?1:0;
}
function autoConfig($n,$v){
    global $config,$now;
	$content=file_get_contents(S2UFW_BASE.S2UFWDS."s2u_firewall_config.php");
	if(!$content){return;}
	$get=getStr($content,"\$config[\"$n\"]=",";");
	$vo=trim($get);
    $new=str_replace("\$config[\"$n\"]=$vo;", "\$config[\"$n\"]=$v;",$content);
    if($new!=$content){
		$ft=fopen(S2UFW_BASE.S2UFWDS."s2u_firewall_config.php", "w");
		fwrite($ft,$new);fclose($ft);
	}unset($content,$get,$vo,$new,$ft);
}
function isBot(){
    $botlist=array("abot","dbot","ebot","hbot","kbot","lbot","mbot","nbot","obot","pbot","rbot","sbot","tbot","vbot","ybot","zbot","bot.","bot/","_bot",".bot","/bot","-bot",":bot","(bot",
	"crawl","slurp","spider","seek","accoona","acoon","adressendeutschland","ah-ha.com","ahoy","altavista","ananzi","anthill","appie","arachnophilia","arale","araneo","aranha","architext",
	"aretha","arks","asterias","atlocal","atn","atomz","augurfind","backrub","bannana_bot","baypup","bdfetch","big brother","biglotron","bjaaland","blackwidow","blaiz","blog","blo.",
	"bloodhound","boitho","booch","bradley","butterfly","calif","cassandra","ccubee","cfetch","charlotte","churl","cienciaficcion","cmc","collective","comagent","combine","computingsite",
	"csci","curl","cusco","daumoa","deepindex","delorie","depspid","deweb","die blinde kuh","digger","ditto","dmoz","docomo","download express","dtaagent","dwcp","ebiness","ebingbong","e-collector",
	"ejupiter","emacs-w3 search engine","esther","evliya celebi","ezresult","falcon","felix ide","ferret","fetchrover","fido","findlinks","fireball","fish search","fouineur","funnelweb","gazz",
	"gcreep","genieknows","getterroboplus","geturl","glx","goforit","golem","grabber","grapnel","gralon","griffon","gromit","grub","gulliver","hamahakki","harvest","havindex","helix",
	"heritrix","hku www octopus","homerweb","htdig","html index","html_analyzer","htmlgobble","hubater","hyper-decontextualizer","ia_archiver","ibm_planetwide","ichiro","iconsurf","iltrovatore",
	"image.kapsi.net","imagelock","incywincy","indexer","infobee","informant","ingrid","inktomisearch.com","inspector web","intelliagent","internet shinchakubin","ip3000","iron33","israeli-search",
	"ivia","jack","jakarta","javabee","jetbot","jumpstation","katipo","kdd-explorer","kilroy","knowledge","kototoi","kretrieve","labelgrabber","lachesis","larbin","legs","libwww","linkalarm",
	"link validator","linkscan","lockon","lwp","lycos","magpie","mantraagent","mapoftheinternet","marvin/","mattie","mediafox","mediapartners","mercator","merzscope","microsoft url control",
	"minirank","miva","mj12","mnogosearch","moget","monster","moose","motor","multitext","muncher","muscatferret","mwd.search","myweb","najdi","nameprotect","nationaldirectory","nazilla","ncsa beta",
	"nec-meshexplorer","nederland.zoek","netcarta webmap engine","netmechanic","netresearchserver","netscoop","newscan-online","nhse","nokia6682/","nomad","noyona","nutch","nzexplorer","objectssearch",
	"occam","omni","open text","openfind","openintelligencedata","orb search","osis-project","pack rat","pageboy","pagebull","page_verifier","panscient","parasite","partnersite","patric","pear.",
	"pegasus","peregrinator","pgp key agent","phantom","phpdig","picosearch","piltdownman","pimptrain","pinpoint","pioneer","piranha","plumtreewebaccessor","pogodak","poirot","pompos","poppelsdorf",
	"poppi","popular iconoclast","psycheclone","publisher","python","rambler","raven search","roach","road runner","roadhouse","robbie","robofox","robozilla","rules","salty","sbider","scooter",
	"scoutjet","scrubby","search.","searchprocess","semanticdiscovery","senrigan","sg-scout","shai'hulud","shark","shopwiki","sidewinder","sift","silk","simmany","site searcher","site valet",
	"sitetech-rover","skymob.com","sleek","smartwit","sna-","snappy","snooper","sohu","speedfind","sphere","sphider","spinner","spyder","steeler/","suke","suntek","supersnooper","surfnomore",
	"sven","sygol","szukacz","tach black widow","tarantula","templeton","/teoma","t-h-u-n-d-e-r-s-t-o-n-e","theophrastus","titan","titin","tkwww","toutatis","t-rex","tutorgig","twiceler","twisted",
	"ucsd","udmsearch","url check","updated","vagabondo","valkyrie","verticrawl","victoria","vision-search","volcano","voyager/","voyager-hc","w3c_validator","w3m2","w3mir","walker","wallpaper",
	"wanderer","wauuu","wavefire","web core","web hopper","web wombat","webbandit","webcatcher","webcopy","webfoot","weblayers","weblinker","weblog monitor","webmirror","webmonkey","webquest",
	"webreaper","websitepulse","websnarf","webstolperer","webvac","webwalk","webwatch","webwombat","webzinger","wget","whizbang","whowhere","wild ferret","worldlight","wwwc","wwwster","xenu",
	"xget","xift","xirq","yandex","yanga","yeti","yodao","zao/","zippp","zyborg","coccoc","wada");
    foreach($botlist as $bot){if(strpos($_SERVER["HTTP_USER_AGENT"],$bot)!==false){unset($botlist,$bot);return 1;}}unset($botlist,$bot);return 0;
}
function getipFW(){
	$ip=(isset($_SERVER["HTTP_CLIENT_IP"]))?$_SERVER["HTTP_CLIENT_IP"]:$_SERVER['HTTP_X_FORWARDED_FOR'];
	$ip=(!$ip&&isset($_SERVER["HTTP_X_FORWARDED"]))?$_SERVER["HTTP_X_FORWARDED"]:$_SERVER['HTTP_FORWARDED'];
	$ip=(!$ip&&isset($_SERVER["REMOTE_ADDR"]))?$_SERVER["REMOTE_ADDR"]:null;
	$ip=($ip=="::1")?"127.0.0.1":$ip;
    return(!preg_match("/^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/",$ip))?showHTML("Sự truy cập của bạn bị cấm vì IP của bạn ko hợp lệ."):$ip;
}
function showHTML($msg,$time=10,$url="",$h=true){global $config;
	if($h){header($_SERVER["SERVER_PROTOCOL"]." 403 Forbidden");}
	header("Content-Type:   text/html; charset=utf-8");
    echo '<html><head><title>S.2.U Firewall System</title><link href="'.$config["s2u_fw_url"].'s2u_firewall_until/css/styleindex.css" rel="stylesheet" type="text/css">
		<script src="'.$config["s2u_fw_url"].'s2u_firewall_until/js/jquery.min.js"></script><script src="'.$config["s2u_fw_url"].'s2u_firewall_until/js/script.js"></script></head>
		<body><p class="welcome">S.2.U Firewall System</p><div class="contentSection"><div class="ar"><div class="statusnob">[ Hệ thống tường lửa ]</div></div>
        <div class="alert"><p>'.$msg.'</p></div>';
	($url=="")?$url=fullAddress():null;
	echo($time>0)?"<div class=\"ref\"><input type=\"hidden\" id=\"url\" value=\"$url\"><span id=\"container\">{$time}</span></div>":null;
    echo "</div></body></html>";unset($url);exit;
}
function closeAS(){global $config;
	$ss=getConfigFW('scoreSystem');
	$end=time()-($config["s2u_fw2_super_time"]*60);
	configSFS('set', 'scoreSystem', "100|$end|".$ss[2]);
}
function closeSP(){global $config;
	$ss=getConfigFW('autoProtect');
	$end=time()-($config["s2u_fw2_protect_time"]*60);
	configSFS('set', 'autoProtect', "$end|".$config["s2u_fw2_protect"]);
	activeProtect(false);
}
function getConfigFW($file){
	$file=S2UFW_BASE.S2UFWDS."s2u_firewall_logs".S2UFWDS.$file;
	$con=file_get_contents($file);
	$s2u=explode("|",$con);unset($con);return $s2u;
}
function updir($path,$n=0,$ds=S2UFWDS){
	$dir=explode($ds, $path);$c=count($dir)-1;
	foreach($dir as $d){$c--;
		if($n<=$c){$p.=$d.$ds;}
	}
	return $p;
}
function blockDomain($dm){
	global $config,$logs;$n=true;$ht=updir(S2UFW_BASE.S2UFWDS,1).$config["s2u_fw_htaccess"];
	($ht==false||!file_exists($ht))?$logs.="Domain block failure -> ":$n=false;
	if($n){return;}$htd=file_get_contents($ht);$ft=fopen($ht, "a");
    if(strpos($htd,$dm)===false){
		if(checkDomain($dm)){fwrite($ft, "\ndeny from $dm");
       	($config["s2u_fw_send_mail"]>=2)?sendMail("Tên miền: $dm đã bị khóa vĩnh viển lúc ".date("H:i:s-d/m/Y").". Tại: ".fullAddress()):null;
		$logs.="Block Domain -> ";}
    }fclose($ft);unset($ft,$ht,$dm,$htd);
}
function blockIP($ip){
    global $config,$logs,$now;$n=true;$ht=updir(S2UFW_BASE.S2UFWDS,1).$config["s2u_fw_htaccess"];
	($ht==false||!file_exists($ht))?$logs.="Block by Page Error -> ":$n=false;if($n){return;}$n=true;
	($config["s2u_fw_userbad"]==1&&$ip==getipFW())?setcookie("user", "bad", 0, '/'):$n=false;
	($n)?$logs.="Block by Cookie -> ":null;
	if($n){return;}$n=true;
    $htd=file_get_contents($ht);
	$ft=fopen($ht, "a");
    if(strpos($htd,$ip)===false){
		(preg_match("/^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/",$ip)||preg_match("/^(((?=(?>.*?(::))(?!.+\3)))\3?|([\dA-F]{1,4}(\3|:(?!$)|$)|\2))(?4){5}((?4){2}|(25[0-5]|(2[0-4]|1\d|[1-9])?\d)(\.(?7)){3})\z/i",$ip))?fwrite($ft, "\ndeny from $ip"):$n=false;
       	($n&&$config["s2u_fw_send_mail"]>=2)?sendMail("Địa chỉ IP: $ip đã bị khóa vĩnh viển lúc ".date("H:i:s-d/m/Y").". Tại: ".fullAddress()):null;
		($n)?$logs.="Block IP -> ":null;
    }fclose($ft);unset($ft,$ht,$ip,$htd);
}
function unlockIP($ip){
    global $config,$logs;
	setcookie("user", "", 0, '/');setcookie("timeout", "");
	$ht=updir(S2UFW_BASE.S2UFWDS,1).$config["s2u_fw_htaccess"];
	configSFS('set', $ip, time()."|0");
    if($ht==false||!file_exists($ht)){return;}
	$htd=file_get_contents($ht);
    if(strpos($htd,$ip)!==false){
		$logs.="Unlock OK -> ";
		$new=str_replace("\ndeny from $ip", "",$htd);
		$ft=fopen($ht, "w");fwrite($ft,$new);fclose($ft);
		($config["s2u_fw_send_mail"]>=2)?sendMail("Địa chỉ IP: $ip đã được mở khóa lúc ".date("H:i:s-d/m/Y")):null;
	}unset($ft,$ht,$htd,$new);
}
function sendMail($msg){
	global $config,$logs;$n=true;
	$mail=$config["s2u_fw_email_admin"];
	($mail!="")?@mail($mail, "Thông báo của S.2.U Firewall System!!!",$msg):$n=false;
	($n)?$logs.="Send Mail -> ":null;
}
function checkUpdate($v,$e=true){
	global $config,$ver;
    $url=base64_decode("aHR0cDovL2FwcC5zMnUudm4vY2hlY2t1cGRhdGUucGhwP3Zlcj0=")."$ver&ref=".$_SERVER["HTTP_HOST"]."&e=".$config["s2u_fw_email_admin"];
    $con=getUrl($url);
    if($con[0]!=0){
		$ver=$con[0];$url=$con[1];
		sendMail("S.2.U Firewall System đã có phiên bản mới! Phiên bản: $ver, Tải và xem hướng dẫn ở: $url");
		checkFileConfig("scoreSystem");$ss=getConfigFW("scoreSystem");
		configSFS("set", "scoreSystem", $ss[0]."|".time()."|$ver");
		if($e){echo base64_encode(utf8_encode("S.2.U Firewall System đã có phiên bản mới!<br/>Phiên bản: v$ver<br/>Tải và xem hướng dẫn ở <a href=\"$url\">[ĐÂY]</a>"));}
	} else {if($e){echo base64_encode(utf8_encode("Bạn đang dùng phiên bản mới nhất!"));}}
	unset($url,$con,$ss);exit;
}
function checkDomain($domain){
   return(!preg_match("/[a-zA-Z0-9](-*[a-zA-Z0-9]+)*(\.[a-zA-Z0-9](-*[a-zA-Z0-9]+)*)+/i",$domain))?false:true;
}
function getStr($string,$start,$end){
	$str=explode($start,$string);
	$str=explode($end,$str[1]);
	return $str[0];
}
function fullAddress(){
	$adr=(isset($_SERVER["HTTPS"])&&$_SERVER["HTTPS"]!="off")?"https://":"http://";
	$adr.=isset($_SERVER["HTTP_HOST"])?$_SERVER["HTTP_HOST"]:getenv("HTTP_HOST");
	$adr.=isset($_SERVER["REQUEST_URI"])?dirname($_SERVER["REQUEST_URI"]):dirname(getenv("REQUEST_URI"));
	return $adr;
}
function getUrl($url,$ref="",$ip=""){
	$ch=curl_init();
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
	($ref!="")?curl_setopt($ch, CURLOPT_REFERER,$ref):null;
	($ip!="")?@curl_setopt($ch, CURLOPT_HTTPHEADER, array("REMOTE_ADDR: $ip", "HTTP_X_FORWARDED_FOR: $ip")):null;
	$data=curl_exec($ch);
	curl_close($ch);
	return $data;
}
function timeEnd($t1,$t2,$t=1){
	return floor($t1-(time()-$t2)/$t);
}
function setAPF($b){
	global $config;
	$ht=updir(S2UFW_BASE.S2UFWDS,1).$config["s2u_fw_htaccess"];
	if($ht==false||!file_exists($ht)){return;}$rep='';$rea='';
	$htd=file_get_contents($ht);
	if($b){
	if(strpos($htd, 'php_value auto_prepend_file "'.S2UFW_BASE.S2UFWDS.'s2u_firewall_system.php"')===false){
		$rep='php_value auto_prepend_file "'.S2UFW_BASE.S2UFWDS.'s2u_firewall_system.php"';
		$rea='php_value auto_append_file "'.S2UFW_BASE.S2UFWDS.'s2u_firewall_antiiframe.php"';
	}else{return;}}else{
		$rep='#php_value auto_prepend_file "s2u_firewall_system.php"';
		$rea='#php_value auto_append_file "s2u_firewall_antiiframe.php"';
	}
	$ft=fopen($ht, "w");
	$rep=preg_replace("/(#)?php_value auto_prepend_file \".*?.php\"/", $rep, $htd);
	$rea=preg_replace("/(#)?php_value auto_append_file \".*?.php\"/", $rea, $rep);
	fwrite($ft,$rea);fclose($ft);
	
}
function activeProtect($act){
	global $config;
	$ht=updir(S2UFW_BASE.S2UFWDS,1).$config["s2u_fw_htaccess"];
	if($ht==false||!file_exists($ht)){return;}
	$htd=file_get_contents($ht);
	$antiSpam="
#!Anti Spam!#
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteCond %{REQUEST_METHOD} POST
RewriteCond %{REQUEST_URI} !.*/(^{$config["s2u_fw2_fnot_spam"]}).*
RewriteCond %{HTTP_REFERER} !(@)?(www\.)?{$config["s2u_fw2_domain_ref"]}/.* [OR]
RewriteCond %{HTTP_REFERER} !$
RewriteRule .* http://{$config["s2u_fw_ref"]} [R=301,L]
</IfModule>
#Anti Spam#";
	if($act){
		if($config["s2u_fw2_antispam"]==0&&strpos($htd, "#!Anti Spam!#")!==false){
			$new=str_replace($antiSpam, "",$htd);$ft=fopen($ht, "w");fwrite($ft,$new);fclose($ft);
		} elseif($config["s2u_fw2_antispam"]==1&&strpos($htd, "#!Anti Spam!#")===false){
			$new=$antiSpam;$ft=fopen($ht, "a");fwrite($ft,$new);fclose($ft);
		}
		autoConfig('s2u_fw2_protect', 2);$_SESSION['protect']=$config["s2u_fw2_protect_name"];
		($config["s2u_fw_send_mail"]>=1)?sendMail("Đã bật chế độ Anti Protect lúc ".date("H:i:s-d/m/Y")):null;
	} else {
		if(strpos($htd, "#Anti Spam#")!==false){
			$new=str_replace($antiSpam, "",$htd);$ft=fopen($ht, "w");fwrite($ft,$new);fclose($ft);
		}
		autoConfig('s2u_fw2_protect', 1);unset($_SESSION['protect']);
		($config["s2u_fw_send_mail"]>=1)?sendMail("Đã tắt chế độ Anti Protect lúc ".date("H:i:s-d/m/Y")):null;
	}
	unset($ft,$new,$htd);return;
}
function activeTimePri($time){
	global $config;
	$ht=updir(S2UFW_BASE.S2UFWDS,1).$config["s2u_fw_htaccess"];
	$time=str_replace('"', "",$time);
	$time=str_replace(" ", "",$time);
	if($ht==false||!file_exists($ht)||strpos($time,"->")===false){return;}
	$htd=file_get_contents($ht);
	$time=explode("->",$time);
	preg_match_all("/ME_(.*?)#/i", $htd, $fix, PREG_PATTERN_ORDER);
	if($time[0]=="NUL"&&strpos($htd, "HOUR} ^$#")===false){
		$new=str_replace($fix[1], "HOUR} ^$", $htd);
		$ft=fopen($ht, "w");fwrite($ft,$new);fclose($ft);
		$closed=file_get_contents(S2UFW_BASE.S2UFWDS."closed.php");
		$new=preg_replace("/ời.*?!/", "ời !!!", $closed);
		$ft=fopen(S2UFW_BASE.S2UFWDS."closed.php", "w");fwrite($ft,$new);fclose($ft);
		$closed=file_get_contents(S2UFW_BASE.S2UFWDS."s2u_firewall_datas.php");
		$new=preg_replace("/\/\*\*\/1\=\>array\(0, '.*?'\)/", "/**/1=>array(0, '')", $closed);
		$ft=fopen(S2UFW_BASE.S2UFWDS."s2u_firewall_datas.php", "w");fwrite($ft,$new);fclose($ft);
		($config["s2u_fw_send_mail"]>=1)?sendMail("Đã tắt thiết lập thời gian cấm lúc ".date("H:i:s-d/m/Y")):null;return;
	}
	$n=$time[0]-1;
	if($time==null){return;}$a=$time[0];$out=(25-$a)+$time[1];
	for($i=0;$i<$out;$i++){
		if($n>22){$n=0;$a=$n+1;}
		else {$n=$a++;}
		$rl=($i<$out-1)?"|":"";
		if($n==$time[1]){$rl="";}
		if($n<10){$n="0".$n;}
		$tn.=$n.$rl;
		if($n==$time[1]){break;}
	}
	$blocktime="HOUR} ^".$tn."$";
	$closed=file_get_contents(S2UFW_BASE.S2UFWDS."closed.php");
	$new=preg_replace("/ời.*?!/", "ời từ {$time[0]}h tới {$time[1]}h !", $closed);
	$ft=fopen(S2UFW_BASE.S2UFWDS."closed.php", "w");fwrite($ft,$new);fclose($ft);
	$new=str_replace($fix[1], $blocktime, $htd);
	$ft=fopen($ht, "w");fwrite($ft,$new);fclose($ft);
	$closed=file_get_contents(S2UFW_BASE.S2UFWDS."s2u_firewall_datas.php");
	$new=preg_replace("/\/\*\*\/1\=\>array\(0, \".*?\"\)/", "/**/1=>array(0, \"{$time[0]}->{$time[1]}\")", $closed);
	$ft=fopen(S2UFW_BASE.S2UFWDS."s2u_firewall_datas.php", "w");fwrite($ft,$new);fclose($ft);
	($config["s2u_fw_send_mail"]>=1)?sendMail("Đã thiết lập thời gian cấm. Từ $time giờ."):null;
	unset($ft,$new,$ht,$htd,$closed);return;
}
function checkFileConfig($file){
	global $config,$ver;
	if(!file_exists(S2UFW_BASE.S2UFWDS."s2u_firewall_logs".S2UFWDS.$file)){
		switch($file){
			case"autoProtect":configSFS("set",$file,time()."|".$config["s2u_fw2_protect"]);break;
			case"scoreSystem":configSFS("set",$file, "100|".time()."|$ver");break;
			case"autoClear":configSFS("set", "autoClear", time());break;			
		}
	}return;
}
function deleteAll($t=false){
	global $logs;$directory=S2UFW_BASE.S2UFWDS."s2u_firewall_logs";
	(substr($directory,-1)=="/")?$directory=substr($directory, 0, -1):null;
    if(!file_exists($directory)||!is_dir($directory)){return false;}
	elseif(!is_readable($directory)){$logs.="Logs folder is denied! ->";return false;}else{
		$con=glob($directory."/*");
        foreach($con as $ip){
			$ip=str_replace($directory."/","",$ip);$path=$directory."/".$ip;
			if($ip!="."&&$ip!=".."&&$ip!="scoreSystem"&&$ip!="autoClear"&&$ip!=".htpasswd"&&$ip!="autoProtect"&&strpos($ip,".admin")===false){
				$ips=getConfigFW($ip);
				if($t){($ips[4])?null:unlink($path);}
				else{($ips[3])?null:unlink($path);}
            }
        }
		unset($con,$ip,$ips);return true;
    }
}
function ghiLog($logs){global $config,$time_start;
	if($config["s2u_fw_logs"]==1){$time=microtime();$time=explode(" ", $time);$time=$time[1]+$time[0];$tt=round(($time-$time_start), 3);unset($time_start);
	(!file_exists(S2UFW_BASE.S2UFWDS."logs.txt")||$_GET["go"]=="dellog")?delLogs():$ft=fopen(S2UFW_BASE.S2UFWDS."logs.txt", "a");
	if($ft){fwrite($ft,$logs." ($tt s)");fclose($ft);}}
}
function delLogs(){
	$ft=fopen(S2UFW_BASE.S2UFWDS."logs.txt", "w");
	fwrite($ft,"");fclose($ft);return 1;
}
function emt($id,$c,$vl){global $config;
	(!isset($vl))?$vl="":null;$html="";
	switch ($c[0]){
		case 0:if(is_array($vl)){$a=($vl[0]=="0")?$vl[1]:$vl[0];}else{$a=$vl;}
		$html="<li>= <input id=\"$id\" accept=\"text/plain\" type=\"text\" value=\"$a\"/></li>\n";break;
		case 1:$w=(isset($c[2]))?"style=\"width:70%\"":null;$html="<li>= <input id=\"$id\" accept=\"text/plain\" type=\"number\" value=\"$vl\" $w /><b class=\"lable\">{$c[1]}</b></li>\n";break;
		case 2:$html="<li>= <select id=\"$id\">";$n=0;$cc=count($c)-1;
				for($i=1;$i<$cc;$i++){$sl="";
					($vl==$n)?$sl="selected=$vl":null;
					$html.="<option $sl value=\"$n\">{$c[$i]}</option>\n";$n++;
				}$html.="</select></li>\n";break;
		case 3: $em=base64_encode(emt($id,$c[2],$vl));$m="";$idi="id=\"$id\"";
				if($vl!='""'&&$vl!="0"&&$vl!=""){
					$m="onMouseOver=\"showNote('".urlencode($c[3])."','$em',$id)\"";
					$idi="id=\"NUL\"";
				}
				$html="<li id=\"btn$id\">= <input $idi type=\"button\" $m onClick=\"onoff($id,'$vl','$em','".urlencode($c[3])."')\" value=\"".btn($vl)."\"/></li>\n";
		break;
		case 4: if(is_array($vl)){$a=$vl[1];}else{$a=$vl;}
		$html="<li id=\"btn$id\">= <input id=\"$id\" type=\"button\" onClick=\"onoff($id,'$a','','')\" value=\"".btn($a)."\"/></li>\n";break;
		case 5: $html="<li id=\"$id\"><a href=\"{$config["s2u_fw_url"]}s2u_firewall_admin.php?go=".$vl[2]."\" target=\"_blank\">".$vl[1]."</a></li>\n";break;
		case 6: $html="<iframe src=\"{$config["s2u_fw_url"]}s2u_firewall_until/".$vl[1]."\" width=\"100%\" height=\"29\" frameborder=\"0\"></iframe>\n";break;
	}return $html;
}
function btn($c){return($c==""&&$c==0)?"Tắt":"Bật";}
function stt($c,$l=0,$s=""){
	$st="<li style=\"color: #10bbfc;$s\">";
	for($i=0;$i<=$l;$i++){
		$br=($l>0)?"<br/>":null;
		$st.=($c>=2)?"Chưa kiểm tra$br":null;
	}
	($c==0)?$st="<li style=\"color: #ff6f28\">Không tương thích":null;
	($c==1)?$st="<li style=\"color: #99ff00\">Tương thích":null;
	$st.="</li>";return $st;
}
function loginFW($pass){
	global $config;$hash = md5($pass);$ip = getipFW();
	if($config["s2u_fw_password"]==$hash){
		configSFS("set", $ip.".admin", time());
		$_SESSION["Commander"]=$hash;unset($_SESSION["denylogin"]);
	} else {
		$_SESSION["denylogin"]=time();
	}
	echo 1;exit;
}
function logoutFW(){$ip = getipFW();
	configSFS("del", $ip.".admin");
	unset($_SESSION["denylogin"]);
	unset($_SESSION["Commander"]);
	echo 1;exit;
}
function getInfoFW($directory="s2u_firewall_logs"){global $config,$ver;
	$directory=S2UFW_BASE.S2UFWDS.$directory;
	(substr($directory,-1)=="/")?$directory=substr($directory, 0, -1):null;
    if(!file_exists($directory)||!is_dir($directory)){return "Lấy thông tin tường lửa thất bại";}
	elseif(!is_readable($directory)){return "Không có quyền truy cập thư mục: $directory";}else{checkFileConfig('autoClear');
		$ss=getConfigFW("scoreSystem");$ac=getConfigFW("autoClear");checkFileConfig("autoProtect");$ap=getConfigFW("autoProtect");
		$score=($ss[0]<=100)?$ss[0]:100;$ac=floor(timeEnd($config["s2u_fw_time_clear"],$ac[0], 60));
		if($ac<0){$ac="chờ truy cập";}else{$ac="$ac phút";}$con=glob($directory."/*");
		if($config["s2u_fw_type"]!=0){$tw=($config["s2u_fw_type"]==1)?"Khóa tức thời":"Chặn từng bước";}else{$tw="Không hoạt động";}
		$ap=floor(timeEnd($config["s2u_fw2_protect_time"],$ap[0], 60));($ap<0||$config["s2u_fw2_protect"]==(0|1))?$ap=0:null;
		$as=floor(timeEnd($config["s2u_fw2_super_time"], $ss[1], 60));($as<0||$config["s2u_fw2_super"]==0)?$as=0:null;
		$uon=0;foreach($con as $ip){$ip=trim(str_replace($directory."/","",$ip));
		if($ip!="."&&$ip!=".."&&$ip!="scoreSystem"&&$ip!="autoClear"&&$ip!=".htpasswd"&&$ip!="autoProtect"&&strpos($ip,".admin")===false){$ips=getConfigFW($ip);$ton=time()-$ips[0];if($ton<=300){$uon++;}}}
    }unset($ss,$directory);$cap=($ap>0)?" <b style=\"color: #0099ff\"><a title=\"Tắt chế độ Super Protect\" onClick=\"callFunction('closesp')\">( Tắt )</a></b>":null;$cas=($as>0)?" <b style=\"color: #0099ff\"><a title=\"Tắt chế độ Anti Super\" onClick=\"callFunction('closeas')\">( Tắt )</a></b>":null;
	return "Phiên bản tường lửa: <b>v$ver</b><br/>Điểm hệ thống: <b>{$score}</b> điểm.<br/>Chế độ tường lửa: <b>$tw</b><br/>Ram sử dụng: <b>MU</b> ~ Số người đang online: <b>$uon</b><br/>Còn <b>$ac</b> để dọn dẹp hệ thống tường lửa.<br/>Thời gian của chế độ Super Protect: <b>$ap phút</b>.$cap<br/>Thời gian của chế độ Anti Super: <b>$as phút</b>.$cas";
}
function getListIP($directory="s2u_firewall_logs"){global $config,$logs;$time=time();$n=false;
	$directory=S2UFW_BASE.S2UFWDS.$directory;
	(substr($directory,-1)=="/")?$directory=substr($directory, 0, -1):null;
    if(!file_exists($directory)||!is_dir($directory)){$logs.="Logs folder is denied! ->";return false;}
	elseif(!is_readable($directory)){return "Không có quyền truy cập thư mục: $directory";}else{
		$con=glob($directory."/*");include(S2UFW_BASE.S2UFWDS."s2u_firewall_geoiploc.php");
        foreach($con as $ip){
			$ip=trim(str_replace($directory."/","",$ip));
			if($ip!="."&&$ip!=".."&&$ip!="scoreSystem"&&$ip!="autoClear"&&$ip!=".htpasswd"&&$ip!="autoProtect"&&strpos($ip,".admin")===false){
				$flag=strtolower(getCountryFromIP($ip));($flag=='')?$flag="zz":null;
				$ips=getConfigFW($ip);$timeUnlock=floor((($config["s2u_fw_type"]==2)?$config["s2u_fw2_time_unlock"]:$config["s2u_fw1_time_unlock"])-(time()-$ips[4])/60);
				$max=($config["s2u_fw_type"]==2)?$config["s2u_fw2_penalty_allow"]:$config["s2u_fw1_penalty_allow"];$tu=($timeUnlock<0)?"đang chờ Unlock":"còn $timeUnlock phút";
				$stt="<b style=\"color: #99ff00\">Bình thường</b>";($ips[3])?$stt="<b style=\"color: #faee0e\">Đang cảnh báo</b>":null;$ton=time()-$ips[0];if($ton<=300){$uon="(<span style=\"color: #72ffff\">Online</span>)";}else{$uon="";}
				if($ips[4]){(($now-$ips[4])/60>=$config["s2u_fw2_time_unlock"])?unlockIP($ip):$stt="<b style=\"color: #fc5110\"><a title=\"Mở khóa IP này\" onClick=\"callFunction('unlockip','$ip')\">Đã chặn</a></b> ($tu)";}
				$listip.="<img src=\"{$config["s2u_fw_url"]}s2u_firewall_image/flag/$flag.png\" weight=\"16\" title=\"".getCountryFromIP($ip, "name")."\"> <b>$ip</b> | Vào trước đó: <b>".cSec($ton)."</b>| Trạng thái: $stt $uon<br/>";
            }
        }
    }
	(!isset($listip))?$listip="Không có địa chỉ IP theo dõi trong hệ thống.<br/>":null;
	unset($con,$ip,$ips,$max,$GLOBALS['geoipaddrfrom'],$GLOBALS['geoipaddrupto'],$GLOBALS['geoipctry'],$GLOBALS['geoipcntry'],$GLOBALS['geoipcountry']);return $listip;
}
function getLogs(){
	$log=@file_get_contents(S2UFW_BASE.S2UFWDS."logs.txt");
	if(!$log){return "Không có thông tin về nhật ký hệ thống.";}
	return(mb_strlen($log)<80000)?"<textarea cols=\"81%\" rows=\"15\">$log</textarea>":"Không thể lấy nhật ký quá lớn, bạn hãy xóa hoặc xem trực tiếp ở <a href=\"{$config["s2u_fw_url"]}logs.txt\">[ĐÂY]</a>";
}
function cSec($time) {
	global $lang;
	$time += $time > 60 ? 30 : 0;
	$days = floor($time / 86400);
	$time %= 86400;
	$hours = floor($time / 3600);
	$time %= 3600;
	$minutes = floor($time / 60);
	$seconds = floor($time % 60);
	$return = array();
	($days>0)?$return[]=$days." ngày":null;
	($hours>0)?$return[]=$hours." tiếng":null;
	($minutes>0)?$return[]=$minutes." phút":null;
	($seconds>0)?$return[]=$seconds.(date("m/d")=="06/03" ? " sex" : "s"):null;
	return implode(", ", $return);
}
function cByte($s,$p=2){
	if(!is_numeric($s))return"?";$n=1024;
	$types = array("B", "KB", "MB", "GB", "TB");$cc=count($types)-1;
	for($i=0;$s>=$n&&$i<$cc;$s/=$n,$i++);
	return(round($s, $p)." ".$types[$i]);
}
function showMemory(){
	return cByte(memory_get_usage());
}
function getStatus($arr){global $config;
	if($arr==null){echo "<center style=\"color:#F60;font-size:40px;\">Không thể lấy thông tin hệ thống tường lửa trên website bạn.<br/><img src=\"{$config["s2u_fw_url"]}s2u_firewall_image/error.png\"/></center>";return;}
	$html.="Tình trạng";
	checkFileConfig("scoreSystem");
	foreach ($arr as $i => $n){
		($i==1)?$html.="~":null;($i==2)?$html.="~":null;
		$html.=getSysIfno($n[0],$n[3]);
	}
	$mu=showMemory();$html=preg_replace("/MU/",$mu,$html);unset($mu,$os,$arr,$i,$n);
	echo base64_encode(utf8_encode($html));exit;
}
function getSysIfno($t,$s=""){
	switch ($t){
		case "FWS":$vl=getInfoFW();break;
		case "IPS":$vl=getListIP();break;
		case "LOGS":$vl=getLogs();break;
	}return "<li id=\"{$t}G\" style=\"$s\">$vl</li>\n";
}
function checkStatus($arr){
	$n=3;$html.="Tình trạng";
	foreach ($arr as $ar => $vl){
		if($ar==9){$html.="~";break;}
		switch($vl[2]){
			case 0:$vl[$n]=(ini_get($vl[0]))?0:1;break;
			case 1:$vl[$n]=(extension_loaded($vl[0]))?1:0;break;
			case 2:$vl[$n]=(function_exists($vl[0]))?1:0;break;
		}
		
		$html.=stt($vl[$n]);
	}$cc=count($arr);
	($vl[6]==1)?autoConfig("s2u_fw_email_admin",'""'):null;
	($vl[7]==0)?setAPF(true):setAPF(false);
	for($i=9;$i<$cc;$i++){
		$html.=($vl[$n]==0)?stt(0):stt(goChmod($arr[$i][1],$arr[$i][$n]));
	}unset($arr,$ar,$vl,$cc);
	echo base64_encode(utf8_encode($html));exit;
}
function loadDefault(){
	global $listCf;
	$i=0;$html.="Thông số";
	foreach ($listCf as $ls => $cf){
		($ls==14||$ls==16)?$html.="~":null;
		$n=count($cf[3])-1;
		$html.=emt($i,$cf[3],$cf[3][$n]);
		$i++;
	}
	echo base64_encode(utf8_encode($html));exit;
}
function loadRestore(){
	global $listCf;
	$i=0;$html.="Thông số";
	foreach ($listCf as $ls => $cf){
		($ls==14||$ls==16)?$html.="~":null;
		$html.=emt($i,$cf[3],$cf[1]);
		$i++;
	}
	echo base64_encode(utf8_encode($html));exit;
}
function saveConf($arr){
	global $listCf,$config;$i=0;
	$vl=explode(",",$arr);
	foreach($listCf as $cf){
		$str=$vl[$i];
		($str=="NUL")?$str='""':null;
		if($cf[4]=="s2u_fw_password"&&$str!="0"){if($str=="NUL"){$str="s2u";}elseif($str!='""'){$str=md5($str);unset($_SESSION["Commander"]);}else{$str=$config["s2u_fw_password"];}}
		if($cf[4]=="s2u_fw_ipw"&&$str!="0"){if($str=='""'||$str==""){$str=$config["s2u_fw_ipw"];}}
		if($cf[4]=="s2u_fw_country"&&$str!="0"){if($str=='""'||$str==""){$str=$config["s2u_fw_country"];}}
		if($cf[4]=="s2u_fw2_super"&&$str!="0"){$ss=getConfigFW('scoreSystem');configSFS('set', 'scoreSystem', $ss[0]."|".time()."|".$ss[2]);}
		(!is_numeric($str)&&$str!='""')?$str="\"$str\"":null;
		($str!="\"NUL\"")?autoConfig($cf[4],$str):null;
		$i++;
	}
	echo 1;exit;
}
function saveTool($arr){
	global $config;$i=0;$vl=explode(",",$arr);
	foreach($vl as $cf){
		$str=$vl[$i];
		switch($i){
			case 0:
				$str=explode("|",$str);
				if($str[1]){$now=time();$tb=($now-$config["s2u_fw2_time_unlock"]*60)+$str[1]*60;
				configSFS("set", $str[0], "$now|6|$now|3|$tb");blockIP($str[0]);}
			break;
			case 1:activeTimePri($str);break;
		}
		$i++;
	}
	echo 1;exit;
}
function checkIPDeny($directory="s2u_firewall_logs"){global $config;
	$directory=S2UFW_BASE.S2UFWDS.$directory;
	(substr($directory,-1)=="/")?$directory=substr($directory, 0, -1):null;
    if(!file_exists($directory)||!is_dir(S2UFW_BASE.S2UFWDS.$directory)){$logs.="Logs folder is denied! ->";return false;}
	elseif(is_readable($directory)){
		$con=glob($directory."/*");
        foreach($con as $ip){
			$ip=str_replace($directory."/","",$ip);
			if($ip!="."&&$ip!=".."&&$ip!="scoreSystem"&&$ip!="autoClear"&&$ip!=".htpasswd"&&$ip!="autoProtect"&&strpos($ip,".admin")===false){
				$ips=getConfigFW($ip);$timeUnlock=floor((($config["s2u_fw_type"]==2)?$config["s2u_fw2_time_unlock"]:$config["s2u_fw1_time_unlock"])-(time()-$ips[4])/60);
				if($ips[4]){($timeUnlock<0)?unlockIP($ip):null;}
            }
        }
    }unset($directory,$con,$ip,$ips);
}
function getCT($url){
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_exec($ch);
	return curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
}
?>