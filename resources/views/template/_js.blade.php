<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
@if(is_int(strpos(Request::path(), 'tes')))
<script type="text/javascript">
	// Before Unload
	$(window).on("beforeunload", function(){
		var c = confirm();
		if(c) return true;
		else return false;
	});
</script>
@endif
<script type="text/javascript">
	// Submit form
	$(document).on("click", "#btn-submit", function(e){
		e.preventDefault();
		var ask = confirm("Anda ingin mengumpulkan tes yang telah dikerjakan?");
		if(ask){
			$(window).off("beforeunload");
			$("#form")[0].submit();
		}
	});
</script>