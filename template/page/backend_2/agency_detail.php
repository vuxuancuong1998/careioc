<?php include_once "header.php";?>
<?php $wonrate = general::getInstance()->get_config("won_rate");?>
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
   	$("#btn-update-status").on("click", function()
   	{
   		$("#update-status-modal").modal();
   	});
	$("#btn-update-fix-fee").on("click", function()
   	{
   		$("#updaet-fix-fee-modal").modal();
   	});
	$("#btn-assign").on("click", function()
   	{
   		$("#assign-staff-modal").modal();
   	});
   	$("#add-note").on("click", function()
   	{
   		$("#add-note-modal").modal();
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
   					  text: 'Đã thêm ghi chú thành công!',
   					  timer: 1000
   					})
   					setTimeout(function(){ location.reload();     }, 1200);
   								
   				}
   			}
   		});
   		return false;
   	});
   	$("#btn-save-note").on("click",function()
   	{
   		var id = $("#oid").val();
   		var content = $("#note-content").val();
   		$.ajax({
   			type: "POST",
   			url: "<?php echo XC_URL;?>/api/addnote",
   			data: {id: id, content: content},
   			dataType: "json",
   			cache: false,
   			success: function(data)
   			{
   				console.log(data.data);
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
   					  text: 'Đã thêm ghi chú thành công!',
   					  timer: 1000
   					})
   					setTimeout(function(){ location.reload();     }, 1200);
   								
   				}
   			}
   		});
   		return false;
   	});
	$("#btn-save-staff").on("click",function()
   	{
   		var id = $("#oid").val();
   		var content = $("#staff-note-status").val();
		var staff = $("#staff-select option:selected").val();
		var staffname = $("#staff-select option:selected").html();
   		$.ajax({
   			type: "POST",
   			url: "<?php echo XC_URL;?>/api/AssignToStaff",
   			data: {id: id, note: content, staff: staff,staffname:staffname},
   			dataType: "json",
   			cache: false,
   			success: function(data)
   			{
   				console.log(data.data);
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
   		return false;
   	});
   	$("#btn-save-status").on("click",function()
   	{
   		var id = $("#oid").val();
   		var newstatus = $("#status-select option:selected").val();
   		var newstatustext = $("#status-select option:selected").html();
   		var note = $("#note-status").val();
   		$.ajax({
   			type: "POST",
   			url: "<?php echo XC_URL;?>/api/UpdateOrderStatus",
   			data: {id: id, newstatus: newstatus,newstatustext:newstatustext,note,note},
   			dataType: "json",
   			cache: false,
   			success: function(data)
   			{
   				console.log(data.data);
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
   					  text: 'Cập nhật trạng thái thành công!',
   					  timer: 1000
   					})
   					setTimeout(function(){ location.reload();     }, 1200);
   								
   				}
   			}
   		});
   		return false;
   	});
   	$("#btn-save-buy").on("click",function()
   	{
   		var id = $("#oid").val();
   		var itemlist = $("input[name='buyitem[]']:checked").map(function(){return $(this).attr("data-id");}).get();
   		var note = $("#note-buy-status").val();
   		
   		$.ajax({
   			type: "POST",
   			url: "<?php echo XC_URL;?>/api/UpdateOrderItemBuy",
   			data: {id: id, itemlist: itemlist,note,note},
   			dataType: "json",
   			cache: false,
   			success: function(data)
   			{
   				console.log(data.data);
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
   					  text: 'Cập nhật thành công!',
   					  timer: 1000
   					})
   					setTimeout(function(){ location.reload();     }, 1200);
   								
   				}
   			}
   		});
   		return false;
   	});
	$("#btn-save-fix-fee").on("click",function()
   	{
   		var id = $("#oid").val();
   		var type = $("#fix-fee-select option:selected").val();
		var typename = $("#fix-fee-select option:selected").html();
   		var amount = $("#fix-fee-amount").val();
   		$.ajax({
   			type: "POST",
   			url: "<?php echo XC_URL;?>/api/UpdateOrderFixFee",
   			data: {id: id, type: type, amount: amount, typename: typename},
   			dataType: "json",
   			cache: false,
   			success: function(data)
   			{
   				console.log(data.data);
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
   					  text: 'Cập nhật thành công!',
   					  timer: 1000
   					})
   					setTimeout(function(){ location.reload();     }, 1200);
   								
   				}
   			}
   		});
   		return false;
   	});
   	$("#btn-update-buy").on("click",function()
   	{
   		var id = $("#oid").val();
   		$.ajax({
   			type: "POST",
   			url: "<?php echo XC_URL;?>/api/GetItemsofOrder",
   			data: {id: id},
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
   					$("#update-buy-zone").html(data.data);
   					$("#update-buy-modal").modal();
   								
   				}
   			}
   		});
   		return false;
   	});
   	
   	
   });
</script>
<main id="main-container">
<div class="content">
    <div class="block block-rounded">
        <div class="block-content text-center">
            <div class="py-4">
                <h1 class="font-size-lg mb-0">
                    <?php echo $user->user_firstname." ".$user->user_lastname;?>
                </h1>
                <p class="text-muted">
                    <i class="fa fa-award text-<?php echo $user->level_bagget;?> mr-1"></i>
                    Hạng <?php echo $user->level_name;?>
                </p>
            </div>
        </div>
        <div class="block-content bg-body-light text-center">
            <div class="row items-push text-uppercase">
                <div class="col-6 col-md-3">
                    <div class="font-w600 text-dark mb-1">Đơn hàng</div>
                    <a class="link-fx font-size-h3" href="javascript:void(0)"><?php echo number_format($user->countpost,0);?></a>
                </div>
                <div class="col-6 col-md-3">
                    <div class="font-w600 text-dark mb-1">Tổng tiền hàng</div>
                    <a class="link-fx font-size-h3" href="javascript:void(0)"><?php echo number_format($user->sumorder,0);?></a>
                </div>
                <div class="col-6 col-md-3">
                    <div class="font-w600 text-dark mb-1">Điểm tích lũy</div>
                    <a class="link-fx font-size-h3" href="javascript:void(0)"><?php echo number_format($user->countpoint,0);?></a>
                </div>
                <div class="col-6 col-md-3">
                    <div class="font-w600 text-dark mb-1">Giới thiệu</div>
                    <a class="link-fx font-size-h3" href="javascript:void(0)"><?php echo number_format($user->countreferal,0);?></a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">Last Orders</h3>
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
                        <a class="page-link" href="<?php echo XC_URL;?>/admin/agency/<?php echo $user->uid;?>?page=<?php echo $i;?>"><?php echo $i;?></a>
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
    <div class="block block-rounded" style="display: none">
        <div class="block-header block-header-default">
            <h3 class="block-title">Last Transactions</h3>
        </div>
        <div class="block-content">
            <div class="table-responsive">
                <table class="table table-borderless table-striped table-vcenter">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 100px;">ID</th>
                            <th class="d-none d-sm-table-cell text-center">Submitted</th>
                            <th class="d-none d-md-table-cell text-center">Products</th>
                            <th>Status</th>
                            <th class="d-none d-sm-table-cell text-right">Value</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>   
                    <tbody>
                                                <tr>
                            <td class="text-center font-size-sm">
                                <a class="font-w600" href="be_pages_ecom_order.html">
                                    ORD.0625                                </a>
                            </td>
                            <td class="d-none d-sm-table-cell text-center font-size-sm">10/09/2019</td>
                            <td class="d-none d-md-table-cell text-center font-size-sm">
                                <a class="font-w600" href="javascript:void(0)">2</a>
                            </td>
                            <td>
                                <span class="badge badge-success">Delivered</span>
                            </td>
                            <td class="text-right d-none d-sm-table-cell font-size-sm">
                                <strong>$228,00</strong>
                            </td>
                            <td class="text-center font-size-sm">
                                <a class="btn btn-sm btn-alt-secondary" href="be_pages_ecom_product_edit.html">
                                    <i class="fa fa-fw fa-eye"></i>
                                </a>
                                <a class="btn btn-sm btn-alt-secondary" href="javascript:void(0)">
                                    <i class="fa fa-fw fa-times text-danger"></i>
                                </a>
                            </td>
                        </tr>
                                                <tr>
                            <td class="text-center font-size-sm">
                                <a class="font-w600" href="be_pages_ecom_order.html">
                                    ORD.0624                                </a>
                            </td>
                            <td class="d-none d-sm-table-cell text-center font-size-sm">10/09/2019</td>
                            <td class="d-none d-md-table-cell text-center font-size-sm">
                                <a class="font-w600" href="javascript:void(0)">9</a>
                            </td>
                            <td>
                                <span class="badge badge-success">Delivered</span>
                            </td>
                            <td class="text-right d-none d-sm-table-cell font-size-sm">
                                <strong>$133,00</strong>
                            </td>
                            <td class="text-center font-size-sm">
                                <a class="btn btn-sm btn-alt-secondary" href="be_pages_ecom_product_edit.html">
                                    <i class="fa fa-fw fa-eye"></i>
                                </a>
                                <a class="btn btn-sm btn-alt-secondary" href="javascript:void(0)">
                                    <i class="fa fa-fw fa-times text-danger"></i>
                                </a>
                            </td>
                        </tr>
                                                <tr>
                            <td class="text-center font-size-sm">
                                <a class="font-w600" href="be_pages_ecom_order.html">
                                    ORD.0623                                </a>
                            </td>
                            <td class="d-none d-sm-table-cell text-center font-size-sm">27/05/2019</td>
                            <td class="d-none d-md-table-cell text-center font-size-sm">
                                <a class="font-w600" href="javascript:void(0)">4</a>
                            </td>
                            <td>
                                <span class="badge badge-success">Delivered</span>
                            </td>
                            <td class="text-right d-none d-sm-table-cell font-size-sm">
                                <strong>$109,00</strong>
                            </td>
                            <td class="text-center font-size-sm">
                                <a class="btn btn-sm btn-alt-secondary" href="be_pages_ecom_product_edit.html">
                                    <i class="fa fa-fw fa-eye"></i>
                                </a>
                                <a class="btn btn-sm btn-alt-secondary" href="javascript:void(0)">
                                    <i class="fa fa-fw fa-times text-danger"></i>
                                </a>
                            </td>
                        </tr>
                                                <tr>
                            <td class="text-center font-size-sm">
                                <a class="font-w600" href="be_pages_ecom_order.html">
                                    ORD.0622                                </a>
                            </td>
                            <td class="d-none d-sm-table-cell text-center font-size-sm">16/03/2019</td>
                            <td class="d-none d-md-table-cell text-center font-size-sm">
                                <a class="font-w600" href="javascript:void(0)">2</a>
                            </td>
                            <td>
                                <span class="badge badge-success">Delivered</span>
                            </td>
                            <td class="text-right d-none d-sm-table-cell font-size-sm">
                                <strong>$120,00</strong>
                            </td>
                            <td class="text-center font-size-sm">
                                <a class="btn btn-sm btn-alt-secondary" href="be_pages_ecom_product_edit.html">
                                    <i class="fa fa-fw fa-eye"></i>
                                </a>
                                <a class="btn btn-sm btn-alt-secondary" href="javascript:void(0)">
                                    <i class="fa fa-fw fa-times text-danger"></i>
                                </a>
                            </td>
                        </tr>
                                                <tr>
                            <td class="text-center font-size-sm">
                                <a class="font-w600" href="be_pages_ecom_order.html">
                                    ORD.0621                                </a>
                            </td>
                            <td class="d-none d-sm-table-cell text-center font-size-sm">24/10/2019</td>
                            <td class="d-none d-md-table-cell text-center font-size-sm">
                                <a class="font-w600" href="javascript:void(0)">4</a>
                            </td>
                            <td>
                                <span class="badge badge-success">Delivered</span>
                            </td>
                            <td class="text-right d-none d-sm-table-cell font-size-sm">
                                <strong>$92,00</strong>
                            </td>
                            <td class="text-center font-size-sm">
                                <a class="btn btn-sm btn-alt-secondary" href="be_pages_ecom_product_edit.html">
                                    <i class="fa fa-fw fa-eye"></i>
                                </a>
                                <a class="btn btn-sm btn-alt-secondary" href="javascript:void(0)">
                                    <i class="fa fa-fw fa-times text-danger"></i>
                                </a>
                            </td>
                        </tr>
                                            </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">Referred Members</h3>
        </div>
        <div class="block-content">
            <div class="row row-deck">
				<?php
				foreach($refusers as $ref)
				{
				?>
                <div class="col-md-4">
                    <a class="block block-rounded block-bordered block-link-shadow" href="javascript:void(0)">
                        <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                            <div>
                                <div class="font-w600 mb-1"><?php echo $ref->user_firstname." ".$ref->user_lastname;?></div>
                                <div class="font-size-sm text-muted"><?php echo number_format($ref->countreforder,0);?> Đơn hàng</div>
                            </div>
                            <div class="ml-3">
                                <img class="img-avatar" src="<?php echo $template_path;?>/assets/media/avatars/avatar7.jpg" alt="">
    
                            </div>
                        </div>
                    </a>
                </div>
				<?php
				}
				?>
            </div>
        </div>
    </div>
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">Private Notes</h3>
        </div>
        <div class="block-content">
            <p class="alert alert-info font-size-sm">
                <i class="fa fa-fw fa-info mr-1"></i> This note will not be displayed to the customer.
            </p>
            <form action="be_pages_ecom_customer.html" onsubmit="return false;">
                <div class="form-group">
                    <label for="dm-ecom-customer-note">Note</label>
                    <textarea class="form-control" id="dm-ecom-customer-note" name="dm-ecom-customer-note" rows="4" placeholder="Maybe a special request?"></textarea>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-alt-primary">Add Note</button>
                </div>
            </form>
        </div>
    </div>
</div>
    </main>

<div class="modal" id="add-note-modal" tabindex="-1" aria-labelledby="modal-block-large" style="display: none;" aria-hidden="true">
   <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
         <div class="block block-themed block-transparent mb-0">
            <div class="block-header bg-primary-dark">
               <h3 class="block-title">Ghi chú</h3>
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
                        <label for="dm-post-add-title">Nội dung</label>
                        <input type="text" class="form-control" id="note-content" name="note-content" placeholder="Nội dung">
                     </div>
                  </div>
               </div>
            </div>
            <div class="block-content block-content-full text-right bg-light">
               <button type="button" class="btn btn-sm btn-light" data-dismiss="modal">Đóng</button>
               <button type="button" class="btn btn-sm btn-primary" id="btn-save-note">Lưu</button>
            </div>
         </div>
      </div>
   </div>
</div>

<?php include_once "footer.php";?>