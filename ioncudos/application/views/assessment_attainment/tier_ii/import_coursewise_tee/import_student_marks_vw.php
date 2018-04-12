<?php
/**
* Description	:	Tier II - Import Course wise Data List View
* Created		:	30-12-2015
* Author 		:   Arihant Prasad
* Modification History:
* Date				Modified By				Description
* 
-------------------------------------------------------------------------------------------------
*/
?>

<?php $this->load->view('includes/head'); ?>
<!--branding here-->
<?php $this->load->view('includes/branding'); ?>

<!-- Navbar here -->
<?php $this->load->view('includes/navbar'); ?>

<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <!-- Contents -->
            <section id="contents">
            	
                <div class="bs-docs-example">
                    <!--content goes here-->	
                    <div class="navbar">
                        <div class="navbar-inner-custom">
							<?php if($ref_id == "ref_12") { ?>
								<?php echo $this->lang->line('entity_cie_full'); ?> Tier-II Assessment Data Import Details
							<?php } else { ?>
								<?php echo $this->lang->line('entity_see_full'); ?> Tier-II Assessment Data Import Details
							<?php } ?>
                        </div>
                    </div>

					<table>
						<tbody>
							<tr>
								<td>
									<label class="cursor_default">
										Department: <font color="blue"><?php echo $department; ?></font>
										<?php echo str_repeat('&nbsp;', 13); ?>
									</label>
								</td>
								<td>
									<label class="cursor_default">
										Program: <font color="blue"><?php echo $program; ?></font>
										<?php echo str_repeat('&nbsp;', 13); ?>
									</label>
								</td>
								<td>
									<label class="cursor_default">
										Curriculum: <font color="blue"><?php echo $curriculum; ?></font>
										<?php echo str_repeat('&nbsp;', 13); ?>
									</label>
								</td>
								<td>
									<label class="cursor_default">
										Term: <font color="blue"><?php echo $term; ?></font>
									</label>
								</td>
							</tr>
					
							<tr>
								<td>
								   <label class="cursor_default">
										Course: <font color="blue"><?php echo $course; ?></font>
									</label>  
								</td>
								<?php if ($ref_id == "ref_12") { ?>
									<td>
										 <label class="cursor_default">
											 Section: <font color="blue"><?php echo $section_name; ?></font>
										</label>
									</td>
								<?php } ?>
								<td>
									<label class="cursor_default">
										File Name: <font color="blue"><?php echo $file_name.'.csv'; ?></font>
									</label>
								</td>
							</tr>
						</tbody>
					</table>
					
					<?php
                    $count = 0;
                    if (!empty($course_marks)) :
                        $count = 1; ?>
                        <table class="table table-bordered table-hover" id="table_imported_data">
                            <thead>
                                <tr>
                                    <?php $indx = 0; $js_data = array();
                                    foreach ($course_marks[0] as $header => $value) : ?>
                                    <th>
                                        <center>                                            
                                            <?php
                                            if($header == 'student_usn') { //skip column defined as identifier in inline table edit plugin
                                                $indx++;
                                            } else if($header == 'student_name') {
                                                $js_data[] = array($indx++, $header);
                                            } else {
                                                $temp_header = substr($header, 0, strpos($header,'('));    
                                                if($temp_header == 'total_marks'){
                                                    $js_data[] = array($indx++, $temp_header);
                                                } else {
                                                    $js_data[] = array($indx++, $temp_header, 'id');
                                                } 
                                            }                                            
                                            echo strtoupper(str_replace("_", " ",str_replace("Q_No_", "", $header)));
                                            ?>
                                        </center>
                                    </th>
                                    <?php endforeach;
                                    $table_columns = json_encode($js_data);
                                    ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($course_marks as $marks) : ?>
                                    <tr>
                                        <?php
                                        foreach ($marks as $key => $value) : 
                                            $val = '-';
                                            $ass_id = '';
                                            
                                            if($value) {
                                                $exp_data = explode('_', $value);
                                                if(isset($exp_data[1])) {
                                                    $ass_id = $exp_data[0];
                                                    $val = ($exp_data[1])?$exp_data[1]:'-';
                                                } else {
                                                    $val = ($exp_data[0])?$exp_data[0]:'-';
                                                }                                                
                                            }                                            
                                            echo '<td style="text-align:center;" data-id="'.$ass_id.'"><center>' .$val. '</center></td>';                                        
                                        endforeach;
                                        ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>                        
                        </table>
                    <?php endif; ?>
                    <br>
					
					<?php if($count == 0) { ?>
						<h4> Import Student Data </h4>
					<?php } ?>
					
					<input type="hidden" id="dept_id" name="dept_id" value="<?php echo $dept_id; ?>">
					<input type="hidden" id="prog_id" name="prog_id" value="<?php echo $prog_id; ?>">
					<input type="hidden" id="crclm_id" name="crclm_id" value="<?php echo $crclm_id; ?>">
					<input type="hidden" id="term_id" name="term_id" value="<?php echo $term_id; ?>">
					<input type="hidden" id="crs_id" name="crs_id" value="<?php echo $crs_id; ?>">
					<input type="hidden" id="qpd_id" name="qpd_id" value="<?php echo $qpd_id; ?>">
					<input type="hidden" id="ref_id" name="ref_id" value="<?php echo $ref_id; ?>">
					
					<?php if($count == 1) { ?>
						<!--Discard-->
						<button id="discard" value="discard" class="btn btn-danger pull-right" style="margin-right: 2px;" onclick="drop_main_table_confirm();"><i class="icon-remove icon-white"></i> Discard Data </button>
						
						<!--dataAnalysis-->
						<button id="dataAnalysis" value="dataAnalysis" class="btn btn-success pull-right" style="margin-right: 2px;" onclick="dataAnalysis();"><i class="icon-file icon-white"></i> Data Analysis </button>
					<?php } ?>

					<!--Re-import students marks for TEE-->
					<a type="button" id="import_module" href="<?php echo base_url('tier_ii/import_coursewise_tee/temp_import_template/'.$dept_id.'/'.$prog_id.'/'.$crclm_id.'/'.$term_id.'/'.$crs_id.'/'.$qpd_id); ?>" class="btn btn-success pull-right" style="margin-right: 2px;"><i class="icon-upload icon-white"></i> Re-import </a>
				
					<!--close and return to list page - TEE-->
					<a href="<?php echo base_url('tier_ii/import_coursewise_tee'); ?>" class="btn btn-primary pull-right" style="margin-right: 2px;"><i class="icon-ok icon-white"></i><span></span> Return to List Page </a><br><br>
					
                    <!-- Modal to display the discard confirmation  -->
					<div id="drop_main_table_confirmation" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="drop_main_table_confirmation" data-backdrop="static" data-keyboard="false"><br>
						<div class="container-fluid">
							<div class="navbar">
								<div class="navbar-inner-custom">
									Discard confirmation
								</div>
							</div>
						</div>

						<div class="modal-body">
							<p> Are you sure you want to discard and return to list page? </p>
						</div>

						<div class="modal-footer">
							<button class="btn btn-primary" data-dismiss="modal" onclick="drop_main_table();"><i class="icon-ok icon-white"></i> Ok </button>
							<button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel </button> 
						</div>
					</div>
					
					<!-- modal to display data analysis -->
					<div id="myModal_dataAnalysis" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display:none; height:80%; width:80%; left:30%; right:30%; bottom:10%;" data-controls-modal="myModal_dataAnalysis" data-backdrop="static" data-keyboard="true"></br>
						<div class="container-fluid">
							<div class="navbar">
								<div class="navbar-inner-custom">
									Imported Student Data Analysis
								</div>
							</div>
						</div>
						
						<center><font style="font-size:18px;" color="blue">Question Level Analysis</font></center>
						
						<div class="modal-body" id="dataAnalysis_modal" style="padding-right:20px;">
							<div id="loading" class="ui-widget-overlay ui-front">
                    			<img src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" id="ajax_loading" class='ajax_loading' />
                			</div>
						</div>

						<div class="modal-footer">
							<button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close </button> 
						</div>
					</div>
					<!--Do not place contents below this line-->
            </section>	
        </div>
    </div>
</div>
<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<?php $this->load->view('includes/js'); ?>
       
	<script type="text/javascript" src="<?php echo base_url('/twitterbootstrap/js/upclick.js'); ?>"></script>
	<script src="<?php echo base_url('twitterbootstrap/js/custom/assessment_attainment/tier_ii/import_coursewise_tee/import_coursewise_tee.js'); ?>" type="text/javascript"></script>
	<script type="text/javascript" src="<?php echo base_url('/twitterbootstrap/js/inline_table_edit/jquery.tabledit.js'); ?>"></script>
	<script type="text/javascript">
		function my_validation(td){
			let table;
			let flag=true;
			let $error_msg='';
			
			$(td).each(function() {
				var $input = $(this).find('.inline-input-val').val();
				var $td_index=$(this).index();
				
				if(typeof table==='undefined'){
					$table=$(this).closest('table');
				}
				
				var $header=$table.find('th').eq($td_index).text();
				var $total_mark=$header.substring($header.indexOf('(')+1, $header.indexOf(')'));            
				var $question=$header.substring(0,$header.indexOf('('));
				
				//validate total mark
				if($total_mark && parseFloat($input)>parseFloat($total_mark)){                
					$error_msg+="<p>Invalid input for \""+$question+"\" ,It should not be greater than "+$total_mark+"</p>";
					flag=false;                
				}           
			 });
			 
			 if(flag){                                
				return true;
			 }else{
				console.log('error msg',$error_msg);
				$('#modal_header').text('Error');
				$('#err_msg').html($error_msg);
				$('#inline_msg_modal').modal('show');
			 }
		
		}
		
		$(function ($) {	
			$('#table_imported_data').Tabledit({
				url:'<?=base_url('assessment_attainment/import_assessment_data/update_student_marks')?>',
				hideIdentifier:false,
				restoreButton:false,
				deleteButton:false,
				Button:false,
				elementValidator:my_validation,
				columns: {
					identifier: [1, "student_usn"],
					editable: <?=$table_columns?>,
				},
				buttons: {
					edit: {
					  class: '',
					  html: '<span class="icon-pencil"></span>',
					  action: 'edit'
					},
					delete: {
					  class: '',
					  html: '<span class="icon-remove"></span>',
					  action: 'delete'
					}
				},
				onSuccess:function(data, textStatus, jqXHR){                
					$('#modal_header').text(data.key);
					$('#err_msg').text(data.message);
					$('#inline_msg_modal').modal('show');
				}
				
			});
		});
	</script>