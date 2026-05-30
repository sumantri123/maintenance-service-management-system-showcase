@extends('layout.default')
@section('content')
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	<div class="breadcrumb-title pe-3">{{$data['head']}}</div>
	<div class="ps-3">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0">
				<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
				<li class="breadcrumb-item active" aria-current="page">{{$data['title']}}</li>
			</ol>
		</nav>
	</div>
</div>
<hr/>
<div class="card border-top border-0 border-primary" id="headerJB">
	<form class="form-horizontal form-label-left" method="post" id="data_form">
		<div class="card-header bg-primary py-3">        
			<div class="row">			
				<div class="col-md-12">								
					<h2 class="text-white">&nbsp;{{$data['title']}}</h2>				
				</div>
			</div>
		</div>
		<div class="card-body" >                
			<div class="border border-primary p-3 rounded">                    
				@csrf                                     
				<div class="row g-3">                        
					<!--<div class="col-md-6 col-sm-12">
						<label class="form-label"><b>Tanggal Awal : </b></label>
						<input type="date" class="{{$data['classForm']}}" id="tgl_awal" name="tgl_awal" required/>
					</div>
					<div class="col-md-6 col-sm-12">
						<label class="form-label"><b>Tanggal Akhir : </b></label>
						<input type="date" class="{{$data['classForm']}}" id="tgl_akhir" name="tgl_akhir" required/>
					</div>-->
					<div class="col-md-6 col-sm-12">
						<label class="form-label"><b>Kriteria Pencarian : </b></label>                                   
						<input type="hidden" class="{{$data['classForm']}}" id="laporan" name="laporan" value="1" required/>						
						<select class="form select single-select" id="kriteria" name="kriteria" aria-label="Default select example">
							<option value="{{Crypt::encrypt(0)}}">Pilih Semua</option>
							<option value="{{Crypt::encrypt(1)}}">Nama Teknisi</option>
							<option value="{{Crypt::encrypt(2)}}">Outlet</option>
							<option value="{{Crypt::encrypt(3)}}">Permasalahan</option>
							<option value="{{Crypt::encrypt(4)}}">Area</option>
							<option value="{{Crypt::encrypt(5)}}">Aktivitas</option>
						</select>
					</div>
					<div class="col-md-6 col-sm-12">
						<label class="form-label"><small><b>(Masukkan Pencarian Disini)</b></small></label>
						<input type="text" class="{{$data['classForm']}}" id="param" name="param"/>
					</div>
				</div><br>
				<div class="toolbar hidden-print">
					<div class="text-start">
						<button type="button" id="btn_search" class="btn btn-primary btn-sm"><i class="bx bxs-search"></i> Cari Data</button>                
					</div>                
				</div>                            
			</div>
		</div>
	</form>
</div>
<div class="card border-top border-0 border-primary" id="contentJB">
	<form class="form-horizontal form-label-left" action="{{ url('/excelSanitasi') }}" method="post" id="formExport">
	@csrf
		<div class="card-body" >
			<div class="border p-3 rounded">
				<div class="toolbar hidden-print">
					<div class="text-end">
						<button type="button" id="btn_back" class="btn btn-primary btn-sm"><i class="bx bxs-arrow-from-right"></i> Kembali</button>                    
						<!--<button type="button" id="btn_print2" class="btn btn-dark btn-sm"><i class="bx bxs-printer"></i> Print</button>-->
						<button type="button" id="btn_excel" class="btn btn-warning btn-sm"><i class="bx bx-download"></i> Export Excel</button>
					</div>
					<hr/>
				</div>	
				<div class="table-responsive">
					<div class='invoice'>
						<input type="hidden" name="laporan" id="laporan_export">
						<input type="hidden" name="param" id="param_export">						<table id="dataTable" class="table table-striped table-bordered"></table>					</div>				</div>			</div>		</div>	</form></div>
@endsection    @push('scripts')   
<script>
	var routeData = "{{ route('reportsanitasi.data') }}";			
	var routeView = "{{ route('reportsanitasi.view', ['id' => 'PARAMETER']) }}"; 	
	var routePdf = "{{ route('reportsanitasi.pdf', ['id' => 'PARAMETER']) }}"; 	
	var routeHapus = "{{ route('reportsanitasi.hapus', ['id' => 'PARAMETER']) }}"; 		
</script> 
<script src="{{ asset('additional/js/report_sanitasi.js?v=1.15') }}"></script>
<script>
	$('.single-select').select2({
		theme: 'bootstrap4',
		width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
		placeholder: $(this).data('placeholder'),
		allowClear: Boolean($(this).data('allow-clear')),
	});	
</script>
@endpush