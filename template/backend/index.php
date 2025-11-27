<?php include_once "header.php";?>
<script>
	! function() {
    function r(r, a) {
        for (var o = 0; o < a.length; o++) {
            var e = a[o];
            e.enumerable = e.enumerable || !1, e.configurable = !0, "value" in e && (e.writable = !0), Object.defineProperty(r, e.key, e)
        }
    }
    var a = function() {
        function a() {
            ! function(r, a) {
                if (!(r instanceof a)) throw new TypeError("Cannot call a class as a function")
            }(this, a)
        }
        var o, e;
        return o = a, e = [{
            key: "initChartsBars",
            value: function() {
                Chart.defaults.global.defaultFontColor = "#495057", Chart.defaults.scale.gridLines.color = "transparent", Chart.defaults.scale.gridLines.zeroLineColor = "transparent", Chart.defaults.scale.ticks.beginAtZero = !0, Chart.defaults.global.elements.line.borderWidth = 1, Chart.defaults.global.legend.labels.boxWidth = 12;
                var r, a = jQuery(".js-chartjs-analytics-bars");
                r = {
                    labels: [<?php echo $day_data;?>],
                    datasets: [{
                        label: "Đơn hàng mới",
                        fill: !0,
                        backgroundColor: "rgba(6, 101, 208, .6)",
                        borderColor: "transparent",
                        pointBackgroundColor: "rgba(6, 101, 208, 1)",
                        pointBorderColor: "#fff",
                        pointHoverBackgroundColor: "#fff",
                        pointHoverBorderColor: "rgba(6, 101, 208, 1)",
                        data: [<?php echo $order_data;?>]
                    }, {
                        label: "Đã cọc",
                        fill: !0,
                        backgroundColor: "#82b54b",
                        borderColor: "transparent",
                        pointBackgroundColor: "#82b54b",
                        pointBorderColor: "#fff",
                        pointHoverBackgroundColor: "#fff",
                        pointHoverBorderColor: "rgba(6, 101, 208, .2)",
                        data: [<?php echo $order_data2;?>]
                    }]
                }, a.length && new Chart(a, {
                    type: "bar",
                    data: r,
                    options: {
                        tooltips: {
                            intersect: !1,
                            callbacks: {
                                label: function(r, a) {
                                    return a.datasets[r.datasetIndex].label + ": " + r.yLabel + " Customers"
                                }
                            }
                        }
                    }
                })
            }
        }, {
            key: "init",
            value: function() {
                this.initChartsBars()
            }
        }], null && r(o.prototype, null), e && r(o, e), a
    }();
    jQuery((function() {
        a.init()
    }))
}();
</script>
         <main id="main-container">
<div class="content content-full">
	<div class="block block-rounded js-appear-enabled animated fadeIn" data-toggle="appear">
        <div class="block-header block-header-default">
            <h3 class="block-title">Đường dẫn giới thiệu</h3>
            <div class="block-options">
                <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                    <i class="si si-refresh"></i>
                </button>
                <button type="button" class="btn-block-option">
                    <i class="si si-wrench"></i>
                </button>
            </div>
        </div>
        <div class="block-content block-content-full">
            <strong>https://khachhang.mmexpress.vn/register?ref=<?php echo $_SESSION['staff']['id'];?></strong>
        </div>
    </div>
    <div class="d-flex justify-content-between align-items-center py-3">
        <h2 class="h3 font-w400 mb-0">Báo cáo tổng quan</h2>
        <div class="dropdown">
            <button type="button" class="btn btn-sm btn-light px-3" id="dropdown-analytics-overview" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?php echo $select_title;?> <i class="fa fa-fw fa-angle-down"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-right font-size-sm" aria-labelledby="dropdown-analytics-overview">
                <a class="dropdown-item" href="?range=last30day">30 ngày qua</a>
                <a class="dropdown-item" href="?range=thisweek">Tuần này</a>
                <a class="dropdown-item" href="?range=lastweek">Tuần trước</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="?range=thismonth">Tháng này</a>
                <a class="dropdown-item" href="?range=lastmonth">Tháng trước</a>
            </div>
        </div>
    </div>
    <div class="row row-deck">
        <div class="col-sm-6 col-xl-3 js-appear-enabled animated fadeIn" data-toggle="appear">
            <a class="block block-rounded block-fx-pop text-center" href="javascript:void(0)">
                <div class="block-content block-content-full">
                    <div class="item item-circle bg-primary-lighter mx-auto my-3">
                        <i class="fa fa-users text-primary"></i>
                    </div>
                    <div class="text-black display-4 font-w700"><?php echo number_format($countcus,0);?></div>
                    <div class="text-muted mt-1">Khách hàng</div>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-xl-3 js-appear-enabled animated fadeIn" data-toggle="appear" data-timeout="150">
            <a class="block block-rounded block-fx-pop text-center" href="javascript:void(0)">
                <div class="block-content block-content-full">
                    <div class="item item-circle bg-xinspire-lighter mx-auto my-3">
                        <i class="fa fa-eye text-xinspire-dark"></i>
                    </div>
                    <div class="text-black display-4 font-w700"><?php echo number_format($countorder,0);?></div>
                    <div class="text-muted mt-1">Đơn hàng</div>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-xl-3 js-appear-enabled animated fadeIn" data-toggle="appear" data-timeout="300">
            <a class="block block-rounded block-fx-pop text-center" href="javascript:void(0)">
                <div class="block-content block-content-full">
                    <div class="item item-circle bg-xsmooth-lighter mx-auto my-3">
                        <i class="fa fa-columns text-xsmooth"></i>
                    </div>
                    <div class="text-black display-4 font-w700"><?php echo number_format($sumdeposite/1000000,0);?>tr</div>
                    <div class="text-muted mt-1">Tiền khách nạp</div>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-xl-3 js-appear-enabled animated fadeIn" data-toggle="appear" data-timeout="450">
            <a class="block block-rounded block-fx-pop text-center" href="javascript:void(0)">
                <div class="block-content block-content-full">
                    <div class="item item-circle bg-xplay-lighter mx-auto my-3">
                        <i class="fa fa-level-up-alt text-xplay"></i>
                    </div>
                    <div class="text-black display-4 font-w700"><?php echo number_format($sumorder/1000000,0);?>tr</div>
                    <div class="text-muted mt-1">Doanh số</div>
                </div>
            </a>
        </div>
    </div>
    <div class="d-flex justify-content-between align-items-center pt-5 pb-3">
        <h2 class="h3 font-w400 mb-0">Đơn hàng tuần qua</h2>
    </div>
    <div class="block block-rounded block-fx-pop js-appear-enabled animated fadeIn" data-toggle="appear">
        <div class="block-content block-content-full">
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <div class="p-md-2 p-lg-3"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                        <canvas class="js-chartjs-analytics-bars chartjs-render-monitor" style="display: block; height: 347px; width: 694px;" width="867" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<div class="block block-rounded js-appear-enabled animated fadeIn" data-toggle="appear">
        <div class="block-header block-header-default">
            <h3 class="block-title">Đơn hàng mới</h3>
            <div class="block-options">
                <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                    <i class="si si-refresh"></i>
                </button>
                <button type="button" class="btn-block-option">
                    <i class="si si-wrench"></i>
                </button>
            </div>
        </div>
        <div class="block-content block-content-full">
            <div class="table-responsive">
                <table class="table table-striped table-borderless table-vcenter mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center" style="width: 100px;">Mã</th>
							<th class="d-none d-md-table-cell">Khách hàng</th>
							<th class="d-none d-sm-table-cell text-center">Thời gian</th>
							<th class="d-none d-sm-table-cell text-center">Trạng thái</th>
                            <th class="d-none d-sm-table-cell text-center">Tổng tiền</th>
                            <th class="d-none d-sm-table-cell text-center">Đã cọc</th>
                            <th class="d-none d-sm-table-cell text-right">Nhân viên</th>
                           
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
                                <?php echo $order->user_firstname." ".$order->user_lastname;?>
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
							
                        </tr>
						<?php
						}
						?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
</div>
    </main>
         <?php include_once "footer.php";?>