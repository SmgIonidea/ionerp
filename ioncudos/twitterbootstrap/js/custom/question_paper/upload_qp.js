
var base_url = $('#get_base_url').val(); 

//function to check the file is uploaded or not
$('.upload_qp').live('click', function (e) { 
	e.preventDefault(); 
	$('#qpd_id').val($(this).attr('data-qpd_id'));
	$('#qpd_type').val($(this).attr('data-qpd_type'));	
	$('#crs_id').val($(this).attr('data-id'));	
	$('#occasion_id').val($(this).attr('data-ao_id'));
	$('#section_id').val($(this).attr('data-sec_id'));
	$('#regerate_name').val($(this).attr('data-qpd_type_name'));
	var post_data = {'qpd_id' : $(this).attr('data-qpd_id') , 'ao_id' : $(this).attr('data-ao_id') }
	$('#loading').show();
	$.ajax({
		type : "POST",		
		url : base_url+'question_paper/assessment_qp_upload/check_file_uploaded',			
		data : 	post_data,
		mimeType: "multipart/form-data",
		async:false,
		dataType: "json",
		success: function(msg){ 
				$('#file_exist').val(msg['count']);
				if(msg['count'] == 0) { 
					$('#upload_file').click();
				} else {	
					html  = '<div id="dynamicModal_warning" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="drop_main_table_confirmation" data-backdrop="static" data-keyboard="false"><br>';
					html += '<div class="container-fluid">';
					html += '<div class="navbar">';
					html += '<div class="navbar-inner-custom"> Warning</div></div></div>';
					html += '<div class="modal-body">';
					html += '<p> <b>Uploaded File :</b> '+ msg['new_name'] + ' </p><p> Are you sure you want to replace the existing file? </p>';
					html += '<input type="hidden" name="modalfile" id="modalfile" class="Filedata" size="1" style="opacity:5" value="erwer"/>';
					html += '</div><div class="modal-footer">';
					html += '<button class="btn btn-primary" data-dismiss="modal" id="upload_again"><i class="icon-ok icon-white"></i> Ok </button>';
					html += '<button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel </button></div></div>';
					$('body').append(html);
					$("#dynamicModal_warning").modal();
					$("#dynamicModal_warning").modal('show');						
					$('#loading').hide();	
				}
					$('#loading').hide();
			}
	});
	$('#loading').hide();
});

//function is to select the file for updating
$('#upload_again').live('click', function (e) { 
		$('#upload_file').click();
});

//function to select the file to be uploaded
$('#upload_file').on('change', function () { 
	var file = $('#upload_file').val();  
	$('#file_name').val(file); 
	$('.qp_data').submit();
});

//function is to upload the file 
$('.qp_data').on('submit', function (e) {
	e.preventDefault();
	var form_data = new FormData($('.qp_data')[0]); 
	var size = document.getElementById('upload_file').files[0];
	var check = 1; 
	if(size.size > 10485760){ 
		$('#larger').modal('show');
		check = 0;
	}	
	if(check){
		$('#loading').show();
		$.ajax({
			type : "POST",
			url : base_url+'question_paper/assessment_qp_upload/upload_qp',			
			data : form_data,
			mimeType: "multipart/form-data",
			contentType : false,
			cache : false,
			processData : false,
			success : function (msg){ 				
				if($('#regerate_name').val() == 'CIA' ){ GetSelectedValue(); }
				if($('#regerate_name').val() == 'MTE' ){ regenerate_mte_data($('#crs_id').val()); }
				if($('#regerate_name').val() == 'TEE' ){ regenerate_tee_data($('#crs_id').val()); }
				
				if(msg == 1){
					var data_options = '{"text":"Your file has been uploaded successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
					var options = $.parseJSON(data_options);
					noty(options); 
				} else {
					var data_options = '{"text":"Your file has not been uploaded successfully <br/> <p> Only .doc, .docx, .odt, .rtf and .pdf files  of size 10MB are allowed.</p>","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
					var options = $.parseJSON(data_options);
					noty(options); 					
				}			
			}
		});	
		$('#loading').hide();
	}
});


//function is to view the uploaded file
//$('#example_table').on('click', '.upload_qp_view',function (e) {
$('.upload_qp_view').live('click', function (e) { 

	var crs_id = $(this).attr('data-crs_id'); 		
	var crclm_id = $(this).attr('data-crclm_id'); 
	var term_id = $(this).attr('data-term_id'); 
	var qpd_id = $(this).attr('data-qpd_id');
	var qpd_type = $(this).attr('data-qpd_type');	
	var ao_id = $(this).attr('data-ao_id');	
	var section_id = $(this).attr('data-sec_id');		
	//$('#regerate_name').val($(this).attr('data-qpd_type_name'));
	var post_data = {						
			'crclm_id': crclm_id,
			'term_id' : term_id,
			'crs_id'  : crs_id,
			'qpd_id' : qpd_id ,
			'ao_id' : ao_id,
			'section_id' : section_id,
			'qpd_type' : qpd_type,
			'qpd_type_name' : $(this).attr('data-qpd_type_name')
		}
	$.ajax({
		type: "POST",		
		url : base_url+'question_paper/assessment_qp_upload/fetch_upload_qp',		
		data: post_data,
		dataType: "json",
		success: function(msg) {		
				if(msg['file_name'] == 0) {					
					var data_options = '{"text":"<p> File needs to be uploaded.</p>","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
					var options = $.parseJSON(data_options);
					noty(options);
				}else { 
					window.open(base_url+'uploads/assessment_upload_qp/'+ msg['sub_folder'] +'/'+msg['file_name'], 'myNewPage' );
				}
			}
	});
});
$('.delete_uploaded_qp').live('click', function (e) { 
	$('#qpd_id').val($(this).attr('data-qpd_id'));
	$('#qpd_type').val($(this).attr('data-qpd_type'));	
	$('#crs_id').val($(this).attr('data-id'));	
	$('#occasion_id').val($(this).attr('data-ao_id'));
	$('#section_id').val($(this).attr('data-sec_id'));
	$('#regerate_name').val($(this).attr('data-qpd_type_name'));
	var file_name = $(this).attr('data-file_name'); 
	var post_data = { 'qpd_id' : $(this).attr('data-qpd_id') } 

		html = "";
		html += '<div id="dynamicModal" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="drop_main_table_confirmation" data-backdrop="static" data-keyboard="false"><br>';
		html += '<div class="container-fluid">';
		html += '<div class="navbar">';
		html += '<div class="navbar-inner-custom"> Warning</div></div></div>';
		html += '<div class="modal-body">';
		html += '<p> <b>Uploaded File :</b><span id="filename"></span></p><p> Are you sure you want to delete this file? </p>';
		html += '<input type="hidden" name="modalfile" id="modalfile" class="Filedata" size="1" style="opacity:5" value="erwer"/>';
		html += '</div><div class="modal-footer">';
		html += '<button class="btn btn-primary" data-dismiss="modal" id="delete_uploaded_qp"><i class="icon-ok icon-white"></i> Ok </button>';
		html += '<button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel </button></div></div>';			
		$('body').append(html);
		$('#filename').text(file_name);
		$("#dynamicModal").modal();
		$("#dynamicModal").modal('show');	

});

$('#delete_uploaded_qp').live('click' , function(){
	var post_data = { 'qpd_id' : $('#qpd_id').val() , 'section_id' : $('#section_id').val() , 'qpd_type' : $('#qpd_type').val()  , 'qpd_type_name': $('#regerate_name').val() ,
	'ao_id' : $('#occasion_id').val()}
		$.ajax({
		type: "POST",		
		url : base_url+'question_paper/assessment_qp_upload/delete_uploaded_qp',		
		data: post_data,
		dataType: "json",
		success: function(msg) {
					
				if($('#regerate_name').val() == 'CIA' ){ GetSelectedValue(); }
				if($('#regerate_name').val() == 'MTE' ){ regenerate_mte_data($('#crs_id').val()); }
				if($('#regerate_name').val() == 'TEE' ){ regenerate_tee_data($('#crs_id').val()); }
				
				var data_options = '{"text":"Your file has been deleted successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
					var options = $.parseJSON(data_options);
					noty(options); 
				
				}});
});
