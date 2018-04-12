
	//Manage Continuous Internal Assessment Occasions
	
	var base_url = $('#get_base_url').val(); 
	
	//Function to fetch term details
	if ($.cookie('remember_dept') !== null) {
		// set the option to selected that corresponds to what the cookie is set to
		$('#department option[value="' + $.cookie('remember_dept') + '"]').prop('selected', true);
		select_pgm_list();
	}

	//function to fetch program details for the dropdown
	function select_pgm_list() {
		$.cookie('remember_dept', $('#department option:selected').val(), {expires: 90, path: '/'});
		var dept_id = $('#department').val();
		
		var post_data = {
			'dept_id': dept_id
		};
		
		$.ajax({type: "POST",
			url: base_url+'assessment_attainment/cia/select_pgm_list',
			data: post_data,
			success: function(msg) {
				$('#pgm_id').html(msg);
				
				if ($.cookie('remember_pgm') !== null) {
					// set the option to selected that corresponds to what the cookie is set to
					$('#pgm_id option[value="' + $.cookie('remember_pgm') + '"]').prop('selected', true);
					select_crclm_list();
				}
			}
		});
	}
	
	//function to fetch curriculum details for the dropdown
	function select_crclm_list() {
		$.cookie('remember_pgm', $('#pgm_id option:selected').val(), {expires: 90, path: '/'});
		var pgm_id = $('#pgm_id').val();
		
		var post_data = {
			'pgm_id': pgm_id
		}; 
		
		$.ajax({type: "POST",
			url: base_url+'assessment_attainment/cia/select_crclm',
			data: post_data,
			success: function(msg) {
				 $('#crclm_id').html(msg);
				 
				 if ($.cookie('remember_crclm') !== null) {
					// set the option to selected that corresponds to what the cookie is set to
					$('#crclm_id option[value="' + $.cookie('remember_crclm') + '"]').prop('selected', true);
					select_termlist();
				}
			}
		});
	}
	
	//function to fetch term list for the dropdown
	function select_termlist() {
		$.cookie('remember_crclm', $('#crclm_id option:selected').val(), {expires: 90, path: '/'});
		var crclm_id = $('#crclm_id').val();
		
		var post_data = {
			'crclm_id': crclm_id
		};
		
		$.ajax({type: "POST",
			url: base_url+'assessment_attainment/cia/select_term',
			data: post_data,
			success: function(msg) {
				$('#term').html(msg);
				
				if ($.cookie('remember_term') !== null) {
					// set the option to selected that corresponds to what the cookie is set to
					$('#term option[value="' + $.cookie('remember_term') + '"]').prop('selected', true);
					GetSelectedValue();
				}
			}
		});
	}
	
	//function to display list of courses when department, program, curriculum and term are selected
	function GetSelectedValue() {
		$.cookie('remember_term', $('#term option:selected').val(), {expires: 90, path: '/'});
		
		var crclm_id = $('#crclm_id').val();
		var term_id = $('#term').val();
		var pgm_id = $('#pgm_id').val();
		
		var post_data = {
			'crclm_id': crclm_id,
			'term_id': term_id,
			'pgm_id': pgm_id
		}
		
		$.ajax({type: "POST",
			url: base_url + 'assessment_attainment/cia/show_course',
			data: post_data,
			dataType: 'json',
			success: populate_table
		});
	}

	//function to generate table to display all the courses - list page
	function populate_table(msg) {
		$('#example').dataTable().fnDestroy();
		$('#example').dataTable(
			{"aoColumns": [
                                        {"sTitle": "Sl No.", "mData": "sl_no"},
                                        {"sTitle": "Section", "mData": "section"},
					{"sTitle": "Code", "mData": "crs_code"},
					{"sTitle": "Course Title", "mData": "crs_title"},
					{"sTitle": "Mode", "mData": "crs_mode"},
					{"sTitle": "Course Type", "mData": "crs_type_name"},
					{"sTitle": "Instructor", "mData": "username"},
					{"sTitle": "Manage".cia_lang, "mData": "crs_id_edit"},
					{"sTitle": "Import Occasion", "mData": "import_occasion"},
					{"sTitle": "Status", "mData": "publish"},
			], "aaData": msg,
			"sPaginationType": "bootstrap"});
	}
        
        // Function to load modal for importing occasions.
        var glob_crs_name='';
        $('#cia_occasion_div').on('click','.import_occasions',function(){
            var course_id = $(this).data('crs_id');
            var curriculum_id = $(this).data('crclm_id');
            var section_id = $(this).attr('data-section_id');
            var dept_id = $('#department').val();
            var pgm_id = $('#pgm_id').val();
            var term_id = $('#term').val();
            var dept_name = $('#department option:selected').text();
            var program_name = $('#pgm_id option:selected').text();
            var crclm_name = $('#crclm_id option:selected').text();
            var term_name = $('#term option:selected').text();
            var course_name = $(this).data('course_name');
            var section_name = $(this).data('section_name');
            glob_crs_name = course_name;
            $('#course_id').val(course_id);
            $('#curriculum_id').val(curriculum_id);
            $('#to_section_id').val(section_id);
            $('#dept_id').val(dept_id);
            $('#program_id').val(pgm_id);
            $('#term_id').val(term_id);
            var post_data = {
                            'course_id':course_id,
                            'curriculum_id':curriculum_id,
                            'dept_id':dept_id,
                            'pgm_id':pgm_id,
                            'term_id':term_id,
                            'dept_name' : dept_name,
                            'program_name' : program_name,
                            'crclm_name' : crclm_name,
                            'term_name' : term_name,
                            'course_name' : course_name,
                            
                         };
            $.ajax({type: "POST",
			url: base_url + 'assessment_attainment/cia/get_dept_list',
			data: post_data,
			success: function(data){
                            $('#dept_name_font').empty();
                            $('#dept_name_font').html(dept_name);
                            $('#pgm_name_font').empty();
                            $('#pgm_name_font').html(program_name);
                            $('#crlcm_name_font').empty();
                            $('#crlcm_name_font').html(crclm_name);
                            $('#term_name_font').empty();
                            $('#term_name_font').html(term_name);
                            $('#crs_name_font').empty();
                            $('#crs_name_font').html(course_name);
                            $('#section_name_font').empty();
                            $('#section_name_font').html(section_name);
							$('#pop_dept_list').empty();
                            $('#pop_dept_list').html(data);
							$('#pop_pgm_list').empty();
                            $('#pop_pgm_list').html();
                            $('#pop_crclm_list').empty();
                            $('#pop_crclm_list').html($('<option value> Select Curriculum</option>'));
                            $('#pop_term_list').empty();
                            $('#pop_term_list').append($('<option value> Select Term</option>'));
                            $('#pop_term_list').trigger("chosen:updated");
                            $('#pop_course_list').empty();
                            $('#pop_course_list').append($('<option value> Select Course</option>'));
                            $('#pop_course_list').trigger("chosen:updated");
                            $('#check_box_div').remove();
                        }
		});
            $('#import_occasion_button').prop('disabled',true);
            $('#import_occasions').modal({dynamic:true});            
        });
        $("#select_form").validate({
	
        errorClass: "help-inline font_color",
        wrapper: "span",
        highlight: function (label) {
            $(label).closest('.control-group').removeClass('success').addClass('error');
        },
        errorPlacement: function (error, element) {
            if (element.parent().parent().hasClass("input-append")) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element.parent());
            }
        },
        onkeyup: false,
        onblur: false,
        success: function (error,label) {
            $(label).closest('.control-group').removeClass('error').addClass('success');
        } 
	
    });
        
		//function call for pgm list
	$('#pop_dept_list').on('change',function() {
		var dept_id = $('#pop_dept_list').val();
		var post_data = {
			'dept_id': dept_id
		};
		
		$.ajax({type: "POST",
			url: base_url+'assessment_attainment/cia/get_pgm_list',
			data: post_data,
			success: function(msg) {
				$('#pop_prog_list').empty();
				$('#pop_prog_list').html(msg);	
            }
		});
			
	});
		
	//function call for crclm_list
	$('#pop_prog_list').on('change',function(){
		var pgm_id = $('#pop_prog_list').val();
		var post_data = {
			'pgm_id': pgm_id
		};
		
		$.ajax({type: "POST",
			url: base_url+'assessment_attainment/cia/get_crclm_list',
			data: post_data,
			success: function(msg) {
				$('#pop_crclm_list').empty();
				$('#pop_crclm_list').html(msg);
			}
		});
	});
		
	// Function Call term list
	$('#pop_crclm_list').on('change',function(){
		var crclm_id = $('#pop_crclm_list').val();
		
		var post_data = {
			'crclm_id': crclm_id
		};
		
		$.ajax({type: "POST",
			url: base_url+'assessment_attainment/cia/get_term_list',
			data: post_data,
			success: function(msg) {
				$('#pop_term_list').empty();
				$('#pop_term_list').html(msg);
			}
		});
	});
        
	// Function to fetch the courses
	$('#pop_term_list').on('change',function() {
		var crclm_id = $('#pop_crclm_list').val();
		var term_id = $('#pop_term_list').val();
		var from_crs_id = $('#course_id').val();
		var post_data = {
			'crclm_id': crclm_id,
			'term_id':term_id,
			'from_crs_id': from_crs_id,
		};
		
		$.ajax({type: "POST",
			url: base_url+'assessment_attainment/cia/get_course_list',
			data: post_data,
			success: function(msg) {
				$('#pop_course_list').empty();
				$('#pop_course_list').html(msg);
			}
		});
	});
        
	// function to fetch the Ocassion data
	$('#import_occasions').on('click','#import_occasion_button',function() {
		var ao_id = new Array();
		var ao_name = new Array();
		var course_id = $('#course_id').val();
		var curriculum_id = $('#curriculum_id').val();
		var term_id = $('#term_id').val();
		var to_section_id = $('#to_section_id').val();
		var occasion_msg;
		
		$('.occasions').each(function(){
			if($(this).is(':checked')){
				ao_id.push($(this).val());
				ao_name.push($(this).attr('data-ao_name'));
			}
			
		});
		
		var post_data = {'ao_id':ao_id,'ao_name':ao_name, 'course_id':course_id, 'to_section_id':to_section_id, 'curriculum_id':curriculum_id, 'term_id':term_id};
		var validate = $("#select_form").valid();
		
		if(validate) {
			$('#loading').show();
			$.ajax({type: "POST",
				url: base_url+'assessment_attainment/cia/import_occasion',
				data: post_data,
				success: function(msg) {
					if(msg == 'true') {
						var data_options = '{"text":"Data imported successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
						var options = $.parseJSON(data_options);
						noty(options);
								$('#loading').hide();
					} else {
						$('#loading').hide();
						
						var occasion_array = msg.split(',');
						var size = occasion_array.length;
						var ocasion_ao_id = new Array();
						var ocasion_ao_name = new Array();
						
						for(var i = 0; i < size;) {
							var j = i + 1;
							ocasion_ao_id.push(occasion_array[i]);
							ocasion_ao_name.push(occasion_array[j]);
							i = i + 2;
						}
						
						occasion_msg = '<b>'+ocasion_ao_name+'</b> occasion already exist. Do you want to delete and re-insert the selected occasion for the course <b>'+glob_crs_name+'</b> ?'
								
						// ok button attributes 
						$('#import_delete_insert').attr('data-course_id',course_id);
						$('#import_delete_insert').attr('data-curriculum_id',curriculum_id);
						$('#import_delete_insert').attr('data-term_id',term_id);
						$('#import_delete_insert').attr('data-from_ao_id',ao_id);
						$('#import_delete_insert').attr('data-ao_id',ocasion_ao_id);
						$('#import_delete_insert').attr('data-ao_name',ocasion_ao_name);
						// cancel button attributes
						$('#import_continue').attr('data-course_id',course_id);
						$('#import_continue').attr('data-curriculum_id',curriculum_id);
						$('#import_continue').attr('data-term_id',term_id);
						$('#import_continue').attr('data-from_ao_id',ao_id);
						$('#import_continue').attr('data-ao_id',ocasion_ao_id);
						$('#import_continue').attr('data-ao_name',ocasion_ao_name);
						$('#occasion_existance_body_msg').empty();
						$('#occasion_existance_body_msg').html(occasion_msg);
						$('#occasion_existance').modal('show');
					}
				}
			});
		}
	});
        
	$('#occasion_existance').on('click','#import_delete_insert',function() {
		var crclm_id = $(this).data('curriculum_id');
		var term_id = $(this).data('term_id');
		var course_id = $(this).data('course_id');
		var to_section_id = $('#to_section_id').val();
		var from_ao_id = $(this).attr('data-from_ao_id');
		var ao_id = $(this).attr('data-ao_id');
		var ao_name = $(this).attr('data-ao_name');
		var post_data = {'crclm_id':crclm_id,'term_id':term_id,'course_id':course_id,'to_section_id':to_section_id,'from_ao_id':from_ao_id,'ao_id':ao_id,'ao_name':ao_name};
		
		$('#loading_popup').show();
		
		$.ajax({type: "POST",
		url: base_url+'assessment_attainment/cia/occasion_import_overwrite',
		data: post_data,
		success: function(msg) {
			if(msg == 'true') {
				var data_options = '{"text":"Data imported successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
			var options = $.parseJSON(data_options);
			noty(options);
				$('#loading_popup').hide();
				$('#occasion_existance').modal('hide');
			} else {
				var data_options = '{"text":"Data import is unsuccessfull.","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
				var options = $.parseJSON(data_options);
				noty(options);
					$('#loading_popup').hide();
				}				
			}
		});
	});
		
	$('#occasion_existance').on('click','#import_continue',function() {
		var crclm_id = $(this).data('curriculum_id');
		var term_id = $(this).data('term_id');
		var course_id = $(this).data('course_id');
		var ao_id = $(this).attr('data-ao_id');
		var from_ao_id = $(this).attr('data-from_ao_id');
		var ao_name = $(this).attr('data-ao_name');
		
		$('#loading_popup').show();
		
		var post_data = {
			'crclm_id':crclm_id,
			'term_id':term_id,
			'course_id':course_id,
			'from_ao_id':from_ao_id,
			'ao_id':ao_id,
			'ao_name':ao_name
		};
		
		$.ajax({type: "POST",
			url: base_url+'assessment_attainment/cia/occasion_import_continue',
			data: post_data,
			success: function(msg) {
				if(msg == 'true') {
					var data_options = '{"text":"Data imported successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
					var options = $.parseJSON(data_options);
					noty(options);
					$('#loading_popup').hide();
					$('#occasion_existance').modal('hide');
				} else {
					var data_options = '{"text":"Data Already Exist With Same Occasion name.","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
					var options = $.parseJSON(data_options);
					noty(options);
					$('#occasion_existance').modal('hide');
				}
			}
		});
	});
	
	// Function to get the list of sections
	$('#pop_course_list').on('change',function() {
		var crclm_id = $('#pop_crclm_list').val();
		var term_id = $('#pop_term_list').val();
		var course_id = $('#pop_course_list').val();
		
		var post_data = {
			'crclm_id': crclm_id,
			'term_id':term_id,
			'course_id':course_id
		};
		
		$.ajax({type: "POST",
			url: base_url+'assessment_attainment/cia/get_the_occasions',
			data: post_data,
			success: function(msg) {
				$('#check_box_div').remove();
				$('#occasion_list_div').empty();
				
				$('#assessment_occasions_list_tbl').dataTable();
				$('#occasion_list_div').html(msg);						   
				$('#assessment_occasions_list_tbl').dataTable({
					"bPaginate": false,
					"bFilter": false,
					"bSearchable":false,
					"fnDrawCallback": function () {
						$('.group').parent().css({'background-color': '#C7C5C5'});
					},
					"aoColumnDefs": [
						{"sType": "natural", "aTargets": [1]}
					],
				}).rowGrouping({iGroupingColumnIndex: 1,
				bHideGroupingColumn: true});							
			}
		});
	});
				
	// Function to get list of Occasions
	$('#pop_section_list').on('change',function() {
		var crclm_id = $('#pop_crclm_list').val();
		var term_id = $('#pop_term_list').val();
		var course_id = $('#pop_course_list').val();
		var section_id = $('#pop_section_list').val();
		
		var post_data = {
		'crclm_id': crclm_id,
					'term_id':term_id,
					'course_id':course_id,
					'section_id':section_id
		};
		
		$.ajax({type: "POST",
		url: base_url+'assessment_attainment/cia/get_the_occasions',
			data: post_data,
			success: function(msg) {
				$('#check_box_div').remove();
				$('#occasion_list_div').empty();
				$('#occasion_list_div').html(msg);
			}
		});
	});
        
	// Function to call increase the count of occasion_count textfield.
	$('#occasion_list_div').on('click','.occasions',function() {
		var oc_count;
		
		if($(this).is(':checked')) {
			oc_count = $('#occasion_count').val();
			oc_count++;
			$('#occasion_count').val(oc_count);
		} else {
			oc_count = $('#occasion_count').val();
			oc_count--;
			$('#occasion_count').val(oc_count);
		}
		
		var chk = $('#occasion_count').val();
		
		if(chk > 0) {
			$('#import_occasion_button').attr('disabled',false);
		} else {
			$('#import_occasion_button').attr('disabled',true);
			$('#occasion_select_all').prop('checked',false);
		}
	});
        
	// Function to select all checkboxes
	$('#occasion_list_div').on('click','#occasion_select_all',function() {
		var oc_count = $('#occasion_count').val();
		var i = $('#occasion_count').val();
		
		if($(this).is(':checked')) {
			$('.occasions').each(function() {
			   $(this).attr('checked',true);
				i++; 
			});
			
			$('#occasion_count').val(i);
		} else {
			var count = $('#occasion_count').val();
			
			if(count > 0) {
				$('.occasions').each(function() {
					$(this).attr('checked',false);
					i--; 
				});
				
				$('#occasion_count').val(i); 
			} else {
				
			}
		}
	   
		var chk = $('#occasion_count').val();
		
		if(chk > 0) {
			$('#import_occasion_button').attr('disabled',false);
		} else {
			$('#import_occasion_button').attr('disabled',true);
		}
	});
	
	//function to fetch qp link or individual details onSelect of assessment type
	$("#mt_list").on('change',function() {
		var mt_list_id = $(this).val();
		$('#ao_details_add').attr('disabled' , false);
		//check whether assessment type is QP(1) or individual(2)
		if(mt_list_id == 1) {
			$('#individual_row').hide();
			$('#qp_link_row').show();
		} else if(mt_list_id == 2) {
			$('#qp_link_row').hide();
			$('#individual_row').show();
		} else {
			$('#qp_link_row').hide();
			$('#individual_row').hide();
		}
	});
	
	//function to fetch PO of selected COs onchange - modal
	$("#modal_co_list , .co_list").on('change',function() {	
		var crclm_id = $('#cia_curriculum_id').val();
		var crs_id = $('#cia_course_id').val();
		var get_selected_co_id = $(this).val();
		
		var post_data = {
			'crclm_id': crclm_id,
			'crs_id': crs_id,
			'get_selected_co_id': get_selected_co_id
		}
		
		$.ajax({type: "POST",
			url: base_url + 'assessment_attainment/cia/co_po_list',
			data: post_data,
			async: false,
			dataType: 'json',
			success: function (msg) {	
			
				$('#modal_bl_list').html(msg['list'][0]['list_bl']);
				$('#bl_list').html(msg['list'][0]['list_bl']);
					$('.bl_list').multiselect({
						includeSelectAllOption: true,
						buttonWidth: '220px',
						nonSelectedText: 'Select Level',
						templates: {
						button: '<button type="button" class="multiselect btn btn-link dropdown-toggle multi_select_decoration" data-toggle="dropdown"></button>' 
					}
				});  				
				$('#modal_bl_list').multiselect('rebuild');	$('#bl_list').multiselect('rebuild');								
				if(msg['list'][0]['list_bl'] == "" && msg['clo_bl_flag'] == 1 && get_selected_co_id != null){$('.individual_bloom_error').html("Cannot create Occassion as Bloom\'s Level are mandatory for this course.<br/><a href="+ base_url + 'curriculum/clo' +"> Click here to Map Bloom's Level</a>"); 
				$('#ao_details_add').attr('disabled' , true);
				}else{
				$('.individual_bloom_error').html(""); $('#ao_details_add').attr('disabled' , false);
				}
			}
		});
	});
	
	//function to allow letters, numbers and underscore for AO Description 
	$("#ao_description").on("keyup", function() { 
		check(this.value);
	});

	function check(value) {
		if(value){
			if(!value.match(/^[a-zA-Z0-9\_]+$/)){
				msg1.innerHTML = "Letters, numbers & underscore are allowed."; 
				return 1;
			}
			else{
				msg1.innerHTML = " ";
                                return  0;
			}
		}
	}
	
	//function to insert AO details - save button
	$('#ao_add_update_form').on('click','#ao_details_add', function() {
		//disable button till successful update message or an error message id displayed
		$('#ao_details_add').attr('disabled', 'disabled');
        var ao=  $('#ao_details_add').val();		
		var crclm_id = $('#cia_curriculum_id').val(); 
		var term_id = $('#cia_term_id').val();
		var crs_id = $('#cia_course_id').val();
		var section_id = $('#section_id').val();
		var ao_name = $('#ao_name').val();
		var ao_description = $('#ao_description').val();  
		var ao_list = $('#ao_list').val();
		var mt_list = $('#mt_list').val();
		var cia_total_weightage = $('#cia_total_weightage').val();
		var weightage = $('#weightage').val();
		var max_marks = $('#max_marks').val();
		var co_list = $('#co_list').val();
		var bl_list = $('#bl_list').val();
        var rubrics_text = $('#mt_list option:selected').text();
                
		//check whether entered weightage and max marks are integers or not
		var numberRegex = /^[+-]?\d+(\.\d+)?([eE][+-]?\d+)?$/;
                var ao_val = check( ao_description ); 
		if(numberRegex.test(weightage) && numberRegex.test(max_marks)) {
		   //if number, do nothing
		} else {
			//else replace character/symbol by zero
			weightage = 0;
		}
		
		if(mt_list == 1) {
			//QP
			var post_data = {
				'crclm_id': crclm_id,
				'term_id': term_id,
				'crs_id': crs_id,
				'section_id': section_id,
				'ao_name': ao_name,
				'ao_description': ao_description,
				'ao_list': ao_list,
				'mt_list': mt_list,
				'cia_total_weightage': cia_total_weightage,
				'weightage': weightage,
				'max_marks': max_marks
			}
		} else if($.trim(rubrics_text) == 'Rubrics'){
                    //Rubrics
			var post_data = {
				'crclm_id': crclm_id,
				'term_id': term_id,
				'crs_id': crs_id,
				'section_id': section_id,
				'ao_name': ao_name,
				'ao_description': ao_description,
				'ao_list': ao_list,
				'mt_list': mt_list,
				'cia_total_weightage': cia_total_weightage,
				'weightage': weightage,
				'max_marks': max_marks
			}
                } 
                    else {
			//individual
			var post_data = {
				'crclm_id': crclm_id,
				'term_id': term_id,
				'crs_id': crs_id,
				'section_id': section_id,
				'ao_name': ao_name,
				'ao_description': ao_description,
				'ao_list': ao_list,
				'mt_list': mt_list,
				'cia_total_weightage': cia_total_weightage,
				'weightage': weightage,
				'max_marks': max_marks,
				'co_list': co_list,
				'bl_list': bl_list
			}
		}
		
		//check if all fields are filled
		if(ao_name && ao_description && ao_list && mt_list && max_marks) {
            if(ao_val == 0) {
				if(mt_list == 1 || $.trim(rubrics_text) == 'Rubrics') {
					//QP
					$.ajax({type: "POST",
						url: base_url + 'assessment_attainment/cia/ao_data_insert',
						data: post_data,
						dataType: 'json',
						success: function(msg) {                                   
								var post_crs_data = {
									'crclm_id': crclm_id,
									'crs_id': crs_id,
									'section_id':section_id,
								}
								
								$.ajax({type: "POST",
									url: base_url + 'assessment_attainment/cia/generate_table_data',
									data: post_crs_data,
									success:[populate_ao_table,reset_achive]
								});
								$('#ao_name').val(msg);
								//display success message
								var data_options = '{"text":"Your data has been saved successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
								var options = $.parseJSON(data_options);
								noty(options); 
								
								$('#individual_row').hide();
								$('#qp_link_row').show();
								
								//enable button
								$('#ao_details_add').removeAttr('disabled');
						}
					});
				} else if(mt_list == 2 && co_list && bl_list) {
					//individual
					$.ajax({type: "POST",
						url: base_url + 'assessment_attainment/cia/ao_data_insert',
						data: post_data,
						dataType: 'json',
						success: function(msg) {
							var post_crs_data = {
								'crclm_id': crclm_id,
								'crs_id': crs_id,
								'section_id':section_id
							}
							
							$.ajax({type: "POST",
								url: base_url + 'assessment_attainment/cia/generate_table_data',
								data: post_crs_data,
								success: [populate_ao_table,reset_achive]
							});

							//display success message
							var data_options = '{"text":"Your data has been updated successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
							var options = $.parseJSON(data_options);
							noty(options);

							$('#individual_row').hide();
							$('#qp_link_row').show();
							
							//enable button
							$('#ao_details_add').removeAttr('disabled');
							$('#ao_name').val(msg);
						}
					});
				} else {
					//display validation message
					var data_options = '{"text":"All fields must be filled before proceeding.","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
					var options = $.parseJSON(data_options);
					noty(options);
					
					//enable button
					$('#ao_details_add').removeAttr('disabled');
				}
            } else {
				//enable button
				$('#ao_details_add').removeAttr('disabled');
            }
		} else {
			//display validation message
			var data_options = '{"text":"All fields must be filled before proceeding.","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
			var options = $.parseJSON(data_options);
			noty(options);
			
			//enable button
			$('#ao_details_add').removeAttr('disabled');
		}
	});
	
	function reset_achive() {
		var ao_name = $('#ao_name').val(); 
		$("#ao_add_form").trigger('reset');
		$('#ao_name').val(ao_name); 
		$("#co_list").multiselect("clearSelection");	
		$("#bl_list").multiselect("clearSelection");	
		$("#po_dropdown").multiselect("clearSelection");
		$("#co_list").multiselect( 'refresh' );
		$("#ao_list").selectmenu( 'refresh' );
		$("#bl_list").multiselect( 'refresh' );
		$("#po_dropdown").multiselect( 'refresh' );
	}
	
	$('#modal_ao_description').on("keyup", function() { 
		describe(this.value);
	});
	
	function describe(value) {
		if(value){
			if(!value.match(/^[a-zA-Z0-9\_]+$/)) {
				msg2.innerHTML = "Letters, numbers & underscore are allowed."; 
				return 1;
			} else {
				msg2.innerHTML = " ";             
				return 0;
			}
		}
	}
	
	//function to load modal for editing AO details
	$('#ao_data_table').on('click','.assessment_edit_modal', function(e) {
		e.preventDefault();
		
		//fetch all the details required to display in the modal - refer controller
		var pgm_id = $('#pgm_id').val();
		var crclm_id = $(this).data("crclm_id");
		var crs_id = $(this).data("crs_id");
		var ao_id = $(this).data("ao_id");
		var qpd_id = $(this).data("qpd_id");
		var ao_name = $(this).data("ao_name");
		var ao_type = $(this).data("ao_type");
		var ao_description = $(this).data("ao_description");
		var ao_method_id = $(this).data("ao_method_id");
		var mt_details_id = $(this).data("mt_details_id");
		var weightage = $(this).data("weightage");
		var max_marks = $(this).data("max_marks");
		var section_id = $(this).data("section_id");
                $('#ao_method_id').val(ao_method_id);
                occasion_edit_details(pgm_id,crclm_id,crs_id,ao_id,section_id,qpd_id,ao_name,ao_description,weightage,max_marks,mt_details_id,ao_method_id);
//                if(qpd_id !=0 && ao_type == 'Rubrics'){
//                    $('#qp_existance_modal_ok').attr('data-pgm_id',pgm_id);
//                    $('#qp_existance_modal_ok').attr('data-qpd_id',qpd_id);
//                    $('#qp_existance_modal_ok').attr('data-ao_id',ao_id);
//                    $('#qp_existance_modal_ok').attr('data-crclm_id',crclm_id);
//                    $('#qp_existance_modal_ok').attr('data-crs_id',crs_id);
//                    $('#qp_existance_modal_ok').attr('data-ao_name',ao_name);
//                    $('#qp_existance_modal_ok').attr('data-ao_type',ao_type);
//                    $('#qp_existance_modal_ok').attr('data-ao_description',ao_description);
//                    $('#qp_existance_modal_ok').attr('data-ao_method_id',ao_method_id);
//                    $('#qp_existance_modal_ok').attr('data-mt_details_id',mt_details_id);
//                    $('#qp_existance_modal_ok').attr('data-weightage',weightage);
//                    $('#qp_existance_modal_ok').attr('data-max_marks',max_marks);
//                    $('#qp_existance_modal_ok').attr('data-section_id',section_id);
//                    $('#rubrics_qp_exist_warning_modal').modal('show');
//                }else if(qpd_id ==0 && ao_type == 'Rubrics'){
//                    $('#rubrics_modal_ok').attr('data-pgm_id',pgm_id);
//                    $('#rubrics_modal_ok').attr('data-qpd_id',qpd_id);
//                    $('#rubrics_modal_ok').attr('data-ao_id',ao_id);
//                    $('#rubrics_modal_ok').attr('data-crclm_id',crclm_id);
//                    $('#rubrics_modal_ok').attr('data-crs_id',crs_id);
//                    $('#rubrics_modal_ok').attr('data-ao_name',ao_name);
//                    $('#rubrics_modal_ok').attr('data-ao_type',ao_type);
//                    $('#rubrics_modal_ok').attr('data-ao_description',ao_description);
//                    $('#rubrics_modal_ok').attr('data-ao_method_id',ao_method_id);
//                    $('#rubrics_modal_ok').attr('data-mt_details_id',mt_details_id);
//                    $('#rubrics_modal_ok').attr('data-weightage',weightage);
//                    $('#rubrics_modal_ok').attr('data-max_marks',max_marks);
//                    $('#rubrics_modal_ok').attr('data-section_id',section_id);
//                    $('#rubrics_warning_modal').modal('show');
//                }else{
//                    occasion_edit_details(pgm_id,crclm_id,crs_id,ao_id,section_id,qpd_id,ao_name,ao_description,weightage,max_marks,mt_details_id,ao_method_id);
//                }
	});
	$('#rubrics_qp_exist_warning_modal').on('click','#qp_existance_modal_ok',function(){
            var pgm_id = $('#pgm_id').val();
		var crclm_id = $(this).data("crclm_id");
		var crs_id = $(this).data("crs_id");
		var ao_id = $(this).data("ao_id");
		var qpd_id = $(this).data("qpd_id");
		var ao_name = $(this).data("ao_name");
		var ao_type = $(this).data("ao_type");
		var ao_description = $(this).data("ao_description");
		var ao_method_id = $(this).data("ao_method_id");
		var mt_details_id = $(this).data("mt_details_id");
		var weightage = $(this).data("weightage");
		var max_marks = $(this).data("max_marks");
		var section_id = $(this).data("section_id");
                var post_data = {
			'pgm_id': pgm_id,
			'crclm_id': crclm_id,
			'crs_id': crs_id,
			'ao_id': ao_id,
			'section_id': section_id,
			'qpd_id': qpd_id,
		};
                
                $.ajax({type: "POST",
			url: base_url + 'assessment_attainment/cia/delete_rubrics_data_qp_data',
			data: post_data,
			async: false,
			//dataType: 'json',
			success: function (msg) {				
				if($.trim(msg) == 'true' ){
                                    occasion_edit_details(pgm_id,crclm_id,crs_id,ao_id,section_id,qpd_id,ao_name,ao_description,weightage,max_marks,mt_details_id,ao_method_id);
                                }else{
                                    
                                }
			}
		});
                
            
        });
        
        $('#rubrics_warning_modal').on('click','#rubrics_modal_ok',function(){
            var pgm_id = $('#pgm_id').val();
		var crclm_id = $(this).data("crclm_id");
		var crs_id = $(this).data("crs_id");
		var ao_id = $(this).data("ao_id");
		var qpd_id = $(this).data("qpd_id");
		var ao_name = $(this).data("ao_name");
		var ao_type = $(this).data("ao_type");
		var ao_description = $(this).data("ao_description");
		var ao_method_id = $(this).data("ao_method_id");
		var mt_details_id = $(this).data("mt_details_id");
		var weightage = $(this).data("weightage");
		var max_marks = $(this).data("max_marks");
		var section_id = $(this).data("section_id");
                
                var post_data = {
			'pgm_id': pgm_id,
			'crclm_id': crclm_id,
			'crs_id': crs_id,
			'ao_id': ao_id,
			'section_id': section_id,
			'qpd_id': qpd_id,
		};
                
                $.ajax({type: "POST",
			url: base_url + 'assessment_attainment/cia/delete_rubrics_data_qp_data',
			data: post_data,
			async: false,
			//dataType: 'json',
			success: function (msg) {				
				if($.trim(msg) == 'true' ){
                                    occasion_edit_details(pgm_id,crclm_id,crs_id,ao_id,section_id,qpd_id,ao_name,ao_description,weightage,max_marks,mt_details_id,ao_method_id);
                                }else{
                                    
                                }
			}
		});
                
        });
        function occasion_edit_details(pgm_id,crclm_id,crs_id,ao_id,section_id,qpd_id,ao_name,ao_description,weightage,max_marks,mt_details_id,ao_method_id){
                                var post_map_data = {
			'pgm_id': pgm_id,
			'crclm_id': crclm_id,
			'crs_id': crs_id,
			'ao_id': ao_id,
			'section_id': section_id,
		};
		
		//fetch all the COs, BL and POs and also the mapped ids
		//variables to capture dropdown list
		var aolistItems;
		var atlistItems;
		var colistItems;
		var bllistItems;
		
		$.ajax({type: "POST",
			url: base_url + 'assessment_attainment/cia/modal_edit_co_bl_po_data',
			data: post_map_data,
			dataType: 'json',
			success: function(msg) {
				//clear all dropdown's before displaying(to avoid repetition of values in the dropdown)
				$("#modal_ao_method_id").html("");
				$("#modal_mt_details_id").html("");
				$("#modal_co_list").html("");
				$("#modal_bl_list").html("");
			
				//get length of all dropdown's
				//ao method and assessment type length
				var ao_method_count = msg['edit_ao_method_data'].length;
				var assessment_type_count = msg['edit_mt_details_data'].length;
				
				//co, bl and po list length
				var co_list_count = msg['edit_co_details_data'].length;
				var bl_list_count = msg['edit_bl_details_data'].length;
				
				//co, bl and po mapped length
				var co_map_list_count = msg['mapped_co_data'].length;
				var bl_map_list_count = msg['mapped_bl_data'].length;
				//var po_map_list_count = msg['mapped_po_data'].length;
				
				//AO method dropdown
				for(var i = 0; i < ao_method_count; i++) {
					//AO method dropdown selected == selected
					if(msg['edit_ao_method_data'][i]['ao_method_id'] == ao_method_id) {
						aolistItems = $('<option></option>').val(msg['edit_ao_method_data'][i]['ao_method_id']).attr('selected',true).text(msg['edit_ao_method_data'][i]['ao_method_name']);
					} else {
						aolistItems = $('<option></option>').val(msg['edit_ao_method_data'][i]['ao_method_id']).text(msg['edit_ao_method_data'][i]['ao_method_name']);
					}
					
					//place value in the ao method id in the modal
					$('#modal_ao_method_id').append(aolistItems);
				}
				
				//assessment type dropdown
				for(var i = 0; i< assessment_type_count; i++) {
					//assessment type dropdown selected == selected
					if(msg['edit_mt_details_data'][i]['mt_details_id'] == mt_details_id) {
						atlistItems = $('<option></option>').val(msg['edit_mt_details_data'][i]['mt_details_id']).attr('selected',true).text(msg['edit_mt_details_data'][i]['mt_details_name']);
					} else {
						atlistItems = $('<option></option>').val(msg['edit_mt_details_data'][i]['mt_details_id']).text(msg['edit_mt_details_data'][i]['mt_details_name']);
					}
					
					//place value in the assessment method id in the modal
					$('#modal_mt_details_id').append(atlistItems);
				}
				
				//check whether assessment type is QP(1) or individual(2)
				if(mt_details_id == 1) {
					//QP
					$('#modal_individual_row').hide();
					$('#modal_qp_link_row').show();
				} else if(mt_details_id == 2) {
					//individual
					$('#modal_qp_link_row').hide();
					$('#modal_individual_row').show();
					
					//CO(11) dropdown
					for(var i = 0; i < co_list_count; i++) {
						colistItems = $('<option title="'+msg['edit_co_details_data'][i]['clo_statement']+'"></option>').val(msg['edit_co_details_data'][i]['clo_id']).text(msg['edit_co_details_data'][i]['clo_code']);
						
						//place value in the co id in the modal
						$('#modal_co_list').append(colistItems);
					}
					
					//selected CO & CO mapped length
					var co_map_data = msg['mapped_co_data'].length;
					for(var j = 0; j < co_map_data; j++){
						$('#modal_co_list option[value='+msg['mapped_co_data'][j]['actual_mapped_id']+']').attr('selected',true);
					}
					
					//multi select CO
					$('.modal_co_list').multiselect({
						includeSelectAllOption: true,

						buttonWidth: '220px',
						nonSelectedText: 'Select CO',
						templates: {
						button: '<button id="" type="button" class="multiselect dropdown-toggle btn btn-link multi_select_decoration" data-toggle="dropdown"></button>' 
						}
					});
					$('.modal_co_list').multiselect('rebuild');
					
					//BL(23) dropdown
					for(var i = 0; i < bl_list_count; i++) {
						bllistItems = $('<option title="'+msg['edit_bl_details_data'][i]['description']+' - '+msg['edit_bl_details_data'][i]['learning']+'"></option>').val(msg['edit_bl_details_data'][i]['bloom_id']).text(msg['edit_bl_details_data'][i]['level']);
						
						//place value in the co id in the modal
						$('#modal_bl_list').append(bllistItems);
					}
					
					//selected BL & BL mapped length
					var bl_map_data = msg['mapped_bl_data'].length;
					for(var j = 0; j < bl_map_data; j++) {
						$('#modal_bl_list option[value='+msg['mapped_bl_data'][j]['actual_mapped_id']+']').attr('selected',true);
						$('.modal_bl_list').append(bl_map_data);
					}
					$('.modal_bl_list').multiselect({
						includeSelectAllOption: true,
						buttonWidth: '220px',
						nonSelectedText: 'Select level',
						templates: {
						button: '<button id="" type="button" class="multiselect dropdown-toggle btn btn-link multi_select_decoration" data-toggle="dropdown"></button>' 
						}
					});
					$('.modal_bl_list').multiselect('rebuild');
				} else {
					//none of the assessment types selected
					$('#modal_qp_link_row').hide();
					$('#modal_individual_row').hide();
				}
			}
		});
                //pass remaining values to the modal
		$("#ao_edit_modal #modal_qpd_id").val(qpd_id);
		$("#ao_edit_modal #modal_ao_id").val(ao_id);
		$("#ao_edit_modal #modal_ao_name").val(ao_name);
		$("#ao_edit_modal #modal_ao_description").val(ao_description);
		$("#ao_edit_modal #modal_weightage").val(weightage);
		$("#ao_edit_modal #modal_max_marks").val(max_marks);
		
		//display edit assessment occasion modal
		$("#ao_edit_modal").modal('show');
        }
        
	//function to fetch qp link or individual details onSelect of assessment type in the edit modal - onchange
	$("#modal_mt_details_id").on('change',function() {
		var mt_list_id = $(this).val();
	
		//check whether assessment type is QP(1) or individual(2)
		if(mt_list_id == 1) {
			//warning message - onchange from QP to individual
			var data_options = '{"text":"Warning - If question paper is defined and student-wise marks are imported, all the data will be erased.","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
			var options = $.parseJSON(data_options);
			noty(options);
		
			//show qp table row and hide individual table row
			$('#modal_individual_row').hide();
			$('#modal_qp_link_row').show();
		} else if(mt_list_id == 2) {
			var pgm_id = $('#pgm_id').val();
			var crclm_id = $('#cia_curriculum_id').val();
			var term_id = $('#cia_term_id').val();
			var crs_id = $('#cia_course_id').val();
			var ao_id = $('#modal_ao_id').val();
		
			var post_map_data = {
				'pgm_id': pgm_id,
				'crclm_id': crclm_id,
				'crs_id': crs_id,
				'ao_id': ao_id,
			}
		
			//warning message - onchange from QP to individual
			var data_options = '{"text":"Warning - If question paper is defined and student-wise marks are imported, all the data will be erased.","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
			var options = $.parseJSON(data_options);
			noty(options);
		
			//hide qp table row and show individual table row
			$('#modal_qp_link_row').hide();
			$('#modal_individual_row').show();
			
			$.ajax({type: "POST",
				url: base_url + 'assessment_attainment/cia/modal_edit_co_bl_po_data',
				data: post_map_data,
				dataType: 'json',
				success: function(msg) {
					//clear all dropdown's before displaying(to avoid repetition of values in the dropdown)
					$("#modal_co_list").html("");
					$("#modal_bl_list").html("");
				
					//get length of co, bl and po list dropdown's
					var co_list_count = msg['edit_co_details_data'].length;
					var bl_list_count = msg['edit_bl_details_data'].length;
					
					//co, bl and po mapped length
					var co_map_list_count = msg['mapped_co_data'].length;
					var bl_map_list_count = msg['mapped_bl_data'].length;
					//var po_map_list_count = msg['mapped_po_data'].length;
					
					//CO(11) dropdown
					for(var i = 0; i < co_list_count; i++) {
						colistItems = $('<option></option>').val(msg['edit_co_details_data'][i]['clo_id']).text(msg['edit_co_details_data'][i]['clo_code']);
						
						//place value in the co id in the modal
						$('#modal_co_list').append(colistItems);
					}
					
					//multi select CO
					$('.modal_co_list').multiselect({
						includeSelectAllOption: true,
						buttonWidth: '220px',
						nonSelectedText: 'Select CO',
						templates: {
						button: '<button type="button" class="multiselect dropdown-toggle btn btn-link multi_select_decoration" data-toggle="dropdown"></button>' 
						}
					});
					
					$('.modal_co_list').multiselect('rebuild');
					
					//BL(23) dropdown
					$('.modal_bl_list').multiselect({
						includeSelectAllOption: true,
						buttonWidth: '220px',
						nonSelectedText: 'Select Level',
						templates: {
						button: '<button type="button" class="multiselect dropdown-toggle btn btn-link multi_select_decoration" data-toggle="dropdown"></button>' 
						}
					});
					
					$('.modal_bl_list').multiselect('rebuild'); 					
				}
			});
		} else {
			//hide qp and individual table row
			$('#modal_qp_link_row').hide();
			$('#modal_individual_row').hide();
		}
	});
	
	//function to delete AO details from the table
	$('#ao_data_table').on('click','.ao_delete', function(e) {
		e.preventDefault();
		
		//fetch all the details required to display in the modal - refer controller
		var modal_delete_crs_id = $(this).data("delete_crs_id");
		var modal_delete_ao_id = $(this).data("delete_ao_id");
		var modal_delete_qpd_id = $(this).data("delete_qpd_id");
		
		//pass the values to the modal 
		$("#ao_delete_modal #modal_delete_crs_id").val(modal_delete_crs_id);
		$("#ao_delete_modal #modal_delete_ao_id").val(modal_delete_ao_id);
		$("#ao_delete_modal #modal_delete_qpd_id").val(modal_delete_qpd_id);
		
		//display modal
		$("#ao_delete_modal").modal('show');
	});
	
	//function to delete AO details
	$('#delete_ao_confirm').on('click', function() {
		var modal_delete_crs_id = $('#modal_delete_crs_id').val();
		var modal_delete_ao_id = $('#modal_delete_ao_id').val();
		var modal_delete_qpd_id = $('#modal_delete_qpd_id').val();
		var section_id = $('#section_id').val();
		var crclm_id = $('#cia_curriculum_id').val();
		var post_data = {
			'modal_delete_ao_id': modal_delete_ao_id,
			'modal_delete_qpd_id': modal_delete_qpd_id,
			'crclm_id' : crclm_id,
			'crs_id': modal_delete_crs_id,
			'section_id': section_id,
		}
		
		//loading icon to be displayed
		$('#loading').show();
		$.ajax({type: "POST",
			url: base_url + 'assessment_attainment/cia/delete_ao_confirm',
			data: post_data,
			dataType: 'json',
			success: function(msg) {			
			$('#ao_name').val(msg);
				var post_crs_data = {
					'crs_id': modal_delete_crs_id,
					'section_id': section_id,
				}
				
				//repopulate the table
				$.ajax({type: "POST",
					url: base_url + 'assessment_attainment/cia/generate_table_data',
					data: post_crs_data,
					success: [populate_ao_table,reset_achive]
				});
				
				//hide loading icon
				$('#loading').hide();
			}
		});
	});
	
	//function to update AO details - update button in the modal
	$('#ao_add_update_form').on('click','.ao_details_update', function(e) {
		e.preventDefault();
		
		var crclm_id = $('#cia_curriculum_id').val();
		var term_id = $('#cia_term_id').val();
		var crs_id = $('#cia_course_id').val();
		var section_id = $('#section_id').val();
		var qpd_id = $('#modal_qpd_id').val();
		var ao_id = $('#modal_ao_id').val();
		var ao_name = $('#modal_ao_name').val();
		var ao_description = $('#modal_ao_description').val();
		var ao_list = $('#modal_ao_method_id').val();
		var mt_list = $('#modal_mt_details_id').val();
		var mt_list_text = $('#modal_mt_details_id option:selected').text();
		var cia_total_weightage = $('#cia_total_weightage').val();
		var weightage = $('#modal_weightage').val();
		var max_marks = $('#modal_max_marks').val();
                var old_ao_method_id = $('#ao_method_id').val();
		var ao_val = describe(ao_description ); 
		//check whether entered weightage and max marks are integers or not
		var numberRegex = /^[+-]?\d+(\.\d+)?([eE][+-]?\d+)?$/;
		if(numberRegex.test(weightage) && numberRegex.test(max_marks)) {
		   //if number, do nothing
		} else {
			//else replace character/symbol by zero
			weightage = 0;
		}
		
		if(mt_list == 1 || mt_list_text == 'Rubrics') {
			//QP
			var post_data = {
				'crclm_id': crclm_id,
				'term_id': term_id,
				'crs_id': crs_id,
				'section_id': section_id,
				'qpd_id': qpd_id,
				'ao_id': ao_id,
				'ao_name': ao_name,
				'ao_description': ao_description,
				'ao_list': ao_list,
				'mt_list': mt_list,
				'cia_total_weightage': cia_total_weightage,
				'weightage': weightage,
				'max_marks': max_marks,
				'old_ao_method_id': old_ao_method_id,
			}
		} else {
			//individual
			var co_list = $('#modal_co_list').val();
			var bl_list = $('#modal_bl_list').val();
		
			var post_data = {
				'crclm_id': crclm_id,
				'term_id': term_id,
				'crs_id': crs_id,
				'section_id': section_id,
				'qpd_id': qpd_id,
				'ao_id': ao_id,
				'ao_name': ao_name,
				'ao_description': ao_description,
				'ao_list': ao_list,
				'mt_list': mt_list,
				'cia_total_weightage': cia_total_weightage,
				'weightage': weightage,
				'max_marks': max_marks,
				'co_list': co_list,
				'bl_list': bl_list,
				'old_ao_method_id': old_ao_method_id,
			}
		}
		
		//check if all fields are filled for QP or individual
		if(crs_id && ao_id && ao_description && ao_list && mt_list && max_marks) {
            if(ao_val == 0) {
				if(mt_list == 1 || mt_list_text == 'Rubrics') {
					//QP
					$.ajax({type: "POST",
						url: base_url + 'assessment_attainment/cia/ao_data_update',
						data: post_data,
						dataType: 'json',
						success: function(msg) { 
							//hide modal on update
							$('#ao_edit_modal').modal('hide');
							
							//if data updated successfully
							var data_options = '{"text":"Your data has been updated successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
							var options = $.parseJSON(data_options);
							noty(options);
		
							var post_crs_data = {
								'crs_id': crs_id,
								'section_id': section_id,
							}
						
							$.ajax({type: "POST",
								url: base_url + 'assessment_attainment/cia/generate_table_data',
								data: post_crs_data,
								success: function(msg){
                                                                    populate_ao_table(msg);
                                                                    location.reload();
                                                                }
							});
						}
					});
				} else if(mt_list == 2 && co_list && bl_list) {
					//individual
					$.ajax({type: "POST",
						url: base_url + 'assessment_attainment/cia/ao_data_update',
						data: post_data,
						dataType: 'json',
						success: function(msg) {
							//hide modal on update
							$('#ao_edit_modal').modal('hide');
							
							//if data updated successfully
							var data_options = '{"text":"Your data has been updated successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
							var options = $.parseJSON(data_options);
							noty(options);

							var post_crs_data = {
								'crs_id': crs_id,
								'section_id': section_id,
							}
						
							$.ajax({type: "POST",
								url: base_url + 'assessment_attainment/cia/generate_table_data',
								data: post_crs_data,
								success: function(msg){
                                                                    populate_ao_table(msg);
                                                                    location.reload();
                                                                }
							});
						}
					});
				} else {
					//display validation message
					var data_options = '{"text":"All fields must be filled before proceeding.","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
					var options = $.parseJSON(data_options);
					noty(options);
				}
			}
		} else {
			//display validation message
			var data_options = '{"text":"All fields must be filled before proceeding.","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
			var options = $.parseJSON(data_options);
			noty(options);
		}
	});
	
	//on page load
	$(document).ready(function() {
		var crclm_id = $('#cia_curriculum_id').val();
		var pgm_id = $('#pgm_id').val();
		var crs_id = $('#cia_course_id').val();
		var section_id = $('#section_id').val();
		
		var post_data = {
			'crclm_id': crclm_id,
			'pgm_id': pgm_id,
			'crs_id': crs_id,
            'section_id' : section_id,
		};
		
		$.ajax({type: "POST",
			url: base_url + 'assessment_attainment/cia/generate_table_data',
			data: post_data,                        
			success: populate_ao_table
		});
	});
	
	//function to generate table to display all the courses
	function populate_ao_table(msg) {            
		$('#ao_data_table_body').html(msg);
	}
	
	//function to allow decimal numbers only
	function isNumber(val) {
		var charCode;
		if (evt.keyCode > 0) {
			charCode = evt.which || evt.keyCode;
		}
		else if (typeof (evt.charCode) != "undefined") {
			charCode = evt.which || evt.keyCode;
		}
		if (charCode == 46)
			return true
		if (charCode > 31 && (charCode < 48 || charCode > 57))
			return false;
		return true;	
	}

	//function to allow numbers only
	function isNumber(event) {
		event = (event) ? event : window.event;
		var charCode = (event.which) ? event.which : event.keyCode;
		
		if (charCode > 31 && (charCode < 48 || charCode > 57)) {
			return false;
		}
		
		return true;
	}
	$(".allownumericwithdecimal").on('keypress blur', function (event) {
            if ((event.which != 8 && event.which != 0 && event.which != 44  && (event.which != 46 || $(this).val().indexOf('.') != -1)) && (event.which < 48 || event.which > 57)) {
					$("#errmsg").html("Digits Only").show().fadeOut("slow");
					$(this).css('border-color', 'red');
					$(this).append('<span>Invalid Value</span>');
					$(this).addClass('num_valid');
					$(this).attr("placeholder", "Only Digits!");					
				    return false;
            } else{		
					$(this).removeClass('num_valid');
					$(this).attr('placeholder', 'Enter Data');
					$(this).css('border-color', '#CCCCCC');
			}
    });
	// multi select function for CO and PO dropdown
	$(function () {
		//multi select CO
		$('.co_list').multiselect({
			includeSelectAllOption: true,
			buttonWidth: '220px',
			nonSelectedText: 'Select CO',
			templates: {
			button: '<button type="button" class="multiselect btn btn-link dropdown-toggle multi_select_decoration" data-toggle="dropdown"></button>' 
			}
		});
		
        //multi select BL
        $('.bl_list').multiselect({
			includeSelectAllOption: true,
			buttonWidth: '220px',
			nonSelectedText: 'Select Level',
			templates: {
			button: '<button type="button" class="multiselect btn btn-link dropdown-toggle multi_select_decoration" data-toggle="dropdown"></button>' 
			}
		});
		
		$('.bl_list').multiselect('rebuild');
		
		$('.co_list').click(function () {
			var selected = $('.co_list option:selected');
			var message = "";
			selected.each(function () {
				message += $(this).text() + " " + $(this).val() + "\n";
			});
        });

		
    });
	
	//message to display animated notification instead of modal
	$('.noty').click(function () {
        var options = $.parseJSON($(this).attr('data-noty-options'));
        noty(options);
    });
	
	//Function to show rubrics data in modal
	$('#ao_add_update_form').on('click','.rubrics_modal', function() {
		var ao_method_id = $(this).data("ao_method_id");
		var ao_method_name = $(this).data("ao_method_name");
		
		var post_data = {
			'ao_method_id': ao_method_id,
			'ao_method_name': ao_method_name
		}
		

		$.ajax({
			type: "POST",
			url: base_url + 'assessment_attainment/cia/define_rubrics',
			data: post_data,
			success: function(msg) {
				$('#rubrics_data').html(msg); 
				$('#rubrics_modal').modal({dynamic:true});
			}
		});
	});
	
	//function to generate .pdf
	$('#rubrics_pdf').on('click', function(e) {
		e.preventDefault();
		
		var cloned = $('#rubrics_data').clone().html();
		$('#pdf').val(cloned);
		$('#form_rubrics').submit();
	});

	
	
	
	$('.qp_types').on('blur' , function(){
		var total_percent =0;
		var curriculum_id = crclm;
		var course = crs;
	
		if($('#cia_total_weightage').val()){var cia_total_weightage = $('#cia_total_weightage').val();}else{ var cia_total_weightage = 0; }
		if($('#mte_total_weightage').val()){var mte_total_weightage = $('#mte_total_weightage').val();}else{ var mte_total_weightage = 0; }
		if($('#tee_total_weightage').val()){var tee_total_weightage = $('#tee_total_weightage').val();}else{ var tee_total_weightage = 0; }
				
		$('.qp_types').each(function () {
			total_percent += (parseFloat(this.value)); 			
		});		
	if(total_percent == "" || total_percent == "." ) {
			msg.innerHTML = "This field is required.";			
		} else if(total_percent.length >= 7 ){
			msg.innerHTML = "Only 6 characters.";
		} else if(total_percent != 100.00) { 
			
			$('#error_type').html('To save weightage , sum of weightage should be equal to 100 %. ');
		} else {
 		 	msg.innerHTML = " ";
			var post_data = {
				'curriculum_id': curriculum_id,
				'course': crs,
				'cia_total_weightage':cia_total_weightage,
				'mte_total_weightage':mte_total_weightage,
				'tee_total_weightage':tee_total_weightage
			}

			$.ajax({
				url: base_url + 'assessment_attainment/cia/save_weightage',
				type: "POST",
				data: post_data,
				success: function(data) { 
					if (!data) {						
						var data_options = '{"text":"Unable to update  .","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
						var options = $.parseJSON(data_options);
						noty(options);
					}else{
						$('#error_type').html('');
						var data_options = '{"text":"Data has been updated successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
						var options = $.parseJSON(data_options);
						noty(options);
					}
				}
			}); 
/* 			$('#test').submit();
			var data_options = '{"text":"Data has been updated successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
			var options = $.parseJSON(data_options);
			noty(options);  */
		}
});
	
	
	//Function to save text contents for the particular curriculum
	$("#cia_total_weightage").bind("keyup", function() {	
		//myAjaxFunction(this.value) 
	});
	
	$("#mte_total_weightage").bind("keyup", function() {
		//myAjaxFunction(this.value) 
	});
	
	

	//Function to save text
	function myAjaxFunction(value) { 	
		var curriculum_id = crclm;
		var course = crs;
		var text = document.forms["test"]["cia_total_weightage"].value;  
		var sum = 100.00;	
		sum -= parseFloat(text); 
		var a = sum; 
		
		$("input#tee_total_weightage").val(sum.toFixed(2));
		document.forms["test"]["tee_total_weightage"].value = a;
		
		if(text == "" || text == "." ) {
			msg.innerHTML = "This field is required.";
			document.forms["test"]["tee_total_weightage"].value = "00.00";
		} else if(!text.match(/^[0-9\.\%\s]+$/) ) {
			test.cia_total_weightage.focus();
			msg.innerHTML = "Enter only numbers.";
			document.forms["test"]["tee_total_weightage"].value = "00.00";
		} else if(text.length >= 7 ){
			msg.innerHTML = "Only 6 characters.";
		} else if(text > 101.00) { 
			document.forms["test"]["tee_total_weightage"].value = "00.00";
		} else {
			msg.innerHTML = " ";
			var post_data = {
				'curriculum_id': curriculum_id,
				'course': crs,
				'text': text
			}

			$.ajax({
				url: base_url + 'assessment_attainment/cia/save_txt',
				type: "POST",
				data: post_data,
				success: function(data) { 
					if (!data) {
						alert("unable to save file!");
					}
				}
			});
		}
	}

	//Function to save the text
	$("#tee_total_weightage").bind("keyup", function() { 
		myAjaxFunction_tee(this.value) 
	});

	function myAjaxFunction_tee(value) {
		var curriculum_id = crclm;
		var course = crs;
		var text = document.forms["test"]["tee_total_weightage"].value;
		
		if(text == "" ) {
			disp.innerHTML = "This field is required.";
		} else if(text.length >= 7) {
			disp.innerHTML = "Only 6 characters.";
		} else if(!text.match(/^[0-9\.\%\s]+$/)) {
			test.tee_total_weightage.focus();
			disp.innerHTML = "Enter only numbers.";
		} else {
			disp.innerHTML = " ";
			var post_data = {
				'curriculum_id': curriculum_id,
				'course': crs,
				'text': text
			};

			$.ajax({
				url: base_url + 'assessment_attainment/cia/save_tee',
				type: "POST",
				data: post_data,
				success: function(data) {
					if (!data) {
						alert("unable to save file!");
					}
				}
			});
		}
	}
    
	$('.noty').click(function () {
        var options = $.parseJSON($(this).attr('data-noty-options'));
        noty(options);
    });

// Code Added By Mritunjay B S
$('#ao_edit_modal').on('change','#modal_ao_method_id',function(){
    var old_ao_method_id = $('#ao_method_id').val();
    var new_ao_method_id = $(this).val();
    var ao_id = $('#modal_ao_id').val();
    var rubrics = $('#modal_mt_details_id option:selected').text();
    
    if(old_ao_method_id != new_ao_method_id && rubrics == 'Rubrics'){
        $('#check_assessment_method_modal').modal('show');
    }
    
});