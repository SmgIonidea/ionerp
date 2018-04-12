/*You may use scrollspy along with creating and removing elements form DOM. But if you do so, you have to call the refresh method . 
 The following code shows how you may do that:*/

$('[data-spy="scroll"]').each(function () {
    var $spy = $(this).scrollspy('refresh')
});

//set cookie
if ($.cookie('remember_curriculum') != null) {
    // set the option to selected that corresponds to what the cookie is set to
    $('#crclm option[value="' + $.cookie('remember_curriculum') + '"]').prop('selected', true);
    select_pos();
}

//second dropdown - term
//display grid on select of curriculum
function select_pos()
{
    $.cookie('remember_curriculum', $('#crclm option:selected').val(), {expires: 90, path: '/'});
    var data_val1 = document.getElementById('crclm').value;

    var crclmSelect = document.getElementById("crclm");
    var selectedcrclm = crclmSelect.options[crclmSelect.selectedIndex].text
    document.getElementById("curr").value = selectedcrclm;

    $('#loading').show();
    if (!data_val1)
        $("a#export").attr("href", "#");
    else
        $("a#export").attr("onclick", "generate_pdf();");
    var grid_path = 'transpose_report/grid';
    var post_data = {
        'crclm_id': data_val1,
    }
    if (data_val1) {
        $.ajax({type: "POST",
            url: grid_path,
            data: post_data,
            success: function (msg) {
                document.getElementById('program_articulation_matrix_grid').innerHTML = msg;
                $('#loading').hide();
            }
        });
    } else {
        $('#loading').hide();
    }
}

function fetch_crclm()
{
    var crclmSelect = document.getElementById("crclm");
    var selectedcrclm = crclmSelect.options[crclmSelect.selectedIndex].text
    document.getElementById("curr").value = selectedcrclm;
}

function generate_pdf()
{
    var cloned = $('#program_articulation_matrix_grid').clone().html();
    $('#pdf').val(cloned);
    $('#form1').submit();
}
