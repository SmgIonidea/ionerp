
//Course Delivery Report

var base_url = $('#get_base_url').val();

//set cookie
if ($.cookie('remember_curriculum') != null) {
    // set the option to selected that corresponds to what the cookie is set to
    $('#curriculum option[value="' + $.cookie('remember_curriculum') + '"]').prop('selected', true);
    termlist();
}

function termlist() {
    $.cookie('remember_curriculum', $('#curriculum option:selected').val(), {expires: 90, path: '/'});
    var crclm_id = $('#curriculum').val();
    $('#loading').show();
    var post_data = {
        'crclm_id': crclm_id
    }
    if (crclm_id) {
        $.ajax({type: "POST",
            url: base_url + 'report/course_report/get_termlist',
            data: post_data,
            success: function (msg) {
                $('#term').html(msg);
                if ($.cookie('remember_term') != null) {
                    $('#term option[value="' + $.cookie('remember_term') + '"]').prop('selected', true);
                    get_courses();
                }
                $('#loading').hide();
            }
        });
    } else {
        $('#term').html("<option>Select Term</option>");
        $('#course_info').html("");
        $('#loading').hide();
    }
}

function get_courses() {
    $.cookie('remember_term', $('#term option:selected').val(), {expires: 90, path: '/'});
    var crclm_id = $('#curriculum').val();
    var term_id = $('#term').val();
    if (!term_id)
        $("a#export").attr("href", "#");
    else
    {
        $("a#export").attr("onclick", "generate_pdf(" + 0 + ");");
        $("a#export_doc").attr("onclick", "generate_pdf(" + 1 + ");");
    }

    $('#loading').show();
    if (term_id) {
        var post_data = {
            'crclm_id': crclm_id,
            'term_id': term_id
        }

        $.ajax({type: "POST",
            url: base_url + 'report/course_report/get_courses',
            data: post_data,
            success: function (msg) {
                $('#course_info').html(msg);
                $('#course_info').append('<center><b>' + entity_cie + ' :</b> ' + entity_cie_full + '<b> &nbsp;&nbsp;' + entity_tee + ' :</b> ' + entity_tee_full + '<b>&nbsp;&nbsp; L :</b> Lecture<b>&nbsp;&nbsp; T :</b> Tutorials<b>&nbsp;&nbsp; P :</b> Practical<b>&nbsp;&nbsp; SS :</b> Self Study</center>');
                $('#loading').hide();
            }
        });
    } else {

        $('#course_info').html("");
        $('#loading').hide();
    }
}
function generate_pdf(type)
{

    if (type == '0') {
        $('#doc_type').val('pdf');
    } else {
        $('#doc_type').val('word');
    }
    var cloned = $('#course_info').clone().html();
    $('#course_info_hidden').val('<b>Curriculum : </b><span style="color:blue;">' + $("#curriculum option:selected").text() + '</span><br /><b>Term : </b><span style="color:blue;">' + $("#term option:selected").text() + '</span>' + cloned);
    $('#list_course_details').submit();
}
// $('#export_course').on('click', function(){
// var pdf_data = $('#course_info').clone().html();
// $('#course_info_hidden').val('<b>Curriculum : <span style="color:blue;">'+$("#curriculum option:selected").text()+'</span></b><br /><br /><b>Term : <span style="color:blue;">'+$("#term option:selected").text()+'</span></b>'+pdf_data);
// $('#list_course_details').submit();
// });
