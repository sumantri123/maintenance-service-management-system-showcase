var selected = new Set();
var allSelected = false;
var data_table;
var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
$(document).ready(function () {           
    $('#contentJB').hide();	
    //disableEntry();	
});    
$(document).on("change", "#checkAll", function () {    
    allSelected = $(this).is(':checked');
    if (allSelected) {
        selected.clear(); // hapus manual selection
    } else {
        selected.clear();
    }
    $('#dataTable .checkItem').prop('checked', allSelected);
});
$(document).on("change", ".checkItem", function () {
    const id = $(this).val();

    if ($(this).is(':checked')) {
        selected.add(id);
    } else {
        selected.delete(id);
        allSelected = false;
        $('#checkAll').prop('checked', false);
    }
});	 	
$('button#btn_print2').on('click', function () {                      
	var bulanMutasiRekening = $('#bulan').val();
	var bagian = $('#bagian').val();
	var tahun = $('#tahun').val();
	var param= btoa(bulanMutasiRekening+"|"+bagian+"|"+tahun);
	cetakRoute = routeCetak.replace('ID_PLACEHOLDER', param);	
    window.open(cetakRoute, '_blank', 'left=0,top=0,width=1000,height=700,status=0');
});
$('button#btn_excel').on('click', function () {
	/* var tglAwal = $('#tgl_awal').val();	var tglAkhir = $('#tgl_akhir').val();     */
	const laporan = $('#laporan').val();

    if (!allSelected && selected.size === 0) {
        info_noti('Belum ada data yang dipilih!');
        return;
    }

    $("#laporan_export").val(laporan);

    if (allSelected) {        
        $("#param_export").val('ALL');
    } else {
        $("#param_export").val(Array.from(selected).join(","));
    }

    $("#formExport").submit();
});

$('button#btn_search').on('click', function () {                
	/* var tglAwal = $('#tgl_awal').val();
	var tglAkhir = $('#tgl_akhir').val();     */
	var form = $('#data_form');
    if (form.valid() == true) {    
        $('#headerJB').hide("slow");
        $('#contentJB').show("slow");    
        loadData();
    } else {
        error_noti('Mohon Isi Form Dengan Lengkap, Cek Input Form Yang Berwarna Merah');
    }
});

$('button#btn_back').on('click', function () {                      
    $('#contentJB').hide("slow");
    $('#headerJB').show("slow");
    $('#myTable').remove();
});
function loadData(){  
    var laporan = $('#laporan').val();
	var kriteria = $('#kriteria').val();	
	var param_search = $('#param').val();	
	data_table  = $('#dataTable').DataTable({            
			processing: true,
			lengthChange: true, 
			destroy: true,	
			pageLength: 50,
			lengthMenu: [ [50, 100, 150, 200], [50, 100, 150, 200] ],
			ajax: {
				url: routeData,				dataSrc: function(json) {										window.canUpdate = json.can_update;					window.canDelete = json.can_delete;					return json.data;				},
				type: 'post',
				dataType: 'JSON',				
				data: {
					_token: CSRF_TOKEN,            			
					laporan: laporan,
					kriteria : kriteria,
					search : param_search,
				},
				error: function (xhr, textStatus, ThrownException) {				
					alert('Error loading data. Exception: ' + ThrownException + "\n" + textStatus);					
				}
			},            columns: [
			{
                title: "<input type='checkbox' id='checkAll'>",
                data: "service_id",
                width: "5%",
                visible: true,
                sortable: false,
                class: "text-center",
                render: function (data, type, row, meta) {
                    var result = '';
                    result += "<input type='checkbox' class='checkItem' name='opsi[]' value='"+row.idx+"'>";
                    return result;
                }
            },{
				title: "Nama Pegawai",                
				width: "15%",
				data: "teknisi_nama",
				visible: true,
				sortable: true,				
            }, {
                title: "Tanggal Service",
                data: "tgl_permintaan_service",
                width: "12%",
                visible: true,
                sortable: true,
                class: "",				
            },{
				title: "Outlet",								
				visible: true,
				width: "20%",
				sortable: true,
				class: "",
				render: function (data, type, row, meta) {
					var result = '';
					result += 'Nama : '+row.outlet_nama+'<br><br>';
					result += 'Alamat : '+row.outlet_alamat+'<br><br>';
					result += 'PIC : '+row.outlet_pic;
					return result;
				}
            },{
				title: "Masalah Chiller",								
				visible: true,
				sortable: true,
				class: "",
				render: function (data, type, row, meta) {
					var result = '';
					result += 'Masalah : '+row.permasalahan_chiller+'<br><br>';
					result += 'Tindakan : '+row.tindakan_chiller;
					return result;
				}
            },{
				title: "Foto",			
				width: "5%",
				visible: true,
				sortable: true,
				class: "",
				render: function (data, type, row, meta) {
					var result = '';
					result += '<button type="button" onclick="detFile(\''+row.idx+'\');" class="btn btn-info btn-sm"><i class="bx bx-search-alt me-0"></i></button>';
					return result;
				}
            },{
				title: "Aksi",				
				width: "17%",
				visible: true,
				sortable: true,
				class: "text-center",
				render: function (data, type, row, meta) {				
					viewRoute = routeView.replace('PARAMETER', row.idx);
					pdfRoute = routePdf.replace('PARAMETER', row.idx);
					var result = '';					if (window.canUpdate) {		
						result += '<a href='+viewRoute+' target="_blank" class="btn btn-warning btn-sm btn-edit"><i class="bx bx-message-square-edit me-0"></i></a>&nbsp;';					}
					result += '<a href='+pdfRoute+' target="_blank" class="btn btn-primary btn-sm btn-pdf"><i class="bx bx-download me-0"></i></a>&nbsp;';
					if (window.canDelete) {						result += '<button type="button" class="btn btn-danger btn-sm btn-hapus"><i class="bx bx-trash-alt me-0"></i></button>';										}
					return result;
				}
            }],
            "drawCallback": function (settings) {               
            }
		}); 
		$('#dataTable').on('click', '.btn-hapus', function () {
			let data = data_table.row($(this).parents('tr')).data();
			Lobibox.confirm({
				iconClass: true,
				title: 'Delete Data',                        
				msg: 'Yakin Hapus Data "' + data.teknisi_nama + '"?',
				callback: function ($this, type, ev) {
					if(type=='yes'){								
						deleteProses(data.idx);						
					}        
				}
			});
		});
		
	data_table.on('draw', function() {
        $('#dataTable .checkItem').each(function() {
            const id = $(this).val();
            $(this).prop('checked', allSelected || selected.has(id));
        });
        $('#checkAll').prop('checked', allSelected);
    });
}
function deleteProses(id) {
	hapusRoute = routeHapus.replace('PARAMETER', id);					
	$.ajax({
		type: 'GET',
		url: hapusRoute,
		dataType: 'JSON',            
		success: function (data) {
			if (data.status == 'success') {
				success_noti(data.message);
				data_table.ajax.reload(null, false);
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
function delFile(id,a) {

	hapusFileRoute = routeFileHapus.replace('PARAMETER', id);					
	
	Lobibox.confirm({
		iconClass: true,
		title: 'Delete Gambar',                        
		msg: 'Yakin Hapus Gambar ?',
		callback: function ($this, type, ev) {
			if(type=='yes'){								
			
				$.ajax({
					type: 'GET',
					url: hapusFileRoute,
					dataType: 'JSON',            
					success: function (data) {
						if (data.status == 'success') {
							success_noti(data.message);
							loadDataFile(a);
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
		}
	});				
}
function detFile(a){		
	if(a === ""){
		info_noti('Data Tidak Ditemukan');
	} else {
		$('#tableFile').find('tbody').empty();  
		$("#modalFile").modal('show');  
		loadDataFile(a);
	}
}
function viewFile(a){	
	$('#myModalFile').modal('show');	
	$('#filePdf').attr('src', base_url+'/'+a)	
}
function loadDataFile(a) {		
	$.ajax({
        type: 'POST',
        url: routeFile,        
        dataType: 'JSON',
        data: {
			_token: CSRF_TOKEN,            
			param: a,			
        },
        success: function (result) {				
			const tbody = $('#tableFile').find('tbody');
            tbody.empty(); 
            tbody.append(result.data);			
		},
		error: function (xmlhttprequest, textstatus, message) {
			error_noti('Koneksi Ke Server Gagal, Mohon Refresh Halaman')
		}
    });		
}
var validator = $('#data_form').validate({
    rules: {                
		/* tgl_awal: {required: true},  
		tgl_akhir: {required: true},  */
		laporan: {required: true}, 		
    },
    highlight: function (element, errorClass, validClass, error) {
        $(element.form).find("[id=" + element.id + "]").addClass('is-invalid');
        $(element.form).find("[id=" + element.id + "]").addClass('is-invalid');
        $(element.form).find("[id=" + element.id + "]").removeClass('is-valid');
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element.form).find("[id=" + element.id + "]").removeClass('is-invalid');
        $(element.form).find("[id=" + element.id + "]").addClass('is-valid');
    }
});
//--------------------- Setup DatePicker ---------------------
$('.datepicker').pickadate({			
    selectMonths: true,
    selectYears: true
});
$(function () {
    $('#date-time').bootstrapMaterialDatePicker({
        format: 'YYYY-MM-DD HH:mm'
    });
    $('#date').bootstrapMaterialDatePicker({
        time: false
    });
    $('#time').bootstrapMaterialDatePicker({
        date: false,
        format: 'HH:mm'
    });
});				

$('.single-select').select2({
    theme: 'bootstrap4',		
    width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
    placeholder: $(this).data('placeholder'),
    allowClear: Boolean($(this).data('allow-clear')),
});
