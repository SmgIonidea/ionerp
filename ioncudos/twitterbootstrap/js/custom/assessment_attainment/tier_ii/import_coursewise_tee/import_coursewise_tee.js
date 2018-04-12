
	//import assessment data (TEE Assessment Data Import)
	
	var base_url = $('#get_base_url').val();
	
	//Function is used to fetch program list
	function select_pgm_list() {
		var dept_id = $('#department').val();
		
		var post_data = {
			'dept_id': dept_id
		}
		
		$.ajax({type: "POST",
			url: base_url+'tier_ii/import_coursewise_tee/select_pgm_list',
			data: post_data,
			success: function(msg) {
				document.getElementById('program').innerHTML = msg;
			}
		});
	}
	
	//function to fetch curriculum list
	function select_crclm_list() {
		var pgm_id = $('#program').val();
		
		var post_data = {
			'pgm_id': pgm_id
		} 
		
		$.ajax({type: "POST",
			url: base_url+'tier_ii/import_coursewise_tee/select_crclm_list',
			data: post_data,
			success: function(msg) {
				document.getElementById('curriculum').innerHTML = msg;
			}
		});
	}
	
	//function to fetch term dropdown
	$('#curriculum').on('change',function() {
		//var crclm_id = document.getElementById('curriculum').value;
		var crclm_id = $('#curriculum').val();
		
		var post_data = {
			'crclm_id': crclm_id
		}
		
		$.ajax({type: "POST",
			url: base_url+'tier_ii/import_coursewise_tee/select_termlist',
			data: post_data,
			success: function(msg) {
				document.getElementById('term').innerHTML = msg;
			}
		});
	});
		
	//function to fetch course
	$('#term').on('change',function() {
		var dept_id = $('#department').val();
		var pgm_id = $('#program').val();
		var crclm_id = $('#curriculum').val();
		var term_id = $('#term').val();
		
		var post_data = {
			'dept_id': dept_id,
			'prog_id': pgm_id,
			'crclm_id': crclm_id,
			'term_id': term_id
		}
		
		$.ajax({type: "POST",
			url: base_url+'tier_ii/import_coursewise_tee/show_course',
			data: post_data,
			dataType: 'json',
			success: populate_table
		});
	});
	
	        /*
 * Function to check Target Levels are Exists are not
 * code added by : Mritunjay B S
 * Date : 25/11/2016
 */
$(document).on('click','.import_data_details',function(e){
    var crclm_id = $(this).attr('data-crclm_id');
    var term_id = $(this).attr('data-term_id');
    var crs_id = $(this).attr('data-crs_id');
    var url = $(this).attr('abbr_href');
    var link = '';
    var post_data = { 'crclm_id':crclm_id,
                      'term_id':term_id,
                      'crs_id':crs_id,
                  };
                  
    $.ajax({type: "POST",
			url: base_url + 'tier_ii/import_coursewise_tee/get_organisation_type',
			data: post_data,
			dataType: "JSON",
			success: function(msg) {
				if($.trim(msg.org_type) == 'org2'){
                                    if(msg.admin_hod_po_val == 1){
                                        link = base_url + 'tier_ii/attainment_level';
                                        $('#define_threshold_target').attr('href',link);
                                    }else{
                                        $('#link_stmt').hide();
                                        $('#link_stmt').empty();
                                        $('#link_stmt').html('Request Chairman(HoD) or Program Owner to add Thresholds/Targets OR Attainment Levels.');
                                        $('#link_stmt').show();
                                    }
				    
				}else{
                                    if(msg.admin_hod_po_val == 1){
                                        link = base_url + 'assessment_attainment/bl_po_co_threshold'; 
                                        $('#define_threshold_target').attr('href',link);
                                    }else{
                                        $('#link_stmt').hide();
                                        $('#link_stmt').empty();
                                        $('#link_stmt').html('Request Chairman(HoD) or Program Owner to add Thresholds/Targets OR Attainment Levels.');
                                        $('#link_stmt').show();
                                    }
				}
				
				if($.trim(msg.target_or_threshold) !=0 && $.trim(msg.org_type) == 'org2'){
					window.location = url;  
				}else if($.trim(msg.target_or_threshold) != 0 && $.trim(msg.org_type) == 'org1'){
					 window.location = url;
				}else{
					
					$('#target_or_threshold_warning_modal').modal('show');
				}
			}
		});
    
            });

        // Code added by Mritunjay B S
// Date: 27/12/2016.
$(document).on('click','.tee_qp_download_template',function(){
    var crclm_id = $(this).attr('data-crclm_id');
   var download_excel_url = $(this).attr('abbr_href');
   var post_data = {
       'crclm_id':crclm_id,
   };
   $.ajax({ type: "POST",
            url: base_url + 'tier_ii/import_coursewise_tee/check_student_uploaded_or_not',
            data: post_data,
            dataType: "JSON",
            success: function(msg) {
                console.log(msg.student_count);
                if($.trim(msg.student_count) != 0){
                    window.location = download_excel_url;
                }else{
                    var link  = base_url+'curriculum/student_list';
                    $('#students_upload_link').attr('href',link);
                    $('#students_not_uploaded_modal').modal('show');
                }
	}
		});
});
	//Function is used to generate table grid
	function populate_table(msg) {
		$('#example_table').dataTable().fnDestroy();
		$('#example_table').dataTable(
				{"aoColumns": [
						{"sTitle": "Sl No.", "mData": "sl_no"},
						{"sTitle": "Code", "mData": "crs_code"},
						{"sTitle": "Course Title", "mData": "crs_title"},
						{"sTitle": "Core / Elective", "mData": "crs_type_name"},
						{"sTitle": "Credits", "mData": "total_credits"},
						{"sTitle": "Total Marks", "mData": "total_marks"},
						{"sTitle": "Course Owner", "mData": "username"},
						{"sTitle": "Mode", "mData": "crs_mode"},
						{"sTitle": "Upload QP / View QP", "mData": "uploaded"},
						{"sTitle": "Template Actions", "mData": "export_import"},
                                                {"sTitle": "Consolidated CIA & TEE", "mData": "cia_tee"}
						//{"sTitle": "Import Status", "mData": "qp_status_flag"}
					], "aaData": msg,
					"sPaginationType": "bootstrap"});
	}
	
	//return back to main page
	function main_page() {
		window.location = base_url+'tier_ii/import_coursewise_tee/index';	
	}
	
	//drop temporary table on click of cancel
	function drop_temp_table() {
		var crs_id = $('#crs_id').val();
		
		var post_data = {
			'crs_id': crs_id
		}
		
		$.ajax({type: "POST",
			url: base_url+'tier_ii/import_coursewise_tee/drop_temp_table',
			data: post_data,
			success: function(msg) {
				
			}
		});
		window.location = base_url+'tier_ii/import_coursewise_tee/index';
	}
	
	//insert into main table
	function insert_into_main_table() {
		$('#loading').show();
		var qpd_id = $('#qpd_id').val();
		var crs_id = $('#crs_id').val();
		var crclm_id = $("#crclm_id").val(); 
		var term_id = $("#term_id").val();  
				
		var post_data = {
			'qpd_id': qpd_id,
			'crs_id': crs_id,
			'crclm_id':crclm_id,
			'term_id': term_id
		}
		
		$.ajax({type: "POST",
			url: base_url+'tier_ii/import_coursewise_tee/insert_into_student_table',
			data: post_data,
			success: function(msg) {
				if(msg == 0) {
					$('#remarks_pending').modal('show');
				} else if (msg == 1) {
					$('#insert_complete').modal('show');
				} else {
					$('#file_not_uploaded').modal('show');
				}
				$('#loading').hide();
			}
		});
	}
	
	//upload .csv file
	var uploader = document.getElementById('uploader');
	var crs_id = $('#crs_id').val();
	var qpd_id = $('#qpd_id').val();
	
	upclick({
		element: uploader,
		action: base_url+'tier_ii/import_coursewise_tee/to_database'+'/'+crs_id+'/'+qpd_id,
		oncomplete:
			function(response_data) {
				if($.trim(response_data) == '0') {
					$('#incorrect_file_name').modal('show');
				} else if($.trim(response_data) == '3') {
					$('#incorrect_file_header').modal('show');
				} else if($.trim(response_data) == '4') {
					$('#csv_file_empty').modal('show');
				} else {
					//display data in temp_generate_table
					$('#student_marks').html(response_data);
				}
			}
	});
	
	//to display data analysis modal
	function dataAnalysis() {
		var qpd_id = $('#qpd_id').val();
		
		var post_data = {
			'qpd_id': qpd_id
		}
		
		$.ajax({type: "POST",
			url: base_url+'tier_ii/import_coursewise_tee/dataAnalysis',
			data: post_data,
			beforeSend:function(){
				$('#loading').show();
			},
			success: function(msg) {
				document.getElementById('dataAnalysis_modal').innerHTML = msg;
				$('#loading').hide();
			}
		});
		$('#myModal_dataAnalysis').modal('show');
	}
	
	//drop main table - on confirm
	function drop_main_table_confirm() {
		$('#drop_main_table_confirmation').modal('show');
	}
	
	//drop main table
	function drop_main_table() {
		var qpd_id = $('#qpd_id').val();
		
		var post_data = {
			'qpd_id': qpd_id
		}
		
		$.ajax({type: "POST",
			url: base_url+'tier_ii/import_coursewise_tee/drop_main_table',
			data: post_data,
			success: function(msg) {
				
			}
		});
		window.location = base_url+'tier_ii/import_coursewise_tee/index';
	}
	
	//function to get & save data / values on key press
	$('#test').on("keyup", function() { 
		var para = $('#test').val(); var para1 = $('#unitid').val(); 
		save_marks(this.value)	
	});

	//function to save the data / values after key press
	function save_marks(value) { 
		var para = $('#test').val(); 
		var para1 = $('#unitid').val(); 
		var para2 = $('#qpd_id').val();
		if(para == ""){						
			msg.innerHTML = "This field is required";
			$("#test").val("00");
		} else if(!para.match(/^[0-9]+$/) ) {
			msg.innerHTML = "Enter only numbers";
		} else if(para.length > 3 ){
			msg.innerHTML = "Only 3 numbers";
		} 
		else {
			msg.innerHTML = "";
			var post_data = {
				'unitid': para1,
				'qpd_id': para2,
				'test' : para				
				};

			$.ajax({
				url: base_url+'tier_ii/import_coursewise_tee/save_txt',
				type: "POST",
				data: post_data,
				success: function(data) { 
					
				}
			});
		}
	}
