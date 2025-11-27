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
		$("#btn-add").on("click", function()
		{
			$("#menu-title").val("");
			$("#menu-url").val("");
			$("#menu-order").val(0);
			$("#updatetype").val("new");
			$("#id").val("");
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
						url: "<?php echo XC_URL;?>/api/deletemenu",
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
		$(".btn-edit").on("click", function()
		{
			var id = $(this).attr("data-id");
			var name = $(this).attr("data-name");
			var url = $(this).attr("data-url");
			var order = $(this).attr("data-order");
			$("#menu-title").val(name);
			$("#menu-url").val(url);
			$("#menu-order").val(order);
			$("#updatetype").val("edit");
			$("#id").val(id);
			$("#add-place-modal").modal();
			return false;
			
		});
		$("#btn-save").on("click",function()
		{
			var id = $("#id").val();
			var updatetype = $("#updatetype").val();
			var title = $("#menu-title").val();
			var url = $("#menu-url").val();
			var order = $("#menu-order").val();
			fd = new FormData();
			fd.append('id', id);
			fd.append('title', title );
			fd.append('url', url);
			fd.append('order', order);
			fd.append('updatetype', updatetype);
			$.ajax({
				type: "POST",
				url: "<?php echo XC_URL;?>/api/addmenu",
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
            <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2">Danh sách menu</h1>
            <button type="button" class="btn btn-alt-success my-2" id="btn-add">
                <i class="fa fa-fw fa-plus mr-1"></i> Thêm menu
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
                        <th>Tên</th>
                        <th style="width: 30%;">Đường dẫn</th>
                        <th style="width: 15%;">Sắp xếp</th>
                        <th style="width: 12%;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
				<?php
					$i = 1;
					foreach($menus as $p)
					{
					?>
					<tr>
                        <td class="text-center"><?php echo $i;?></td>
                        <td class="font-w600"><?php echo $p->menu_title;?></td>
						<td><?php echo $p->menu_url;?></td>
						<td><?php echo $p->menu_order;?></td>
						<td class="text-center">
                            <div class="btn-group">
                                <button type="button" data-id="<?php echo $p->id;?>" data-name="<?php echo $p->menu_title;?>" data-url="<?php echo $p->menu_url;?>" data-order="<?php echo $p->menu_order;?>" class="btn btn-sm btn-primary js-tooltip-enabled btn-edit" data-toggle="tooltip" title="" data-original-title="Edit">
                                    <i class="fa fa-pencil-alt"></i>
                                </button>
                                <button type="button" data-id="<?php echo $p->id;?>" class="btn btn-sm btn-primary js-tooltip-enabled btn-delete" data-toggle="tooltip" title="" data-original-title="Delete">
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
                    <h3 class="block-title">Thêm tỉnh</h3>
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
                            <input type="text" class="form-control" id="menu-title" name="menu-title" placeholder="Tiêu đề">
                        </div>
						<div class="form-group">
                            <label for="dm-post-add-title">Đường dẫn</label>
                            <input type="text" class="form-control" id="menu-url" name="menu-url" placeholder="Đường dẫn">
                        </div>
						<div class="form-group">
                            <label for="dm-post-add-title">Sắp xếp</label>
                            <input type="number" class="form-control" id="menu-order" name="menu-order" placeholder="0">
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