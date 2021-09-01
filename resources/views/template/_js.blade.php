<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
{{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script> --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script type="text/javascript">
	// $('[data-bs-toggle="tooltip"]').tooltip();

	// Tooltip
	var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
	var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
		return new bootstrap.Tooltip(tooltipTriggerEl);
	});

	function j(e){
	    e.preventDefault();
	    e.returnValue = '';
	}
</script>
@if(is_int(strpos(Request::path(), 'tes')) && !is_int(strpos(Request::path(), 'admin/tes')))
<script type="text/javascript">
	// Before Unload
	window.addEventListener("beforeunload", j);

	// Unload
	window.addEventListener("unload", function(e){
		console.log("Bye bye");
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