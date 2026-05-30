$(document).ready(function() {
    $("#other_sparepart").hide('slow');
	$("#other_branding").hide('slow');	
});

document.addEventListener("DOMContentLoaded", function () {
	flatpickr("#waktu_mulai", {
		enableTime: true,
		noCalendar: true,
		dateFormat: "H:i",   // Format 24 jam
		time_24hr: true
	});
	
	flatpickr("#waktu_selesai", {
		enableTime: true,
		noCalendar: true,
		dateFormat: "H:i",   // Format 24 jam
		time_24hr: true
	});
});

const canvas = document.getElementById('signature-pad');
const canvas1 = document.getElementById('signature-pad1');
const signaturePad = new SignaturePad(canvas);
const signaturePad1 = new SignaturePad(canvas1);

/* let dataURL = signaturePad.toDataURL(); 
$('#signature-data').val(dataURL);

let dataURL1 = signaturePad1.toDataURL(); 
$('#signature-data1').val(dataURL1); */

$('#clear').on('click', function () {
    signaturePad.clear();
});

$('#clear1').on('click', function () {
    signaturePad1.clear();
});


$(".sparepart").on("click", function() {	
    if($(this).is(":checked")) {
		var other = $(this).val();
		if(other === 'Other'){
			$("#other_sparepart").show('slow');
		}
    } else {
		var other = $(this).val();
		if(other === 'Other'){
			$("#other_sparepart").hide('slow');
		}
    }
});

$(".branding").on("click", function() {	
    if($(this).is(":checked")) {
		var other = $(this).val();
		if(other === 'Other'){
			$("#other_branding").show('slow');
		}
    } else {
		var other = $(this).val();
		if(other === 'Other'){
			$("#other_branding").hide('slow');
		}
    }
});

let lokasiSiap = false;
let map, marker;

function setLokasi(lat, lng) {	
	$('#lat').val(lat);
	$('#lng').val(lng);
	lokasiSiap = true;

	if (marker) {
		marker.setLatLng([lat, lng]);
	} else {
		marker = L.marker([lat, lng], { draggable: true }).addTo(map);
		marker.on('dragend', function (e) {
			let pos = e.target.getLatLng();
			setLokasi(pos.lat, pos.lng);
		});
	}

	getAddress(lat, lng);
}

function getAddress(lat, lng) {
	let url = "https://nominatim.openstreetmap.org/reverse?format=json&lat=" + lat + "&lon=" + lng;

	fetch(url)
		.then(response => response.json())
		.then(data => {
			console.log(data);
			let jalan = data.display_name; 
			$('#alamatInput').val(jalan); 
		})
		.catch(err => console.log(err));
}

function cekLokasi() {
	if (!lokasiSiap) {
		alert("❗ Lokasi belum aktif. Silakan aktifkan GPS dan izinkan akses lokasi agar bisa login.");
		return false;
	}
	return true;
}

// Inisialisasi peta
map = L.map('map').setView([-2.5489, 118.0149], 5); // default Indonesia
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
	maxZoom: 18,
}).addTo(map);

// Ambil lokasi otomatis (sekali)
if (navigator.geolocation) {
	navigator.geolocation.getCurrentPosition(
		function (pos) {
			let lat = pos.coords.latitude;
			let lng = pos.coords.longitude;
			map.setView([lat, lng], 15);
			setLokasi(lat, lng);
		},
		function (error) {
			switch (error.code) {
				case error.PERMISSION_DENIED:
					alert("❗ Anda menolak akses lokasi. Aktifkan GPS lalu izinkan lokasi di browser.");
					break;
				case error.POSITION_UNAVAILABLE:
					alert("❗ Lokasi tidak tersedia. Pastikan GPS aktif.");
					break;
				case error.TIMEOUT:
					alert("❗ Gagal mengambil lokasi (timeout). Coba nyalakan ulang GPS.");
					break;
				default:
					alert("❗ Terjadi kesalahan. Pastikan GPS dan lokasi aktif.");
			}
		},
		{ enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
	);

	// Pantau lokasi terus (real-time update)
	navigator.geolocation.watchPosition(function (pos) {
		let lat = pos.coords.latitude;
		let lng = pos.coords.longitude;
		setLokasi(lat, lng);

		let info = "Latitude: " + lat + "<br>Longitude: " + lng;
		document.getElementById("demo").innerHTML = info;
		
		$('#Latitude').val(lat);
		$('#Longitude').val(lng);
	});
} else {
	alert("❗ Browser tidak mendukung Geolocation.");
}

// Kalau user klik manual di peta
map.on('click', function (e) {
	setLokasi(e.latlng.lat, e.latlng.lng);
});

$('#form_upload').submit(function(e) {	
    e.preventDefault();
	
	if ($('input[name="branding[]"]:checked').length === 0) {
		e.preventDefault(); 
		error_noti('Silakan pilih minimal satu branding!');          
        return false;
    }

	if (signaturePad.isEmpty()) {
		error_noti('Tanda tangan PIC kosong!');
		return false;
	}
	
	if (signaturePad1.isEmpty()) {
		error_noti('Tanda tangan Teknisi kosong!');
		return false;
	}

	$('#signature-data').val(signaturePad.toDataURL());
	$('#signature-data1').val(signaturePad1.toDataURL());

	var formData = new FormData(this);        
    var form = $('#form_upload');	

	$.ajax({
		type:'POST',
		url: routeUploadData,
		data: formData,
		cache:false,
		contentType: false,
		processData: false,		
		beforeSend: function () {
			sweetAlertLoading('Memproses');
		},
		success: (data) => {
			Swal.close();
						
			if(data.status == 'success'){
				this.reset();
				signaturePad.clear();
				signaturePad1.clear();
				$(".select-2").select2().select2('val','""');
				$(".select-2").select2({ width: "100%" });   
				success_noti(data.message);  					
			} else {
				error_noti(data.message);  
			}					                			
		},
		error: function (xhr, textStatus, ThrownException) {				
			Swal.close(); 
			sweetAlertDefault('Gagal mengambil data'); 									
		},
	});
    
});

function resizeCanvas(canvas, signaturePad) {
    const ratio = Math.max(window.devicePixelRatio || 1, 1);
    canvas.width = canvas.offsetWidth * ratio;
    canvas.height = canvas.offsetHeight * ratio;
    canvas.getContext("2d").scale(ratio, ratio);
    signaturePad.clear(); // clear biar konsisten
}

resizeCanvas(canvas, signaturePad);
resizeCanvas(canvas1, signaturePad1);

window.addEventListener("resize", function() {
    resizeCanvas(canvas, signaturePad);
    resizeCanvas(canvas1, signaturePad1);
});

function getArea(select) {
    let id = select.value;	

    if(id) {
		$.ajax({
			type:'post',
			url: routeArea,			
			data: {			
				paramx: id,		
				_token: $('meta[name="csrf-token"]').attr('content')	
			},
			dataType: 'JSON',					
			success: function (data) {							
				// kosongkan select kota dulu
				$('#area').empty();
				$('#area').append('<option value="">Pilih Area</option>');
				
				$.each(data, function(key, value) {					
					$('#area').append('<option value="'+ value.id_area +'">'+ value.area_nama +'</option>');
				});							
								
			},
			error: function (xhr, textStatus, ThrownException) {								
				error_noti('Gagal mengambil data'); 								
			},
		});		
        
    } else {
        $('#area').empty();
        $('#area').append('<option value="">-- Pilih Area --</option>');
    } 
}

$('.single-select').select2({
	theme: 'bootstrap4',		
	width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
	placeholder: $(this).data('placeholder'),
	allowClear: Boolean($(this).data('allow-clear')),
});

function sweetAlertLoading(html, timer = null ) {
	Swal.fire({
		title: html,
		width: '350px',
		padding: '1em',
		position: 'top-end',
		showCancelButton: false,
		showConfirmButton: false,
		timer: timer,
		onBeforeOpen: () => {
			Swal.showLoading()
		}
	})
}