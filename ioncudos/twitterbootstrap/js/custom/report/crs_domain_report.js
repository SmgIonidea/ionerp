/* You may use scrollspy along with creating and removing elements form DOM. 
 * But if you do so, you have to call the refresh method . 
 * The following code shows how you may do that:
 */
$("#export").hide();
$('[data-spy="scroll"]').each(function () {
    var $spy = $(this).scrollspy('refresh')
});

if ($.cookie('remember_department') != null) {
    // set the option to selected that corresponds to what the cookie is set to
    $('#dept option[value="' + $.cookie('remember_department') + '"]').prop('selected', true);
    fetch_curriculum();
}

/* Function is used to fetch curriculum details.
 * @param- department id.
 * @retuns - an object array values of curriculum.
 */
function fetch_curriculum()
{

    $.cookie('remember_department', $('#dept option:selected').val(), {expires: 90, path: '/'});
    var base_url = $('#get_base_url').val();
    var data_val1 = document.getElementById('dept').value;
    $('#loading').show();
    var post_data = {
        'dept_id': data_val1,
    }
    $.ajax({type: "POST",
        url: base_url + 'report/crs_domain_report/fetch_crclm',
        data: post_data,
        success: function (msg) {
            $("a#export").hide();
            document.getElementById('crclm').innerHTML = msg;
            if ($.cookie('remember_curriculum') != null) {
                $('#crclm option[value="' + $.cookie('remember_curriculum') + '"]').prop('selected', true);
                display_table();
            }
            $('#loading').hide();
        }
    });
}

/* Function is used to fetch mapped Term, Course Domain & Course details.
 * @param-
 * @retuns - the table grid view of course stream report details.
 */
function display_table()
{
    //$("a#export").hide();
    $.cookie('remember_curriculum', $('#crclm option:selected').val(), {expires: 90, path: '/'});
    var base_url = $('#get_base_url').val();
    var data_val = document.getElementById('dept').value;
    var data_val1 = document.getElementById('crclm').value;
    $('#loading').show();
    if (!data_val1)
        $("a#export").attr("href", "#");
    else
        $("a#export").attr("onclick", "generate_pdf();");
    var post_data = {
        'dept_id': data_val,
        'crclm_id': data_val1,
    }
    if (data_val1) {
        $.ajax({type: "POST",
            url: base_url + 'report/crs_domain_report/generate_table_grid',
            data: post_data,
            success: function (msg) {
                $("a#export").show();
                document.getElementById('crs_stream_report_table_id').innerHTML = msg;
                $('#loading').hide();
            }
        });
    } else {
        document.getElementById('crs_stream_report_table_id').innerHTML = "";
        $('#loading').hide();
    }
}

// Function is used to insert the curriculum id onto an hidden input field.
function fetch_crclm()
{
    $("a#export").show();
    var crclmSelect = document.getElementById("crclm");
    var selectedcrclm = crclmSelect.options[crclmSelect.selectedIndex].text
    document.getElementById("curr").value = selectedcrclm;

}

function generate_pdf()
{
    var cloned = $('#crs_stream_report_table_id').clone().html();
    $('#pdf').val(cloned);
    $('#form_id').submit();
}
