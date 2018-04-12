var data_val, po_clk_id, crclm_attain_status;
var user_type = $('#logged_in_user').val();
var plp_id;
$(document).ready(function () {

	crclm_attain_status = false;
	select_curriculum_list();

	$('.get_apl_id').live('click', function (e) {
		data_val = $(this).attr('id');
	});
	$('.get_acl_id').live('click', function (e) {
		data_val = $(this).attr('id');
	});
	$('.get_acrsl_id').live('click', function (e) {
		data_val = $(this).attr('id');
	});
	//validation rules
	$.validator.addMethod("onlyDigit", function (value, element) {
		return this.optional(element) || value.match(/^[0-9]+$/);
	}, "only digits.");
	$.validator.addMethod("loginRegex", function (value, element) {
		return this.optional(element) || /^[a-zA-Z\s]+([-\a-zA-Z\s])*$/i.test(value);
	}, "Field must contain only letters, spaces or dashes.");
	$.validator.addMethod("onlyFloat", function (value, element) {
		return this.optional(element) || value.match(/^[0-9]*\.?[0-9]*$/);
	}, "Only decimals");

	$.validator.addClassRules("num", {
		required : true,
		minlength : 1,
		digits : true,
	});

	$.validator.addClassRules("required", {
		required : true,
	});
});
function empty_divs() {
	$('#no_crclm_data').empty();
}

//functions
$('.program_name').live('change', function () {
	$.cookie('remember_apl', $('.program_name option:selected').val(), {
		expires : 90,
		path : '/'
	});
	var pgm_id = $('#program_name').val();
	var post_data = {
		'pgm_id' : pgm_id
	};
	$('#loading').show();
	$.ajax({
		type : "POST",
		url : base_url + 'tier_ii/attainment_level/getProgramListByUser',
		data : post_data,
		success : function (msg) {
			$('.generate_pgm_table_view').html(msg);
			$('#apl_id_pgm').val(pgm_id);

			if ($.cookie('remember_apl') != null) {
				$('#program_name option[value="' + $.cookie('remember_apl') + '"]').prop('selected', true);
			}

			$('#pgrm_level_assess_table').dataTable({
				"sPaginationType" : "bootstrap",
				"aaSorting" : [[0, "asc"]]
			});
			$('#loading').hide();
		}
	});
}); //End of onChange for program name

function select_curriculum_list() {
	if (user_type == 1) {
		$('#course_tab').addClass('tab-pane fade in active');
		$('#crs_div_data').addClass('active');
	} else {
		$('#crclm_div_data').addClass('active');
		$('#crclm_tab').addClass('tab-pane fade in active');
	}
	$.ajax({
		type : "POST",
		url : base_url + 'tier_ii/attainment_level/select_curriculum',
		data : '',
		success : function (msg) {
			$('#curriculum_data').html(msg);
			if ($.cookie('remember_crclm') != null) {
				$('#curriculum_data option[value="' + $.cookie('remember_crclm') + '"]').prop('selected', true);
				$('#curriculum_data').trigger('change');
			}
		}
	});
}

$('#curriculum_data').live('change', function () {
	$.cookie('remember_crclm', $('#curriculum_data option:selected').val(), {
		expires : 90,
		path : '/'
	});
	empty_divs();
	var curlm_id = $('#curriculum_data').val();
	var post_data = {
		'curlm_id' : curlm_id
	};
	$('#loading').show();

	$.ajax({
		type : "POST",
		url : base_url + 'tier_ii/attainment_level/getCurriculumListByProgram',
		data : post_data,
		success : function (msg) {
			
			if ($.cookie('remember_term') != null) {
				$('#term_data option[value="' + $.cookie('remember_term') + '"]').prop('selected', true);
			}
			if (user_type !=6){
				$('.generate_crlm_table_view').html(msg);
				loadPoData(); //function to load po list for performance assessment level
				$('#crclm_id').val(curlm_id);
			}
			select_term();
			$('#crclm_level_assess_table').dataTable({
				"sPaginationType" : "bootstrap",
				"aaSorting" : [[2, "asc"]]
			});
			$('#indirect_crclm_attainment_table').dataTable({
				"sPaginationType" : "bootstrap",
				"aaSorting" : [[2, "asc"]]
			});
			$('#loading').hide();
		}
	});
});
function select_term() {
	var curlm_id = $('.curriculum_name').val();
	var post_data = {
		'curlm_id' : curlm_id
	};
	$.ajax({
		type : "POST",
		url : base_url + 'tier_ii/attainment_level/select_term',
		data : post_data,
		success : function (msg) {
			$('#term_data').html(msg);
			if ($.cookie('remember_term') != null) {
				$('#term_data option[value="' + $.cookie('remember_term') + '"]').prop('selected', true);
				$('#term_data').trigger('change');
			}
		}
	});
} //End of select_term()
function loadPoData() {
	var curlm_id = $('.curriculum_name').val();
	var post_data = {
		'curlm_id' : curlm_id
	};
	$.ajax({
		type : "POST",
		url : base_url + 'tier_ii/attainment_level/getPoListByCurriculum',
		data : post_data,
		success : function (msg) {
			$('.generate_po_list_table_view').html(msg);
			$('#per_crclm_id').val(curlm_id);
			if ($.cookie('remember_crclm_id') != null) {
				$('#curriculum_data option[value="' + $.cookie('remember_crclm') + '"]').prop('selected', true);
			}
			$('#example').dataTable({
				"sPaginationType" : "bootstrap",
				"aaSorting" : [[2, "asc"]]

			});
			select_term();
		}
	});
} //End of loadPoData()
$('.term_data').live('change', function () {
	$.cookie('remember_term', $('#term_data option:selected').val(), {
		expires : 90,
		path : '/'
	});
	empty_divs();
	$('course_data').trigger('change');
	select_course();
});

function select_course() {
	var curlm_id = $('.curriculum_name').val();
	var term_id = $('.term_data').val();
	var post_data = {
		'curlm_id' : curlm_id,
		'term_id' : term_id
	};
	$.ajax({
		type : "POST",
		url : base_url + 'tier_ii/attainment_level/select_course',
		data : post_data,
		success : function (msg) {
			$('.course_data').html(msg);
			if ($.cookie('remember_course') != null) {
				$('#course_data option[value="' + $.cookie('remember_course') + '"]').prop('selected', true);
				$('#course_data').trigger('change');
			}
		}
	});
} //End of select_course()

	$('#save_course_bloom_level').live('click',function(){
            

		var bl_min = $("input[name='bl_min_data[]']")
							  .map(function(){return $(this).val();}).get();	
	  var mte_min = $("input[name='mte_min_data[]']")
							  .map(function(){return $(this).val();}).get();

		var tee_min = $("input[name='tee_min_data[]']")
							  .map(function(){return $(this).val();}).get();                                                  
		var bl_stud = $("input[name='bl_stud_data[]']")
							  .map(function(){return $(this).val();}).get();
        var bl_justify_data = $("textarea[name='bl_justify_data[]']")
							  .map(function(){return $(this).val();}).get();
	
   	    var bloom_id = $("input[name='bloom_id_data[]']")
							  .map(function(){return $(this).val();}).get();		
		var crs_id_data =  $('#course_data').val();
					
		var crclm_id = $('#curriculum_data').val();
		var term_id = $('#term_data').val();
		 $('#bl_course_table_view').validate(); 
		var flag = $('#bl_course_table_view').valid(); 
		var post_data = {
			'crclm_id' : crclm_id,
			'term_id' : term_id,
			'bl_min': bl_min,
			'mte_min': mte_min,
            'tee_min': tee_min,
			'bl_stud' : bl_stud ,
			'bl_justify' :bl_justify_data,
			'bloom_id' : bloom_id,
			'crs_id_data':crs_id_data
		}  
		if(flag == true){
			$.ajax({
				type : "POST",
				url  : base_url + 'tier_ii/attainment_level/save_bloom_course_wise',
				data : post_data,			
				success : function(msg) {
					if(msg == 1){success_modal(msg);}else{fail_modal(msg);}
				}
			});					  
		}
	});
	/**Calling the modal on success**/
		function success_modal(msg) { 
				var data_options = '{"text":"Your data has been updated successfully","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
									var options = $.parseJSON(data_options);
									noty(options);				
		}

		function fail_modal(msg){//$('#myModal_fail').modal('show');				
				$('#loading').hide();
				var data_options = '{"text":"Your data not updated! ","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
									var options = $.parseJSON(data_options);
									noty(options);
		}

$('.course_data').live('change', function () {
	$.cookie('remember_course', $('#course_data option:selected').val(), {
		expires : 90,
		path : '/'
	});
	empty_divs();
	$('#loading').show();
	var course_id = $('.course_data').val();
	var curlm_id = $('.curriculum_name').val();
	var term_id = $('.term_data').val();
	var post_data = {
		'curlm_id' : curlm_id,
		'term_id' : term_id,
		'course_id' : course_id
	};
	$.ajax({
		type : "POST",
		url : base_url + 'tier_ii/attainment_level/getCourseListByTerm',
		data : post_data,
		success : function (msg) {
			$('.generate_course_table_view').html(msg);
                        if($.trim(curlm_id) != 0  && $.trim(term_id) != 0  && $.trim(course_id) != 0 ){
                            $('#course_level_assess_table').dataTable({
				"sPaginationType" : "bootstrap",
				"aaSorting" : [[2, "asc"]]
			});
                        
			$('#course_level_indirect_assess_table').dataTable({
				"sPaginationType" : "bootstrap",
				"aaSorting" : [[2, "asc"]]
			});
                        }
//			$('#course_level_assess_table').dataTable({
//				"sPaginationType" : "bootstrap",
//				"aaSorting" : [[2, "asc"]]
//			});
//                        
//			$('#course_level_indirect_assess_table').dataTable({
//				"sPaginationType" : "bootstrap",
//				"aaSorting" : [[2, "asc"]]
//			});
			$('#curriculum_id').val(curlm_id);
			$('#term_id').val(term_id);
			$('#course_id').val(course_id);
			$('#loading').hide();
		}
	});
	var post_data = {
		'curlm_id' : curlm_id,
		'term_id' : term_id,
		'crs_id' : course_id
	};
		$.ajax({
		type : "POST",
		url : base_url + 'tier_ii/attainment_level/bl_course_wise',
		data : post_data,
		success : function (msg) {			
			$('.bloom_level_display').html(msg);
			}
		});
});

//form submit for adding program assessment level
//code starts here
$('#apl_add_btn').live('click', function (e) {
	$('#apl_form_add').validate();
	e.preventDefault();
	if ($('#apl_form_add').valid()) {
		$('#apl_form_add').submit();
	}
});
$('#apl_form_add').live('submit', function (e) {
	e.preventDefault();
	var form_data = new FormData(this);
	$.ajax({
		type : "POST",
		url : base_url + 'tier_ii/attainment_level/save_progm_level_attainment',
		data : form_data,
		contentType : false,
		cache : false,
		processData : false,
		success : function (msg) {
			console.log(msg);
			$('#program_name').trigger('change');
			$("#apl_form_add").trigger('reset');
		}
	});
});
//form submit for adding curriculum assessment level
//code ends here
$('#acl_add_btn').live('click', function (e) {
	$('#acl_form_add').validate();
	e.preventDefault();
	if ($('#acl_form_add').valid()) {
		$('#acl_form_add').submit();
	}
});
$('#acl_form_add').live('submit', function (e) {
	e.preventDefault();
	var form_data = new FormData(this);
	$.ajax({
		type : "POST",
		url : base_url + 'tier_ii/attainment_level/save_curriculum_level_attainment',
		data : form_data,
		contentType : false,
		cache : false,
		processData : false,
		success : function (msg) {
			console.log(msg);
			$('#curriculum_data').trigger('change');
			$("#acl_form_add").trigger('reset');
		}
	});
});
//code ends here
$('.add_po_assess').live('click', function (e) {
	$('#po_id').val($(this).attr('id'));
	var post_data = {
		'po_id' : $('#po_id').val(),
	}
	$.ajax({
		type : "POST",
		url : base_url + 'tier_ii/attainment_level/get_performance_level_attainments_by_po',
		data : post_data,
		datatype : "html",
		success : function (msg) {
			var data = jQuery.parseJSON(msg);
			$('#existing_assess_table tbody').remove();
			var id = $('#po_id').val();
			var po_statement = $('#po_statement_' + id).val();
			$('#selected_po').html("<p>" + po_statement + "</p>");
			var i = 0;
			if (data.length <= 0) {
				$('#edit_modal_title').hide();
				$('#edit_modal_title').css('visibility', 'hidden');
				$('#update_perform_po_assess').hide();
				//$('#existing_assess').html("<h4><center>No Performance levels found for this Program Outcome (PO).</center></h4>");
				$('.reset_po_lst').hide();
			} else {

				$('#update_perform_po_assess').show();
				$('.reset_po_lst').show();

				$.each(data, function () {
					var add_more_rows = "";
					add_more_rows += '<tr>';
					add_more_rows += '<td><center>' + (i + 1) + '<center></td>';
					add_more_rows += '<td><center><input type="hidden" name="po_id' + i + '" id="="po_id' + i + '"" value="' + data[i].plp_id + '"/><input type="text" value="' + data[i].performance_level_name_alias + '" name="plp_name' + i + '" id="plp_name' + i + '" class="loginRegex form-control required" placeholder="Level name alias"/></center></td>';
					add_more_rows += '<td><center><input type="text" name="plp_level_val' + i + '" id="plp_level_val' + i + '" value="' + data[i].performance_level_value + '" class="onlyDigit input-mini required" placeholder="Value"/></center></td>';
					add_more_rows += '<td><center><input type="text" name="plp_start_range' + i + '" id="plp_start_range' + i + '" value="' + data[i].start_range + '" class="onlyFloat input-mini required" placeholder="Description" /></center></td>';
					add_more_rows += '<td><center><input type="text" name="plp_conditional_opr' + i + '" id="plp_conditional_opr' + i + '" value="' + data[i].conditional_opr + '" class="input-mini required" placeholder=""  readonly="readonly" style="text-align:center;" /></center></td>';
					add_more_rows += '<td><center><input type="text" name="plp_end_range' + i + '" id="plp_end_range' + i + '" value="' + data[i].end_range + '" class="onlyFloat input-mini required" placeholder="" /></center></td>';
					add_more_rows += '<td><center><input type="text" name="plp_desc' + i + '" id="plp_desc' + i + '" value="' + data[i].description + '" class="loginRegex input-large required" placeholder="Description" /></center></td>';
					add_more_rows += '<td><center><a href="#" data-toggle="modal" po_id="' + data[i].po_id + '" id="' + data[i].plp_id + '" data-target="#myModalDeletePoAssess" class="icon-remove perf_id"></a></center></td>';
					add_more_rows += '</tr>';
					$('#existing_assess_table').append(add_more_rows);
					i++;
				});
				$('#plp_count_val').val(i);
				$('.confirmation').on('click', function (e) {
					return confirm('Are you sure?');
				});
			} //end of if else
		} //end of success function
	}); //end of ajax call
	e.preventDefault();
});
$('#save_performance_progm_level').live('click', function (e) {
	e.preventDefault();
	$('#add_perform_assess_form').validate();
	if ($('#add_perform_assess_form').valid()) {
		$('#add_perform_assess_form').submit();
	}

});

$('#update_perform_po_assess').live('click', function (e) {
	e.preventDefault();
	$('#perform_assess_update_form').validate();
	if ($('#perform_assess_update_form').valid()) {
		$('#perform_assess_update_form').submit();
	}
});
$('#perform_assess_update_form').live('submit', function (e) {
	var form_data = new FormData(this);
	$.ajax({
		type : "POST",
		url : base_url + 'tier_ii/attainment_level/update_perform_level_assess',
		data : form_data,
		contentType : false,
		cache : false,
		processData : false,
		success : function (msg) {
			console.log(msg);
			update_modal_view($('#po_id').val());
			$('#myModalSuccess').modal('show');
			$('#update_status_msg').html("<p>Performance levels Updated Successfully.</p>");
		}
	});
	e.preventDefault();
});
$('#add_perform_assess_form').live('submit', function (e) {
	e.preventDefault();
	var form_data = new FormData(this);
	$.ajax({
		type : "POST",
		url : base_url + 'tier_ii/attainment_level/save_perform_level_attainment',
		data : form_data,
		contentType : false,
		cache : false,
		processData : false,
		success : function (msg) {
			console.log(msg);
			update_modal_view($('#po_id').val());
			$("#reset_btn").trigger('click');
		}
	});
	return false;
});
//function update modal after insertion
function update_modal_view(po_id) {
	var post_data = {
		'po_id' : po_id,
	}
	$.ajax({
		type : "POST",
		url : base_url + 'tier_ii/attainment_level/get_performance_level_attainments_by_po',
		data : post_data,
		datatype : "html",
		success : function (msg) {
			var data = jQuery.parseJSON(msg);
			$('#existing_assess_table tbody').remove();
			var id = $('#po_id').val();
			var po_statement = $('#po_statement_' + id).val();
			$('#selected_po').html("<p>" + po_statement + "</p>");
			var i = 0;
			if (data.length <= 0) {
				$('#edit_modal_title').hide();
				$('#edit_modal_title').css('visibility', 'hidden');
				$('#update_perform_po_assess').hide();
				//$('#existing_assess').html("<h4><center>No Performance levels found for this Program Outcome (PO).</center></h4>");
				$('.reset_po_lst').hide();
			} else {

				$('#update_perform_po_assess').show();
				$('.reset_po_lst').show();

				$.each(data, function () {
					var add_more_rows = "";
					add_more_rows += '<tr>';
					add_more_rows += '<td><center>' + (i + 1) + '<center></td>';
					add_more_rows += '<td><center><input type="hidden" name="po_id' + i + '" id="="po_id' + i + '"" value="' + data[i].plp_id + '"/><input type="text" value="' + data[i].performance_level_name_alias + '" name="plp_name' + i + '" id="plp_name' + i + '" class="loginRegex form-control required" placeholder="Level name alias"/></center></td>';
					add_more_rows += '<td><center><input type="text" name="plp_level_val' + i + '" id="plp_level_val' + i + '" value="' + data[i].performance_level_value + '" class="onlyDigit input-mini required" placeholder="Value"/></center></td>';
					add_more_rows += '<td><center><input type="text" name="plp_start_range' + i + '" id="plp_start_range' + i + '" value="' + data[i].start_range + '" class="onlyFloat input-mini required" placeholder="Description" /></center></td>';
					add_more_rows += '<td><center><input type="text" name="plp_conditional_opr' + i + '" id="plp_conditional_opr' + i + '" value="' + data[i].conditional_opr + '" class="input-mini required" placeholder="" readonly="readonly" style="text-align:center;"/></center></td>';
					add_more_rows += '<td><center><input type="text" name="plp_end_range' + i + '" id="plp_end_range' + i + '" value="' + data[i].end_range + '" class="onlyFloat input-mini required" placeholder="" /></center></td>';
					add_more_rows += '<td><center><input type="text" name="plp_desc' + i + '" id="plp_desc' + i + '" value="' + data[i].description + '" class="loginRegex input-large required" placeholder="Description" /></center></td>';
					add_more_rows += '<td><center><a href="#" data-toggle="modal" po_id="' + data[i].po_id + '" id="' + data[i].plp_id + '" data-target="#myModalDeletePoAssess" class="icon-remove perf_id "></a></center></td>';
					add_more_rows += '</tr>';
					$('#existing_assess_table').append(add_more_rows);
					i++;
				});
				$('#plp_count_val').val(i);
			} //end of if else
		} //end of success function
	}); //end of ajax call
}

$('.perf_id').live('click', function () {
	plp_id = $(this).attr('id');
});
//View PO List
$('.view_po_assess').live('click', function () {
	$('#po_id').val($(this).attr('id'));
	var post_data = {
		'po_id' : $('#po_id').val(),
	}
	$.ajax({
		type : "POST",
		url : base_url + 'tier_ii/attainment_level/get_performance_level_attainments_by_po',
		data : post_data,
		datatype : "html",
		success : function (msg) {
			var data = jQuery.parseJSON(msg);
			var id = $('#po_id').val();
			var po_statement = $('#po_statement_' + id).val();

			$('#selected_po_vw').html("<p>" + po_statement + "</p>");
			if (data.length <= 0) {
				$('#performance_po_list').html("<h4><center>No Performance Levels found for this Program Outcome (PO)</center></h4>");
			} else {

				$('#performance_po_list').html("<table id='perform_po_list' class='table table-bordered'><thead><th><center>Sl.No</center></th><th><center>Level Name</center></th><th><center>Level Value</center></th><th><center>Start Range</center></th><th><center></center></th><th><center>End Range</center></th><th><center>Description</center></th></thead><tbody>");
				var i = 0;

				$.each(data, function () {
					var add_more_rows = "";
					add_more_rows += '<tr>';
					add_more_rows += '<td><center>' + (i + 1) + '</center></td>';
					add_more_rows += '<td><center>' + data[i].performance_level_name_alias + '</center></td>';
					add_more_rows += '<td><center>' + data[i].performance_level_value + '</center></td>';
					add_more_rows += '<td><center>' + data[i].start_range + '</center></td>';
					add_more_rows += '<td><center>' + data[i].conditional_opr + '</center></td>';
					add_more_rows += '<td><center>' + data[i].end_range + '</center></td>';
					add_more_rows += '<td><center>' + data[i].description + '</center></td>';
					add_more_rows += '</tr>';
					$('#perform_po_list').append(add_more_rows);
					i++;
				});
				$('#perform_po_list').append("</tbody></table>");
			} //end of if
		}
	});
	e.preventDefault();
});

$('#delete_ok').live('click', function () {
	var po_id = $('#po_id').val();
	var id = $(this).attr('id');
	var post_data = {
		'id' : plp_id,
	}
	$.ajax({
		type : 'post',
		url : base_url + 'tier_ii/attainment_level/performance_assess_lvl_delete',
		data : post_data,
		success : function (msg) {
			update_modal_view(po_id);
			$('#myModalDeletePoAssess').modal('hide');

		},
		error : function () {},

	});
});

//form submit for adding course assessment level
//code ends here
$('#acrsl_add_btn').live('click', function (e) {	
	var term_id = document.getElementById('term_data').value;
	
	if(crclm_attain_status == false){
		$('#myModal_crclm_attain_status').modal('show');
	} else if(term_id == 0){
		$('#select_dropdowns').modal('show');
	}else{ 
		$('#acrsl_add_form').validate();
		e.preventDefault();
		if ($('#acrsl_add_form').valid()) {
			$('#acrsl_add_form').submit();
		}
	}
});
$('#acrsl_add_form').live('submit', function (e) {
	e.preventDefault();
	var form_data = new FormData(this);
	$.ajax({
		type : "POST",
		url : base_url + 'tier_ii/attainment_level/save_course_level_attainment',
		data : form_data,
		contentType : false,
		cache : false,
		processData : false,
		success : function (msg) {
			console.log(msg);
			$('#course_data').trigger('change');
			$("#acrsl_add_form").trigger('reset');
		}
	});
});
//get program level by id and display in modal
$('.edti_pgm_lvl').live('click', function () {
	var apl_id = $(this).attr('id');
	var post_data = {
		'apl_id' : apl_id,
	}
	$.ajax({
		type : "POST",
		url : base_url + 'tier_ii/attainment_level/get_program_level_assessment_by_id',
		data : post_data,
		success : function (msg) {
			console.log(msg);
			var data = jQuery.parseJSON(msg);
			$.each(data, function () {
				$('#et_apl_id').val(data[0].alp_id);
				$('#et_apl_level_name').val(data[0].assess_level_name);
				$('#et_apl_level_value').val(data[0].assess_level_value);
				$('#et_apl_student_perc').val(data[0].student_percentage);
				$('#et_apl_target_perc').val(data[0].cia_target_percentage);
			});
		}
	});
});
//update assessment level program
$('#update_pgm_al_btn').live('click', function () {
	$('#update_pgm_level').validate();
	if ($('#update_pgm_level').valid()) {
		$('#update_pgm_level').submit();
	}
});
$('#update_pgm_level').live('submit', function (e) {
	e.preventDefault();
	var form_data = new FormData(this);
	$.ajax({
		type : "POST",
		url : base_url + 'tier_ii/attainment_level/update_program_level_attainment',
		data : form_data,
		contentType : false,
		cache : false,
		processData : false,
		success : function (msg) {
			console.log(msg);
			$('#program_name').trigger('change');
			$("#update_pgm_level").trigger('reset');
			$('#myModal_apl_edit').modal('hide');
			$('body').css('overflow', 'visible');
		}
	});
});

//get Curriculum level by id and display in modal
$('.edit_crclm_level').live('click', function () {
	var apl_id = $(this).attr('id');
	var post_data = {
		'acl_id' : apl_id,
	}
	$.ajax({
		type : "POST",
		url : base_url + 'tier_ii/attainment_level/get_curriculum_level_assessment_by_id',
		data : post_data,
		success : function (msg) {
			var data = jQuery.parseJSON(msg);
			$.each(data, function () {
				$('#acl_id').val(data[0].al_crclm_id);
				$('#acl_level_name').val(data[0].assess_level_name);
				$('#acl_level_value').val(data[0].assess_level_value);
				$('#acl_direct_perc').val(data[0].cia_direct_percentage);
				$('#acl_conditional_opr').val(data[0].conditional_opr);
				$('#acl_target_perc').val(data[0].cia_target_percentage);
				$('#acl_justify').val(data[0].justify);
			});
		}
	});
});
//update assessment level program
$('#update_crclm_btn').live('click', function () {
	$('#update_crclm_form').validate();
	if ($('#update_crclm_form').valid()) {
		$('#update_crclm_form').submit();
	}
});
$('#update_crclm_form').live('submit', function (e) {
	e.preventDefault();
	var form_data = new FormData(this);
	$.ajax({
		type : "POST",
		url : base_url + 'tier_ii/attainment_level/update_curriculum_level_attainment',
		data : form_data,
		contentType : false,
		cache : false,
		processData : false,
		success : function (msg) {
			console.log(msg);
			$('#curriculum_data').trigger('change');
			$("#update_crclm_form").trigger('reset');
			$('#myModal_edit_acl').modal('hide');
			$('body').css('overflow', 'visible');
		}
	});
});
$('.edit_indirect_crclm_level').live('click', function () {
	var apl_id = $(this).attr('id');
	var post_data = {
		'acl_id' : apl_id,
	}

	$.ajax({
		type : "POST",
		url : base_url + 'tier_ii/attainment_level/get_curriculum_level_assessment_by_id',
		data : post_data,
		success : function (msg) {
			var data = jQuery.parseJSON(msg);
			$.each(data, function () {
				$('#indirect_crclm_id').val(data[0].al_crclm_id);
				$('#crclm_indirect_perc').val(data[0].indirect_percentage);
			});
		}
	});
});
$('#update_indirect_crclm').live('click', function () {
	$('#update_indirect_crclm_form').validate();
	if ($('#update_indirect_crclm_form').valid()) {
		$('#update_indirect_crclm_form').submit();
	}
});
$('#update_indirect_crclm_form').live('submit', function (e) {
	e.preventDefault();
	var form_data = new FormData(this);
	$.ajax({
		type : "POST",
		url : base_url + 'tier_ii/attainment_level/update_curriculum_level_attainment',
		data : form_data,
		contentType : false,
		cache : false,
		processData : false,
		success : function (msg) {
			$('#curriculum_data').trigger('change');
			$('#myModal_edit_indirect_crclm').modal('hide');
			$('body').css('overflow', 'visible');
		}
	});
});

//get Course level by id and display in modal
$('.edit_crs_alp').live('click', function () {
	var acrsl_id = $(this).attr('id');
	var post_data = {
		'acrsl_id' : acrsl_id,
	}
	$.ajax({
		type : "POST",
		url : base_url + 'tier_ii/attainment_level/get_course_level_assessment_by_id',
		data : post_data,
		success : function (msg) {
			var data = jQuery.parseJSON(msg);
			$.each(data, function () {
				$('#et_acl_id').val(data[0].alp_id);
				$('#et_acl_level_name').val(data[0].assess_level_name);
				$('#et_acl_level_val').val(data[0].assess_level_value);
				$('#et_acl_direct_perc').val(data[0].cia_direct_percentage);
				$('#et_acl_mte_direct_perc').val(data[0].mte_direct_percentage);
				$('#et_acl_see_direct_perc').val(data[0].tee_direct_percentage);
				$('#et_acl_indirect_perc').val(data[0].indirect_percentage);
				$('#et_acl_conditional_opr').val(data[0].conditional_opr);
				$('#et_acl_target_perc').val(data[0].cia_target_percentage);
				$('#et_acl_mte_target_perc').val(data[0].mte_target_percentage);
				$('#et_tee_target_perc').val(data[0].tee_target_percentage);
				$('#et_acl_justify').val(data[0].justify);
			});
		}
	});
});
//edit indirect course attainment
$('.edit_indirect_crs_al').live('click', function () {
	var acrsl_id = $(this).attr('id');
	var post_data = {
		'acrsl_id' : acrsl_id,
	}
	$.ajax({
		type : "POST",
		url : base_url + 'tier_ii/attainment_level/get_course_level_assessment_by_id',
		data : post_data,
		success : function (msg) {
			var data = jQuery.parseJSON(msg);
			$.each(data, function () {
				$('#indirect_crs_id').val(data[0].alp_id);
				$('#crs_indirect_perc').val(data[0].indirect_percentage);
			});
		}
	});
});
$('#update_indirect_crs').live('click', function () {
	$('#update_indirect_crs_form').validate();
	if ($('#update_indirect_crs_form').valid()) {
		$('#update_indirect_crs_form').submit();
	}
});
$('#update_indirect_crs_form').live('submit', function (e) {
	e.preventDefault();
	var form_data = new FormData(this);
	$.ajax({
		type : "POST",
		url : base_url + 'tier_ii/attainment_level/update_course_assess',
		data : form_data,
		contentType : false,
		cache : false,
		processData : false,
		success : function (msg) {
			console.log(msg);
			$('#course_data').trigger('change');
			$('body').css('overflow', 'visible');
		}
	});
});
//update assessment level program
$('#update_crs_btn').live('click', function () {
	$('#et_acl_crs_id').val($('#course_data').val());
	$('#update_course_form').validate();
	if ($('#update_course_form').valid()) {
		$('#update_course_form').submit();
	}
});
$('#update_course_form').live('submit', function (e) {
	e.preventDefault();
	var form_data = new FormData(this);
	$.ajax({
		type : "POST",
		url : base_url + 'tier_ii/attainment_level/update_course_assess',
		data : form_data,
		contentType : false,
		cache : false,
		processData : false,
		success : function (msg) {
			console.log(msg);
			$('#course_data').trigger('change');
			$("#update_course_form").trigger('reset');
			$('#myModaledit_acrsl').modal('hide');
			$('body').css('overflow', 'visible');
		}
	});
});
//function to delete program level assessment
$('#delete_assess_prog_level').live('click', function () {
	var post_data = {
		'alp_id' : data_val,
	}
	$.ajax({
		type : "POST",
		url : base_url + 'tier_ii/attainment_level/delete_program_level_attainment',
		data : post_data,
		success : function (msg) {
			console.log(msg);
			$('#program_name').trigger('change');
			$('#myModaldelete').modal('hide');
			$('body').css('overflow', 'visible');
		}
	});
});
//function to delete curriculum level assessment
$('#delete_assess_crclm_level').live('click', function () {
	var post_data = {
		'alp_id' : data_val,
	}
	$.ajax({
		type : "POST",
		url : base_url + 'tier_ii/attainment_level/delete_crclm_level_attainment',
		data : post_data,
		success : function (msg) {
			console.log(msg);
			$('#curriculum_data').trigger('change');
			$('#myModaldelete_acl').modal('hide');
			$('body').css('overflow', 'visible');
		}
	});
});
//function to delete Course level assessment
$('#delete_assess_crs_level').live('click', function () {
	var post_data = {
		'alp_id' : data_val,
	}
	$.ajax({
		type : "POST",
		url : base_url + 'tier_ii/attainment_level/assessment_level_course_delete',
		data : post_data,
		success : function (msg) {
			console.log(msg);
			$('#course_data').trigger('change');
			$('#myModaldelete_acrsl').modal('hide');
			$('body').css('overflow', 'visible');
		}
	});
});

function is_crclm_attainment_exists() {
	var crclm_id = $('#curriculum_data').val();
	var term_id = $('#term_data').val();
	var crs_id = $('#course_data').val();
	var post_data = {
		'crclm_id' : crclm_id,
		'term_id' : term_id,
		'crs_id' : crs_id,
	}
	$.ajax({
		type : "POST",
		url : base_url + 'tier_ii/attainment_level/is_check_crclm_attainment_level_exists',
		data : post_data,
		success : function (msg) {
			if (msg == "nodata") {
				$('#no_crclm_data').html("<h4 class='err_msg'>Chairman / Progrsm Owner should define Common Course Target Levels, then only Course Owner's can able to define individual Course Target Levels</h4><br/>");
				crclm_attain_status = false;
			} else {
				crclm_attain_status = true;
			}
		}
	});
}


// Send for Approve Function.
// Author: Mritunjay B S
// Date: 17/11/2016


$(document).on('click','#send_for_approve',function(){
     var crclm_id = $('#curriculum_data').val();
	var term_id = $('#term_data').val();
	var crs_id = $('#course_data').val();
	var post_data = {
		'crclm_id' : crclm_id,
		'term_id' : term_id,
		'crs_id' : crs_id,
	}
	$.ajax({
		type : "POST",
		url : base_url + 'tier_ii/attainment_level/skip_review_confirm',
		data : post_data,
		success : function (msg) {
                        if($.trim(msg) !=0 ){
                            $('#send_skip_approval_ok').show();
                        }else{
                             $('#send_skip_approval_ok').hide();
                        }
                        $('#send_approval_confirmation_modal').modal('show');
		}
	});
    
});
$(document).on('click','#send_approval_ok',function(){
        var crclm_id = $('#curriculum_data').val();
	var term_id = $('#term_data').val();
	var crs_id = $('#course_data').val();
	var post_data = {
		'crclm_id' : crclm_id,
		'term_id' : term_id,
		'crs_id' : crs_id,
	}
	$.ajax({
		type : "POST",
		url : base_url + 'tier_ii/attainment_level/send_for_approve',
		data : post_data,
		success : function (msg) {
                        
			$('.generate_course_table_view').empty();
			$('.generate_course_table_view').html(msg);
                        //location.reload();
                         window.location = base_url + 'tier_ii/attainment_level';
                        
		}
	});
});

$(document).on('click','#accept_target',function(){
   $('#accept_confirmation_modal').modal('show'); 
});

$(document).on('click','#accept_target_levels',function(){
        var crclm_id = $('#crclm_id').val();
	var term_id = $('#term_id').val();
	var crs_id = $('#crs_id').val();
	var target_comment = $('#course_target_justify').val();
	var post_data = {
		'crclm_id' : crclm_id,
		'term_id' : term_id,
		'crs_id' : crs_id,
                'justification':target_comment,
	}
        $.ajax({
		type : "POST",
		url : base_url + 'tier_ii/attainment_level/target_accept_function',
		data : post_data,
                dataType: 'html',
		success : function (msg) {
                    if(msg=='success'){
                        window.location = base_url + 'dashboard/dashboard';
                    }else{
                        
                    }
			
		}
	});
});


$(document).on('click','#send_skip_approval_ok',function(){
     var crclm_id = $('#curriculum_data').val();
	var term_id = $('#term_data').val();
	var crs_id = $('#course_data').val();
	var post_data = {
		'crclm_id' : crclm_id,
		'term_id' : term_id,
		'crs_id' : crs_id,
	}
	$.ajax({
		type : "POST",
		url : base_url + 'tier_ii/attainment_level/send_for_skip_approve',
		data : post_data,
                dataType:'html',
		success : function (msg) {
                        
			if($.trim(msg) == 'success' ){
                            window.location = base_url + 'tier_ii/attainment_level';
                        }
                        //location.reload();
                         
                        
		}
	});
});

$(document).on('click','#rework_target',function(){
   $('#rework_confirmation_modal').modal('show'); 
});


$(document).on('click','#rework_target_levels',function(){
        var crclm_id = $('#crclm_id').val();
	var term_id = $('#term_id').val();
	var crs_id = $('#crs_id').val();
	var target_comment = $('#course_target_justify').val();
	var post_data = {
		'crclm_id' : crclm_id,
		'term_id' : term_id,
		'crs_id' : crs_id,
                'justification':target_comment,
	}
        $.ajax({
		type : "POST",
		url : base_url + 'tier_ii/attainment_level/target_rework_function',
		data : post_data,
                dataType: 'html',
		success : function (msg) {
                    if(msg=='success'){
                        window.location = base_url + 'dashboard/dashboard';
                    }else{
                        
                    }
			
		}
	});
});

$(document).on('click','#open_for_rework',function(){
   $('#open_for_rework_modal').modal('show'); 
});

$(document).on('click','#confirm_ok_open_for_rework',function(){
    var crclm_id = $('#curriculum_data').val();
    var term_id = $('#term_data').val();
    var crs_id = $('#course_data').val();
    var post_data = {
        'crclm_id':crclm_id,
        'term_id':term_id,
        'crs_id':crs_id,
    };
    
    $.ajax({
		type : "POST",
		url : base_url + 'tier_ii/attainment_level/open_for_rework_function',
		data : post_data,
         dataType: 'html',
		success : function (msg) {
                    if(msg=='success'){
                        location.reload();
                    }else{
                        
                    }
			
		}
	});
    
    
});

