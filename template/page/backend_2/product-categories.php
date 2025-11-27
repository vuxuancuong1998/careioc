<?php include_once "header.php"; ?>
<div class="content-page">

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap align-items-center justify-content-between">
                   <div class="d-flex align-items-center justify-content-between">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb p-0 mb-0">
                                <li class="breadcrumb-item"><a href="<?php echo XC_URL;?>/admin">Tổng quan</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Nhóm sản phẩm</li>
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
                <h4 class="font-weight-bold d-flex align-items-center">Danh mục nhóm sản phẩm</h4>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="font-weight-bold mb-3">Tạo/sửa nhóm sản phẩm</h5>
                        <form class="row g-3" id="frm-add-product">
                            <div class="col-md-12 mb-3">
                                <label for="Text1" class="form-label font-weight-bold text-muted text-uppercase">Tên nhóm sản phẩm</label>
                                <input type="text" class="form-control font-weight-bold" value="<?php echo ($action == "edit")? $editcategory->category_name : "";?>" required id="product_name" placeholder="Nhập tên nhóm sản phẩm">
                            </div>
                            
                            <div class="col-md-12 mb-3">
                                <label for="aou_category_parent" class="form-label font-weight-bold text-muted text-uppercase">Nhóm cha</label>
								<select id="aou_category_parent" name="aou_category_parent"  class="form-select form-control choicesjs" placeholder="Chọn nhóm cấp trên">
									<option <?php echo ($editcategory->category_parent == 0)? "selected" : "";?> value="0">Nhóm gốc</option>
									<?php
									foreach($categories as $category)
									{
									?>
									<option <?php echo ($editcategory->category_parent == $category->id)? "selected" : "";?> value="<?php echo $category->id;?>"><?php echo $category->category_name;?></option>
									<?php
									}
									?>
								</select>
                            </div>
							<div class="col-md-12 mb-3">
                                <label for="aou_category_status" class="form-label font-weight-bold text-muted text-uppercase">Trạng thái</label>
								<select id="aou_category_status" name="aou_category_status" required class="form-select form-control " >
									<option <?php echo ($editcategory->category_status == 1)? "selected" : "";?> value="1">Hoạt động</option>
									<option <?php echo ($action == "edit" && $editcategory->category_status == 0)? "selected" : "";?> value="0">Không hoạt động</option>
								</select>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="aou_category_description" class="form-label font-weight-bold text-muted text-uppercase">Mô tả nhóm</label>
                                <textarea class="form-control text-editor" required id="aou_category_description" rows="10" placeholder="Vui lòng nhập mô tả nhóm"><?php echo ($action == "edit")? $editcategory->category_description : "";?></textarea>
                            </div>
							<div class="col-md-12 mb-3">
                                <label for="aou_category_image" class="form-label font-weight-bold text-muted text-uppercase">Ảnh đại diện</label>
								<div class="custom-file">
									 <input type="file" class="custom-file-input" id="aou_category_image">
									 <label class="custom-file-label" for="aou_category_image">Chọn ảnh</label>
								</div>
								<div class="form-group fillimage mt-2" id="fillimage"> </div>
                            </div>
							<div class="col-md-12 mb-3">
                                <label for="aou_category_icon" class="form-label font-weight-bold text-muted text-uppercase">Ảnh biểu tượng</label>
								<div class="custom-file">
									 <input type="file" class="custom-file-input" id="aou_category_icon">
									 <label class="custom-file-label" for="aou_category_icon">Chọn ảnh</label>
								</div>
								<div class="form-group fillimage mt-2" id="fillicon"> </div>
                            </div>
							
                            
                        </form>
                    </div>
					<div class="card-footer">
						<div class="d-flex justify-content-end mt-3">
								<button class="btn btn-primary" id="btn-add-product">
									Lưu
								</button>
							</div>
					</div>
                </div>
            </div>
			
			<div class="col-lg-8">
				<div class="card">
					<div class="card-header">
						Danh sách nhóm sản phẩm
					</div>
                    <div class="card-body">
						<table id="datatable" class="table data-table table-striped table-bordered" >
                           <thead>
                              <tr>
                                 <th style="width: 5%">STT</th>
                                 <th>Ảnh</th>
                                 <th>Tên nhóm</th>
                                 <th>Nhóm cha</th>
                                 <th class="text-center">Trạng thái</th>
                                 <th style="width: 7%">Thao tác</th>
                              </tr>
                           </thead>
                           <tbody>
								<?php 
								$i = 1;
								foreach($categories as $category)
								{
								?>
                              <tr>
                                 <td><?php echo $i;?></td>
                                 <td class="">
									<div class="h-avatar is-medium">
										<img class="avatar rounded" style="height: 60px;width: auto;max-height: 60px;" alt="user-icon" src="<?php echo ($category->category_image)? $this->helper->__upload_url().$category->category_image : $template_path."/manager/assets/images/error/no_image_available.jpg";?>">
									</div>
								</td>
                                 <td><?php echo $category->category_name;?></td>
                                 <td><?php echo ($category->parentname)? $category->parentname : "";?></td>
                                 <td class="text-center"><?php echo ($category->category_status == 1)? "Hoạt động" : "Ngừng hoạt động"; ?></td>
                                 <td>
									<div class="d-flex justify-content-end align-items-center">
										<a class="" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit" href="<?php echo XC_URL;?>/admin/product-categories?action=edit&id=<?php echo $category->id;?>">
											<svg xmlns="http://www.w3.org/2000/svg" class="text-secondary mx-4" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
											</svg>
										</a>
										<a class="badge bg-danger" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete" href="#">
											<svg xmlns="http://www.w3.org/2000/svg" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
											</svg>
										</a>
									</div>
								</td>
                              </tr>
							  <?php
							  $i++;
								}
							  ?>
                           </tbody>
                        </table>
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
		$("#aou_category_image").on("change", function (e) {
			
			var formData = new FormData();
			formData.append('file', $('#aou_category_image')[0].files[0]);
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
						$("#aou_category_image").val("");
						deleteuploadedimg();
					}
				}
			});
			return false;
		});
		$("#aou_category_icon").on("change", function (e) {
			
			var formData = new FormData();
			formData.append('file', $('#aou_category_icon')[0].files[0]);
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
						$('#fillicon').append('<div class="mr-2 mb-2  boximgdt"><img src="'+data.url+'" class="img-fluid img-thumbnail rounded imgdt " ><i class="far fa-trash-alt btndel"></i><input type="hidden" value="'+data.url+'" name="meta-hinh_anh2[]"><input type="hidden" value="'+data.id+'" name="meta-id_hinh_anh2[]"></div>');
						$("#aou_category_icon").val("");
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
	