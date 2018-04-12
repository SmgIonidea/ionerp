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
            url: base_url + 'assessment_attainment/tier1_po_bacth_wise_attainment/get_po_list',
            data: post_data,
            dataType: 'json',
            success: function (data) {
                $('#po_populate_div').empty();
                $('#po_populate_div').html($.trim(data.po_option));
                
                $('#po_populate_div_one').empty();
                $('#po_populate_div_one').html($.trim(data.po_option_one));
            }
        });
});
//FILTER BOX
    if ($.cookie('remember_crclm') !== 0) {
		// set the option to selected that corresponds to what the cookie is set to
		$('#curriculum_data option[value="' + $.cookie('remember_crclm') + '"]').prop('selected', true);
                $('#curriculum_data').trigger('change');
	}
$('#po_populate_div').on('change','#po_data',function(){
    var crclm_id = $('#curriculum_data').val();
    var po_id = $(this).val();
    var post_data = {
        'crclm_id':crclm_id,
        'po_id':po_id,
    };
    $('#po_attain_table_plot_div').empty();
    $('#po_attain_chart1').empty();
    if(crclm_id && po_id){
        $('#export_doc').removeAttr('disabled');
    }else{
        $('#export_doc').attr('disabled','disabled');
    }
    $.ajax({
            type: "POST",
            url: base_url + 'assessment_attainment/tier1_po_bacth_wise_attainment/get_po_attainment_data',
            data: post_data,
            dataType:'json',
            success: function (data) {
                console.log(data.label);
                $('#po_attain_table_plot_div').empty();
                $('#po_attain_table_plot_div').html(data.table);
                var ticks = data.term_name;
                                $('#po_attain_chart1').empty();
                                var plot1 = $.jqplot('po_attain_chart1',data.graph_bars, {
								seriesColors : data.bar_color, // #4bb2c5   colors that will
						// be assigned to the series.  If there are more series than colors, colors
						// will wrap around and start at the beginning again.
						seriesDefaults : {
							renderer : jQuery.jqplot.BarRenderer,
							rendererOptions : {
								barWidth : 10,
								fill : true,
								showDataLabels : true,
								sliceMargin : 10,
								lineWidth : 5,
                                fillToZero : true,
								//dataLabelFormatString : '%.2f%'							
							},
							pointLabels : {
								show : true,
								stackedValue: true,
								//dataLabelFormatString : '%.2f%',
                                                               // formatString: '%s (%%)',
								//labels : [data.avg_po_att+'%', data.avg_dir_att+'%',data.hml_po_att+'%',data.hml_po_wtd_att+'%']
							}
						},
						series : data.label,
						
						highlighter : {
							show : true,
							tooltipLocation : 'e',
							tooltipAxes : 'x',
							fadeTooltip : true,
							showMarker : false,
							tooltipFormatString : '%s',
							tooltipContentEditor : function (str, seriesIndex, pointIndex, plot) {
								return data.po_statement[pointIndex];
							}
						},
						legend : {
							show : true,
							placement : 'outsideGrid'
						},
						axes : {
							xaxis : {
								renderer : $.jqplot.CategoryAxisRenderer,
								tickRenderer : $.jqplot.CanvasAxisTickRenderer,
								ticks : ticks
							},
							yaxis : {
								min : 0,
								max : 100,
								padMin : 0,
								tickOptions : {
									formatString : '%#.2f%'
								}
							}
						}
					}); 
                            var imgData = $('#po_attain_chart1').jqplotToImageStr({'width':'50'});
                            $('#batchwise_attainmetn_graph').val(imgData);
                            $('#export_doc').removeAttr('disabled');
              
            }
        });
});


/* In future this function is required.
 * $('#po_populate_div_one').on('change','#po_data_one',function(){
    var crclm_id = $('#curriculum_data').val();
    var po_id = $(this).val();
    var post_data = {
        'crclm_id':crclm_id,
        'po_id':po_id,
    };
    $.ajax({
            type: "POST",
            url: base_url + 'assessment_attainment/tier1_po_bacth_wise_attainment/get_across_crclm_po_attainment_data',
            data: post_data,
            dataType:'json',
            success: function (data) {
                
            }
        });
    });
    */