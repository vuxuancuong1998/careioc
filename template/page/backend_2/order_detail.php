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
<div class="modal" id="update-status-modal" tabindex="-1" aria-labelledby="modal-block-large" style="display: none;" aria-hidden="true">
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
                     <div id="update-zone">
                        <div class="form-group">
                           <label for="dm-post-add-title">Trạng thái hiện tại</label>
                           <input type="text" readonly class="form-control" id="current-status" name="current-status" placeholder="" value="<?php echo $order->status_name;?>">
                        </div>
                        <div class="form-group">
                           <label for="group-select">Trạng thái mới</label>
                           <select class="form-control" id="status-select" name="status-select">
                              <?php 
                                 $status = general::getInstance()->get_status_list();
                                 foreach($status as $t)
                                 {
                                 ?>
                              <option value="<?php echo $t->id;?>"><?php echo $t->status_name;?></option>
                              <?php 
                                 }
                                 ?>
                           </select>
                        </div>
                        <div class="form-group">
                           <label for="dm-post-add-title">Ghi chú</label>
                           <input type="text" class="form-control" id="note-status" name="note-status" placeholder="Ghi chú cho cập nhật này" value="">
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="block-content block-content-full text-right bg-light">
               <input type="hidden" id="updatetype" value="new">
               <input type="hidden" id="uid" value="">
               <button type="button" class="btn btn-sm btn-light" data-dismiss="modal">Đóng</button>
               <button type="button" class="btn btn-sm btn-primary" id="btn-save-status">Lưu</button>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="modal" id="update-buy-modal" tabindex="-1" aria-labelledby="modal-block-large" style="display: none;" aria-hidden="true">
   <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
         <div class="block block-themed block-transparent mb-0">
            <div class="block-header bg-primary-dark">
               <h3 class="block-title">Cập nhật mua hàng</h3>
               <div class="block-options">
                  <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                  <i class="fa fa-fw fa-times"></i>
                  </button>
               </div>
            </div>
            <div class="block-content">
               <div class="row justify-content-center push">
                  <div class="col-md-12">
                     <div id="update-buy-zone" class="form-group row items-push mb-0">
                     </div>
                     <div class="form-group">
                        <label for="dm-post-add-title">Vận đơn nội địa TQ</label>
                        <input type="text" class="form-control" id="note-buy-status" name="note-buy-status" placeholder="Vận đơn nội địa TQ" value="">
                     </div>
                  </div>
               </div>
            </div>
            <div class="block-content block-content-full text-right bg-light">
               <input type="hidden" id="updatetype" value="new">
               <input type="hidden" id="uid" value="">
               <button type="button" class="btn btn-sm btn-light" data-dismiss="modal">Đóng</button>
               <button type="button" class="btn btn-sm btn-primary" id="btn-save-buy">Lưu</button>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="modal" id="updaet-fix-fee-modal" tabindex="-1" aria-labelledby="modal-block-large" style="display: none;" aria-hidden="true">
   <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
         <div class="block block-themed block-transparent mb-0">
            <div class="block-header bg-primary-dark">
               <h3 class="block-title">Cập nhật phí cố định</h3>
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
					   <label for="group-select">Loại phí cố định</label>
					   <select class="form-control" id="fix-fee-select" name="fee-select">
							<option value="order_buy_fee">Phí mua hàng</option>
							<option value="order_service_fee">Phí cố định</option>
							<option value="order_kr_ship_fee">Phí vận chuyển nội địa</option>
							<option value="order_global_ship_fee">Phí vận chuyển quốc tế</option>
							<option value="order_vn_ship_fee">Phí giao hàng VN</option>
							<option value="order_check_fee">Phí kiểm đếm</option>
							<option value="order_box_fee">Phí đóng thùng</option>
					   </select>
					</div>
                     <div class="form-group">
                        <label for="dm-post-add-title">Số tiền phí</label>
                        <input type="number" class="form-control" id="fix-fee-amount" name="fix-fee-amount" placeholder="Nhập số tiền bằng số">
                     </div>
                  </div>
               </div>
            </div>
            <div class="block-content block-content-full text-right bg-light">
               <input type="hidden" id="updatetype" value="new">
               <input type="hidden" id="id" value="">
               <button type="button" class="btn btn-sm btn-light" data-dismiss="modal">Đóng</button>
               <button type="button" class="btn btn-sm btn-primary" id="btn-save-fix-fee">Lưu</button>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="modal" id="assign-staff-modal" tabindex="-1" aria-labelledby="modal-block-large" style="display: none;" aria-hidden="true">
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
					   <label for="group-select">Chọn nhân viên</label>
					   <select class="form-control" id="staff-select" name="staff-select">
							<?php
							foreach($staff as $s)
							{
							?>
								<option value="<?php echo $s->id;?>"><?php echo $s->user_fullname;?></option>
							<?php
							}
							?>
					   </select>
					</div>
                    <div class="form-group">
                           <label for="dm-post-add-title">Ghi chú</label>
                           <input type="text" class="form-control" id="staff-note-status" name="staff-note-status" placeholder="Ghi chú cho cập nhật này" value="">
                        </div>
                  </div>
               </div>
            </div>
            <div class="block-content block-content-full text-right bg-light">
               <input type="hidden" id="updatetype" value="new">
               <input type="hidden" id="id" value="">
               <button type="button" class="btn btn-sm btn-light" data-dismiss="modal">Đóng</button>
               <button type="button" class="btn btn-sm btn-primary" id="btn-save-staff">Lưu</button>
            </div>
         </div>
      </div>
   </div>
</div>
<main id="main-container">
   <input type="hidden" id="oid" name="oid" value="<?php echo $order->oid;?>">
   <div class="content">
      <div class="row row-deck">
         <div class="col-6 col-lg-3">
            <a class="block block-rounded block-link-shadow text-center" href="javascript:void(0)">
               <div class="block-content py-5">
                  <div class="item rounded-lg bg-xeco-lighter mx-auto mb-3">
                     <i class="fa fa-check text-xeco-dark"></i>
                  </div>
                  <p class="font-w600 font-size-sm text-muted text-uppercase mb-0">
                     #<?php echo $order->order_code;?>
                  </p>
               </div>
            </a>
         </div>
         <div class="col-6 col-lg-3">
            <a class="block block-rounded block-link-shadow text-center" href="javascript:void(0)">
               <div class="block-content py-5">
                  <div class="item rounded-lg bg-xeco-lighter mx-auto mb-3">
                     <i class="fa <?php echo ($order->order_status >= 2)? "fa-check" : "fa-sync fa-spin";?> text-xeco-dark"></i>
                  </div>
                  <p class="font-w600 font-size-sm text-muted text-uppercase mb-0">
                     Chờ thanh toán
                  </p>
               </div>
            </a>
         </div>
         <div class="col-6 col-lg-3">
            <a class="block block-rounded block-link-shadow text-center" href="javascript:void(0)">
               <div class="block-content py-5">
                  <div class="item rounded-lg bg-xsmooth-lighter mx-auto mb-3">
                     <i class="fa <?php echo ($order->order_status  >= 5 )? "fa-check" : "fa-sync fa-spin";?> text-xsmooth-dark"></i>
                  </div>
                  <p class="font-w600 font-size-sm text-muted text-uppercase mb-0">
                     Mua hàng
                  </p>
               </div>
            </a>
         </div>
         <div class="col-6 col-lg-3">
            <a class="block block-rounded block-link-shadow text-center" href="javascript:void(0)">
               <div class="block-content py-5">
                  <div class="item rounded-lg bg-body mx-auto mb-3">
                     <i class="fa fa-times text-muted"></i>
                  </div>
                  <p class="font-w600 font-size-sm text-muted text-uppercase mb-0">
                     Giao hàng
                  </p>
               </div>
            </a>
         </div>
      </div>
      <div class="bg-white p-3 rounded push">
         <div class="d-lg-none">
            <button type="button" class="btn btn-block btn-light d-flex justify-content-between align-items-center" data-toggle="class-toggle" data-target="#horizontal-navigation-hover-normal" data-class="d-none">
            Menu - Hover Normal
            <i class="fa fa-bars"></i>
            </button>
         </div>
         <div id="horizontal-navigation-hover-normal" class="d-none d-lg-block mt-2 mt-lg-0">
            <ul class="nav-main nav-main-horizontal nav-main-hover">
               <li class="nav-main-item">
                  <a class="nav-main-link active" href="">
                  <i class="nav-main-link-icon fa fa-rocket"></i>
                  <span class="nav-main-link-name">Tổng quan</span>
                  </a>
               </li>
               <li class="nav-main-heading">Manage</li>
               <li class="nav-main-item">
                  <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                  <i class="nav-main-link-icon fa fa-boxes"></i>
                  <span class="nav-main-link-name">Đơn hàng</span>
                  </a>
                  <ul class="nav-main-submenu">
                     <li class="nav-main-item">
                        <a class="nav-main-link" id="btn-update-status" href="#">
                        <i class="nav-main-link-icon fa fa-pencil-alt"></i>
                        <span class="nav-main-link-name">Cập nhật trạng thái</span>
                        </a>
                     </li>
                     <li class="nav-main-item">
                        <a class="nav-main-link" id="btn-update-buy" href="javascript:void(0)">
                        <i class="nav-main-link-icon fa fa-plus-circle"></i>
                        <span class="nav-main-link-name">Cập nhật mua hàng</span>
                        </a>
                     </li>
                  </ul>
               </li>
               <li class="nav-main-item">
                  <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                  <i class="nav-main-link-icon fa fa-money-bill"></i>
                  <span class="nav-main-link-name">Thanh toán</span>
                  </a>
                  <ul class="nav-main-submenu">
                     <li class="nav-main-item">
                        <a class="nav-main-link" id="btn-update-fix-fee" href="javascript:void(0)">
                        <i class="nav-main-link-icon fa fa-plus-circle"></i>
                        <span class="nav-main-link-name">Cập nhật phí cố định</span>
                        </a>
                     </li>
					 <li class="nav-main-item">
                        <a class="nav-main-link" href="javascript:void(0)">
                        <i class="nav-main-link-icon fa fa-plus-circle"></i>
                        <span class="nav-main-link-name">Thêm phí khác</span>
                        </a>
                     </li>
                  </ul>
               </li>
               <li class="nav-main-heading">Personal</li>
               <li class="nav-main-item">
                  <a class="nav-main-link" id="add-note" href="javascript:void(0)">
                  <i class="nav-main-link-icon fa fa-plus-circle"></i>
                  <span class="nav-main-link-name">Ghi chú</span>
                  </a>
               </li>
			   <li class="nav-main-item">
                  <a class="nav-main-link" id="btn-assign" href="javascript:void(0)">
                  <i class="nav-main-link-icon fa fa-check"></i>
                  <span class="nav-main-link-name">Nhân viên</span>
                  </a>
               </li>
            </ul>
         </div>
      </div>
      <div class="block block-rounded">
         <div class="block-header block-header-default">
            <h3 class="block-title">Sản phẩm</h3>
         </div>
         <div class="block-content">
            <div class="table-responsive">
               <table class="table table-borderless table-striped table-vcenter font-size-sm">
                  <thead>
                     <tr>
                        <th class="text-center" style="width: 100px;">ID</th>
                        <th>Tên sản phẩm</th>
                        <th class="text-center">SL</th>
                        <th class="text-right" style="width: 10%;">Đơn giá</th>
                        <th class="text-right" style="width: 10%;">Price</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php 
                        $total = 0;
                        foreach($items as $item)
                        {
                        	?>
                     <tr>
                        <td class="text-center"><a href=""><strong><?php echo $item->item_pid;?></strong></a></td>
                        <td><a href="<?php echo $item->item_p_link;?>"><strong><?php echo $item->item_pname;?> 
						<br>
						Size: <?php echo $item->item_p_size;?> | Color: <?php echo $item->item_p_color;?> | Note: <?php echo $item->item_note;?> <?php echo ($item->item_buy == 1)? "| Đã mua hàng ngày: ".date("d-m-Y",strtotime($item->item_buy_date)) : "";?></strong></a></td>
                        <td class="text-center"><strong><?php echo $item->item_qty;?></strong></td>
                        <td class="text-right">¥<?php echo number_format($item->item_price,0);?></td>
                        <td class="text-right">¥<?php echo number_format($item->item_price*$item->item_qty,0);?></td>
                     </tr>
                     <?php
                        $total += $item->item_price*$item->item_qty;
                        }
                        ?>
                     <tr>
                        <td colspan="4" class="text-right"><strong>Tổng tiền hàng (¥):</strong></td>
                        <td class="text-right"><?php echo number_format($total,0);?><br>
                        </td>
                     </tr>
                     <tr>
                        <td colspan="4" class="text-right"><strong>Tổng cộng (VNĐ):</strong></td>
                        <td class="text-right">
                           <?php echo number_format($total*$wonrate,0);?>
                        </td>
                     </tr>
					 <tr>
                        <td colspan="4" class="text-right"><strong>Tổng phí (VNĐ):</strong></td>
                        <td class="text-right">
                           <?php echo number_format($this->shop->get_order_fees($order->oid),0);?>
                        </td>
                     </tr>
                     <tr>
                        <td colspan="4" class="text-right"><strong>Đã thanh toán:</strong></td>
                        <td class="text-right"><?php echo number_format($order->order_deposit,0);?></td>
                     </tr>
                     <tr class="table-active">
                        <td colspan="4" class="text-right text-uppercase"><strong>Còn lại:</strong></td>
                        <td class="text-right"><strong><?php echo number_format($total*$wonrate + $this->shop->get_order_fees($order->oid) - $order->order_deposit,0);?></strong></td>
                     </tr>
                  </tbody>
               </table>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-sm-6">
            <div class="block block-rounded">
               <div class="block-header block-header-default">
                  <h3 class="block-title">Người mua hàng</h3>
               </div>
               <div class="block-content">
                  <div class="font-size-h4 mb-1"><?php echo $order_user->user_firstname." ".$order_user->user_lastname;?></div>
                  <address class="font-size-sm">
                     <?php echo $order_user->address;?><br>
                     <?php echo $order_user->ward_name;?> - <?php echo $order_user->district_name;?><br>
                     <?php echo $order_user->province_name;?><br><br>
                     <i class="fa fa-phone"></i> <?php echo $order_user->user_phone;?><br>
                     <i class="fa fa-envelope-o"></i> <a href="javascript:void(0)"><?php echo $order_user->user_email;?></a>
                  </address>
               </div>
            </div>
         </div>
         <div class="col-sm-6">
            <div class="block block-rounded">
               <div class="block-header block-header-default">
                  <h3 class="block-title">Giao hàng</h3>
               </div>
               <div class="block-content">
                  <div class="font-size-h4 mb-1"><?php echo $order_user->user_firstname." ".$order_user->user_lastname;?></div>
                  <address class="font-size-sm">
                     <?php echo $order_user->address;?><br>
                     <?php echo $order_user->ward_name;?> - <?php echo $order_user->district_name;?><br>
                     <?php echo $order_user->province_name;?><br><br>
                     <i class="fa fa-phone"></i> <?php echo $order_user->user_phone;?><br>
                     <i class="fa fa-envelope-o"></i> <a href="javascript:void(0)"><?php echo $order_user->user_email;?></a>
                  </address>
               </div>
            </div>
         </div>
      </div>
      <div class="block block-rounded">
         <div class="block-header block-header-default">
            <h3 class="block-title">Nhật ký cập nhật</h3>
         </div>
         <div class="block-content">
            <table class="table table-borderless table-striped table-vcenter font-size-sm">
               <tbody>
                  <?php
                     foreach($updates as $update)
                     {
                     ?>
                  <tr>
                     <td class="font-size-base" style="width: 80px;">
                        <span class="badge badge-<?php echo $update->type_label;?>"><?php echo $update->type_name;?></span>
                     </td>
                     <td style="width: 220px;">
                        <span class="font-w600"><?php echo date("H:i:s d/m/Y",strtotime($update->update_time));?></span>
                     </td>
                     <td>
                        <a href="javascript:void(0)"><?php echo $update->user_fullname;?></a>
                     </td>
                     <td class="text-<?php echo $update->type_label;?>"><strong><?php echo $update->update_value;?></strong></td>
                  </tr>
                  <?php
                     }
                     ?>
               </tbody>
            </table>
         </div>
      </div>
      <div class="block block-rounded">
         <div class="block-header block-header-default">
            <h3 class="block-title">Ghi chú đơn hàng</h3>
         </div>
         <div class="block-content">
            <table class="table table-borderless table-striped table-vcenter font-size-sm">
               <tbody>
                  <?php
                     foreach($notes as $note)
                     {
                     ?>
                  <tr>
                     <td style="width: 20%;">
                        <span class="font-w600"><?php echo date("H:i:s d/m/Y",strtotime($note->note_time));?></span>
                     </td>
                     <td style="width: 15%;">
                        <a href="javascript:void(0)"><?php echo $note->user_fullname;?></a>
                     </td>
                     <td class=""><strong><?php echo $note->note_text;?></strong></td>
                  </tr>
                  <?php
                     }
                     ?>
               </tbody>
            </table>
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