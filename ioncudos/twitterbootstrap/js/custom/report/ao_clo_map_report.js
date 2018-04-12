//Mapping Report of Assessment Occasions (AO) to Course Outcomes (CO)

var base_url = $('#get_base_url').val();

//Mapping Report of Assessment Occasions (AO) to Course Outcomes (CO) View and Static View Page
/*You may use scrollspy along with creating and removing elements form DOM. But if you do so, you have to call the refresh method . 
 The following code shows how you may do that:*/

$('[data-spy="scroll"]').each(function () {
    var $spy = $(this).scrollspy('refresh')
});

if ($.cookie('remember_crclm') != null) {
    // set the option to selected that corresponds to what the cookie is set to
    $('#crclm option[value="' + $.cookie('remember_crclm') + '"]').prop('selected', true);
    select_term();
}

//Function to fetch term details for term dropdown
function select_term() {
    $.cookie('remember_crclm', $('#crclm option:selected').val(), {expires: 90, path: '/'});
    var curriculum_id = document.getElementById('crclm').value;
    $('#loading').show();
    var post_data = {
        'curriculum_id': curriculum_id
    }

    $.ajax({type: "POST",
        url: base_url + 'report/ao_clo_map_report/select_term',
        data: post_data,
        success: function (msg) {
            document.getElementById('term').innerHTML = msg;
            if ($.cookie('remember_term') != null) {
                // set the option to selected that corresponds to what the cookie is set to
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
        url: base_url + 'report/ao_clo_map_report/term_course_details',
        data: post_data,
        success: function (msg) {
            document.getElementById('course').innerHTML = msg;
            if ($.cookie('remember_course') != null) {
                // set the option to selected that corresponds to what the cookie is set to
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
        $("a#export").attr("onclick", "generate_pdf();");
    }

    var post_data = {
        'curriculum_term_id': curriculum_term_id,
        'curriculum_id': curriculum_id,
        'course_id': course_id,
    }
    if (course_id) {
        $.ajax({type: "POST",
            url: base_url + 'report/ao_clo_map_report/ao_clo_mapping',
            data: post_data,
            success: function (msg) {
                document.getElementById('ao_clo_view_id').innerHTML = msg;
                $('#loading').hide();
            }
        });
    } else {
        document.getElementById('ao_clo_view_id').innerHTML = "";
        $('#loading').hide();
    }
}

//Function to generate .pdf
function generate_pdf() {
    var cloned = $('#ao_clo_view_id').clone().html();
    $('#pdf').val(cloned);
    $('#form1').submit();
}
