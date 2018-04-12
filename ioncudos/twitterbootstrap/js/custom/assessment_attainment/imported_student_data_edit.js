var base_url = $('#get_base_url').val();
var controller = base_url + 'assessment_attainment/imported_student_data_edit/';
var fields_data = "";
$(document).ready(function () {
	if ($.cookie('remember_dept') != null) {
		// set the option to selected that corresponds to what the cookie is set to
		$('#department option[value="' + $.cookie('remember_dept') + '"]').prop('selected', true);
		select_pgm();
	}
});
function empty_divs() {
	$('#jsGrid').empty();
}
function select_pgm() {
	empty_divs();
	$.cookie('remember_dept', $('#department option:selected').val(), {
		expires : 90,
		path : '/'
	});
	$('#occasion_div').css({
		"display" : "none"
	});
	var dept_id = $('#department').val();
	var post_data = {
		'dept_id' : dept_id
	}
	$.ajax({
		type : "POST",
		url : base_url + 'assessment_attainment/imported_student_data_edit/select_pgm_list',
		data : post_data,
		success : function (msg) {
			$('#actual_data_div').html('');
			$('#program').html(msg);
			if ($.cookie('remember_pgm') != null) {
				// set the option to selected that corresponds to what the cookie is set to
				$('#program option[value="' + $.cookie('remember_pgm') + '"]').prop('selected', true);
				select_crclm();
			}
		}
	});
}

function select_crclm() {
	empty_divs();
	$.cookie('remember_pgm', $('#program option:selected').val(), {
		expires : 90,
		path : '/'
	});
	$('#occasion_div').css({
		"display" : "none"
	});
	var pgm_id = $('#program').val();
	var post_data = {
		'pgm_id' : pgm_id
	}
	$.ajax({
		type : "POST",
		url : base_url + 'assessment_attainment/imported_student_data_edit/select_crclm_list',
		data : post_data,
		success : function (msg) {
			$('#actual_data_div').html('');
			$('#curriculum').html(msg);
			if ($.cookie('remember_crclm') != null) {
				// set the option to selected that corresponds to what the cookie is set to
				$('#curriculum option[value="' + $.cookie('remember_crclm') + '"]').prop('selected', true);
				//get_selected_value();
				select_term();
			}
		}
	});

}

function select_term() {
	empty_divs();
	$.cookie('remember_crclm', $('#curriculum option:selected').val(), {
		expires : 90,
		path : '/'
	});
	$('#occasion_div').css({
		"display" : "none"
	});
	var crclm_id = $('#curriculum').val();
	var post_data = {
		'crclm_id' : crclm_id
	}
	$.ajax({
		type : "POST",
		url : base_url + 'assessment_attainment/imported_student_data_edit/select_termlist',
		data : post_data,
		success : function (msg) {
			$('#actual_data_div').html('');
			$('#term').html(msg);
			if ($.cookie('remember_term') != null) {
				// set the option to selected that corresponds to what the cookie is set to
				$('#term option[value="' + $.cookie('remember_term') + '"]').prop('selected', true);

				select_course();
			}
		}
	});
}

function select_course() {
	empty_divs();
	$.cookie('remember_term', $('#term option:selected').val(), {
		expires : 90,
		path : '/'
	});
	$('#occasion_div').css({
		"display" : "none"
	});
	var term_id = $('#term').val();
	var post_data = {
		'term_id' : term_id
	}
	$.ajax({
		type : "POST",
		url : base_url + 'assessment_attainment/imported_student_data_edit/select_course',
		data : post_data,
		success : function (msg) {
			$('#actual_data_div').html('');
			$('#course').html(msg);
			if ($.cookie('remember_course') != null) {
				// set the option to selected that corresponds to what the cookie is set to
				$('#course option[value="' + $.cookie('remember_course') + '"]').prop('selected', true);
				select_type();
			}
		}
	});
}

function select_type() {
	empty_divs();
	$.cookie('remember_course', $('#course option:selected').val(), {
		expires : 90,
		path : '/'
	});
	$('#occasion_div').css({
		"display" : "none"
	});
	$('#actual_data_div').empty();
	var course_id = $('#course').val();
	if (course_id) {
		$('#type_data').html('<option value=0>Select Type</option><option value=1>' + entity_see + '</option><option value=2>' + entity_cie + '</option>');
	} else {
		$('#occasion_div').css({
			"display" : "none"
		});
		$('#actual_data_div').html('');
		$('#type_data').html('<option value=0>Select Type</option>');
	}
}

$('#imported_student_edit_form').on('change', '#type_data', function () {
	var type_data_id = $('#type_data').val();
	empty_divs();
	if (type_data_id == 1) {
		$('#occasion_div').css({
			"display" : "none"
		});
		var crclm_id = $('#curriculum').val();
		var term_id = $('#term').val();
		var course_id = $('#course').val();
		var post_data = {
			'crclm_id' : crclm_id,
			'term_id' : term_id,
			'crs_id' : course_id
		}
		$.ajax({
			type : "POST",
			url : base_url + 'assessment_attainment/data_series/getTEEQPId',
			data : post_data,
			success : function (qpd_id) {
				if (Number(qpd_id)) {
					get_fields(qpd_id);
				} else if (!Number(qpd_id)) {
					$('#actual_data_div').empty();
					$('#actual_data_div').html('<font color="red">' + entity_see + ' Question has not been rolled out</font>');
				}
			}
		});

	}

	if (type_data_id == 2) {
		var crclm_id = $('#curriculum').val();
		var term_id = $('#term').val();
		var crs_id = $('#course').val();
		var post_data = {
			'crclm_id' : crclm_id,
			'term_id' : term_id,
			'crs_id' : crs_id
		}
		$.ajax({
			type : "POST",
			url : controller + 'select_occasion',
			data : post_data,
			success : function (occasionList) {
				if (occasionList != 0) {
					$('#occasion_div').css({
						"display" : "inline"
					});
					$('#occasion').html(occasionList);
				} else {
					$('#occasion_div').css({
						"display" : "none"
					});
					$('#actual_data_div').html('<font color="red">' + entity_cie_full + ' (CIA) Occasions are not defined</font>');
				}
			}
		});
	}
});
$('#imported_student_edit_form').on('change', '#occasion', function () {
	var qpd_id = $('#occasion').val();
	empty_divs();
	var post_data = {
		'qpd_id' : qpd_id
	}
	get_fields(qpd_id);
});
function get_fields(qpd_id) {
	var post_data = {
		'qpd_id' : qpd_id,
	}
	$.ajax({
		type : "POST",
		url : controller + "get_table_fields_data/",
		data : post_data,
		success : function (msg) {
			fields_data = msg;
			load_grid(fields_data, post_data);
		}
	});
}
function load_grid(fields_data, post_data) {
	$("#jsGrid").jsGrid({
		width : "100%",
		height : "400px",
		filtering : false,
		inserting : false,
		editing : true,
		dataType : 'json',
		sorting : true,
		paging : true,
		autoload : true,
		deleteButton : false,
		pageSize : 30,
		pageButtonCount : 5,
		insertRowRenderer : null,
		deleteConfirm : "Do you really want to delete client?",
		onDataLoading : function () {},
		controller : {
			loadData : function () {

				return $.ajax({
					type : "POST",
					url : controller + "get_imported_student_data",
					data : post_data,
				});
			},
			updateItem : function (item) {
				return $.ajax({
					type : "POST",
					url : controller + "update_marks/",
					data : item,
				}).done(function () {});
			},
		},
		fields : fields_data,
		onItemUpdating: function(args) {
        if(args.item.total_marks === "") {
            args.cancel = true;
            alert("Total marks can not be empty");
        }
    },
	});
} //end of load grid
