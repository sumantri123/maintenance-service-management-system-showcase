<!--navigation-->
<div class="primary-menu">
   <nav class="navbar navbar-expand-lg align-items-center">
	  <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
		<div class="offcanvas-header border-bottom">
			<div class="d-flex align-items-center">
				<div class="">
					<img src="{{asset('backend/images/arta.jpg') }}" class="logo-icon" alt="logo icon">
				</div>
				<div class="">
					<h4 class="logo-text">PT. Arta Utama Adijaya</h4>
				</div>
			</div>
		  <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
		</div>
		<div class="offcanvas-body">
		  <ul class="navbar-nav align-items-center flex-grow-1">							@foreach(App\Helpers\SiteHelpers::main_menu() as $mm)					<li class="nav-item dropdown">						<a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="javascript:;" data-bs-toggle="dropdown">							<div class="parent-icon"><i class='{{$mm->submenu_icon}}'></i></div>							<div class="menu-title d-flex align-items-center">{{$mm->submenu_nama}}</div>							<div class="ms-auto dropy-icon"><i class='bx bx-chevron-down'></i></div>						</a>						<ul class="dropdown-menu">							@foreach(App\Helpers\SiteHelpers::side_menu($mm->submenu_parent) as $sm)  								<li><a class="dropdown-item" href="{{ route($sm->submenu_link) }}"><i class='{{$sm->submenu_icon}}'></i>{{$sm->submenu_nama}}</a></li>							@endforeach									</ul>					</li>					@endforeach		  </ul>
		</div>
	  </div>
  </nav>
</div>
<!--end navigation-->