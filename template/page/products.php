<?php include "header.php"; ?>
<link rel="stylesheet" href="<?php echo $template_path; ?>/assets/modules/doctor/css/specialistad59.css?vs=1.0.9" type="text/css" />
<script type="text/javascript" src="<?php echo $template_path; ?>/assets/js/jquery.numeric/jquery.numeric.js"></script>
<!-- <script type="text/javascript" src="<?php echo $template_path; ?>/assets/modules/doctor/js/specialistad59.js?vs=1.0.9"></script> -->
<style>
 /* BOX */
.mcol {
    
    padding: 10px;
}

.itspecimm {
    background: #e9f7ff;
    border-radius: 20px;
    padding: 20px 16px;
    text-align: center;
    position: relative;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
}

/* LINK PHỦ */
.mlinks {
    position: absolute;
    inset: 0;
    z-index: 1;
}

/* WRAP ẢNH + BADGE */
.mthumb-wrap {
    position: relative;
    width: 150px;
    height: 150px;
    margin: 0 auto 16px;
    z-index: 2;
}

/* ẢNH TRÒN */
.mthumb {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    border: 6px solid #4f8df7;
    background: #fff;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
}

.mthumb img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    border-radius: 50%;
}

/* BADGE */
.discount-badge {
    position: absolute;
    top: -10px;
    right: -10px;
    background: #ffcc00;
    color: #ff0000;
    font-weight: 700;
    font-size: 14px;
    padding: 6px 12px;
    border-radius: 8px;
    z-index: 5;
    box-shadow: 0 4px 10px rgba(0,0,0,0.25);
}

/* TÊN */
.product-name {
    font-size: 16px;
    font-weight: 600;
    color: #333;
    margin: 10px 0 6px;
}

/* GIÁ */
.price-container {
    margin-top: 6px;
}

.price-row {
    display: flex;
    justify-content: center;
    align-items: baseline;
    gap: 8px;
}

.current-price {
    color: #ff3b00;
    font-size: 14px;
    font-weight: 700;
}
.current-price i{
    font-size: 13px;
}

.old-price {
    color: #999;
    font-size: 14px;
    text-decoration: line-through;
}
.old-price i{
    font-size: 13px;
}

/* META */
.meta-row {
    margin-top: 6px;
}

.sold-count {
    font-size: 13px;
    color: #666;
    font-weight: 500;
}

</style>
    <div class="gdcontent">
    <div class="vhspeciapg">
        <div class="wrapper">
            <div class="hpspeciapg">
                <div class="vnttitle vcolor vupper vcenter">
                    <div class="inline-block ">
                        <h1>NHÀ THUỐC</h1>
                    </div>
                </div>
                <div class="menuTab" bis_skin_checked="1">
                        <ul>
                                <?php foreach($product_category as $product_category){?>
                                <li class="current"><a href="#" rel="nofollow"><?php echo $product_category->category_name; ?> </a></li>
                            <?php }?>
                        </ul>
                    </div>
                <div class="vntconts">
                    <div class="tpspeciamm">
                        <div class="mmlist">
                            <div class="pggrip">
                                <?php foreach($products as $product){ ?>
                                      <div class="mcol">
                                        <div class="itspecimm">

                                            <a class="mlinks" href="trung-tam-noi-soi-tieu-hoa.html"></a>

                                            <!-- IMAGE + BADGE -->
                                            <div class="mthumb-wrap">
                                                <div class="mthumb">
                                                    <img src="<?php echo XC_URL; ?>/uploads/products/<?php echo $product->product_image; ?>" 
                                                        alt="<?php echo $product->product_name; ?>">
                                                </div>
                                                <?php if($product->product_discount != 0){ ?>
                                                <div class="discount-badge">-<?php echo (int) $product->product_discount; ?>%</div>
                                                <?php }else{echo '';}?>
                                            </div>

                                            <!-- CONTENT -->
                                            <div class="mdecss">
                                                <h3 class="product-name"><?php echo $product->product_name; ?></h3>

                                                <div class="price-container">
                                                    <div class="price-row">
                                                        <span class="current-price">
                                                            <?php
                                                            $price = (float)$product->product_price;
                                                            $discount = (int)$product->product_discount;

                                                            $price_after = $price * (100 - $discount) / 100;
                                                            echo number_format($price_after, 0, ',', '.') . 'đ'; 
                                                            
                                                            ?>
                                                            </span>
                                                        <span class="old-price"><?php
                                                        echo ($product->product_discount != 0 ) ?
                                                          number_format($product->product_price, 0, ',', '.') . 'đ' : ''; 
                                                         
                                                         ?></span>
                                                    </div>
                                                    
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <?php } ?>
                                                    
                                    
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include "footer.php"; ?>
