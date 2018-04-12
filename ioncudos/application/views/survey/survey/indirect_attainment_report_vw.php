
<style>
.div_border{
	border:1px solid #ddd;
	position:relative;
	padding: 10px 20px 10px;
}
.div_margin{
	margin-left: 0px !important;
}


</style>
<div class="row-fluid">
    <div class="span12">
        <div class="row-fluid"> 
			<div class="control-group" id="survey_information">
                                    <h5><center>Survey Details</center></h5>
                                     <div class="bs-docs-example">
                                         <table style="width: 100%;text-align: center;" class="table table-bordered">
                                        <tr>
                                            <td><b style="color:blue;"> Survey: </b><?php echo $meta_data['name'];?></td>
                                            <td><b style="color:blue;"> Survey Type: </b><?php echo $meta_data['mt_details_name']; ?></td>
                                            <td><b style="color:blue;"> Survey For: </b><?php if($survey_for==6){ echo 'PEO';} if($survey_for==7){ echo 'PO';} if($survey_for==8){ echo 'CO';} ?></td>
                                        </tr>
                                        <tr>
                                            <td><b style="color:blue;"> Curriculum Name: </b><?php echo $meta_data['crclm_name']; ?></td>
											<?php if($survey_for == 8){ ?>
                                            <td><b style="color:blue;"> Term: </b><?php echo $meta_data['term_name']; ?></td>
                                            <td><b style="color:blue;"> Course: </b><?php echo $meta_data['crs_title']; ?></td>
											<?php } else{ ?>
											<td><b style="color:blue;">  </b></td>
                                            <td><b style="color:blue;">  </b></td>
											<?php } ?>
                                        </tr>
                                        <!--<tr>
                                            <td colspan="3"><b style="color:blue;">Report Summary:</b>
                                                Number of people Responded / Total number of  Responders: <b><?php echo $responded." / "; ?><?php echo $totalResp; ?></b> People
                                            </td>
                                        </tr>-->
                                    </table>
                                     </div>
                                </div>
				<input type="hidden" name="in_curriculum_id" id="in_curriculum_id" value="<?php echo $crclm_id; ?>" /> 
				<input type="hidden" name="in_survey_id" id="in_survey_id" value="<?php echo $survey_id; ?>" /> 
				<input type="hidden" name="in_su_for" id="in_su_for" value="<?php echo $survey_for; ?>" /> 
				<input type="hidden" name="in_crs_id" id="in_crs_id" value="<?php echo $crs_id; ?>" /> 
								<br><br>
				<div id="indirect_attainment_actual_data" style="height:auto;">

                </div>
                <div id="indirect_attainment_graph_val">
					<table class="table table-bordered dataTables" id="indirect_attainment_table">
						<thead>
							<tr>
								<th><?php
								
								if($survey_for==6){   echo 'PEO';} if($survey_for==7){ echo 'PO';} if($survey_for==8){ echo 'CO';} ?>
								</th>
								<th>Questions</th>
								<!--<th>Indirect Attainment %</th>-->
								<!--<th>Levels</th>-->
							</tr>
						</thead>
						<tbody>
							<?php foreach($indirect_attainment as $indirect){ ?>
								<tr>
									<td><?php echo '<font color="#0088cc"><b>'.$indirect['reference'].':</b> '.$indirect['statement'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</br><b>Indirect Attainment: '.$indirect['ia_percentage'].' %</b></font>'; ?> </td>
									<td><?php echo $indirect['question']; ?> </td>
									<!--<td><?php echo $indirect['ia_percentage']; ?> </td>-->
									<!--<td><?php echo $indirect['attainment_level']; ?> </td>-->
									
								</tr>
							<?php } ?>
						</tbody>
					</table>
                </div>
				
            
		</div>
	</div>
</div>
<script src="<?php echo base_url('twitterbootstrap/js/jquery.dataTables.rowGrouping.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('twitterbootstrap/js/jquery_data_tables.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('twitterbootstrap/js/jquery_data_tables_natural.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('twitterbootstrap/js/jquery_data_tables_numeric-comma.js'); ?>" type="text/javascript"></script>
<script>
$(document).ready(function(){
	var in_crclm_id = $('#in_curriculum_id').val();
	var in_survey_id = $('#in_survey_id').val();
	var in_su_for = $('#in_su_for').val();
	var in_crs_id = $('#in_crs_id').val();
	var post_data = {'crclm_id':in_crclm_id,'survey_id':in_survey_id,'survey_for':in_su_for,'crs_id':in_crs_id}
	var actual_data = [];
        var actual_ticks = [];
        var i = 1;
        var graphTitle = $('#graphTitle').val();
        $.ajax({type: "POST",
            url: base_url + 'survey/surveys/get_indirect_attainment_data',
            data: post_data,
            dataType:'JSON',
			//async:false,
            success: function (survey_data) {
				console.log(survey_data);
				var temp='';
				for(var i=0; i<(survey_data.length);i++){
					
					if(survey_data[i].actual_id != temp){
						temp = survey_data[i].actual_id;
						actual_data.push(survey_data[i].ia_percentage);
						if(survey_data[i]['entity_id'] == 5){
						actual_ticks.push(survey_data[i].reference);
					}
					if(survey_data[i]['entity_id'] == 6){
						actual_ticks.push(survey_data[i].reference);
					}
					if(survey_data[i]['entity_id'] == 11){
						actual_ticks.push(survey_data[i].reference);
					}
					}
						
					
					
				}
				console.log(actual_data);
                $('#export').show();
                $("#indirect_attainment_actual_data").html('');
                //$("#graph_val").html(survey_data);

                //= ['PEO1', 'PEO2', 'PEO3', 'PEO4'];
                //var actual_data = [oneVal, twoVal, threeVal, fourVal];
                var bar_chart = $.jqplot('indirect_attainment_actual_data', [actual_data], {
                    //stackSeries: true,
                    seriesDefaults: {
                        renderer: $.jqplot.BarRenderer,
                        rendererOptions: {
                            barWidth: 40,
                            //varyBarColor: true,
                            barMargin: 20
                        },
                        pointLabels: {
                            show: true,
                            stackedValue: false,
                            location: 's'
                        }
                    },
                    highlighter: {
                        show: true,
                        tooltipLocation: 'n',
                        showMarker: true,
                        tooltipContentEditor: tooltipContentEditor
                    },
                    series: [
                        {label: 'Average'},
                        {label: 'PEO2'},
                        {label: 'PEO3'},
                        {label: 'PEO4'}
                    ],
                    axes: {
                        xaxis: {
                            pad: -1.05,
                            renderer: $.jqplot.CategoryAxisRenderer,
                            ticks: actual_ticks,
                            tickOptions: {
                                showGridline: false
                            }
                        },
                        yaxis: {
                            min: 0,
                            max: 100,
                            numberTicks: 11
                        }
                    },
                    legend: {
                        show: true,
                        location: 'ne',
                        placement: 'outside'
                    },
                    title: graphTitle,
                    legend: {
                        show: true,
                        placement: 'outsideGrid',
                    }
                });
				
				$('#indirect_attainment_table').dataTable().fnDestroy();
						
						$('#indirect_attainment_table').dataTable({
							
							"fnDrawCallback":function(){
								$('.group').parent().css({'background-color':'#ccc'}); 
								},
							
							"aaSorting": [[1, 'asc']],
							 "aoColumnDefs": [
												{ "sType": "natural", "aTargets": [ 0 ] },
												{ "sType": "numeric-comma", "aTargets": [ 0 ] },
												
											],
							"sPaginationType": "bootstrap"
								
							}).rowGrouping({ iGroupingColumnIndex:0,
									bHideGroupingColumn: true });
            }
        });
});
</script>