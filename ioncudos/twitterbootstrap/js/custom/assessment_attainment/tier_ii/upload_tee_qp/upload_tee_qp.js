
var base_url = $('#get_base_url').val(); 

//function to check the file is uploaded or not
$('#example_table').on('click', '.upload_qp',function (e) { 
	e.preventDefault();
	var crclm = $('#curriculum').val(); 
	$('#u_crclm_id').val(crclm);
	var term_id = $('#term').val(); 
	$('#u_term_id').val(term_id);
	var crs_id = $(this).attr('data-id'); 
	$('#u_crs_id').val(crs_id); 
		
	var post_data = {
		'crs_id' : crs_id,
		'term_id': term_id,
		'curriculum': crclm
	}
	$('#loading').show();
	$.ajax({
		type : "POST",
		url : base_url+'tier_ii/import_coursewise_tee/check_crsid',
		data : 	post_data,
		mimeType: "multipart/form-data",
		async:false,
		dataType: "json",
		success: function(msg){ 
				if(msg['file'] == 0 && msg['qpd_id'] != 2) { 
					$('#Filedata').click();
				} else if(msg['file'] == 0 && msg['qpd_id'] == 2){
					$('#rollout').modal('show');
				} else {
					$("#modalfile").val(crs_id);
					$('#success').modal('show');					
				}
			}
	});
	$('#loading').hide();
});

//function to select the file to be uploaded
$('#Filedata').on('change', function () { 
	var file = $('#Filedata').val();  
	$('#u_filedata').val(file); 
	$('#tee_qp').submit();
});

//function is to upload the file 
$('#tee_qp').on('submit', function (e) {
	e.preventDefault();
	var form_data = new FormData(this); 
	var size = document.getElementById('Filedata').files[0];
	var check = 1; 
	if(size.size > 10485760){ 
		$('#larger').modal('show');
		check = 0;
	}
	
	if(check){
		$('#loading').show();
		$.ajax({
			type : "POST",
			url : base_url+'tier_ii/import_coursewise_tee/upload_tee',
			data : form_data,
			mimeType: "multipart/form-data",
			contentType : false,
			cache : false,
			processData : false,
			success : function (msg){ 
					if(msg == 1){
						$('#save').modal('show');
					}
					else if(msg == -1) { 
						$('#msg').modal('show');
						$('#Filedata').val(''); 
					} else {
						$('#failure').modal('show');
					}
				}
		});	
		$('#loading').hide();
	}
});

//function is to select the file for updating
$('#upload_again').click(function(){ 
	$('#Filedata_1').click(); 
});

$('#Filedata_1').on('change', function () {
	
	var file_data = $('#Filedata_1').val();  
	$('#re_u_filedata').val(file_data); 
	var data = $('#modalfile').val(); 
	$('#re_u_crs_id').val(data);
	$("#modalfile").val(data);
	$('#re_tee_qp').submit();
});

//function id to update the qp_tee_upload table
$('#re_tee_qp').on('submit', function (e) {

	e.preventDefault();
	var form = new FormData(this); 
	var data = $('#modalfile').val(); 
	var size = document.getElementById('Filedata_1').files[0];
	var check = 1; 
	if(size.size > 10485760){ 
		$('#larger').modal('show');
		check = 0;
	}

	if(check){
		$('#loading').show();
		$.ajax({
			type: "POST",
			url: base_url+'tier_ii/import_coursewise_tee/update_filename',
			data : form,
			mimeType: "multipart/form-data",
			contentType : false,
			cache : false,
			processData : false,
			dataType: "json",
			success : function (msg) { 
					if(msg == 1) {
						$('#save').modal('show');
					} else if(msg == -1) { 
						$('#msg').modal('show');
						$('#Filedata_1').val('');
					} else {
						$('#failure').modal('show');
					}		
				}
		});
		$('#loading').hide();
	}
});

//function is to view the uploaded file
$('#example_table').on('click', '.upload_qp_view',function (e) {
	var crs_id = $(this).attr('data-crs_id'); 
	var dept_id = $(this).attr('data-dept_id'); 
	var prog_id = $(this).attr('data-prog_id'); 
	var crclm_id = $(this).attr('data-crclm_id'); 
	var term_id = $(this).attr('data-term_id'); 
	
	var post_data = {
			'crs_id'  : crs_id,
			'dept_id' : dept_id,
			'prog_id' : prog_id,
			'crclm_id': crclm_id,
			'term_id' : term_id
		}

	$.ajax({
		type: "POST",
		url : base_url+'tier_ii/import_coursewise_tee/fetch_upload_qp',
		data: post_data,
		success: function(msg) {
				if(msg == 0) {
					$('#upload_file').modal('show');
				} else if(msg == -2) {
					$('#upload_file').modal('show');
				}
				else { 
					window.open(base_url+'uploads/upload_qp/'+msg, 'myNewPage' );
				}
			}
	});
});

