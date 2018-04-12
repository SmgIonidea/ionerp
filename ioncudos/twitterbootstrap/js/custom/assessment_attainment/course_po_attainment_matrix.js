/*
 * Description	:	JScript for Tier I PO Batch Wise Attainment Report
 
 * Created		:	Dec 22nd, 2016
 
 * Author		:	Mritunjay B S 
 
 * Modification History:
 * Date				Modified By				Description
 
 ----------------------------------------------------------------------------------------------*/
var base_url = $('#get_base_url').val();

$('#curriculum_data').on('change',function(){
    $('#po_attain_table_plot_div').empty();
    $('#po_attain_chart1').empty();
    var crclm_id = $(this).val();
    var post_data = {
        'crclm_id':crclm_id,
    };
    $.ajax({
            type: "POST",
            url: base_url + 'assessment_attainment/course_po_attainment_matrix/get_occasion_type',
            data: post_data,
            dataType: 'json',
            success: function (data) {
                $('#occasion_type').empty();
                $('#occasion_type').append($(data.option));
            }
        });

});

$('#occasion_type').on('click',function(){
    $('#po_attain_table_plot_div').empty();
    $('#po_attain_chart1').empty();
    var crclm_id = $('#curriculum_data').val();
    var occa_type = $(this).val();
    var post_data = {
        'crclm_id':crclm_id,
        'occa_type':occa_type,
    };

        $.ajax({
            type: "POST",
            url: base_url + 'assessment_attainment/course_po_attainment_matrix/get_po_attainment_data',
            data: post_data,
            dataType: 'json',
            success: function (data) {
                $('#po_attainment_report_div').empty();
                if($.trim(data.msg) == 'success'){
                    $('#po_attainment_report_div').html($.trim(data.po_attainment));
                    $('#course_po_matrices_tab').dataTable({
                                "fnDrawCallback": function () {
                                    $('.group').parent().css({'background-color': '#C7C5C5'});
                                },
                                "aoColumnDefs": [
                                    {"sType": "natural", "aTargets": [2]}
                                ],
                               // "sPaginationType": "bootstrap",
                                "bPaginate": false, //hide pagination
                                "bFilter": false, //hide Search bar
                                "bInfo": false, // hide showing entries

                            }).rowGrouping({iGroupingColumnIndex: 0,
                                bHideGroupingColumn: true});
                    $('#export_doc').removeAttr('disabled');
                    //var html_data_doc = $('#po_attainment_report_div').html();
                    //$('#doc_data').val(html_data_doc);
                }else{
                    $('#po_attainment_report_div').html($.trim(data.error_message));
                }
                
            }
        }); 
});

//FILTER BOX
    if ($.cookie('remember_crclm') !== 0) {
		// set the option to selected that corresponds to what the cookie is set to
		$('#curriculum_data option[value="' + $.cookie('remember_crclm') + '"]').prop('selected', true);
                $('#curriculum_data').trigger('change');
	}
        
   $('#export_doc').on('click',function(){
       //$('#graph').jqplotToImageStr({});
       var clone_data = $('#po_attainment_report_div').clone();
        var filename = 'PO & PSO Attainment Matrices Report';
       $(clone_data).wordExport(filename);
   });
