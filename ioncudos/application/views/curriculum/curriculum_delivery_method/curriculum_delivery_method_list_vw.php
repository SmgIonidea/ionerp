<?php
/**
* Description	:	List View for Delivery Method Module.
* Created		:	23-05-2015
* Author		:	Arihant Prasad
* Modification History:-
 *   Date                Modified By                			Description
 * 22-05-2015			Abhinay Angadi						Edit view functionalities
 * 11-03-2016			Shayista Mulla				Changed Warning message statement.
-------------------------------------------------------------------------------
*/
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
                <?php $this->load->view('includes/sidenav_2'); ?>
                <div class="span10">
                    <!-- Contents -->
                    <section id="contents">
                        <div class="bs-docs-example fixed-height"">
                            <!--content goes here-->	
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Curriculum Delivery Method List
                                </div>
                            </div>	
							
							<div class="row pull-right">
								<button type="button" disabled id="add_crclm_dm_button" class="add_crclm_dm_button btn btn-primary"><i class="icon-plus-sign icon-white"></i> Add </button>
                            </div>
							
							<div>
                                <label>
                                    Curriculum:<font color="red"> * </font>
                                    <select size="1" id="crclm_id" name="crclm_id" autofocus = "autofocus" aria-controls="example" onChange="dm_table_generate();">
                                        <option value="" selected> Select Curriculum </option>
                                        <?php foreach ($results['result_curriculum_list'] as $list_item) { ?>
                                            <option value="<?php echo $list_item['crclm_id']; ?>"> <?php echo $list_item['crclm_name']; ?> </option>
                                        <?php } ?>
                                    </select>
									
                                </label>
                            </div><br><br>
							
							<div id="example_wrapper" class="dataTables_wrapper" role="grid">
                                <table class="table table-bordered table-hover " id="example" aria-describedby="example_info">
                                    <thead >
                                        <tr class="gradeU even" role="row">
                                            <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending">Curriculum Delivery Methods</th>
											<th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending">Guidelines </th>
                                            <th class="header span1" role="columnheader" tabindex="0" aria-controls="example">Edit
											</th>
                                            <th class="header span1" role="columnheader" tabindex="0" aria-controls="example">Delete
											</th>
                                        </tr>
                                    </thead>
                                </table>
                                <br><br><br>
								
								<div class="pull-right">
									<button type="button" disabled class="add_crclm_dm_button btn btn-primary"><i class="icon-plus-sign icon-white"></i> Add </button>
								</div><br>	
							</div><br><br>
							
                            <!-- Modal do confirm before deleting -->
							<div id="myModaldelete" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModaldelete" data-backdrop="static" data-keyboard="true"><br>
                                <div class="container-fluid">
									<div class="navbar">
										<div class="navbar-inner-custom">
											Delete Confirmation
										</div>
									</div>
								</div>
								
								<div class="modal-body">
									<p> Are you sure you want to Delete? </p>
								</div>
                                    
								<div class="modal-footer">
									<button class="delete_dm btn btn-primary" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok</button>
									<button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
								</div>
                            </div>
							
							<!-- Warning modal - if delivery method is already assigned in any topic -->
							<div id="cant_delete" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="cant_delete" data-backdrop="static" data-keyboard="true">
                                <div class="modal-header">
                                    <div class="navbar-inner-custom">
                                        Warning 
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <p>You cannot delete this Delivery Method, as it has been assigned to a <?php echo $this->lang->line('entity_topic'); ?> . </p>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-danger" data-dismiss="modal"> <i class="icon-remove icon-white"></i> Close</button>
                                </div>
                            </div>
                            <!--Do not place contents below this line-->	
                            </section>	
                        </div>
                </div>
            </div>
            
            <?php $this->load->view('includes/footer'); ?> 
            <!---place js.php here -->
            <?php $this->load->view('includes/js'); ?>
			
			<script src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/curriculum_delivery_method.js'); ?>" type="text/javascript"> </script>
			
<!-- End of file curriculum_delivery_method_list_vw.php 
                        Location: .curriculum/curriculum_delivery_method/curriculum_delivery_method_list_vw.php -->
