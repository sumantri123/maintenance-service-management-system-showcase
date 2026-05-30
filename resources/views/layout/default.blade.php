<!doctype html>

<html lang="en">

	<head>
		@include('layout.parts._head')
	</head>

	<body>
		
		<div class="wrapper">
			<div class="header-wrapper">	
				@include('layout.parts._header')
				@include('layout.parts._sidebar')	
			</div>					
			<div class="page-wrapper">
				<div class="page-content">
					@yield('content')						
					
				</div>
			</div>
			
			<div class="overlay toggle-icon"></div>			
			<a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>			
			<footer class="page-footer">
				<p class="mb-0">Copyright © 2023. All right reserved.</p>
			</footer>					
		</div>
		
		@include('layout.parts._scripts')
		
	</body>

</html>
