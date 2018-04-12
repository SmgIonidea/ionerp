//Mapping Report of Course Learning Objective to Program Outcome

var base_url = $('#get_base_url').val();

//Mapping Report of Course Learning Objective to Program Outcome View and Static View Page
/*You may use scrollspy along with creating and removing elements form DOM. But if you do so, you have to call the refresh method . 
 The following code shows how you may do that:*/

$('[data-spy="scroll"]').each(function () {
    var $spy = $(this).scrollspy('refresh')
});

//set cookie
if ($.cookie('remember_curriculum') != null) {
    // set the option to selected that corresponds to what the cookie is set to
    $('#crclm option[value="' + $.cookie('remember_curriculum') + '"]').prop('selected', true);
    select_term();
}
//Function to fetch term details for term dropdown
function select_term() {
    $.cookie('remember_curriculum', $('#crclm option:selected').val(), {expires: 90, path: '/'});
    var curriculum_id = document.getElementById('crclm').value;
    $('#loading').show();
    var post_data = {
	'curriculum_id': curriculum_id
    }

    $.ajax({type: "POST",
	url: base_url + 'report/clo_po_map_report/select_term',
	data: post_data,
	success: function (msg) {
	    document.getElementById('term').innerHTML = msg;
	    document.getElementById('table_view').innerHTML = "";
	    if ($.cookie('remember_term') != null) {
		$('#term option[value="' + $.cookie('remember_term') + '"]').prop('selected', true);
		select_term_course();
	    }
	    $('#loading').hide();
	}
    });
}

//Function to fetch course details for course dropdown
function select_term_course() {
    $.cookie('remember_term', $('#term option:selected').val(), {expires: 90, path: '/'});
    var curriculum_term_id = document.getElementById('term').value;
    var curriculum_id = document.getElementById('crclm').value;
    $('#loading').show();
    var post_data = {
	'curriculum_term_id': curriculum_term_id,
	'curriculum_id': curriculum_id,
    }

    $.ajax({type: "POST",
	url: base_url + 'report/clo_po_map_report/term_course_details',
	data: post_data,
	success: function (msg) {
	    document.getElementById('course').innerHTML = msg;
	    document.getElementById('table_view').innerHTML = "";
	    if ($.cookie('remember_course') != null) {
		$('#course option[value="' + $.cookie('remember_course') + '"]').prop('selected', true);
		select_course();
	    }
	    $('#loading').hide();
	}
    });
}

//Function to display grid on select of course
function select_course() {
    $.cookie('remember_course', $('#course option:selected').val(), {expires: 90, path: '/'});
    var curriculum_term_id = document.getElementById('term').value;
    var curriculum_id = document.getElementById('crclm').value;
    var course_id = document.getElementById('course').value;
    $('#loading').show();

    if (!course_id) {
	$("a#export").attr("href", "#");
    } else {
	$("a#export").attr("onclick", "generate_pdf(" + 0 + ");");
	$("a#export_doc").attr("onclick", "generate_pdf(" + 1 + ");");
    }

    var post_data = {
	'curriculum_term_id': curriculum_term_id,
	'curriculum_id': curriculum_id,
	'course_id': course_id,
    }
    if (course_id) {
	$.ajax({type: "POST",
	    url: base_url + 'report/clo_po_map_report/clo_details',
	    data: post_data,
	    success: function (msg) {
		document.getElementById('table_view').innerHTML = msg;
		$('#loading').hide();
	    }
	});

	$.ajax({type: "POST",
	    url: base_url + 'report/clo_po_map_report/justification',
	    data: post_data,
	    success: function (msg) {
		if (msg != 0) {
		    $('#justification_view').attr("class", "bs-docs-example");
		    document.getElementById('justification_view').innerHTML = msg;
		} else {
		    $('#justification_view').attr("class", "1");
		    document.getElementById('justification_view').innerHTML = "";
		}
	    }
	});
    } else {
	document.getElementById('table_view').innerHTML = "";
	$('#loading').hide();
    }

    $.ajax({type: "POST",
	url: base_url + 'report/clo_po_map_report/individual_justification',
	data: post_data,
	success: function (msg) {	//alert(msg);
	    if (msg != 0) {
		$('#individual_justification_view').attr("class", "bs-docs-example");
		document.getElementById('individual_justification_view').innerHTML = msg;
	    } else {
		$('#individual_justification_view').attr("class", "1");
		document.getElementById('individual_justification_view').innerHTML = "";
	    }
	}
    });
}

//Function to generate .pdf and word file

function generate_pdf(type)
{
    if (type == '0') {
	$('#doc_type').val('pdf');
    } else {
	$('#doc_type').val('word');
    }
    var cloned = $('#table_view').clone().html();
    $('#pdf').val(cloned + $('#justification_view').clone().html() + $('#individual_justification_view').clone().html());
    $('#form1').submit();
}

