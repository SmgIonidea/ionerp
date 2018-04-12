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
            url: base_url + 'tier_ii/tier2_po_bacth_wise_attainment/get_po_list',
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
	if(po_id){
	 $('#export_doc').prop('disabled', false);
    $.ajax({
            type: "POST",
            url: base_url + 'tier_ii/tier2_po_bacth_wise_attainment/get_po_attainment_data',
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
								barWidth : 15,
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
              
            }
        });
	}else{  $('#export_doc').prop('disabled', true);}
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
	
	
	
$('form[name="po_attainment_within_batch_form"]').on('click', '#export_doc', function(e){
	var crclm_name = $("#curriculum_data option:selected").text(); 
		$('#po_attainment_graph_data').html('<table class="table table-bordered" style="width:100%;"><tr><td width=325><b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;">Curriculum : </b><b needAlt=1 class="font_h ul_class">'+ crclm_name +'</b></td></tr></table><br/><br/><b>Curriculum (Batch Wise) '+ po +' Report.</b><br/><br/>');
		var export_data =  $('#po_attain_table_plot_div').html();
		$('#export_data_to_doc').val(export_data);
		var imgData = $('#po_attain_chart1').jqplotToImageStr({});
		var main_head =  $('#po_attainment_graph_data').html();
		var filename  = po +'Batch_Wise_Report';
	$('#file_name').val(filename);
	$('#export_data_to_doc').val(export_data);
	$('#export_graph_data_to_doc').val(imgData);
	$('#main_head').val(main_head);
	$('#po_attainment_within_batch_form').submit();
});
