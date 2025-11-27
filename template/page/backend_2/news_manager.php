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
						url: "<?php echo XC_URL;?>/api/deletenews",
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
								  timer: 2700
								})
								setTimeout(function(){ location.reload();     }, 3000);
											
							}
						}
					});
					
			  }
			})
			
			
		});
		$(".btn-edit-place").on("click", function()
		{
			var id = $(this).attr("data-id");
			$.ajax({
				type: "POST",
				url: "<?php echo XC_URL;?>/api/getnews",
				data: {id:id},
				dataType: "json",
				cache: false,
				success: function(data)
				{
					
					if(data.status == 200)
					{
						console.log(data);
						$("#dm-post-add-title").val(data.title);
						$("#cat-select").html(data.category);
						$('.js-summernote').summernote('code',data.content);
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
			var category = $("#cat-select").val();
			var huyen = $("#district-select").val();
			var gioithieu = $('.js-summernote').summernote('code');
			
			//alert(hinhanh);
			//{title: title, tinh: tinh,huyen: huyen, hinhanh: hinhanh,noidung: gioithieu}
			fd = new FormData();
			fd.append('hinhanh', $("#place-file-input-custom").get(0).files[0]);
			fd.append('id', id);
			fd.append('title', title );
			fd.append('category', category);
			fd.append('updatetype', updatetype);
			fd.append('noidung', gioithieu);
			$.ajax({
				type: "POST",
				url: "<?php echo XC_URL;?>/api/addnews",
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
            <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2">Danh sách tin tức</h1>
            <button type="button" class="btn btn-alt-success my-2" id="btn-add-place">
                <i class="fa fa-fw fa-plus mr-1"></i> Thêm tin tức
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
                        <th class="d-none d-sm-table-cell" style="width: 20%;">Chuyên mục</th>
                        <th class="d-none d-sm-table-cell" style="width: 20%;">Người đăng</th>
                        <th style="width: 15%;">Ngày đăng</th>
                        <th style="width: 12%;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
				<?php
					$i = 1;
					foreach($posts as $post)
					{
					?>
					<tr>
                        <td class="text-center"><?php echo $i;?></td>
                        <td class="font-w600"><?php echo $post->news_title;?></td>
                        <td class="d-none d-sm-table-cell">
                            <?php echo $post->cat_name;?>
                        </td>
                        <td class="d-none d-sm-table-cell">
							<?php echo $post->user_fullname;?>
                        </td>
						<td><?php echo date("d/m/Y",strtotime($post->news_date));?></td>
						<td class="text-center">
                            <div class="btn-group">
                                <button type="button" data-id="<?php echo $post->pid;?>" class="btn btn-sm btn-primary js-tooltip-enabled btn-edit-place" data-toggle="tooltip" title="" data-original-title="Edit">
                                    <i class="fa fa-pencil-alt"></i>
                                </button>
                                <button type="button" data-id="<?php echo $post->pid;?>" class="btn btn-sm btn-primary js-tooltip-enabled btn-delete" data-toggle="tooltip" title="" data-original-title="Delete">
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
                    <h3 class="block-title">Thêm tin tức</h3>
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
                            <label for="dm-post-add-title">Tiêu đề tin</label>
                            <input type="text" class="form-control" id="dm-post-add-title" name="dm-post-add-title" placeholder="Tiêu đề tin">
                        </div>
						<div class="form-group">
                            <label for="dm-post-add-title">Chuyên mục</label>
                            <select class="form-control" id="cat-select" name="example-select">
								<option value="">Vui lòng chọn</option>
                                <?php 
								   foreach($news_cat as $cat)
								   {
								   ?>
								   <option value="<?php echo $cat->id;?>"><?php echo $cat->cat_name;?></option>
								   <?php
								   }
								   ?>
								
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="dm-post-add-excerpt">Nội dung</label>
                            <div class="block-content block-content-full">
								<div class="js-summernote"></div>
							</div>
                            <div class="form-text text-muted font-size-sm font-italic">Giới thiệu về địa điểm này</div>
                        </div>
                        <div class="form-group row">
                            <div class="col-xl-12">
                                <label>Ảnh đại diện</label>
								<div class="custom-file">
                                <input type="file" class="custom-file-input" data-toggle="custom-file-input" id="place-file-input-custom" accept="image/x-png,image/gif,image/jpeg" name="example-file-input-custom">
                                <label class="custom-file-label" for="example-file-input-custom">Choose file</label>
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