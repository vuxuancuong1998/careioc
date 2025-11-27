<?php include_once "header.php"; ?>

<div class="content-page">

    <div class="container-fluid">
        <div class="row">
            
            <div class="col-lg-12">
                <div class="d-flex flex-wrap align-items-center justify-content-between my-schedule mb-4">
                   <div class="d-flex align-items-center justify-content-between">
                        <h4 class="font-weight-bold">Danh sách diễn giả</h4>
                    </div>  
                    <div class="create-workform">
                        <div class="d-flex flex-wrap align-items-center justify-content-between">
                            <div class="modal-product-search d-flex">
                                <form class="mr-3 position-relative">
                                    <div class="form-group mb-0">
                                    <input type="text" class="form-control" id="exampleInputText"  placeholder="Tìm kiếm">
                                    <a class="search-link" href="#">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </a>
                                    </div>
                                </form>
								<a href="product-add.html" class="btn btn-secondary btn-sm position-relative d-flex align-items-center justify-content-between mr-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-2" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Xuất dữ liệu
                                </a>
                            </div>                            
                        </div>
                    </div>                    
                </div>
                
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-block card-stretch">
                            <div class="card-body p-0">
                                <div class="d-flex justify-content-between align-items-center p-3">
                                    
                                    
                                </div>
                                <div class="table-responsive">
                                    <table class="table data-table mb-0">
                                        <thead class="table-color-heading">
                                            <tr class="text-light">
												<th scope="col" >
                                                    <label class="text-muted m-0" >Họ và tên</label>
                                                </th>
                                                <th scope="col" >
                                                    <label class="text-muted mb-0" >Ngày đăng ký</label>
                                                </th>
                                                <th scope="col"  class="">
                                                    <label class="text-muted mb-0" >Sự kiện tổ chức</label>
                                                </th>
                                                <th scope="col" >
                                                    <label class="text-muted mb-0" >Sự kiện tham gia</label>
                                                </th>
                                                <th scope="col" >
                                                    <label class="text-muted mb-0" >Tình trạng</label>
                                                </th>
                                                <th scope="col" class="text-right">
                                                    <span class="text-muted" >Thao tác</span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
											<?php 
											$i = 1;
											foreach($speakers as $speaker)
											{
											?>
                                            <tr class="white-space-no-wrap">
                                                <td class="">
                                                    <div class="active-project-1 d-flex align-items-center mt-0 ">
                                                        <div class="h-avatar is-medium">
                                                            <img class="avatar rounded-circle" alt="<?php echo $speaker->user_firstname." ".$speaker->user_lastname;?>" src="<?php echo ($speaker->user_avatar)? $this->helper->__upload_url("user")."/".$speaker->user_avatar : $this->helper->__asset_url("backend")."/images/user/5.jpg";?>">
                                                        </div>
                                                        <div class="data-content">
                                                            <div>
                                                            <span class="font-weight-bold"><?php echo $speaker->user_firstname." ".$speaker->user_lastname;?></span>                           
                                                            </div>
                                                            <p class="m-0 mt-1">
                                                            <?php echo $speaker->user_phone; ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><?php echo date("d-m-Y",strtotime($speaker->speaker_register_date)); ?></td>
                                                <td class="text-center">
                                                    <?php echo number_format($speaker->counthostevent,0);?>
                                                </td>
                                                <td class="text-center">
                                                    <?php echo number_format($speaker->counthostevent,0);?>
                                                </td>
                                                <td>
                                                    <p class="mb-0 text-<?php echo $speaker->status_class;?> font-weight-bold d-flex justify-content-start align-items-center">
                                                        <small><svg class="mr-2" xmlns="http://www.w3.org/2000/svg" width="18" viewBox="0 0 24 24" fill="none">
															<circle cx="12" cy="12" r="8" fill="<?php echo $speaker->status_color;?>"></circle></svg>
                                                        </small><?php echo $speaker->status_name;?>
                                                    </p>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-end align-items-center">
														<button type="button" class="btn btn-outline-info btn-sm mx-1"><i class="fa-solid fa-pen"></i></button>
														<button type="button" class="btn btn-outline-primary btn-sm mx-1"><i class="fa-solid fa-check"></i></button>
														<button type="button" class="btn btn-outline-danger btn-sm mx-1"><i class="fa-solid fa-trash"></i></button>
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
                    </div>
                </div>
            </div>
        </div>
    </div>

      </div>
<?php include_once "footer.php"; ?>