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
		$(".btn-edit-place").on("click", function()
		{
			var id = $(this).attr("data-id");
			$.ajax({
			type: "POST",
			url: "<?php echo XC_URL;?>/api/getplace",
			data: {id:id},
			dataType: "json",
			cache: false,
			success: function(data)
			{
				
				if(data.status == 200)
				{
					console.log(data);
					$("#dm-post-add-title").val(data.title);
					$("#province-select").val(data.province);
					$("#district-select").html(data.district);
					$('.js-summernote').summernote('code',data.about);
					$("#updatetype").val("edit");
					$("#placeid").val(id);
					$("#add-place-modal").modal();
				}
				else
				{
					//$("#meta-quan_huyen").val("");
				}
			}
		});
			
		});
		$("#btn-save-place").on("click",function()
		{
			var id = $("#placeid").val();
			var updatetype = $("#updatetype").val();
			var title = $("#dm-post-add-title").val();
			var tinh = $("#province-select").val();
			var huyen = $("#district-select").val();
			var gioithieu = $('.js-summernote').summernote('code');
			var hinhanh = $('#place-file-input-custom')[0].files[0];
			//alert(hinhanh);
			//{title: title, tinh: tinh,huyen: huyen, hinhanh: hinhanh,noidung: gioithieu}
			fd = new FormData();
			fd.append('hinhanh', $("#place-file-input-custom").get(0).files[0]);
			fd.append('feature', $("#place-feature-file-input-custom").get(0).files[0]);
			fd.append('id', id);
			fd.append('title', title );
			fd.append('tinh', tinh);
			fd.append('huyen', huyen);
			fd.append('updatetype', updatetype);
			fd.append('noidung', gioithieu);
			$.ajax({
				type: "POST",
				url: "<?php echo XC_URL;?>/api/addplace",
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
            <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2">Danh sách địa điểm</h1>
            <button type="button" class="btn btn-alt-success my-2" id="btn-add-place">
                <i class="fa fa-fw fa-plus mr-1"></i> Thêm địa điểm
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
                        <th>Tiêu đề</th>
                        <th class="d-none d-sm-table-cell" style="width: 20%;">Quận/huyện</th>
                        <th class="d-none d-sm-table-cell" style="width: 20%;">Tỉnh</th>
                        <th style="width: 15%;">Tin đăng</th>
                        <th style="width: 12%;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
				<?php
					$i = 1;
					foreach($places as $place)
					{
					?>
					<tr>
                        <td class="text-center"><?php echo $i;?></td>
                        <td class="font-w600"><?php echo $place->place_name;?></td>
                        <td class="d-none d-sm-table-cell">
                            <?php echo $place->district_name;?>
                        </td>
                        <td class="d-none d-sm-table-cell">
							<?php echo $place->province_name;?>
                        </td>
						<td><?php echo number_format($place->countpost,0);?></td>
						<td class="text-center">
                            <div class="btn-group">
                                <button type="button" data-id="<?php echo $place->placeid;?>" class="btn btn-sm btn-primary js-tooltip-enabled btn-edit-place" data-toggle="tooltip" title="" data-original-title="Edit">
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
                    <h3 class="block-title">Thêm địa điểm</h3>
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
                            <label for="dm-post-add-title">Tên địa điểm</label>
                            <input type="text" class="form-control" id="dm-post-add-title" name="dm-post-add-title" placeholder="Tên địa điểm">
                        </div>
						<div class="form-group">
                            <label for="dm-post-add-title">Tỉnh/thành phố</label>
                            <select class="form-control" id="province-select" name="example-select">
								<option value="">Vui lòng chọn</option>
                                <?php 
								   $provinces = $this->home->get_list_province();
								   foreach($provinces as $p)
								   {
								   ?>
								   <option value="<?php echo $p->id;?>"><?php echo $p->province_name;?></option>
								   <?php
								   }
								   ?>
								
                            </select>
                        </div>
						<script>
							 $(document).ready(function(){
								 $('#province-select').change(function(){
									 var idparent = $(this).val();
									 if(idparent!='')
									 {
										 $.ajax({
											type: "POST",
											url: "<?php echo XC_URL;?>/api/getdistrict",
											data: {pid: idparent},
											dataType: "json",
											cache: false,
											success: function(data)
											{
												if(data.status == 200)
												{
													 $('#district-select').html(data.data).prop('disabled',false);
													
												}
												else
												{
													$("#district-select").val("");
												}
											}
										});
									 }else{
										 $('#district-select').html('<option value="">Vui lòng chọn</option>').prop('disabled',true);
										 $('#district-select').change();
									 }
								 });
							 });
						  </script>
						<div class="form-group">
                            <label for="dm-post-add-title">Quận/huyện</label>
                            <select class="form-control" id="district-select" name="example-select">
                                <option value="0">Please select</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="dm-post-add-excerpt">Giới thiệu</label>
                            <div class="block-content block-content-full">
								<div class="js-summernote"></div>
							</div>
                            <div class="form-text text-muted font-size-sm font-italic">Giới thiệu về địa điểm này</div>
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
						<div class="form-group row">
                            <div class="col-xl-12">
                                <label>Hình ảnh</label>
								<div class="custom-file">
                                <input type="file" class="custom-file-input" data-toggle="custom-file-input" id="place-feature-file-input-custom" accept="image/x-png,image/gif,image/jpeg" name="place-feature-file-input-custom">
                                <label class="custom-file-label" for="place-feature-file-input-custom">Choose file</label>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
                <div class="block-content block-content-full text-right bg-light">
					<input type="hidden" id="updatetype" value="new">
					<input type="hidden" id="placeid" value="">
                    <button type="button" class="btn btn-sm btn-light" data-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-sm btn-primary" id="btn-save-place">Lưu</button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once "footer.php";?>