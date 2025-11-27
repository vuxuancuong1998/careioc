<?php include_once "header.php"; ?>

      <div class="content-page">

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap align-items-center justify-content-between">
                   <div class="d-flex align-items-center justify-content-between">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb p-0 mb-0">
                                <li class="breadcrumb-item"><a href="#">Tổng quan</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Sự kiện</li>
                            </ol>
                        </nav>
                    </div>
                    <a href="<?php echo XC_URL;?>/admin/products" class="btn btn-primary btn-sm d-flex align-items-center justify-content-between ml-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        <span class="ml-2">Danh sách sự kiện</span>
                    </a>
                </div>
            </div>
            <div class="col-lg-12 mb-3 d-flex justify-content-between">
                <h4 class="font-weight-bold d-flex align-items-center">Tạo sự kiện mới</h4>
            </div>
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-body">
                        <h5 class="font-weight-bold mb-3">Thông tin sự kiện</h5>
                        <form class="row g-3" id="frm-add-product">
                            <div class="col-md-12 mb-3">
                                <label for="event_title" class="form-label font-weight-bold text-muted text-uppercase">Tên sự kiện</label>
                                <input type="text" class="form-control" required id="event_title" name="event_title" placeholder="Nhập tên sự kiện">
                            </div>
							<div class="col-md-6 mb-3">
                                <label for="event_location" class="form-label font-weight-bold text-muted text-uppercase">Loại sự kiện</label>
                                <select id="event_location" name="event_location" required class="form-select form-control choicesjs" placeholder="Chọn nơi tổ chức">
									<option value="1">Offline</option>
									<option value="2">Online</option>
									<option value="3">Kết hợp</option>
								</select>
                            </div>
							<div class="col-md-6 mb-3">
                                <label for="event_host" class="form-label font-weight-bold text-muted text-uppercase">Host</label>
								<select id="event_host" name="event_host" required class="form-select form-control choicesjs" placeholder="Chọn thương hiệu">
									<?php
									foreach($speakers as $speaker)
									{
									?>
									<option value="<?php echo $speaker->speakerid;?>"><?php echo $speaker->user_firstname." ".$speaker->user_lastname;?></option>
									<?php
									}
									?>
								</select>
                            </div> 
							<div class="col-md-12 mb-3">
                                <label for="event_speaker" class="form-label font-weight-bold text-muted text-uppercase">Diễn giả</label>
								<select name="event_speaker" id="event_speaker" class="custom-select form-control choicesjs" multiple>
									<?php
									foreach($speakers as $speaker)
									{
									?>
									<option value="<?php echo $speaker->speakerid;?>"><?php echo $speaker->user_firstname." ".$speaker->user_lastname;?></option>
									<?php
									}
									?>
								</select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="event_channel" class="form-label font-weight-bold text-muted text-uppercase">Kênh Online</label>
                                <select id="event_channel" name="event_channel" required class="form-select form-control choicesjs" placeholder="Chọn nơi tổ chức">
									<?php
									foreach($channels as $channel)
									{
									?>
										<option value="<?php echo $channel->id;?>"><?php echo $channel->channel_name;?></option>
									<?php
									}
									?>
								</select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="event_address" class="form-label font-weight-bold text-muted text-uppercase">Địa điểm</label>
                                <input type="text" class="form-control" required id="event_address" name="event_address" placeholder="Nhập địa điểm tổ chức sự kiện">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="event_address" class="form-label font-weight-bold text-muted text-uppercase">Bắt đầu</label>
                                <input type="datetime-local" class="form-control" id="exampleInputdatetime" value="2019-12-19T13:45:00">
								<input type="text" class="form-control datetimepicker" id="event_filter_date_range" placeholder="Select Date">
                            </div>
                            
							
                            <div class="col-md-12 mb-3">
                                <label for="event_description" class="form-label font-weight-bold text-muted text-uppercase">Thông tin sự kiện</label>
                                <textarea class="form-control text-editor" required id="event_description" rows="10" placeholder="Vui lòng nhập mô tả sự kiện"></textarea>
                            </div>
							<div class="col-md-6 mb-3">
                                <label for="event_ticket" class="form-label font-weight-bold text-muted text-uppercase">Số lượng vé (0 = không giới hạn)</label>
                                <input type="number" class="form-control" required id="event_ticket" value="0" name="event_ticket" placeholder="Số lượng vé">
                            </div>
							<div class="col-md-6 mb-3">
                                <label for="event_preorder" class="form-label font-weight-bold text-muted text-uppercase">Cho phép đặt vé trước</label>
								<div class="input-group mb-4">
									<input type="text" class="form-control" required id="event_preorder" name="event_preorder" placeholder="Số ngày đặt vé trước">
									<div class="input-group-append">
									   <span class="input-group-text">ngày</span>
									</div>
								 </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="event_price" class="form-label font-weight-bold text-muted text-uppercase">Giá vé</label>
								<div class="input-group mb-4">
									<input type="text" class="form-control" required id="event_price" name="event_price" placeholder="Nhập giá vé">
									<div class="input-group-append">
									   <span class="input-group-text">VNĐ</span>
									</div>
								 </div>
                                
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="event_discount_price" class="form-label font-weight-bold text-muted text-uppercase">Giá khuyến mãi</label>
								<div class="input-group mb-4">
									<input type="text" class="form-control" id="event_discount_price" name="event_discount_price" placeholder="Nhập giá khuyến mãi">
									<div class="input-group-append">
									   <span class="input-group-text">VNĐ</span>
									</div>
								 </div>
                                
                            </div>
                            
                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h5 class="font-weight-bold mb-3">Ảnh sự kiện</h5>
                        <input type="file" class="custom-file-input dropify" id="btnupload" name="btnupload">
						<div class="form-group fillimage mt-2" id="fillimage"> </div>
                        <div class="d-flex justify-content-end mt-3">
                            <button class="btn btn-primary" id="btn-add-product">
                                Thêm sự kiện
                            </button>
                        </div>
                    </div>
                </div>
            </div>
			
			<div class="col-lg-3">
				<div class="card">
					<div class="card-header">
						Trạng thái
					</div>
                    <div class="card-body">
						<div class="col-md-12 mb-3">
							<select id="product_status" required class="form-select form-control choicesjs">
								<option value="1">Hoạt động</option>
								<option value="2">Hết vé</option>
								<option value="3">Đã huỷ</option>
							</select>
						</div>
					</div>
				</div>
				<div class="card">
					<div class="card-header">
						Danh mục sự kiện
					</div>
                    <div class="card-body">
						<div class="col-md-12">
							<select id="product_category" required class="form-select form-control choicesjs">
								<?php 
								foreach($categories as $category)
								{
								?>
									<option value="<?php echo $category->id;?>"><?php echo $category->category_name;?></option>
								<?php
								}
								?>
							</select>
						</div>
					</div>
				</div>
				<div class="card">
					<div class="card-header">
						Thẻ
					</div>
                    <div class="card-body">
						<div class="col-md-12">
							<input type="text" id="product_tags" class="choicesjs">
						</div>
					</div>
				</div>
			</div>
        </div>
    </div>

      </div>
    <?php include_once "footer.php"; ?>
	<script>
	$(document).ready(function(){
		
		$("#btn-add-products").on("click",function()
		{
			
			alert(data);
		});
		$("#btn-add-product").on("click",function()
		{
			if($("#frm-add-product").valid()){
				var name = $("#product_name").val();
				var sku = $("#product_sku").val();
				var brand = $("#product_brand").val();
				var description = editor.getData();
				var in_stock = $("#product_in_stock").val();
				var tax = $("#product_tax").val();
				var price = $("#product_price").val();
				var discount = $("#product_discount_price").val();
				var status = $("#product_status").val();
				var category = $("#product_category").val();
				var tags = $("#product_tags").val();
				var images = $("input[name='meta-id_hinh_anh[]']").map(function(){return $(this).val();}).get();
				var formData = new FormData();
				formData.append('name', name);
				formData.append('sku', sku);
				formData.append('brand', brand);
				formData.append('description', description);
				formData.append('in_stock', in_stock);
				formData.append('tax', tax);
				formData.append('price', price);
				formData.append('discount', discount);
				formData.append('status', status);
				formData.append('category', category);
				formData.append('images', images);
				formData.append('tags', tags);
				$.ajax({
					type: "POST",
					url: "<?php echo XC_URL;?>/api/AddProduct",
					data: formData,
					dataType: "json",
					cache: false,
					processData: false, 
					contentType: false, 
					enctype: 'multipart/form-data',
					success: function(data)
					{
						console.log(data.data);
						if(data.status == 200)
						{
							Swal.fire({
							  icon: 'success',
							  title: 'Thành công',
							  text: 'Thêm sản phẩm thành công',
							  timer: 1700
							})
							setTimeout(function(){ window.location= ("<?php echo XC_URL;?>/admin/products");     }, 2000);   
						}
						else
						{
							Swal.fire({
							  icon: 'error',
							  title: 'Oops...',
							  text: 'Tạo sản phẩm không thành công!',
							  footer: '<a href>Xem thêm về lỗi này?</a>'
							})
						}
					}
				});
				return false;
			}
			
			
		});
		$("#btnupload").on("change", function (e) {
			
			var formData = new FormData();
			formData.append('file', $('#btnupload')[0].files[0]);
			$.ajax({
				type: "POST",
				url: "<?php echo XC_URL;?>/api/upload",
				data: formData,
				dataType: "json",
				cache: false,
				processData: false,  // tell jQuery not to process the data
				contentType: false, 
				enctype: 'multipart/form-data',
				success: function(data)
				{
					console.log(data);
					if(data.status = 200)
					{
						$('#fillimage').append('<div class="mr-2 mb-2  boximgdt"><img src="'+data.url+'" class="img-fluid img-thumbnail rounded imgdt " ><i class="far fa-trash-alt btndel"></i><input type="hidden" value="'+data.url+'" name="meta-hinh_anh[]"><input type="hidden" value="'+data.id+'" name="meta-id_hinh_anh[]"></div>');
						$("#btnupload").val("");
						var filedropper = $('.dropify').dropify();
						filedropper = filedropper.data('dropify');
						filedropper.resetPreview();
						filedropper.clearElement();
						filedropper.destroy();
						filedropper.init();
						deleteuploadedimg();
					}
				}
			});
			return false;
		});
		function deleteuploadedimg()
		{
			$('.btndel').click(function(){
				$(this).closest('.boximgdt').remove();
			});
		}
		
	})
	</script>
	