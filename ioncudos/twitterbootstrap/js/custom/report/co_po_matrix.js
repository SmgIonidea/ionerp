$(document).ready(function () {});
$("a#export").hide();
$('#generate_clo').hide();
$(function () {
    $('#curriculum_list_cos').on('change', function () {
        var base_url = $('#get_base_url').val();
        var curriculum = $(this).val();
        if (!curriculum) {
            $("a#export").attr("onclick", "");
        } else {
            $("a#export").attr("onclick", "generate_pdf();");
        }
        var post_data = {
            'curriculum': curriculum,
        }
        $.ajax({
            type: "POST",
            url: base_url + 'report/co_po_matrix/display_co',
            async: false,
            data: post_data,
            dataType: 'json',
            success: function (msg) {
                $("a#export").show();
                $('#co_vw_id').empty();
                $('#clo_vw_id').empty();
                $('#co_vw_id').html(msg['crs_view']);
                //$('#clo_vw_id').html(msg['clo_view']);
            }
        });
    });


    $('#view').on('click', '.select_all_box', function () {
        $("a#generate_clo").hide();
        var inc_val = $(this).attr('data-count');
        var crclm_id = $('#curriculum_list_course_po').val();
        var crs_id_array = new Array();
        if ($('.select_all_chk_' + inc_val).is(':checked')) {
            $('.select_all__' + inc_val).each(function () {
                $(this).prop('checked', true);
            });
        } else {
            $('.select_all__' + inc_val).each(function () {
                $(this).prop('checked', false);
            });
        }
    });

    $('#generate_clo').on('click', function () {
        $("a#export").show();
        $('#loading').show();
        var postdata = {
            'view_form': $('#view_form .filter').serializeArray(),
            'curriculum': $('#curriculum_list_cos').val(),
        };
        $.ajax({
            data: postdata,
            async: false,
            type: 'post',
            url: base_url + "report/co_po_matrix/generate_report",
            success: function (msg) {
                $('#co_matrix_data').html(msg);
                $('#loading').hide();
            }
        });
        $('#loading').hide();
    });

    $('.filter').on('click', function () {
        $('#generate_clo').show();
    });
});

function generate_pdf() {
    var co_po_matrices_data = $('#co_matrix_data').clone().html();
    $('#pdf').val(co_po_matrices_data);
    $('#view_form').submit();
}