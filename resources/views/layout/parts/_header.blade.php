<header>
	<div class="topbar d-flex align-items-center">
		<nav class="navbar navbar-expand gap-3">
			<div class="topbar-logo-header d-none d-lg-flex">
				<div class="">
					<img src="{{asset('backend/images/arta.jpg') }}" class="logo-icon" alt="logo icon">
				</div>
				<div class="">
					<h4 class="logo-text">PT. Arta Utama Adijaya</h4>
				</div>
			</div>
			<div class="mobile-toggle-menu d-block d-lg-none" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"><i class='bx bx-menu'></i></div>
			<div class="position-relative search-bar d-lg-block d-none" data-bs-toggle="modal" data-bs-target="#SearchModal">
				<input class="form-control px-5" type="search" placeholder="Search">
				<span class="position-absolute top-50 search-show ms-3 translate-middle-y start-0 top-50 fs-5"><i class='bx bx-search'></i></span>
			  </div>
			  <div class="top-menu ms-auto">
				<ul class="navbar-nav align-items-center gap-1">
					<li class="nav-item mobile-search-icon d-flex d-lg-none" data-bs-toggle="modal" data-bs-target="#SearchModal">
						<a class="nav-link" href="avascript:;"><i class='bx bx-search'></i>
						</a>
					</li>										
					<li class="nav-item dropdown dropdown-app">
						<a class="nav-link dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown" href="javascript:;"><i class='bx bx-grid-alt'></i></a>
						<div class="dropdown-menu dropdown-menu-end p-0">
							<div class="app-container p-2 my-2">
							  <div class="row gx-0 gy-2 row-cols-3 justify-content-center p-2">
							  </div><!--end row-->
		
							</div>
						</div>
					</li>

					<li class="nav-item dropdown dropdown-large">
						<a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" data-bs-toggle="dropdown"><span class="alert-count"></span>
							<i class='bx bx-bell'></i>
						</a>
						
					</li>
					<li class="nav-item dropdown dropdown-large">
						<a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"> <span class="alert-count"></span>
							<i class='bx bx-shopping-bag'></i>
						</a>
						
					</li>
				</ul>
			</div>
			<div class="user-box dropdown px-3">
				<a class="d-flex align-items-center nav-link dropdown-toggle gap-3 dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
					<img src="{{asset('backend/images/avatars/avatar-2.png') }}" class="user-img" alt="user avatar">
					<div class="user-info">
						<p class="user-name mb-0">{{Session::get('nama')}}</p>
						<p class="designattion mb-0">PT. Arta UtamaAdijaya</p>
					</div>
				</a>
				<ul class="dropdown-menu dropdown-menu-end">
					<li><a class="dropdown-item d-flex align-items-center" href="{{route('reset_password')}}"><i class="bx bx-download fs-5"></i><span>Ubah Password</span></a></li>
					<li><div class="dropdown-divider mb-0"></div></li>								
					<li><a class="dropdown-item d-flex align-items-center" href="{{route('logout')}}"><i class="bx bx-log-out-circle"></i><span>Logout</span></a></li>
				</ul>
			</div>
		</nav>
	</div>
</header>