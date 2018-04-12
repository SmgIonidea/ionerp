<?php
/**
* Description	:	Model View of Question Paper
* Created		:	21-10-2014. 
* Modification History:
* Date				Modified By				Description
* 21-10-2014		Jyoti				Question Paper View with graph display and PDF creation
--------------------------------------------------------------------------------------------------------
*/
?>

<!--head here -->
    <?php
    $this->load->view('includes/head');
    ?>
    <!--branding here-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.nailthumb.1.1.css'); ?>" media="screen" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.nailthumb.1.1.min.css'); ?>" media="screen" />
       
        <!-- Navbar here -->
       
        <div class="container-fluid">
            <div class="row-fluid">
                <!--sidenav.php-->
                <?php //$this->load->view('includes/sidenav_1'); ?>
                <div class="span12">
                    <!-- Contents
    ================================================== -->
                    <section id="contents">
                                      
							<?php if($paper) { ?>
								<div class="bs-docs-example fixed-height">       
								<form class="form-horizontal" method="POST" target="_blank" id="export_model_qp_pdf" name="export_model_qp_pdf" action="<?php echo base_url('question_paper/manage_model_qp/export_model_qp_pdf'); ?>">
								<div id="qp_content_pdf" >
									<table border=0 style="width:100%;">
										<tr>									
											<td style="text-align:center" colspan="3"><b>Question Paper Title:</b>
												<?php echo $paper[0]['qpd_title']; ?>
											</td>
										</tr>
										<tr>
											<td style="text-align:left"><b>Total Duration (H:M):</b><?php echo $paper[0]['qpd_timing'];?></td>
											<td style="text-align:center"><b>Course  :</b><?php echo $paper[0]['crs_title'] ?> (<?php echo $paper[0]['crs_code'] ?>)</td>	
											<td style="text-align:right"><b>Maximum Marks :</b><?php echo $paper[0]['qpd_max_marks'];?></td>
										</tr>
										<tr>	
											<td style="text-align:center" colspan="3"><b>Note :</b><?php echo $paper[0]['qpd_notes'];?></td>
										</tr>
									</table>
							
									
								
										<table border=0 class="table table-bordered dataTable" id="qp_table_data">
											
												<thead>
													<tr>
														<th>Unit Name</th>
														<th><center><b>Question No.</b></center> </th>
														<th><center><b>Question</b></center> </th>
														<?php foreach($entity_list as $qp_config){ 
														if($qp_config['entity_id'] == 11 ){?>
														<th><center><b><?php echo $this->lang->line('entity_clo'); ?> </b></center></th>
														<?php } ?>
														<?php
														if($qp_config['entity_id'] == 6){?>
														<th><center><b><?php echo $this->lang->line('so'); ?></b></center></th>
														<?php } ?>
														<?php
														if($qp_config['entity_id'] == 10){?>
														<th><center><b><?php echo $this->lang->line('entity_topic'); ?></b></center></th>
														<?php } ?>
														<?php  
														if($qp_config['entity_id'] == 23){?>
														<th><center><b>Level</b></center> </th>
														<?php } ?>
														<?php 
														if($qp_config['entity_id'] == 22){?>
														<th><center><b>PI Code</b></center></th>
														<?php } 
														
														if($qp_config['entity_id'] == 22){?>
														<th><center><b>PI Code</b></center></th>
														<?php }	
														
														if($qp_config['entity_id'] == 29){?>
														<th><center><b>Question Type</b></center></th>
														<?php }
														}?>
														<th><center><b>Marks</b></center></th>
													</tr>
												</thead>
												<tbody>
													<?php echo $qp_table; ?>
												</tbody>
											
												
											
											</table>
											
										
								
							</div><!--qp_content_pdf closed-->
							<?php if($paper[0]['qpd_gt_marks'] != '') { ?>
								<div class="span12">
								<div class="control-group">
								<div class=" controls">
								<div class="form-inline pull-right">
								<div id="total"><label for="qp_max_marks"><b>Grand Total Marks</b></label>
								<?php echo $paper[0]['qpd_gt_marks']; ?></div>
								</div>		
								</div>		
								</div>		
								</div>
							<?php }  ?>
							<?php foreach($qp_entity as $config){?>
							<?php if($config['entity_id']==23) {?>
							<?php echo form_input($BloomsLevel); ?>
							<?php echo form_input($PlannedPercentageDistribution); ?>
							<?php echo form_input($ActualPercentageDistribution); ?>					
							<?php echo form_input($BloomsLevelDescription); ?>					
							
							
							<?php echo form_input($blooms_level_marks_dist); ?>
							<?php echo form_input($total_marks_marks_dist); ?>
							<?php echo form_input($percentage_distribution_marks_dist); ?>		
							<?php echo form_input($bloom_level_marks_description); }?>		
							
							<?php if($config['entity_id']==11) {?>
							<?php echo form_input($clo_code); ?>
							<?php echo form_input($clo_total_marks_dist); ?>
							<?php echo form_input($clo_percentage_dist); ?>		
							<?php echo form_input($clo_statement_dist);} ?>		
							
							<?php //if($config['entity_id']==10) {?>
							<?php // echo form_input($topic_title); ?>
							<?php //echo form_input($topic_marks_dist); ?>
							<?php //echo form_input($topic_percentage_dist);} ?>	
							<?php }?>
						<br/><br/><br/>
					<div id="chart_data">
					<?php foreach($qp_entity as $config){?>
					<?php if($config['entity_id']==23) {?>
				
								
						<div class="navbar">
							<div class="navbar-inner-custom">
								Bloom's Level Marks Distribution 
							</div>
						</div>
						<div class="row-fluid">
							<div class="span12">
							<div class="span6">
							<div id="chart2" class="jqplot-target" >
							</div>
								
							</div>
							<div class="span6" style="overflow:auto;">
								<br>
								<div id="bloomslevelplannedmarksdistribution_div" >
									<table id="bloomslevelplannedmarksdistribution" border=1 class="table table-bordered">
									<thead></thead>
									<tbody></tbody>
									</table>
								</div>
							</div>
							</div>
							<div class="span12">
							<div id="bloomslevelplannedmarksdistribution_note"></div>
							</div>
						</div>
						<br/>
						<?php }if($config['entity_id']==11) {?>
						<div class="navbar">
							<div class="navbar-inner-custom">
								Course Outcome Marks Distribution 
							</div>								
						</div>
						<div class="row-fluid">
							<div class="span12">
							<div class="span6">
							<div id="chart3" class="jqplot-target" >
							</div>
								
							</div>
							<div class="span6" style="overflow:auto;">
								<br>
								<div id="coplannedcoveragesdistribution_div">
									<table id="coplannedcoveragesdistribution" border=1 class="table table-bordered">
									<thead></thead>
									<tbody></tbody>
									</table>
								</div>
							</div>
							</div>
							<div class="span12">
								<div id="coplannedcoveragesdistribution_note"></div>
							</div>
						</div>
						<br>
						
											<?php }if($config['entity_id']==10) {?>
						<!-- <div class="navbar">
							<div class="navbar-inner-custom">
								<?php echo $this->lang->line('entity_topic'); ?> Marks Distribution 
							</div>								
						</div>
						<div class="row-fluid">
							<div class="span12">
							<div class="span6">
							<div id="chart4" class="jqplot-target" >
							</div>
								
							</div>
							<div class="span6" style="overflow:auto;">
								<br>
								<div id="topicplannedcoveragesdistribution_div">
									<table id="topicplannedcoveragesdistribution" border=1 class="table table-bordered">
									<thead></thead>
									<tbody></tbody>
									</table>
								</div>
							</div>
							</div>
							<div class="span12">
								<div id="topicplannedcoveragesdistribution_note"></div>
							</div>
						</div>-->
						
						<?php } }?>
					</div>
	
				
						<input type="hidden" id="qp_hidden" name="qp_hidden" />
						<input type="hidden" id="total_val_hidden" name="total_val_hidden" />
						<div id="chart1_img" style="display:none;"></div>
						<div id="chart2_img" style="display:none;"></div>
						<div id="chart3_img" style="display:none;"></div>
						<div id="chart4_img" style="display:none;"></div>
						<input type="hidden" id="chart1_img_hidden" name="chart1_img_hidden" />
						<input type="hidden" id="chart2_img_hidden" name="chart2_img_hidden" />
						<input type="hidden" id="chart3_img_hidden" name="chart3_img_hidden" />
						<input type="hidden" id="chart4_img_hidden" name="chart4_img_hidden" />
						
					</form>
					</div><!--fixed-height-->
					<?php 
					}  else { ?>
						<div class="bs-docs-example custom_question_paper_undefined_disp"> 
							<h3 class="custom_question_paper_undefined"><center>Question Paper has not been defined for this course.</center></h3>
							<input type="hidden" id="no_data" name="no_data" value="0" />
						</div>
					<?php } ?>
					<!--</div><!--qp_content_pdf closed-->
					
					
                    </div>
					<!--Do not place contents below this line-->
				</section>
			</div>  
		</div>
		
		</div>
        <!---place footer.php here -->
       
        <!---place js.php here -->
        <?php $this->load->view('includes/js'); ?>
		
	
	<script type="text/javascript" src = "<?php echo base_url('twitterbootstrap/js/custom/curriculum/SimpleAjaxUploader.min.js'); ?> "> </script>
	<script type="text/javascript" src = "<?php echo base_url('twitterbootstrap/js/custom/curriculum/jquery.nailthumb.1.1.js'); ?> "> </script>
	
