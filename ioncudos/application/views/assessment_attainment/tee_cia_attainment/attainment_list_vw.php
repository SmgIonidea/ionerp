 <?php
/**
* Description	:	Import Assessment Marks List View
* Created		:	07-10-2014. 
* Author 		:   Waseemraj
* Modification History:
* Date				Modified By				Description
* 10-10-2014	   Arihant Prasad		integration, variable naming convention,
										indentation
-------------------------------------------------------------------------------------------------
*/
?>
				<?php $cloneCntr = 0;?>
                    <table id="cia_tee_table-view" class="table table-bordered table-hover">
						<thead>
							<tr>
								<th>Sl No.</th>
								<th>Course Code</th>
								<th>Course Title</th>
								<th>Actual <?php echo $this->lang->line('entity_see'); ?> <br> Attainment %</th>
								<th>After Weight <br> <?php echo $this->lang->line('entity_see'); ?> Attainment %</th>
								<th>Actual <?php echo $this->lang->line('entity_cie'); ?> <br> Attainment %</th>
								<th>After Weight <br> <?php echo $this->lang->line('entity_cie'); ?> Attainment %</th>
								<th>Overall <br> Attainment %</th>
							</tr>
						</thead>
						<tbody>
						<tr>
							<?php foreach ($course_attainment as $attainment) { $cloneCntr++; ?>
									<td align="left">
										<?php echo $cloneCntr.".";?>
									</td>
									<td>
										<?php echo $attainment['crs_code']; ?>
									</td>
									<td align="left">
										<?php echo $attainment['crs_title']; ?>
									</td>
									<td> <p class="pull-right">
										<?php echo $attainment['ActualTEEAttaintment']; ?> %
									</td>
									<td> <p class="pull-right">
										<?php echo $attainment['TEEAttaintment']; ?> %
									</td>
									<td> <p class="pull-right">
										<?php echo $attainment['ActualCIAAttaintment']; ?> %
									</td>
									<td> 
										<a data-toggle="modal" href="#myModal_cia_details" class="get_cia_details" id=<?php echo $attainment['crs_id']; ?>>  CIA details</a>
										<p class="pull-right">
										<?php echo $attainment['CIAAttaintment']; ?> % 
									</td>
									<td> <p class="pull-right">
										<?php echo $attainment['OverAllAttaintment']; ?> %
									</td>
								</tr>
							<?php } ?>
                    </table><br>
					<!-- Modal to display send for myModal_cia_details message -->
								<div id="myModal_cia_details" class="modal hide fade" style="width: 60%;right: 40%;left: 40%" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_cia_details" data-backdrop="static" data-keyboard="true">
									<div class="modal-header">
										<div class="navbar">
											<div class="navbar-inner-custom">
												<?php echo $this->lang->line('entity_cie'); ?> Occasions List
											</div>
										</div>
									</div>
									<div class="modal-body" id="get_cia_details_list">
									</div>				
									<div class="modal-footer">
										<button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Close </button>	
									</div>
								</div>
					
