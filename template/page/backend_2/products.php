<?php include_once "header.php"; ?>

<div class="content-page">

    <div class="container-fluid">
        <div class="row">
            
            <div class="col-lg-12">
                <div class="d-flex flex-wrap align-items-center justify-content-between my-schedule mb-4">
                   <div class="d-flex align-items-center justify-content-between">
                        <h4 class="font-weight-bold">Danh sách sản phẩm</h4>
                    </div>  
                    <div class="create-workform">
                        <div class="d-flex flex-wrap align-items-center justify-content-between">
                            <div class="modal-product-search d-flex">
                                <form class="mr-3 position-relative">
                                    <div class="form-group mb-0">
                                    <input type="text" class="form-control" id="exampleInputText"  placeholder="Search Product">
                                    <a class="search-link" href="#">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </a>
                                    </div>
                                </form>
                                <a href="product-add.html" class="btn btn-primary position-relative d-flex align-items-center justify-content-between mr-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-2" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Thêm sản phẩm
                                </a>
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
                                                    <label class="text-muted m-0" >Tên sản phẩm</label>
                                                </th>
                                                <th scope="col" >
                                                    <label class="text-muted mb-0" >Nhóm sản phẩm</label>
                                                </th>
                                                <th scope="col"  class="text-right">
                                                    <label class="text-muted mb-0" >Giá</label>
                                                </th>
                                                <th scope="col" >
                                                    <label class="text-muted mb-0" >Tồn kho</label>
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
											foreach($products as $product)
											{
											?>
                                            <tr class="white-space-no-wrap">
                                                <td class="">
                                                    <div class="active-project-1 d-flex align-items-center mt-0 ">
                                                        <div class="h-avatar is-medium">
                                                            <img class="avatar rounded" alt="ảnh sản phẩm" src="<?php $this->helper->__upload_url(); ?>/<?php echo $product->product_image;?>">
                                                        </div>
                                                        <div class="data-content">
                                                            <div>
                                                            <span class="font-weight-bold"><?php echo $product->product_name;?></span>                           
                                                            </div>
                                                            <p class="m-0 mt-1">
                                                            <?php echo $product->product_sku; ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><?php echo $product->category_name; ?></td>
                                                <td class="text-right">
                                                    <?php echo number_format($product->product_price,0);?>
													<p class="text-muted"><del><?php echo number_format($product->product_price,0);?></del></p>
                                                </td>
                                                <td>
                                                    <?php 
													echo number_format($product->product_in_stock,0);?>
                                                </td>
                                                <td>
                                                    <p class="mb-0 text-<?php echo $product->status_class;?> font-weight-bold d-flex justify-content-start align-items-center">
                                                        <small><svg class="mr-2" xmlns="http://www.w3.org/2000/svg" width="18" viewBox="0 0 24 24" fill="none">
															<circle cx="12" cy="12" r="8" fill="<?php echo $product->status_color;?>"></circle></svg>
                                                        </small><?php echo $product->status_name;?>
                                                    </p>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-end align-items-center">
                                                        <a class="" data-toggle="tooltip" data-placement="top" title="" data-original-title="Sửa" href="#">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="text-secondary mx-4" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                            </svg>
                                                        </a>
                                                        <a class="badge bg-danger" data-toggle="tooltip" data-placement="top" title="" data-original-title="Xoá" href="#">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
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
                    </div>
                </div>
            </div>
        </div>
    </div>

      </div>
<?php include_once "footer.php"; ?>