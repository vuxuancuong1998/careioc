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
		$("#btn-add-cat").on("click", function()
		{
			$("#cat-title").val("");
			$("#updatetype").val("new");
			$("#id").val("");
			$("#add-cat-modal").modal();
		});
		$("#btn-save-meta").on("click", function()
		{
			var data = $("input[name='metadata[]']:checked").map(function(){return $(this).val();}).get();
			console.log(data);
			var id = $("#catmetaid").val();
			$.ajax({
				type: "POST",
				url: "<?php echo XC_URL;?>/api/updatecatmeta",
				data: {id:id,data:data},
				dataType: "json",
				cache: false,
				success: function(data)
				{
					console.log(data);
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
						Swal.fire({
						  icon: 'success',
						  title: 'Thành công',
						  text: 'Đã cập nhật thành công!',
						  timer: 1000
						})
						setTimeout(function(){ location.reload();     }, 1200);
									
					}
				}
			});
		});
		$(".btn-edit-meta").on("click", function()
		{
			//metabox
			var id = $(this).attr("data-id");
			$.ajax({
				type: "POST",
				url: "<?php echo XC_URL;?>/api/getmetadatacat",
				data: {id:id},
				dataType: "json",
				cache: false,
				success: function(data)
				{
					
					if(data.status != "200")
					{
						
					}
					else
					{
						$("#metabox").html(data.data);
						$("#catmetaid").val(id);
					}
				}
			});
			$("#edit-meta-modal").modal();
		});
		$(".btn-delete").on("click", function()
		{
			var button = $(this);
			Swal.fire({
			  title: 'Bạn có chắc chắn?',
			  text: "Thao tác này không thể khôi phục được!",
			  icon: 'warning',
			  showCancelButton: true,
			  confirmButtonColor: '#3085d6',
			  cancelButtonColor: '#d33',
			  confirmButtonText: 'Đồng ý',
			  cancelButtonText: 'Hủy',
			}).then((result) => {
			  if (result.isConfirmed) {
				  var id = button.attr("data-id");
					$.ajax({
						type: "POST",
						url: "<?php echo XC_URL;?>/api/deletecat",
						data: {id:id},
						dataType: "json",
						cache: false,
						success: function(data)
						{
							
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
								Swal.fire({
								  icon: 'success',
								  title: 'Thành công',
								  text: 'Đã xóa thành công!',
								  timer: 1000
								})
								setTimeout(function(){ location.reload();     }, 1200);
											
							}
						}
					});
					
			  }
			})
			
			
		});
		$(".btn-edit-cat").on("click", function()
		{
			var id = $(this).attr("data-id");
			var catname = $(this).attr("data-name");
			$("#cat-title").val(catname);
			$("#updatetype").val("edit");
			$("#id").val(id);
			$("#add-cat-modal").modal();
			
		});
		$("#btn-save").on("click",function()
		{
			var id = $("#id").val();
			var updatetype = $("#updatetype").val();
			var title = $("#cat-title").val();
			fd = new FormData();
			fd.append('id', id);
			fd.append('title', title );
			fd.append('updatetype', updatetype);
			$.ajax({
				type: "POST",
				url: "<?php echo XC_URL;?>/api/addcat",
				data: fd,
				dataType: "json",
				cache: false,
				processData: false,  // tell jQuery not to process the data
				contentType: false, 
				enctype: 'multipart/form-data',
				success: function(data)
				{
					if(data.status == 200)
					{
						location.reload();
					}
					else
					{
						Swal.fire({
						  icon: 'error',
						  title: 'Oops...',
						  text: data.message,
						  footer: '<a href>Xem thêm về lỗi này?</a>'
						})
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
            <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2">Danh sách tỉnh thành phố</h1>
            <button type="button" class="btn btn-alt-success my-2" id="btn-add-cat">
                <i class="fa fa-fw fa-plus mr-1"></i> Thêm danh mục
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
                        <th style="width: 15%;">Tin đăng</th>
                        <th style="width: 12%;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
				<?php
					$i = 1;
					foreach($categories as $c)
					{
					?>
					<tr>
                        <td class="text-center"><?php echo $i;?></td>
                        <td class="font-w600"><?php echo $c->cat_name;?></td>
						<td><?php echo number_format($c->countpost,0);?></td>
						<td class="text-center">
                            <div class="btn-group">
                                <button type="button" data-id="<?php echo $c->id;?>" data-name="<?php echo $c->cat_name;?>" class="btn btn-sm btn-primary js-tooltip-enabled btn-edit-meta" data-toggle="tooltip" title="" data-original-title="Meta">
                                    <i class="fa fa-atom"></i>
                                </button>
								<button type="button" data-id="<?php echo $c->id;?>" data-name="<?php echo $c->cat_name;?>" class="btn btn-sm btn-primary js-tooltip-enabled btn-edit-cat" data-toggle="tooltip" title="" data-original-title="Edit">
                                    <i class="fa fa-pencil-alt"></i>
                                </button>
                                <button type="button" data-id="<?php echo $c->id;?>" class="btn btn-sm btn-primary js-tooltip-enabled btn-delete" data-toggle="tooltip" title="" data-original-title="Delete">
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
	<div class="modal" id="add-cat-modal" tabindex="-1" aria-labelledby="modal-block-large" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Thêm danh mục</h3>
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
                            <label for="dm-post-add-title">Tiêu đề</label>
                            <input type="text" class="form-control" id="cat-title" name="cat-title" placeholder="Tiêu đề">
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
<div class="modal" id="edit-meta-modal" tabindex="-1" aria-labelledby="modal-block-large" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Thêm danh mục</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="fa fa-fw fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <div class="row justify-content-center push">
                    <div class="col-md-12">
                        <div class="form-group form-row" id="metabox">
                            
                        </div>
                    </div>
                </div>
                </div>
                <div class="block-content block-content-full text-right bg-light">
					<input type="hidden" id="catmetaid" value="">
                    <button type="button" class="btn btn-sm btn-light" data-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-sm btn-primary" id="btn-save-meta">Lưu</button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once "footer.php";?>