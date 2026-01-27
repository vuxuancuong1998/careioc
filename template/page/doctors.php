<?php include "header.php"; ?>

<link rel="stylesheet" href="<?php echo $template_path; ?>/assets/modules/doctor/css/doctorad59.css?vs=1.1.0" type="text/css" />
<script type="text/javascript" src="<?php echo $template_path; ?>/assets/js/jquery.numeric/jquery.numeric.js"></script>
<script type="text/javascript" src="<?php echo $template_path; ?>/assets/modules/doctor/js/doctorad59.js?vs=1.1.0"></script>
<style>
/* Phân trang */
.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  margin-top: 30px;
  gap: 8px; /* Khoảng cách giữa các nút */
}

.pagination a {
  color: #333;
  padding: 8px 16px;
  text-decoration: none;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 9px;
  transition: all 0.3s ease;
}

/* Nút đang được chọn */
.pagination a.active {
  background-color: #6bc0e7; /* Màu đỏ đồng bộ với card */
  color: white;
  border-color: #6bc0e7;
}

/* Hiệu ứng khi di chuột qua các nút chưa chọn */
.pagination a:hover:not(.active) {
  background-color: #f2f2f2;
  border-color: #ccc;
}

/* Định dạng dấu ba chấm */
.dots {
  color: #888;
  padding: 0 5px;
}

/* Nút Trước/Sau */
.prev, .next {
  font-weight: bold;
}
</style>
    <div id="vnt-content" >
      
        <div class="gdmaintop ">
    <!-- <div class=''>
        <div id='vnt-slide' class='slick-init'>
            <div class='item'>
                <div class='img'>
                    <img src='https://phongkhamdaiphuoc.vn/vnt_upload/weblink/slide_11_webnew.png' alt='Bác sĩ - Chuyên gia' />
                </div>
            </div>
        </div>
    </div> -->
</div>
<div class="gdnavatio">
    <div id="vnt-navation" class="breadcrumb hidden-xs hidden-sm"><div class="wrapper"><div class="navation"><ul><li><a class="home" href="https://phongkhamdaiphuoc.vn/vn/" >trang chủ</a></li><li><a href="https://phongkhamdaiphuoc.vn/vn/chuyen-khoa.html">bác sĩ - chuyên gia</a></li><li><a href="https://phongkhamdaiphuoc.vn/vn/khoa-chan-doan-hinh-anh.html">khoa chẩn đoán hình ảnh</a></li></ul></div></div></div>
</div>
        <div class="gdcontent">
    <div class="vhdoctorpg">
        <div class="wrapper">
            <div class="hpdoctorpg">
                <div class="vgripall">
                    <div class="lxcol">
                        <div class="tptablnkss hidden-md hidden-lg">
                            <div class="menuTab">
                                <div class="mc-menu">Chọn danh mục</div>
                                <ul><li ><a href='https://phongkhamdaiphuoc.vn/vn/doi-ngu-bac-si.html/?cat_id=17' rel='nofollow'>Khoa Nội Soi Tiêu Hóa</a></li><li ><a href='https://phongkhamdaiphuoc.vn/vn/doi-ngu-bac-si.html/?cat_id=20' rel='nofollow'>Khoa Sinh Học Phân Tử</a></li><li ><a href='https://phongkhamdaiphuoc.vn/vn/doi-ngu-bac-si.html/?cat_id=15' rel='nofollow'>Giải Phẫu Bệnh - Tế Bào Học</a></li><li ><a href='https://phongkhamdaiphuoc.vn/vn/doi-ngu-bac-si.html/?cat_id=11' rel='nofollow'>Khoa Tiêu Hóa - Gan Mật</a></li><li ><a href='https://phongkhamdaiphuoc.vn/vn/doi-ngu-bac-si.html/?cat_id=12' rel='nofollow'>Khoa Tim Mạch - Nội Tiết</a></li><li ><a href='https://phongkhamdaiphuoc.vn/vn/doi-ngu-bac-si.html/?cat_id=8' rel='nofollow'>Khoa Hô Hấp</a></li><li ><a href='https://phongkhamdaiphuoc.vn/vn/doi-ngu-bac-si.html/?cat_id=10' rel='nofollow'>Khoa Nội Thần Kinh</a></li><li ><a href='https://phongkhamdaiphuoc.vn/vn/doi-ngu-bac-si.html/?cat_id=9' rel='nofollow'>Khoa Cơ Xương Khớp</a></li><li ><a href='https://phongkhamdaiphuoc.vn/vn/doi-ngu-bac-si.html/?cat_id=24' rel='nofollow'>Khoa Thận Tiết Niệu</a></li><li ><a href='https://phongkhamdaiphuoc.vn/vn/doi-ngu-bac-si.html/?cat_id=26' rel='nofollow'>Khoa Truyền Nhiễm</a></li><li ><a href='https://phongkhamdaiphuoc.vn/vn/doi-ngu-bac-si.html/?cat_id=4' rel='nofollow'>Khoa Tai Mũi Họng</a></li><li ><a href='https://phongkhamdaiphuoc.vn/vn/doi-ngu-bac-si.html/?cat_id=1' rel='nofollow'>Khoa Da Liễu Thẩm Mỹ</a></li><li ><a href='https://phongkhamdaiphuoc.vn/vn/doi-ngu-bac-si.html/?cat_id=2' rel='nofollow'>Khoa Mắt</a></li><li ><a href='https://phongkhamdaiphuoc.vn/vn/doi-ngu-bac-si.html/?cat_id=3' rel='nofollow'>Nha Khoa Thẩm Mỹ</a></li><li ><a href='https://phongkhamdaiphuoc.vn/vn/doi-ngu-bac-si.html/?cat_id=5' rel='nofollow'>Khoa Sản Phụ</a></li><li ><a href='https://phongkhamdaiphuoc.vn/vn/doi-ngu-bac-si.html/?cat_id=6' rel='nofollow'>Khoa Tâm Lý Tâm Thần</a></li><li ><a href='https://phongkhamdaiphuoc.vn/vn/doi-ngu-bac-si.html/?cat_id=30' rel='nofollow'>Khoa Huyết Học</a></li><li ><a href='https://phongkhamdaiphuoc.vn/vn/doi-ngu-bac-si.html/?cat_id=27' rel='nofollow'>Ngoại Khoa - Tiểu Phẫu</a></li><li ><a href='https://phongkhamdaiphuoc.vn/vn/doi-ngu-bac-si.html/?cat_id=28' rel='nofollow'>Cấp cứu - gây mê hồi sức</a></li><li  class='active' ><a href='https://phongkhamdaiphuoc.vn/vn/doi-ngu-bac-si.html/?cat_id=21' rel='nofollow'>Khoa Chẩn Đoán Hình Ảnh</a></li><li ><a href='https://phongkhamdaiphuoc.vn/vn/doi-ngu-bac-si.html/?cat_id=16' rel='nofollow'>Khoa Xét Nghiệm</a></li><li ><a href='https://phongkhamdaiphuoc.vn/vn/doi-ngu-bac-si.html/?cat_id=23' rel='nofollow'>Khoa Dịch Vụ Tiêm Ngừa</a></li><li ><a href='https://phongkhamdaiphuoc.vn/vn/doi-ngu-bac-si.html/?cat_id=22' rel='nofollow'>Khoa Dược</a></li></ul>
                            </div>
                        </div>
                        <div class="tptabmenus hidden-xs hidden-sm">
                            <div class="mntitle">
                                <div class="inline-block ">
                                    <h2>Chuyên khoa</h2>
                                    
                                </div>
                            </div>
                            <div class="mnconts">
                                <ul><li ><a href='https://phongkhamdaiphuoc.vn/vn/doi-ngu-bac-si.html/?cat_id=17' rel='nofollow'>Khoa Nội Soi Tiêu Hóa</a></li><li ><a href='https://phongkhamdaiphuoc.vn/vn/doi-ngu-bac-si.html/?cat_id=20' rel='nofollow'>Khoa Sinh Học Phân Tử</a></li><li ><a href='https://phongkhamdaiphuoc.vn/vn/doi-ngu-bac-si.html/?cat_id=15' rel='nofollow'>Giải Phẫu Bệnh - Tế Bào Học</a></li><li ><a href='https://phongkhamdaiphuoc.vn/vn/doi-ngu-bac-si.html/?cat_id=11' rel='nofollow'>Khoa Tiêu Hóa - Gan Mật</a></li><li ><a href='https://phongkhamdaiphuoc.vn/vn/doi-ngu-bac-si.html/?cat_id=12' rel='nofollow'>Khoa Tim Mạch - Nội Tiết</a></li><li ><a href='https://phongkhamdaiphuoc.vn/vn/doi-ngu-bac-si.html/?cat_id=8' rel='nofollow'>Khoa Hô Hấp</a></li><li ><a href='https://phongkhamdaiphuoc.vn/vn/doi-ngu-bac-si.html/?cat_id=10' rel='nofollow'>Khoa Nội Thần Kinh</a></li><li ><a href='https://phongkhamdaiphuoc.vn/vn/doi-ngu-bac-si.html/?cat_id=9' rel='nofollow'>Khoa Cơ Xương Khớp</a></li><li ><a href='https://phongkhamdaiphuoc.vn/vn/doi-ngu-bac-si.html/?cat_id=24' rel='nofollow'>Khoa Thận Tiết Niệu</a></li><li ><a href='https://phongkhamdaiphuoc.vn/vn/doi-ngu-bac-si.html/?cat_id=26' rel='nofollow'>Khoa Truyền Nhiễm</a></li><li ><a href='https://phongkhamdaiphuoc.vn/vn/doi-ngu-bac-si.html/?cat_id=4' rel='nofollow'>Khoa Tai Mũi Họng</a></li><li ><a href='https://phongkhamdaiphuoc.vn/vn/doi-ngu-bac-si.html/?cat_id=1' rel='nofollow'>Khoa Da Liễu Thẩm Mỹ</a></li><li ><a href='https://phongkhamdaiphuoc.vn/vn/doi-ngu-bac-si.html/?cat_id=2' rel='nofollow'>Khoa Mắt</a></li><li ><a href='https://phongkhamdaiphuoc.vn/vn/doi-ngu-bac-si.html/?cat_id=3' rel='nofollow'>Nha Khoa Thẩm Mỹ</a></li><li ><a href='https://phongkhamdaiphuoc.vn/vn/doi-ngu-bac-si.html/?cat_id=5' rel='nofollow'>Khoa Sản Phụ</a></li><li ><a href='https://phongkhamdaiphuoc.vn/vn/doi-ngu-bac-si.html/?cat_id=6' rel='nofollow'>Khoa Tâm Lý Tâm Thần</a></li><li ><a href='https://phongkhamdaiphuoc.vn/vn/doi-ngu-bac-si.html/?cat_id=30' rel='nofollow'>Khoa Huyết Học</a></li><li ><a href='https://phongkhamdaiphuoc.vn/vn/doi-ngu-bac-si.html/?cat_id=27' rel='nofollow'>Ngoại Khoa - Tiểu Phẫu</a></li><li ><a href='https://phongkhamdaiphuoc.vn/vn/doi-ngu-bac-si.html/?cat_id=28' rel='nofollow'>Cấp cứu - gây mê hồi sức</a></li><li  class='active' ><a href='https://phongkhamdaiphuoc.vn/vn/doi-ngu-bac-si.html/?cat_id=21' rel='nofollow'>Khoa Chẩn Đoán Hình Ảnh</a></li><li ><a href='https://phongkhamdaiphuoc.vn/vn/doi-ngu-bac-si.html/?cat_id=16' rel='nofollow'>Khoa Xét Nghiệm</a></li><li ><a href='https://phongkhamdaiphuoc.vn/vn/doi-ngu-bac-si.html/?cat_id=23' rel='nofollow'>Khoa Dịch Vụ Tiêm Ngừa</a></li><li ><a href='https://phongkhamdaiphuoc.vn/vn/doi-ngu-bac-si.html/?cat_id=22' rel='nofollow'>Khoa Dược</a></li></ul>
                            </div>
                        </div>
                    </div>
                    <div class="rxcol">
                        <div class="tpcontinfo">
                            <div class="vnttitle vmargin vcolor vupper vcenter">
                                <div class="inline-block ">
                                    <h1>ĐỘI NGŨ BÁC SĨ</h1>
                                    
                                </div>
                            </div>
                            <div class="vntconts">
                                <div class="tpdoctormm">
                                    <div class="mmlist">
                                        <div class="mmrow ">
    <div class="itdoctormm">
        <div class="mmgrip">
            <div class="mcol">
                <div class="thumb">
                    <a class="vhthumbzoom" href="https://phongkhamdaiphuoc.vn/vn/bs-ck1-nguyen-xuan-quang.html">
                        <img src="https://phongkhamdaiphuoc.vn/vnt_upload/doctor/08_2024/24.png" alt="BS CK1 NGUYỄN XUÂN QUANG">
                    </a>
                </div>
            </div>
            <div class="hcol">
                <div class="decss">
                    <div class="dstitle">
                        <h3><a href="https://phongkhamdaiphuoc.vn/vn/bs-ck1-nguyen-xuan-quang.html">BS CK1 NGUYỄN XUÂN QUANG</a></h3>
                    </div>
                    <div class="dsconts">
                        <div><span style="line-height:1.5;"><span style="font-size:16px;"><span style="font-family:Open Sans,sans-serif;">Nhiều năm kinh nghiệm về chuyên khoa Chẩn đoán hình ảnh như siêu âm, X Quang, CT Scanner, nhũ ảnh, ...<br />
Thực hiện chọc sinh thiết tế bào bằng kim nhỏ (FNA).<br />
Phạm vi hoạt động chuyên môn: KCB chuyên khoa Chẩn đoán hình ảnh</span></span></span><br />
&nbsp;</div>
                    </div>
                    <div class="dslinks">
                        <ul>
                            <li class="vm ">
                                <a href="https://phongkhamdaiphuoc.vn/vn/bs-ck1-nguyen-xuan-quang.html"><span>Xem chi tiết</span></a>
                                
                            </li>
                            <li class="vh ph  ">
                                <a href="tel:0938829831"><span>0938 829 831</span></a>
                                
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>

<div class="mmrow ">
    <div class="itdoctormm">
        <div class="mmgrip">
            <div class="mcol">
                <div class="thumb">
                    <a class="vhthumbzoom" href="https://phongkhamdaiphuoc.vn/vn/bs-ck1-duong-thi-my-phuong.html">
                        <img src="https://phongkhamdaiphuoc.vn/vnt_upload/doctor/08_2024/26.png" alt="BS CK1 DƯƠNG THỊ MỸ PHƯƠNG">
                    </a>
                </div>
            </div>
            <div class="hcol">
                <div class="decss">
                    <div class="dstitle">
                        <h3><a href="https://phongkhamdaiphuoc.vn/vn/bs-ck1-duong-thi-my-phuong.html">BS CK1 DƯƠNG THỊ MỸ PHƯƠNG</a></h3>
                    </div>
                    <div class="dsconts">
                        <div><span style="line-height:1.5;"><span style="font-size:16px;"><span style="font-family:Open Sans,sans-serif;">Nhiều năm kinh nghiệm về chuyên khoa Chẩn đoán hình ảnh như siêu âm, X Quang, CT Scanner, nhũ ảnh, ...<br />
Thực hiện chọc sinh thiết tế bào bằng kim nhỏ (FNA).<br />
Phạm vi hoạt động chuyên môn: KCB chuyên khoa Chẩn đoán hình ảnh</span></span></span></div>
                    </div>
                    <div class="dslinks">
                        <ul>
                            <li class="vm ">
                                <a href="https://phongkhamdaiphuoc.vn/vn/bs-ck1-duong-thi-my-phuong.html"><span>Xem chi tiết</span></a>
                                
                            </li>
                            <li class="vh ph  ">
                                <a href="tel:0938829831"><span>0938 829 831</span></a>
                                
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>

<div class="mmrow ">
    <div class="itdoctormm">
        <div class="mmgrip">
            <div class="mcol">
                <div class="thumb">
                    <a class="vhthumbzoom" href="https://phongkhamdaiphuoc.vn/vn/bs-ck1-truong-thi-phuong-yen.html">
                        <img src="https://phongkhamdaiphuoc.vn/vnt_upload/doctor/08_2024/25.png" alt="BS CK1 TRƯƠNG THỊ PHƯƠNG YẾN">
                    </a>
                </div>
            </div>
            <div class="hcol">
                <div class="decss">
                    <div class="dstitle">
                        <h3><a href="https://phongkhamdaiphuoc.vn/vn/bs-ck1-truong-thi-phuong-yen.html">BS CK1 TRƯƠNG THỊ PHƯƠNG YẾN</a></h3>
                    </div>
                    <div class="dsconts">
                        <div><span style="line-height:1.5;"><span style="font-family:Open Sans,sans-serif;"><span style="font-size:16px;">Nhiều năm kinh nghiệm về chuyên khoa Chẩn đoán hình ảnh như siêu âm, X Quang, CT Scanner, nhũ ảnh, ...&nbsp;<br />
Thực hiện chọc sinh thiết tế bào bằng kim nhỏ (FNA).&nbsp;<br />
Phạm vi hoạt động chuyên môn: KCB chuyên khoa Chẩn đoán hình ảnh</span></span></span></div>
                    </div>
                    <div class="dslinks">
                        <ul>
                            <li class="vm ">
                                <a href="https://phongkhamdaiphuoc.vn/vn/bs-ck1-truong-thi-phuong-yen.html"><span>Xem chi tiết</span></a>
                                
                            </li>
                            <li class="vh ph  ">
                                <a href="tel:0938829831"><span>0938 829 831</span></a>
                                
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>

<div class="mmrow ">
    <div class="itdoctormm">
        <div class="mmgrip">
            <div class="mcol">
                <div class="thumb">
                    <a class="vhthumbzoom" href="https://phongkhamdaiphuoc.vn/vn/bs-ck1-hoang-anh-khoi.html">
                        <img src="https://phongkhamdaiphuoc.vn/vnt_upload/doctor/09_2024/BSkhoi_pts.png" alt="BS CK1 HOÀNG ANH KHÔI">
                    </a>
                </div>
            </div>
            <div class="hcol">
                <div class="decss">
                    <div class="dstitle">
                        <h3><a href="https://phongkhamdaiphuoc.vn/vn/bs-ck1-hoang-anh-khoi.html">BS CK1 HOÀNG ANH KHÔI</a></h3>
                    </div>
                    <div class="dsconts">
                        <p><span style="line-height:1.5;"><span style="font-size:16px;"><span style="font-family:Open Sans,sans-serif;">Nhiều năm kinh nghiệm về chuyên khoa Chẩn đoán hình ảnh như siêu âm, X Quang, CT Scanner, nhũ ảnh, ...&nbsp;<br />
Thực hiện chọc sinh thiết tế bào bằng kim nhỏ (FNA).&nbsp;<br />
Phạm vi hoạt động chuyên môn: KCB chuyên khoa Chẩn đoán hình ảnh</span></span></span></p>
                    </div>
                    <div class="dslinks">
                        <ul>
                            <li class="vm ">
                                <a href="https://phongkhamdaiphuoc.vn/vn/bs-ck1-hoang-anh-khoi.html"><span>Xem chi tiết</span></a>
                                
                            </li>
                            <li class="vh ph  ">
                                <a href="tel:0938829831"><span>0938 829 831</span></a>
                                
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>
                                    </div>
                                    <div class="mmpagi">
                                        <div id="pagination"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="pagination">
                    <a href="#" class="prev">&laquo; Trước</a>
                    <a href="#" class="page-number active">1</a>
                    <a href="#" class="page-number">2</a>
                    <span class="dots">...</span>
                    <a href="#" class="page-number">10</a>
                    <a href="#" class="next">Sau &raquo;</a>
                </div>
    </div>
    
</div>
<?php include "footer.php"; ?>