 <?php include "header.php";?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    $(document).ready(function(){
		 $.validator.addMethod("alpha", function(value, element){

        return this.optional(element) || value == value.match(/^[0-9, '']+$/);

    }, "Vui lòng nhập ký tự số!");
	$("#myForm").validate({
		onfocusout: false,
		onkeyup: false,
		onclick: false,
		rules: {
			"booking_person_name": {
				required: true
			},
			"booking_person_phone": {
				required: true
			},
			"booking_person_gender":{
				required: true
			},
			"booking_person_year":{
				required: true
			},
			"booking_person_address":{
				required: true
			}
		},
		messages:{
				booking_person_name: {
					required: "Bạn chưa nhập họ và tên"
				},
				booking_person_phone: {
					required: "Bạn chưa nhập số điện thoại"
				},
				booking_person_gender: "Bạn chưa chọn giới tính",
				booking_person_year: "Bạn chưa nhập năm sinh",
				booking_person_address: "Bạn chưa nhập địa chỉ"
				
			}
	});
		$('#btnBooking').click(function(e) {
			if ($("#myForm").valid()) {
				var formData = new FormData();
				formData.append('booking_person_name', $('#booking_person_name').val());
				formData.append('booking_person_phone', $('#booking_person_phone').val());
				formData.append('booking_person_gender', $('#booking_person_gender').val());
				formData.append('booking_person_year', $('#booking_person_year').val());
				formData.append('booking_person_address', $('#booking_person_address').val());
				formData.append('booking_doctor', $('#booking_doctor').val());
				formData.append('booking_date', $('#booking_date').val());
				formData.append('booking_hour', $('#booking_hour').val());
				formData.append('booking_description', $('#booking_description').val());
				
				
			$.ajax({
				type: "POST",
				url: "<?php echo XC_URL;?>/api/addBooking",
				data:formData,
				dataType: 'json',
				enctype: 'multipart/form-data',
				processData: false,
				contentType: false,
				success: function(data){
					if (data.status == 200) {
						console.log(data);
						let timerInterval;

						Swal.fire({
							icon: 'success',
							title: 'Đặt lịch thành công',
							html: data.message, // Bạn có thể thay bằng data.message
							footer: 'Hệ thống tự động chuyển hướng sau <b id="countdown" style="color:red; padding: 0 5px;">5</b> giây',
							timer: 5000,
							timerProgressBar: true,
							allowOutsideClick: false, // Ngăn người dùng tắt thông báo sớm
							didOpen: () => {
								const b = Swal.getFooter().querySelector('#countdown');
								let timeLeft = 10;
								timerInterval = setInterval(() => {
									timeLeft--;
									if (b) b.textContent = timeLeft;
								}, 1000);
							},
							willClose: () => {
								clearInterval(timerInterval);
							}
						}).then((result) => {
							// Sau 5 giây hoặc khi bấm OK sẽ nhảy về trang chủ
							window.location.href = '<?php echo XC_URL;?>'; // Thay index.php bằng link trang chủ của bạn
						});
					} else {
						Swal.fire({
							icon: 'error',
							title: 'Lỗi',
							text: data.message
						});
					}
				}
			});
		}
			
		});
	flatpickr("#booking_date", {
    dateFormat: "d/m/Y",
    minDate: "today", // Không cho chọn ngày quá khứ
    locale: "vn"      // Nếu muốn tiếng Việt
});
		

	});
	
</script>

    <div id="vnt-content" >
        <div class="gdmaintop ">
    
</div>
<div class="gdnavatio">
    <div id="vnt-navation" class="breadcrumb hidden-xs hidden-sm"><div class="wrapper"><div class="navation"><ul><li><a href="https://phongkhamdaiphuoc.vn/vn/"><span>Trang chủ</span></a></li><li><span>Đặt lịch khám</span></li></ul></div></div></div>
</div>
        <style type="text/css">.g-recaptcha > div{margin: 0 auto;}</style>
<link href="<?php echo $template_path; ?>/assets/js/material-picker/duDatepicker.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $template_path; ?>/assets/js/material-picker/duDatepicker-theme.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $template_path; ?>/assets/js/material-picker/duDatepicker.js"></script>

<div class="gdcontent">
    <div class="vhbookspg">
        <div class="wrapper">
            <div class="wraps">
                <div class="hpbookspg">
                    <div class="vnttitle vcolor vupper vcenter">
                        <div class="inline-block ">
                            <h1>Đặt lịch khám</h1>
                            
                        </div>
                    </div>
                    <div class="vntconts">
                        <div class="tpbooksmm">
                            <div class="tpbooksma ">
                                <form action="#" method="POST" id="myForm" onsubmit="return false;">
                                    <div class="mainput">
                                        <div class="magrip">
                                            <div class="hcol ">
                                                <div class="frmgroup div_input">
                                                    <input type="text" name="booking_person_name" class="required" value="" placeholder="Họ & tên (*)" autocomplete="off" id='booking_person_name'/>
                                                </div>
                                                
                                            </div>
                                            <div class="hcol ">
                                                <div class="frmgroup div_input">
                                                    <input type="text" name="booking_person_phone" class="format_number required" value="" minlength="10" maxlength="10" placeholder="Điện thoại (*)" autocomplete="off" id='booking_person_phone' />
                                                </div>
                                                
                                            </div>
                                            <div class="hcol ">
                                                <div class="frmgroup div_input">
                                                    <select name="booking_person_gender" size="1" class='chosen required' class="select form-control" id='booking_person_gender' >
                                                        <option value="">Giới tính (*)</option>
                                                        <option value="1" >Nam</option>
                                                        <option value="2" >Nữ</option>
													</select>
                                                </div>
                                                
                                            </div>
                                            <div class="hcol ">
                                                <div class="frmgroup div_input">
                                                    <input type="text" name="booking_person_year" class="format_number required" value="" minlength="4" maxlength="4" placeholder="Tuổi (VD: 1980) (*)" autocomplete="off" id='booking_person_year' />
                                                </div>
                                                
                                            </div>
                                            <div class="mcol ">
                                                <div class="frmgroup div_input">
                                                    <input type="text" name="booking_person_address" class="" value="" placeholder="Địa chỉ (*)" autocomplete="off" id="booking_person_address"/>
                                                </div>
                                                
                                            </div>
                                            
                                            <div class="hcol">
                                                <div class="frmgroup div_input" id="gptypebook">
                                                    <select name="booking_doctor" class="" id="booking_doctor">
                                                        <option value="">Chọn Bác sĩ (*)</option>
														<?php foreach($doctors as $doctor){?>
                                                        <option value="<?php echo $doctor->id; ?>"><?php echo $doctor->employee_name; ?></option>
															<?php }?>
                                                    </select>
                                                </div>
                                                
                                            </div>
                                            <div class="hcol row">
                                                <div class="frmgroup col-6 ncol">
                                                    <div class="frmgroup vdate fa-calendar-alt div_input">
                                                        <input type="text" name="date_book" id="booking_date" class="date_book " value="" minlength="10" maxlength="10" placeholder="Ngày khám *" autocomplete="off" />
                                        
													</div>  
                                                </div>
                                                <div class="frmgroup col-6 ncol vtime div_input">
                                                   <select name="hour_book" class="chosen-select hour_book" id="booking_hour">
                                                        <option value="">Giờ khám *</option>
                                                        <optgroup label="Buổi sáng">
                                                            <option value="07:00">07:00</option>
                                                            <option value="08:00">08:00</option>
                                                            <option value="09:00">09:00</option>
                                                            <option value="10:00">10:00</option>
                                                        </optgroup>
                                                        <optgroup label="Buổi chiều">
                                                            <option value="13:00">13:00</option>
                                                            <option value="14:00">14:00</option>
                                                            <option value="15:00">15:00</option>
                                                            <option value="15:00">14:00</option>
                                                        </optgroup>
                                                    </select>
                                                </div>
                                                
                                                
                                            </div>
                                            
                                            <div class="mcol ">
                                                <div class="frmgroup div_inpust" >
                                                    <textarea name="content" id="booking_description" placeholder="Nhập tình trạng sức khỏe của bạn, câu hỏi dành cho bác sĩ và các vấn đề sức khỏe cần khám"></textarea>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="manotes ">
                                        Ghi chú: Vui lòng điền thông tin đầy đủ của bệnh nhân
                                        
                                    </div>
                                    
                                    <div class="maboton ">
                                        <button type="submit" id="btnBooking"><span>XÁC NHẬN Đặt hẹn</span></button>
                                        <button type="reset" class="btnReset" name="btnReset" style="display: none;"></button>
                                        
                                    </div>
                                </form>
                                
                            </div>
                            <div class="tpbooksmb ">
                                <div class="mbtitle">
									

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
    
    
<?php include "footer.php"; ?>
