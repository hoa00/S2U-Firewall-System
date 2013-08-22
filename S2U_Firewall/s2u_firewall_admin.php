<?
###########################################################
### S.2.U Application - http://app.s2u.vn				###
### S.2.U Firewall System by Mr.Won						###
### Phiên bản 2.7 - 30/06/2013							###
### Không xóa bản quyền nhé mấy đại ca!!				###
###########################################################

###########################################################
### Chú ý: Nội dung file này không được chỉnh sửa.		###
###########################################################
$time=microtime();$time=explode(" ", $time);$time=$time[1]+$time[0];$time_start=$time;unset($time);define('S2UFW_BASE',dirname(__FILE__));define('S2UFWDS',DIRECTORY_SEPARATOR );
//////////////////////////////Int////////////////////////////
include_once(S2UFW_BASE.S2UFWDS."s2u_firewall_datas.php");
include_once(S2UFW_BASE.S2UFWDS."s2u_firewall_config.php");
$i=0;header('Content-Type: text/html; charset=utf-8');
if(isset($_GET['go'])&&$_GET['go']=="checkUnlock"){
	$ip=getipFW();$ips=getConfigFW($ip);
	if($ips[4]){
		((time()-$ips[4])/60>=$config['s2u_fw2_time_unlock'])?unlockIP($ip):null;
	}return;
}
foreach ($config as $cf => $vl){
	$listCf[$i]=array($arrConfigName[$i],$vl,$arrConfigNote[$i],$arrConfigEmt[$i],$cf);$i++;
}
$i=0;unset($arrConfigName,$arrConfigNote,$arrConfigEmt);
foreach ($arrToolEmt as $cf => $vl){
	$listTool[$i]=array($arrToolName[$i],$arrToolNote[$i],$arrToolEmt[$i]);$i++;
}unset($arrToolEmt,$arrToolName,$arrToolNote,$cf,$vl);
switch($_POST['go']){
	case 'default':loadDefault();exit;
	case 'restore':loadRestore();exit;
	case 'save':saveConf($_POST['value']);exit;
	case 'add':saveTool($_POST['value']);exit;
	case 'check':checkStatus($arrCheckEmt);exit;
	case 'status':getStatus($arrSttEmt);exit;
	case 'dellog':delLogs();exit;
	case 'clearip':deleteAll(true);exit;
	case 'updatefw':checkUpdate($ver);exit;
	case 'unlockip':unlockIP($_POST['value']);getStatus($arrSttEmt);exit;
	case 'closesp':closeSP();getStatus($arrSttEmt);exit;
	case 'closeas':closeAS();getStatus($arrSttEmt);exit;
	case 'stopud':echo 0;exit;
	case 'autoud':echo 1;exit;
	case 'login':loginFW($_POST['value']);checkUpdate($ver,false);exit;
	case 'logout':logoutFW();exit;
	default:break;
}
if(isset($_SESSION['Commander'])&&file_exists(S2UFW_BASE.S2UFWDS."s2u_firewall_logs".S2UFWDS.getipFW().".admin")||($config['s2u_fw_password']==0&&$config['s2u_fw_password']==''&&$config['s2u_fw_password']=='""')){$admin=true;}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>S.2.U Firewall System - Administrator</title>
<link rel="shortcut icon" href="./s2u_firewall_image/favicon.ico">
<link rel="stylesheet" href="./s2u_firewall_until/css/styleadmin.css" type="text/css">
<script src="./s2u_firewall_until/js/jquery.min.js"></script>
<script src="./s2u_firewall_until/js/jquery.hotkeys.js"></script>
<script src="./s2u_firewall_until/js/script.js" charset="utf-8"></script>
</head>
<body id="home">
<div class="s2utop">
	<div class="headertop">
    	<div class="logo">
        	<a id="logo" href="/">S.2.U Firewall System - Quản lý hệ thống tường lửa</a>
        </div>
        <div class="menus">
        	<ul class="menu">
				<? if($admin){ ?>
            	<li>
                	<a id="trangchubtn" href="#trangchu" class="">Trang chủ</a>
                </li>
            	<li>
                	<a id="cauhinhbtn" href="#cauhinh" class="">Cấu hình</a>
                </li>
                <li>
                	<a id="kiemtrabtn" href="#kiemtra" class="">Kiểm tra</a>
                </li>
                <li>
                	<a id="tinhtrangbtn" href="#tinhtrang" class="">Tình trang</a>
                </li>
				<li>
                	<a id="congcubtn" href="#congcu" class="">Công cụ</a>
                </li>
				<li>
                	<a href="http://app.s2u.vn/ff/" class="">Hướng dẫn</a>
                </li>
				<? } ?>
            </ul>
			<ul class="option"></ul>
        </div>
    </div>
</div>
<div class="s2umain">
	<div id="trangchu" class="main wrap">
		
	<? if($admin){ ?>
		<img src="s2u_firewall_image/success.png" height="128"/>
		<div style="padding-top: 50px;">Chào Commander!<br/><br/><?php if($config['s2u_fw_password']!=0||!is_numeric($config['s2u_fw_password'])){?><a onClick="callFunction('logout',0)">[THOÁT]</a><?php }?></div>
        <div><br/>S.2.U Firewall System v<? echo $ver; ?></div>
	<? } else if(floor((timeEnd($config['s2u_fw_pass_time'],$_SESSION['denylogin'], 60)-1)*60)<=0||(!$_SESSION['denylogin']&&!isset($_SESSION['Commander']))){?>
		<img src="s2u_firewall_image/login.png" height="128"/>
		<div style="padding-top: 50px;">Đăng nhập vào hệ thống</div>
        <input id="login" type="password" onKeyUp="callFunction('login',event)"/>
	<? } else { ?>
		<img src="s2u_firewall_image/deny.png" height="128"/>
		<div style="padding-top: 50px;">Bạn vui lòng chờ <? echo cSec((timeEnd($config['s2u_fw_pass_time'],$_SESSION['denylogin'],60)-1)*60) ?> để thử lại!</div>
	<? } ?>
		<div><br/>IP: <? echo getipFW(); ?></div>
	</div>
	<? if($admin){ ?>
	<div id="cauhinh" class="main warp">
        <div class="contant">
        	<ul>
			<?
			$html='Cấu hình chung';
			foreach ($listCf as $ls => $cf){
				($ls==14)?$html.='Cấu hình chế độ Firewall khóa tức thời':null;
				($ls==16)?$html.='Cấu hình chế độ Firewall chặn từng bước':null;
				$html.="<li onMouseOver=\"showNote('".urlencode($cf[2])."','')\">{$cf[0]}</li>\n";
			}
			echo $html;unset($html,$cf);
			?>
            </ul>
        </div>
        <div class="vl">
       		<ul id='conf'>
			<?
			$i=0;$html.='Thông số';
			foreach ($listCf as $ls => $cf){
				($ls==14||$ls==16)?$html.='~':null;
				if($i<35){$html.=emt($i,$cf[3],$cf[1]);}
				$i++;
			}
			$ne=$i;
			echo $html;unset($html,$ls,$cf);
			?>
            </ul>
        </div>
    </div>
	<div id="kiemtra" class="main wrap">
		<div class="contant">
        	<ul>
			<?
			$html='Yêu cầu của hệ thống';
			foreach ($arrCheckEmt as $i => $n){
				($i==9)?$html.='CHMOD hệ thống firewall':null;
				$html.="<li>{$n[1]}</li>\n";
			}
			echo $html;unset($html,$n);
			?>
            </ul>
        </div>
        <div class="vl">
       		<ul id='check'>
			<?
			$html.='Tình trạng';
			foreach ($arrCheckEmt as $i => $n){
				($i==9)?$html.='~':null;
				$html.=stt($n[3]);
			}
			echo $html;unset($html,$i,$n);
			?>
            </ul>
        </div>
	</div>
	<div id="tinhtrang" class="main wrap">
		<div id="cstt" class="contant">
        	<ul>
			<?
			$html='Tình trạng hệ thống trường lửa';
			foreach ($arrSttEmt as $i => $n){
				($i==1)?$html.='Danh sách IP theo dõi <a title="Dọn dẹp IP theo dõi" onClick="callFunction(\'clearip\')"><b>[Làm sạch]</b></a>':null;
				($i==2)?$html.='Nhật ký hệ thống tường lửa <a title="Xóa nhật ký" onClick="callFunction(\'dellog\')"><b>[Làm sạch]</b></a>':null;
				$html.="<li id='{$n[0]}' type='text/x-handlebars-template'>{$n[1]}</li>\n";
			}
			echo $html;unset($html,$n);
			?>
            </ul>
        </div>
        <div class="vl">
       		<ul id='status'>
			<?
			$html.='Tình trạng';
			foreach ($arrSttEmt as $i => $n){
				($i==1)?$html.='~':null;($i==2)?$html.='~':null;
				$html.=stt(2,$n[2],$n[3]);
			}
			echo $html;unset($html,$i,$n);
			?>
            </ul>
        </div>
	</div>
	<div id="congcu" class="main wrap">
		<div class="contant">
        	<ul>
			<?
			$html='Công cụ hệ thống tường lửa';
			foreach ($listTool as $ls => $cf){
				$html.="<li onMouseOver=\"showNote('".urlencode($cf[1])."','')\">{$cf[0]}</li>\n";
			}
			echo $html;unset($html,$cf);
			?>
            </ul>
        </div>
        <div class="vl">
       		<ul id='conf'>
			<?
			$i=$ne;$html.='Thông số';
			foreach ($listTool as $ls => $cf){
				if($i<$ne+3){$html.=emt($i,$cf[2],$cf[2]);}
				$i++;
			}
			echo $html;unset($html,$ls,$cf,$listTool);
			?>
            </ul>
        </div>
	</div>
	<div class="fix">
		<div class="note" style="display: none;"></div>
		<div class="pop" style="display: none;"></div>
	</div>
	<div class="main wrap"><a href="#trangchu"><div id="backtop"></div></a></div>
	<? } ?>
</div>
</body>
</html>