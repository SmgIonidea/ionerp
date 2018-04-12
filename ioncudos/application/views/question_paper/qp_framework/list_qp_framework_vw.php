<?php
/**
 * Description	:	QP Framework List View Page.
 * Created		:	17-09-2014
 * Author		:	Mritunjay. B S
 * Modification History:
 *  
 *
  ------------------------------------------------------------------------------------------------- */
?>

<!--head here -->
    <?php $this->load->view('includes/head'); ?>
        <!--branding here-->
        <?php $this->load->view('includes/branding'); ?>

        <!-- Navbar here -->
        <?php $this->load->view('includes/navbar'); ?>
        <div class="container-fluid">
            <div class="row-fluid">
                <!--sidenav.php-->
                <?php $this->load->view('includes/sidenav_4'); ?>
                <div class="span10">
                    <!-- Contents -->
                    <section id="contents">
                        <div class="bs-docs-example">
                            <!--content goes here-->	
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    <?php echo $this->lang->line('entity_see_full'); ?> (<?php echo $this->lang->line('entity_see'); ?>) List of Frameworks
                                </div>
                            </div>
                            <div class="row">   
                                <a href="<?php echo base_url('question_paper/manage_qp_framework/add_qp_framework'); ?>" align="right">
                                    <button type="button" class="btn btn-primary pull-right"><i class="icon-plus-sign icon-white"> </i> Add </button>
                                </a>
                            </div>
                            <br>
                            <div id="example_wrapper" class="dataTables_wrapper container-fluid" role="grid">
                            <table class="table table-bordered table-hover" id="example" aria-describedby="example_info">
							<thead>
								<tr role="row">
									<th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" ><?php echo $this->lang->line('entity_see'); ?> Framework Title</th>
									<th class="header" role="columnheader" tabindex="0" aria-controls="example" >No. of Parts(Units)</th>
									<th class="header" role="columnheader" tabindex="0" aria-controls="example" >Grand Total Marks</th>
									<th class="header" role="columnheader" tabindex="0" aria-controls="example" >Max Marks to Attempt</th>
									<th class="header" role="columnheader" tabindex="0" aria-controls="example" >Notes</th>
									<th class="header" role="columnheader" tabindex="0" aria-controls="example" >Important Instructions</th>
									<th class="header span1">Edit</th>
									<th class="header span1">Delete</th>
								</tr>
							</thead>
							<tbody role="alert" aria-live="polite" aria-relevant="all">
							
							<?php if(!empty($records)){ 
							foreach ($records as $records): ?>
								<tr>
									 <td class="sorting_1 table-left-align"><?php echo $records['qpf_title']; ?></td>  
									<td class="sorting_1 table-left-align"><?php echo $records['qpf_num_units'];?></td>
									<td class="sorting_1 table-left-align"><?php echo $records['qpf_gt_marks'];?></td>
									<td class="sorting_1 table-left-align"><?php echo $records['qpf_max_marks'];?></td>
									<td class="sorting_1 table-left-align"><?php echo $records['qpf_notes'];?></td>
									<td class="sorting_1 table-left-align"><?php echo $records['qpf_instructions'];?></td>
									<td> <center>
										<a class="" href="<?php echo base_url('question_paper/manage_qp_framework/qp_framework_edit') . '/' . $records['qpf_id']; ?>"><i class="icon-pencil icon-black"> </i></a></center></td>
										
									<td>
										<div id="hint">
											<center>
												<a href = "" id="<?php echo $records['qpf_id']; ?>" class=" get_id icon-remove Delete_framework" data-toggle="modal" data-original-title="Delete" rel="tooltip" 
												title="Delete" value="<?php echo $records['qpf_id']; ?>">
												</a>
											</center>
										</div>
									</td>
								</tr>
							<?php endforeach; 
							}?>	
							</tbody>
							</table>
							
							</div>
							<br>
							<!-- Modal -->
                            <div id="myModaldelete" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                <div class="modal-header">
                                    <div class="navbar-inner-custom">
                                        Delete Confirmation 
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to Delete?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="delete_qp btn btn-primary "  data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok</button>
                                        <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                                    </div>
                                </div>		
                            </div>
							
							<div id="myModalCannotdelete" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                <div class="modal-header">
                                    <div class="navbar-inner-custom">
                                        Delete Failure
                                    </div>
                                    <div class="modal-body">
                                        <p>You Cannot Delete this Framework,as there are Question Papers designed under this Framework</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-primary "  data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok</button>
                                    </div>
                                </div>		
                            </div>
							<!-- Modal ends here -->
								<div class="row">   
                                <a href="<?php echo base_url('question_paper/manage_qp_framework/add_qp_framework'); ?>" align="right">
                                    <button type="button" class="btn btn-primary pull-right"><i class="icon-plus-sign icon-white"> </i> Add </button>
                                </a>
                            </div>
							<!-- Modal popup for Disable confirmation -->
                            <div id="cannot_disable_dialog" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="cannot_disable_dialog" data-backdrop="static" data-keyboard="true">
								<div class="modal-header">
									<div class="navbar">
										<div class="navbar-inner-custom">
											Disable Failure 
										</div>
									</div>
								</div>
								<div class="modal-body">
									<p>You Cannot Disable this Program Type, as there are Programs currently being running under this Program Type</p>
								</div>
								<div class="modal-footer">
									<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" ><i class="icon-ok icon-white"></i> Ok</button>
								</div>
							</div><br>
                            <!--Do not place contents below this line-->
                    </section>	
                        </div>
                </div>
            </div>
        </div>
        <!---place footer.php here -->
        <?php $this->load->view('includes/footer'); ?> 
        <?php $this->load->view('includes/js'); ?>
        <!---place js.php here -->
    <script src="<?php echo base_url('twitterbootstrap/js/custom/question_paper/qp_framework.js'); ?>" type="text/javascript"> </script>
