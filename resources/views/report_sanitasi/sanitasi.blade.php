<!doctype html>

<html lang="en">

	<script>
		var base_url = window.location.origin;
	</script>
	
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">	
		<meta name="description" content="PT. Arta Utama Adijaya">
		<meta name="keywords" content="PT. Arta Utama Adijaya">	
		<meta name="csrf-token" content="{{ csrf_token() }}">	
		<link rel="icon" href="{{asset('backend/images/arta.jpg') }}" type="image/png" />			
		<link href="{{ mix('backend/mix/frontend.css') }}" rel="stylesheet">	
		<link href="{{asset('backend/plugins/notifications/css/lobibox.min.css') }}" rel="stylesheet"/>						
		<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">											
		<link rel="stylesheet" href="{{asset('backend/css/flatpickr.min.css') }}" />		
		<title>PT. Arta Utama Adijaya</title>
	</head>	

	<body>			
		<div class="wrapper">			
			<div class="page-content">
				<style>
					#map { height: 300px; margin-top: 10px; }
				</style>
				
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
						<table>
							<tr>
								<td><img src="{{asset('backend/images/arta_white.png')}}" alt="Logo Perusahaan" width="70px"></td>
								<td class="text-left"><h2 class="text-white">{{$data['title']}}</h2></td>
							</tr>
						</table>
					</div>
					<div class="card-body">
						<div class="border border-primary px-4 py-3 rounded">			
							<form class="form-horizontal form-label-left" id="form_upload" method="post" enctype="multipart/form-data">
								@csrf
								<input type="hidden" class="{{$data['classForm']}}" id="method_field" name="_method" value="POST" />
								<input type="hidden" class="{{$data['classForm']}}" id="id" value="{{Crypt::encrypt($data['sanitasi'][0]->sanitasi_id)}}" name="id">
								<div id="error-validation"></div>
								<div class="row g-3">
									<div class="col-md-12 col-sm-12">
										<label class="form-label"><b>Nama Teknisi&nbsp;<span style="color:red">*</span></b></label>
										<select id="nama_teknisi" name="nama_teknisi" class="{{$data['classFormSelect2']}} clear" onchange="getArea(this)" required>
											<option value="">Pilih Nama Teknisi</option>
											@foreach ($data['teknisi'] as $teknisi)
												<option value="{{Crypt::encryptString($teknisi->pgw_id)}}" {{ $teknisi->pgw_id == $data['sanitasi'][0]->id_pgw ? 'selected' : '' }} >{{$teknisi->pgw_nama}}</option>
											@endforeach	
										</select>						
									</div>	
									<div class="col-md-6 col-sm-12">
										<label class="form-label"><b>Area&nbsp;<span style="color:red">*</span></b></label>
										<input type="hidden" class="{{$data['classForm']}}" id="area_id" name="area_id" value="{{ $data['sanitasi'][0]->id_area ?? ''}}" required />
										<select id="area" name="area" class="{{$data['classFormSelect2']}} clear" required>
											<option value="">Pilih Area</option>							
										</select>						
									</div>
									<div class="col-md-6 col-sm-12">
										<label class="form-label"><b>Aktivitas&nbsp;<span style="color:red">*</span></b></label>						
										<select id="aktivitas" name="aktivitas" class="{{$data['classFormSelect2']}} clear" required>
											<option value="">Pilih Aktivitas</option>
											@foreach ($data['aktivitas'] as $aktivitas)
												<option value="{{Crypt::encryptString($aktivitas->aktivitas_id)}}" {{ $aktivitas->aktivitas_id == $data['sanitasi'][0]->id_aktivitas ? 'selected' : '' }}>{{$aktivitas->aktivitas_nama}}</option>
											@endforeach	
										</select>						
									</div>
									<div class="col-md-6 col-sm-12">
										<label class="form-label"><b>Bulan&nbsp;<span style="color:red">*</span></b></label>														
										<input  type="month" class="{{$data['classForm']}}" id="bulan" name="bulan" value="{{ $data['sanitasi'][0]->bulan ?? ''}}" required />
									</div>
									<div class="col-md-6 col-sm-12">
										<label  class="form-label"><b>Tanggal Permintaan Sercive&nbsp;<span style="color:red">*</span></b></label>						
										<input  type="date" class="{{$data['classForm']}}" id="tanggal" name="tanggal" value="{{ $data['sanitasi'][0]->tgl_permintaan_service ?? ''}}" required />
									</div>
									<div class="col-md-6 col-sm-12">
										<label  class="form-label"><b>Berita Dari&nbsp;<span style="color:red">*</span></b></label>					
										<select id="berita_dari" name="berita_dari" class="{{$data['classFormSelect2']}}" required>
											<option value="">Berita Dari</option>
											<option value="Aktivitas Rutin" {{ ('Aktivitas Rutin' == $data['sanitasi'][0]->berita_dari) ? 'selected' : '' }}>Aktivitas Rutin</option>
											<option value="Email" {{ ('Email' == $data['sanitasi'][0]->berita_dari) ? 'selected' : '' }}>Email</option>
											<option value="Phone/Whatsapp" {{ ('Phone/Whatsapp' == $data['sanitasi'][0]->berita_dari) ? 'selected' : '' }}>Phone / Whatsapp</option>							
										</select>						
									</div>
									<div class="col-md-6 col-sm-12">
										<label  class="form-label"><b>No. Telp / Email Pelaporan</b></label>						
										<input  type="text" class="{{$data['classForm']}}" id="telp" name="telp" value="{{ $data['sanitasi'][0]->no_telp_email ?? ''}}"  />
									</div>
									<div class="col-md-12 col-sm-12">
										<label  class="form-label"><b>Informasi / Masalah Yang Dilaporkan</b></label>						
										<input  type="text" class="{{$data['classForm']}}" id="informasi_laporan" name="informasi_laporan" value="{{ $data['sanitasi'][0]->masalah_pelaporan ?? ''}}"/>															
									</div>
									<div class="col-md-6 col-sm-12">
										<label  class="form-label"><b>Nama Outlet&nbsp;<span style="color:red">*</span></b></label>					
										<input  type="text" class="{{$data['classForm']}}" id="nama_outlet" name="nama_outlet" value="{{ $data['sanitasi'][0]->outlet_nama ?? ''}}" placeholder="" required/>
									</div>
									<div class="col-md-6 col-sm-12">
										<label  class="form-label"><b>Alamat Outlet&nbsp;<span style="color:red">*</span></b></label>						
										<input  type="text" class="{{$data['classForm']}}" id="alamat_outlet" name="alamat_outlet" value="{{ $data['sanitasi'][0]->outlet_alamat ?? ''}}" placeholder="" required />
									</div>
									<div class="col-md-4 col-sm-12">
										<label  class="form-label"><b>Tanggal Service&nbsp;<span style="color:red">*</span></b></label>					
										<input  type="date" class="{{$data['classForm']}}" id="tgl_service" name="tgl_service" value="{{ $data['sanitasi'][0]->tgl_service ?? ''}}" placeholder="" required />
									</div>
									<div class="col-md-4 col-sm-12">
										<label  class="form-label"><b>Waktu Mulai&nbsp;<span style="color:red">*</span></b></label>						
										<input  type="time" class="{{$data['classForm']}}" id="waktu_mulai" name="waktu_mulai" value="{{ $data['sanitasi'][0]->jam_mulai ?? ''}}" placeholder="" required />
									</div>
									<div class="col-md-4 col-sm-12">
										<label  class="form-label"><b>Waktu Selesai&nbsp;<span style="color:red">*</span></b></label>						
										<input  type="time" class="{{$data['classForm']}}" id="waktu_selesai" name="waktu_selesai" value="{{ $data['sanitasi'][0]->jam_selesai ?? ''}}" placeholder="" required />
									</div>
									<div class="col-md-6 col-sm-12">
										<label  class="form-label"><b>Tipe Mesin&nbsp;<span style="color:red">*</span></b></label>					
										<select id="tipe_mesin" name="tipe_mesin" class="{{$data['classFormSelect2']}}" required>
											<option value="">Pilih Tipe Mesin</option>
											<option value="mobile" {{ ('mobile Rutin' == $data['sanitasi'][0]->tipe_mesin) ? 'selected' : '' }}>Mobile</option>
											<option value="under counter" {{ ('under counter' == $data['sanitasi'][0]->tipe_mesin) ? 'selected' : '' }}>Under Counter</option>
											<option value="david 20 l" {{ ('david 20 l' == $data['sanitasi'][0]->tipe_mesin) ? 'selected' : '' }}>David 20L</option>
											<option value="chiller" {{ ('chiller' == $data['sanitasi'][0]->tipe_mesin) ? 'selected' : '' }}>Chiller</option>
										</select>
									</div>
									<div class="col-md-6 col-sm-12">
										<label  class="form-label"><b>Nomor Tagging&nbsp;<span style="color:red">*</span></b></label>						
										<input  type="text" class="{{$data['classForm']}}" id="nomor_tagging" name="nomor_tagging" value="{{ $data['sanitasi'][0]->no_tagging ?? ''}}" placeholder="Nomor Tagging Y4A10-DBM/REG/XXXX" required/>
									</div>
									<div class="col-md-6 col-sm-12">
										<label  class="form-label"><b>Nomor Seri Mesin&nbsp;<span style="color:red">*</span></b></label>					
										<input  type="text" class="{{$data['classForm']}}" id="nomor_mesin" name="nomor_mesin" value="{{ $data['sanitasi'][0]->no_seri_mesin ?? ''}}" placeholder="NOMOR SERIAL MESIN ADA DIBAWAH BARCODE" required />
									</div>
									<div class="col-md-6 col-sm-12">
										<label  class="form-label"><b>Nomor Ring CO2 / Kapasitas Tekanan</b></label>						
										<input  type="text" class="{{$data['classForm']}}" id="nomor_ring" name="nomor_ring" value="{{ $data['sanitasi'][0]->kapasitas_tekanan ?? ''}}" placeholder="" />
									</div>
									<div class="col-md-12 col-sm-12">
										<label  class="form-label"><b>Alamat&nbsp;<span style="color:red">*</span></b></label>						
										<input disabled type="text" class="{{$data['classForm']}}" id="alamatInput" name="alamatInput" value="{{ $data['sanitasi'][0]->sanitasi_alamat_map ?? ''}}"/>
									</div>
									<div class="col-md-6 col-sm-12">
										<label  class="form-label"><b>Latitude&nbsp;<span style="color:red">*</span></b></label>					
										<input disabled type="text" class="{{$data['classForm']}}" name="lat" id="lat" value="{{ $data['sanitasi'][0]->sanitasi_latitude ?? ''}}" >						
									</div>
									<div class="col-md-6 col-sm-12">
										<label  class="form-label"><b>Longitude&nbsp;<span style="color:red">*</span></b></label>						
										<input disabled type="text" class="{{$data['classForm']}}" name="lng" id="lng" value="{{ $data['sanitasi'][0]->sanitasi_longitude ?? ''}}" >						
									</div>	
									
									<div class="col-md-12 col-sm-12">
										<label  class="form-label"><b>Branding&nbsp;<span style="color:red">*</span></b></label>	
										@foreach ($data['branding'] as $branding)						
											<div class="form-check">
												<input class="form-check-input branding" type="checkbox" value="{{$branding->branding_nama}}" id="branding" name="branding[]" {{ in_array($branding->branding_nama, $data['selectedBranding']) ? 'checked' : '' }}>
												<label class="form-check-label" for="flexCheckDefault">{{$branding->branding_nama}}</label>
											</div>						
										@endforeach
										<input  type="text" class="{{$data['classForm']}}" name="other_branding" id="other_branding" value="{{$data['sanitasi'][0]->other_branding ?? ""}}" placeholder="Masukkan Other Branding Disini">
									</div>
									<div class="col-md-12 col-sm-12">
										<label  class="form-label"><b>Pergantian Sparepart</b></label>
										<div class="row">
											@foreach ($data['sparepart'] as $sparepart)
												<div class="col-md-4">
													<div class="form-check form-check-success">
														<input class="form-check-input sparepart" type="checkbox" value="{{$sparepart->sparepart_nama}}" id="sparepart" name="sparepart[]" {{ in_array($sparepart->sparepart_nama, $data['selectedSparepart']) ? 'checked' : '' }}>
														<label class="form-check-label" for="flexCheckBeerLine">
														  {{$sparepart->sparepart_nama}}
														</label>
													</div>
												</div>
											@endforeach										
										</div>
										<input  type="text" class="{{$data['classForm']}}" name="other_sparepart" id="other_sparepart" value="{{$data['sanitasi'][0]->other_sparepart ?? ""}}" placeholder="Masukkan Other Sparepart Disini">
									</div>					
									<div class="col-md-12 col-sm-12">
										<label  class="form-label"><b>Type Barel : Nomor Barel / Tanggal Best Before</b></label>
										<input  type="text" class="{{$data['classForm']}}" name="no_barel" id="no_barel" placeholder="HNK30L : 4114 /BB 270824" value="{{ $data['sanitasi'][0]->type_barel ?? ''}}">
									</div>
									<div class="col-md-12 col-sm-12">
										<label  class="form-label"><b>Informasi dan Tindakan&nbsp;<span style="color:red">*</span></b></label>
										<input  type="text" class="{{$data['classForm']}}" name="informasi_tindakan" id="informasi_tindakan" required value="{{ $data['sanitasi'][0]->tindakan ?? ''}}">
									</div>
									<div class="col-md-6 col-sm-12">
										<label  class="form-label"><b>Nama PIC Pihat Outlet&nbsp;<span style="color:red">*</span></b></label>					
										<input  type="text" class="{{$data['classForm']}}" name="nama_pic_outlet" id="nama_pic_outlet" required value="{{ $data['sanitasi'][0]->outlet_pic ?? ''}}">
									</div>
									<div class="col-md-6 col-sm-12">
										<label  class="form-label"><b>Posisi Jabatan PIC Pihak Outlet&nbsp;<span style="color:red">*</span></b></label>						
										<input  type="text" class="{{$data['classForm']}}" name="jabatan_pic_outlet" id="jabatan_pic_outlet" required value="{{ $data['sanitasi'][0]->outlet_pic_jabatan ?? ''}}">
									</div>					
									<div class="card bg-primary " >
										<div class="card-body">
											<div class="row">
												<div class="col-md-4 col-sm-12">
													<label  class="form-label text-white"><b>Jumlah Tabung CO2 Terpasang</b></label>						
													<input  type="text" class="{{$data['classForm']}}" name="co2_terpasang" id="co2_terpasang" placeholder="Co2 Terpasang" value="{{ $data['sanitasi'][0]->co2_terpasang ?? ''}}">
												</div>
												<div class="col-md-4 col-sm-12">
													<label  class="form-label text-white"><b>Jmlh Tabung CO2 Penuh Cadangan</b></label>						
													<input  type="text" class="{{$data['classForm']}}" name="co2_cadangan" id="co2_cadangan" placeholder="Co2 Cadangan (Tdk terpasang)" value="{{ $data['sanitasi'][0]->co2_cadangan ?? ''}}">
												</div>
												<div class="col-md-4 col-sm-12">
													<label  class="form-label text-white"><b>Jumlah Tabung CO2 Kosong</b></label>						
													<input  type="text" class="{{$data['classForm']}}" name="co2_kosong" id="co2_kosong" placeholder="Co2 Kosong" value="{{ $data['sanitasi'][0]->co2_kosong ?? ''}}">
												</div>
											</div>
										</div>
									</div>					
									<div class="col-md-6 col-sm-12">
										<label  class="form-label"><b>Tanda Tangan PIC Outlet&nbsp;<span style="color:red">*</span></b></label><br>
										@if(!empty($data['sanitasi'][0]->ttd_pic_path))
											<img src="{{ asset('storage/'.$data['sanitasi'][0]->ttd_pic_path) }}"  class="img-fluid rounded shadow-sm" >
										@else
											<p class="text-muted">Belum ada tanda tangan</p>
										@endif
									</div>
									<div class="col-md-6 col-sm-12">
										<label  class="form-label"><b>Tanda Tangan Teknisi&nbsp;<span style="color:red">*</span></b></label>
										@if(!empty($data['sanitasi'][0]->ttd_teknisi_path))
											<img src="{{ asset('storage/'.$data['sanitasi'][0]->ttd_teknisi_path) }}"  class="img-fluid rounded shadow-sm" >
										@else
											<p class="text-muted">Belum ada tanda tangan</p>
										@endif
									</div>
									<div class="col-md-12 col-sm-12 text-center">
										<button type="submit" id="btnSimpan" class="btn btn-primary px-5 radius-30">Simpan</button>
									</div>
								</div>														
							</form>			
						</div>
					</div>
				</div>
			</div>
			<div class="overlay toggle-icon"></div>			
			<a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>			
			<footer class="page-footer">
				<p class="mb-0">PT. Arta Utama Adijaya</p>
			</footer>					
		</div>														
		<script src="{{ mix('backend/mix/frontend.js') }}"></script>			
		<script src="{{ asset('additional/js/lap_update_sanitasi.js') }}"></script>				
		<script src="{{ asset('backend/js/flatpickr.js') }}"></script>						
		<script>		
			var routeUpdate = "{{ route('reportsanitasi.update') }}";
			var routeArea = "{{ route('sanitasi.area') }}";
			var routeAreaSelected = "{{ route('sanitasi.areaselected') }}";		
		</script>	
	</body>

</html>				

