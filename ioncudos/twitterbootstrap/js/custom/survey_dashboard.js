

/**********Dashboard(08-01-2015)**********/
var base_url = $('#get_base_url').val();
	

$('#survey_year, #department').change(function(){
	$('#pie_chart').empty();
	$('#stacked_bar_chart').empty();
        $('#survey_list').empty();
	var year_val = $('#survey_year').val();
        var dept_id = $('#department').val();
	var post_data = {
		'year_val' : year_val,
                'dept_id' : dept_id
	}
	if(year_val != 0 && dept_id != 0){ 
		$.ajax({type:"POST",
				url: base_url+'survey/dashboard/getChartData',
				data: post_data,
				dataType: 'json',
				success:function(data){
					if(Number(data['p']['total'][0]['Total'])){
						not_init = (Number(data['p']['not_init'][0]['Not Initiated']) * 100 ) / (Number(data['p']['total'][0]['Total']));
						inprog = (Number(data['p']['inprogress'][0]['Inprogress']) * 100 ) / (Number(data['p']['total'][0]['Total']));
						completed = (Number(data['p']['completed'][0]['Completed']) * 100 ) / (Number(data['p']['total'][0]['Total']));
						var plot1 = $.jqplot('pie_chart', [[['Not_Initiated',not_init],['Inprogress',inprog],['Completed',completed]]], {
							title: {
								text: 'Survey Status for the Year - '+year_val,
								show: true 
							},							
							seriesDefaults: {
								renderer: jQuery.jqplot.PieRenderer,
								trendline:{ show:false }, 
								rendererOptions: {
									padding: 8,
									fill: true,
									showDataLabels: true,
									sliceMargin: 4,
									lineWidth: 5
								}
							},				
							legend: {show: true, location: 'ne'}
						});
						
                                                
						var dept_name = new Array();
						var i = 0;
						$.each(data['s'],function(){
							dept_name.push(data['s'][i++]['dept_name'][0]['dept_acronym']);
						});
						var j = 0;
						var actual_data = new Array();
						var not_init_cnt = new Array();
						$.each(data['s'],function(){
							var not_init = Number(data['s'][j]['not_init'][0]['Not Initiated']);						
							not_init_cnt.push(not_init);
							j++;
						});
						var inprog_cnt = new Array();
						var k = 0;
						$.each(data['s'],function(){
							var inprogress = Number(data['s'][k]['inprogress'][0]['Inprogress']);							
							inprog_cnt.push(inprogress);
							k++;
						});
						var completed_cnt = new Array();
						var l = 0;
						$.each(data['s'],function(){						
							var completed = Number(data['s'][l]['completed'][0]['Completed']);				
							completed_cnt.push(completed);
							l++;
						});
						actual_data.push(not_init_cnt);
						actual_data.push(inprog_cnt);
						actual_data.push(completed_cnt);						
						/* var stacked_bar_chart = $.jqplot('stacked_bar_chart',  actual_data, {
								stackSeries: true,
								seriesDefaults: {
									renderer: $.jqplot.BarRenderer,
									rendererOptions: {
										barWidth:40,
										barMargin: 20
									},
									pointLabels: {
										show: true,
										stackedValue: false,
										location: 's'
									}
								},						
								axes: {
									xaxis: {
										renderer: $.jqplot.CategoryAxisRenderer,
										ticks: dept_name
									},
									yaxis: {
										
									}
								},
								legend: {
									show: true,
									location: 'ne',
									placement: 'outside'
								},
								title: "",
								series: [
									{label : 'Not Initiated'},
									{label : 'Inprogress'},
									{label : 'Completed'}
								]
						}); */
                                                var listHtml = '<table><tr><td style="background:#ccc;color:#0088cc;" colspan="3"><h4 style="margin:5px 0;">Survey Listing</h4></td></tr><tr style="color:#0088cc;"><th>Program</th><th>Survey</th><th>Report</th></tr>';
                                                var g = 0;
                                                $.each(data['listSurveys'],function(){
													if(data['listSurveys'][g]['user_count'] !=0 && data['listSurveys'][g]['crs_id'] != ''){
														listHtml+= '<tr><td>'+data['listSurveys'][g]['pgm_title']+'</td><td>'+data['listSurveys'][g]['name']+'</td><td><a href="survey/surveys/view_survey/'+data['listSurveys'][g]['survey_id']+'">View Report</a></td></tr>';
													}else if(data['listSurveys'][g]['crs_id'] != ''){
														//data['listSurveys'][g]['crs_id'] = '0';
														listHtml+= '<tr><td>'+data['listSurveys'][g]['pgm_title']+'</td><td>'+data['listSurveys'][g]['name']+'</td><td><a href="survey/surveys/indirect_attainment_report/'+data['listSurveys'][g]['crclm_id']+'/'+data['listSurveys'][g]['survey_id']+'/'+data['listSurveys'][g]['su_for']+'/'+data['listSurveys'][g]['crs_id']+'">View Report</a></td></tr>';
													}else{
														data['listSurveys'][g]['crs_id'] = '0';
														listHtml+= '<tr><td>'+data['listSurveys'][g]['pgm_title']+'</td><td>'+data['listSurveys'][g]['name']+'</td><td><a href="survey/surveys/indirect_attainment_report/'+data['listSurveys'][g]['crclm_id']+'/'+data['listSurveys'][g]['survey_id']+'/'+data['listSurveys'][g]['su_for']+'/'+data['listSurveys'][g]['crs_id']+'">View Report</a></td></tr>';
													}
                                                    g++;
                                                });
                                                listHtml+= '</table>';
                                                
                                                $('#survey_list').html(listHtml);
					} else {
						$('#pie_chart').html('Survey not created for selected year');
					}
				}
			});
	}
});