<?php include "header.php"; ?>
<link rel="stylesheet" href="<?php echo $template_path; ?>/assets/modules/doctor/css/specialistad59.css?vs=1.0.9" type="text/css" />
<script type="text/javascript" src="<?php echo $template_path; ?>/assets/js/jquery.numeric/jquery.numeric.js"></script>
<!-- <script type="text/javascript" src="<?php echo $template_path; ?>/assets/modules/doctor/js/specialistad59.js?vs=1.0.9"></script> -->
<style>
    .itspecimm{
        background-color:#fff;
    }
 .news-card {
  width: 350px;
  font-family: Arial, sans-serif;
  border: 1px solid #eee;
  box-shadow: 0 4px 8px rgba(0,0,0,0.1);
  overflow: hidden;
  background: #fff;
}

/* Phần hình ảnh và hiệu ứng Read More */
.image-container {
  position: relative;
  width: 100%;
}

.image-container img {
  width: 100%;
  height: 60%;
  object-fit: cover;
}

.overlay {
  position: absolute;
  bottom: 0;
  width: 100%;
  height: 40%;
  background: rgba(180, 0, 0, 0.7); /* Màu đỏ mờ */
  display: flex;
  justify-content: center;
  align-items: center;
  color: white;
  font-weight: bold;
  font-size: 1.2rem;
}

/* Phần nội dung văn bản */
.content {
  padding: 20px;
}

.category {
  color: #888;
  /* font-size: 16px; */
  display: flex;
  align-items: center;
  margin-bottom: 15px;
}

.dot {
  height: 8px;
  width: 8px;
  background-color: #b40000;
  border-radius: 50%;
  display: inline-block;
  margin-right: 8px;
}

.title {
  /* font-size: 1.1rem; */
  line-height: 1.4;
  color: #333;
  margin-bottom: 20px;
  font-weight: 600;
}

.date {
  color: #666;
  font-size: 0.9rem;
  margin-bottom: 10px;
}

.description {
  color: #444;
  /* font-size: 0.95rem; */
  line-height: 1.5;
}
.itspecimm{
    padding: 0;
}

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

</script>
<div class="gdnavatio">
    <div id="vnt-navation" class="breadcrumb hidden-xs hidden-sm"><div class="wrapper"><div class="navation"><ul><li><a href="index.html" ><span>trang chủ</span></a></li><li><span>Tin tức & Sự kiện</span></li></ul></div></div></div>
</div>
    <div class="gdcontent">
    <div class="vhspeciapg">
        <div class="wrapper">
            <div class="hpspeciapg">
                <div class="vnttitle vcolor vupper vcenter">
                    <div class="inline-block ">
                        <h1>TIN TỨC & SỰ KIỆN</h1>
                    </div>
                </div>
            
                <div class="vntconts">
                    <div class="tpspeciamm">
                        <div class="mmlist">
                            <div class="pggrip">
                                <?php foreach($events as $event){ ?>
                                      <div class="mcol">
                                        <div class="itspecimm">

                                            <a class="" href="#"></a>
                                        
                                            <!-- IMAGE + BADGE -->
                                            <div class="image-container">
                                                <img src="https://www.kthcm.edu.vn/wp-content/uploads/2025/12/2-7-480x320.jpg" alt="News Image">
                                                
                                            </div>

                                            <!-- CONTENT -->
                                                <div class="content">
                                                   
                                                    <h2 class="title">Lễ Bế Mạc Hội Thao Học Sinh – Sinh Viên Lần Thứ XI, Năm Học 2025-2026</h2>
                                                    <p class="date">Dec 25, 2025</p>
                                                    
                                                </div>

                                        </div>
                                    </div>

                                    <?php } ?>
                                                    
                                    
                                
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
    </div>
</div>
<?php include "footer.php"; ?>
