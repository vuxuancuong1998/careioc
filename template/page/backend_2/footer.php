</div>
    <!-- Wrapper End-->
    <footer class="iq-footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6">
                    <ul class="list-inline mb-0">
                        <li class="list-inline-item"><a href="privacy-policy.html">Privacy Policy</a></li>
                        <li class="list-inline-item"><a href="terms-of-service.html">Terms of Use</a></li>
                    </ul>
                </div>
                <div class="col-lg-6 text-right">
                    <span class="mr-1">
                        Copyright
                        <script>document.write(new Date().getFullYear())</script>© <a href="https://clouderp.vn" class="">HiSpa</a>
                        All Rights Reserved.
                    </span>
                </div>
            </div>
        </div>
    </footer>    <!-- Backend Bundle JavaScript -->
    
    
    <!-- Flextree Javascript-->
    <script src="<?php echo $template_path; ?>/backend/assets/js/flex-tree.min.js"></script>
    <script src="<?php echo $template_path; ?>/backend/assets/js/tree.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.3/moment.min.js"></script>
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js" integrity="sha512-Y+0b10RbVUTf3Mi0EgJue0FoheNzentTMMIE2OreNbqnUPNbQj8zmjK3fs5D2WhQeGWIem2G2UkKjAL/bJ/UXQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- Table Treeview JavaScript -->
    <script src="<?php echo $template_path; ?>/backend/assets/js/table-treeview.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.4/jquery.validate.min.js" integrity="sha512-FOhq9HThdn7ltbK8abmGn60A/EMtEzIzv1rvuh+DqzJtSGq8BRdEN0U+j0iKEIffiw/yEtVuladk6rsG4X6Uqg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- SweetAlert JavaScript -->
    <script src="<?php echo $template_path; ?>/backend/assets/js/sweetalert.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    
    <!-- Vectoe Map JavaScript -->
    <script src="<?php echo $template_path; ?>/backend/assets/js/vector-map-custom.js"></script>
    
    <!-- Chart Custom JavaScript -->
    <script src="<?php echo $template_path; ?>/backend/assets/js/chart-custom.js"></script>
    <script src="<?php echo $template_path; ?>/backend/assets/js/charts/01.js"></script>
    <script src="<?php echo $template_path; ?>/backend/assets/js/charts/02.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js" integrity="sha512-8QFTrG0oeOiyWo/VM9Y8kgxdlCryqhIxVeRpWSezdRRAvarxVtwLnGroJgnVW9/XBRduxO/z1GblzPrMQoeuew==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- slider JavaScript -->
    <script src="<?php echo $template_path; ?>/backend/assets/js/slider.js"></script>
    
    
    
    <!-- app JavaScript -->
    <script src="<?php echo $template_path; ?>/backend/assets/js/app.js"></script>  
	<script src="<?php echo $template_path; ?>/backend/assets/vendor/ckeditor5/build/ckeditor.js"></script>
	<script>
		ClassicEditor.create(document.querySelector('.text-editor'), 
		{
			licenseKey: ''
		}).then( editor => 
		{
			window.editor = editor;
		}).catch( error => 
		{
			console.error( 'Oops, something went wrong!' );
			console.error( 'Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:' );
			console.warn( 'Build id: 5xr5skecoao3-9qh4febwg8gq' );
			console.error( error );
		});
	</script>
	<script>
		$(document).ready(function() {
			$('.dropify').dropify();
		});
		</script>
	</body>

</html>