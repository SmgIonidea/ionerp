$(document).ready(function () {
	getDepartmentList();
});

$('#dept_name').live('change', function () {
	var dept_id = $('#dept_name').val();
	$.cookie('remember_dept_id', $('#dept_name option:selected').val(), {
		expires : 90,
		path : '/'
	});
	var post_data = {
		'dept_id' : dept_id,
	}
	$.ajax({
		type : "POST",
		url : base_url + 'survey/import_student_data/loadProgramList',
		data : post_data,
		success : function (pgm_list) {
			$('#program_name').html(pgm_list);
			$('#dept_id').val($('#dept_name').val());
			if ($.cookie('remember_pgm_id') != null) {
				$('#program_name option[value="' + $.cookie('remember_pgm_id') + '"]').prop('selected', true);
				$('#program_name').trigger('change');
			}
		}
	});
});
$('#program_name').live('change', function () {
	var pgm_id = $('#program_name').val();
	$.cookie('remember_pgm_id', $('#program_name option:selected').val(), {
		expires : 90,
		path : '/'
	});
	var post_data = {
		'pgm_id' : pgm_id,
	}
	$.ajax({
		type : "POST",
		url : base_url + 'survey/import_student_data/loadCurriculumList',
		data : post_data,
		success : function (msg) {
			$('#curriculum_name').html(msg);
			$('#pgm_id').val($('#program_name').val());
			if ($.cookie('remember_crclm_id') != null) {
				$('#curriculum_name option[value="' + $.cookie('remember_crclm_id') + '"]').prop('selected', true);
				$('#curriculum_name').trigger('change');
			}
		}
	});
});
$('#curriculum_name').live('change', function () {
	$('#crclm_id').val($('#curriculum_name').val());
	$.cookie('remember_crclm_id', $('#curriculum_name option:selected').val(), {
		expires : 90,
		path : '/'
	});
	var crclm_id = $('#curriculum_name').val();
	var post_data = {
        'crclm_id': crclm_id, 
    }	
    $.ajax({
        type: "POST",
        url: base_url + 'survey/import_student_data/loadSectionList',
        data: post_data,
        success: function (msg) {
            $('#section_name').html(msg);
            $('#pgm_id').val($('#program_name').val());
            if ($.cookie('remember_section_id') != null) {
                $('#section_name option[value="' + $.cookie('remember_section_id') + '"]').prop('selected', true);
                $('#section_name').trigger('change');
            }
        }
    });
});
function getDepartmentList() {
	$.ajax({
		type : "POST",
		url : base_url + 'survey/import_student_data/loadDepartmentList',
		data : '',
		success : function (dept_list) {
			$('#dept_name').html(dept_list);
			if ($.cookie('remember_dept_id') != null) {
				$('#dept_name option[value="' + $.cookie('remember_dept_id') + '"]').prop('selected', true);
				$('#dept_name').trigger('change');
			}
		}
	});
}
$('#stkholder_submit').live('click', function (e) {
alert("");
	e.preventDefault();
	$('#add_stud_stakeholder_form').validate({
		rules : {
			'dept_name' : 'required',
			'program_name' : 'required',
			'curriculum_name' : 'required',
			'student_usn' : 'required',
			'title' : 'required',
			'first_name' : 'required',
			'section_name' : 'required',
			'last_name' : 'required',
			'email' : {
				required : true,
				email : true,
			},
                        'contact': {
                            required: true,
                          // phone_number_valid: true
                        }
		}
	});
	$('#add_stud_stakeholder_form').valid();
	$('#add_stud_stakeholder_form').submit();
});

$("#dp3").datepicker({
	format : "dd-mm-yyyy",
	endDate:'-1d',
	viewMode : "defaultViewDate",
	minViewMode : "defaultViewDate"
}).on('changeDate', function (ev) {
	$(this).blur();
	$(this).datepicker('hide');
});

$('#btn').click(function () {
	$(document).ready(function () {
		$("#dp3").datepicker().focus();
	});
});

