
//Course Delivery Report

var base_url = $('#get_base_url').val();

if ($.cookie('remember_curriculum') != null) {
    // set the option to selected that corresponds to what the cookie is set to
    $('#crclm option[value="' + $.cookie('remember_curriculum') + '"]').prop('selected', true);
    fetch_term();
}

//Function to fetch term details for term dropdown
function fetch_term() {
    $.cookie('remember_curriculum', $('#crclm option:selected').val(), {expires: 90, path: '/'});
    var curriculum_id = document.getElementById('crclm').value;

    $('#loading').show();
    var post_data = {
        'curriculum_id': curriculum_id
    }
    $.ajax({type: "POST",
        url: base_url + 'report/course_delivery_report/fetch_po_statement',
        data: post_data,
        success: function (msg) {
            document.getElementById('text_po_statement').innerHTML = msg;
        }
    });
    $.ajax({type: "POST",
        url: base_url + 'report/course_delivery_report/fetch_term',
        data: post_data,
        success: function (msg) {
            document.getElementById('term').innerHTML = msg;

            if ($.cookie('remember_term') != null) {
                // set the option to selected that corresponds to what the cookie is set to
                $('#term option[value="' + $.cookie('remember_term') + '"]').prop('selected', true);
                fetch_course();
            }
            $('#loading').hide();
        }
    });

}

//Function to fetch course details
function fetch_course() {
    var curriculum_id = document.getElementById('crclm').value;
    var term_id = document.getElementById('term').value;
    $.cookie('remember_term', $('#term option:selected').val(), {expires: 90, path: '/'});
    $('#loading').show();
    var post_data = {
        'curriculum_id': curriculum_id,
        'term_id': term_id
    }
    if (term_id) {
        $.ajax({type: "POST",
            url: base_url + 'report/course_delivery_report/fetch_course',
            data: post_data,
            success: function (msg) {
                document.getElementById('course').innerHTML = msg;
                if ($.cookie('remember_selected_course') != null) {
                    $('#course option[value="' + $.cookie('remember_selected_course') + '"]').prop('selected', true);
                    fetch_all_details();
                }
                $('#loading').hide();

            }
        });
    } else {
        document.getElementById('course').innerHTML = "<option>Select Course</option>";
        $('.div2').empty();
        $('.div3').empty();
        $('#clo_stmt').empty();
        $('#po_stmt').empty();
        $('#loading').hide();
    }
}

$('input[type="checkbox"]').bind('click', function () {
    fetch_all_details();
});
//Function to fetch grid and all other details on selecting course dropdown
function fetch_all_details() {
    $.cookie('remember_selected_course', $('#course option:selected').val(), {expires: 90, path: '/'});
    var curriculum_id = document.getElementById('crclm').value;
    var term_id = document.getElementById('term').value;
    var course_id = document.getElementById('course').value;
    $('#loading').show();
    if ($('input[type="checkbox"]').is(':checked')) {
        var check = parseInt(1);
    } else
        var check = parseInt(0);
    if (!course_id) {
        $("a#export").attr("href", "#");
    } else {
        $("a#export").attr("onclick", "generate_pdf();");
    }

    if (!course_id) {
        $("a#export_mapping").attr("href", "#");
    } else {
        $("a#export_mapping").attr("onclick", "generate_mapping_pdf();");
    }

    if (course_id) {
        var post_data = {
            'curriculum_id': curriculum_id,
            'term_id': term_id,
            'course_id': course_id,
            'status': check
        }

        $.ajax({type: "POST",
            url: base_url + 'report/course_delivery_report/fetch_clo_statement',
            data: post_data,
            success: function (msg) {
              document.getElementById('text_clo_statement').innerHTML = msg;
            }
        });

        $.ajax({type: "POST",
            url: base_url + 'report/course_delivery_report/fetch_course_plan',
            data: post_data,
            dataType: 'json',
            success: function (msg) {
                //to display data in the course delivery lesson plan grid
                $('.div2').html(msg['d1']);
                //to display data in the course delivery mapping grid
                $('.div3').html(msg['d2']);
                $('#loading').hide();
            }
        });
    } else {
        //to empty the course delivery grid content
        $('.div2').empty();
        $('.div3').empty();
        $('#clo_stmt').empty();
        $('#po_stmt').empty();
        $('#loading').hide();
    }
}

function generate_pdf() {
    var cloned = $('#gene_table').clone();
    cloned.find('.remove_li li').unwrap();
    cloned.find('label').wrap('<p></p>');
    $('#pdf').val(cloned.html());
    $('#form1').submit();
}

function generate_mapping_pdf() {
    var cloned = $('#main_table').clone().html();
    $('#pdf_mapping').val(cloned);
    $('#form2').submit();
}
