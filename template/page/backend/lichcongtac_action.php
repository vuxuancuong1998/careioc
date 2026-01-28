<?php include "header.php";?>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<link href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script>
   $(document).ready(function() {
       $('#summernote_calendar_work').summernote({
           placeholder: 'Bắt đầu soạn thảo: Bạn có thể kẻ bảng, chèn ảnh, link...',
           tabsize: 2,
           height: 400,
           toolbar: [
               // Các nhóm công cụ
               ['style', ['style']],
               ['font', ['bold', 'underline', 'clear', 'italic']],
                ['fontname', ['fontname']],   // chọn font chữ
         ['fontsize', ['fontsize']],   // chọn cỡ chữ
               ['color', ['color']],
               ['para', ['ul', 'ol', 'paragraph']],
               ['table', ['table']], // Nút kẻ bảng
               ['insert', ['link', 'picture', 'video']], // Nút chèn link, ảnh, video
               ['view', ['fullscreen', 'codeview', 'help']]
           ]
       });
            $('#sendDB').click(function(e) {
			// if ($("#frm-action").valid()) {
                var content = $('#summernote_calendar_work').summernote('code');
                var method =  $('#method').val();

                // console.log(method);
				var formData = new FormData();
				formData.append('calendar_work_name', $('#calendar_work_name').val());
				formData.append('calendar_work_description', content);
				formData.append('wid', $('#id').val());
				formData.append('uid', $('#uid').val());
				formData.append('calendar_work_to', $('#calendar_work_to').val());
				formData.append('calendar_work_from', $('#calendar_work_from').val());
				formData.append('method',method);
                
				// Upload file
				var file = $('#calendar_work_file')[0].files[0];
                // console.log(file);
				if (file) {
					formData.append('calendar_work_file', file);
				}
			$.ajax({
				type: "POST",
				url: "<?php echo XC_URL;?>/api/calendar_works",
				data:formData,
				dataType: 'json',
				enctype: 'multipart/form-data',
				processData: false,
				contentType: false,
				success: function(data){
					if(data.status == 200){						
						Swal.fire({
						  icon: 'success',
						  title: data.message,
						  footer: '<a href=""></a>',
						  timer: 1700
						})
						setTimeout(function(){ window.location.href=data.returnUrl;     }, 2000);
					}else{
						Swal.fire({
						  icon: 'error',
						  title: "Lỗi",
						  text: data.message,
						  footer: '<a href=""></a>'
						})
					}
				}
			});
			// }
		});

   });
   // function getContent() {
   //     var content = $('#summernote_gioithieu').summernote('code');
   //    //  alert("Dữ liệu HTML bạn vừa nhập:\n" + content);
   //    //  console.log(content);
   // }

   
</script>
<div class="content container-fluid">
   <div class="page-header">
      <div class="row">
         <div class="col">
            <h3 class="page-title"><?php echo $method == 'add' ? "Thêm mới ".$category_name : "Chỉnh sửa ".$category_name;?></h3>
            <ul class="breadcrumb">
               <li class="breadcrumb-item"><a href="index.html">Trang chủ</a></li>
               <li class="breadcrumb-item active"><?php echo $method == 'add' ? "Thêm mới" : "Chỉnh sửa";?></li>
            </ul>
         </div>
      </div>
   </div>
   <div class="row">
      <div class="col-lg-12">
         <div class="card">
            <div class="card-header">
               <h4 class="page-title">Thông tin chung</h4>
            </div>
            <div class="card-body">
               <form action="#">
                <div class="form-group row">
                     <div class="col-md-2">
                       <label>Tiêu đề</label><span class='text-danger'>*</span>
                     </div>
                     <div class="col-md-10">
                       <input type = 'text' id='calendar_work_name' name='calendar_work_name' class='form-control' value='<?php echo $calendar_work_detail->calendar_work_name ?? '';?>'/>
                     </div>
                </div>
                <div class="form-group row">
                        <div class="col-md-2">
                             <label>Từ ngày</label><span class='text-danger'>*</span>
                        </div>
                        <div class="col-md-4">
                             <input type="date" class="form-control" id='calendar_work_from' value='<?= isset($calendar_work_detail->calendar_work_to_date)
                            ? date('Y-m-d', strtotime($calendar_work_detail->calendar_work_to_date))
                            : '' ?>'/>
                                            </div>
                                            <div class="col-md-2">
                                                <label>Đến ngày</label><span class='text-danger' >*</span>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="date" class="form-control" id='calendar_work_to' value='<?= isset($calendar_work_detail->calendar_work_from_date)
                            ? date('Y-m-d', strtotime($calendar_work_detail->calendar_work_from_date))
                            : '' ?>'/>
                        </div>
                     
                </div>

                
                <div class="form-group row">
                     <div class="col-md-2">
                       <label>File</label><span class='text-danger'>*</span>
                     </div>
                     <?php if(isset($calendar_work_detail->calendar_work_file)){ ?>
                       <div class="col-md-5">
                       <input type = 'file' id='calendar_work_file' name='calendar_work_file' class='form-control' value=''/>
                     </div>
                     <div class="col-md-5">
                        <a class="btn btn-outline-primary" target="_blank" href="view.php?file=FILE.pdf">
                                <i class="fa fa-eye"></i> Xem
                            </a>
                            <a class="btn btn-outline-success" href="download.php?file=FILE.pdf">
                                <i class="fa fa-download"></i> Tải về
                            </a>
                     </div>
                     <?php }else{?>
                     <div class="col-md-10">
                       <input type = 'file' id='calendar_work_file' name='calendar_work_file' class='form-control' value=''/>
                     </div>
                     <?php } ?>
                </div>
                    <div class="form-group row">
                     <div class="col-md-12">
                        <?php echo XC_URL; ?>/uploads/files/<?php echo  $calendar_work_detail->calendar_work_file; ?>
                        <iframe
                    src="/pdfjs/web/viewer.html?file=http://localhost/caodangkontum.edu.vn/uploads/files/770753b334723db19fbb7a2300a4d669-rp_phieunhapvien_1769486261.pdf>"
                    width="100%" height="600px">
                    </iframe>

                     </div>
                  </div>
                  <div class="form-group row">
                     <div class="col-md-12">
                        <textarea id="summernote_calendar_work" name="editordata"><?php echo $calendar_work_detail->calendar_work_description ?? '';?></textarea>
                        <input type='hidden' value='<?php echo $_SESSION['user']['id']; ?>' id = 'uid'/>
                        <input type='hidden' value = '<?php echo $calendar_work_detail->id; ?>' id='wid' />
                        <input type='hidden' value = '<?php echo $method; ?>' id='method' />
                        <button type="button" id ='sendDB' class="btn btn-primary">Lưu</button>
                     </div>
                  </div>
            </div>
            </form>
         </div>
      </div>
    </div>
   </div>
</div>
</div>
</div>
</div>
<script>
   ClassicEditor.create(document.querySelector('#content'));
</script>
<?php include "footer.php";?>