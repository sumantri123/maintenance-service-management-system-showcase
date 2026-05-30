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
						<div class="col company-details">							@if(hasPermission('area','tambah',Session::get('role')))
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
								<label for="inputNote" class="form-label"><b>Area</b></label>                            
								<input type="text" class="{{$data['classForm']}}" id="area_nama" name="area_nama" required/>
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
	var routeData = "{{ route('area.data') }}";		
	var routeNonAktif = "{{ route('area.nonaktif') }}";		
	var routeAktif = "{{ route('area.aktif') }}";		
	var routeHapus = "{{ route('area.hapus', ['id' => 'PARAMETER']) }}"; 		
	var routeSimpan = "{{ route('area.save') }}";		
	var routeUbah = "{{ route('area.ubah') }}";			
</script>
<script src="{{ asset('additional/js/setup/area.js?v=1.01') }}"></script>
@endpush