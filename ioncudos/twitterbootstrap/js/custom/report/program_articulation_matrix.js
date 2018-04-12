/*You may use scrollspy along with creating and removing elements form DOM. But if you do so, you have to call the refresh method . 
 The following code shows how you may do that:*/
document.getElementById("restore_map_data").style.visibility = "hidden";
var base_url = $('#get_base_url').val();
$('[data-spy="scroll"]').each(function () {
    var $spy = $(this).scrollspy('refresh')
});

//set cookie
if ($.cookie('remember_curriculum') != null) {
    // set the option to selected that corresponds to what the cookie is set to
    $('#crclm option[value="' + $.cookie('remember_curriculum') + '"]').prop('selected', true);
    select_term();
}

//second dropdown - term
function select_term()
{
    $.cookie('remember_curriculum', $('#crclm option:selected').val(), {expires: 90, path: '/'});
    var data_val = document.getElementById('crclm').value;
    var select_term_path = base_url + 'report/crs_articulation_report/select_term';
    $('#loading').show();
    var post_data = {
	'crclm_id': data_val
    }
    $.ajax({type: "POST",
	url: select_term_path,
	data: post_data,
	success: function (msg) {
	    document.getElementById('term').innerHTML = msg;
	    if ($.cookie('remember_term') != null) {
		$('#term option[value="' + $.cookie('remember_term') + '"]').prop('selected', true);
		func_grid();
	    }
	    $('#loading').hide();
	}
    });
}
//select only mapped column list.
//$('input[id="status"]').bind('click', function () {
$('#course_type ,#status').bind('click change', function () {
    func_grid();
});

//display grid on select of term
function func_grid()
{
    $.cookie('remember_term', $('#term option:selected').val(), {expires: 90, path: '/'});
    var data_val = document.getElementById('term').value;
    var data_val1 = document.getElementById('crclm').value;
    $('#loading').show();
    /*       if (document.getElementById('course').checked == true)
     {
     var core = 1;
     } else
     {
     var core = 0;
     }   */
    var core = $('#course_type').val();

    if ($('input[id="status"]').is(':checked')) {
	var check = parseInt(1);
	$('#restore_map_data').hide();
	$('#export').show();


    } else {
	var check = parseInt(0);
	$('#restore_map_data').show();
	$('#export').hide();
    }
    if (!data_val)
	$("a#export").attr("href", "#");
    else
	$("a#export").attr("onclick", "generate_pdf();");

    var clo_details_path = base_url + 'report/program_articulation_matrix/clo_details';
    var po_details_path = base_url + 'report/program_articulation_matrix/po_details';
    var post_data = {
	'crclm_term_id': data_val,
	'crclm_id': data_val1,
	'core': core,
	'status': check
    }
    if (data_val) {
	$.ajax({type: "POST",
	    url: clo_details_path,
	    data: post_data,
	    success: function (msg) {
		// if (msg == 1)
		//   document.getElementById('course_articulation_matrix_grid').innerHTML = '<b>No Data to Display </b>';
		//else {
		document.getElementById('course_articulation_matrix_grid').innerHTML = msg;
		$.ajax({type: "POST",
		    url: po_details_path,
		    data: post_data,
		    success: function (msg1) {
			document.getElementById('text1').innerHTML = msg1;
		    }
		});
		//}
	    }
	});
	var termSelect = document.getElementById("crclm");
	var selectedterm = termSelect.options[termSelect.selectedIndex].text
	document.getElementById("curr").value = selectedterm;
	var termSelect = document.getElementById("term");
	var selectedterm = termSelect.options[termSelect.selectedIndex].text

	document.getElementById("term_name").value = selectedterm;
	$('#loading').hide();
	document.getElementById("restore_map_data").style.visibility = "visible";
    } else {
	document.getElementById("restore_map_data").style.visibility = "hidden";
	document.getElementById('course_articulation_matrix_grid').innerHTML = "";
	document.getElementById('text1').innerHTML = "";
	$('#loading').hide();
    }
}

function generate_pdf() {
    $("#po_statement").attr('class', 'table table-bordered table-hover');
    var cloned = $('#course_articulation_matrix_grid').clone().html();
    $("#table_data").attr('class', 'table table-bordered table-hover');
    var cloned1 = $('#po_stmt').clone().html();
    $("#table_data").attr('class', '');
    $('#pdf').val(cloned);
    $('#stmt').val(cloned1);
    $("#po_statement").attr('class', '');
    $('#form1').submit();
}

function fetch_crclm() {
    var crclmSelect = document.getElementById("crclm");
    var selectedcrclm = crclmSelect.options[crclmSelect.selectedIndex].text
    document.getElementById("curr").value = selectedcrclm;
}

function clo_details(temp) {
    var clo_path = base_url + 'report/program_articulation_matrix/fetch_clo';

    var post_data = {
	'crs_id': temp,
    }

    $.ajax({type: "POST",
	url: clo_path,
	data: post_data,
	success: function (msg) {
	    $('#myModal1').modal('show');
	    document.getElementById('comments').innerHTML = msg;
	}
    });
}

//to display po statement to the user
function writetext2(po) {
}


$('.map_level').live('change', function () {

    var id = $(this).attr('value');
    var select_id = $(this).find('option:selected').attr('abbr');
    var map_level_data = id.split('|');

    var core = $('#course_type').val();
    globalid = $(this).attr('id');
    window.id = id;

    var map_level_data = id.split('|');

    if (map_level_data == "") {
	$('#unmap_modal').modal('show');
	globalid = $(this).attr('id');
	window.id = id;
	$('#unmap_ok').click(function () {
	    var post_data = {
		'po': globalid
	    }
	    $.ajax({
		type: "POST",
		url: base_url + 'report/program_articulation_matrix/unmap',
		data: post_data,
	    });
	});

	$('#unmap_cancel').click(function () {

	    var crs_po_id = $('#crs_po_id').val();
	    var crs_po_id_array = crs_po_id.split('|');
	    var po_id = crs_po_id_array[0];
	    var crs_id = crs_po_id_array[1];
	    var crclm_id = crs_po_id_array[2];
	    var post_data = {
		'crs_id': crs_id,
		'po_id': po_id,
		'crclm_id': crclm_id
	    }
	    $.ajax({
		type: "POST",
		url: base_url + 'report/program_articulation_matrix/get_maplevel_data',
		data: post_data,
		dataType: "json",
		success: function (msg) {

		    if (msg != 0) {

			$('select[id^="' + globalid + '"] option[value="' + po_id + '|' + crs_id + '|' + $.trim(msg) + '|' + crclm_id + '"]').attr('selected', 'selected');
		    } else {
			$('#' + globalid).find('option:first').attr('selected', 'selected');
		    }
		}
	    });
	});

	check = $(this).find('option:selected').attr('abbr');
	$('#crs_po_id').val(check);
	map_level_data = check.split('|');
    } else {
	globalid = $(this).attr('id');
	window.id = select_id;
	var map_po_id = map_level_data[0];
	var map_clo_id = map_level_data[1];
	var map_level = map_level_data[2];
	var map_crclm_id = map_level_data[3];
	var curriculum_id = document.getElementById('crclm').value;
	var course_id = map_clo_id;
	var maplevel = map_level;

	var post_data = {
	    'po': map_po_id,
	    'crs_id': course_id,
	    'crclm_id': curriculum_id,
	    'map_level': maplevel
	}

	$.ajax({
	    type: "POST",
	    url: base_url + 'report/program_articulation_matrix/change_maplevel',
	    data: post_data,
	    async: false,
	    success: function () {

	    }
	});
    }
});

$('.map_level').live('change', function () {

    var id = $(this).attr('value');
    var select_id = $(this).find('option:selected').attr('abbr');
    var map_level_data = id.split('|');
    var map_po_id = map_level_data[0];
    var map_clo_id = map_level_data[1];

    $('#restore_map_data').click(function () {
	$('#restore_data').modal('show');
	var data_val = document.getElementById('term').value;
	var data_val1 = document.getElementById('crclm').value;
	var core = $('#course_type').val();
	var po_id = map_po_id;
	var crs_id = map_clo_id;

	var clo_details_path = base_url + 'report/program_articulation_matrix/restore_details';
	var po_details_path = base_url + 'report/program_articulation_matrix/po_details';
	var post_data = {
	    'crs_id': crs_id,
	    'po': po_id,
	    'crclm_term_id': data_val,
	    'crclm_id': data_val1,
	    'core': core
	}

	$('#restore').click(function () {
	    $('#loading').show();
	    $.ajax({
		type: "POST",
		url: clo_details_path,
		data: post_data,
		success: function (msg) {
		    document.getElementById('course_articulation_matrix_grid').innerHTML = msg;
		    $('#loading').hide();
		    $.ajax({type: "POST",
			url: po_details_path,
			data: post_data,
			success: function (msg1) {
			    document.getElementById('text1').innerHTML = msg1;
			    $('#restore_data').modal('hide');
			}
		    });
		}
	    });
	});
    });
});

$('#restore_map_data').click(function () {
    if ($('#crclm').val() == "" || $('#term').val() == "") {
	$('#select_modal').modal('show');
    } else {
	var id = $(this).attr('value');
	var select_id = $(this).find('option:selected').attr('abbr');
	var map_level_data = id.split('|');
	var map_po_id = map_level_data[0];
	var map_clo_id = map_level_data[1];

	$('#restore_data').modal('show');
	var data_val = document.getElementById('term').value;
	var data_val1 = document.getElementById('crclm').value;
	var core = $('#course_type').val();
	var po_id = map_po_id;
	var crs_id = map_clo_id;

	var clo_details_path = base_url + 'report/program_articulation_matrix/restore_details';
	var po_details_path = base_url + 'report/program_articulation_matrix/po_details';
	var post_data = {
	    'crs_id': crs_id,
	    'po': po_id,
	    'crclm_term_id': data_val,
	    'crclm_id': data_val1,
	    'core': core
	}

	$('#restore').click(function () {
	    $('#loading').show();
	    $.ajax({
		type: "POST",
		url: clo_details_path,
		data: post_data,
		success: function (msg) {
		    document.getElementById('course_articulation_matrix_grid').innerHTML = msg;
		    $('#loading').hide();
		    $.ajax({
			type: "POST",
			url: po_details_path,
			data: post_data,
			success: function (msg1) {
			    document.getElementById('text1').innerHTML = msg1;
			    $('#restore_data').modal('hide');
			}
		    });
		}
	    });
	});
    }
});

