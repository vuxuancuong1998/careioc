<?php include_once "header.php";?>
<script>
	//
	$(document).ready(function(){
		$(".btn-edit-pack").on("click", function()
		{	
			var status = $(this).attr("data-status");
			var id = $(this).attr("data-id");
			$("#update_pid").val(id);
			$("#current-status").val(status);
			$("#update-status-modal").modal();
			return false;
		});
		$("#btn-create-package").on("click", function()
		{
			$("#create-package-modal").modal();
			return false;
		});
		$("#btn-create").on("click", function()
		{
			var packtype = $("#package-type option:selected").val();
			$.ajax({
				type: "POST",
				url: "<?php echo XC_URL;?>/api/CreatePackage",
				data: {packtype: packtype},
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
						  text: 'Đã tạo thành công!',
						  timer: 1000
						})
						setTimeout(function(){ window.location= ("<?php echo XC_URL;?>/order/packages");     }, 1200);
									
					}
				}
			});
		});
		$(".btn-delete-post").on("click", function()
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
				  var pid = button.attr("data-id");
					$.ajax({
						type: "POST",
						url: "<?php echo XC_URL;?>/api/admindeletelisting",
						data: {id: pid},
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
								setTimeout(function(){ window.location= ("<?php echo XC_URL;?>/admin/posts");     }, 3000);
											
							}
						}
					});
					
			  }
			})
		  return false;
		});
		
		
	});
</script>
<div class="modal" id="create-package-modal" tabindex="-1" aria-labelledby="modal-block-large" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Tạo kiện hàng</h3>
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
                            <label for="update-type">Loại kiện hàng</label>
							<select class="form-control" id="package-type" name="package-type">
								<?php 
								foreach($packtype as $type)
								{
								?>
									<option value="<?php echo $type->id;?>"><?php echo $type->type_name;?></option>
								<?php
								}
								?>
                            </select>
                        </div>
                    </div>
                </div>
                </div>
                <div class="block-content block-content-full text-right bg-light">
					<input type="hidden" id="updatetype" value="new">
					<input type="hidden" id="uid" value="">
                    <button type="button" class="btn btn-sm btn-light" data-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-sm btn-primary" id="btn-create">Lưu</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="update-status-modal" tabindex="-1" aria-labelledby="modal-block-large" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Cập nhật trạng thái</h3>
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
								<label for="dm-post-add-title">Trạng thái hiện tại</label>
								<input type="text" readonly class="form-control" id="current-status" name="current-status" placeholder="" value="">
							</div>
                        <div class="form-group">
                            <label for="update-type">Trạng thái mới</label>
							<select class="form-control" id="update-type" name="update-type">
								<?php 
								foreach($packstatus as $status)
								{
								?>
									<option value="<?php echo $status->id;?>"><?php echo $status->status_name;?></option>
								<?php
								}
								?>
                            </select>
                        </div>
                    </div>
                </div>
                </div>
                <div class="block-content block-content-full text-right bg-light">
					<input type="hidden" id="updatetype" value="new">
					<input type="hidden" id="update_pid" value="">
                    <button type="button" class="btn btn-sm btn-light" data-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-sm btn-primary" id="btn-save">Lưu</button>
                </div>
            </div>
        </div>
    </div>
</div>
<main id="main-container">
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2">Danh sách kiện hàng</h1>
            <button type="button" class="btn btn-alt-success my-2" id="btn-create-package">
                <i class="fa fa-fw fa-plus mr-1"></i> Thêm kiện hàng
            </button>
        </div>
   </div>
</div>
<div class="content">
    <div class="row row-deck">
        <div class="col-6 col-lg-3">
            <a class="block block-rounded block-link-shadow text-center" href="javascript:void(0)">
                <div class="block-content py-5">
                    <div class="font-size-h3 font-w600 text-info mb-1">0</div>
                    <p class="font-w600 font-size-sm text-info text-uppercase mb-0">
                        Mới
                    </p>
                </div>
            </a>
        </div>
        <div class="col-6 col-lg-3">
            <a class="block block-rounded block-link-shadow text-center" href="javascript:void(0)">
                <div class="block-content py-5">
                    <div class="font-size-h3 font-w600 text-warning	mb-1">0</div>
                    <p class="font-w600 font-size-sm text-warning text-uppercase mb-0">
                        Đã đóng hàng
                    </p>
                </div>
            </a>
        </div>
        <div class="col-6 col-lg-3">
            <a class="block block-rounded block-link-shadow text-center" href="">
                <div class="block-content py-5">
                    <div class="font-size-h3 font-w600 text-success  mb-1">0</div>
                    <p class="font-w600 font-size-sm text-success text-uppercase mb-0">
                        Đã về kho Việt Nam
                    </p>
                </div>
            </a>
        </div>
        <div class="col-6 col-lg-3">
            <a class="block block-rounded block-link-shadow text-center" href="#">
                <div class="block-content py-5">
                    <div class="font-size-h3 font-w600 text-dark mb-1"><?php echo number_format($totalpack,0);?></div>
                    <p class="font-w600 font-size-sm text-muted text-uppercase mb-0">
                        Tổng kiện hàng
                    </p>
                </div>
            </a>
        </div>
    </div>
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">Danh sách kiện hàng</h3>
            <div class="block-options">
                <div class="dropdown">
                    <button type="button" class="btn btn-light" id="dropdown-ecom-filters" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Lọc <i class="fa fa-angle-down ml-1"></i> 
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-ecom-filters" style="">
                        <a class="dropdown-item d-flex align-items-center justify-content-between" href="javascript:void(0)">
                            New
                            <span class="badge badge-success badge-pill">260</span>
                        </a>
                        <a class="dropdown-item d-flex align-items-center justify-content-between" href="javascript:void(0)">
                            Out of Stock
                            <span class="badge badge-danger badge-pill">63</span>
                        </a>
                        <a class="dropdown-item d-flex align-items-center justify-content-between" href="javascript:void(0)">
                            All
                            <span class="badge badge-secondary badge-pill">36k</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="block-content bg-body-dark">
            <form action="#" method="POST" onsubmit="return false;">
                <div class="form-group">
                    <input type="text" class="form-control form-control-alt" id="dm-ecom-products-search" name="dm-ecom-products-search" placeholder="Tìm kiếm bằng mã kiện hàng">
                </div>
            </form>
        </div>
        <div class="block-content">
            <div class="table-responsive">
                <table class="table table-borderless table-striped table-vcenter">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 100px;">Mã</th>
							<th class="d-none d-md-table-cell">Người tạo</th>
							<th class="d-none d-sm-table-cell text-center">Thời gian</th>
							<th class="d-none d-sm-table-cell text-center">Trạng thái</th>
                            <th class="d-none d-sm-table-cell text-center">Loại kiện</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                    </thead>   
                    <tbody>
						<?php
						foreach($packs as $pack)
						{
						?>
						<tr>
                            <td class="text-center font-size-sm">
                                <a class="font-w600" href="<?php echo XC_URL;?>/order/package/<?php echo $pack->pack_code;?>">
                                    <strong><?php echo $pack->pack_code;?></strong>
                                </a>
                            </td>
							<td class="d-none d-md-table-cell font-size-sm">
                                <a class="font-w600" href=""><?php echo $pack->user_fullname;?></a>
                            </td>
							<td class="d-none d-sm-table-cell text-center font-size-sm"><?php echo date("H:i d/m/Y",strtotime($pack->pack_create_time));?></td>
							<td class="d-none d-sm-table-cell text-center">
							<span class="badge badge-<?php echo $pack->status_label;?>"><?php echo $pack->status_name;?></span>
                                
                            </td>
                            
                            
							<td class="text-center d-none d-sm-table-cell font-size-sm">
                                <strong><?php echo $pack->type_name;?></strong>
                            </td>
                            <td class="text-center font-size-sm">
								<a target="_blank" href="<?php echo XC_URL;?>/order/package/<?php echo $pack->pack_code;?>" class="btn btn-sm btn-alt-secondary" data-id="<?php echo $pack->pid;?>" >
                                    <i class="fa fa-fw fa-boxes"></i>
                                </a>
								<a href="" class="btn btn-sm btn-alt-primary btn-edit-pack" data-status="<?php echo $pack->status_name;?>" data-id="<?php echo $pack->pid;?>">
                                    <i class="fa fa-fw fa-pen"></i>
                                </a>
								<a href="" class="btn btn-sm btn-alt-danger btn-edit-pack" data-id="<?php echo $pack->pid;?>">
                                    <i class="fa fa-fw fa-times"></i>
                                </a>
                            </td>
							
                        </tr>
						<?php
						}
						?>
                                               
                                            </tbody>
                </table>
            </div>
            <nav aria-label="Photos Search Navigation">
                <ul class="pagination justify-content-end mt-2">
                    <li class="page-item">
                        <a class="page-link" href="javascript:void(0)" tabindex="-1" aria-label="Previous">
                            Prev
                        </a>
                    </li>
					<?php 
					for($i = 1;$i <= round($totalpage,0)+1;$i++)
					{
					?>
                    <li class="page-item <?php echo ($page == $i) ? "active" : "";?>">
                        <a class="page-link" href="<?php echo XC_URL;?>/admin/posts/?page=<?php echo $i;?>"><?php echo $i;?></a>
                    </li>
					<?php
					}
					?>
                    <li class="page-item">
                        <a class="page-link" href="javascript:void(0)" aria-label="Next">
                            Next
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>
    </main>
<?php include_once "footer.php";?>