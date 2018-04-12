var data_val, po_clk_id;
$(document).ready(function () {
	if ($.cookie('remember_apl') != null) {
		// set the option to selected that corresponds to what the cookie is set to
		$('#program_name option[value="' + $.cookie('remember_apl') + '"]').prop('selected', true);
		$('#program_name').trigger('change');
	}

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
	$.ajax({
		type : "POST",
		url : base_url + 'assessment_attainment/assessment_level/getProgramListByUser',
		data : post_data,
		success : function (msg) {
			$('.generate_pgm_table_view').html(msg);
			$('#apl_id_pgm').val(pgm_id);

			if ($.cookie('remember_apl') != null) {
				$('#program_name option[value="' + $.cookie('remember_apl') + '"]').prop('selected', true);
			}
			select_curriculum_list();
			$('#pgrm_level_assess_table').dataTable({
				"sPaginationType" : "bootstrap",
				"aaSorting" : [[0, "asc"]]
			});
		}
	});
}); //End of onChange for program name

function select_curriculum_list() {
	var pgm_id = $('#program_name').val();
	var post_data = {
		'pgm_id' : pgm_id
	}
	$.ajax({
		type : "POST",
		url : base_url + 'assessment_attainment/assessment_level/select_curriculum',
		data : post_data,
		success : function (msg) {
			$('#curriculum_data').html(msg);
			if ($.cookie('remember_crclm') != null) {
				$('#curriculum_data option[value="' + $.cookie('remember_crclm') + '"]').prop('selected', true);
				$('#curriculum_data').trigger('change');
			}
		}
	});
}

$('.curriculum_name').live('change', function () {
	$.cookie('remember_crclm', $('#curriculum_data option:selected').val(), {
		expires : 90,
		path : '/'
	});
	var curlm_id = $('.curriculum_name').val();
	var post_data = {
		'curlm_id' : curlm_id
	};
	$.ajax({
		type : "POST",
		url : base_url + 'assessment_attainment/assessment_level/getCurriculumListByProgram',
		data : post_data,
		success : function (msg) {
			$('.generate_crlm_table_view').html(msg);
			$('#crclm_id').val(curlm_id);
			if ($.cookie('remember_term') != null) {
				$('#term_data option[value="' + $.cookie('remember_term') + '"]').prop('selected', true);
			}
			loadPoData(); //function to load po list for performance assessment level
			select_term();
			$('#crclm_level_assess_table').dataTable({
				"sPaginationType" : "bootstrap",
				"aaSorting" : [[0, "asc"]]
			});
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
		url : base_url + 'assessment_attainment/assessment_level/select_term',
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
		url : base_url + 'assessment_attainment/assessment_level/getPoListByCurriculum',
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
		url : base_url + 'assessment_attainment/assessment_level/select_course',
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

$('.course_data').live('change', function () {
	$.cookie('remember_course', $('#course_data option:selected').val(), {
		expires : 90,
		path : '/'
	});
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
		url : base_url + 'assessment_attainment/assessment_level/getCourseListByTerm',
		data : post_data,
		success : function (msg) {
			$('#curriculum_id').val(curlm_id);
			$('#term_id').val(term_id);
			$('#course_id').val(course_id);
			$('.generate_course_table_view').html(msg);
			$('#course_level_assess_table').dataTable({
				"sPaginationType" : "bootstrap",
				"aaSorting" : [[0, "asc"]]
			});
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
		url : base_url + 'assessment_attainment/assessment_level/insert_progm_level_assess',
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
		url : base_url + 'assessment_attainment/assessment_level/insert_curriculum_level_assess',
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
		url : base_url + 'assessment_attainment/assessment_level/get_performance_level_assessments_by_po',
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
					add_more_rows += '<td><center><input type="text" name="plp_desc' + i + '" id="plp_desc' + i + '" value="' + data[i].description + '" class="loginRegex input-large required" placeholder="Description" /></center></td>';
					add_more_rows += '<td><center><input type="text" name="plp_start_range' + i + '" id="plp_start_range' + i + '" value="' + data[i].start_range + '" class="onlyFloat input-mini required" placeholder="Description" /></center></td>';
					add_more_rows += '<td><center><input type="text" name="plp_conditional_opr' + i + '" id="plp_conditional_opr' + i + '" value="' + data[i].conditional_opr + '" class="input-mini required" placeholder=""  readonly="readonly" style="text-align:center;" /></center></td>';
					add_more_rows += '<td><center><input type="text" name="plp_end_range' + i + '" id="plp_end_range' + i + '" value="' + data[i].end_range + '" class="onlyFloat input-mini required" placeholder="" /></center></td>';
					add_more_rows += '<td><center><a href="' + base_url + 'assessment_attainment/assessment_level/performance_assess_lvl_delete?id=' + data[i].plp_id + '" onClick="return confirmDelete();" class="confirmation icon-trash"></a></center></td>';
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
	$('#add_perform_assess_form').valid();
	$('#add_perform_assess_form').submit();
});

$('#update_perform_po_assess').live('click', function (e) {
	$('#perform_assess_update_form').validate();
	$('#perform_assess_update_form').valid();
	$('#perform_assess_update_form').submit();
});
$('#add_perform_assess_form').live('submit', function (e) {
	e.preventDefault();
	var form_data = new FormData(this);
	console.log("add po triggerd");
	$.ajax({
		type : "POST",
		url : base_url + 'assessment_attainment/assessment_level/insert_perform_level_assess',
		data : form_data,
		contentType : false,
		cache : false,
		processData : false,
		success : function (msg) {
			console.log(msg);
			update_modal_view($('#po_id').val());
			$("#add_perform_assess_form").trigger('reset');
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
		url : base_url + 'assessment_attainment/assessment_level/get_performance_level_assessments_by_po',
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
					add_more_rows += '<td><center><input type="text" name="plp_desc' + i + '" id="plp_desc' + i + '" value="' + data[i].description + '" class="loginRegex input-large required" placeholder="Description" /></center></td>';
					add_more_rows += '<td><center><input type="text" name="plp_start_range' + i + '" id="plp_start_range' + i + '" value="' + data[i].start_range + '" class="onlyDigit input-mini required" placeholder="Description" /></center></td>';
					add_more_rows += '<td><center><input type="text" name="plp_conditional_opr' + i + '" id="plp_conditional_opr' + i + '" value="' + data[i].conditional_opr + '" class="input-mini required" placeholder="" readonly="readonly" /></center></td>';
					add_more_rows += '<td><center><input type="text" name="plp_end_range' + i + '" id="plp_end_range' + i + '" value="' + data[i].end_range + '" class="onlyDigit input-mini required" placeholder="" /></center></td>';
					add_more_rows += '<td><center><a href="' + base_url + 'assessment_attainment/assessment_level/performance_assess_lvl_delete?id=' + data[i].plp_id + '" onClick="return confirmDelete();" class="confirmation icon-trash"></a></center></td>';
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
}

//View PO List
$('.view_po_assess').live('click', function () {
	$('#po_id').val($(this).attr('id'));
	var post_data = {
		'po_id' : $('#po_id').val(),
	}
	$.ajax({
		type : "POST",
		url : base_url + 'assessment_attainment/assessment_level/get_performance_level_assessments_by_po',
		data : post_data,
		datatype : "html",
		success : function (msg) {
			var data = jQuery.parseJSON(msg);
			var id = $('#po_id').val();
			var po_statement = $('#po_statement_' + id).val();

			$('#selected_po_vw').html("<p>" + po_statement + "</p>");
			if (data.length <= 0) {
				$('#performance_po_list').html("<h4><center>No Performance Level found for this Program Outcome (PO)</center></h4>");
			} else {

				$('#performance_po_list').html("<table id='perform_po_list' class='table table-bordered'><thead><th><center>Sl.No</center></th><th><center>Level Name</center></th><th><center>Level Value</center></th><th><center>Description</center></th></thead><tbody>");
				var i = 0;

				$.each(data, function () {
					var add_more_rows = "";
					add_more_rows += '<tr>';
					add_more_rows += '<td><center>' + (i + 1) + '</center></td>';
					add_more_rows += '<td><center>' + data[i].performance_level_name_alias + '</center></td>';
					add_more_rows += '<td><center>' + data[i].performance_level_value + '</center></td>';
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
//
//form submit for adding course assessment level
//code ends here
$('#acrsl_add_btn').live('click', function (e) {
	$('#acrsl_add_form').validate();
	e.preventDefault();
	if ($('#acrsl_add_form').valid()) {
		$('#acrsl_add_form').submit();
	}
});
$('#acrsl_add_form').live('submit', function (e) {
	e.preventDefault();
	var form_data = new FormData(this);
	$.ajax({
		type : "POST",
		url : base_url + 'assessment_attainment/assessment_level/insert_course_level_assess',
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
		url : base_url + 'assessment_attainment/assessment_level/get_program_level_assessment_by_id',
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
		url : base_url + 'assessment_attainment/assessment_level/update_assessment_level_pgm',
		data : form_data,
		contentType : false,
		cache : false,
		processData : false,
		success : function (msg) {
			console.log(msg);
			$('#program_name').trigger('change');
			$("#update_pgm_level").trigger('reset');
			$('#myModal_apl_edit').modal('hide');
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
		url : base_url + 'assessment_attainment/assessment_level/get_curriculum_level_assessment_by_id',
		data : post_data,
		success : function (msg) {
			console.log(msg);
			var data = jQuery.parseJSON(msg);
			$.each(data, function () {
				$('#acl_id').val(data[0].alp_id);
				$('#acl_level_name').val(data[0].assess_level_name);
				$('#acl_level_value').val(data[0].assess_level_value);
				$('#acl_student_perc').val(data[0].student_percentage);
				$('#acl_target_perc').val(data[0].cia_target_percentage);
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
		url : base_url + 'assessment_attainment/assessment_level/update_curriculum_assess',
		data : form_data,
		contentType : false,
		cache : false,
		processData : false,
		success : function (msg) {
			console.log(msg);
			$('#curriculum_data').trigger('change');
			$("#update_crclm_form").trigger('reset');
			$('#myModal_edit_acl').modal('hide');
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
		url : base_url + 'assessment_attainment/assessment_level/get_course_level_assessment_by_id',
		data : post_data,
		success : function (msg) {
			console.log(msg);
			var data = jQuery.parseJSON(msg);
			$.each(data, function () {
				$('#et_acl_id').val(data[0].alp_id);
				$('#et_acl_level_name').val(data[0].assess_level_name);
				$('#et_acl_level_val').val(data[0].assess_level_value);
				$('#et_acl_direct_perc').val(data[0].cia_direct_percentage);
				$('#et_acl_indirect_perc').val(data[0].indirect_percentage);
				$('#et_acl_conditional_opr').val(data[0].conditional_opr);
				$('#et_acl_target_perc').val(data[0].cia_target_percentage);
			});
		}
	});
});
//update assessment level program
$('#update_crs_btn').live('click', function () {
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
		url : base_url + 'assessment_attainment/assessment_level/update_course_assess',
		data : form_data,
		contentType : false,
		cache : false,
		processData : false,
		success : function (msg) {
			console.log(msg);
			$('#course_data').trigger('change');
			$("#update_course_form").trigger('reset');
			$('#myModaledit_acrsl').modal('hide');
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
		url : base_url + 'assessment_attainment/assessment_level/assessment_level_program_delete',
		data : post_data,
		success : function (msg) {
			console.log(msg);
			$('#program_name').trigger('change');
			$('#myModaldelete').modal('hide');
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
		url : base_url + 'assessment_attainment/assessment_level/assessment_level_curriculum_delete',
		data : post_data,
		success : function (msg) {
			console.log(msg);
			$('#curriculum_data').trigger('change');
			$('#myModaldelete_acl').modal('hide');
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
		url : base_url + 'assessment_attainment/assessment_level/assessment_level_course_delete',
		data : post_data,
		success : function (msg) {
			console.log(msg);
			$('#course_data').trigger('change');
			$('#myModaldelete_acrsl').modal('hide');
			$('body').removeClass('modal-open');
		}
	});
});
