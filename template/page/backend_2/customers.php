<?php include_once "header.php";?>
 <link rel="stylesheet" href="<?php echo $template_path;?>/assets/js/plugins/datatables/dataTables.bootstrap4.css">
<link rel="stylesheet" href="<?php echo $template_path;?>/assets/js/plugins/datatables/buttons-bs4/buttons.bootstrap4.min.css">
<script src="<?php echo $template_path;?>/assets/js/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo $template_path;?>/assets/js/plugins/datatables/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo $template_path;?>/assets/js/plugins/datatables/buttons/dataTables.buttons.min.js"></script>
<script src="<?php echo $template_path;?>/assets/js/plugins/datatables/buttons/buttons.print.min.js"></script>
<script src="<?php echo $template_path;?>/assets/js/plugins/datatables/buttons/buttons.html5.min.js"></script>
<script src="<?php echo $template_path;?>/assets/js/plugins/datatables/buttons/buttons.flash.min.js"></script>
<script src="<?php echo $template_path;?>/assets/js/plugins/datatables/buttons/buttons.colVis.min.js"></script>
<script>
    $(document).ready(function(){
        jQuery(".js-dataTable-full-pagination").dataTable({
			pagingType: "full_numbers",
			pageLength: 20,
			lengthMenu: [
				[5, 10, 20],
				[5, 10, 20]
			],
			autoWidth: !1
		});
		$("#btn-add-user").on("click", function()
		{
			
			$("#group-select").val(4);
			$("#user-add-email").val("");
			$("#user-add-password").val("");
			$("#user-add-name").val("");
			$("#updatetype").val("new");
			$("#uid").val("");
			$("#add-user-modal").modal();
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
						url: "<?php echo XC_URL;?>/api/deleteuser",
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
								  timer: 1700
								})
								setTimeout(function(){ location.reload();     }, 2000);
											
							}
						}
					});
					
			  }
			})
			
		});
		
		//
		$("#btn-save-staff").on("click", function()
		{
			var cid = $("#cid").val();
			var staff = $("#staff-select").val();
			var note = $("#staff-note-status").val();
			$.ajax({
				type: "POST",
				url: "<?php echo XC_URL;?>/api/AssignCustomerToStaff",
				data: {cid:cid,staff:staff,note:note},
				dataType: "json",
				cache: false,
				success: function(data)
				{
					console.log(data);
					if(data.status == 200)
					{
						
						Swal.fire({
								  icon: 'success',
								  title: 'Thành công',
								  text: 'Đã thực hiện thành công!',
								  timer: 1700
								})
								//setTimeout(function(){ location.reload();     }, 2000);
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
			
		});
		$(".btn-assign-customer").on("click", function()
		{
			var button = $(this);
			Swal.fire({
			  title: 'Bạn có chắc chắn?',
			  text: "Thao tác này sẽ chuyển Khách hàng và toàn bộ đơn hàng của Khách hàng này cho nhân viên mới. Không thể khôi phục!",
			  icon: 'warning',
			  showCancelButton: true,
			  confirmButtonColor: '#3085d6',
			  cancelButtonColor: '#d33',
			  confirmButtonText: 'Đồng ý',
			  cancelButtonText: 'Hủy',
			}).then((result) => {
			  if (result.isConfirmed) {
				  $("#cid").val(button.attr("data-id"));
				  $("#assign-staff-modal").modal();
			  }
			})
			
		});
		$("#btn-save").on("click",function()
		{
			var id = $("#uid").val();
			var updatetype = $("#updatetype").val();
			var email = $("#user-add-email").val();
			var password = $("#user-add-password").val();
			var name = $("#user-add-name").val();
			var group = $("#group-select").val();

			fd = new FormData();
			fd.append('uid', id);
			fd.append('email', email);
			fd.append('password', password);
			fd.append('name', name);
			fd.append('group', group);
			fd.append('updatetype', updatetype);
			$.ajax({
				type: "POST",
				url: "<?php echo XC_URL;?>/api/adduser",
				data: fd,
				dataType: "json",
				cache: false,
				processData: false,  
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
						
					}
				}
			});
			return false;
		});

    })
    </script>
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
               <input type="hidden" id="cid" value="">
               <button type="button" class="btn btn-sm btn-light" data-dismiss="modal">Đóng</button>
               <button type="button" class="btn btn-sm btn-primary" id="btn-save-staff">Lưu</button>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="modal" id="add-user-modal" tabindex="-1" aria-labelledby="modal-block-large" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Thêm Khách hàng</h3>
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
                            <label for="dm-post-add-title">Email</label>
                            <input type="email" class="form-control" id="user-add-email" name="user-add-email" placeholder="Email">
                        </div>
						<div class="form-group">
                            <label for="dm-post-add-title">Mật khẩu</label>
                            <input type="password" class="form-control" id="user-add-password" name="user-add-password" placeholder="Password">
                        </div>
						<div class="form-group">
                            <label for="user-add-name">Họ</label>
                            <input type="text" class="form-control" id="user-add-name" name="user-add-name" placeholder="Họ">
                        </div>
						<div class="form-group">
                            <label for="user-add-name">tên</label>
                            <input type="text" class="form-control" id="user-add-lastname" name="user-add-lastname" placeholder="tên">
                        </div>
						<div class="form-group">
                            <label for="group-select">Nhóm</label>
                            <select class="form-control" id="group-select" name="group-select">
								<option value="4">Thường</option>
								<option value="5">VIP</option>
                            </select>
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
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2">Danh sách tài khoản</h1>
            
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
                        <th>Họ và tên</th>
                        <th class="d-none d-sm-table-cell" style="width: 20%;">Email</th>
                        <th class="d-none d-sm-table-cell" style="width: 10%;">Nhân viên</th>
                        <th style="width: 15%;">Số đơn hàng</th>
                        <th style="width: 15%;">Số dư</th>
                        <th style="width: 15%;">Ngày đăng ký</th>
                        <th style="width: 12%;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
				<?php
					$i = 1;
					foreach($users as $user)
					{
					?>
					<tr>
                        <td class="text-center"><?php echo $i;?></td>
                        <td class="font-w600"><?php echo $user->user_firstname." ".$user->user_lastname;?></td>
                        <td class="d-none d-sm-table-cell">
                            <?php echo $user->uemail;?>
                        </td>
                        <td class="d-none d-sm-table-cell">
							<span class="badge badge-primary"><?php echo $user->user_fullname;?></span>
                        </td>
						<td><?php echo number_format($user->countpost,0);?></td>
						<td><?php echo number_format($user->user_available_wallet,0);?></td>
                        <td>
                            <em class="text-muted"><?php echo $this->helper->time_ago($user->user_created_time);?></em>
                        </td>
						<td class="text-center">
                            <div class="btn-group">
                                <a href="<?php echo XC_URL;?>/admin/customers/<?php echo $user->uid;?>" class="btn btn-sm btn-primary js-tooltip-enabled btn-edit-user"  data-toggle="tooltip" title="" data-original-title="Edit">
                                    <i class="fa fa-eye"></i>
                                </a>
								<a href="#" data-id="<?php echo $user->uid;?>" class="btn btn-sm btn-primary js-tooltip-enabled btn-assign-customer"  data-toggle="tooltip" title="" data-original-title="Edit">
                                    <i class="fa fa-user-circle"></i>
                                </a>
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
<?php include_once "footer.php";?>