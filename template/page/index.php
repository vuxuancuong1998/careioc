<?php include "header.php";?>
    <div id="vnt-content" >
      
        
        <script src="<?php echo $template_path; ?>/assets/js/sameheight/sameHeight.js" defer></script>
<script src="<?php echo $template_path; ?>/assets/js/jquery.numeric/jquery.numeric.js" defer></script>
<link href="<?php echo $template_path; ?>/assets/modules/main/css/mainad59.css?vs=1.0.9" rel="stylesheet" type="text/css" />
<script src="<?php echo $template_path; ?>/assets/modules/main/js/mainad59.js?vs=1.0.9"></script>
<div class="boxhome">
    <div class="infohome">
        <div class="wrapper">
            <div class="hpblockall">
                <div class="xxgrip">
                    <div class="lcol">
                        <div class="tpblockalm ">
                            <div class="slblockalm vhslickload vhbtnslick vhdots vhdotsab ">
                                <div class="colsl">
    <div class="itblockalm">
      <!-- <a onmousedown="return rwt(this,'advertise',6)" href=vn/index.html# target=_self> -->
        <img src="<?php echo $template_path; ?>/assets/images/banner.jpg" alt="banner" fetchpriority='high'>
      </a>
    </div>
</div>
                            </div>
                            
                            
                        </div>
                    </div>
                    <div class="rcol">
                        <link href="<?php echo $template_path; ?>/assets/js/material-picker/duDatepicker.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $template_path; ?>/assets/js/material-picker/duDatepicker-theme.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $template_path; ?>/assets/js/material-picker/duDatepicker.js"></script>

<div class="tpblockalh">
    <div class="lhtitle ">
        <div class="ticon">
            <i class="fa fa-edit"></i>
        </div>
        <div class="ttext">ĐẶT LỊCH HẸN KHÁM</div>
        
    </div>
    <div class="lhconts" style="background-image: url('');">
        <form action="#" method="POST" id="myForm" onsubmit="return false;">
            <div class="frminput">
                <div class="frmgrip">
                    <div class="mcol">
                        <div class="frmgroup div_input">
                            <input type="text" name="name" class="required" value="" placeholder="Họ & tên (*)" autocomplete="off" />
                        </div>
                    </div>
                    <div class="mcol">
                        <div class="frmgroup div_input">
                            <input type="text" name="phone" class="format_number required" value="" minlength="10" maxlength="10" placeholder="Điện thoại (*)" autocomplete="off" />
                        </div>
                    </div>
                    <div class="hcol">
                        <div class="frmgroup div_input">
                            <select name="gender" id="gender" size="1" class='chosen required' class="select form-control" ><option value="">Giới tính (*)</option><option value="1" >Nam</option><option value="2" >Nữ</option></select>
                        </div>
                    </div>
                    <div class="hcol">
                        <div class="frmgroup div_input">
                            <input type="text" name="year" class="format_number required" value="" minlength="4" maxlength="4" placeholder="Tuổi (VD: 1980) (*)" autocomplete="off" />
                        </div>
                    </div>
                    
                    <div class="mcol">
                        <div class="frmgroup vdate fa-calendar-alt div_input">
                            <input type="text" name="date_book" class="date_book required" id="date_book" value="" minlength="10" maxlength="10" placeholder="Ngày khám *" autocomplete="off" />
                        </div>
                    </div>
                     <div class="mcol">
                        <div class="frmgroup div_input">
                            <select name="typecat" class="required" id="typecat">
                                <option value="0">Bác sĩ khám</option>
                                    <option type='service' value='2' >Bác sĩ A</option>
                                    <option type='service' value='2' >Bác sĩ B</option>
                                
                               
                                <!-- <optgroup label="Tin tức">
                                    
                                </optgroup>
                                <optgroup label="Hướng dẫn">
                                    
                                </optgroup> -->
                            </select>
                        </div>
                </div>
            </div>
            <div class="frmbuton">
                <div class="viewall vcenter">
                    <input type="hidden" name="modu" value="">
                    <input type="hidden" name="typebook" value="0">
                    <button type="submit" class="btnSendb"><span>XÁC NHẬN Đặt hẹn</span></button>
                </div>
            </div>
        </form>
    </div>
</div>
<style type="text/css">.vhpopbooks {padding: 30px 45px;background-color: #fff;overflow: unset;margin-top: 50px;}.vhpopbooks .fancybox-close-small {position: absolute;top: -50px;right: 0;height: 50px;padding: 0;color: #fff;opacity: 1;}.vhpopbooks .fancybox-close-small svg {display: none;}.vhpopbooks .fancybox-close-small::after {content: '\f057';font-family: 'Font Awesome 5 Free';font-weight: 900;position: absolute;right: 42px;top: 50%;transform: translateY(-50%);}.vhpopbooks .fancybox-close-small::before {content: 'Đóng';position: absolute;right: 0;top: 50%;transform: translateY(-50%);color: #fff;}.vhpopbooks .bkicon {margin-bottom: 20px;text-align: center;}.vhpopbooks .bkdecs {font-size: 16px;line-height: 26px;text-align: center;}.vhpopbooks .fancybox-close-small::before{display: none;}.vhpopbooks .fancybox-close-small::after{right: 0px;}</style>
<a href="#vhpopbooks" rel="nofollow" class="clpopnotifi hidden"></a>
<div id="vhpopbooks" class="vhpopbooks" style="max-width: 370px; width: 100%; display: none;">
    <div class="bkicon">
        <img src="<?php echo $template_path; ?>/assets/modules/popup/images/isuccess.jpg" alt="success" />
    </div>
    <div class="bkdecs "><span><!-- --></span></div>
</div>
<script>
    var link_book = 'vn/dang-ky-lich-kham.html',
        typecat = "",
        typebook = 0,
        bookid = typebook,
        disDatesDefault = [],
        disDates = [],
        disabledVip = {},
        minDate = '11/12/2025',
        maxDate = '11/18/2025',
        dayTomorrow = "12/11/2025",
        timeLimit = 20,
        timeCurrent = parseInt(20),
        mess_timelimit = "Đã quá giờ đặt cho ngày [day], vui lòng chọn ngày khám khác";

    $(document).ready(function() {
        $(".clpopnotifi").fancybox({
            baseClass: "vstylepop",
            toolsbar: false,
            toolbar: false,
            smallBtn: true,
            fitToView: false,
            autoSize: true,
            autoHeight: true,
            autoWidth: true,
            closeClick: false,
            clickSlide: "false",
            clickOutside: "false",
            touch: false,
            openEffect: "elastic",
            closeEffect: "elastic",
            helpers: {
                overlay: { closeClick: false },
            },
        });

        $('.format_number').numeric({ negative: false });

        duDatepicker('#date_book',{
            minDate: minDate,
            maxDate: maxDate,
            disabledDays: dayOffWeek
        });
        $("#date_book").keypress(function(e) {
            return false;
        });

        get_type_book($("#typecat option:selected").attr("type"), $("#typecat").val(), bookid);
        typecat = $("#typecat option:selected").attr("type");

        $("#typecat").change(function(e) {
            typecat = $("#typecat option:selected").attr("type");
            bookid = 0;
            if (typecat == "doctor" || typecat == "chuyenkhoa") {
                disDates = disDatesDefault;
                if (typecat == "doctor") {
                    bookid = typebook;
                }
            }else{
                disDates = [];
            }

            get_type_book(typecat, $(this).val(), bookid);
        });

        $.validator.addMethod("check_phone", function(value, element) {
            return this.optional(element) || value.match(/((^0)+([1-9]{1,1})+([0-9]{1,1})+([0-9]{7,7})\b)/g);
        }, "Số điện thoại không hợp lệ. Nhập từ 9 chữ số trở lên" );

        var validator = $("#myForm").validate({
            rules: {
                name: {
                    required: true,
                    minlength: 2,
                    maxlength: 50
                },
                phone: {
                    required: true,
                    check_phone: true,
                    minlength: 10,
                    maxlength: 10
                },
                year: {
                    required: true,
                    min: 1920,
                    max: 2100,
                    minlength: 4,
                    maxlength: 4
                },
                gender: {
                    required: true
                },
                date_book: {
                    required: true,
                    minlength: 10,
                    maxlength: 10
                },
                hour_book: {
                    required: true
                },
                typecat: {
                    required: true
                },
                typebook: {
                    required: true
                },
                content: {
                    minlength: 5,
                    maxlength: 2000
                }
            },
            messages: {
            },
            errorElement: "em",
            errorPlacement: function ( error, element ) {
                // Add the `help-block` class to the error element
                error.addClass( "help-block" );

                // Add `has-feedback` class to the parent div.form-group
                // in order to add icons to inputs
                element.parents( ".div_input" ).addClass( "has-feedback" );

                // Add the span element, if doesn't exists, and apply the icon classes to it.
                if ( !element.next( "span" )[ 0 ] ) {
                    $( "<span class='glyphicon glyphicon-remove form-control-feedback'></span>" ).insertAfter( element );
                }
            },
            success: function ( label, element ) {
                // Add the span element, if doesn't exists, and apply the icon classes to it.
                if ( !$( element ).next( "span" )[ 0 ] ) {
                    $( "<span class='glyphicon glyphicon-ok form-control-feedback'></span>" ).insertAfter( $( element ) );
              }
            },
            highlight: function ( element, errorClass, validClass ) {
                $( element ).parents( ".div_input" ).addClass( "has-error" ).removeClass( "has-success" );
                $( element ).next( "span" ).addClass( "glyphicon-remove" ).removeClass( "glyphicon-ok" );
            },
            unhighlight: function ( element, errorClass, validClass ) {
                $( element ).parents( ".div_input" ).addClass( "has-success" ).removeClass( "has-error" );
                $( element ).next( "span" ).addClass( "glyphicon-ok" ).removeClass( "glyphicon-remove" );
            },
            submitHandler: function(form) {
                window.location.href = link_book+"/?"+$(form).serialize();
                return false;
            }
        });
    });

    function get_type_book(mod, cid, id = 0){
        $("input[name='modu']").val(mod);

        $.ajax({
            url: ROOT + 'load_ajax.php?do=get_type_book',
            type: 'POST',
            dataType: 'json',
            data: "mod="+mod+"&cid="+cid+"&id="+id+"&lang="+lang
        })
        .done(function(data) {
            disDates = disDatesDefault;
            if (data.disabledDates.length > 0) {
                disDates = data.disabledDates;
            }
            if (typecat != "doctor" && typecat != "chuyenkhoa") {
                disDates = [];
            }
            // VIP
            disabledVip = data.disabledVip;
            rand_input_date();
        });
    }

    function rand_input_date(){
        $(".dudp__date").removeClass("dudp_vip");

        var elmidDate = "date_book_"+Math.floor(Math.random() * 9999);
        $(".vdate").html('<input type="text" name="date_book" class="date_book required" id="'+elmidDate+'" value="" minlength="10" maxlength="10" placeholder="Ngày khám *" autocomplete="off" />');
        duDatepicker('#'+elmidDate,{
            minDate: minDate,
            maxDate: maxDate,
            disabledDates: disDates,
            disabledDays: dayOffWeek,
            events: {
                shown: function(datepicker){
                    setTimeout(() => {
                        // VIP
                        if (Object.keys(disabledVip).length > 0) {
                            Object.keys(disabledVip).forEach(function(key) {
                                if (disabledVip[key] != "" && disabledVip[key] != undefined) {
                                    const arrDvp = disabledVip[key].split("index.html");
                                    $(".dudp__date[data-date='"+parseInt(arrDvp[0])+"'][data-month='"+(parseInt(arrDvp[1]) - 1)+"'][data-year='"+parseInt(arrDvp[2])+"']").addClass("dudp_vip");
                                }
                            });
                        }
                        $(".dudp__buttons .dudp__messvip").remove();
                        $(".dudp__buttons").append("<span class='dudp__messvip ok'>* Lưu ý: màu đỏ là ngày khám VIP</span>");
                    }, 50);
                },
                dateChanged: function(data, datepicker){
                    $(".btnSendb").removeClass("isDisabled");
                    var daychon = data.date;

                    if (daychon == dayTomorrow) {
                        $.ajax({
                            url: ROOT + 'load_ajax.php?do=hour',
                            type: 'POST',
                            dataType: 'json',
                            data: 'lang='+lang
                        })
                        .done(function(data) {
                            timeCurrent = data.hour;
                            timeLimit = data.limitHour;
                            if (timeCurrent >= timeLimit) {
                                $(".bkdecs span").html(mess_timelimit.replace("[day]", daychon));
                                $("input[name='date_book']").val("");
                                $(".clpopnotifi").trigger("click");
                                $(".btnSendb").addClass("isDisabled");
                            }
                        });
                    }
                }
            }
        });
    }
</script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
  $(".playMuted").click(function (e) {
    toggleMute();
  });

  function toggleMute() {
      var video = document.getElementById("videoPlay");
      video.muted = !video.muted; // false tự chạy

      if (video.muted == false) {
          $(".playMuted").html('<i class="fas fa-volume-down"></i>');
      } else {
          $(".playMuted").html('<i class="fas fa-volume-mute"></i>');
      }
  }
</script>

<div class="boxhome">
    <div class="abouthome">
        <div class="wrapper ">
            <div class="hpabouthh ">
                <div class="tpaboutha">
<div class="hagrip">
<div class="mcol">
<div class="hadecss">
<div class="dscapts">PHÒNg khám đa khoa</div>

<div class="hatitle">
<h2>Cao đẳng Kon Tum</h2>
</div>

<div class="haconts">Phòng Khám chính thức khai trương và đi vào hoạt động từ ngày 25 tháng 11 năm 2025. Cùng theo đuổi những giá trị cốt lõi “Nâng niu sức khỏe - Giữ trọn niềm tin” và mô hình quản lý dịch vụ y tế chuyên nghiệp theo chuẩn quốc tế, Phòng khám đa khoa Cao đẳng Kon Tum là một làn gió mới góp phần thay đổi tích cực trong việc chăm sóc sức khỏe cho cộng đồng.</div>

<div class="haviews">
<div class="viewall"><a href="<?php echo $template_path; ?>/dang-ky-lich-kham.html"><span>Đặt hẹn khám</span> </a></div>
</div>
</div>
</div>

<div class="hcol">
<div class="hathumb"><trust><img alt="caodangkontum" src="<?php echo $template_path; ?>/assets/images/banner.jpg" /><span class="ck_desc_img">Tập thể Bác sĩ Phòng khám Đa khoa Cao Đẳng Kon Tum</span></trust></div>
</div>
</div>
</div>
<!-- 
<div class="tpabouthb">
    <div class="hbgrip">
                      
                        <div class="bcol">
                            <div class="itabouthb">
                                <div class="thumb">
                                  
                                    <img src="<?php echo $template_path; ?>/assets/vnt_upload/weblink/abimg2.jpg" alt="abimg2" loading='lazy'>
                                  
                                </div>
                                <div class="decss">
                                    <div class="dstitle">
                                        Nâng niu sức khoẻ<br />
Giữ trọn niềm tin
                                    </div>
                                </div>
                            </div>
                        </div>
                      
                        <div class="bcol">
                            <div class="itabouthb">
                                <div class="thumb">
                                  
                                    <img src="<?php echo $template_path; ?>/assets/vnt_upload/weblink/abimg3.jpg" alt="abimg3" loading='lazy'>
                                  
                                </div>
                                <div class="decss">
                                    <div class="dstitle">
                                        Tầm soát ung thư sớm
                                    </div>
                                </div>
                            </div>
                        </div>
                      
                        <div class="bcol">
                            <div class="itabouthb">
                                <div class="thumb">
                                  
                                    <img src="<?php echo $template_path; ?>/assets/vnt_upload/weblink/abimg4.jpg" alt="abimg4" loading='lazy'>
                                  
                                </div>
                                <div class="decss">
                                    <div class="dstitle">
                                        Tận tâm - nhẹ nhàng chính xác
                                    </div>
                                </div>
                            </div>
                        </div>
                      
                        <div class="bcol">
                            <div class="itabouthb">
                                <div class="thumb">
                                  <a onmousedown="return rwt(this,'advertise',12)" href=vn/kham-bao-hiem-y-te.html target=_self>
                                    <img src="<?php echo $template_path; ?>/assets/vnt_upload/weblink/Ma_BHYT_(1).png" alt="Ma_BHYT_(1)" loading='lazy'>
                                  </a>
                                </div>
                                <div class="decss">
                                    <div class="dstitle">
                                        <p><span style="font-size:20px;">MÃ BHYT</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                      
                    </div>
                </div>
            </div>
            
            
        </div>
    </div>
</div> -->

<div class="boxhome">
    <div class="centerhome">
        <div class="wrapper ">
            <div class="hpcenterhh ">
                <div class="vnttitle vcolor vupper vcenter">
                  <div class="inline-block ">
                    <h2>CÁC TIN TỨC NỔI BẬT</h2>
                    
                  </div>
                </div>
                <div class="vntconts">
                    <div class="tpcenterhh">
                        <div class="slcenterhh vhbtnslick vchange vhslickload vhslickflex vhslick-lg-15 vhslick-sm-10">
                          
                          
                            <div class="colsl">
                                <div class="itcenterhh">
                                    <div class="thumb">
                                        <a class='vhthumbzoom vhthumb-65 vhthumb-full' href='#' target=_blank>
                                            <img src="<?php echo $template_path; ?>/assets/images/khaitruong.jpg" alt="Tin tức" loading='lazy'>
                                        </a>
                                    </div>
                                    <div class="decss">
                                        <div class="dstitle">
                                            <h3><a href="<?php echo $template_path; ?>/trung-tam-noi-soi-tieu-hoa.html" target=_blank>TƯNG BỪNG KHAI TRƯƠNG</a></h3>
                                        </div>
                                        <div class="dsviews ">
                                            <a href="<?php echo $template_path; ?>/trung-tam-noi-soi-tieu-hoa.html" target=_blank>
                                                <span>Xem chi tiết</span>
                                            </a>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                          
                            <div class="colsl">
                                <div class="itcenterhh">
                                    <div class="thumb">
                                        <a class='vhthumbzoom vhthumb-65 vhthumb-full'  href="<?php echo $template_path; ?>/trung-tam-sinh-hoc-phan-tu.html" target=_self>
                                            <img src="<?php echo $template_path; ?>/assets/images/nhathuoc.jpg" alt="NHÀ THUỐC ĐI VÀO HOẠT ĐỘNG" loading='lazy'>
                                        </a>
                                    </div>
                                    <div class="decss">
                                        <div class="dstitle">
                                    <h3 class="title-with-new">
                                        <a href="<?php echo $template_path; ?>/trung-tam-sinh-hoc-phan-tu.html" target="_self">
                                            NHÀ THUỐC ĐI VÀO HOẠT ĐỘNG
                                        </a>
                                        <!-- <span class="new-badge">
                                            <img src="<?php echo $template_path; ?>/assets/images/new.gif" alt="NEW">
                                        </span> -->
                                    </h3>    
                                    </div>
                                        <div class="dsviews ">
                                            <a  href="<?php echo $template_path; ?>/trung-tam-sinh-hoc-phan-tu.html" target=_self>
                                                <span>Xem chi tiết</span>
                                            </a>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                          
                            <div class="colsl">
                                <div class="itcenterhh">
                                    <div class="thumb">
                                        <a class='vhthumbzoom vhthumb-65 vhthumb-full' href="<?php echo $template_path; ?>/trung-tam-giai-phau-benh-te-bao-hoc.html" target=_self>
                                            <img src="<?php echo $template_path; ?>/assets/images/bannercong.jpg" alt="GIẢI PHẪU BỆNH - TẾ BÀO HỌC" loading='lazy'>
                                        </a>
                                    </div>
                                    <div class="decss">
                                        <div class="dstitle">
                                            <h3><a href="<?php echo $template_path; ?>/trung-tam-giai-phau-benh-te-bao-hoc.html" target=_self>CỔNG ĐÓN TIẾP BỆNH NHÂN</a></h3>
                                        </div>
                                        <div class="dsviews ">
                                            <a href="<?php echo $template_path; ?>/trung-tam-giai-phau-benh-te-bao-hoc.html" target=_self>
                                                <span>Xem chi tiết</span>
                                            </a>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="colsl">
                                <div class="itcenterhh">
                                    <div class="thumb">
                                        <a class='vhthumbzoom vhthumb-65 vhthumb-full' href="<?php echo $template_path; ?>/trung-tam-giai-phau-benh-te-bao-hoc.html" target=_self>
                                            <img src="<?php echo $template_path; ?>/assets/" alt="GIẢI PHẪU BỆNH - TẾ BÀO HỌC" loading='lazy'>
                                        </a>
                                    </div>
                                    <div class="decss">
                                        <div class="dstitle">
                                            <h3><a href="<?php echo $template_path; ?>/trung-tam-giai-phau-benh-te-bao-hoc.html" target=_self>TIN TỨC A</a></h3>
                                        </div>
                                        <div class="dsviews ">
                                            <a href="<?php echo $template_path; ?>/trung-tam-giai-phau-benh-te-bao-hoc.html" target=_self>
                                                <span>Xem chi tiết</span>
                                            </a>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="colsl">
                                <div class="itcenterhh">
                                    <div class="thumb">
                                        <a class='vhthumbzoom vhthumb-65 vhthumb-full' href="<?php echo $template_path; ?>/trung-tam-giai-phau-benh-te-bao-hoc.html" target=_self>
                                            <img src="<?php echo $template_path; ?>/assets/" alt="GIẢI PHẪU BỆNH - TẾ BÀO HỌC" loading='lazy'>
                                        </a>
                                    </div>
                                    <div class="decss">
                                        <div class="dstitle">
                                            <h3><a href="<?php echo $template_path; ?>/trung-tam-giai-phau-benh-te-bao-hoc.html" target=_self>TIN TỨC C</a></h3>
                                        </div>
                                        <div class="dsviews ">
                                            <a href="<?php echo $template_path; ?>/trung-tam-giai-phau-benh-te-bao-hoc.html" target=_self>
                                                <span>Xem chi tiết</span>
                                            </a>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                          

                        </div>
                    </div>
                </div>
            </div>
            
            
        </div>
    </div>
</div>

<div class="boxhome">
    <div class="packethome">
        <div class="wrapper ">
            <div class="hppackethh">
                <div class="vnttitle vcolor vupper vcenter">
                    <h2>Gói KHÁM</h2>
                </div>
                <div class="vntconts">
                    <!-- <div class="tppacketha">
                        <div class="slpacketha vhbtnslick vchange vhresslick vhslickload vhslickflex vhslick-lg-15 vhslick-sm-10">
                            
                                <div class="colsl ">
                                    <div class="itpacketha">
                                        <div class="thumb">
                                            <a class="vhthumbzoom vhthumb-65 vhthumb-full" href="#">
                                                <img src="<?php echo $template_path; ?>/assets/vnt_upload/service/07_2024/KHAM_VIP_DOANH_NHAN.png" alt="KHÁM VIP - VIP DOANH NHÂN" loading='lazy' />
                                            </a>
                                        </div>
                                        <div class="decss">
                                            
                                            <div class="dstitle">
                                                <h3><a href="vn/kham-vip-doanh-nhan.html">KHÁM NỘI TỔNG HỢP</a></h3>
                                            </div>
                                            <div class="dsviews ">
                                                <a href="vn/kham-vip-doanh-nhan.html">
                                                    <span>Xem chi tiết</span>
                                                </a>
                                                
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            
                                <div class="colsl ">
                                    <div class="itpacketha">
                                        <div class="thumb">
                                            <a class="vhthumbzoom vhthumb-65 vhthumb-full" href="vn/chuyen-khoa.html.html">
                                                <img src="<?php echo $template_path; ?>/assets/vnt_upload/service/07_2024/DSC04723_copy.jpg" alt="KHÁM BỆNH ĐA CHUYÊN KHOA" loading='lazy' />
                                            </a>
                                        </div>
                                        <div class="decss">
                                            
                                            <div class="dstitle">
                                                <h3><a href="vn/chuyen-khoa.html.html">KHÁM PHỤ SẢN</a></h3>
                                            </div>
                                            <div class="dsviews ">
                                                <a href="vn/chuyen-khoa.html.html">
                                                    <span>Xem chi tiết</span>
                                                </a>
                                                
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            
                                <div class="colsl ">
                                    <div class="itpacketha">
                                        <div class="thumb">
                                            <a class="vhthumbzoom vhthumb-65 vhthumb-full" href="vn/kham-bao-hiem-y-te.html">
                                                <img src="<?php echo $template_path; ?>/assets/vnt_upload/service/09_2024/79490.png" alt="KHÁM BẢO HIỂM Y TẾ - DỊCH VỤ" loading='lazy' />
                                            </a>
                                        </div>
                                        <div class="decss">
                                            
                                            <div class="dstitle">
                                                <h3><a href="vn/kham-bao-hiem-y-te.html">KHÁM BẢO HIỂM Y TẾ - DỊCH VỤ</a></h3>
                                            </div>
                                            <div class="dsviews ">
                                                <a href="vn/kham-bao-hiem-y-te.html">
                                                    <span>Xem chi tiết</span>
                                                </a>
                                                
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            
                        </div>
                    </div> -->
                    <div class="tppackethb">
                        <div class="slpackethb vhbtnslick vchange vhslickload vhslickflex vhslick-lg-15 vhslick-sm-10">
                            
                                <div class="colsl ">
                                    <div class="itpackethb">
                                        <div class="tdinfo">
                                            <div class="thumb">
                                                <a class="vhthumbzoom vhthumb-65 vhthumb-full" href="vn/goi-kham-cap-giay-kham-suc-khoe.html">
                                                    <img src="<?php echo $template_path; ?>/assets/vnt_upload/service/07_2024/DSC03756_copy.jpg" alt="GÓI KHÁM CẤP GIẤY KHÁM SỨC KHỎE" loading='lazy' />
                                                </a>
                                            </div>
                                            <div class="decss">
                                                <div class="dstitle layot2">
                                                    <h3>
                                                        <a href="vn/goi-kham-cap-giay-kham-suc-khoe.html">GÓI KHÁM A</a>
                                                    </h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tdview ">
                                            <a href="vn/goi-kham-cap-giay-kham-suc-khoe.html">
                                                <span>Xem chi tiết</span>
                                            </a>
                                            
                                        </div>
                                    </div>
                                    
                                </div>
                            
                                <div class="colsl ">
                                    <div class="itpackethb">
                                        <div class="tdinfo">
                                            <div class="thumb">
                                                <a class="vhthumbzoom vhthumb-65 vhthumb-full" href="vn/lay-mau-xet-nghiem-tai-nha.html">
                                                    <img src="<?php echo $template_path; ?>/assets/vnt_upload/service/09_2024/DSC04070_copy.jpg" alt="LẤY MẪU XÉT NGHIỆM TẠI NHÀ" loading='lazy' />
                                                </a>
                                            </div>
                                            <div class="decss">
                                                <div class="dstitle layot2">
                                                    <h3>
                                                        <a href="vn/lay-mau-xet-nghiem-tai-nha.html">LẤY MẪU XÉT NGHIỆM TẠI NHÀ</a>
                                                    </h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tdview ">
                                            <a href="vn/lay-mau-xet-nghiem-tai-nha.html">
                                                <span>Xem chi tiết</span>
                                            </a>
                                            
                                        </div>
                                    </div>
                                    
                                </div>
                            
                                <div class="colsl ">
                                    <div class="itpackethb">
                                        <div class="tdinfo">
                                            <div class="thumb">
                                                <a class="vhthumbzoom vhthumb-65 vhthumb-full" href="vn/goi-kham-an-toan-thuc-pham.html">
                                                    <img src="<?php echo $template_path; ?>/assets/vnt_upload/service/07_2024/pw_an_toan_ve_sinh_thuc_pham_2018.jpg" alt="GÓI KHÁM AN TOÀN THỰC PHẨM" loading='lazy' />
                                                </a>
                                            </div>
                                            <div class="decss">
                                                <div class="dstitle layot2">
                                                    <h3>
                                                        <a href="vn/goi-kham-an-toan-thuc-pham.html">GÓI KHÁM AN TOÀN THỰC PHẨM</a>
                                                    </h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tdview ">
                                            <a href="vn/goi-kham-an-toan-thuc-pham.html">
                                                <span>Xem chi tiết</span>
                                            </a>
                                            
                                        </div>
                                    </div>
                                    
                                </div>
                            
                                <div class="colsl ">
                                    <div class="itpackethb">
                                        <div class="tdinfo">
                                            <div class="thumb">
                                                <a class="vhthumbzoom vhthumb-65 vhthumb-full" href="vn/kham-suc-khoe-tien-hon-nhan.html">
                                                    <img src="<?php echo $template_path; ?>/assets/images/khamphusan.jpg" alt="KHÁM SỨC KHỎE TIỀN HÔN NHÂN" loading='lazy' />
                                                </a>
                                            </div>
                                            <div class="decss">
                                                <div class="dstitle layot2">
                                                    <h3>
                                                        <a href="vn/kham-suc-khoe-tien-hon-nhan.html">KHÁM SỨC KHỎE SINH SẢN</a>
                                                    </h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tdview ">
                                            <a href="vn/kham-suc-khoe-tien-hon-nhan.html">
                                                <span>Xem chi tiết</span>
                                            </a>
                                            
                                        </div>
                                    </div>
                                    
                                </div>

                                
                            
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

<div class="boxhome">
    <div class="screehome">
        <div class="wrapper ">
            <div class="hpscreehh">
                <div class="vnttitle vcolor vupper vcenter">
                    <h2>CHUYÊN KHOA</h2>
                </div>
                <div class="vntconts">
                    <div class="tpscreeha">
                        <div class="slscreeha vhbtnslick vchange vhresslick vhslickload vhslickflex vhslick-lg-15 vhslick-sm-10">
                            
                                <div class="colsl ">
                                    <div class="itscreeha">
                                        <div class="thumb">
                                            <a class="vhthumbzoom vhthumb-65 vhthumb-full" href="vn/tam-soat-ung-thu-tuoi-18-30.html">
                                                <img src="<?php echo $template_path; ?>/assets/images/khamnoi.jpg" alt="TẦM SOÁT UNG THƯ 18 - 30t" loading='lazy' />
                                            </a>
                                        </div>
                                        <div class="decss">
                                            <div class="dstitle">
                                                <h3><a href="vn/tam-soat-ung-thu-tuoi-18-30.html">CHUYÊN KHOA NỘI</a></h3>
                                            </div>
                                            <div class="dsviews ">
                                                <a href="vn/tam-soat-ung-thu-tuoi-18-30.html">
                                                    <span>Xem chi tiết</span>
                                                </a>
                                                
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            
                                <div class="colsl ">
                                    <div class="itscreeha">
                                        <div class="thumb">
                                            <a class="vhthumbzoom vhthumb-65 vhthumb-full" href="vn/tam-soat-ung-thu-tuoi-30-40.html">
                                                <img src="<?php echo $template_path; ?>/assets/images/khamngoai.jpg" alt="TẦM SOÁT UNG THƯ 30 - 40t" loading='lazy' />
                                            </a>
                                        </div>
                                        <div class="decss">
                                            <div class="dstitle">
                                                <h3><a href="vn/tam-soat-ung-thu-tuoi-30-40.html">CHUYÊN KHOA NGOẠI</a></h3>
                                            </div>
                                            <div class="dsviews ">
                                                <a href="vn/tam-soat-ung-thu-tuoi-30-40.html">
                                                    <span>Xem chi tiết</span>
                                                </a>
                                                
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            
                                <div class="colsl ">
                                    <div class="itscreeha">
                                        <div class="thumb">
                                            <a class="vhthumbzoom vhthumb-65 vhthumb-full" href="vn/tam-soat-ung-thu-tren-40.html">
                                                <img src="<?php echo $template_path; ?>/assets/vnt_upload/service/04_2024/tsimg3.jpg" alt="TẦM SOÁT UNG THƯ TRÊN 40t" loading='lazy' />
                                            </a>
                                        </div>
                                        <div class="decss">
                                            <div class="dstitle">
                                                <h3><a href="vn/tam-soat-ung-thu-tren-40.html">CHUYÊN KHOA CHĂM SÓC SỨC KHỎE</a></h3>
                                            </div>
                                            <div class="dsviews ">
                                                <a href="vn/tam-soat-ung-thu-tren-40.html">
                                                    <span>Xem chi tiết</span>
                                                </a>
                                                
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            
                        </div>
                    </div>
                    <div class="tpscreehb">
                        <div class="slscreehb vhbtnslick vchange vhslickload vhslickflex vhslick-lg-15 vhslick-sm-10">
                            
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

<div class="boxhome ">
    <div class="tetimohome " style="background-image: url('<?php echo $template_path; ?>/assets/images/baner_web.jpg');">
        <div class="wrapper">
            <div class="hptetimohh">
                <div class="sltetimohh vhslickload vhdots">
                  
                    <div class="colsl">
                        <div class="ittetimohh">
                            <div class="hhtends">
                                Đã từng rất sợ nội soi cho đến khi đc người quen giới thiệu và biết đến phòng khám Đa khoa Cao đẳng Kon Tum. Nội soi nhẹ nhàng và được bác sĩ, điều dưỡng hướng dẫn tận tình , giải đáp những thắc mắc của bệnh nhân.
                            </div>
                            <div class="hhinfos">
                                <div class="infgrip">
                                    <div class="lcol">
                                        <div class="ithumb">
                                          <a onmousedown="return rwt(this,'advertise',16)" href=https://maps.app.goo.gl/xuDWYSpxZ133ftyD8 target=_blank>
                                            <img src="<?php echo $template_path; ?>/assets/vnt_upload/weblink/no_image.jpg" alt="Chị Chilli Chilli" loading='lazy'>
                                          </a>
                                        </div>
                                    </div>
                                    <div class="rcol">
                                        <div class="idecss">
                                            <div class="dsname"><a onmousedown="return rwt(this,'advertise',16)" href=https://maps.app.goo.gl/xuDWYSpxZ133ftyD8 target=_blank>Chị Chilli Chilli</a></div>
                                            <div class="dschuc">Khách hàng</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                  
                    <div class="colsl">
                        <div class="ittetimohh">
                            <div class="hhtends">
                                Phòng khám sạch sẽ, khang trang, làm việc chuyên nghiệp. Bác sĩ và nhân viên y tế chuyên môn cao, tận tâm. Mỗi năm gia đình và mình đều nội soi kiểm tra. Highly recommended …
                            </div>
                            <div class="hhinfos">
                                <div class="infgrip">
                                    <div class="lcol">
                                        <div class="ithumb">
                                          <a onmousedown="return rwt(this,'advertise',17)" href=https://maps.app.goo.gl/LMJ5m2emAqAujBxF9 target=_blank>
                                            <img src="<?php echo $template_path; ?>/assets/vnt_upload/weblink/no_image.jpg" alt="KH dieptran2108" loading='lazy'>
                                          </a>
                                        </div>
                                    </div>
                                    <div class="rcol">
                                        <div class="idecss">
                                            <div class="dsname"><a onmousedown="return rwt(this,'advertise',17)" href=https://maps.app.goo.gl/LMJ5m2emAqAujBxF9 target=_blank>KH dieptran2108</a></div>
                                            <div class="dschuc">Khách hàng</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                  
                    <div class="colsl">
                        <div class="ittetimohh">
                            <div class="hhtends">
                                Mình vừa mới khám ở đây lần đầu tiên, nhưng phải vào đánh giá ngay, chuyên viên tư vấn nhiệt tình, bác sĩ làm việc rất tận tâm, mọi thứ ở đây đều rất tuyệt vời
                            </div>
                            <div class="hhinfos">
                                <div class="infgrip">
                                    <div class="lcol">
                                        <div class="ithumb">
                                          <a onmousedown="return rwt(this,'advertise',18)" href=https://maps.app.goo.gl/n39at52XcJT9Hf9x6 target=_blank>
                                            <img src="<?php echo $template_path; ?>/assets/vnt_upload/weblink/no_image.jpg" alt="Vũ Xuân CƯơng" loading='lazy'>
                                          </a>
                                        </div>
                                    </div>
                                    <div class="rcol">
                                        <div class="idecss">
                                            <div class="dsname"><a onmousedown="return rwt(this,'advertise',18)" href=https://maps.app.goo.gl/n39at52XcJT9Hf9x6 target=_blank>Vũ Xuân CƯơng</a></div>
                                            <div class="dschuc">Khách hàng</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                  
                    <div class="colsl">
                        <div class="ittetimohh">
                            <div class="hhtends">
                                Đã nhiều lần đến khám và chữa bệnh tại phòng khám. Mình thấy mọi thứ đều ổn, từ bác bảo vệ, các bác sĩ, nhân viên hay điều dưỡng đều rất nhiệt tình, chu đáo, nhẹ nhàng, ân cần, hướng dẫn tận tâm. Mình rất hài lòng về phòng.
                            </div>
                            <div class="hhinfos">
                                <div class="infgrip">
                                    <div class="lcol">
                                        <div class="ithumb">
                                          <a onmousedown="return rwt(this,'advertise',19)" href=https://maps.app.goo.gl/oiF5c4wXeHchPPqx7 target=_blank>
                                            <img src="<?php echo $template_path; ?>/assets/vnt_upload/weblink/no_image.jpg" alt="KH Khanh Nguyen" loading='lazy'>
                                          </a>
                                        </div>
                                    </div>
                                    <div class="rcol">
                                        <div class="idecss">
                                            <div class="dsname"><a onmousedown="return rwt(this,'advertise',19)" href=https://maps.app.goo.gl/oiF5c4wXeHchPPqx7 target=_blank>KH Khanh Nguyen</a></div>
                                            <div class="dschuc">Khách hàng</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                  
                    <div class="colsl">
                        <div class="ittetimohh">
                            <div class="hhtends">
                                Dịch vụ tốt, tiện lợi, sạch sẽ. Bác sĩ, nhân viên nhiệt tình, dễ gần Mình thấy thực hiện được nhiều kỹ thuật, xét nghiệm đa dạng. Mình có thắc mắc được giải đáp ngay.
                            </div>
                            <div class="hhinfos">
                                <div class="infgrip">
                                    <div class="lcol">
                                        <div class="ithumb">
                                          <a onmousedown="return rwt(this,'advertise',54)" href=https://maps.app.goo.gl/Q3QjGCYzL3Ym9sHa8 target=_blank>
                                            <img src="<?php echo $template_path; ?>/assets/vnt_upload/weblink/no_image.jpg" alt="KH Nga Pham" loading='lazy'>
                                          </a>
                                        </div>
                                    </div>
                                    <div class="rcol">
                                        <div class="idecss">
                                            <div class="dsname"><a onmousedown="return rwt(this,'advertise',54)" href=https://maps.app.goo.gl/Q3QjGCYzL3Ym9sHa8 target=_blank>KH Nga Pham</a></div>
                                            <div class="dschuc">Khách hàng</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                  
                </div>
            </div>
        </div>
    </div>
    
    
</div>
<?php include "footer.php";