S2U-Firewall-System
===================


 Hệ thống tường lửa cao cấp
 Version 2.7 - 30/06/2013
 Author: Mr.Won (won.baria@gmail.com)
 Website: http://code.s2u.vn/S2U-Firewall-System


===================

Hệ thống chống ddos hiệu quả dành cho website của bạn, khả năng chống botnet, flood, spam,... 
hạn chế tối đa từ các lượt tấn công! Nó chạy ngầm và phân tích tình hình, tùy biến cấu hình dễ dàng,
phù hợp với nhiều website sử dụng ngôn ngữ PHP.

===================

Yêu cầu hệ thống (System request):
 - PHP 5.x
 - Hỗ trợ Mod_rewrite

===================


Các tính năng:
 - Chống các dạng tấn công mạng (ddos, spam, flood,..).
 - Gọn nhẹ, cấu hình và quản lý trực quan.
 - Chạy ngầm, không ảnh hưởng nhiều tới website.
 - Hai chế độ tường lửa đơn giản và chuyên nghiệp.
 - Phân tích tình trạng và tự động chuyển đổi chế độ phòng chống ddos.
 - Ngăn chặn với 2 lớp bảo vệ.
 - Cảnh báo trang bị chèn iframe và chống bị chèn iframe. (nếu host hỗ trợ auto_append_file trong htaccess)
 - Thiết lập thời gian bảo trì trong ngày.
 - Thử cấu hình tưởng lửa bằng công cụ S.2.U Ddos Flash Client.
 - Chặn nguồn ddos flash (domain, ip host, ip bot).
 - Hỗ trợ nhiều dạng website.
 - Mở khóa IP bằng captcha.
 - Hỗ trợ chạy full site, không cần include bất kì file nào. (nếu host hỗ trợ auto_prepend_file trong htaccess)
 - Chặn máy trong mạng LAN bằng Cookies.
 - Chặn các IP từ các nước khác.
 - Chế độ đăng nhập trước khi truy cập site.
 - Thông báo tình trạng firewall qua email.
 - Nhiều cấu hình tự thiết lập dễ dàng.
 - Và một số tính năng khác bạn có thể tham khảo ở trong trang Quản Lý Hệ Thống.

===================

Hướng dẫn cài đặt:
 1. Download file nén .zip ở dưới bài viết.
 2. Xóa firewall cũ (nếu có). cần chmod thư mục 701 và file 604 để có thể xóa hoàn toàn.
 3. Giải nén và up lên thư mục chính của website cần bảo vệ. (Nếu file .htaccess trên host đã có thì copy nội dung file .htaccess vừa giải ghi thêm vào cuối nội dung file đã có trên host)
 4. Truy cập http://domain/S2U_Firewall/ và đăng nhập bằng mật khẩu mặc định: s2u
 5. Chạy "tối ưu" ở mục cấu hình -> chạy "kiểm tra" ở mục kiểm tra (nếu "Hỗ trợ AUTO_PREPEND_FILE" báo không tương thích thì bạn làm bước 7 ) để có cấu hình tốt nhất.
 6. Kiểm tra hoạt động của firewall bằng cách nhấn F5 liên tục ở website bạn, ip và logs sẽ được ghi trong trang quản lý ("Lấy thông tin" ở mục tình trạng). Nếu đã chắc chắn, bạn có thể thử thêm công cụ S.2.U Ddos Flash Client trong mục công cụ.
 7. Nếu không thấy ip hay logs thì mở file index hoặc file global của website và include file s2u_firewall_system.php vào. 

===================

Support or Contact: 
https://github.com/s2u.vn/S2U-Firewall-System/wiki
Email: won.baria@gmail.com
