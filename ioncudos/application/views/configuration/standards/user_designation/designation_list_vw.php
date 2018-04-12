<?php
/**
* Description	:	List View for User Designation Module.
* Created		:	03-04-2013. 
* Modification History:
* Date				Modified By				Description
* 22-08-2013		Abhinay B.Angadi        Added file headers, indentations.
* 29-08-2013		Abhinay B.Angadi		Variable naming, Function naming &
*											Code cleaning.
* 26-03-2014		Jevi V. G				Added description field for designation
--------------------------------------------------------------------------------
*/
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
                    <!-- Contents
    ================================================== -->
                    <section id="contents">
                        <div class="bs-docs-example">
                            <!--content goes here-->	
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Designation List
                                </div>
                            </div>	
                            <div class="row pull-right">   
                                <a href="<?php echo base_url('configuration/user_designation/designation_add_record'); ?>" align="right">
                                    <button type="button" class="btn btn-primary"><i class="icon-plus-sign icon-white"> </i> Add</button>
                                </a> </div>
                            <br><br>
                            <div id="example_wrapper" class="dataTables_wrapper" role="grid">
                                <table class="table table-bordered table-hover " id="example" aria-describedby="example_info">
                                    <thead align = "center">
                                        <tr class="gradeU even" role="row">
                                            <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >Designation </th>
                                            <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >Description </th>
                                            <th class="header" rowspan="1" colspan="1" style="width: 60px;" role="columnheader" tabindex="0" aria-controls="example" align="center" ><center></center>Edit</th>
											<th class="header" rowspan="1" colspan="1" style="width: 60px;" role="columnheader" tabindex="0" aria-controls="example" align="center" ><center></center>Delete</th>
                                    </tr>
                                    </thead>
                                    <tbody role="alert" aria-live="polite" aria-relevant="all">
                                        <?php foreach ($records as $records): ?>
                                            <tr>
                                                <td class="sorting_1 table-left-align"><?php echo $records['designation_name']; ?></td>  
                                                <td class="sorting_1 table-left-align"><?php echo $records['designation_description']; ?></td>  
                                                <td> <a class="" href="<?php echo base_url('configuration/user_designation/designation_edit_record') . '/' . $records['designation_id']; ?>">
                                                        <i class="icon-pencil icon-black"> </i></a></td>
												
												<?php
                                                if ($records['is_designation'] == 0) {
                                                ?>
                                                <td>
                                                    <div id="hint">
                                                        <center>
																<a href = "#myModaldelete" id="<?php echo $records['designation_id']; ?>" class=" get_id icon-remove" data-toggle="modal" data-original-title="Delete" rel="tooltip" 
                                                                   title="Delete" 
                                                                   value="<?php echo $records['designation_id']; ?>"
                                                                   onclick="delete();" >
																</a>
                                                        </center>
                                                    </div>
                                                </td>
												<?php
													} else {
                                                ?>
												<td>
                                                    <div id="hint">
                                                        <center>
																<a href = "#cant_delete" class=" get_id icon-remove" data-toggle="modal" data-original-title="Delete" rel="tooltip" 
                                                                   title="Delete" ></a>
                                                        </center>
                                                    </div>
                                                </td>
												<?php } ?> 
                                            </tr>
                                        <?php endforeach; ?>	
                                    </tbody>
                                </table><br><br><br>
                                <div class="row pull-right">   
                                    <a href="<?php echo base_url('configuration/user_designation/designation_add_record'); ?>" align="right">
                                        <button type="button" class="btn btn-primary"><i class="icon-plus-sign icon-white"> </i> Add</button>
                                    </a> </div><br>
								</div>
                            <br>
                        </div>
                        <!-- Modal -->
                        <div id="myModaldelete" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true">
                            <div class="modal-header">
                                <div class="navbar-inner-custom">
                                    Delete Confirmation
                                </div>
			    </div>
                                <div class="modal-body">
                                    <p>Are you sure you want to delete?</p>
                                </div>
                                <div class="modal-footer">
                                    <button class="delete_designation btn btn-primary "  data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok</button>
                                    <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                                </div>			
                        </div>
			<!--Do not place contents below this line-->
						<div id="cant_delete" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display:none;">
                                <div class="modal-header">
                                    <div class="navbar-inner-custom">
                                        Warning 
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <p>You cannot delete this Designation, as it has been assigned to a User. </p>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-danger" data-dismiss="modal"> <i class="icon-remove icon-white"></i> Close</button>
                                </div>
                            </div>
                    </section>
                </div>
            </div>
        </div>
        <!---place footer.php here -->
        <?php $this->load->view('includes/footer'); ?> 
        <?php $this->load->view('includes/js'); ?>
        <!---place js.php here -->
		<script src="<?php echo base_url('twitterbootstrap/js/custom/configuration/designation.js'); ?>" type="text/javascript"></script>
