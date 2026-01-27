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
</style>
    <div id="vnt-content" >
      
        <div class="gdmaintop ">
    <div class=''><div id='vnt-slide' class='slick-init'><div class='item'><div class='img'><img src='<?php echo $template_path; ?>/assets/images/banergioithieu.jpg' height='320px' alt='GIỚI THIỆU' /></div></div></div></div>
</div>
<div class="gdnavatio">
    <div id="vnt-navation" class="breadcrumb hidden-xs hidden-sm"><div class="wrapper"><div class="navation"><ul><li><a href="index.html" ><span>trang chủ</span></a></li><li><span>giới thiệu</span></li></ul></div></div></div>
</div>
        <div class="gdcontent">
    <div class="vhaboutpg">
        <div class="wrapper">
            <div class="hpaboutpg">
                <div class="vgripall">
                    <div class="lxcol hidden-xs hidden-sm">
                        <div class="tptabmenus">
                            <div class="mntitle">
                                <h2>GIỚI THIỆU</h2>
                            </div>
                            <div class="mnconts">
                                <ul>
                                  <?php foreach($category as $category){?>
                                <li class="current" ><a class='<?php echo ($category->id===$id) ? "text-success" : "";?>' href="<?php echo $this->helper->permalink($category->id, 'introduce');?>" data-id='<?php echo $category->id;?>' rel="nofollow"><?php echo $category -> category_name;?></a></li>
                                <?php }?>
                              </ul>
                            </div>
                        </div>
                    </div>
                    <div class="rxcol">
                        <div class="tpcontinfo">
                            <div class="ifconts">
                                <div class="tpaboutpg " id="vtab1">
  <div class="tpaboutmm">
<div class="iftitle" >
<h1 class='introduce_1'><span class='introduce_title'><?php echo $introduce->category_name;?></span></h1>
</div>

<div class="vcontsab">
<div class="thecontab">
<p><span class='introduce_content'><?php echo $introduce->introduce_content;?></span></span></span></p>

</div>



<!-- <div class="tpaboutpg " id="vtab6">
  <div class="tpaboutss">
<div class="vcontsab">
<div class="sslinks"><a href="dang-ky-lich-kham.html"><trust><img alt="daiphuc" src="../vnt_upload/about/04_2024/editw2.png" /><span class="ck_desc_img"></span></trust> <span>ĐẶT LỊCH HẸN KHÁM</span> </a></div>
</div>
</div> -->
  
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