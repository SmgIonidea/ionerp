
	//student threshold

	var base_url = $('#get_base_url').val();

	function empty_all_divs() {
		$('#data_div').empty();
		$('#co_level_nav').empty();
		$('#clo_list_div').css({"display":"none"});
		$('#co_level_table_id').remove();
		$('#co_level_body').remove();
	}

	//List Page
	if ($.cookie('remember_crclm') != null) {
		// set the option to selected that corresponds to what the cookie is set to
		$('#crclm_id option[value="' + $.cookie('remember_crclm') + '"]').prop('selected', true);
		select_term();
	}

	//function to fetch term details
	function select_term() {
		$.cookie('remember_crclm', $('#crclm_id option:selected').val(), {expires: 90, path: '/'});
		empty_all_divs();
		var term_list_path = base_url + 'assessment_attainment/student_attainment/select_term';
		
		var crclm_id = $('#crclm_id').val();
		
		if(crclm_id == '') {
			$('#clo_thold').hide();
			$('#occasion').css({"display":"none"});
			$('#threshold_level').css({"display":"none"});
			$('#clo_list_div').css({"display":"none"});
			$('#cia_error_msg').css({"display":"none"});
			$('#student_dataAnalysis').css({"display":"none"});
		}
		
		var post_data = {
			'crclm_id': crclm_id
		}
		
		$.ajax({type: "POST",
			url: term_list_path,
			data: post_data,
			success: function(msg) {
				$('#term').html(msg);
				if ($.cookie('remember_term') != null) {
					// set the option to selected that corresponds to what the cookie is set to
					$('#term option[value="' + $.cookie('remember_term') + '"]').prop('selected', true);
					select_course();
				}
			}
		}); 
	}

	//function to fetch course details
	function select_course() {
		$.cookie('remember_term', $('#term option:selected').val(), {expires: 90, path: '/'});
		empty_all_divs();
		
		var course_list_path = base_url + 'assessment_attainment/student_attainment/select_course';
		var term_id = $('#term').val();
		
		if(term_id) {
			var post_data = {
				'term_id': term_id
			}
			
			$.ajax({type: "POST",
				url: course_list_path,
				data: post_data,
				success: function(msg) {
					$('#course').html(msg);	
					if ($.cookie('remember_course') != null) {
						// set the option to selected that corresponds to what the cookie is set to
						$('#course option[value="' + $.cookie('remember_course') + '"]').prop('selected', true);
						select_type();
					}
				}
			}); 
		} else {
			$('#clo_thold').hide();
			$('#occasion').css({"display":"none"});
			$('#threshold_level').css({"display":"none"});
			$('#clo_list_div').css({"display":"none"});
			$('#cia_error_msg').css({"display":"none"});
			$('#student_dataAnalysis').css({"display":"none"});
			$('#course').html('<option value="">Select Course</option>');
		}
	}
 
	//function to fetch type details
	function select_type() {
		$.cookie('remember_course', $('#course option:selected').val(), {expires: 90, path: '/'});
		empty_all_divs();
		
		$('#occasion_div').css({"display":"none"});
		var course_id = $('#course').val();
		
		if(course_id != '') {		
			$('#usn_div').css({"display":"none"});
			$('#threshold_div').css({"display":"none"});
			$('#cia_error_msg').empty();
			
			var course_id = $('#course').val();
			var crclm_id = $('#crclm_id').val();
			var term_id = $('#term').val();	
			var post_data =  {'course_id':course_id,'crclm_id':crclm_id,'term_id':term_id};
			
			$.ajax({
					type : "POST",
					url : base_url + 'assessment_attainment/student_threshold/fetch_type_data',
					data : post_data,
					dataType: 'json',
					success : function (msg) {
						$('#type_data').html(msg.type_list);
					
			}});
	
		//	$('#type_data').html('<option value=0>Select Type</option><option value=2>'+entity_cie+'</option><option value=1>'+entity_see+'</option>');
			
			
			
			
			$('#occasion').css({"display":"inline"});
			$('#threshold_level').css({"display":"inline"});
			var type_data = $('#type_data').val();
			
			if(type_data == 0) {
				var entity_id = $('#entity_id').val();
				var crclm_id = $('#crclm_id').val();
				var term_id = $('#term').val();
				var crs_id = $('#course').val();
				var post_data = {
					'entity_id': entity_id,
					'crclm_id': crclm_id,
					'term_id': term_id,
					'crs_id': crs_id
				}
				$.ajax({type: "POST",
						url: base_url + 'assessment_attainment/student_threshold/submit_threshold_details1',
						data: post_data,
						success: function (msg) {
							$('#student_dataAnalysis').html(msg);
							$('#loading').hide();
						}
					})
			}			
		} else {
			$('#clo_thold').hide();
			$('#occasion_div').css({"display":"none"});
			$('#clo_list_div').css({"display":"none"});
			$('#cia_error_msg').css({"display":"none"});
			$('#student_dataAnalysis').css({"display":"none"});
			$('#type_data').html('<option value=0>Select Type</option>');
		}
	}

	//function to display occasion dropdown if CIA is selected followed by threshold level
	// & if TEE is selected display threshold level dropdown only
	$('#add_form').on('change','#type_data',function() {	
		var type_data_id = $('#type_data').val();
		var tee_qpd_id = $('#tee_qpd_id').val(); 
		if(type_data_id == 5 ) {
			
			//TEE 
			empty_all_divs(); 	
			$('#occasion_div').css({"display":"none"});
			$('#clo_list_div').css({"display":"none"});
			$('#cia_error_msg').css({"display":"none"});
			$('#improvement_plan').hide();
			$('#stud_dtls').css({"display":"none"});
			$('#improvement_deatils').css({"display":"none"});
			$('#cia_error_msg').empty();	

			var course = $('#course').val();		
			var qpd_id = '';		
			var type = 'tee';
			var post_data1 = {
				'course' : course,
				'type'   : type
			}
				
			$.ajax({type: "POST",
				url: base_url+'assessment_attainment/student_threshold/fetch_tee_qpd_id',
				data: post_data1,
				success: function(msg) {				
					if(msg != 0){
						$('#tee_qpd_id').val(msg); 
					//	if( msg != 0){ 
				
							$('#threshold_div').css({"display":"inline"});
							$('#threshold_level').html('<option value=0>Select Threshold Level</option><option value=10>Above Threshold</option><option value=11>Below Threshold</option>');	
					//	}
						
					} else{
										$('#clo_thold').hide();empty_all_divs();
										$('#occasion_div').css({"display":"none"});
										$('#cia_error_msg').show();
										$('#cia_error_msg').html('<span><font color="maroon"><b>' + entity_see_full + ' (' + entity_see + ') Occasions are not defined</b></font></span>');
										$('#stud_dtls').css({"display":"none"});
										$('#improvement_deatils').css({"display":"none"});
						}
				}
			});
	
		
		}
		if(type_data_id == 3) {
			//CIA
			empty_all_divs(); 
			$('#threshold_div').css({"display":"none"});
			$('#clo_list_div').css({"display":"none"});
			$('#cia_error_msg').css({"display":"none"});
			 
			$('#import_crs_table ').hide();
			$('#improvement_plan').hide();
			$('#stud_dtls').css({"display":"none"});
			$('#improvement_deatils').css({"display":"none"});
			
			var crclm_id = $('#crclm_id').val();
			var term_id = $('#term').val();
			var crs_id = $('#course').val();
			
			var post_data1 = {
				'crclm_id' : crclm_id,
				'term_id' : term_id,
				'crs_id' : crs_id
			}
			
			//list occasions
			$.ajax({type: "POST",
				url: base_url+'assessment_attainment/student_threshold/select_occasion',
				data: post_data1,
				success: function(occasionList) {
					if(occasionList != 0){
						$('#occasion_div').css({"display":"inline"});
						var buttonTwo = document.getElementById("space");
						buttonTwo.style.marginRight = "288px";
						document.getElementById("occasion_div").appendChild(buttonTwo);
						$('#occasion').html(occasionList);
					} else {
						$('#clo_thold').hide();
						$('#occasion_div').css({"display":"none"});
						$('#cia_error_msg').show();
						$('#cia_error_msg').html('<span><font color="maroon"><b>' + entity_cie_full + ' (' + entity_cie + ') Occasions are not defined</b></font></span>');
					}
				}
			}); 
		}
		if(type_data_id == 6) {
			//CIA
			empty_all_divs(); 
			$('#threshold_div').css({"display":"none"});
			$('#clo_list_div').css({"display":"none"});
			$('#cia_error_msg').css({"display":"none"});
			 
			$('#import_crs_table ').hide();
			$('#improvement_plan').hide();
			$('#stud_dtls').css({"display":"none"});
			$('#improvement_deatils').css({"display":"none"});
			
			var crclm_id = $('#crclm_id').val();
			var term_id = $('#term').val();
			var crs_id = $('#course').val();
			
			var post_data1 = {
				'crclm_id' : crclm_id,
				'term_id' : term_id,
				'crs_id' : crs_id
			}
			
			//list occasions
			$.ajax({type: "POST",
				url: base_url+'assessment_attainment/student_threshold/select_occasion_mte',
				data: post_data1,
				success: function(occasionList) {
					if(occasionList != 0){
						$('#occasion_div').css({"display":"inline"});
						var buttonTwo = document.getElementById("space");
						buttonTwo.style.marginRight = "288px";
						document.getElementById("occasion_div").appendChild(buttonTwo);
						$('#occasion').html(occasionList);
					} else {
						$('#clo_thold').hide();
						$('#occasion_div').css({"display":"none"});
						$('#cia_error_msg').show();
						$('#cia_error_msg').html('<span><font color="maroon"><b>' + entity_cie_full + ' (' + entity_cie + ') Occasions are not defined</b></font></span>');
					}
				}
			}); 
		} /* else {
			// Both CIA & TEE option
			empty_all_divs(); 
	 		$('#occasion_div').css({"display":"none"});						
			$('#usn_div').css({"display":"none"});
			$('#threshold_div').css({"display":"none"});
			$('#clo_list_div').css({"display":"none"});
			$('#cia_error_msg').empty();
			$('#cia_error_msg').css({"display":"none"});
			
			$('#improvement_plan').hide();
			$('#stud_dtls').css({"display":"none"});
			
			if(type_data_id == 3) {
				$('#occasion_div').css({"display":"none"});						
				$('#cia_error_msg').empty();	
				var course = $('#course').val();		
				var qpd_id = '';		
				var type = 'both';
				var post_data1 = {
						'course' : course,
						'qpd_id' : qpd_id,
						'type'   : type
					}

				$('#threshold_div').css({"display":"inline"});
				$('#threshold_level').html('<option value=0>Select Threshold Level</option><option value=10>Above Threshold</option><option value=11>Below Threshold</option>');
			}
		} */
	});

	//function to display 
	$('#add_form').on('change','#occasion',function() {
		$('#cia_error_msg').empty();
		var type = 'cia';
		$('#improvement_plan').hide();
		$('#stud_dtls').css({"display":"none"});
		$('#improvement_deatils').css({"display":"none"});
		$('#clo_list_div').css({"display":"none"});
		$('#threshold_div').css({"display":"inline"});
		$('#threshold_level').html('<option value=0>Select Threshold Level</option><option value=10>Above Threshold</option><option value=11>Below Threshold</option>');
	}); 
	
	// CO level Threshold
	$('#add_form').on('change','#threshold_level',function(){
		$('#cia_error_msg').empty();
		var threshold_level = $('#threshold_level').val();
		var course = $('#course').val();		
		var type = $('#type_data').val();
		$('#clo_list_div').css({"display":"inline"});
		$('#improvement_plan').hide();
		$('#stud_dtls').css({"display":"none"});
		$('#improvement_deatils').css({"display":"none"});
		if(threshold_level == 0) {
			$('#clo_thold').hide();
			$('#clo_list_div').css({"display":"none"});
		}
	});	
		
	// function to fetch CO id Array for Listing Students based on Threshold
	var clo_id_array = new Array(); // global array variable declaration.

	//when user clicks on select all option - COs
	$('#clo_list_div').on('click', '.clo_check_all', function () {
		if ($(this).is(':checked')) {
			$('.clo_check').each(function () {
				$(this).attr('checked', true);
				clo_id_array.push($(this).val());
			});
			
			$('#clo_ids').val(clo_id_array);
			
			var clo_id = $('#clo_ids').val();
			
			if (clo_id != '') {
				$('#submit_clo').attr('disabled', false);
			} else {
				$('#submit_clo').attr('disabled', true);
			}
		} else {
			$('.clo_check').each(function () {
				$(this).attr('checked', false);
			});
			clo_id_array = [];
			$('#clo_ids').val('');
			
			var clo_id = $('#clo_ids').val();
			
			if (clo_id != '') {
				$('#submit_clo').attr('disabled', false);
			} else {
				$('#submit_clo').attr('disabled', true);
			}
		}
	});

	//when user selects individual COs
	$('#clo_list_div').on('click', '.clo_check', function () {
		if ($(this).is(':checked')) {
			clo_id_array.push($(this).val());
			$('#clo_ids').val(clo_id_array);
			
			var clo_id = $('#clo_ids').val();
			
			if (clo_id != '') {
				$('#submit_clo').attr('disabled', false);
			} else {
				$('#submit_clo').attr('disabled', true);
			}
		} else {
			var id = $(this).val();
			var index = $.inArray(id, clo_id_array);
			
			clo_id_array.splice(index, 1);
			$('#clo_ids').val(clo_id_array);
			
			var clo_id = $('#clo_ids').val();
			
			if (clo_id != '') {
				$('#submit_clo').attr('disabled', false);
			} else {
				$('#submit_clo').attr('disabled', true);
			}
		}
	});

	//function to get Course outcome details
	function getCOs() {
		var crs_id = $('#course').val();
		var tee_qpd_id = $('#tee_qpd_id').val();
		var type_data_id = $('#type_data').val();
		var occasion_id = $('#occasion').val();
		
		if(type_data_id == 5) {
			//tee
			qpd_id = $.trim(tee_qpd_id);
		} else if (type_data_id == 3) {
			//cia
			qpd_id = occasion_id;
		} else if (type_data_id == 6) {
			//cia
			qpd_id = occasion_id;
		} else {
			//cia and tee
			qpd_id = 'BOTH';
		}
			
		var post_data = {
			'crs_id': crs_id,
			'type_data_id': type_data_id,
			'qpd_id': qpd_id
		};
		
		$.ajax({type: "POST",
			url: base_url+'assessment_attainment/student_threshold/fetch_course_clo',
			dataType: 'json',
			data: post_data,
			success: function(list) {
				$('.clo_thold').val('');
				$('.clo_thold').show();
				
				//display table containing COs
				$('#clo_list_div').html(list['list_value']);
				//display threshold value in the text box
				$('.clo_thold').val(list['clo_thold']);
			}	
		});
	}
	
	//function to submit values to get list of students above or below a given threshold
	$('#submit_clo').on('click', function (e) {
		//prevent the page from reloading on click of submit button
		e.preventDefault();
		
		var course_id_array = new Array();
		var course_id;
		
		var entity_id = $('#entity_id').val();
		var crclm_id = $('#crclm_id').val();
		var term_id = $('#term').val();
		var crs_id = $('#course').val();
		var type_data_id = $('#type_data').val();
		var occasion_id = $('#occasion').val();
		var tee_qpd_id = $('#tee_qpd_id').val();
		var clostring  = $('#clo_thold').val();
		clo_thold = clostring.replace('%','');
		var threshold_level_id = $('#threshold_level').val();
		var clo_id = $('#clo_ids').val();
		
		//before proceeding check whether occasion dropdown is  selected or not when CIA is selected
		if(type_data_id == '2' && occasion_id == '') {
			$('#myModal_select_dropdown').modal('show');
		} else {
			var post_data = {
				'entity_id': entity_id,
				'crclm_id': crclm_id,
				'term_id': term_id,
				'crs_id': crs_id,
				'type_data_id': type_data_id,
				'occasion_id': occasion_id,
				'tee_qpd_id': tee_qpd_id,
				'clo_thold': clo_thold,
				'threshold_level_id': threshold_level_id,
				'clo_ids': clo_id
			};

			$('#loading').show();
			$.ajax({type: "POST",
				url: base_url + 'assessment_attainment/student_threshold/submit_threshold_details',
				data: post_data,
				datatype: "JSON",
				success: function (msg) {
					$('#student_dataAnalysis').html(msg);
					
					$('#loading').hide();
				}
			});
			
			//clear checkbox and clear hidden fields
			$('input').filter(':checkbox').prop('checked', false);
			$('#clo_ids').val('');
			clo_id_array = new Array();
			$('#submit_clo').attr('disabled', true);
			
			$('#clo_list_div').css({"display":"inline"});
			$('#cia_error_msg').css({"display":"inline"});
			$('#student_dataAnalysis').css({"display":"inline"});
		}
	});

	//function to allow numbers only
	function isNumber(evt) {
		evt = (evt) ? evt : window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		
		if (charCode > 31 && (charCode < 48 || charCode > 57)) {
			return false;
		}
		
		return true;
	}
	
	/* 
		function to submit all the values (tags with name attributes) to improvement plan page.
		form tag contains the path to improvement plan page
	*/
	$('#student_dataAnalysis').on('click','#improvement_plan', function() {
		var type_data_id = $('#type_data').val();
		var occasion_id = $('#occasion').val();
		var items=[], options=[];

		//Iterate all td's in second column
		$('#TABLE_DATA tbody tr td:nth-child(2)').each( function(){
		   //add item to array
		   items.push( $(this).text() );       
		});
		//restrict array to unique items
		var items = $.unique( items ); 
		$('#student_usn').val(items);
		
		//before proceeding check whether occasion dropdown is  selected or not when CIA is selected
		if((type_data_id == '3' || type_data_id == '6' ||  type_data_id == '0') && occasion_id == '') {
			$('#myModal_select_dropdown').modal('show');
		} else {
			$('#add_form').submit();
		}
	});
	
	//function to confirm before deleting improvement plan - modal
	$('#student_dataAnalysis').on("click", '.imp_plan_delete', function () {
		var abbr = $(this).attr('abbr');
		$('.imp_plan_sip_id').val(abbr);
		$('#myModal_imp_plan_delete').modal('show');
	});
	
	//function to delete improvement plan
	function delete_imp_plan() {
		var id_value = $('.imp_plan_sip_id').val();
		
		var post_data = {
			'imp_plan_sip_id': id_value
		};
		
		$('#loading').show();
		$.ajax({type: "POST",
			url: base_url + 'assessment_attainment/student_threshold/improvement_plan_delete',
			data: post_data,
			datatype: "JSON",
			success: function (msg) {
				//hide the row once the improvement plan is deleted
				var id_value = $('.imp_plan_sip_id').val();
				$('#imp_plan_row_'+id_value).hide();
				$('.imp_plan_sip_id').val('');
				$('#loading').hide();
			}
		});
	}
	
	//function to view improvement plan details - modal
	$("#student_dataAnalysis").on('click', '.btn_view_ip', function() {
		var id_value = $(this).attr('abbr');
		var crclm_id = $('#crclm_id').val();
		var term_id = $('#term').val();
		var crs_id = $('#course').val();
		var occasion_id = $('#occasion').val();
		
		var post_data = {
			'imp_plan_sip_id': id_value,
			'crclm_id': crclm_id,
			'term_id': term_id,
			'crs_id': crs_id,
			'occasion_id': occasion_id
		};
	
		$('#loading').show();
		$.ajax({type: "POST",
			url: base_url + 'assessment_attainment/student_threshold/view_improvement_plan',
			data: post_data,
			dataType: "json",
			success: function (msg) {
				$('#loading').hide();
				$('#problem_statement_view').html(msg['problem_statement']);
				$('#root_cause_view').html(msg['root_cause']);
				$('#corrective_action_view').html(msg['corrective_action']);
				$('#action_item_view').html(msg['action_item']);
				$("#student_usn_view").css('word-wrap', 'break-word');
				$('#student_usn_view').html(msg['student_usn']);				
				//to display the modal
				$('#myModal_view_ip').modal('show');
			}
		});
	});

$(document).ready(function () {
$('#student_dataAnalysis').on("click", '.upload_data_file', function () { //$('.upload_data_file').on('click',function(){		

var id = $(this).attr('abbr');
$('#upload_modal').modal('show');
	//	$('.imp_plan_sip_id').val(abbr);
	var post_data = {
		'id':id
	}	
	fetch_file_data(id);
	var uploader = document.getElementById('uploaded_file');	 
		upclick({
			element: uploader,
			action_params : post_data,
			multiple: true,
			action: base_url+'assessment_attainment/student_threshold/upload',
			oncomplete:
				function(response_data) {	
				
					fetch_file_data(id);
							if(response_data=="file_name_size_exceeded") {
								$('#file_name_size_exc').modal('show');
							} else if(response_data=="file_size_exceed") {
								$('#larger').modal('show');
							} else{
							var data_options = '{"text":"Your file has  been uploaded successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
							var options = $.parseJSON(data_options);
							noty(options);
							
							}
				}
		 }); 
});

	function fetch_file_data(sip_id){
		document.getElementById('res_guid_files').innerHTML = '';
		post_data =  {'sip_id':sip_id}
		$.ajax({type: 'POST',
		url: base_url+'assessment_attainment/student_threshold/fetch_files',
		data: post_data,
		success: function(data) {
			document.getElementById('res_guid_files').innerHTML = data;
		}});
	}
	
	$('.delete_file').live('click',function(){
		var uload_id = $(this).attr('data-id');
		var sip_id = $(this).attr('data-sip_id');
		$('#sip_id').val(sip_id);
		$('#uload_id').val(uload_id);
		$('#delete_modal').modal('show');
	});
				
	$('#delete_file').live('click',function(){	
		var uload_id = $('#uload_id').val(); 
		var sip_id=$('#sip_id').val();
		post_data =  {'uload_id':uload_id}
		$.ajax({type: 'POST',
		url: base_url+'assessment_attainment/student_threshold/delete_file',
		data: post_data,
		success: function(data) {
			var data_options = '{"text":"Your file has been deleted successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
			var options = $.parseJSON(data_options);
			noty(options);
			fetch_file_data(sip_id);
		}});
	});
	
	$('body').on('focus',".actual_date_data_res_detl", function(){
		$("#actual_date").datepicker( {	
				format: "dd-mm-yyyy",
		}).on('changeDate',function (ev){
			$(this).blur();
			$(this).datepicker('hide');
		});
		$(this).datepicker({	
				format: "dd-mm-yyyy",
				//endDate:'-1d' 
		}).on('changeDate',function (ev){
			$(this).blur();
			$(this).datepicker('hide');
		});
		
		$('#actual_btn').click(function(){
			$(document).ready(function(){
				$("#actual_date").datepicker().focus();	
			});
		});
	});	
	
	$('#save_res_guid_desc').live('click', function(e) {
		e.preventDefault();
		$('#myform').submit();
	});
	
	$('#myform').live('submit', function(e) {
		e.preventDefault();
		var form_data = new FormData(this);
		var form_val = new Array();
		
		$('.save_form_data').each(function() {
			//values fetched will be inserted into the array
			form_val.push($(this).val());
		});

		//check whether file any file exists or not
		 if(form_val.length > 0) {
			//if file exists
			$.ajax({
				  type: "POST",
				  url: base_url+'assessment_attainment/student_threshold/save_res_guid_desc',
				   data : form_data,
				   contentType : false,
				   cache : false,
				   processData : false,
				   success: function(msg) {
						if($.trim(msg) == 1) {
							//display success message on save
							var data_options = '{"text":"Your data has been updated successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
							var options = $.parseJSON(data_options);
							noty(options);
						} else {
							//display error message - if description and date could not be saved
							var data_options = '{"text":"Your data could not be saved.","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
							var options = $.parseJSON(data_options);
							noty(options);
						}
				  }
			});
		} else {
			//display error message if file does not exist and user tries to click save button
			var data_options = '{"text":"File needs to be uploaded.","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
			var options = $.parseJSON(data_options);
			noty(options);
		} 
	});
});

