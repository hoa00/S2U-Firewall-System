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
define('S2UFW_BASE',dirname(__FILE__));define('S2UFWDS',DIRECTORY_SEPARATOR );
header('Content-Type: xml; charset=utf-8');
include_once(S2UFW_BASE.S2UFWDS."s2u_firewall_func.php");
if($_GET['stt']){
	$hd=get_headers($_GET['stt']);
	$get=getStr($hd[0]," "," ");
	echo $get;
	exit;
}
echo '<?xml version="1.0" encoding="UTF-8"?>
<configs><config speed="0.1" domain="',$_SERVER['HTTP_HOST'],'">
	<url>
		<loc><![CDATA[http://'.$_SERVER["HTTP_HOST"].']]></loc>
	</url>
</config></configs>';
?>