$(document).ready(function() {
	$("#other_sparepart").hide('slow');
	$("#other_branding").hide('slow');		$('#image-uploadify1').imageuploadify();
	toggleOtherBranding();
	toggleOtherSparepart();
});
$(".sparepart").on("click", function() {	
	toggleOtherSparepart();        
});
$(".branding").on("click", function() {	
	toggleOtherBranding();    
});$('button#btnFotoBefore').on('click', function () {		function resetUploadify(selector) {							$(selector).next('.imageuploadify').remove();							$(selector).val('');		$(selector).imageuploadify();	}	resetUploadify('#image-uploadify');		$('#myFotoBefore').modal('show');	});$('button#btnFotoAfter').on('click', function () {                      	function resetUploadify(selector) {							$(selector).next('.imageuploadify').remove();							$(selector).val('');		$(selector).imageuploadify();	}		resetUploadify('#image-uploadify1'); 		$('#myFotoAfter').modal('show');	});
function toggleOtherBranding() {
	if ($(".branding[value='Other']").is(":checked")) {
		$("#other_branding").show('slow');
	} else {
		$("#other_branding").hide('slow');
		$("#other_branding").val("");
	}
}
function toggleOtherSparepart() {
	if ($(".sparepart[value='Other']").is(":checked")) {
		$("#other_sparepart").show('slow');
	} else {
		$("#other_sparepart").hide('slow');
		$("#other_sparepart").val("");
	}
}
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
    }});
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
$('#form_upload').submit(function(e) {	
    e.preventDefault();
    var formData = new FormData(this);        
    var form = $('#form_upload');	
	if ($('input[name="branding[]"]:checked').length === 0) {
		e.preventDefault(); 
		error_noti('Silakan pilih minimal satu branding!');          
        return false;
    } 
	$.ajax({
		type:'POST',
		url: routeUpdate,
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
				$(".select-2").val(null).trigger("change");							
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
});$('#formUploadBefore').submit(function(e) {	    e.preventDefault();    	const files = $('#image-uploadify')[0].files;    if (files.length === 0) {        error_noti('Silakan pilih minimal satu foto sebelum mengunggah.');        return;    }		var formData = new FormData(this);            var form = $('#formUploadBefore');		$.ajax({		type:'POST',		url: routeUploadDataBefore,		data: formData,		cache:false,		contentType: false,		processData: false,				beforeSend: function () {			sweetAlertLoading('Memproses');		},		success: (data) => {			Swal.close();			if(data.status == 'success'){								success_noti(data.message);  									location.reload();			} else {				error_noti(data.message);  			}					                					},		error: function (xhr, textStatus, ThrownException) {							Swal.close(); 			sweetAlertDefault('Gagal mengambil data'); 											},	});});$('#formUploadAfter').submit(function(e) {	    e.preventDefault();    		const files = $('#image-uploadify1')[0].files;    if (files.length === 0) {        error_noti('Silakan pilih minimal satu foto sebelum mengunggah.');        return;    }		var formData = new FormData(this);            var form = $('#formUploadAfter');		$.ajax({		type:'POST',		url: routeUploadDataAfter,		data: formData,		cache:false,		contentType: false,		processData: false,				beforeSend: function () {			sweetAlertLoading('Memproses');		},		success: (data) => {			Swal.close();			if(data.status == 'success'){								success_noti(data.message);  									location.reload();			} else {				error_noti(data.message);  			}					                					},		error: function (xhr, textStatus, ThrownException) {							Swal.close(); 			sweetAlertDefault('Gagal mengambil data'); 											},	});});
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