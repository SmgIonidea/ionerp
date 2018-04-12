<?php
/**
 * Description	:	To display the existing group, provisions to edit and delete the groups.
					Permission(s) allocated for each group can also be viewed
 * 					
 * Created		:	25-03-2013
 *
 * Author		:	
 * 		  
 * Modification History:
 *   Date                Modified By                			Description
 * 23-08-2013		   Arihant Prasad			File header, indentation, comments and variable 
												naming.
 *
  ------------------------------------------------------------------------------------------------- */
?>
<!--head here -->
    <?php
    $this->load->view('includes/head');
    ?>
    <!--branding here-->
        <?php
        $this->load->view('includes/branding');
        ?>
        <!-- Navbar here -->
        <?php
        $this->load->view('includes/navbar');
        ?> 
        <div class="container-fluid">
            <div class="row-fluid">
                <!--sidenav.php-->
                <?php $this->load->view('includes/sidenav_1'); ?>
                <div class="span10">
                    <!-- Contents -->
                    <section id="contents">
                        <div class="bs-docs-example">
                            <!--content goes here-->	
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    User Groups List
                                </div>
                            </div>
							<div class="block">
                                <div id="example_wrapper" class="dataTables_wrapper" role="grid">
                                    <table class="table table-bordered table-hover" id="example" aria-describedby="example_info" style="font-size:12px;">
                                        <thead align = "center">
                                            <tr align = "center">
                                                <th rowspan="1" colspan="1" style="width:100px; padding-right:10px;"> Name </th>
                                                <th class="sorting1" rowspan="1" colspan="1" style="width: 200px;"> Description(s) </th>
                                                <th class="sorting1" rowspan="1" colspan="1" style="width: 200px;"> Permission </th>
                                                <!--<th class="sorting1" rowspan="1" colspan="1" style="width: 20px;"> Edit </th>
                                                <th class="sorting1" rowspan="1" colspan="1" style="width: 20px;"> Delete </th> -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($result_grid_details as $data_value): ?>
                                                <tr class="odd gradeX">
                                                    <td><?php echo $data_value['name']; ?> </td>
                                                    <td><?php echo $data_value['description']; ?> </td>
                                                    <td><a data-toggle="modal" href="#myModal_permission" class="get_id security" id="<?php echo $data_value['id']; ?>"> Permission </a></td>
													<!--
													<?php //if ($data_value['id']!= 1 && $data_value['id']!=2) { ?>
                                                    <td><a href="<?php// echo base_url('configuration/user_groups/user_groups_edit') . '/' . $data_value['id']; ?>" ><i class="icon-pencil"></i></a>
                                                    </td>
                                                    
													 <?php// } else { ?>
													 <td><a data-toggle="modal" href="#cant_edit" ><i class="icon-pencil"></i></a>
                                                    </td>-->
													 <?php //} ?>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table><br><br>

                                  <br><br>
                                </div>
                            </div>
                        </div>

                        <!-- Modal to display the list of permissions allocated for a particular group -->
                        <div id="myModal_permission" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_permission" data-backdrop="static" data-keyboard="true">
							<div class="modal-header">
								<div class="navbar">
									<div class="navbar-inner-custom">
										Permissions Assigned 
									</div>
								</div>	
							</div>
                            <div class="modal-body">
                                <div id="permission">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close </button>
                            </div>
                        </div>

                        <!-- Modal to display the confirmation before deleting a particular group -->
                        <div id="myModal_delete" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_delete" data-backdrop="static" data-keyboard="true">
							<div class="modal-header">
								<div class="navbar">
									<div class="navbar-inner-custom">
										Delete Confirmation 
									</div>
								</div>
							</div>
                            <div class="modal-body">
                                <p> Are you sure you want to delete the User Group? </p>
                            </div>
                            <div class="modal-footer">
                                <button class="usergroup_delete btn btn-primary " data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok </button>
                                <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel </button>
                            </div>
                        </div>
						
						<div id="cant_edit" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: 	none;">
                                <div class="modal-header">
                                    <div class="navbar-inner-custom">
                                        Warning 
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <p>You cannot modify the Permissions for this Group</p>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-danger" data-dismiss="modal"> <i class="icon-remove icon-white"></i> Close</button>
                                </div>
                            </div>
                </div>
                <div class="clear">
                </div>
            </div>
            <!---place footer.php here -->
            <?php $this->load->view('includes/footer'); ?> 
            <!---place js.php here -->
            <?php $this->load->view('includes/js'); ?>
    <script src="<?php echo base_url('twitterbootstrap/js/custom/configuration/user_group.js'); ?>" type="text/javascript"></script>

<!-- End of file user_groups_list_vw.php 
            Location: .configuration/user_groups/user_groups_list_vw.php -->