var base_url = $('#get_base_url').val();
var controller = base_url + 'scheduler/student_marks_upload/';
var tab_name = '';

$(document).ready(function () {
	$('#upload_btn').prop('disabled', true);
	if($('#files_count').val() > 1){
		$('.process_all_files').show();
	}else{
		$('.process_all_files').hide();
	}
});
$('#upload_form').on('click', '#upload_btn', function (e) {
var files  = $('#upload_files').val();
if(files != ""){$('#upload_form').submit();}
	e.preventDefault();
});
/* 	function edit_notify(){
	$.notify('Edit here','info', {
			className:"info",
			allow_dismiss: false,
			showProgressbar: true,
		},
		 { position:"right" });
	/*  $(".table-bordered").notify(
		  "Edit Here", 'info',
		  { position:"bottom" }
		);	 	 */			
//	} */
function view_call(file){
	var post_data  = {'a' : 1}
	    $.ajax({
        type: "POST",
        url: base_url + 'scheduler/student_marks_upload/view',
        data: post_data,
        success: function (data) {	
			if($('#files_count').val() > 1){
				$('.process_all_files').show();
			}else{
				$('.process_all_files').hide();
			}
		$('#uploaded_data').html(data);
		$('html,body').animate({ scrollTop: $("#tabs").offset().top},'slow');
		//edit_notify();
		if(file == 'pending'){			
/* 			var data_options = '{"text":"File uploaded successfully. ","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
			var options = $.parseJSON(data_options);
			noty(options);  */ 
			$('#pending_files').addClass('active');
			$('#tabs a[href="#pending_files"]').tab('show');	
			
		}else if(file == 'invalid'){		
			/* var data_options = '{"text":"Invalid. ","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
			var options = $.parseJSON(data_options);
			noty(options); 	 */	
			$('#invalid_files').addClass('active');
			$('#tabs a[href="#invalid_files"]').tab('show');		
		}else if(file == 'rejected'){
			$('#rejected_files').addClass('active');
			$('#tabs a[href="#rejected_files"]').tab('show');		
		}else if(file == 'processed'){
			$('#processed_files').addClass('active');
			$('#tabs a[href="#processed_files"]').tab('show');
		}		
		
        }
	});
}
$('#upload_files').change(function () {
	$('#uploaded_files_list').empty();
	//var files = $('#upload_files').val();
	var file = $("input[type=file]");
	var files = file[0].files;
	if (files.length > 0) {
		$('#upload_btn').prop('disabled', false);
	}
	var table = '<h4>Uploaded files Information:</h4>';
	table += '<table class="table table-bordered table-stripped">';
	table += '<thead><tr><th>Sl No.</th><th>File Name</th></tr></thead>';
	var i = 0;
	$.each(files, function () {
		table += '<tr>';
		table += '<td>' + (i + 1) + '</td>';
		table += '<td>' + files[i].name + '</td>';
		table += '</tr>';
		i++;
	});
	table += '</table>';
	$('#uploaded_files_list').addClass('well')
	$('#uploaded_files_list').html(table);
});
$('#upload_form').submit(function (e) {
	$('#loading').show();
	e.preventDefault();
	var form_data = new FormData(this);
	$.ajax({
		type : 'POST',
		url : controller + 'upload_files',
		data : form_data,
		contentType : false,
		cache : true,
		async : false,
		processData : false,
		success : function (msg) {
			console.log(msg);
			$('#reset').trigger('click');
			if ($.trim(msg) == 0) {
			$('#upload_file').val("");
			$('#pending_files').addClass('active');
/* 				e.preventDefault();
				$('#status').html("<h4 class='text-success'>Files uploaded successfully and will be proccessed at scheduled time.</h4>");				
				var data_options = '{"text":"File uploaded successfully. ","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
				var options = $.parseJSON(data_options);
				noty(options); */
				view_call('pending');
			}else{
			view_call('invalid');
			}

			$('#loading').hide();
				$('#uploaded_files_list').removeClass('well')
		},
		error : function (err_msg) {
			$('#loading').hide();
			/* 		var data_options = '{"text":"File not uploaded successfully. ","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
				var options = $.parseJSON(data_options);
				noty(options); */
				view_call();
		},
	});
});
$('#reset').click(function () {
	$('#uploaded_files_list').html("");
	$('#uploaded_files_list').removeClass('well')
	$('#upload_btn').prop('disabled', true);
});

$('.get_tab_name').click(function () {
	tab_name = $(this).attr('temp_tab');
});
$('.show_remarks').live('click', function () {
	var post_data = {
		'temp_table' : tab_name,
	}
	$.ajax({
		type : "POST",
		url : controller + 'get_temp_table_data',
		data : post_data,
		success : function (msg) {
			//console.log(msg);
			if ($.trim(msg) == 1) {
				$('#file_name').html("No remarks found");
			} else {
				$('#file_name').html(msg);
			}
			$('#rejected_files_modal').modal('show');
		},
	});
});

$('#rejected_file_table').on('click', '.reupload_file', function () {
	$('#rej_file_name').val(tab_name);
	$('#upload_file').click();
});
$('#student_data_upload_form').on('change', '#upload_file', function () {
	var file = $("input[name=upload_file]");
	var files = file[0].files;
	if (files.length > 0) {
		var file_data = $('#upload_file').val().split('\\').pop();
		if ($.trim(file_data.split('.')[0]) == $.trim(tab_name)) {
			console.log("Upload correct");
		} else {
			$('#err_status').html("<p>Wrong file is uploaded.</p>");
			$('#error_modal').modal('show');
			console.log("Wrong file uploaded");
		}
		$('#student_data_upload_form').submit();
	} else {
		console.log("No file selected");
	}
});
$('#student_data_upload_form').submit(function (e) {
	$('#loading').show();
	var form_data = new FormData(this);
	$.ajax({
		type : 'POST',
		url : controller + 're_upload_file',
		data : form_data,
		contentType : false,
		async : false,
		cache : false,
		processData : false,
		success : function (msg) {
			$('#loading').hide();
			if(msg == "fail0" || msg ==1){ view_call('rejected');}else{ view_call('processed');}
			//location.reload();
		},
		error : function (err_msg) {
			$('#loading').hide();
		},
	});
	e.preventDefalut();
});

$('.process_file').click(function () {
	$('#loading').show();
	var file_name = $(this).attr('file_name');
	$.ajax({
		type : 'POST',
		url : controller + 'process_file/' + file_name,
		data : '',
		success : function (msg) {
			$('#loading').hide();
			if(msg == "fail0" || msg ==1){ view_call('rejected');}else{ view_call('processed');}
		},
		error : function (err_msg) {
			$('#loading').hide();
			view_call();
		},
	});
});

$('.del_invalid_file').click(function(){
	var file_name = $(this).attr('file_name');
	$.ajax({
		type : 'POST',
		url : controller + 'delete_invalid_files/' + file_name,
		data : '',
		success : function (msg) {
			$('#loading').hide();
			//location.reload();
	/* 		var data_options = '{"text":"File(s) deleted successfully. ","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
			var options = $.parseJSON(data_options);
			noty(options); */
			view_call('invalid');
		},
		error : function (err_msg) {
			$('#loading').hide();
		},
	});
});

$('#delete_all_files').click(function(){
	$.ajax({
		type : 'POST',
		url : controller + 'empty_folder/',
		data : '',
		success : function (msg) {
			console.log(msg);
			$('#loading').hide();
			view_call('invalid');
			//location.reload();
		},
		error : function (err_msg) {
			$('#loading').hide();
		},
	});
});
