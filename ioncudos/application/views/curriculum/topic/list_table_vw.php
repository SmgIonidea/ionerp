<?php
/*****************************************************************************************


page refresh -> enable/disable



* Description	:	Program grid provides the entire list of Programs along with their
					respective department, acronym, No. of credits and mode.
					Edit and Toggle buttons are also provided to edit the existing Program
					and to toggle the status between Enable/Disable respectively.
					
* Created		:	March 30th, 2013

* Author		:	Arihant Prasad D 
		  
* Modification History:
* Date                Modified By                Description

*******************************************************************************************/
?>
	<!--head here -->
	<!--head here -->
	
							
			<table class="table table-bordered table-hover " id="example" aria-describedby="example_info">
					<thead align = "center">
											<tr role="row">
												<th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" ><?php echo $this->lang->line('entity_topic'); ?>(s)</th>
												<th class="header" role="columnheader" tabindex="0" aria-controls="example" >Edit</th>
												<th class="header" role="columnheader" tabindex="0" aria-controls="example" >Delete</th>
												
												
											</tr>
										</thead>
							
										<tbody id="example1" role="alert" aria-live="polite" aria-relevant="all">
										
									<?php if(isset($topic_details)) 
									{?>   
									
											<?php foreach($topic_details as $data_value):{ ?>
											
												<tr class="gradeU even">
													<td class="sorting_1 table-left-align"> <?php echo $data_value['topic_title']; ?></td>
													<td class="sorting_1 table-left-align"><center><i class="icon-pencil"></i> </center></td>
													<td class="sorting_1 table-left-align"><center><a href="#"><i class="icon-remove delbutton"></i></a> </center></td>
												</tr>
											<?php }
											endforeach;
									} ?>
											
										</tbody>
									</table>
								
								