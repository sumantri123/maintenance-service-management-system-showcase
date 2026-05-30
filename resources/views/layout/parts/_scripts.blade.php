<!-- Bootstrap JS -->
<script src="{{asset('backend/js/bootstrap.bundle.min.js') }}"></script>
<!--plugins-->
<script src="{{asset('backend/js/jquery.min.js') }}"></script>
<script src="{{asset('backend/js/jquery.validate.min.js') }}"></script>
<script src="{{asset('backend/plugins/simplebar/js/simplebar.min.js') }}"></script>
<script src="{{asset('backend/plugins/metismenu/js/metisMenu.min.js') }}"></script>
<script src="{{asset('backend/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
<script src="{{asset('backend/plugins/chartjs/js/Chart.min.js') }}"></script>
<script src="{{asset('backend/plugins/vectormap/jquery-jvectormap-2.0.2.min.js') }}"></script>
<script src="{{asset('backend/plugins/vectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
<script src="{{asset('backend/plugins/jquery.easy-pie-chart/jquery.easypiechart.min.js') }}"></script>
<script src="{{asset('backend/plugins/sparkline-charts/jquery.sparkline.min.js') }}"></script>
<script src="{{asset('backend/plugins/jquery-knob/excanvas.js') }}"></script>
<script src="{{asset('backend/plugins/jquery-knob/jquery.knob.js') }}"></script>
<script src="{{asset('backend/plugins/sweetalert2/dist/sweetalert2.js') }}"></script>

<script src="{{asset('additional/js/global.js') }}"></script>
<script src="{{asset('backend/js/app.js') }}"></script>
<script src="{{asset('backend/plugins/select2/js/select2.min.js') }}"></script>
<script src="{{asset('backend/js/pace.min.js') }}"></script>
<!-- Tabel -->
<script src="{{asset('backend/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{asset('backend/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
<!-- Date Time -->
<script src="{{asset('backend/plugins/datetimepicker/js/legacy.js') }}"></script>
<script src="{{asset('backend/plugins/datetimepicker/js/picker.js') }}"></script>
<script src="{{asset('backend/plugins/datetimepicker/js/picker.time.js') }}"></script>
<script src="{{asset('backend/plugins/datetimepicker/js/picker.date.js') }}"></script>
<script src="{{asset('backend/plugins/bootstrap-material-datetimepicker/js/moment.min.js') }}"></script>
<script src="{{asset('backend/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.min.js') }}"></script>

<!-- Notification -->
<script src="{{asset('backend/plugins/notifications/js/lobibox.min.js') }}"></script>
<script src="{{asset('backend/plugins/notifications/js/notifications.min.js') }}"></script>
<script src="{{asset('additional/js/notification-custom-script.js') }}"></script>

<!-- AutoComplete -->
<script src="{{asset('backend/plugins/jquery-ui-1.12.1/jquery-ui.min.js') }}"></script>
<script src="{{asset('backend/plugins/jquery-ui-1.12.1/jquery-ui.js') }}"></script>
<!-- <script src="{{asset('backend/plugins/smart-wizard/js/jquery.smartWizard.min.js') }}"></script> -->

<script>
	$(document).ready(function () {	
        
        $('button#btn_ubahPassword').on('click', function () {
            ubahPassword();
            
        });
	});

	$(function () {
		$('#date-time').bootstrapMaterialDatePicker({
			format: 'YYYY-MM-DD HH:mm'
		});
		$('#date-time-awal').bootstrapMaterialDatePicker({
			format: 'YYYY-MM-DD HH:mm'
		});
		$('#date-time-akhir').bootstrapMaterialDatePicker({
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
	
	function ubahPassword(){
		var form = $('#formPassword');

        if (form.valid() == true) {
			if(document.getElementById("password").value == document.getElementById("password_confirm").value){

				var method = $('#method_field').val();
				var action_url = "" + base_url + "/ubahPassword";
				
				$.ajax({
					type: 'POST',
					url: action_url,
					dataType: 'JSON',
					data: form.serialize(),
					beforeSend: function () {
						sweetAlertLoading('Memproses');
					},

					success: function (data) {
						if (data.status == 'insert_successful') {
							sweetAlertDefault('<b>Berhasil Ubah Password</b>', 'success', 2000 );


						} else if (data.status == 'insert_failed') {

							sweetAlertDefault('<b>Gagal ' + action_type + ' </b>', 'error', 2000 );


							var errors = data.error;
							errorValidationLaravel(errors, '#error-validation');

						} else {
							sweetAlertDefault('<b>Gagal ' + action_type + ' (Kesalahan Sistem) </b>', 'error', 2000 );
						}

					},

					error: function (xmlhttprequest, textstatus, message) {
						sweetAlertDefault('<b>Koneksi Ke Server Gagal, Mohon Refresh Halaman</b>', 'error', 2000 );
					}

				});
			 } else {
				sweetAlertLoading('Password dan konfirmasi password tidak sama',1000);
			}
        } else {
            sweetAlertLoading('Mohon Isi Form Dengan Lengkap, Cek Input Form Yang Berwarna Merah',1000);
        }
	}
	
	var validator = $('#formPassword').validate({

        rules: {

			password: {
                required: true
			},
            password_confirm: {
                required: true,
            }
            

        },

        highlight: function (element, errorClass, validClass, error) {

            $(element.form).find("[id=" + element.id + "]").addClass('is-invalid');

            $(element.form).find("[id=" + element.id + "]").removeClass('is-valid');

        },

        unhighlight: function (element, errorClass, validClass) {
			$(element.form).find("[id=" + element.id + "]").removeClass('is-invalid');
            $(element.form).find("[id=" + element.id + "]").addClass('is-valid');
        }

    });

</script>	

@stack('scripts')
