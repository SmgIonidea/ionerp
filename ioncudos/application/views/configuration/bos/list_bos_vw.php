<?php
/**
 * Description	: BoS grid provides the entire list of BoS Users along with their
  respective department, email id.
  Edit and Toggle buttons are also provided to edit the existing BOS users
  and to toggle the status between Enable/Disable respectively
 * Created		:	03-04-2013. 
 * Modification History:
 * Date				Modified By				Description
 * 23-08-2013		Abhinay B.Angadi        Added file headers, indentations.
 * 30-08-2013		Abhinay B.Angadi		Variable naming, Function naming &
 * 											Code cleaning.
  --------------------------------------------------------------------------------
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
        <?php $this->load->view('includes/sidenav_1'); ?>
        <div class="span10">
            <!-- Contents -->
            <section id="contents">
                <div class="bs-docs-example fixed-height">
                    <!--content goes here-->	
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Board of Studies(BoS) Member List
                        </div>
                    </div>
                    <div class="pull-right">
                        <a  class="btn btn-primary" href="<?php echo base_url('configuration/addbos/create_user'); ?>"><i class="icon-plus-sign icon-white"></i> Add New </a>
                    </div>
                    <div class="pull-right" style="margin-right:2px;">                                
                        <a  class="btn btn-primary" href="<?php echo base_url('configuration/bos/bos_add_existing'); ?>"><i class="icon-plus-sign icon-white"></i> Add Existing User</a>
                    </div>
                    <br><br>
                    <div id="example_wrapper" class="dataTables_wrapper" role="grid">
                        <table class="table table-bordered table-hover" id="example" aria-describedby="example_info">
                            <thead>
                                <tr role="row">
                                    <th class="header headerSortDown span2" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >First Name</th>
                                    <th class="header span2" role="columnheader" tabindex="0" aria-controls="example" >Last Name</th>
                                    <th class="header span2" role="columnheader" tabindex="0" aria-controls="example" >Designation</th>
                                    <th class="header span2" role="columnheader" tabindex="0" aria-controls="example" >Department</th>
                                                                                <!--<th class="header" role="columnheader" tabindex="0" aria-controls="example" >Member For</th>-->
                                    <th class="header span2" role="columnheader" tabindex="0" aria-controls="example" >Email</th>
                                    <th class="header" style="width:30px">Edit</th>
                                    <th class="header" style="width:40px">Delete</th>
                                </tr>
                            </thead>
                            <tbody role="alert" aria-live="polite" aria-relevant="all">
                                <?php //var_dump($users);?>
                                <?php foreach ($users as $user): ?>
                                    <tr class="gradeU even">
                                        <td class="  sorting_1"><?php echo $user->title . ' ' . $user->first_name; ?> </td>
                                        <td><?php echo $user->last_name; ?></td>
                                        <td><?php echo $user->designation_name; ?></td>
                                        <td>
                                            <?php echo $user->dept_acronym; ?>
                                        </td>
                                        <td><?php echo $user->email; ?></td>
                                        <td ><center><?php
                                    echo anchor("configuration/bos/edit_bos_user/" . $user->id . "/" . $user->bos_id, '<i class="icon-pencil"></i>', 'rel="tooltip" title="Edit" data-toggle="modal" data-original-title="Enable" ');
                                    ?></center></td>
                                <!--	<?php //if ($user->active == 0) {    ?>
                                    <td>	
                                        <div id="hint">
                                            <center><a href="#myModal" rel="tooltip" title="Enable" data-toggle="modal" class="icon-remove" data-original-title="enable"  value="<?php echo $user->id; ?>" onclick="javascript:currentIDSt(<?php echo $user->id; ?>);"></a></center>
                                        </div>
                                    </td>
                                <?php //} else { ?>
                                                <td>
                                        <div id="hint">
                                            <center><a href="#myModal1" rel="tooltip" title="Disable" data-toggle="modal" class="icon-ban-circle" data-original-title="disable"   value="<?php echo $user->id; ?>" onclick="javascript:currentIDSt(<?php echo $user->id; ?>);"></a></center>
                                        </div>
                                    </td>
                                <?php // } ?>-->
                                <td><div id="hint">						
                                        <center><a href="#myModal_delete" rel="tooltip" title="Delete" data-toggle="modal" class="icon-remove" data-original-title="disable"   value="<?php echo $user->id; ?>" onclick="javascript:currentIDSt(<?php echo $user->id; ?>, <?php echo $user->bos_dept_id; ?>);"></a></center>

                                    </div> </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table><br><br><br>
                        <div class="pull-right">
                            <a  class="btn btn-primary" href="<?php echo base_url('configuration/addbos/create_user'); ?>"><i class="icon-plus-sign icon-white"></i> Add New </a>
                        </div>
                        <div class="pull-right" style="margin-right:2px;">                                
                            <a  class="btn btn-primary" href="<?php echo base_url('configuration/bos/bos_add_existing'); ?>"><i class="icon-plus-sign icon-white"></i> Add Existing User</a>
                        </div><br><br>

                        <!-- Modal -->


                        <div id="myModal_delete" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true">
                            <div class="modal-header">
                                <div class="navbar-inner-custom">
                                    Delete Confirmation 
                                </div>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to Delete?</p>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary" id="bos_delete" data-dismiss="modal" aria-hidden="true" ><i class="icon-ok icon-white"></i> Ok</button>
                                <button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                            </div>
                        </div>			
                        <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true">
                            <div class="modal-header">
                                <div class="navbar-inner-custom">
                                    Delete Confirmation 
                                </div>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to Enable?</p>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" Onclick="javascript:enable(); location.reload();"><i class="icon-ok icon-white"></i> Ok</button>
                                <button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                            </div>
                        </div>
                        <div id="myModal1" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true">
                            <div class="modal-header">
                                <div class="navbar-inner-custom">
                                    Disable Confirmation 
                                </div>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to Disable?</p>
                            </div>						
                            <div class="modal-footer">
                                <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" Onclick="javascript:disable(); location.reload();"><i class="icon-ok icon-white"></i> Ok </button>
                                <button type="reset" class="btn btn-danger cancel" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel </button>
                            </div>
                        </div>

                        <div id="cannot_delete" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true">
                            <div class="modal-header">
                                <div class="navbar-inner-custom">
                                    Warning 
                                </div>
                            </div>
                            <div class="modal-body">
                                <p> You cannot disable the User, as the User is assigned as a Course Owner for Curriculum. </p>
                            </div>						
                            <div class="modal-footer">
                                <button class="btn btn-primary" data-dismiss="modal"> <i class="icon-ok icon-white"></i> Ok</button>
                            </div>
                        </div>
                    </div><br> <br>

                    <!--Do not place contents below this line-->	
                </div>	
            </section>
        </div>
    </div>
</div>
<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<!---place js.php here -->
<?php $this->load->view('includes/js'); ?>
<script src="<?php echo base_url('twitterbootstrap/js/custom/configuration/bos.js'); ?>" type="text/javascript"></script>