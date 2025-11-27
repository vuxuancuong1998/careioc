<?php include_once "header.php";?>
<script>
   $(document).ready(function(){
	function changevalue()
		{
			$(".item-meta-weight").on("change",function(){
				var values = $(this).val();
				var id = $(this).attr("data-id");
				var type = "item_weight";
				$.ajax({
					type: "POST",
					url: "<?php echo XC_URL;?>/api/ChangeItemMetas",
					data: {type: type, id : id, values: values},
					dataType: "json",
					cache: false,
					success: function(data)
					{
						console.log(data.data);
						if(data.status != "200")
						{
							Swal.fire({
							  icon: 'error',
							  title: 'Oops...',
							  text: data.message,
							  footer: '<a href>Xem thêm về lỗi này?</a>'
							})
						}
						else
						{
							
						}
					}
				});
			});
			$(".item-meta-lenght").on("change",function(){
				var values = $(this).val();
				var id = $(this).attr("data-id");
				var type = "item_lenght";
				$.ajax({
					type: "POST",
					url: "<?php echo XC_URL;?>/api/ChangeItemMetas",
					data: {type: type, id : id, values: values},
					dataType: "json",
					cache: false,
					success: function(data)
					{
						console.log(data.data);
						if(data.status != "200")
						{
							Swal.fire({
							  icon: 'error',
							  title: 'Oops...',
							  text: data.message,
							  footer: '<a href>Xem thêm về lỗi này?</a>'
							})
						}
						else
						{
							
						}
					}
				});
			});
			$(".item-meta-width").on("change",function(){
				var values = $(this).val();
				var id = $(this).attr("data-id");
				var type = "item_width";
				$.ajax({
					type: "POST",
					url: "<?php echo XC_URL;?>/api/ChangeItemMetas",
					data: {type: type, id : id, values: values},
					dataType: "json",
					cache: false,
					success: function(data)
					{
						console.log(data.data);
						if(data.status != "200")
						{
							Swal.fire({
							  icon: 'error',
							  title: 'Oops...',
							  text: data.message,
							  footer: '<a href>Xem thêm về lỗi này?</a>'
							})
						}
						else
						{
							
						}
					}
				});
			});
			$(".item-meta-height").on("change",function(){
				var values = $(this).val();
				var id = $(this).attr("data-id");
				var type = "item_height";
				$.ajax({
					type: "POST",
					url: "<?php echo XC_URL;?>/api/ChangeItemMetas",
					data: {type: type, id : id, values: values},
					dataType: "json",
					cache: false,
					success: function(data)
					{
						console.log(data.data);
						if(data.status != "200")
						{
							Swal.fire({
							  icon: 'error',
							  title: 'Oops...',
							  text: data.message,
							  footer: '<a href>Xem thêm về lỗi này?</a>'
							})
						}
						else
						{
							
						}
					}
				});
			});
		}
      $('#barcode').keypress(function(event){
			var keycode = (event.keyCode ? event.keyCode : event.which);
			if(keycode == '13'){
				var code = $(this).val();
				$.ajax({
					type: "POST",
					url: "<?php echo XC_URL;?>/api/ScanTrackingItemIntoKRStock",
					data: {code: code},
					dataType: "json",
					cache: false,
					success: function(data)
					{
						console.log(data.data);
						if(data.status != "200")
						{
							Swal.fire({
							  icon: 'error',
							  title: 'Oops...',
							  text: data.message,
							  footer: '<a href>Xem thêm về lỗi này?</a>'
							})
						}
						else
						{
							
							$("#display-item").prepend(data.data);
							var countcurrent = parseFloat($("#countsuccess").html());
							$("#countsuccess").html(countcurrent+1);
							changevalue();
						}
					}
				});
				return false;
			}
			
		});
		
   
   })
</script>
         <main id="main-container">
		 <div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2">Nhập kho Trung Quốc</h1>
            
        </div>
   </div>
</div>
<div class="content">
    <div class="p-3 bg-white rounded push">
        <form action="" method="POST">
            <div class="input-group input-group-lg">
                <input type="text" id="barcode" class="form-control form-control-alt" placeholder="Quét hoặc nhập mã vận đơn">
                <div class="input-group-append">
                    <span class="input-group-text border-0 bg-body">
                        <i class="fa fa-fw fa-barcode"></i>
                    </span>
                </div>
            </div>
        </form>
    </div>
    <div class="block block-rounded">
        <ul class="nav nav-tabs nav-tabs-block js-tabs-enabled" data-toggle="tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" href="">Sản phẩm đã nhập kho</a>
            </li>
        </ul>
        <div class="block-content tab-content overflow-hidden">
            
            <div class="tab-pane fade active show" id="search-customers" role="tabpanel">
                <div class="font-size-h3 font-w600 pt-2 pb-4 mb-4 text-center border-bottom">
                    Đã nhập thành công <span class="text-primary font-w700" id="countsuccess">0</span> sản phẩm.
                </div>
                <table class="table table-striped table-borderless table-vcenter">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center" style="width: 70px;"><i class="si si-user"></i></th>
                            <th>Tên sản phẩm</th>
                            <th class="d-none d-sm-table-cell">Đơn hàng</th>
                            <th class="d-none text-center d-lg-table-cell" style="width: 10%;">KL (g)</th>
                            <th class="text-center" style="width: 30%;">Kích thước (cm)</th>
                            <th class="text-center" style="width: 5%;"></th>
                        </tr>
                    </thead>
                    <tbody id="display-item">
					
                                                
                                            </tbody>
                </table>
                
            </div>
        </div>
    </div>
</div>
    </main>
         <?php include_once "footer.php";?>