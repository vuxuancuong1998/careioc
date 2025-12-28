<?php include "header.php";?>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<link href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script>
   $(document).ready(function() {
       $('#summernote_gioithieu').summernote({
           placeholder: 'Bắt đầu soạn thảo: Bạn có thể kẻ bảng, chèn ảnh, link...',
           tabsize: 2,
           height: 400,
           toolbar: [
               // Các nhóm công cụ
               ['style', ['style']],
               ['font', ['bold', 'underline', 'clear', 'italic']],
               ['color', ['color']],
               ['para', ['ul', 'ol', 'paragraph']],
               ['table', ['table']], // Nút kẻ bảng
               ['insert', ['link', 'picture', 'video']], // Nút chèn link, ảnh, video
               ['view', ['fullscreen', 'codeview', 'help']]
           ]
       });


      $('#updatePage').click(function(e){
                  var userid = $('#uid').val();
                  var content = $('#summernote_gioithieu').summernote('code');
                  console.log(content);
                  var type_id = $('#tid').val();
               $.ajax({
                  type: "POST",
                  url: "<?php echo XC_URL;?>/api/updatePage",
                  data:{
                     'userid': userid,
                     'content': content,
                     'type_id': type_id
                     
                  },
                  dataType: 'json',
                  success: function(data){
                     if(data.status == 200){
                        Swal.fire({
                        icon: 'success',
                        title: "Thành công",
                        footer: '<a href=""></a>',
                        timer: 1700
                        })
                        setTimeout(function(){window.location.reload();     }, 2000);	
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
            <h3 class="page-title">Giới thiệu</h3>
            <ul class="breadcrumb">
               <li class="breadcrumb-item"><a href="index.html">Trang chủ</a></li>
               <li class="breadcrumb-item active">Giới thiệu</li>
            </ul>
         </div>
      </div>
   </div>
   <div class="row">
      <div class="col-lg-12">
         <div class="card">
            <div class="card-header">
               <h4 class="page-title">Giới thiệu</h4>
            </div>
            <div class="card-body">
               <form action="#">
               <div class="form-group row">
               <?php foreach($type as $type){?>
                <div class="col-md-2">
                  <a href='<?php echo XC_URL?>/admin/gioithieu/<?php echo $type->id;?>' class='btn btn-primary text-white' value='<?php echo $type->type_name;?>'><?php echo $type->type_name;?></a>
                </div>
                <?php }?>
                </div>
                  <div class="form-group row">
                     <div class="col-md-12">
                        <textarea id="summernote_gioithieu" name="editordata"><?php echo $introduce->introduce_content;?></textarea>
                        <input type='hidden' value='<?php echo $_SESSION['user']['id']; ?>' id = 'uid'/>
                        <input type='hidden' value = '<?php echo $id; ?>' id='tid' />
                        <button type="button" id ='updatePage' class="btn btn-primary">Lưu</button>
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