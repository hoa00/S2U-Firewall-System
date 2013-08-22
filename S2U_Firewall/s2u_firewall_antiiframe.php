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
if($config['s2u_fw_iframe']==1){
	header("Content-Type: text/html; charset=utf-8");
	echo '<script>var readyStateCheckInterval=setInterval(function(){
		if(document.readyState==="complete"){if(frames.length>0){clearInterval(readyStateCheckInterval);alert("Cảnh báo! Trang này đang có chèn iframe!!!");if(top.location!=self.location){top.location=self.location;}}}
	},10);</script>';
}
?>