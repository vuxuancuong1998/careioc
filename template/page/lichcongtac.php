<?php include "header.php";?>
<script>
$(document).ready(function(e){
	
   		$(document).on('click', '#introduce_type', function (e) {
        // alert('s');
			var id =  $(this).attr("data-id");
			$.ajax({
				type: "POST",
				url: "<?php echo XC_URL;?>/api/loadIntroduce",
				"data": {
					'id': id,
				},
				"dataType":'json',
				success: function(data){
					if(data.status == 200){		
              $(".introduce_title").empty();
              $(".introduce_content").empty();	
						  $(".introduce_title").text(data.title);
              $(".thecontab").html(data.content);
					}
				}
			});
		});
		
	});
	
</script>
<style>
  .introduce_1 {
    font-size: 26px;
    line-height: 1.5;
    font-family: "Open Sans", sans-serif;
}
/* Bộ lọc */
.custom-select-container {
  position: relative;
  display: inline-block;
  width: auto;
}

.week-selector {
  /* Màu nền cam và chữ trắng */
  background-color: #6bc0e7; 
  color: white;
  
  /* Căn chỉnh và font chữ */
  padding: 10px 40px 10px 20px;
  
  /* Bo góc và bỏ viền mặc định */
  border: none;
  border-radius: 8px;
  
  /* Xóa mũi tên mặc định của hệ thống */
  appearance: none;
  -webkit-appearance: none;
  -moz-appearance: none;
  
  cursor: pointer;
  outline: none;
}

/* Tạo biểu tượng mũi tên xuống màu trắng */
.custom-select-container::after {
 
  color: white;
  position: absolute;
  right: 15px;
  top: 50%;
  transform: translateY(-50%);
  pointer-events: none; /* Để khi click vào mũi tên vẫn mở được menu */
}

/* Hiệu ứng khi di chuột qua */
.week-selector:hover {
  /* background-color: #6bc0e7; */
}
/* Định dạng các hàng lựa chọn bên trong */
.week-selector option {
  background-color: #ffffff; /* Màu trắng */
  color: #333333;           /* Màu chữ đen/xám đậm */
  padding: 10px;
}

/* Loại bỏ viền xanh mặc định khi click trên một số trình duyệt */
.week-selector:focus {
  outline: none;
}
</style>
    
<div class="gdnavatio">
    <div id="vnt-navation" class="breadcrumb hidden-xs hidden-sm"><div class="wrapper"><div class="navation"><ul><li><a href="<?php echo XC_URL;?>" ><span>trang chủ</span></a></li><li><span>Lịch công tác</span></li></ul></div></div></div>
</div>
        <div class="gdcontent">
    <div class="vhaboutpg">
        <div class="wrapper">
            <div class="hpaboutpg">
                <div class="vgripall">
                    <div class="custom-select-container">
                            <select class="week-selector">
                                <option>Tuần từ ngày 30/10/2023 đến 05/11/2023</option>
                                <option>Tuần từ ngày 06/11/2023 đến 12/11/2023</option>
                                <option>Tuần từ ngày 13/11/2023 đến 19/11/2023</option>
                            </select>
                            </div>
                    <div class="rxcol">
                        <div class="tpcontinfo">
                            <div class="ifconts">
                                <div class="tpaboutpg " id="vtab1">
                            <div class="tpaboutmm">
                            <div class="iftitle" >
                            <h1 class='introduce_1'><span class='introduce_title'><?php echo $introduce->type_name;?></span></h1>
                            </div>

                            <div class="vcontsab">
                            <div class="thecontab">
                            <p><span class='introduce_content'><?php echo $introduce->introduce_content;?></span></span></span></p>

                            </div>

  
                                </div>  

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    </div>
   <?php include "footer.php";?>