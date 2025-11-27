<?php include_once "header.php";?>
 <link rel="stylesheet" href="<?php echo $template_path;?>/assets/js/plugins/datatables/dataTables.bootstrap4.css">
<link rel="stylesheet" href="<?php echo $template_path;?>/assets/js/plugins/datatables/buttons-bs4/buttons.bootstrap4.min.css">

<script src="<?php echo $template_path;?>/assets/js/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo $template_path;?>/assets/js/plugins/datatables/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo $template_path;?>/assets/js/plugins/datatables/buttons/dataTables.buttons.min.js"></script>
<script src="<?php echo $template_path;?>/assets/js/plugins/datatables/buttons/buttons.print.min.js"></script>
<script src="<?php echo $template_path;?>/assets/js/plugins/datatables/buttons/buttons.html5.min.js"></script>
<script src="<?php echo $template_path;?>/assets/js/plugins/select2/js/select2.full.min.js"></script>
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
		$(".js-select2").select2();
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
		$(".btn-approve").on("click", function()
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
				var tid = $(this).attr("data-id");
				$.ajax({
					type: "POST",
					url: "<?php echo XC_URL;?>/api/approvetransaction",
					data: {id: tid},
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
							  text: 'Đã duyệt thành công!',
							  timer: 1000
							})
							setTimeout(function(){ window.location= ("<?php echo XC_URL;?>/admin/transactions");     }, 1700);
										
						}
					}
				});
			  }})
			  
		});
		$(".btn-denine").on("click", function()
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
				var tid = $(this).attr("data-id");
				$.ajax({
					type: "POST",
					url: "<?php echo XC_URL;?>/api/deninetransaction",
					data: {id: tid},
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
							  text: 'Đã xử lý thành công!',
							  timer: 1000
							})
							setTimeout(function(){ window.location= ("<?php echo XC_URL;?>/admin/transactions");     }, 1700);
										
						}
					}
				});
			  }})
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
		$(".btn-edit-user").on("click", function()
		{
			var id = $(this).attr("data-id");
			$.ajax({
				type: "POST",
				url: "<?php echo XC_URL;?>/api/getuser",
				data: {id:id},
				dataType: "json",
				cache: false,
				success: function(data)
				{
					
					if(data.status == 200)
					{
						console.log(data);
						$("#user-add-email").val(data.email);
						$("#user-add-name").val(data.name);
						$("#user-add-password").val("");
						$("#group-select").val(data.group);
						$("#updatetype").val("edit");
						$("#uid").val(id);
						$("#add-user-modal").modal();
					}
					else
					{
						
					}
				}
			});
			
		});
		$("#btn-save").on("click",function()
		{
			var uid = $("#deposite_uid option:selected").val();
			var amount = $("#deposite_amount").val();
			var hash = $("#hash").val();
			var code = $("#deposite_code").val();
			var note = $("#deposite_note").val();
			if(amount == 0)
			{
				Swal.fire({
				  icon: 'error',
				  title: 'Oops...',
				  text: "Amount must be more than 0",
				  footer: '<a href>Xem thêm về lỗi này?</a>'
				})
			
			}
			else if(code == "" || note == "")
			{
				Swal.fire({
				  icon: 'error',
				  title: 'Oops...',
				  text: "Please fill all required field",
				  footer: '<a href>Xem thêm về lỗi này?</a>'
				})
			}
			else
			{
				$.ajax({
					type: "POST",
					url: "<?php echo XC_URL;?>/api/DepositeCustomer",
					data: {uid:uid,amount:amount,hash:hash,code:code,note:note},
					dataType: "json",
					cache: false,
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
			}
			
			return false;
		});

    })
    </script>
<div class="modal" id="add-user-modal" tabindex="-1" aria-labelledby="modal-block-large" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Manual Deposite</h3>
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
                            <label for="deposite_uid">Customer</label>
                            <select class="js-select2 form-control" id="deposite_uid" name="deposite_amount" style="width: 100%;" data-placeholder="Select Customer...">
                                <?php 
								foreach($users as $user)
								{
								?>
                                <option value="<?php echo $user->id;?>"><?php echo $user->user_firstname." ".$user->user_lastname;?></option>
								<?php
								}
								?>
                            </select>
                        </div>
						<div class="form-group">
                            <label for="dm-post-add-title">Amount</label>
                            <input type="number" class="form-control" id="deposite_amount" name="deposite_amount" placeholder="0">
                        </div>
						<div class="form-group">
                            <label for="user-add-name">Transaction Code</label>
                            <input type="text" class="form-control" id="deposite_code" name="deposite_code" placeholder="Transaction Code">
                        </div>
						<div class="form-group">
                            <label for="deposite_note">Transaction Note</label>
                            <input type="text" class="form-control" id="deposite_note" name="deposite_note" placeholder="Note for transaction">
                        </div>
						
                    </div>
                </div>
                </div>
                <div class="block-content block-content-full text-right bg-light">
					<input type="hidden" name="hash" id="hash" value="<?php echo bin2hex(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM));?>">
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
            <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2">Danh sách giao dịch</h1>
            <button type="button" class="btn btn-alt-success my-2" id="btn-add-user">
                <i class="fa fa-fw fa-plus mr-1"></i> Thêm giao dịch
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
                        <th>Khách hàng</th>
                        <th class="d-none d-sm-table-cell" style="width: 10%;">Mã giao dịch</th>
                        <th class="d-none d-sm-table-cell" style="width: 20%;">Loại</th>
                        <th style="width: 10%;">Nội dung</th>
                        <th style="width: 10%;">Số tiền</th>
                        <th style="width: 15%;">Thời gian</th>
                        <th style="width: 10%;">Trạng thái</th>
                        <th style="width: 10%;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
				<?php
					$i = 1;
					foreach($transactions as $trans)
					{
					?>
					<tr>
                        <td class="text-center"><?php echo $i;?></td>
                        <td class="font-w600"><?php echo $trans->user_firstname." ".$trans->user_lastname;?></td>
                        <td class="d-none d-sm-table-cell">
                            <?php echo $trans->trans_code;?>
                        </td>
                        <td class="d-none d-sm-table-cell">
							<?php 
							if($trans->trans_type == 1)
							{
								echo "Nạp tiền";
							}
							elseif($trans->trans_type == 2)
							{
								echo "Đặt cọc đơn hàng";
							}
							?>
                        </td>
						<td><?php echo $trans->trans_data;?></td>
						<td><?php echo number_format($trans->trans_amount,0);?></td>
						<td><em class="text-muted"><?php echo $this->helper->time_ago($trans->trans_time);?></em></td>
						<td class="d-none d-sm-table-cell">
							<?php 
							if($trans->trans_status == 1)
							{
								echo '<span class="badge badge-warning">Chờ duyệt</span>';
							}
							elseif($trans->trans_status == 2)
							{
								echo '<span class="badge badge-success">Đã duyệt</span>';
							}
							elseif($trans->trans_status == 3)
							{
								echo '<span class="badge badge-danger">Từ chối</span>';
							}
							?>
                        </td>
						<td class="text-center">
                            <div class="btn-group">
								<a target="_blank" href="https://khachhang.mmexpress.vn/invoice/bill/<?php echo $trans->trans_code;?>" type="button" class="btn btn-sm btn-primary js-tooltip-enabled btn-print" data-id="<?php echo $trans->tid;?>" data-toggle="tooltip" title="" data-original-title="In">
                                    <i class="fa fa-print"></i>
                                </a>
								<?php
								if($trans->trans_status == 1)
								{
								?>
                                
								<button type="button" class="btn btn-sm btn-primary js-tooltip-enabled btn-approve" data-id="<?php echo $trans->tid;?>" data-toggle="tooltip" title="" data-original-title="Duyệt">
                                    <i class="fa fa-check-square"></i>
                                </button>
								<button type="button" class="btn btn-sm btn-primary js-tooltip-enabled btn-denine" data-toggle="tooltip" title="" data-original-title="Từ chối" data-id="<?php echo $trans->tid;?>" >
                                    <i class="fa fa-times"></i>
                                </button>
								<?php
								}
								?>
                                
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