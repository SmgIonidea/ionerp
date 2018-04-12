
//Internal & Final Exam Report

var base_url = $('#get_base_url').val();

if ($.cookie('remember_term') != null) {
    // set the option to selected that corresponds to what the cookie is set to
    $('#crclm option[value="' + $.cookie('remember_term') + '"]').prop('selected', true);
    fetch_term();
}

function empty_divs() {
    $('.exam_table_grid').empty();
}

//Function to fetch term details for term dropdown
function fetch_term() {
$('a#export').attr('disabled', true);$('a#export_doc').attr('disabled', true);
    empty_divs();
    $.cookie('remember_term', $('#crclm option:selected').val(), {expires: 90, path: '/'});
    var curriculum_id = $('#crclm').val();

    var post_data = {
        'curriculum_id': curriculum_id
    }

    $.ajax({type: "POST",
        url: base_url + 'report/internal_final_exam/fetch_term',
        data: post_data,
        success: function (msg) {
            document.getElementById('term').innerHTML = msg;

            if ($.cookie('remember_selected_value') != null) {
                // set the option to selected that corresponds to what the cookie is set to
                $('#term option[value="' + $.cookie('remember_selected_value') + '"]').prop('selected', true);
                fetch_course();
            }
        }
    });
}

//Function to fetch course details
function fetch_course() {
    empty_divs();
	$('a#export').attr('disabled', true);$('a#export_doc').attr('disabled', true);
    $.cookie('remember_selected_value', $('#term option:selected').val(), {expires: 90, path: '/'});
    var curriculum_id = $('#crclm').val();
    var term_id = $('#term').val();

    if (term_id) {
        var post_data = {
            'curriculum_id': curriculum_id,
            'term_id': term_id
        }

        $.ajax({type: "POST",
            url: base_url + 'report/internal_final_exam/fetch_course',
            data: post_data,
            success: function (msg) {
                //document.getElementById('course').innerHTML = msg;
                $('#course').html(msg);
                if ($.cookie('remember_course') != null) {
                    // set the option to selected that corresponds to what the cookie is set to
                    $('#course option[value="' + $.cookie('remember_course') + '"]').prop('selected', true);
                    fetch_type();
                }
            }
        });
    } else {
        $('#course').html('<option value="">Select Course</option>');
    }
}

//Function to fetch assessment type details
function fetch_type() {
    empty_divs();
    $('#cia_error_msg').html("");
    $.cookie('remember_course', $('#course option:selected').val(), {expires: 90, path: '/'});
    $('#occasion_div').css({"display": "none"});
	$('a#export').attr('disabled', true);$('a#export_doc').attr('disabled', true);
    var crclm_id = $('#crclm').val();
    var term_id = $('#term').val();
    var course_id = $('#course').val();
	var post_data = {
            'crclm_id': crclm_id,
            'term_id': term_id,
            'crs_id': course_id,
        }
    if (course_id != '') {
	    $.ajax({type: "POST",
            url: base_url + 'report/internal_final_exam/fetch_type_data',
            data: post_data,
			dataType: 'json',
            success: function (msg) {			
              $('#ao_type_id').html(msg);
            }
        });
		
      //  $('#ao_type_id').html('<option value=0>Select Type</option><option value=2>' + entity_cie + '</option><option value = 6 >'+ entity_mte+'</option><option value=3>Model ' + entity_see + '</option><option value=1>' + entity_see + '</option>');
    } else {
        $('#occasion_div').css({"display": "none"});
    }
}

function type_data_enable_dissable(ao_type_id , type_data_id){
    if (!ao_type_id){
        $("a#export").attr("href", "#");
		$('a#export').attr('disabled', true);$('a#export_doc').attr('disabled', true);
   } else
    {
		var occasion =   $('#occasion').val(); 
		if(type_data_id == 5 || type_data_id == 4){
			$("a#export").attr("onclick", "generate_pdf(" + 0 + ");");
			$("a#export_doc").attr("onclick", "generate_pdf(" + 1 + ");");
			$('a#export').attr('disabled', false);$('a#export_doc').attr('disabled', false);
		}else if((type_data_id == 3 || type_data_id == 6) && occasion != '' ){
			$("a#export").attr("onclick", "generate_pdf(" + 0 + ");");
			$("a#export_doc").attr("onclick", "generate_pdf(" + 1 + ");");
			$('a#export').attr('disabled', false);$('a#export_doc').attr('disabled', false);
		}else{
		  $("a#export").attr("href", "#");
			$('a#export').attr('disabled', true);$('a#export_doc').attr('disabled', true);
		}
    }
}

//function to display occasion dropdown
$('#ia_tee_form').on('change', '#ao_type_id', function () {
    $('#cia_error_msg').html("");
    empty_divs();
    var type_data_id = $('#ao_type_id').val();
    $('#occasion_div').css({"display": "none"});

    var crclm_id = $('#crclm').val();
    var term_id = $('#term').val();
    var crs_id = $('#course').val();
    var ao_type_id = $('#ao_type_id').val();

	type_data_enable_dissable(ao_type_id , type_data_id);

    if (type_data_id == 5 || type_data_id == 4) {
        //TEE
        $('#cia_error_msg').empty();
        var occasion_id = 0;
		
        var post_data = {
            'crclm_id': crclm_id,
            'term_id': term_id,
            'crs_id': crs_id,
            'occasion_id': occasion_id,
            'ao_type_id': ao_type_id
        }

        //if TEE is selected directly fetch rolled out question paper
        $.ajax({type: "POST",
            url: base_url + 'report/internal_final_exam/fetch_internal_final_exam_details',
            data: post_data,
            success: function (msg) {
                //to display data in the table grid
                if (msg == 1)
                    $('#cia_error_msg').html("<font color='red'>Question Paper is not defined.");
                else
                    $('#cia_error_msg').html(msg);
            }
        });
    } else if (type_data_id == 3) {
        //CIA
        var post_data = {
            'crclm_id': crclm_id,
            'term_id': term_id,
            'crs_id': crs_id
        }

        //if CIA is selected list occasions
        $.ajax({type: "POST",
            url: base_url + 'report/internal_final_exam/select_occasion',
            data: post_data,
            success: function (occasionList) {
                if (occasionList != 0) {
                    $('#occasion_div').css({"display": "inline"});
                    $('#occasion').html(occasionList);
                } else {
                    $('#occasion_div').css({"display": "none"});
                    $('#cia_error_msg').html('<font color="red">' + entity_cie_full + ' (' + entity_cie + ') Occasions are not defined.</font>');
                }
            }
        });
    }      else if (type_data_id == 6) {
        //CIA
        var post_data = {
            'crclm_id': crclm_id,
            'term_id': term_id,
            'crs_id': crs_id
        }

        //if CIA is selected list occasions
        $.ajax({type: "POST",
            url: base_url + 'report/internal_final_exam/select_occasion_mte',
            data: post_data,
            success: function (occasionList) {
                if (occasionList != 0) {
                    $('#occasion_div').css({"display": "inline"});
                    $('#occasion').html(occasionList);
                } else {
                    $('#occasion_div').css({"display": "none"});
                    $('#cia_error_msg').html('<font color="red">' + entity_cie_full + ' (' + entity_cie + ') Occasions are not defined.</font>');
                }
            }
        });
    }else if (type_data_id == 4) {
        //CIA
        var post_data = {
            'crclm_id': crclm_id,
            'term_id': term_id,
            'crs_id': crs_id
        }

        //if CIA is selected list occasions
        $.ajax({type: "POST",
            url: base_url + 'report/internal_final_exam/select_occasion',
            data: post_data,
            success: function (occasionList) {
                if (occasionList != 0) {
                    $('#occasion_div').css({"display": "inline"});
                    $('#occasion').html(occasionList);
                } else {
                    $('#occasion_div').css({"display": "none"});
                    $('#cia_error_msg').html('<font color="red">' + entity_cie_full + '(CIA) Occasions are not defined</font>');
                }
            }
        });
    }
});

//function to display final exam dropdown
$('#ia_tee_form').on('change', '#occasion', function () {
    $('#cia_error_msg').html("");
    $('.exam_table_grid').html("");
    var crclm_id = $('#crclm').val();
    var term_id = $('#term').val();
    var crs_id = $('#course').val();
    var occasion_id = $('#occasion').val();
	var type_data_id = $('#ao_type_id').val();
	type_data_enable_dissable(ao_type_id , type_data_id);
    if (!occasion_id)
        $("a#export").attr("href", "#");
    else
    {
        $("a#export").attr("onclick", "generate_pdf(" + 0 + ");");
        $("a#export_doc").attr("onclick", "generate_pdf(" + 1 + ");");
    }
    if (occasion_id) {
        var post_data = {
            'crclm_id': crclm_id,
            'term_id': term_id,
            'crs_id': crs_id,
            'occasion_id': occasion_id
        }

        $.ajax({type: "POST",
            url: base_url + 'report/internal_final_exam/fetch_internal_final_exam_details',
            data: post_data,
            success: function (msg) {
                //to display data in the table grid
                if (msg == 1)
                    $('#cia_error_msg').html("<font color='red'>" + entity_cie_full + " Question Paper is not defined.");
                else
                    $('.exam_table_grid').html(msg);
            }
        })
    }
});


//export to .pdf
function generate_pdf(type)
{

    if (type == '0') {
        $('#doc_type').val('pdf');
    } else {
        $('#doc_type').val('word');
    }
    var cloned = $('#question_paper_report').clone().html();
    $('#pdf').val(cloned);
    $('#ia_tee_form').submit();
}

/* $('.export_to_doc').click(function() {
 var crclm_id = $('#crclm').val();
 var term_id = $('#term').val();
 var crs_id = $('#course').val();
 var ao_type_id = $('#ao_type_id').val();
 var occasion_id = $('#occasion').val();
 
 if(crclm_id && term_id && crs_id && ao_type_id != 0) {
 if(occasion_id) {
 //CIA
 
 } else {
 //TEE
 
 }
 } else {
 $('#export_to_doc_warning').modal('show');
 }
 }); */
	