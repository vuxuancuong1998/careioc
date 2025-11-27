<?php include_once "header.php"; ?>

      <div class="content-page">

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap align-items-center justify-content-between">
                   <div class="d-flex align-items-center justify-content-between">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb p-0 mb-0">
                                <li class="breadcrumb-item"><a href="product.html">Sản phẩm</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Tạo sản phẩm</li>
                            </ol>
                        </nav>
                    </div>
                    <a href="<?php echo XC_URL;?>/admin/products" class="btn btn-primary btn-sm d-flex align-items-center justify-content-between ml-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        <span class="ml-2">Sản phẩm</span>
                    </a>
                </div>
            </div>
            <div class="col-lg-12 mb-3 d-flex justify-content-between">
                <h4 class="font-weight-bold d-flex align-items-center">Tạo sản phẩm mới</h4>
            </div>
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-body">
                        <h5 class="font-weight-bold mb-3">Thông tin sản phẩm</h5>
                        <form class="row g-3" id="frm-add-product">
                            <div class="col-md-12 mb-3">
                                <label for="Text1" class="form-label font-weight-bold text-muted text-uppercase">Tên sản phẩm</label>
                                <input type="text" class="form-control" required id="product_name" placeholder="Nhập tên sản phẩm">
                            </div>
							<div class="col-md-6 mb-3">
                                <label for="product_sku" class="form-label font-weight-bold text-muted text-uppercase">SKU</label>
                                <input type="text" class="form-control" required id="product_sku" name="product_sku" placeholder="Nhập SKU">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="Text4" class="form-label font-weight-bold text-muted text-uppercase">Thương hiệu</label>
								<select id="product_brand" name="product_brand" required class="form-select form-control choicesjs" placeholder="Chọn thương hiệu">
									<?php
									foreach($brands as $brand)
									{
									?>
									<option value="<?php echo $brand->id;?>"><?php echo $brand->brand_name;?></option>
									<?php
									}
									?>
								</select>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="Text9" class="form-label font-weight-bold text-muted text-uppercase">Mô tả sản phẩm</label>
                                <textarea class="form-control text-editor" required id="product_description" rows="10" placeholder="Vui lòng nhập mô tả sản phẩm"></textarea>
                            </div>
							<div class="col-md-6 mb-3">
                                <label for="product_in_stock" class="form-label font-weight-bold text-muted text-uppercase">Trong kho</label>
                                <input type="number" class="form-control" required id="product_in_stock" value="0" name="product_in_stock" placeholder="Số lượng trong kho">
                            </div>
							<div class="col-md-6 mb-3">
                                <label for="Text7" class="form-label font-weight-bold text-muted text-uppercase">Thuế</label>
                                <select id="product_tax" name="product_tax" required class="form-select form-control choicesjs" placeholder="Chọn thuế suất">
									<?php
									foreach($taxs as $tax)
									{
									?>
									<option value="<?php echo $tax->id;?>"><?php echo $tax->tax_name;?> <?php echo number_format($tax->tax_value,0);?>%</option>
									<?php
									}
									?>
								</select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="product_price" class="form-label font-weight-bold text-muted text-uppercase">Giá bán</label>
								<div class="input-group mb-4">
									<input type="text" class="form-control" required id="product_price" name="product_price" placeholder="Nhập giá bán">
									<div class="input-group-append">
									   <span class="input-group-text"><?php echo $currency->currency_code;?></span>
									</div>
								 </div>
                                
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="product_discount_price" class="form-label font-weight-bold text-muted text-uppercase">Giá khuyến mãi</label>
								<div class="input-group mb-4">
									<input type="text" class="form-control" id="product_discount_price" name="product_discount_price" placeholder="Nhập giá khuyến mãi">
									<div class="input-group-append">
									   <span class="input-group-text"><?php echo $currency->currency_code;?></span>
									</div>
								 </div>
                                
                            </div>
                            
                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h5 class="font-weight-bold mb-3">Ảnh sản phẩm</h5>
                        <input type="file" class="custom-file-input dropify" id="btnupload" name="btnupload">
						<div class="form-group fillimage mt-2" id="fillimage"> </div>
                        <div class="d-flex justify-content-end mt-3">
                            <button class="btn btn-primary" id="btn-add-product">
                                Thêm sản phẩm
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
								<option value="1">Đang kinh doanh</option>
								<option value="2">Hết hàng</option>
								<option value="3">Hàng đang về</option>
								<option value="4">Ngừng kinh doanh</option>
							</select>
						</div>
					</div>
				</div>
				<div class="card">
					<div class="card-header">
						Nhóm sản phẩm
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
	