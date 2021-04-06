  <!-- Bootstrap core JavaScript-->
  <script src="{{ asset('templates/sb-admin-2/vendor/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('templates/sb-admin-2/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

  <!-- Core plugin JavaScript-->
  <script src="{{ asset('templates/sb-admin-2/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

  <!-- Custom scripts for all pages-->
  <script src="{{ asset('templates/sb-admin-2/js/sb-admin-2.min.js') }}"></script>

  <!-- JavaScripts -->
  <script type="text/javascript">
  	$(function(){
	    // Tooltip
	    $('[data-toggle="tooltip"]').tooltip();
	    
	    // Logo Responsive
	    $(document).on("click", "#sidebarToggle", function(e){
	       e.preventDefault();
	       $("#logo-full").hasClass("d-none") ? $("#logo-full").removeClass("d-none d-md-block").addClass("d-block d-md-none") : $("#logo-full").removeClass("d-block d-md-none").addClass("d-none d-md-block") ;
	       $("#logo-mini").hasClass("d-block") ? $("#logo-mini").removeClass("d-block d-md-none").addClass("d-none d-md-block") : $("#logo-mini").removeClass("d-none d-md-block").addClass("d-block d-md-none") ;
	    });
	});
  </script>