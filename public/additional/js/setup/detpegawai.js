var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
var dataTable;

$(document).ready(function () {	
	loadData();		   
});

$('button#tambah').on('click', function () {   

	$('#modal_label').text('Form Tambah / Ubah Data');
	$('#method_field').val("POST");
	$(".modal-form").modal('show');		

	clearModal();	
});

function loadData() {
	
	let id = $('#idPgw').val();
	
	dataRoute = routeData.replace('PARAMETER', id);				
	dataTable = $('#aktivitas_datatable').DataTable({
		processing: true,
		ajax: {
			url: dataRoute,
			method: 'GET',
			headers: {
				'X-CSRF-TOKEN': CSRF_TOKEN
			},
			dataType: 'json',
			error: function (xhr, textStatus, thrownError) {
				console.error('Error loading data:', {
					status: xhr.status,
					statusText: xhr.statusText,
					response: xhr.responseText
				});
				alert('Error loading data!\nStatus: ' + xhr.status + '\nError: ' + xhr.responseText);
			}
		},
		columns: [{
			title: "Area Nama",
			data: "nama",
			visible: true,
			sortable: true,				
			render: function (data, type, full, meta) {
				return titleCase(full.nama);
			}
		}, {
			title: "Aksi",
			data: "id",
			visible: true,
			sortable: false,
			width: "20%",
			class: "text-center",
			render: function (data, type, full, meta) {
				var result = '';                                        				
				result += '<button type="button" class="btn btn-danger btn-hapus"><i class="bx bx-trash-alt me-0"></i></button>';					

				return result;			
			}
		}],		
	});

	$('#aktivitas_datatable').on('click', '.btn-hapus', function () {
		let data = dataTable.row($(this).parents('tr')).data();
		Lobibox.confirm({
			iconClass: true,
			title: 'Delete Data',                        
			msg: 'Yakin Hapus Data "' + data.nama + '"?',
			callback: function ($this, type, ev) {
				if(type=='yes'){
					deleteProses(data.id);
				}        
			}
		});
    });
}


$('#data_form').submit(function(e) {
	e.preventDefault();
	
	var method = $('#method_field').val();
	var action_url = routeSimpan;
	var action_type = "Tambah";		
	var tipe = $('#id').val();

	if (tipe !== "") {
		action_url = routeUbah;
		action_type = "Ubah";
	}				

	var formData = new FormData(this);        
	var form = $('#data_form');
	
	if (form.valid() == true) {    
					
		$.ajax({
			type:'POST',
			url: action_url,
			data: formData,
			cache:false,
			contentType: false,
			processData: false,
			beforeSend: function(){
				sweetAlertLoading('Memproses');
			},				
			success: (data) => {	
				Swal.close();	
				if (data.status == 'success') {
					success_noti(data.message);   										
					$('.modal-form').modal('toggle');
					this.reset();
					dataTable.ajax.reload(null, false);
				} else {
					error_noti(data.message); 
				}

			},
			error: function (error) {					
				error_noti(data.message);
				$('.modal-form').modal('toggle');
				this.reset();
			}
		});
	} else {
		
		error_noti('Mohon Isi Form Dengan Lengkap, Cek Input Form Yang Berwarna Merah');
	} 

});


function deleteProses(id) {
	hapusRoute = routeHapus.replace('PARAMETER', id);					
	$.ajax({
		type: 'GET',
		url: hapusRoute,
		dataType: 'JSON',            

		success: function (data) {
			if (data.status == 'success') {
				success_noti(data.message);
				dataTable.ajax.reload(null, false);
			} else if (data.status == 'error') {
				error_noti(data.message);
			} else {
				error_noti('Data Gagal Dihapus (Kesalahan Sistem)');
			}
		},

		error: function (xmlhttprequest, textstatus, message) {
			error_noti('Koneksi Ke Server Gagal, Mohon Refresh Halaman')
		}
	});
}



