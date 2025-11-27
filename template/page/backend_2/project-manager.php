<?php include_once "header.php";?>
 <link rel="stylesheet" href="<?php echo $template_path;?>/backend/assets/js/plugins/summernote/summernote-bs4.css">
<link rel="stylesheet" href="<?php echo $template_path;?>/backend/assets/js/plugins/simplemde/simplemde.min.css">
 <link rel="stylesheet" href="<?php echo $template_path;?>/backend/assets/js/plugins/datatables/dataTables.bootstrap4.css">
<link rel="stylesheet" href="<?php echo $template_path;?>/backend/assets/js/plugins/datatables/buttons-bs4/buttons.bootstrap4.min.css">
<script src="<?php echo $template_path;?>/backend/assets/js/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo $template_path;?>/backend/assets/js/plugins/datatables/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo $template_path;?>/backend/assets/js/plugins/datatables/buttons/dataTables.buttons.min.js"></script>
<script src="<?php echo $template_path;?>/backend/assets/js/plugins/datatables/buttons/buttons.print.min.js"></script>
<script src="<?php echo $template_path;?>/backend/assets/js/plugins/datatables/buttons/buttons.html5.min.js"></script>
<script src="<?php echo $template_path;?>/backend/assets/js/plugins/datatables/buttons/buttons.flash.min.js"></script>
<script src="<?php echo $template_path;?>/backend/assets/js/plugins/datatables/buttons/buttons.colVis.min.js"></script>
<script src="<?php echo $template_path;?>/backend/assets/js/plugins/summernote/summernote-bs4.min.js"></script>
<script src="<?php echo $template_path;?>/backend/assets/js/plugins/simplemde/simplemde.min.js"></script>
<script>
    $(document).ready(function(){
		jQuery(function(){ Dashmix.helpers(['summernote', 'simplemde', 'ckeditor']); });
        jQuery(".js-dataTable-full-pagination").dataTable({
			pagingType: "full_numbers",
			pageLength: 20,
			lengthMenu: [
				[5, 10, 20],
				[5, 10, 20]
			],
			autoWidth: !1
		})

    })
    </script>
	<script>
	//
	$(document).ready(function(){
		$("#btn-add-place").on("click", function()
		{
			$("#dm-post-add-title").val("");
					$("#province-select").val(0);
					$("#district-select").html("");
					$('.js-summernote').summernote('code',"");
					$("#updatetype").val("new");
					$("#placeid").val("");
			$("#add-place-modal").modal();
		});
		$(".btn-edit").on("click", function()
		{
			var id = $(this).attr("data-id");
			$.ajax({
			type: "POST",
			url: "<?php echo XC_URL;?>/api/getproject",
			data: {id:id},
			dataType: "json",
			cache: false,
			success: function(data)
			{
				
				if(data.status == 200)
				{
					console.log(data);
					$("#new-project-name").val(data.title);
					$("#new-project-holder").val(data.holder);
					$("#new-project-address").val(data.address);
					$("#new-project-category").val(data.category);
					$("#new-project-area").val(data.area);
					$("#new-project-scale").val(data.scale);
					$("#new-project-price").val(data.price);
					$("#new-project-area-detail").val(data.area_detail);
					$('.js-summernote').summernote('code',data.intro);
					$("#updatetype").val("edit");
					$("#id").val(id);
					$("#add-place-modal").modal();
				}
				else
				{
					//$("#meta-quan_huyen").val("");
				}
			}
		});
			
		});
		$("#btn-save").on("click",function()
		{
			var id = $("#id").val();
			var updatetype = $("#updatetype").val();
			var name = $("#new-project-name").val();
			var holder = $("#new-project-holder").val();
			var address = $("#new-project-address").val();
			var category = $("#new-project-category").val();
			var area = $("#new-project-area").val();
			var scale = $("#new-project-scale").val();
			var ad = $("#new-project-area-detail").val();
			var price = $("#new-project-price").val();
			var gioithieu = $('.js-summernote').summernote('code');
			var hinhanh = $("input[name='meta-id_hinh_anh[]']").map(function(){return $(this).val();}).get();
			fd = new FormData();
			fd.append('anhbia', $("#place-file-input-custom").get(0).files[0]);
			fd.append('id', id);
			fd.append('name', name );
			fd.append('holder', holder);
			fd.append('address', address);
			fd.append('category', category);
			fd.append('area', area);
			fd.append('scale', scale);
			fd.append('ad', ad);
			fd.append('price', price);
			fd.append('hinhanh', hinhanh);
			fd.append('updatetype', updatetype);
			fd.append('noidung', gioithieu);
			$.ajax({
				type: "POST",
				url: "<?php echo XC_URL;?>/api/addproject",
				data: fd,
				dataType: "json",
				cache: false,
				processData: false,  // tell jQuery not to process the data
				contentType: false, 
				enctype: 'multipart/form-data',
				success: function(data)
				{
					console.log(data.data);
					if(data.status == 200)
					{
						location.reload();
					}
					else
					{
						//$("#meta-quan_huyen").val("");
					}
				}
			});
			return false;
		});
		
		
	});
	</script>
<main id="main-container">
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2">Danh sách dự án</h1>
            <button type="button" class="btn btn-alt-success my-2" id="btn-add-place">
                <i class="fa fa-fw fa-plus mr-1"></i> Thêm dự án
            </button>
        </div>
   </div>
</div>
<div class="content">
    <div class="block block-rounded">
        
        <div class="block-content block-content-full">
            <table class="table table-bordered table-striped table-vcenter js-dataTable-full-pagination">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 8%;">#</th>
                        <th>Tên dự án</th>
                        <th class="d-none d-sm-table-cell" style="width: 20%;">Chủ đầu tư</th>
                        <th class="d-none d-sm-table-cell" style="width: 20%;">Loại dự án</th>
                        <th style="width: 15%;">Giá</th>
                        <th style="width: 12%;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
				<?php
					$i = 1;
					foreach($projects as $project)
					{
					?>
					<tr>
                        <td class="text-center"><?php echo $i;?></td>
                        <td class="font-w600"><?php echo $project->project_name;?></td>
                        <td class="d-none d-sm-table-cell">
							<?php echo $project->project_holder;?>
                        </td>
                        <td class="d-none d-sm-table-cell">
							<?php echo $project->project_categories;?>
                        </td>
						<td><?php echo $project->project_price;?></td>
						<td class="text-center">
                            <div class="btn-group">
                                <button type="button" data-id="<?php echo $project->id;?>" class="btn btn-sm btn-primary js-tooltip-enabled btn-edit" data-toggle="tooltip" title="" data-original-title="Edit">
                                    <i class="fa fa-pencil-alt"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-primary js-tooltip-enabled" data-toggle="tooltip" title="" data-original-title="Delete">
                                    <i class="fa fa-times"></i>
                                </button>
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
    </main>
	<div class="modal" id="add-place-modal" tabindex="-1" aria-labelledby="modal-block-large" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Thêm dự án</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="fa fa-fw fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <div class="row justify-content-center push">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="dm-post-add-title">Tên dự án</label>
                            <input type="text" class="form-control" id="new-project-name" name="new-project-name" placeholder="Tên dự án">
                        </div>
						<div class="form-group">
                            <label for="dm-post-add-title">Chủ đầu tư</label>
                            <input type="text" class="form-control" id="new-project-holder" name="new-project-holder" placeholder="Chủ đầu tư">
                        </div>
						<div class="form-group">
                            <label for="dm-post-add-title">Địa chỉ</label>
                            <input type="text" class="form-control" id="new-project-address" name="new-project-address" placeholder="Địa chỉ">
                        </div>
						<div class="form-group">
                            <label for="dm-post-add-title">Loại hình sản phẩm</label>
                            <input type="text" class="form-control" id="new-project-category" name="new-project-category" placeholder="Loại hình sản phẩm">
                        </div>
						<div class="form-group">
                            <label for="dm-post-add-title">Tổng diện tích khu đất</label>
                            <input type="text" class="form-control" id="new-project-area" name="new-project-area" placeholder="Tổng diện tích">
                        </div>
						<div class="form-group">
                            <label for="dm-post-add-title">Quy mô dự án</label>
                            <input type="text" class="form-control" id="new-project-scale" name="new-project-scale" placeholder="Quy mô dự án">
                        </div>
						<div class="form-group">
                            <label for="dm-post-add-title">Cơ cấu diện tích</label>
                            <input type="text" class="form-control" id="new-project-area-detail" name="new-project-area-detail" placeholder="Cơ cấu diện tích">
                        </div>
						<div class="form-group">
                            <label for="dm-post-add-title">Giá sản phẩm</label>
                            <input type="text" class="form-control" id="new-project-price" name="new-project-price" placeholder="Giá sản phẩm">
                        </div>
						
                        <div class="form-group">
                            <label for="dm-post-add-excerpt">Giới thiệu</label>
                            <div class="block-content block-content-full">
								<div class="js-summernote"></div>
							</div>
                            <div class="form-text text-muted font-size-sm font-italic">Giới thiệu về dự án này</div>
                        </div>
						<div class="form-group">
                            <label for="dm-post-add-excerpt">Tiện ích</label>
                            <div class="block-content block-content-full">
								<div class="js-summernote" id="project-utility"></div>
							</div>
                        </div>
                        <div class="form-group">
                            <label for="dm-post-add-excerpt">Thiết kế</label>
                            <div class="block-content block-content-full">
								<div class="js-summernote" id="project-design"></div>
							</div>
                        </div>
                        <div class="form-group row">
                            <div class="col-xl-12">
                                <label>Ảnh bìa</label>
								<div class="custom-file">
                                <input type="file" class="custom-file-input" data-toggle="custom-file-input" id="place-file-input-custom" accept="image/x-png,image/gif,image/jpeg" name="example-file-input-custom">
                                <label class="custom-file-label" for="example-file-input-custom">Choose file</label>
                            </div>
                            </div>
                        </div>
						<script>
                                    $(document).ready(function(){
                                    
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
														
													}
												}
										});
										return false;
										});
										
                                        $('.btndel').click(function(){
                                                $(this).closest('.boximgdt').remove();
                                            })
                                    })
                                 </script>
						
							   
						<div class="form-group row">
                            <div class="col-xl-12">
                                <label>Hình ảnh</label>
								<div class="custom-file">
                                <input type="file" name="btnupload" id="btnupload" class="custom-file-input" data-toggle="custom-file-input" accept="image/x-png,image/gif,image/jpeg" >
                                <label class="custom-file-label" for="place-feature-file-input-custom">Choose file</label>
                            </div>
                            </div>
                        </div>
						<div class="form-group row fillimage" id="fillimage">
							   </div>
                    </div>
                </div>
                </div>
                <div class="block-content block-content-full text-right bg-light">
					<input type="hidden" id="updatetype" value="new">
					<input type="hidden" id="id" value="">
                    <button type="button" class="btn btn-sm btn-light" data-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-sm btn-primary" id="btn-save">Lưu</button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once "footer.php";?>