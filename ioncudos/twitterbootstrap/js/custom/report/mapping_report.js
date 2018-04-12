
//mapping report
$("a#export").hide();
var base_url = $('#get_base_url').val();
//set cookie
if ($.cookie('remember_curriculum') != null) {
    // set the option to selected that corresponds to what the cookie is set to
    $('#crclm option[value="' + $.cookie('remember_curriculum') + '"]').prop('selected', true);
    fetch_term();
}
//Function to fetch term details for term dropdown
function fetch_term() {
    $("a#export").hide();
    $.cookie('remember_curriculum', $('#crclm option:selected').val(), {expires: 90, path: '/'});
    var curriculum_id = document.getElementById('crclm').value;
    var option_id = document.getElementById('option').value;
    $('#loading').show();
    var post_data = {
        'curriculum_id': curriculum_id,
        'option_id': option_id
    }
    if (!curriculum_id) {
        $("a#export").attr("onclick", "");
    } else {
        $("a#export").attr("onclick", "generate_pdf();");
    }
    if (curriculum_id) {
        var termSelect = document.getElementById("crclm");
        var selectedterm = termSelect.options[termSelect.selectedIndex].text
        document.getElementById("curr").value = selectedterm;
        $.ajax({type: "POST",
            url: base_url + 'report/mapping_report/fetch_term',
            data: post_data,
            success: function (msg) {
                $("a#export").hide();
                document.getElementById('term').innerHTML = msg;
                if ($.cookie('remember_term') != null) {
                    $('#term option[value="' + $.cookie('remember_term') + '"]').prop('selected', true);
                }
            }
        });
        $.ajax({type: "POST",
            url: base_url + 'report/mapping_report/fetch_po_statement',
            data: post_data,
            success: function (msg) {
                document.getElementById('text_po_statement').innerHTML = msg;
                document.getElementById('po_stmt1').style.display = 'block';
            }
        });
        if (option_id == 3) {
            //$("#po").html($("#psos").val());
            $("#po").html("Program Educational Outcomes(PEOs)");
        } else
            //$("#po").html($("#pos").val());
            $("#po").html("Program Educational Outcomes(PEOs)");

        $.ajax({type: "POST",
            url: base_url + 'report/mapping_report/map_po_to_peo',
            data: post_data,
            success: function (msg) {
                $("a#export").hide();
                $('.map_po_to_peo').html(msg);
                document.getElementById('main_table').style.display = 'block';
                if ($.cookie('remember_term') != null) {
                    $('#term option[value="' + $.cookie('remember_term') + '"]').prop('selected', true);
                    fetch_course();
                }
                $('#loading').hide();
            }
        });
    } else {
        document.getElementById('text_po_statement').innerHTML = "";
        $('.map_po_to_peo').html("");
        $("#po").html("");
        document.getElementById('main_table').style.display = 'none';
        document.getElementById('po_stmt1').style.display = 'none';
        document.getElementById('term').innerHTML = "<option>Select Term</option>";
        $('#loading').hide();
    }
}

//Function to fetch course details
function fetch_course() {
    $.cookie('remember_term', $('#term option:selected').val(), {expires: 90, path: '/'});
    var curriculum_id = document.getElementById('crclm').value;
    var term_id = document.getElementById('term').value;
    var option_id = document.getElementById('option').value;
    $('#loading').show();
    var post_data = {
        'curriculum_id': curriculum_id,
        'term_id': term_id,
        'option_id': option_id
    }

    if (term_id) {
        $.ajax({type: "POST",
            url: base_url + 'report/mapping_report/fetch_course',
            data: post_data,
            success: function (msg) {
                $("a#export").hide();
                document.getElementById('course').innerHTML = msg;
                if ($.cookie('remember_course') != null) {
                    $('#course option[value="' + $.cookie('remember_course') + '"]').prop('selected', true);
                    fetch_all_details();
                }
                $('#loading').hide();
            }
        });
    } else {
        document.getElementById('course').innerHTML = "<option>Select Course</option>";
        $('.map_co_to_po').html("");
        document.getElementById('main_table1').style.display = 'none';
        document.getElementById('mapping').style.display = 'none';
        $('#loading').hide();
    }
}
//Function to fetch co to po mapping all details.
function fetch_all_details() {
    $.cookie('remember_course', $('#course option:selected').val(), {expires: 90, path: '/'});
    var curriculum_id = document.getElementById('crclm').value;
    var term_id = document.getElementById('term').value;
    var course_id = document.getElementById('course').value;
    var option_id = document.getElementById('option').value;
    $('#loading').show();
    if ($('input[type="checkbox"]').is(':checked')) {
        var mapped_list = parseInt(1);
    } else
        var mapped_list = parseInt(0);
    var post_data = {
        'course_id': course_id,
        'crclm_id': curriculum_id,
        'term_id': term_id,
        'option_id': option_id,
        'map_list': mapped_list
    }

    if (course_id && term_id) {
        $.ajax({type: "POST",
            url: base_url + 'report/mapping_report/map_co_to_po',
            data: post_data,
            success: function (msg) {
                $("a#export").show();
                $('.map_co_to_po').html(msg);
                document.getElementById('main_table1').style.display = 'block';
                document.getElementById('mapping').style.display = 'block';
                $('#loading').hide();
            }
        });
    } else {
        $('.map_co_to_po').html("");
        document.getElementById('main_table1').style.display = 'none';
        document.getElementById('mapping').style.display = 'none';
        $('#loading').hide();
    }
}
$('input[type="checkbox"]').bind('click', function () {
    fetch_all_details();
});

//Function to create pdf 
function generate_pdf() {//alert("check");
    var cloned = $('#main_table').clone().html();
    $('#pdf').val(cloned);
    var cloned = $('#main_table1').clone().html();
    $('#pdf').val($('#pdf').val() + "<p>" + cloned + "</p>");
    $("#po_statement").attr('class', 'table table-bordered table-hover');
    po_statements = $("#po_stmt1").html();
    $("#po_statement").attr('class', '');
    $('#pdf').val($('#pdf').val() + po_statements);
    $('#form_id').submit();
}

