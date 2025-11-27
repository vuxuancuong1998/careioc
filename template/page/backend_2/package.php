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
	$('#barcode').keypress(function(event){
			var keycode = (event.keyCode ? event.keyCode : event.which);
			if(keycode == '13'){
				var code = $(this).val();
				var pid = $("#pid").val();
				$.ajax({
					type: "POST",
					url: "<?php echo XC_URL;?>/api/ScanItemForPackage",
					data: {code: code,id:pid},
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
							
							$("#display-item").prepend(data.data);
							var countcurrent = parseFloat($("#countsuccess").html());
							$("#countsuccess").html(countcurrent+1);
							$("#barcode").val("");
							$("#barcode").focus();
						}
					}
				});
				return false;
			}
			
		});
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
                        <label for="dm-post-add-title">Vận đơn nội địa HQ</label>
                        <input type="text" class="form-control" id="note-buy-status" name="note-buy-status" placeholder="Vận đơn nội địa HQ" value="">
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
   <div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2">Thông tin kiện hàng</h1>
            <button type="button" class="btn btn-alt-success my-2" id="btn-update-pack">
                <i class="fa fa-fw fa-plus mr-1"></i> Cập nhật
            </button>
        </div>
   </div>
</div>
   <input type="hidden" id="pid" name="pid" value="<?php echo $pack->pid;?>">
   <div class="content">
      <div class="row row-deck">
         <div class="col-6 col-lg-3">
            <a class="block block-rounded block-link-shadow text-center" href="javascript:void(0)">
               <div class="block-content py-5">
                  <div class="item rounded-lg bg-xeco-lighter mx-auto mb-3">
                     <i class="fa fa-check text-xeco-dark"></i>
                  </div>
                  <p class="font-w600 font-size-sm text-muted text-uppercase mb-0">
                     #<?php echo $pack->pack_code;?>
                  </p>
               </div>
            </a>
         </div>
         <div class="col-6 col-lg-3">
            <a class="block block-rounded block-link-shadow text-center" href="javascript:void(0)">
               <div class="block-content py-5">
                  <div class="item rounded-lg bg-xeco-lighter mx-auto mb-3">
                     <i class="fa <?php echo ($pack->pack_status >= 2)? "fa-check" : "fa-sync fa-spin";?> text-xeco-dark"></i>
                  </div>
                  <p class="font-w600 font-size-sm text-muted text-uppercase mb-0">
                     Đang đóng hàng
                  </p>
               </div>
            </a>
         </div>
         <div class="col-6 col-lg-3">
            <a class="block block-rounded block-link-shadow text-center" href="javascript:void(0)">
               <div class="block-content py-5">
                  <div class="item rounded-lg bg-xsmooth-lighter mx-auto mb-3">
                     <i class="fa <?php echo ($pack->pack_status  >= 5 )? "fa-check" : "fa-sync fa-spin";?> text-xsmooth-dark"></i>
                  </div>
                  <p class="font-w600 font-size-sm text-muted text-uppercase mb-0">
                     Đang vận chuyển
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
                     Đã về kho
                  </p>
               </div>
            </a>
         </div>
      </div>
      
	  <div class="p-3 bg-white rounded push">
        <form action="" method="POST">
            <div class="input-group input-group-lg">
                <input type="text" id="barcode" class="form-control form-control-alt" placeholder="Quét hoặc nhập mã vận đơn">
                <div class="input-group-append">
                    <span class="input-group-text border-0 bg-body">
                        <i class="fa fa-fw fa-barcode"></i>
                    </span>
                </div>
            </div>
        </form>
    </div>
      <div class="block block-rounded">
        <ul class="nav nav-tabs nav-tabs-block js-tabs-enabled" data-toggle="tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" href="">Sản phẩm đã nhập kho</a>
            </li>
        </ul>
        <div class="block-content tab-content overflow-hidden">
            
            <div class="tab-pane fade active show" id="search-customers" role="tabpanel">
                <div class="font-size-h3 font-w600 pt-2 pb-4 mb-4 text-center border-bottom">
                    Có <span class="text-primary font-w700" id="countsuccess"><?php echo $countitem;?></span> sản phẩm trong kiện hàng.
                </div>
                <table class="table table-striped table-borderless table-vcenter">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center" style="width: 70px;"><i class="si si-user"></i></th>
                            <th>Tên sản phẩm</th>
                            <th class="d-none d-sm-table-cell">Đơn hàng</th>
                            <th class="d-none text-center d-lg-table-cell" style="width: 10%;">KL (g)</th>
                            <th class="text-center" style="width: 30%;">Kích thước (cm)</th>
                            <th class="text-center" style="width: 5%;"></th>
                        </tr>
                    </thead>
                    <tbody id="display-item">
					<?php
					foreach($pack_item as $item)
					{
						echo '<tr>
						<td class="text-center">
							<img class="img-avatar img-avatar48" src="'.$item->item_p_image.'" alt="">
						</td>
						<td class="font-w600">
							<a href="javascript:void(0)" class="font-size-sm">'.$item->item_pname.'</a>
						</td>
						<td class="d-none d-sm-table-cell">
							'.$item->order_code.'
						</td>
						<td class="d-none d-lg-table-cell text-center">
							'.number_format($item->item_weight,0).'
						</td>
						<td class="text-center">
							<div class="form-group mb-0 form-row">
						<div class="col-12">
							'.number_format($item->item_lenght,0).' x '.number_format($item->item_width,0).' x '.number_format($item->item_height,0).'
						</td>
						<td><button type="button" class="btn btn-sm btn-danger js-tooltip-enabled" data-toggle="tooltip" title="" data-original-title="Delete">
												<i class="fa fa-times"></i>
											</button></td>
					</tr>';
					
					}
					?>
					
                                                
                                            </tbody>
                </table>
                
            </div>
        </div>
    </div>
      
      <div class="block block-rounded">
         <div class="block-header block-header-default">
            <h3 class="block-title">Nhật ký kiện hàng</h3>
         </div>
         <div class="block-content">
            <table class="table table-borderless table-striped table-vcenter font-size-sm">
               <tbody>
                  <?php
                     foreach($pack_tracking as $update)
                     {
                     ?>
                  <tr>
                     <td class="font-size-base" style="width: 80px;">
                        <span class="badge badge-<?php echo $update->type_label;?>"><?php echo $update->type_name;?></span>
                     </td>
                     <td style="width: 220px;">
                        <span class="font-w600"><?php echo date("H:i:s d/m/Y",strtotime($update->tracking_time));?></span>
                     </td>
                     <td>
                        <a href="javascript:void(0)"><?php echo $update->user_fullname;?></a>
                     </td>
                     <td class="text-<?php echo $update->type_label;?>"><strong><?php echo $update->tracking_value;?></strong></td>
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