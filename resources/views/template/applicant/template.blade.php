<!DOCTYPE html>
<html lang="en">

<head>

	@include('template/applicant/_head')

	@yield('css-extra')

	<title>Tes PersonalityTalk</title>

</head>

<body id="page-top">

	<!-- Page Wrapper -->
	<div id="wrapper">

		@include('template/applicant/_sidebar')

		<!-- Content Wrapper -->
		<div id="content-wrapper" class="d-flex flex-column">

			<!-- Main Content -->
			<div id="content">

				@include('template/applicant/_topbar')

		        <!-- Begin Page Content -->
		        <div class="container-fluid">

		        	@yield('content')

		        </div>
		        <!-- /.container-fluid -->

			</div>
			<!-- End of Main Content -->

	  		@include('template/applicant/_footer')

	    </div>
	    <!-- End of Content Wrapper -->

	</div>
	<!-- End of Page Wrapper -->

	@include('template/applicant/_scroll-to-top-button')

	@include('template/applicant/_logout-modal')

	@include('template/applicant/_js')

	@yield('js-extra')

</body>

</html>
