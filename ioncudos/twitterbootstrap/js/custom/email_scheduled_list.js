//email scheduled list
var base_url = $('#get_base_url').val(); //get base url
var esl_controller = "scheduler/email_scheduler_list/";

$(document).ready(function () {
	$('#email_list_tab').empty();
	getDepartmentList();
});

$('#example_tab').dataTable({
	"sPaginationType" : "bootstrap"
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
		url : base_url + esl_controller + 'loadProgramList',
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
		url : base_url + esl_controller + 'loadCurriculumList',
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
	var dept_id = $('#dept_id').val();
	var pgm_id = $('#pgm_id').val();
	var crclm_id = $('#crclm_id').val();
	var post_data = {
		'dept_id' : dept_id,
		'pgm_id' : pgm_id,
		'crclm_id' : crclm_id,
	}
	$.ajax({
		type : "POST",
		url : base_url + esl_controller + 'loadSurveyList',
		data : post_data,
		success : function (msg) {
			$('#survey_title').html(msg);
			if ($.cookie('remember_survey_id') != null) {
				$('#survey_title option[value="' + $.cookie('remember_survey_id') + '"]').prop('selected', true);
				$('#survey_title').trigger('change');
			}
		}
	});
});
$('#survey_title').live('change', function () {
	$('#email_list_tab').empty();
	var survey_id = $('#survey_title').val();
	$.cookie('remember_survey_id', survey_id, {
		expires : 90,
		path : '/'
	});
	var post_data = {
		'survey_id' : survey_id,
	}
	$.ajax({
		type : "POST",
		url : base_url + esl_controller + 'getEmailList',
		data : post_data,
		success : function (msg) {
			$('#email_list_tab').html(msg);
			$('#example_tab').dataTable({
				"sPaginationType" : "bootstrap"
			});
		}
	});
});
function getDepartmentList() {
	$.ajax({
		type : "POST",
		url : base_url + esl_controller + 'loadDepartmentList',
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
