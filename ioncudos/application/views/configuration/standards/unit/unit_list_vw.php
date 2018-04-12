<?php
/**
 * Description	:	List View for Unit Module.
 * Created		:	03-04-2013. 
 * Modification History:
 * Date				Modified By				Description
 * 26-08-2013                   Abhinay B.Angadi                        Added file headers & indentations.
 * 03-09-2013                   Mritunjay B S                           Changed Function name, Variable names.
 * 27-03-2014		   Jevi V. G				Added description field for unit		
  -----------------------------------------------------------------------------------------------------------------
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
                    <!-- Contents -->
                    <section id="contents">
                        <div class="bs-docs-example">
                            <!--content goes here-->	
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Duration List
                                </div>
                            </div>
                            <div class="row pull-right">   
                                <a href="<?php echo base_url('configuration/unit/unit_add_record'); ?>" align="right">
                                    <button type="button" class="btn btn-primary"><i class="icon-plus-sign icon-white"> </i> Add</button>
                                </a> </div>
                            <br><br>
                            <div id="example_wrapper" class="dataTables_wrapper" role="grid">
                                <table class="table table-bordered table-hover " id="example" aria-describedby="example_info">
                                    <thead align = "center">
                                        <tr class="gradeU even" role="row">
                                            <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >Duration </th>
											<th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending"> Description </th>
                                            <th class="header" rowspan="1" colspan="1" style="width: 60px;" role="columnheader" tabindex="0" aria-controls="example" align="center" ><center></center>Edit</th>
                                    <th class="header" rowspan="1" colspan="1" style="width: 60px;" role="columnheader" tabindex="0" aria-controls="example" align="center" ><center></center>Delete</th>
                                    </tr>
                                    </thead>
                                    <tbody role="alert" aria-live="polite" aria-relevant="all">
                                        <?php foreach ($records as $records): ?>
                                            <tr>
                                                <td class="sorting_1 table-left-align"><?php echo $records['unit_name']; ?></td>  
												<td class="sorting_1 table-left-align"><?php echo $records['unit_description']; ?></td>  
                                                <td> <a class="" href="<?php echo base_url('configuration/unit/unit_edit') . '/' . $records['unit_id']; ?>">
                                                        <i class="icon-pencil icon-black"> </i></a></td>
                                                <td>
                                                    <div id="hint">
                                                        <center>
                                                            <a href id="<?php echo $records['unit_id']; ?>" class=" get_id icon-remove unit_delete" 	data-toggle="modal" data-original-title="Delete" rel="tooltip" 
                                                               title="Delete" value="<?php echo $records['unit_id']; ?>">
                                                            </a>
                                                        </center>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>	
                                    </tbody>
                                </table><br><br><br>
                                <div class="row pull-right">   
                                    <a href="<?php echo base_url('configuration/unit/unit_add_record'); ?>" align="right">
                                        <button type="button" class="btn btn-primary"><i class="icon-plus-sign icon-white"> </i> Add</button>
                                    </a> </div><br><br>
                            </div>
                            <br>
                        </div>
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
                                    <button class="delete_unit btn btn-primary "  data-dismiss="modal" ><i class="icon-ok icon-white"></i> Ok</button>
                                    <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                                </div>
                            </div>
                            <!--Do not place contents below this line-->	
                        </div>
						<div id="myModaldelete1" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                            <div class="modal-header">
                                <div class="navbar-inner-custom">
                                    Warning 
                                </div>
                                <div class="modal-body">
                                    <p>You cannot Delete this Duration, as it is been assigned to a Program</p>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>
                                </div>
                            </div>		
                            <br>
                            <br>
                            <!--Do not place contents below this line-->	
                        </div>
						<div id="myModaldeleteSuccess" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                            <div class="modal-header">
                                <div class="navbar-inner-custom">
                                    Delete Confirmation 
                                </div>
                                <div class="modal-body">
                                    <p>Duration Deleted Successfully</p>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>
                                </div>
                            </div>		
                            <br>
                            <br>
                            <!--Do not place contents below this line-->	
                        </div>
                    </section>
                </div>
            </div>
        </div>
        <!---place footer.php here -->
        <?php $this->load->view('includes/footer'); ?> 
        <?php $this->load->view('includes/js'); ?>
        <!---place js.php here -->
        <script src="<?php echo base_url('js/setup.js'); ?>" type="text/javascript"></script>
		<script language="javascript" type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/custom/configuration/unit.js');?>"> </script>