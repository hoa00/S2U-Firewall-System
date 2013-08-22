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
header('Content-Type: text/html; charset=utf-8');
include_once(S2UFW_BASE.S2UFWDS."s2u_firewall_func.php");
//////////////////////////////Int////////////////////////////
$arrConfigName=array(
	0=>"Chế độ Firewall",
	1=>"Thời gian làm sạch hệ thống",
	2=>"Mật khẩu quản lý hệ thống Firewall",
	3=>"Thời gian chặn khi đăng nhập sai",
	4=>"Thông báo Email tới người quản trị",
	5=>"Địa chỉ Email",
	6=>"Địa chỉ file .htaccess",
	7=>"Khóa ddos bằng cookie",
	8=>"Danh sách địa chỉ IP cho phép",
	9=>"Cấm truy cập từ các quốc gia",
	10=>"Ngăn chặn iframe",
	11=>"Nhật kí hệ thống",
	12=>"Địa chỉ tự động chuyển khi bị chặn",
	13=>"Địa chỉ thư mục Firewall",
	14=>"Số lần kết nối cho phép trong 1s",
	15=>"Thời gian khóa địa chỉ IP",
	16=>"Bảo vệ hai lớp",
	17=>"Chặn các Ref từ website khác",
	18=>"Ngăn chặn yêu cầu ngay từ đầu (Anti Super)",
	19=>"Thời gian hoạt động của Anti Super",
	20=>"Đăng nhập trước khi truy cập website (Protect Super)",
	21=>"Thời gian hoạt động của Protect Super",
	22=>"Tên tài khoản Protect Super",
	23=>"Mật khẩu tài khoản Protect Super",
	24=>"Chống Spam",
	25=>"File được phép có nhiều yêu cầu (Spam)",
	26=>"Chặn BOT tìm kiếm",
	27=>"Số lần kết nối cho phép trong 1s",
	28=>"Số lần chặn tạm thời",
	29=>"Thời gian chờ nhập Captcha",
	30=>"Thời gian khóa địa chỉ IP",
	31=>"Mã PublicKey Captcha",
	32=>"Mã PrivateKey Captcha",
	33=>"Tên miền xác nhận Ref",
	34=>"Danh sách tên miền cho phép"
);
$arrConfigNote=array(
	0=>"Bạn hãy lựa chọn cho mình chế độ Firewall phù hợp với website. Chế độ tức thời: Hoạt động đơn giản, cấu hình dễ, nhưng dễ chặn nhầm các người dùng bình thường nếu cấu hình không quá khắc. Chế độ từng bước: Hoạt động phân tích và đưa ra cảnh báo chi tiết, cấu hình thêm những chế độ đặc biệt khác (Anti Super, Protect Super, Chống Spam,...).",
	1=>"Thiết lập thời gian định kì để làm sạch các IP và Logs trên hệ thống.",
	2=>"Không giới hạn ký tự và có thể không thiết lập.",
	3=>"Thời gian đợi nếu đăng nhập vào quản lý tường lửa sai.",
	4=>"Hệ thống sẽ thông báo tình hình và các lỗi hệ thống tới email được thiết lập.",
	5=>"Địa chỉ Email mà bạn muốn hệ thống gửi thông báo tình trạng website.",
	6=>"Địa chỉ .htaccess cho hệ thống tường lửa, có thể là một chỗ nằm cùng cấp với website bạn.",
	7=>"Sử dụng cách chặn thông qua cookie sẽ giảm thiểu chặn các máy trong một mạng LAN (cùng IP).",
	8=>"Danh sách các IP được Firewall bỏ qua.",
	9=>"Firewall sẽ chặn và đưa ra thông báo với IP của nước đó mà bạn thiết lập.",
	10=>"Nếu trang của bạn có iframe chèn vào sẽ có một thông báo cho người dùng biết và không có website nào có thể chèn site của bạn làm iframe được.",
	11=>"Ghi lại các hoạt động của IP và hệ thống, được xem ở mục tình trạng.",
	12=>"Nếu khi không thể khóa IP bằng .htaccess thì khi truy cập sẽ được chuyển đến một trang đã thiết lập.",
	13=>"Đinh dạng: http://domain.com/S2U_Firewall/",
	14=>"Đây là thiết lập quan trong nhất. Tùy vào website mà bạn đang sở hữu, hãy thiết lập số kết nối mà bạn cho là hợp lý và ổn định trong 1s.",
	15=>"Trước khi bị khóa bằng .htaccess, hệ thống sẽ cảnh báo người dùng. Đây là số lần cảnh báo trước khi bị khóa.",
	16=>"Tính năng nay sẽ tiếp tục phân tích và ngăn chặn truy cập khi có yêu cầu nhấp vào ảnh để xác nhận. Nếu tắt, hệ thống chỉ hoạt động xác nhận người dùng qua click ảnh mà không khóa IP.",
	17=>"Một số người click vào website bạn từ web khác hay trang của bạn bị người khác chèn iframe trên web họ, thì hệ thống sẽ kiểm tra gắt hơn các IP khác hoặc đưa ra thông báo click ảnh để xác nhận.",
	18=>"Anti Super hoạt động một cách tự động, hệ thống sẽ đưa ra số điểm chuẩn và khi số điểm này <= tắt thì chế độ này tự bật lên. Trong thời gian hoạt động, mọi truy cập điều phải qua xác nhận bằng cách click ảnh (một lần).",
	19=>"Thời gian hoạt động của Anti Super",
	20=>"Protect Super sẽ tự kích hoạt khi phát hiện website bạn bị lag (Ping có ms quá cao) và cũng sẽ tự tắt khi website chạy bình thường.",
	21=>"Thời gian hoạt động của Protect Super",
	22=>"Tên tài khoản Protect Super",
	23=>"Mật khẩu tài khoản Protect Super",
	24=>"Khi có một yêu cầu qua giao thức POST liên tục gửi thông tin trùng lập thì hệ thống này sẽ ngăn việc đó lại.",
	25=>"Các file được phép, nhập theo dạng: downloads.php|abc.html|..., nếu bạn không sử dụng thì để trống.",
	26=>"Nếu bạn bật thì có thể sẽ ảnh hưởng tới mức hạng trên các máy chủ tìm kiếm.",
	27=>"Đây là thiết lập quan trong nhất. Tùy vào website mà bạn đang sở hữu, hãy thiết lập số kết nối mà bạn cho là hợp lý và ổn định trong 1s.",
	28=>"Trước khi bị khóa bằng .htaccess, hệ thống sẽ cảnh báo người dùng. Đây là số lần cảnh báo trước khi bị khóa.",
	29=>"Trước khi bị khóa địa chỉ IP, người truy cập có thể nhập mã Captcha để mở khóa nếu vô tình bị chặn.",
	30=>"Thời gian mà IP bị khóa cho đến lúc mở khóa.",
	31=>"Bạn lấy mã PublicKey ở http://www.google.com/recaptcha, sử dụng tài khoảng Google.",
	32=>"Bạn lấy mã PrivateKey ở http://www.google.com/recaptcha, sử dụng tài khoảng Google.",
	33=>"Nhập tên miền của website, không có Http://www và dấu /. VD: app.s2u.vn.",
	34=>"Bạn chỉ cần nhập theo dạng domain.com/forum|www.domain.com|... không có dấu / ở cuối."
);
$arrConfigEmt=array(
	0=>array(2, "Tắt Firewall", "Khóa tức thời", "Chặn từng bước", 2),
	1=>array(1, "Phút", 1440),
	2=>array(3, 4, 0, "Mật khẩu của bạn sẽ được mã hóa dạng MD5.", "503ceee97024bf073f1ff49ee057be13"),
	3=>array(1, "Phút", 5),
	4=>array(2, "Tắt", "Ít", "Trung bình", "Chi tiết", 2),
	5=>array(0, ""),
	6=>array(0, ".htaccess"),
	7=>array(4, 0),
	8=>array(3, 4, 1, "Mỗi IP là một dấu |. VD: 192.168.0.1|192.168.0.2|192.168.0.3", gethostbyname($_SERVER["HTTP_HOST"])),
	9=>array(3, 4, 0, "CN: China US: Anh TW: Đài Loan AU: Úc CA: Canada FR: Pháp DE: Đức HK: Hồng Kông IQ: Iraq IT: Italy JP: Nhật bản KP: Hàn Quốc RU: Nga. Bạn tham khảo thêm ở: http://countrycode.org/", 0),
	10=>array(4, 1),
	11=>array(4, 1),
	12=>array(0, "google.com.vn"),
	13=>array(0, "http://".$_SERVER["HTTP_HOST"].updir($_SERVER["REQUEST_URI"],0,'/')),
	14=>array(1, "Lần", 12),
	15=>array(1, "Phút", 30),
	16=>array(4, 1),
	17=>array(2, "Tắt", "Tự động", "Bật", 1),
	18=>array(4, 0),
	19=>array(1, "Phút", 15),
	20=>array(2, "Tắt", "Tự động", "Bật", "Luôn dùng", 1),
	21=>array(1, "Phút", 60),
	22=>array(0, "sfs"),
	23=>array(0, "sfs"),
	24=>array(4, 1),
	25=>array(0, ""),
	26=>array(4, 0),
	27=>array(1, "Lần", 6),
	28=>array(1, "Lần", 3),
	29=>array(1, "Giây", 120),
	30=>array(1, "Phút", 60),
	31=>array(0, ""),
	32=>array(0, ""),
	33=>array(0, $_SERVER["HTTP_HOST"]),
	34=>array(0, "http://".$_SERVER["HTTP_HOST"])
);
$arrCheckEmt=array(
	0=>array("safe_mode","Chế độ SAFE_MODE",0 ,2),
	1=>array("session","Hỗ trợ SESSION",1 ,2),
	2=>array("setcookie","Hỗ trợ COOKIES",2 ,2),
	3=>array("curl","Hỗ trợ CURL",1 ,2),
	4=>array("fopen","Hỗ trợ FOPEN",2 ,2),
	5=>array("file_get_contents","Hỗ trợ FILE_GET_CONTENTS",2 ,2),
	6=>array("mail","Hỗ trợ MAIL",2 ,2),
	7=>array("auto_prepend_file","Hỗ trợ AUTO_PREPEND_FILE",0 ,2),
	8=>array("chmod","Hỗ trợ CHMOD",2 ,2),
	9=>array("chmod","CHMOD thư mục [s2u_firewall_image/flag]",3 ,0501),
	10=>array("chmod","CHMOD file [s2u_firewall_image/vline.jpg]",3 ,0604),
	11=>array("chmod","CHMOD file [s2u_firewall_image/favicon.ico]",3 ,0604),
	12=>array("chmod","CHMOD file [s2u_firewall_image/quangcao.png]",3 ,0604),
	13=>array("chmod","CHMOD file [s2u_firewall_image/radio-bg.jpg]",3 ,0604),
	14=>array("chmod","CHMOD file [s2u_firewall_image/success.png]",3 ,0604),
	15=>array("chmod","CHMOD file [s2u_firewall_image/login.png]",3 ,0604),
	16=>array("chmod","CHMOD file [s2u_firewall_image/error.png]",3 ,0604),
	17=>array("chmod","CHMOD file [s2u_firewall_image/deny.png]",3 ,0604),
	18=>array("chmod","CHMOD file [s2u_firewall_image/back-to-top.png]",3 ,0604),
	19=>array("chmod","CHMOD thư mục [s2u_firewall_image]",3 ,0501),
	20=>array("chmod","CHMOD thư mục [s2u_firewall_logs]",3 ,0701),
	21=>array("chmod","CHMOD file [s2u_firewall_until/css/styleindex.css]",3 ,0604),
	22=>array("chmod","CHMOD file [s2u_firewall_until/css/styleadmin.css]",3 ,0604),
	23=>array("chmod","CHMOD thư mục [s2u_firewall_until/css]",3 ,0501),
	24=>array("chmod","CHMOD file [s2u_firewall_until/js/script.js]",3 ,0604),
	25=>array("chmod","CHMOD file [s2u_firewall_until/js/jquery.hotkeys.js]",3 ,0604),
	26=>array("chmod","CHMOD file [s2u_firewall_until/js/jquery.min.js]",3 ,0604),
	27=>array("chmod","CHMOD thư mục [s2u_firewall_until/js]",3 ,0501),
	28=>array("chmod","CHMOD file [s2u_firewall_until/400.php]",3 ,0604),
	29=>array("chmod","CHMOD file [s2u_firewall_until/401.php]",3 ,0604),
	30=>array("chmod","CHMOD file [s2u_firewall_until/403.php]",3 ,0604),
	31=>array("chmod","CHMOD file [s2u_firewall_until/404.php]",3 ,0604),
	32=>array("chmod","CHMOD file [s2u_firewall_until/500.php]",3 ,0604),
	33=>array("chmod","CHMOD file [s2u_firewall_until/index.php]",3 ,0604),
	34=>array("chmod","CHMOD file [s2u_firewall_until/dfc.swf]",3 ,0604),
	35=>array("chmod","CHMOD thư mục [s2u_firewall_until]",3 ,0501),
	36=>array("chmod","CHMOD file [s2u_firewall_admin.php]",3 ,0604),
	37=>array("chmod","CHMOD file [s2u_firewall_config.php]",3 ,0604),
	38=>array("chmod","CHMOD file [s2u_firewall_datas.php]",3 ,0604),
	39=>array("chmod","CHMOD file [s2u_firewall_func.php]",3 ,0604),
	40=>array("chmod","CHMOD file [s2u_firewall_recaptchalib.php]",3 ,0604),
	41=>array("chmod","CHMOD file [s2u_firewall_geoiploc.php]",3 ,0604),
	42=>array("chmod","CHMOD file [s2u_firewall_system.php]",3 ,0604),
	43=>array("chmod","CHMOD file [s2u_firewall_tester.php]",3 ,0604)
);
$arrSttEmt=array(
	0=>array("FWS","FWS",0,"margin-left: -438px;"),
	1=>array("IPS","IPS",0,"margin-left: -438px;"),
	2=>array("LOGS","LOGS",0,"margin-left: -438px;")
);
$arrToolName=array(
	0=>"Chặn IP",
	1=>"Khoảng thời gian chặn mọi truy cập",
	2=>"Công cụ thử DDos (1 bot)"
);
$arrToolNote=array(
	0=>"Nhập địa chỉ IP mà bạn muốn chặn. Cách nhập: [ip]|[time] (phút) (time=0 => khóa vĩnh viển).",
	1=>"Thời gian bảo trì website hoặc khoảng cấm. Cách nhập: 1->15 (chặn từ 1h tới 15h). Để trống ~ không dùng.",
	2=>"Sử dụng công cụ S.2.U DDos Flash Client để thử nghiệm cấu hình hệ thống tường lửa S.2.U Firewall System."
);

$arrToolEmt=array(
	0=>array(0, ""),
	/**/1=>array(0, ""),
	2=>array(6, "dfc.swf")
);
?>
