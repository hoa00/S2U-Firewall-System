<?php
#################################
### S.2.U Firewall System by Mr.Won         ###
### Phiên bản 3.0 - 24/03/2016                ###
#################################

###########################################
### Chú ý: Luôn lưu config dưới dạng UTF-8 without BOM  ###
###########################################

//Chế độ của Firewall.
$config['s2u_fw_active']=1; //-> 0 Ngưng hoạt động, 1 Chặn theo lượt truy cập, 2 Tạo vùng cách ly (Trang web ảo).
	
//Đóng băng trang web
$config['s2u_fw_cache']=1; //-> 0 Tắt, 1 lần tải tiếp theo sẽ lấy cache của trình duyệt.
	
//Thời gian đóng băng
$config['s2u_fw_cache_timeout']=60; //-> Tính theo giây.

//Sửa các URL lỗi trong trang
$config['s2u_fw_fix_url']=1; //-> 0 Tắt, 1 Chạy kiểm tra URL trong trang rồi mới xuất cho trình duyệt xử lý.

//Thời gian lưu liên kết lỗi
$config['s2u_fw_check_timeout']=86400; //-> Tính theo giây.
	
//Thời gian tồn tại của file cache.
$config['s2u_fw_cache_clear']=3600; //-> Tính theo giây.

//Cho phép IP được vượt tường lửa.
$config['s2u_fw_ipw']=''; //-> VD: '192.168.0.1|192.168.0.2|192.168.0.3'.

//Chặn truy cập từ các nước khác.
$config['s2u_fw_country']=''; //-> Nhập mã nước để chặn, VD: 'CN|US|UK'.

//Các file được bỏ qua firewall.
$config['s2u_fw_file_allow']='fw_system.php|fw_admin.php'; //-> Giúp tránh xung đột hoặc chạy không được, VD: 'song.php|download.php'.
	
//Số lần kết nối cảnh báo trong 1s ([s2u_fw1_penalty_allow]/s).
$config['s2u_fw_medium_allow']=5; //-> Càng cao càng gắt.

//Số lần kết nối nguy hiểm trong 1s ([s2u_fw1_penalty_allow]/s).
$config['s2u_fw_penalty_allow']=10; //-> Càng cao càng gắt.

//Thời gian ip bị cảnh báo.
$config['s2u_fw_time_wait']=10; //-> Tính theo giây.

//Thời gian ip bị khóa bằng .htaccess được mở khóa.
$config['s2u_fw_time_unlock']=1800; //-> Tính theo giây.

//Gửi thông báo qua Email khi có IP bị chặn.
$config['s2u_fw_send_mail']=2; //-> 0 tắt, 1 thông báo IP bị khóa, 2 thêm thông báo IP bị cảnh báo.

//Địa chỉ Email để gửi thông báo.
$config['s2u_fw_email_admin']='won.baria@gmail.com'; //-> Nếu để trống thì chức năng này bị tắt.

//Mật khẩu đăng nhập quản lý hệ thống firewall. mặc định: s2u.
$config['s2u_fw_password']='503ceee97024bf073f1ff49ee057be13'; //-> 0 không sử dụng, sử dụng mã hóa MD5.

//File .htaccess trên web để khóa ip theo thời gian quy định.
$config['s2u_fw_htaccess']='../.htaccess'; //-> Nếu để trống thì chức năng này bị tắt.
	
//File chưa nội quy cache
$config['s2u_fw_urls']='fw_urls.json'; //-> Không được để trống.
	
//File chứa nội quy ip.
$config['s2u_fw_ips']='fw_ips.json'; //-> Không được để trống.

//Địa chỉ đặt S2U Firewall. vd: app.s2u.vn/testfw/S2U_FW/.
$config['s2u_fw_url_fw']='';

###########################################
### Chú ý: Luôn lưu config dưới dạng UTF-8 without BOM  ###
###########################################
?>
