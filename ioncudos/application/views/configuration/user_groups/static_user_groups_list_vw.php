<?php
/**
 * Description	:	Static user group allows the guest user to view the user list and their
					corresponding permissions.
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
    <?php $this->load->view('includes/head'); ?>
        <!--branding here-->
        <?php $this->load->view('includes/branding'); ?>

        <!-- Navbar here -->
        <?php $this->load->view('includes/navbar'); ?>
        <div class="container-fluid">
            <div class="row-fluid">
                <!--sidenav.php-->
                <?php $this->load->view('includes/static_sidenav_1'); ?>
                <div class="span10">
                    <!-- Contents -->
                    <section id="contents">
                        <div class="bs-docs-example">
                            <!--content goes here-->	
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    User Groups List
                                </div>
                            </div><br>
							
                            <div class="block">
                                <div id="example_wrapper" class="dataTables_wrapper" role="grid">
                                    <table class="table table-bordered table-hover" id="example" aria-describedby="example_info" style="font-size:14px;">
                                        <thead align = "center">
                                            <tr align = "center">
                                                <th rowspan="1" colspan="1" style="width:100px ; padding-right:10px;"> Name </th>
                                                <th class="sorting1" rowspan="1" colspan="1" style="width: 200px;"> Description(s) </th>
                                                <th class="sorting1" rowspan="1" colspan="1" style="width: 200px;"> Permission </th>	
                                            </tr>
                                        </thead>										
                                        <tbody>
                                            <?php foreach ($result_grid_details as $data_value): ?>
                                                <tr class="odd gradeX">
                                                    <td><?php echo $data_value['name']; ?> </td>
                                                    <td><?php echo $data_value['description']; ?> </td>
                                                    <td><a data-toggle="modal" href="#myModal_permission" class="get_id security" id="<?php echo $data_value['id']; ?>"> Permission </a></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div><br><br>
                        </div>

                        <!-- Modal to display the list of permissions allocated for a particular group -->
                        <div id="myModal_permission" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_permission" data-backdrop="static" data-keyboard="true">
                            <div class="">
                                <div class="modal-header">
                                    <div class="navbar">
                                        <div class="navbar-inner-custom">
                                            Permissions Assigned
                                        </div>
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
                </div><br>
            </div>
            <!---place footer.php here -->
            <?php $this->load->view('includes/footer'); ?> 
            <!---place js.php here -->
            <?php $this->load->view('includes/js'); ?>
	<script src="<?php echo base_url('twitterbootstrap/js/custom/configuration/user_group.js'); ?>" type="text/javascript"> </script>

<!-- End of file static_user_groups_list_vw.php 
                        Location: .configuration/user_groups/static_user_groups_list_vw.php -->