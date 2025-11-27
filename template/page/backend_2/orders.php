<?php include_once "header.php";?>
<script>
	//
	$(document).ready(function(){
		$(".btn-update").on("click", function()
		{
			var pid = $(this).attr("data-id");
			$("#update-order-modal").modal();
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
<div class="modal" id="update-order-modal" tabindex="-1" aria-labelledby="modal-block-large" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Cập nhật đơn hàng</h3>
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
                            <label for="update-type">Loại cập nhật</label>
							<select class="form-control" id="update-type" name="update-type">
								<option value="1">Cập nhật trạng thái</option>
								<option value="2">Cập nhật vận đơn</option>
								<option value="3">Cập nhật giao hàng</option>
                            </select>
                        </div>
						<div id="update-zone">
							<div class="form-group">
								<label for="dm-post-add-title">Trạng thái hiện tại</label>
								<input type="text" readonly class="form-control" id="current-status" name="current-status" placeholder="" value="Đơn hàng mới">
							</div>
							<div class="form-group">
								<label for="group-select">Trạng thái mới</label>
								<select class="form-control" id="group-select" name="group-select">
									<option value="1">Đơn hàng mới</option>
									<option value="2">Đã đặt hàng</option>
									<option value="3">Đã về kho Trung Quốc</option>
									<option value="4">Đang về Việt Nam</option>
									<option value="5">Đã về kho Việt Nam</option>
								</select>
							</div>
						</div>
						
						
						
                    </div>
                </div>
                </div>
                <div class="block-content block-content-full text-right bg-light">
					<input type="hidden" id="updatetype" value="new">
					<input type="hidden" id="uid" value="">
                    <button type="button" class="btn btn-sm btn-light" data-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-sm btn-primary" id="btn-save">Lưu</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="assign-order-modal" tabindex="-1" aria-labelledby="modal-block-large" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Giao cho nhân viên</h3>
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
                            <label for="update-type">Loại cập nhật</label>
							<select class="form-control" id="update-type" name="update-type">
								<option value="1">Cập nhật trạng thái</option>
								<option value="2">Cập nhật vận đơn</option>
								<option value="3">Cập nhật giao hàng</option>
                            </select>
                        </div>
						<div id="update-zone">
							<div class="form-group">
								<label for="dm-post-add-title">Nhân viên hiện tại</label>
								<input type="text" readonly class="form-control" id="current-status" name="current-status" placeholder="" value="Đơn hàng mới">
							</div>
							<div class="form-group">
								<label for="group-select">Nhân viên mới</label>
								<select class="form-control" id="group-select" name="group-select">
									<option value="1">Đơn hàng mới</option>
									<option value="2">Đã đặt hàng</option>
									<option value="3">Đã về kho Trung Quốc</option>
									<option value="4">Đang về Việt Nam</option>
									<option value="5">Đã về kho Việt Nam</option>
								</select>
							</div>
						</div>
						
						
						
                    </div>
                </div>
                </div>
                <div class="block-content block-content-full text-right bg-light">
					<input type="hidden" id="updatetype" value="new">
					<input type="hidden" id="uid" value="">
                    <button type="button" class="btn btn-sm btn-light" data-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-sm btn-primary" id="btn-save">Lưu</button>
                </div>
            </div>
        </div>
    </div>
</div>
<main id="main-container">
<div class="content">
    <div class="row row-deck">
        <div class="col-6 col-lg-3">
            <a class="block block-rounded block-link-shadow text-center" href="javascript:void(0)">
                <div class="block-content py-5">
                    <div class="font-size-h3 font-w600 text-info mb-1">0</div>
                    <p class="font-w600 font-size-sm text-info text-uppercase mb-0">
                        Đã cọc
                    </p>
                </div>
            </a>
        </div>
        <div class="col-6 col-lg-3">
            <a class="block block-rounded block-link-shadow text-center" href="javascript:void(0)">
                <div class="block-content py-5">
                    <div class="font-size-h3 font-w600 text-warning	mb-1">0</div>
                    <p class="font-w600 font-size-sm text-warning text-uppercase mb-0">
                        Chờ cọc
                    </p>
                </div>
            </a>
        </div>
        <div class="col-6 col-lg-3">
            <a class="block block-rounded block-link-shadow text-center" href="">
                <div class="block-content py-5">
                    <div class="font-size-h3 font-w600 text-success  mb-1">0</div>
                    <p class="font-w600 font-size-sm text-success text-uppercase mb-0">
                        Hoàn thành
                    </p>
                </div>
            </a>
        </div>
        <div class="col-6 col-lg-3">
            <a class="block block-rounded block-link-shadow text-center" href="#">
                <div class="block-content py-5">
                    <div class="font-size-h3 font-w600 text-dark mb-1"><?php echo $totalpost;?></div>
                    <p class="font-w600 font-size-sm text-muted text-uppercase mb-0">
                        Tổng đơn hàng
                    </p>
                </div>
            </a>
        </div>
    </div>
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">Danh sách đơn hàng</h3>
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
            <form action="" method="GET" onsubmit="">
                <div class="form-group">
                    <input type="text" name="keyword" class="form-control form-control-alt" id="dm-ecom-products-search" placeholder="Tìm kiếm bằng mã đơn hàng, người mua">
                </div>
            </form>
        </div>
        <div class="block-content">
            <div class="table-responsive">
                <table class="table table-borderless table-striped table-vcenter">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 100px;">Mã</th>
							<th class="d-none d-md-table-cell">Khách hàng</th>
							<th class="d-none d-sm-table-cell text-center">Thời gian</th>
							<th class="d-none d-sm-table-cell text-center">Trạng thái</th>
                            <th class="d-none d-sm-table-cell text-center">Tổng tiền</th>
                            <th class="d-none d-sm-table-cell text-center">Đã cọc</th>
                            <th class="d-none d-sm-table-cell text-right">Nhân viên</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                    </thead>   
                    <tbody>
						<?php
						foreach($orders as $order)
						{
						?>
						<tr>
                            <td class="text-center font-size-sm">
                                <a class="font-w600" target="_blank" href="<?php echo XC_URL;?>/order/detail/<?php echo $order->order_code;?>">
                                    <strong><?php echo $order->order_code;?></strong>
                                </a>
                            </td>
							<td class="d-none d-md-table-cell font-size-sm">
                                <a class="font-w600" href=""><?php echo $order->user_firstname." ".$order->user_lastname;?></a>
                            </td>
							<td class="d-none d-sm-table-cell text-center font-size-sm"><?php echo date("H:i d/m/Y",strtotime($order->order_time));?></td>
							<td class="d-none d-sm-table-cell text-center">
							<span class="badge badge-<?php echo $order->status_label;?>"><?php echo $order->status_name;?></span>
                                
                            </td>
                            
                            <td class="text-center d-none d-sm-table-cell font-size-sm">
                                <strong><?php echo number_format($order->order_total,0);?></strong>
                            </td>
							<td class="text-center d-none d-sm-table-cell font-size-sm">
                                <strong><?php echo number_format($order->order_deposit,0);?></strong>
                            </td>
							<td class="text-right d-none d-sm-table-cell font-size-sm">
                                <strong><?php echo $order->staff_fullname;?></strong>
                            </td>
                            <td class="text-center font-size-sm">
								<a target="_blank" href="<?php echo XC_URL;?>/order/detail/<?php echo $order->order_code;?>" class="btn btn-sm btn-alt-secondary" data-id="<?php echo $order->oid;?>" target="_blank">
                                    <i class="fa fa-fw fa-pen"></i>
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