
//Curriculum details report

var base_url = $('#get_base_url').val();
//set cookie.
$('#crclm option[value=""]').prop('selected', true);
$('.export_doc').hide();
if ($.cookie('remember_curriculum') != null) {
    // set the option to selected that corresponds to what the cookie is set to
    $('#crclm option[value=""]').prop('selected', true);
    $('.export_doc').hide();
    $('#crclm option[value="' + $.cookie('remember_curriculum') + '"]').prop('selected', true);
    curriculum_details();
}

if ($.cookie('remember_curriculum') == '') {
    $(".export_doc").hide();
}

function curriculum_details() {
    $('.export_doc').show();
    $.cookie('remember_curriculum', $('#crclm option:selected').val(), {expires: 90, path: '/'});

    if ($('#crclm option:selected').val() == '') {
        $(".export_doc").hide();
    }

    var crclm_id = $('#crclm').val();
    $('#loading').show();
    var post_data = {
        'crclm_id': crclm_id
    }

    if (crclm_id) {

        $("a#export").attr("onclick", "generate_pdf(" + 0 + ");");
        $("a#export_doc").attr("onclick", "generate_pdf(" + 1 + ");");

        $.ajax({type: "POST",
            url: base_url + 'curriculum/curriculum_details/get_curriculum_details',
            data: post_data,
            success: function (msg) {
                $('#curriculum_details_div').html(msg);
                $('#loading').hide();
            }
        });
    } else {
        $('#curriculum_details_div').html("");
        $('#loading').hide();
    }
}

// $('#export').on('click',function(){
// var export_data = $('#curriculum_details_div').clone().html();
// $('#curriculum_details_div_hidden').empty('');
// $('#curriculum_details_div_hidden').val('<b>Curriculum : <span styl="color:blue;">'+$("#crclm option:selected").text()+'</span></b><br /><br />'+export_data);	
// $('#list_curriculum_details').submit();
// });	
function generate_pdf(type)
{
    if (type == '0') {
        $('#doc_type').val('pdf');
    } else {
        $('#doc_type').val('word');
    }
    var export_data = $('#curriculum_details_div').clone().html();
    $('#curriculum_details_div_hidden').empty('');
    $('#curriculum_details_div_hidden').val('<b>Curriculum :' + $("#crclm option:selected").text() + '</b>' + export_data);
    $('#list_curriculum_details').submit();
}


