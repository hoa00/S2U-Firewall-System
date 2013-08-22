<?
###########################################################
### S.2.U Application - http://app.s2u.vn				###
### S.2.U Firewall System by Mr.Won						###
### Phiên bản 2.7 - 30/06/2013							###
### Không xóa bản quyền nhé mấy đại ca!!				###
###########################################################

###########################################################
### Chú ý: Luôn lưu config dưới dạng UTF-8 without BOM	###
###########################################################

### Cấu hình chung ||||||||||||||||||||||||||||||||||||||||

//Chế độ của Firewall.
$config["s2u_fw_type"]=2; //-> 0 Không hoạt động, 1 Khóa tức thời, 2 Chặn từng bước.

//Khoảng thời gian làm sạch log IP. 0 tắt.
$config["s2u_fw_time_clear"]=1440; //-> Tính theo phút.

//Mật khẩu đăng nhập quản lý hệ thống firewall. mặc định: s2u
$config["s2u_fw_password"]="503ceee97024bf073f1ff49ee057be13"; //-> 0 không sử dụng, sử dụng mã hóa MD5

//Thời gian chặn đăng nhập khi đăng nhập sai. mặc định: 5 phút
$config["s2u_fw_pass_time"]=5; //-> 0 không sử dụng, tính theo phút.

//Bật tắt tính năng gửi mail thông báo.
$config["s2u_fw_send_mail"]=2; //-> 0 tắt, 1 (thông báo Web bị ddos, lỗi), 2 +(thông báo ip bị khóa vĩnh viễn, làm sạch IP), 3 +thông báo ip bị khóa tạm thời.

//Email để thông báo cho người bị khóa liên hệ.
$config["s2u_fw_email_admin"]=""; //-> Nếu để trống thì chức năng này bị tắt.

//File .htaccess trên web để khóa ip theo thời gian quy định.
$config["s2u_fw_htaccess"]=".htaccess"; //-> Nếu để trống thì chức năng này bị tắt.

//Khóa người dùng theo địa chỉ IP kết hợp cookie. 0 tắt, 1 bật.
$config["s2u_fw_userbad"]=0; //-> Cách chặn kết hợp để tránh ảnh hưởng các máy khác trong mạng LAN.

//Cho phép IP được vượt tường lửa.
$config["s2u_fw_ipw"]=0; //-> VD: "192.168.0.1|192.168.0.2|192.168.0.3"

//Chặn truy cập từ các nước khác.
$config["s2u_fw_country"]=0; //-> Nhập mã nước để chặn, 0 = tắt. VD: "CN|US|UK"

//Chặn iframe.
$config["s2u_fw_iframe"]=1; //-> 0 tắt, 1 bật.

//Ghi log.
$config["s2u_fw_logs"]=1; //-> 0 tắt, 1 bật.

//Tên miền chuyển đến khi IP bị khóa. Người truy cập phải xóa cache mới unlock được.
$config["s2u_fw_ref"]="google.com.vn"; //-> Kích hoạt khi không thể Block bằng .htaccess

//Địa chỉ thư mục Firewall
$config["s2u_fw_url"]=""; //-> Cần thiết lập.

#///////////////////////////////////////////////////////////
### Chế độ khóa tức thời |||| Chế độ đơn giản nhưng mạng mẽ
#\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

//Số lần kết nối cho phép trong 1s ([s2u_fw1_penalty_allow]/s)
$config["s2u_fw1_penalty_allow"]=12; //-> Càng ít càng gắt.

//Thời gian ip bị block bằng .htaccess được mở khóa.
$config["s2u_fw1_time_unlock"]=30; //-> Tính theo phút.

#///////////////////////////////////////////////////////////
### Chế độ chặn từng bước |||| Chế độ này có nhiều lựa chọn và sàn lọc IP, tốt cho website lớn.
#\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

//Anti 2 lớp, 0 tắt, 1 bật.
$config["s2u_fw2_two_layer"]=1; //-> Nếu bật thì vẫn chống ddos và block ip, tắt thì chỉ có lớp chờ click xác nhận.

//Bật/tắt chức năng chặn ref từ web khác.
$config["s2u_fw2_lock_ref"]=1; //-> 0 tắt, 1 bật, 2 + khi web đang bình thường.

//Chế độ ngăn chặn các yêu cầu ngay từ đầu, 0 tự động, 1 kích hoạt.
$config["s2u_fw2_super"]=0; //-> Khi số điểm hệ thống = 0 thì hệ thống sẽ tự động kích hoạt tính năng này.

//Thời gian hoạt động của chế độ Anti Super.
$config["s2u_fw2_super_time"]=15; //-> Tính theo phút.

//Chế độ đăng nhập trước khi vào web, 0 tắt, 1 tự động, 2 đang kích hoạt, 3 luôn bật.
$config["s2u_fw2_protect"]=1; //-> Khi có dấu hiệu Ddos thì hệ thống sẽ tự động kích hoạt tính năng này.

//Thời gian hoạt động của chế độ Protect Super.
$config["s2u_fw2_protect_time"]=60; //-> Tính theo phút.

//Name Protect Super.
$config["s2u_fw2_protect_name"]="sfs";

//Pass Protect Super.
$config["s2u_fw2_protect_pass"]="sfs";

//Chế độ chống các yêu cầu (POST) liên tục. 0 tắt, 1 bật.
$config["s2u_fw2_antispam"]=1; //-> Khi chế độ Protect Super bật thì tính năng này sẽ kích hoạt.

//Những file được chấp nhận khi có yêu cầu (POST).
$config["s2u_fw2_fnot_spam"]=""; //-> Ví dụ: downloads.php|abc.html|...

//Firewall chặn BOT, 0 tắt, 1 bật.
$config["s2u_fw2_isbot"]=0; //-> Trung bình tốc độ của bot ~2lần/1s.

//Số lần kết nối cho phép trong 1s ([s2u_fw2_penalty_allow]/s)
$config["s2u_fw2_penalty_allow"]=6; //-> Càng ít càng gắt.

//Giới hạn số lần bị khóa IP tạm thời sang khóa IP vĩnh viễn.
$config["s2u_fw2_max_lockcount"]=3; //-> Càng ít càng gắt.

//Thời gian chờ nhập captcha và thông báo IP bị khóa vĩnh viển.
$config["s2u_fw2_time_captcha"]=120; //-> Tính theo giây.

//Thời gian Block ip bằng .htaccess.
$config["s2u_fw2_time_unlock"]=60; //-> Tính theo phút.

//Mã PublicKey khi đăng kí ở http://www.google.com/recaptcha. Để trống = không dùng.
$config["s2u_fw2_captcha_public_key"]="";

//Mã PrivateKey khi đăng kí ở http://www.google.com/recaptcha. Để trống = không dùng.
$config["s2u_fw2_captcha_private_key"]="";

//Địa chỉ tên miền để xác nhận Ref. vd: app.s2u.vn
$config["s2u_fw2_domain_ref"]="";

//Những tên miền được phép truy cấp. vd: http://app.s2u.vn|http://www.app.s2u.vn
$config["s2u_fw2_domain_allow"]="";

#///////////////////////////////////////////////////////////
#\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
?>