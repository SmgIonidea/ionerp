//-------------CIA QP LIST ADD/EDIT PAGE SCRIPT STARTS HERE---------------------//
var base_url = $('#get_base_url').val();
//global declaration
var count_array = new Array();
var pgm_id = $('#pgm_id').val();
var course_id = $('#cia_course_id').val();
var crs_type_id = $('#crs_type_id').val();
//-------List Page to show curriculum,term,and program dropdowns----------//
//Function to fetch term details
if ($.cookie('remember_dept') != null) {
    // set the option to selected that corresponds to what the cookie is set to
    $('#department option[value="' + $.cookie('remember_dept') + '"]').prop('selected', true);
    select_pgm_list();
}

function select_pgm_list() {
    $.cookie('remember_dept', $('#department option:selected').val(), {expires: 90, path: '/'});
    //var dept_id = document.getElementById('department').value;
    var dept_id = $('#department').val();

    var post_data = {
	'dept_id': dept_id
    }

    $.ajax({type: "POST",
	url: base_url + 'assessment_attainment/import_cia_data/select_pgm_list',
	data: post_data,
	success: function (msg) {
	    //document.getElementById('pgm_id').innerHTML = msg;
	    $('#pgm_id').html(msg);
	    if ($.cookie('remember_pgm') != null) {
		// set the option to selected that corresponds to what the cookie is set to
		$('#pgm_id option[value="' + $.cookie('remember_pgm') + '"]').prop('selected', true);
		select_crclm_list();
	    }
	}
    });
}

function select_crclm_list()
{
    $.cookie('remember_pgm', $('#pgm_id option:selected').val(), {expires: 90, path: '/'});
    //var pgm_id = document.getElementById('pgm_id').value;
    var pgm_id = $('#pgm_id').val();

    var post_data = {
	'pgm_id': pgm_id
    }

    $.ajax({type: "POST",
	url: base_url + 'assessment_attainment/import_cia_data/select_crclm_list',
	data: post_data,
	success: function (msg) {
	    //document.getElementById('crclm_id').innerHTML = msg;
	    $('#crclm_id').html(msg);
	    if ($.cookie('remember_crclm') != null) {
		// set the option to selected that corresponds to what the cookie is set to
		$('#crclm_id option[value="' + $.cookie('remember_crclm') + '"]').prop('selected', true);
		//get_selected_value();
		select_termlist();
	    }
	}
    });
}

function select_termlist() {
    $.cookie('remember_crclm', $('#crclm_id option:selected').val(), {expires: 90, path: '/'});
    //var crclm_id = document.getElementById('crclm_id').value;
    var crclm_id = $('#crclm_id').val();

    var post_data = {
	'crclm_id': crclm_id
    }

    $.ajax({type: "POST",
	url: base_url + 'assessment_attainment/import_cia_data/select_termlist',
	data: post_data,
	success: function (msg) {
	    //document.getElementById('term').innerHTML = msg;
	    $('#term').html(msg);
	    if ($.cookie('remember_term') != null) {
		// set the option to selected that corresponds to what the cookie is set to
		$('#term option[value="' + $.cookie('remember_term') + '"]').prop('selected', true);
		select_courselist();
	    }
	}
    });
}

function select_courselist()
{
    $.cookie('remember_term', $('#term option:selected').val(), {expires: 90, path: '/'});
    var course_list_path = base_url + 'question_paper/manage_cia_qp/select_course';
    var crclm_id = $('#crclm_id').val();
    var term_id = $('#term').val();
    if (term_id) {
	var post_data = {
	    'crclm_id': crclm_id,
	    'term_id': term_id
	}
	$.ajax({type: "POST",
	    url: course_list_path,
	    data: post_data,
	    success: function (msg) {
		$('#course').html(msg);
		if ($.cookie('remember_course') != null) {
		    // set the option to selected that corresponds to what the cookie is set to
		    $('#course option[value="' + $.cookie('remember_course') + '"]').prop('selected', true);
		    GetSelectedValue();
		}
	    }
	});
    } else {
	$('#course').html('<option value="">Select Course</option>');
    }
}
/*---Shows list of courses in CIA List Page--- */
function GetSelectedValue()
{
    $.cookie('remember_course', $('#course option:selected').val(), {expires: 90, path: '/'});
    var cia_list_path = base_url + 'question_paper/manage_cia_qp/show_cia_occasion';
    var crclm_id = $('#crclm_id').val();
    var term = $('#term').val();
    var pgm_id = $('#pgm_id').val();
    var course_id = $('#course').val();

    var post_data = {
	'crclm_id': crclm_id,
	'term': term,
	'pgm_id': pgm_id,
	'course_id': course_id
    }

    $.ajax({type: "POST",
	url: cia_list_path,
	data: post_data,
	dataType: 'json',
	success: populate_table
    });
}


/* Function is used to generate table grid of  course details.
 * @param - 
 * @returns- an array of course details.
 */
function populate_table(msg) {
    var m = 'd';
    $('#example').dataTable().fnDestroy();
    $('#example').dataTable(
	    {"aoColumns": [
		    {"sTitle": "Sl No.", "mData": "sl_no"},
		    {"sTitle": "Section Name", "mData": "section_name"},
		    {"sTitle": "Assessment Occasion Name", "mData": "ao_name"},
		    {"sTitle": "Assessment Type", "mData": "mt_details_name"},
		    {"sTitle": "Manage " + cia_lang + " QP", "mData": "crs_id_edit"},
		    //{"sTitle": "Course Title - (Code)", "mData": "crs_title"},
		    {"sTitle": "Course Mode", "mData": "crs_mode"},
		    {"sTitle": "Course Owner / Instructor", "mData": "username"},
		    {"sTitle": "Import "+ cia_lang +" QP", "mData": "import_qp"},
		    {"sTitle": "View QP", "mData": "view_qp"},
			{"sTitle": "Upload QP", "mData": "upload_qp"},
		    {"sTitle": "Delete", "mData": "delete_qp"}

		], "aaData": msg,
		"sPaginationType": "bootstrap"});
    $('#example').dataTable().fnDestroy();
    $('#example').dataTable({
	"fnDrawCallback": function () {
	    $('.group').parent().css({'background-color': '#C7C5C5'});
	},
	"aoColumnDefs": [
	    {"sType": "natural", "aTargets": [2]}
	],
	"sPaginationType": "bootstrap"

    }).rowGrouping({iGroupingColumnIndex: 1,
	bHideGroupingColumn: true});
	
/* 	.rowGrouping({iGroupingColumnIndex: 1,
	bHideGroupingColumn: true}) */
}

$('#example').on('click', '.cia_qp_delete_warning', function () {
    $('#cia_qp_delete_warning').modal('show');
});

$('#example').on('click', '.marks_uploaded_already', function () {
    $('#marks_uploaded_already_modal').modal('show');
});

$('#example').on('click', '.marks_uploaded_already1', function () {
    $('#marks_uploaded_already_modal1').modal('show');
});


$('#example').on('click', '.delete_qp', function () {

    $('#pgmtype_id').val($(this).attr('data-pgm_id'));
    $('#crclm_id').val($(this).attr('data-crclm_id'));
    $('#term_id').val($(this).attr('data-term_id'));
    $('#crs_id').val($(this).attr('data-crs_id'));
    $('#ao_id').val($(this).attr('data-ao_id'));


    $.ajax({type: "POST",
	url: base_url + 'question_paper/manage_cia_qp/fetch_qp_details',
	data: {'qpd_id': $(this).attr('data-qpd_id'), 'pgmtype_id': $(this).attr('data-pgm_id'), 'crclm_id': $(this).attr('data-crclm_id'), 'term_id': $(this).attr('data-term_id'), 'crs_id': $(this).attr('data-crs_id'), 'ao_id': $(this).attr('data-ao_id')},
	dataType: 'JSON',
	success: function (msg) {
	    if (msg == 'rolledout') {
		$('#cia_qp_delete_warning').modal('show');
	    } else {
		if (msg == null) {
		    $('#qp_not_defined_modal').modal('show');
		} else {
		    $('#model_qp_delete').modal('show');
		}
	    }
	}});
});

$('#delete_qp').on('click', function () {
    var pgmtype_id = $('#pgmtype_id').val();
    var crclm_id = $('#crclm_id').val();
    var term_id = $('#term_id').val();
    var crs_id = $('#crs_id').val();
    var ao_id = $('#ao_id').val();
    $.ajax({type: "POST",
	url: base_url + 'question_paper/manage_cia_qp/delete_qp',
	data: {'pgmtype_id': pgmtype_id, 'crclm_id': crclm_id, 'term_id': term_id, 'crs_id': crs_id, 'ao_id': ao_id},
	dataType: 'JSON',
	success: function (msg) {

	    if (msg) {
		location.reload();
	    }
	}});
});
//---------------------------End of Add CIA js file---------------------------//	

/*
 * Auther Name: Mritunjay B S
 * Date: 30/3/2016
 * Code For: Importing CIA Question paper for individual occasion.
 */

/*
 * Function to load modal to import question paper to the current selected course.
 * 
 */

$('#example').on('click', '.import_cia_qp', function () {
    var crclm_id = $(this).attr('data-crclm_id');
    var term_id = $(this).attr('data-term_id');
    var crs_id = $(this).attr('data-course_id');
    var ao_id = $(this).attr('data-ao_id');
    var ao_name = $(this).attr('data-ao_name');
    var sec_name = $(this).attr('data-section_name');
    var post_data = {'crclm_id': crclm_id, 'term_id': term_id, 'crs_id': crs_id, 'ao_id': ao_id};
    var crclm_name = $('#crclm_id option:selected').text();
    var term_name = $('#term option:selected').text();
    var crs_name = $('#course option:selected').text();

    //   $('.font_color').each(function(){
    //       $(this).remove();
    //   });
    // $("span").remove();
    $('#curriculum_id').val(crclm_id);
    $('#qpterm_id').val(term_id);
    $('#course_id').val(crs_id);
    $('#occasion_ao_id').val(ao_id);
    $('#import_qp_button').prop('disabled', true);
    $.ajax({type: "POST",
	url: base_url + 'question_paper/manage_cia_qp/get_dept_list',
	data: post_data,
	//dataType: 'json',
	success: function (data) {
	    $('#crlcm_name_font').html(crclm_name);
	    $('#term_name_font').empty();
	    $('#term_name_font').html(term_name);
	    $('#crs_name_font').empty();
	    $('#crs_name_font').html(crs_name);
            $('#sec_name_font').empty();
	    $('#sec_name_font').html(sec_name);
	    $('#ao_name_font').empty();
	    $('#ao_name_font').html(ao_name);
	    $('#pop_dept_list').empty();
	    $('#pop_dept_list').html(data);
	    $('#pop_prog_list').empty();
	    $('#pop_prog_list').html($('<option value>Select Program</option>'));
	    $('#pop_crclm_list').empty();
	    $('#pop_crclm_list').html($('<option value> Select Curriculum</option>'));
	    $('#pop_term_list').empty();
	    $('#pop_term_list').append($('<option value> Select Term</option>'));
	    $('#pop_term_list').trigger("chosen:updated");
	    $('#pop_course_list').empty();
	    $('#pop_course_list').append($('<option value> Select Course</option>'));
	    $('#pop_course_list').trigger("chosen:updated");
	    $('#check_box_div').remove();
	    $('#import_occasions_question_paper').modal('show');
	}
    });


});

$('.cancel').on('click', function () {
    $('#cia_qp_list_table').remove();
    var validator = $("#select_form").validate();
    validator.resetForm();

})

/*
 * Function to fetch the program list
 */
$('#import_occasions_question_paper').on('change', '#pop_dept_list', function () {
    $('#import_qp_button').prop('disabled',true);
    var dept_id = $(this).val();
    var post_data = {'dept_id': dept_id};
    $.ajax({type: "POST",
	url: base_url + 'question_paper/manage_cia_qp/get_pgm_list',
	data: post_data,
	//dataType: 'json',
	success: function (data) {
	    $('#pop_prog_list').empty();
	    $('#pop_prog_list').html(data);
            $('#pop_crclm_list').empty();
	    $('#pop_crclm_list').append($('<option>Select Curriculum</option>'));
            $('#pop_term_list').empty();
	    $('#pop_term_list').append($('<option>Select Term</option>'));
            $('#pop_course_list').empty();
	    $('#pop_course_list').append($('<option>Select Course</option>'));
            $('#cia_qp_list_table_wrapper').empty();

	}
    });

});

/*
 * Function to fetch the program curriculum list
 */
$('#import_occasions_question_paper').on('change', '#pop_prog_list', function () {
    $('#import_qp_button').prop('disabled',true);
    var dept_id = $('#pop_dept_list').val();
    var prog_id = $(this).val();
    var post_data = {'dept_id': dept_id, 'pgm_id': prog_id};
    $.ajax({type: "POST",
	url: base_url + 'question_paper/manage_cia_qp/get_crclm_list',
	data: post_data,
	//dataType: 'json',
	success: function (data) {
	    $('#pop_crclm_list').empty();
	    $('#pop_crclm_list').html(data);
            
            $('#pop_term_list').empty();
	    $('#pop_term_list').append($('<option>Select Term</option>'));
            $('#pop_course_list').empty();
	    $('#pop_course_list').append($('<option>Select Course</option>'));
            $('#cia_qp_list_table_wrapper').empty();
            

	}
    });

});

/*
 * Function to fetch the term list
 */
$('#import_occasions_question_paper').on('change', '#pop_crclm_list', function () {
    $('#import_qp_button').prop('disabled',true);
    var dept_id = $('#pop_dept_list').val();
    var prog_id = $('#pop_prog_list').val();
    var crclm_id = $(this).val();
    var post_data = {'dept_id': dept_id, 'pgm_id': prog_id, 'crclm_id': crclm_id};
    $.ajax({type: "POST",
	url: base_url + 'question_paper/manage_cia_qp/get_term_list',
	data: post_data,
	//dataType: 'json',
	success: function (data) {
	    $('#pop_term_list').empty();
	    $('#pop_term_list').html(data);
           
            $('#pop_course_list').empty();
	    $('#pop_course_list').append($('<option>Select Course</option>'));
            $('#cia_qp_list_table_wrapper').empty();

	}
    });

});

/*
 * Function to fetch the course list
 */
$('#import_occasions_question_paper').on('change', '#pop_term_list', function () {
    $('#import_qp_button').prop('disabled',true);
    var to_crs_id = $('#course_id').val();
    var dept_id = $('#pop_dept_list').val();
    var prog_id = $('#pop_prog_list').val();
    var crclm_id = $('#pop_crclm_list').val();
    var term_id = $(this).val();
    var post_data = {'dept_id': dept_id, 'pgm_id': prog_id, 'crclm_id': crclm_id, 'term_id': term_id, 'course_id': to_crs_id};
    $.ajax({type: "POST",
	url: base_url + 'question_paper/manage_cia_qp/get_course_list',
	data: post_data,
	//dataType: 'json',
	success: function (data) {
	    $('#pop_course_list').empty();
	    $('#pop_course_list').html(data);
           
            $('#cia_qp_list_table_wrapper').empty();

	}
    });

});

/*
 * Function to fetch the list of CIA QP
 */
$('#import_occasions_question_paper').on('change', '#pop_course_list', function () {
    $('#import_qp_button').prop('disabled',true);
    var dept_id = $('#pop_dept_list').val();
    var prog_id = $('#pop_prog_list').val();
    var crclm_id = $('#pop_crclm_list').val();
    var term_id = $('#pop_term_list').val();
    var course_id = $(this).val();
    var ao_id = $('#occasion_ao_id').val();
    var post_data = {'dept_id': dept_id, 'pgm_id': prog_id, 'crclm_id': crclm_id, 'term_id': term_id, 'crs_id': course_id, 'ao_id': ao_id};
    $.ajax({type: "POST",
	url: base_url + 'question_paper/manage_cia_qp/get_qp_list',
	data: post_data,
	//dataType: 'json',
	success: function (data) {
	    $('#qp_list_div').empty();
	    $('#qp_list_div').html(data);
	    $('#cia_qp_list_table').dataTable({
		"bPaginate": false,
		"bFilter": false,
		"bSearchable": false,
		"fnDrawCallback": function () {
		    $('.group').parent().css({'background-color': '#C7C5C5'});
		},
		"aoColumnDefs": [
		    {"sType": "natural", "aTargets": [1]}
		],
		"sPaginationType": "bootstrap"

	    }).rowGrouping({
                iGroupingColumnIndex: 0,
		bHideGroupingColumn: true});


	}
    });

});

$('#import_occasions_question_paper').on('click', '.qp_list', function () {

    if ($(this).is(':checked')) {

	$('#import_qp_button').prop('disabled', false);
    } else {
	$('#import_qp_button').prop('disabled', true);
    }

});

$('#example').on('click' , '.topic_not_defined', function(){
		$('.topic_error_msg').html( $(this).attr('data-error_mag') + '<br/><a href ="'+ base_url + $(this).attr('data-link') +'"> Click here to  '+ $(this).attr('data-type_name') +' .<a>');
		$('#topic_not_defined_modal').modal('show');
});  


$('#import_occasions_question_paper').on('click', '#import_qp_button', function () {
    
    var validate = $('#select_form').valid();
    var qpd_id;
    $('.qp_list').each(function () {
	if ($(this).is(':checked')) {
	    qpd_id = $(this).val();
	}
    });
    
    var crclm_id = $('#curriculum_id').val();
    var term_id = $('#qpterm_id').val();
    var crs_id = $('#course_id').val();
    var ao_id = $('#occasion_ao_id').val();
    var post_data = {'qpd_id': qpd_id, 'ao_id': ao_id, 'crs_id': crs_id, 'term_id': term_id, 'crclm_id': crclm_id};
    if (validate == true) {
        $('#loading').show();
        if(qpd_id){
        $('#import_qp_button').prop('disabled', false);
        }else{
            $('#import_qp_button').prop('disabled', true);
        }
	$.ajax({type: "POST",
	    url: base_url + 'question_paper/manage_cia_qp/existance_of_qp',
	    data: post_data,
	    //dataType: 'json',
	    success: function (data) {

		if (data >= 1) {
		    $('#curriculum_id_one').val(crclm_id);
		    $('#qpterm_id_one').val(term_id);
		    $('#course_id_one').val(crs_id);
		    $('#ao_id_one').val(ao_id);
		    $('#qpd_id_one').val(qpd_id);
                    $('#loading').hide();
		    $.fn.modal.Constructor.prototype.enforceFocus = function () {};
		    $('#occasion_existance_body_msg').html('CIA Question Paper already exist under this Course. Do you want to overwrite the existing question paper ?');
		    $('#qp_existance').modal('show');
		} else {
		    $('#loading').show();
		    $.ajax({type: "POST",
			url: base_url + 'question_paper/manage_cia_qp/get_qp_data_import',
			data: post_data,
			//dataType: 'json',
			success: function (msg) {
			    if ($.trim(msg) == "true") {
				var data_options = '{"text":"QP import is successfull.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
				var options = $.parseJSON(data_options);
				noty(options);
				$('#loading').hide();
			    }
			}
		    });
		}


	    }
	});
    }


});

$('#qp_existance').on('click', '#force_import_qp', function () {

    var crclm_id = $('#curriculum_id_one').val();
    var term_id = $('#qpterm_id_one').val();
    var crs_id = $('#course_id_one').val();
    var ao_id = $('#ao_id_one').val();
    var qpd_id = $('#qpd_id_one').val();
    var post_data = {'qpd_id': qpd_id, 'ao_id': ao_id, 'crs_id': crs_id, 'term_id': term_id, 'crclm_id': crclm_id};
    $('#loading_popup').show();
    $.ajax({type: "POST",
	url: base_url + 'question_paper/manage_cia_qp/overwrite_qp',
	data: post_data,
	//dataType: 'json',
	success: function (data) {
	    $('#loading_popup').hide();
	    $('#qp_existance').modal('hide');
	    if ($.trim(data) == "true") {
		var data_options = '{"text":"QP overwrite is successfull.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
		var options = $.parseJSON(data_options);
		noty(options);

	    } else {
		var data_options = '{"text":"<b>This question paper already exist for this occasion.</b> </br> <b>You cannot import the same question paper for this occasion.</b>","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
		var options = $.parseJSON(data_options);
		noty(options);
	    }

	}
    });

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
    success: function (error, label) {
	$(label).closest('.control-group').removeClass('error').addClass('success');
    }

});

$('.noty').click(function () {

    var options = $.parseJSON($(this).attr('data-noty-options'));
    noty(options);

});

$('#example').on('click', '.view_cia_qp', function () {
    $('#view_crclm_id').val($(this).attr('data-crclm_id'));
    $('#view_term_id').val($(this).attr('data-term_id'));
    $('#view_crs_id').val($(this).attr('data-crs_id'));
    $('#view_qpd_type').val($(this).attr('data-qpd_type'));
    $('#view_ao_id').val($(this).attr('data-ao_id'));
    $('#view_qpd_id').val($(this).attr('data-qpd_id'));

    $.ajax({
	type: "POST",
	url: base_url + 'question_paper/manage_cia_qp/fetch_question_paper',
	data: {'crclm_id': $(this).attr('data-crclm_id'), 'term_id': $(this).attr('data-term_id'), 'crs_id': $(this).attr('data-crs_id'), 'qpd_type': $(this).attr('data-qpd_type'), 'ao_id': $(this).attr('data-ao_id'), 'qpd_id': $(this).attr('data-qpd_id')},
	//dataType: 'JSON',
	success: function (msg) {
			document.getElementById('qp_content_display').innerHTML = msg;
			$('#loading').hide();
 			$('#qp_table_data').dataTable().fnDestroy();
			$('#qp_table_data').dataTable({
				"fnDrawCallback" : function () {
					$('.group').parent().css({
						'background-color' : '#C7C5C5'
					});
				},
				"bPaginate" : false,
				"bFilter" : false,
				"bInfo" : false,
				//"aaSorting" : [[1, 'asc']],

			}).rowGrouping({
				iGroupingColumnIndex : 0,
				bHideGroupingColumn : true
			});
			//BloomsPlannedCoverageDistribution

			$('#chart1').empty();
			$('#chart2').empty();
			$('#chart3').empty();
			$('#chart4').empty();
		$('#bloomslevelplannedcoveragedistribution > tbody:first').empty();
			$('#bloomslevelplannedcoveragedistribution_note').empty();
			$('#bloomslevelplannedmarksdistribution > tbody:first').empty();
			$('#bloomslevelplannedmarksdistribution_note').empty();
			$('#coplannedcoveragesdistribution > tbody:first').empty();
			$('#coplannedcoveragesdistribution_note').empty();
			$('#topicplannedcoveragesdistribution > tbody:first').empty();
			$('#topicplannedcoveragesdistribution_note').empty();
			var plot1,
			plot2,
			plot3;
			
		/*	var BloomsLevel = $('#BloomsLevel').val();
			var PlannedPercentageDistribution = $('#PlannedPercentageDistribution').val();
			var ActualPercentageDistribution = $('#ActualPercentageDistribution').val();
			var BloomsLevelDescription = $('#BloomsLevelDescription').val();
			var pln = PlannedPercentageDistribution.split(",");
			var actual = ActualPercentageDistribution.split(",");
			var ticks = BloomsLevel.split(",");
			var level = BloomsLevelDescription.split(",");
			var i = 0;
			var s1_arr = new Array();
			$.each(pln, function () {
				s1_arr.push(Number(pln[i++]));
			});
			var j = 0;
			var s2_arr = new Array();
			$.each(actual, function () {
				s2_arr.push(Number(actual[j++]));
			});

			plot1 = jQuery.jqplot('chart1', [s1_arr, s2_arr], {
					seriesDefaults : {
						renderer : $.jqplot.BarRenderer,
						pointLabels : {
							show : true
						}
					},
					series : [{
							label : 'Framework Level Distribution'
						}, {
							label : 'Planned Distribution'
						}
					],
					axes : {
						xaxis : {
							renderer : $.jqplot.CategoryAxisRenderer,
							tickRenderer : $.jqplot.CanvasAxisTickRenderer,
							ticks : ticks
						},
						yaxis : {
							min : 0,
							max : 100,
							tickOptions : {
								formatString : '%d%%'
							}
						}
					},
					highlighter : {
						show : true,
						tooltipLocation : 'e',
						tooltipAxes : 'x',
						fadeTooltip : true,
						showMarker : false,
						tooltipContentEditor : function (str, seriesIndex, pointIndex, plot) {
							return level[pointIndex];
						}
					},
					legend : {
						show : true,
						location : 'ne',
						placement : 'inside'
					}
				});

			var k = 0;
			$('#bloomslevelplannedcoveragedistribution > thead:first').html('<tr><td><center><b>Blooms Level</b></center></td><td><center><b>Framework level Distribution</b></center></td><td><center><b>Planned Distribution</b></center></td></tr>');
			$.each(ticks, function () {
				$('#bloomslevelplannedcoveragedistribution > tbody:last').append('<tr><td><center>' + ticks[k] + '</center></td><td><center>' + pln[k] + ' %</center></td><td><center>' + actual[k] + ' %</center></td></tr>');
				k++;
			});
			$('#bloomslevelplannedcoveragedistribution_note').append('<br><div class="bs-docs-example"><b>Note:</b><table class="table table-bordered"><tbody><tr><td>The above bar graph depicts the individual Blooms Level planned coverage percentage distribution and Blooms Level actual coverage percentage distribution as in the question paper.<br>Planned distribution is defined by the QP Framework.</td></tr><tr><td><b>Distribution % = ((Count of questions at each Blooms Level) / (Total number of questions) ) * 100 </b></td></tr></tbody></table></div>');
 */
			//BloomsLevelMarksDistribution
			var blooms_level_marks_dist = $('#blooms_level_marks_dist').val();
			var total_marks_marks_dist = $('#total_marks_marks_dist').val();
			var percentage_distribution_marks_dist = $('#percentage_distribution_marks_dist').val();
			var bloom_level_marks_desc = $('#bloom_level_marks_description').val();
			blooms_level = blooms_level_marks_dist.split(",");
			total_marks_dist = total_marks_marks_dist.split(",");
			percentage_dist = percentage_distribution_marks_dist.split(",");
			bloom_lvl_marks_desc = bloom_level_marks_desc.split(",");
			actual_data = new Array();
			var i = 0;
			var j = 0;
			$.each(blooms_level, function () {
				var bloom_lvl = blooms_level[i];
				var percent_distr = percentage_dist[i];
				data = new Array();
				data.push(bloom_lvl, Number(percent_distr));
				i++;
				actual_data[j++] = data;
			});
			plot2 = jQuery.jqplot('chart2', [actual_data], {
					title : {
						text : '', //Blooms Level Planned Marks Distribution',
						show : true
					},
					seriesDefaults : {
						renderer : jQuery.jqplot.PieRenderer,
						rendererOptions : {
							fill : true,
							showDataLabels : true,
							sliceMargin : 4,
							lineWidth : 5,
							dataLabelFormatString : '%.2f%'
						}
					},
					legend : {
						show : true,
						location : 'ne'
					},
					highlighter : {
						show : true,
						tooltipLocation : 's',
						tooltipAxes : 'y',
						useAxesFormatters : false,
						tooltipFormatString : '%s',
						tooltipContentEditor : function (str, seriesIndex, pointIndex, plot) {
							return bloom_lvl_marks_desc[pointIndex];
						}
					}
				});
			$('#bloomslevelplannedmarksdistribution > thead:first').html('<tr><td><center><b>Blooms Level</b></center></td><td><center><b>Marks Distribution</b></center></td><td><center><b>% Distribution</b></center></td></tr>');
			var l = 0;
			$.each(blooms_level, function () {
				$('#bloomslevelplannedmarksdistribution > tbody:last').append('<tr><td><center>' + blooms_level[l] + '</center></td><td><center>' + total_marks_dist[l] + '</center></td><td><center>' + percentage_dist[l] + ' %</center></td></tr>');
				l++;
			});
			$('#bloomslevelplannedmarksdistribution_note').append('<br><div class="bs-docs-example"><b>Note:</b><table class="table table-bordered"><tbody><tr><td>The above pie chart depicts the individual Blooms Level actual marks percentage distribution as in the question paper.</td></tr><tr><td> <b> X = Individual Bloom\'s Level marks  <br/> Y = Sum of all Bloom\'s Level marks <br/> Distribution (%) = (X / Y) * 100 </b> </td></tr></tbody></table></div>');

			//COPlannedCoverageDistribution
			var clo_code = $('#clo_code').val();
			var clo_total_marks_dist = $('#clo_total_marks_dist').val();
			var clo_percentage_dist = $('#clo_percentage_dist').val();
			var clo_statement_dist = $('#clo_statement_dist').val();
			clo_code = clo_code.split(",");
			clo_total_marks_dist = clo_total_marks_dist.split(",");
			clo_percentage_dist = clo_percentage_dist.split(",");
			clo_statement_dist = clo_statement_dist.split(",");
			actual_data = new Array();
			var i = 0;
			var j = 0;
			$.each(clo_code, function () {
				var clo_code_data = clo_code[i];
				var clo_percentage_dist_data = clo_percentage_dist[i];
				data = new Array();
				data.push(clo_code_data, Number(clo_percentage_dist_data));
				i++;
				actual_data[j++] = data;
			});
			plot3 = jQuery.jqplot('chart3', [actual_data], {
					title : {
						text : '', //Blooms Level Planned Marks Distribution',
						show : true
					},
					seriesDefaults : {
						renderer : jQuery.jqplot.PieRenderer,
						rendererOptions : {
							fill : true,
							showDataLabels : true,
							sliceMargin : 4,
							lineWidth : 5,
							dataLabelFormatString : '%.2f%'
						}
					},
					legend : {
						show : true,
						location : 'ne'
					},
					highlighter : {
						show : true,
						tooltipLocation : 's',
						tooltipAxes : 'y',
						useAxesFormatters : false,
						tooltipFormatString : '%s',
						tooltipContentEditor : function (str, seriesIndex, pointIndex, plot) {
							return clo_statement_dist[pointIndex];
						}
					}
				});
			$('#coplannedcoveragesdistribution > thead:first').html('<tr><td><center><b>' + entity_clo + ' Level</b></center></td><td><center><b>Planned Marks</b></center></td><td><center><b>Planned Distribution</b></center></td></tr>');
			var m = 0;
			$.each(clo_code, function () {
				$('#coplannedcoveragesdistribution > tbody:last').append('<tr><td><center>' + clo_code[m] + '</center></td><td><center>' + clo_total_marks_dist[m] + '</center></td><td><center>' + clo_percentage_dist[m] + ' %</center></td></tr>');
				m++;
			});
			$('#coplannedcoveragesdistribution_note').append('<br><div class="bs-docs-example"><b>Note:</b><table class="table table-bordered"><tbody><tr><td>The above pie chart depicts the individual Course Outcome(CO) wise actual marks percentage distribution as in the question paper.</td></tr><tr><td><b> 	X = Individual '+ entity_clo_full_singular +' marks <br/> Y = Sum of all '+ entity_clo_full +' marks <br/> Planned Distribution (%) = (X / Y) * 100 </b> </td></tr></tbody></table></div>');

			//topicCoverageDistribution
			
			$('.myModalQPdisplay').on('shown', function () {
				plot1.replot();
				plot2.replot();
				plot3.replot();
				topic_chart_plot.replot();
			});
						}


    }); 

 $('.myModalQPdisplay_paper_modal_2').modal('show');
	
});

$(document).on('click','.view_rubrics',function(){
    var ao_method_id = $(this).attr('data-ao_method_id');
    var ao_id = $(this).attr('data-ao_id');
    var crclm_id = $(this).attr('data-crclm_id');
    var term_id = $(this).attr('data-term_id');
    var crs_id = $(this).attr('data-crs_id');
    var post_data = {
                        'ao_method_id':ao_method_id,
                        'ao_id':ao_id,
                        'crclm_id':crclm_id,
                        'term_id':term_id,
                        'crs_id':crs_id,
                    };
    $.ajax({type: "POST",
	url: base_url + 'assessment_attainment/cia_rubrics/get_rubrics_table_modal_view',
	data: post_data,
	dataType: 'html',
	success: function (data) {
            if($.trim(data)!='false'){
            $('#rubrics_table_div').empty();
            $('#rubrics_table_div').html(data);
        }else{
            $('#rubrics_table_div').empty();
            $('#rubrics_table_div').html('<center><font color="red"><b>No Data to Display</b></font></center>');
        }
            $('#rubrics_table_view_modal').modal('show');
            
        }
    });
});

$(document).on('click','#export_to_pdf',function(){
   var clone_data = $('#rubrics_table_div').clone().html();
  $('#report_in_pdf').val(clone_data);
  $('#rubrics_report').submit();
});

$(document).on('click','.delete_rubrics',function(){
   var ao_id = $(this).attr('data-ao_id');
  $('#del_ao_id').val(ao_id);
  $('#delete_rubrics_occasion_modal').modal('show');
});

$(document).on('click','#delete_rubrics_occasion',function(){
    var ao_id = $('#del_ao_id').val();
    var post_data = {'ao_id':ao_id,}
    $.ajax({type: "POST",
	url: base_url + 'assessment_attainment/cia_rubrics/delete_rubrics_assement_occasion_details',
	data: post_data,
	dataType: 'html',
	success: function (data) {
           if($.trim(data) == 'true'){
               $('#delete_rubrics_occasion_modal').modal('hide');
               location.reload();
           }else{
               
           }
            
        }
    });
})