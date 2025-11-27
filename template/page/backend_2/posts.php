<?php include_once "header.php";?>
<script>
	//
	$(document).ready(function(){
		$(".btn-approve-post").on("click", function()
		{
			var pid = $(this).attr("data-id");
			$.ajax({
				type: "POST",
				url: "<?php echo XC_URL;?>/api/adminapprovedlisting",
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
						  text: 'Đã duyệt thành công!',
						  timer: 1000
						})
						setTimeout(function(){ window.location= ("<?php echo XC_URL;?>/admin/posts");     }, 1700);
									
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
<main id="main-container">
<div class="content">
    <div class="row row-deck">
        <div class="col-6 col-lg-3">
            <a class="block block-rounded block-link-shadow text-center" href="javascript:void(0)">
                <div class="block-content py-5">
                    <div class="font-size-h3 font-w600 text-success mb-1">0</div>
                    <p class="font-w600 font-size-sm text-success text-uppercase mb-0">
                        Tin đã duyệt
                    </p>
                </div>
            </a>
        </div>
        <div class="col-6 col-lg-3">
            <a class="block block-rounded block-link-shadow text-center" href="javascript:void(0)">
                <div class="block-content py-5">
                    <div class="font-size-h3 font-w600 text-warning	mb-1">0</div>
                    <p class="font-w600 font-size-sm text-warning text-uppercase mb-0">
                        Tin chờ duyệt
                    </p>
                </div>
            </a>
        </div>
        <div class="col-6 col-lg-3">
            <a class="block block-rounded block-link-shadow text-center" href="be_pages_ecom_dashboard.html">
                <div class="block-content py-5">
                    <div class="font-size-h3 font-w600 text-danger  mb-1">0</div>
                    <p class="font-w600 font-size-sm text-danger text-uppercase mb-0">
                        Từ chối
                    </p>
                </div>
            </a>
        </div>
        <div class="col-6 col-lg-3">
            <a class="block block-rounded block-link-shadow text-center" href="#">
                <div class="block-content py-5">
                    <div class="font-size-h3 font-w600 text-dark mb-1"><?php echo $totalpost;?></div>
                    <p class="font-w600 font-size-sm text-muted text-uppercase mb-0">
                        Tất cả tin
                    </p>
                </div>
            </a>
        </div>
    </div>
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">Danh sách tin đăng</h3>
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
                    <input type="text" class="form-control form-control-alt" id="dm-ecom-products-search" name="dm-ecom-products-search" placeholder="Tìm kiếm bằng mã tin, người đăng">
                </div>
            </form>
        </div>
        <div class="block-content">
            <div class="table-responsive">
                <table class="table table-borderless table-striped table-vcenter">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 100px;">Mã</th>
							<th class="d-none d-sm-table-cell text-center">Loại</th>
                            <th class="d-none d-sm-table-cell text-center">Thời gian</th>
                            <th class="d-none d-md-table-cell">Người đăng</th>
                            <th class="d-none d-sm-table-cell text-center">Danh mục</th>
                            <th class="d-none d-sm-table-cell text-right">Giá</th>
							<th>Trạng thái</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                    </thead>   
                    <tbody>
						<?php
						foreach($posts as $post)
						{
						?>
						<tr>
                            <td class="text-center font-size-sm">
                                <a class="font-w600" target="_blank" href="<?php echo $this->url->permalink($post->pid,"post");?>">
                                    <strong><?php echo $post->post_code;?></strong>
                                </a>
                            </td>
							<td class="d-none d-sm-table-cell text-center">
								<?php if($post->post_type == 1)
								{
								?>
								<span class="badge badge-success">Bán</span>
								<?php
								}
								else
								{
									?>
								<div class="badge badge-info">Cho thuê</div>
									<?php
								}
								?>
                                
                            </td>
                            <td class="d-none d-sm-table-cell text-center font-size-sm"><?php echo date("d/m/Y",strtotime($post->post_create_time));?></td>
                            <td class="d-none d-md-table-cell font-size-sm">
                                <a class="font-w600" href="<?php echo XC_URL;?>"><?php echo $post->user_fullname;?></a>
                            </td>
                            <td class="text-center d-none d-sm-table-cell font-size-sm">
                                <strong><?php echo $post->cat_name;?></strong>
                            </td>
							<td class="text-right d-none d-sm-table-cell font-size-sm">
                                <strong><?php echo number_format($post->post_price,0);?></strong>
                            </td>
							<td>
								<?php if($post->post_status == 1)
								{
								?>
									<span class="badge badge-success">Đã duyệt</span>
								<?php
								}
								elseif($post->post_status == 2)
								{
									?>
								<div class="badge badge-danger">Từ chối</div>
									<?php
								}
								else
								{
									?>
								<div class="badge badge-warning">Chờ duyệt</div>
									<?php
								}
								?>
                                
                            </td>
                            <td class="text-center font-size-sm">
                                <a class="btn btn-sm btn-alt-secondary" target="_blank" href="<?php echo $this->url->permalink($post->pid,"post");?>">
                                    <i class="fa fa-fw fa-eye"></i>
                                </a>
								<a class="btn btn-sm btn-alt-secondary" target="_blank" href="<?php echo XC_URL."/sua-tin.html?id=".$post->pid;?>">
                                    <i class="fa fa-fw fa-pen"></i>
                                </a>
								
                                <a class="btn btn-sm btn-alt-secondary btn-approve-post" data-id="<?php echo $post->pid;?>" href="javascript:void(0)">
                                    <i class="fa fa-fw fa-check text-success"></i>
                                </a>
                                <a class="btn btn-sm btn-alt-secondary btn-delete-post" data-id="<?php echo $post->pid;?>" href="javascript:void(0)">
                                    <i class="fa fa-fw fa-times text-danger"></i>
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