// Static View JS functions.
	var base_url = $('#get_base_url').val();
	$("#hint a").tooltip();
	var crs_id;
	var table_row;
	
if ($.cookie('remember_dept') != null) {
    // set the option to selected that corresponds to what the cookie is set to
    $('#department option[value="' + $.cookie('remember_dept') + '"]').prop('selected', true);
    select_pgm_list();
}
	function select_pgm_list() {
		$.cookie('remember_dept', $('#dept_id option:selected').val(), {expires: 90, path: '/'});
		var dept_id = document.getElementById('department').value;
		
		var post_data = {
			'dept_id': dept_id
		}
		
		$.ajax({type: "POST",
			url: base_url+'assessment_attainment/tee_cia_attainment/select_pgm_list',
			data: post_data,
			success: function(msg) {
				//document.getElementById('program').innerHTML = msg;
				$('#program').html(msg);
			if ($.cookie('remember_pgm') != null) {
                // set the option to selected that corresponds to what the cookie is set to
                $('#program option[value="' + $.cookie('remember_pgm') + '"]').prop('selected', true);
                select_crclm_list();
            }
			}
		});
	}
	
	function select_crclm_list()
	{
		$.cookie('remember_pgm', $('#program option:selected').val(), {expires: 90, path: '/'});
		var pgm_id = document.getElementById('program').value;
		var post_data = {
			'pgm_id': pgm_id
		} 
		
		$.ajax({type: "POST",
			url: base_url+'assessment_attainment/tee_cia_attainment/select_crclm_list',
			data: post_data,
			success: function(msg) {
				//document.getElementById('curriculum').innerHTML = msg;
				$('#curriculum').html(msg);
			 if ($.cookie('remember_crclm') != null) {
                // set the option to selected that corresponds to what the cookie is set to
                $('#curriculum option[value="' + $.cookie('remember_crclm') + '"]').prop('selected', true);
                //get_selected_value();
                select_termlist();
            }
			}
		});
	}
	
	function select_termlist() {
		$.cookie('remember_crclm', $('#curriculum option:selected').val(), {expires: 90, path: '/'});
		var crclm_id = document.getElementById('curriculum').value;
		
		var post_data = {
			'crclm_id': crclm_id
		}
		
		$.ajax({type: "POST",
			url: base_url+'assessment_attainment/tee_cia_attainment/select_termlist',
			data: post_data,
			success: function(msg) {
				$('#term').html(msg);
				if ($.cookie('remember_term') != null) {
                // set the option to selected that corresponds to what the cookie is set to
                $('#term option[value="' + $.cookie('remember_term') + '"]').prop('selected', true);
                GetSelectedValue();
               // select_termlist();
            }
			}
		});
	}
		
	/* Function is used to fetch course details.
	* @param - curriculum id & term id.
	* @returns- an array of course details.
	*/	
	function GetSelectedValue() {
		$.cookie('remember_term', $('#term option:selected').val(), {expires: 90, path: '/'});
		var dept_id = document.getElementById('department').value;
		var prog_id = document.getElementById('program').value;
		var crclm_id = document.getElementById('curriculum').value;
		var crclm_term_id = document.getElementById('term').value;
		
		var post_data = {
			'dept_id': dept_id,
			'prog_id': prog_id,
			'crclm_id': crclm_id,
			'crclm_term_id': crclm_term_id
		}
		
		$.ajax({
			type: "POST",
			url: base_url+'assessment_attainment/tee_cia_attainment/show_course',
			data: post_data,
			success: function(msg) {
				$('#generate_table').html(msg);
			}
		});
	}

	// List View JS functions.
	$('.get_crs_id').live("click", function() {
		crs_id = $(this).attr('id');
		table_row = $(this).closest("tr").get(0);
	});
	
//Function to fetch CIA Occasions details of corresponding Course
	$('.get_cia_details').live('click', function(e) {
		e.preventDefault();
		data_rowId = $(this).attr('id');
		var pgm_id = $('#program').val();
		var crclm_id = $('#curriculum').val();
		var term_id = $('#term').val();
		var post_data = {
			'crs_id': data_rowId,
			'pgm_id': pgm_id,
			'crclm_id':crclm_id,
			'term_id':term_id
		}
		$.ajax({type: "POST",
			url: base_url + 'assessment_attainment/import_cia_data/get_cia_details',
			data: post_data,
			datatype: "JSON",
			success: function(msg) {
				document.getElementById('get_cia_details_list').innerHTML = msg;
			}
		});
	});
	
	$(document).on('click','.view_qp',function(){
		document.getElementById('qp_content_display').innerHTML = "";
		$('#loading').prependTo($('#qp_content_display')); 
		$('#loading').show();
		var abbr_val = $(this).attr('abbr');
		var data = abbr_val.split("/");
		var pgmtype = data[0];
		var crclm_id = data[1];
		var term_id = data[2];
		var crs_id = data[3];
		var qp_type = data[4];
		var qpd_id = data[5];
		var post_data = {
			'pgmtype' : pgmtype,
			'crclm_id': crclm_id,
			'term_id' : term_id,
			'crs_id' : crs_id,
			'qp_type' : qp_type,
			'qpd_id' : qpd_id
		}
		$.ajax({type:"POST",
			url: base_url+'question_paper/manage_model_qp/generate_model_qp_modal_tee',
			data: post_data,
			cache: false,
			success:function(msg){
				document.getElementById('qp_content_display').innerHTML = msg;
				$('#loading').hide();			
				//if($('.myModalQPdisplay').hasClass('in')){
				//BloomsPlannedCoverageDistribution				
					$('#chart1').empty();
					$('#chart2').empty();
					$('#chart3').empty();
					$('#chart4').empty();
					$('#bloomslevelplannedcoveragedistribution > tbody:first').empty();
					$('#bloomslevelplannedcoveragedistribution_note').empty();
					$('#bloomslevelplannedmarksdistribution > tbody:first').empty();
					$('#bloomslevelplannedmarksdistribution_note').empty();
					$('#coplannedcoveragesdistribution > tbody:first').empty();
					$('#coplannedcoveragesdistribution_note').empty();
					$('#topicplannedcoveragesdistribution > tbody:first').empty();
					$('#topicplannedcoveragesdistribution_note').empty();
					var plot1,plot2,plot3;
					var BloomsLevel = $('#BloomsLevel').val();
					var PlannedPercentageDistribution = $('#PlannedPercentageDistribution').val();
					var ActualPercentageDistribution = $('#ActualPercentageDistribution').val();
					var BloomsLevelDescription = $('#BloomsLevelDescription').val();
					var pln = PlannedPercentageDistribution.split(",");
					var actual = ActualPercentageDistribution.split(",");
					var ticks = BloomsLevel.split(",");	 
					var level = BloomsLevelDescription.split(",");
					var i = 0;
					var s1_arr = new Array();
					$.each(pln, function(){
						s1_arr.push(Number(pln[i++]));
					});
					var j = 0;
					var s2_arr = new Array();
					$.each(actual, function(){
						s2_arr.push(Number(actual[j++]));
					});		
				
					plot1 = jQuery.jqplot('chart1',[s1_arr,s2_arr], {
						seriesDefaults:{
							renderer:$.jqplot.BarRenderer,
							pointLabels: { show: true }
						},
						series:[
							{label:'Framework Level Distribution'},
							{label:'Planned Distribution'}
						],
						axes: {
							xaxis: {
								renderer: $.jqplot.CategoryAxisRenderer,
								tickRenderer: $.jqplot.CanvasAxisTickRenderer ,
								ticks: ticks
							},
							yaxis: {
								min:0,
								max:100,
								tickOptions: {
									formatString: '%d%%'
								}
							}
						},
						highlighter: {					
							show: true,
							tooltipLocation: 'e', 
							tooltipAxes: 'x', 
							fadeTooltip	:true,
							showMarker:false,
							tooltipContentEditor:function (str, seriesIndex, pointIndex, plot){						
								return level[pointIndex];							
							}
						},
						legend: {
							show: true,
							location: 'ne',
							placement: 'inside'
						}
					});
				
					var k = 0;
					$('#bloomslevelplannedcoveragedistribution > thead:first').html('<tr><td><center><b>Blooms Level</b></center></td><td><center><b>Framework level Distribution</b></center></td><td><center><b>Planned Distribution</b></center></td></tr>');
					$.each(ticks, function(){
						$('#bloomslevelplannedcoveragedistribution > tbody:last').append('<tr><td><center>'+ticks[k]+'</center></td><td><center>'+pln[k]+' %</center></td><td><center>'+actual[k]+' %</center></td></tr>');
						k++;
					});
					$('#bloomslevelplannedcoveragedistribution_note').append('<br><div class="bs-docs-example"><b>Note:</b><table class="table table-bordered"><tbody><tr><td>The above bar graph depicts the individual Blooms Level planned coverage percentage distribution and Blooms Level actual coverage percentage distribution as in the question paper.<br>Planned distribution is defined by the QP Framework.</td></tr><tr><td><b>Distribution % = ((Count of questions at each Blooms Level) / (Total number of questions) ) * 100 </b></td></tr></tbody></table></div>');
				
					//BloomsLevelMarksDistribution		
					var blooms_level_marks_dist = $('#blooms_level_marks_dist').val();
					var total_marks_marks_dist = $('#total_marks_marks_dist').val();
					var percentage_distribution_marks_dist = $('#percentage_distribution_marks_dist').val();
					var bloom_level_marks_desc = $('#bloom_level_marks_description').val();
					blooms_level = blooms_level_marks_dist.split(",");
					total_marks_dist = total_marks_marks_dist.split(",");
					percentage_dist = percentage_distribution_marks_dist.split(",");
					bloom_lvl_marks_desc = bloom_level_marks_desc.split(",");
					actual_data = new Array();
					var i = 0;
					var j = 0;
					$.each(blooms_level,function() {
						var bloom_lvl = blooms_level[i];
						var percent_distr = percentage_dist[i];
						data = new Array();					
						data.push(bloom_lvl,Number(percent_distr));
						i++;
						actual_data[j++] = data;
					});
					plot2 = jQuery.jqplot('chart2', [actual_data],{
								title: {
									text: '',//Blooms Level Planned Marks Distribution',
									show: true 
								},							
								seriesDefaults: {
									renderer: jQuery.jqplot.PieRenderer,
									rendererOptions: {
										fill: true,
										showDataLabels: true,
										sliceMargin: 4,
										lineWidth: 5,
										dataLabelFormatString: '%.2f%'
									}
								},				
								legend: {show: true, location: 'ne'},
								highlighter: {
									show: true,
									tooltipLocation: 's', 
									tooltipAxes: 'y', 
									useAxesFormatters: false,
									tooltipFormatString: '%s',
									tooltipContentEditor:function(str, seriesIndex, pointIndex, plot){
										return bloom_lvl_marks_desc[pointIndex];
									}
								}
							}); 
					$('#bloomslevelplannedmarksdistribution > thead:first').html('<tr><td><center><b>Blooms Level</b></center></td><td><center><b>Marks Distribution</b></center></td><td><center><b>% Distribution</b></center></td></tr>');
					var l = 0;
					$.each(blooms_level, function(){
						$('#bloomslevelplannedmarksdistribution > tbody:last').append('<tr><td><center>'+blooms_level[l]+'</center></td><td><center>'+total_marks_dist[l]+'</center></td><td><center>'+percentage_dist[l]+' %</center></td></tr>');
						l++;
					});					
					$('#bloomslevelplannedmarksdistribution_note').append('<br><div class="bs-docs-example"><b>Note:</b><table class="table table-bordered"><tbody><tr><td>The above pie chart depicts the individual Blooms Level actual marks percentage distribution as in the question paper.</td></tr><tr><td><b> Distribution % = (Sum(Count of individual Blooms Level marks) * 100) / (Total marks)</b></td></tr></tbody></table></div>'); 
				
					//COPlannedCoverageDistribution		
					var clo_code = $('#clo_code').val();
					var clo_total_marks_dist = $('#clo_total_marks_dist').val();
					var clo_percentage_dist = $('#clo_percentage_dist').val();
					var clo_statement_dist = $('#clo_statement_dist').val();
					clo_code = clo_code.split(",");
					clo_total_marks_dist = clo_total_marks_dist.split(",");
					clo_percentage_dist = clo_percentage_dist.split(",");
					clo_statement_dist = clo_statement_dist.split(",");
					actual_data = new Array();
					var i = 0;
					var j = 0;
					$.each(clo_code,function() {
						var clo_code_data = clo_code[i];
						var clo_percentage_dist_data = clo_percentage_dist[i];
						data = new Array();					
						data.push(clo_code_data,Number(clo_percentage_dist_data));
						i++;
						actual_data[j++] = data;
					});
					plot3 = jQuery.jqplot('chart3', [actual_data],{
								title: {
									text: '',//Blooms Level Planned Marks Distribution',
									show: true 
								},							
								seriesDefaults: {
									renderer: jQuery.jqplot.PieRenderer,
									rendererOptions: {
										fill: true,
										showDataLabels: true,
										sliceMargin: 4,
										lineWidth: 5,
										dataLabelFormatString: '%.2f%'
									}
								},				
								legend: {show: true, location: 'ne'},
								highlighter: {
									show: true,
									tooltipLocation: 's', 
									tooltipAxes: 'y', 
									useAxesFormatters: false,
									tooltipFormatString: '%s',
									tooltipContentEditor:function(str, seriesIndex, pointIndex, plot){
										return clo_statement_dist[pointIndex];
									}
								}
							}); 
					$('#coplannedcoveragesdistribution > thead:first').html('<tr><td><center><b>'+entity_clo+' Level</b></center></td><td><center><b>Planned Marks</b></center></td><td><center><b>Planned Distribution</b></center></td></tr>');
					var m = 0;
					$.each(clo_code, function(){
						$('#coplannedcoveragesdistribution > tbody:last').append('<tr><td><center>'+clo_code[m]+'</center></td><td><center>'+clo_total_marks_dist[m]+'</center></td><td><center>'+clo_percentage_dist[m]+' %</center></td></tr>');
						m++;
					});				
					$('#coplannedcoveragesdistribution_note').append('<br><div class="bs-docs-example"><b>Note:</b><table class="table table-bordered"><tbody><tr><td>The above pie chart depicts the individual Course Outcome(CO) wise actual marks percentage distribution as in the question paper.</td></tr><tr><td><b> Planned Distribution % = (Sum(Count of individual '+entity_clo+' marks) * 100) / (Total marks)</b></td></tr></tbody></table></div>'); 
											
					//topicCoverageDistribution
					var topic_title = $('#topic_title').val();
					var topic_marks_dist = $('#topic_marks_dist').val();
					var topic_percentage_dist = $('#topic_percentage_dist').val();
					topic_title = topic_title.split(",");
					topic_marks_dist = topic_marks_dist.split(",");
					topic_percentage_dist = topic_percentage_dist.split(",");
					actual_topic_data = new Array();
					var i = 0;
					var j = 0;
					$.each(topic_title,function() {
						var topic_title_data = topic_title[i];
						var topic_percentage_dist_data = topic_percentage_dist[i];
						topic_data = new Array();					
						topic_data.push(topic_title_data,Number(topic_percentage_dist_data));
						i++;
						actual_topic_data[j++] = topic_data;
					});
					topic_chart_plot = jQuery.jqplot('chart4', [actual_topic_data],{
								title: {
									text: '',//Blooms Level Planned Marks Distribution',
									show: true 
								},							
								seriesDefaults: {
									renderer: jQuery.jqplot.PieRenderer,
									rendererOptions: {
										fill: true,
										showDataLabels: true,
										sliceMargin: 4,
										lineWidth: 5,
										dataLabelFormatString: '%.2f%'
									}
								},				
								legend: {show: true, location: 'ne'},
								highlighter: {
									show: true,
									tooltipLocation: 's', 
									tooltipAxes: 'y', 
									useAxesFormatters: false,
									tooltipFormatString: '%s',
									tooltipContentEditor:function(str, seriesIndex, pointIndex, plot){
										return topic_title[pointIndex];
									}
								}
							});
					$('#topicplannedcoveragesdistribution > thead:first').html('<tr><td><center><b>'+entity_topic+'</b></center></td><td><center><b>Planned Marks</b></center></td><td><center><b>Planned Distribution</b></center></td></tr>');
					var m = 0;
					$.each(topic_title, function(){
						$('#topicplannedcoveragesdistribution > tbody:last').append('<tr><td><center>'+topic_title[m]+'</center></td><td><center>'+topic_marks_dist[m]+'</center></td><td><center>'+topic_percentage_dist[m]+' %</center></td></tr>');
						m++;
					});				
					$('#topicplannedcoveragesdistribution_note').append('<br><div class="bs-docs-example"><b>Note:</b><table class="table table-bordered"><tbody><tr><td>The above pie chart depicts the '+ entity_topic +'Coverage wise actual marks percentage distribution as in the question paper.</td></tr><tr><td><b> Planned Distribution % = (Sum(Count of individual '+entity_topic+' marks) * 100) / (Total marks)</b></td></tr></tbody></table></div>');  
				//}
				$('.myModalQPdisplay').on('shown',function(){
					plot1.replot();
					plot2.replot();
					plot3.replot();
					topic_chart_plot.replot();
				}); 
			}
		});
		$('.myModalQPdisplay').modal('show');
	});

	$('#export').on('click',function(){				
		var imgData_1 = $('#chart1').jqplotToImageStr({});
		var imgElem_1 = $('<img/>').attr('src',imgData_1);
		$('#chart1_img').html('<b>Blooms Planned Coverage Distribution</b><br><br>');
		$('#chart1_img').append(imgElem_1);
		$('#chart1_img').append($('#bloomslevelplannedcoveragedistribution_div').clone().html());
		$('#chart1_img').append($('#bloomslevelplannedcoveragedistribution_note').clone().html());
		
		var imgData_2 = $('#chart2').jqplotToImageStr({});
		var imgElem_2 = $('<img/>').attr('src',imgData_2);
		$('#chart2_img').html('<b>Blooms Planned Marks Distribution</b><br><br>');
		$('#chart2_img').append(imgElem_2);
		$('#chart2_img').append($('#bloomslevelplannedmarksdistribution_div').clone().html());
		$('#chart2_img').append($('#bloomslevelplannedmarksdistribution_note').clone().html());
		
		var imgData_3 = $('#chart3').jqplotToImageStr({});
		var imgElem_3 = $('<img/>').attr('src',imgData_3);
		$('#chart3_img').html('<b>Course Outcome Planned Coverage Distribution</b><br><br>');
		$('#chart3_img').append(imgElem_3);
		$('#chart3_img').append($('#coplannedcoveragesdistribution_div').clone().html());
		$('#chart3_img').append($('#coplannedcoveragesdistribution_note').clone().html());
		
		//Topic Coverage Distribution
		var imgData_4 = $('#chart4').jqplotToImageStr({});
		var imgElem_4 = $('<img/>').attr('src',imgData_4);
		$('#chart4_img').html('<b>'+Topic+' Coverage Distribution</b><br><br><br>');
		$('#chart4_img').append(imgElem_4);
		$('#chart4_img').append($('#topicplannedcoveragesdistribution_div').clone().html());
		$('#chart4_img').append($('#topicplannedcoveragesdistribution_note').clone().html());
		
		var question_paper = $('#qp_content_pdf').clone().html();
		var total_val = $('#total').clone().html();
		var chart1_img = $('#chart1_img').clone().html();
		var chart2_img = $('#chart2_img').clone().html();
		var chart3_img = $('#chart3_img').clone().html();
		var chart4_img = $('#chart4_img').clone().html();
		$('#qp_hidden').val(question_paper);	
		$('#total_val_hidden').val(total_val);	
		$('#chart1_img_hidden').val(chart1_img);	
		$('#chart2_img_hidden').val(chart2_img);	
		$('#chart3_img_hidden').val(chart3_img);	
		$('#chart4_img_hidden').val(chart4_img);			
		$('#export_model_qp_pdf').submit();
	});
