<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
@if(is_int(strpos(Request::path(), 'tes')))
<script type="text/javascript">
	// Before Unload
	window.addEventListener("beforeunload", j);
	function j(e){
	    e.preventDefault();
	    e.returnValue = '';
	}

	// Unload
	window.addEventListener("unload", function(e){
		console.log("Sayonara...");
	});
</script>
@endif
<script type="text/javascript">
	// Log out
	$(document).on("click", "#btn-logout", function(e){
		e.preventDefault();
		var ask = confirm("Anda yakin ingin keluar?");
		if(ask){
			window.removeEventListener("beforeunload", j);
			$("#form-logout")[0].submit();
		}
	});

	// Next form
	$(document).on("click", "#btn-next", function(e){
		e.preventDefault();
		var ask = confirm("Anda ingin melanjutkan ke bagian selanjutnya?");
		if(ask){
			window.removeEventListener("beforeunload", j);
			$("input[name=is_submitted]").val(0);
			$("#form")[0].submit();
		}
	});

	// Submit form
	$(document).on("click", "#btn-submit", function(e){
		e.preventDefault();
		var ask = confirm("Anda yakin ingin mengumpulkan tes yang telah dikerjakan?");
		if(ask){
			window.removeEventListener("beforeunload", j);
			$("#form")[0].submit();
		}
	});
</script>