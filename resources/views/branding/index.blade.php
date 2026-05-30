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
<div class="card border-top border-0 border-primary">
	<div class="card-header bg-primary py-3">        
		<div class="row">			
			<div class="col-md-12">								
				<h2 class="text-white">&nbsp;{{$data['title']}}</h2>				
			</div>
		</div>
	</div>
	<div class="card-body">
		<div class="border border-primary p-3 rounded">
			<div id="invoice">            
				<div class="invoice">	
					<div class="row">
						<div class="col company-details">																												@if(hasPermission('branding','tambah',Session::get('role')))
								<button type="button" id="tambah" class="{{$data['btnClass']}}"><i class="lni lni-circle-plus"></i>Tambah</button><br /><br />							@endif
						</div>
					</div>
					<div class="table-responsive">
						<table id="aktivitas_datatable" class="table table-striped table-bordered" style="width:100%"></table>					    
					</div>
				</div>    
			</div>
		</div>
	</div>
</div>

<div class="modal fade modal-form" id="exampleLargeModal" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modal_label">Form Tambah / Ubah Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="form-horizontal form-label-left" method="post" id="data_form">
                <div class="modal-body">                
                    @csrf
					<input type="hidden" class="form-control" id="method_field" name="_method" value="POST" />
					<input readonly type="hidden" class="form-control" id="id" name="id">					
					<div id="error-validation"></div>
						<div class="row g-3">                        							
							<div class="col-md-12 col-sm-12">
								<label for="inputNote" class="form-label"><b>Tipe</b></label>                            
								<select id="branding_tipe" name="branding_tipe" class="{{$data['classForm']}} clear" required>
									<option value="">Pilih Tipe Branding</option>									
										<option value="1">Sanitasi</option>
										<option value="2">Service</option>
										<option value="3">Sanitasi & Service</option>
								</select>
							</div>
							<div class="col-md-12 col-sm-12">
								<label for="inputNote" class="form-label"><b>Nama Branding</b></label>                            
								<input type="text" class="{{$data['classForm']}}" id="branding_nama" name="branding_nama" required/>
							</div>							                         
						</div>                    
                </div>
                <div class="modal-footer">                    
                    <button type="submit" id="btn_simpan" class="btn btn-primary"><i class="lni lni-save"></i>Simpan</button>
                    <a href="javascript:void(0);" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection    

@push('scripts')    
<script>		
	var routeData = "{{ route('branding.data') }}";		
	var routeNonAktif = "{{ route('branding.nonaktif') }}";		
	var routeAktif = "{{ route('branding.aktif') }}";		
	var routeHapus = "{{ route('branding.hapus', ['id' => 'PARAMETER']) }}"; 		
	var routeSimpan = "{{ route('branding.save') }}";		
	var routeUbah = "{{ route('branding.ubah') }}";			
</script>
<script src="{{ asset('additional/js/setup/branding.js?v=1.00') }}"></script>
@endpush