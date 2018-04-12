<?php
/**
 * Description          :	Department users list page - View
 * Created		:	27-03-2013
 * Author		:	Arihant Prasad
 * Modification History:
 *   Date                Modified By                        Description
 *  4/12/2016		Bhagyalaxmi S S			Addedd faculty  Contribution Column
  --------------------------------------------------------------------------------------------------- */
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
                <div class="bs-docs-example">
                    <!--content goes here-->
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            User List							
                            <a href="#help" class="pull-right show_help" data-toggle="modal" style="text-decoration: underline; color: white; font-size: 12px;"> Guidelines&nbsp;<i class="icon-white icon-question-sign"></i></a>
                        </div>
                    </div>	
					
                    <div class="row">
					<div class="span4">
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="" style="font-size : 14px;"><b> Department : </b></span> <span style="font-size : 13px;"><?php echo $users[0]['dept_name']; ?>   </pan>
					</div>
					<div class="span8">	
                     <a class="btn btn-primary pull-right" href="<?php echo base_url('curriculum/dept_users/create_dept_user'); ?>"><i class="icon-plus-sign icon-white"></i> Add </a>
					 </div>
                    </div></br>

                    <div id="example_wrapper" class="dataTables_wrapper" role="grid">
                        <form>
                            <table class="table table-bordered table-hover" id="example" aria-describedby="example_info">
                                <thead>
                                    <tr role="row">
                                        <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending"> First Name </th>
                                        <th class="header" role="columnheader" tabindex="0" aria-controls="example"> Last Name </th>
                                     <!--  <th class="header" role="columnheader" tabindex="0" aria-controls="example"> Department </th>-->
                                        <th class="header" role="columnheader" tabindex="0" aria-controls="example"> Designation </th>
                                        <th class="header" role="columnheader" tabindex="0" aria-controls="example"> Email </th>
                                       <!-- <th class="header" role="columnheader" tabindex="0" aria-controls="example"> Faculty Contribution </th>-->
                                        <th class="header" style="width: 40px;"> Edit </th>
                                        <th class="header" style="width: 50px;"> Status </th>
                                    </tr>
                                </thead>

                                <tbody role="alert" aria-live="polite" aria-relevant="all">
                                    <?php foreach ($users as $user) { ?>
                                        <tr class="gradeU even">
                                            <td class="  sorting_1"><?php echo $user['title'] . ' ' . $user['first_name']; ?></td>
                                            <td><?php echo $user['last_name']; ?></td>
                                           <!-- <td><?php echo $user['dept_name']; ?></td>-->
                                            <td><?php echo $user['designation_name']; ?></td>
                                            <td><?php echo $user['email']; ?></td>
                                            <!-- .'/'.$user['email']-->
                                            <?php if (($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')))  ?>
                                            <!-- <td><?php // $uid = $this->crypter->c_encode($user['id']); ?><a class="cursor_pointer" href="<?php // echo base_url('report/edit_profile/index/' . $uid) ?>"> View Faculty Contribution  </a></td> -->
                                            <?php if (($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')) && ($user['id'] != 1)) { ?>
                                                <td>
                                        <center>
                                            <?php
                                            $e_id = $this->crypter->c_encode($user['id']);
                                            echo anchor("curriculum/dept_users/edit_dept_user/" . $e_id, '<i class="icon-pencil"></i>', 'title="Edit"');
                                            ?>
                                        </center>
                                        </td>
                                        <td>
                                        <center>
                                            <?php
                                            echo ($user['active']) ? '<a class="status" href="#disable" id="' . $user['id'] . '" role="button" data-toggle="modal"><i class="icon-ban-circle"></i></a>' : '<a class="status" href="#enable" id="' . $user['id'] . '"role="button" id="' . $user['id'] . '" data-toggle="modal"><i class="icon-ok-circle"></i></a>';
                                            ?>
                                        </center>
                                        </td>
                                    <?php } else { ?>
                                        <td><center><a data-toggle="modal" href="#cant_edit" ><i class="icon-pencil "></i></a></center></td>
                                        <td><center><a data-toggle="modal" href="#cant_disable"><i class="icon-ban-circle"></i></a></center></td>
                                    <?php } ?>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table><br><br><br>
                            <div class="row">
                                <a  class="btn btn-primary pull-right"" href="<?php echo base_url('curriculum/dept_users/create_dept_user'); ?>"><i class="icon-plus-sign icon-white"></i> Add </a>
                            </div><br><br>
                        </form>

                        <!-- Modal to confirm before enabling a user -->
                        <div id="enable" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true">
                            <div class="modal-header">
                                <div class="navbar-inner-custom">
                                    Enable Confirmation 
                                </div>
                            </div>
                            <div class="modal-body">
                                <p> Are you sure you want to enable? </p>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary enable-ok" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok</button>
                                <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Cancel</button>
                            </div>
                        </div>

                        <!-- Modal to confirm before disabling a user -->
                        <div id="disable" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true">
                            <div class="modal-header">
                                <div class="navbar-inner-custom">
                                    Disable Confirmation 
                                </div>
                            </div>
                            <div class="modal-body">
                                <p> Are you sure you want to disable? </p>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary disable-ok" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok</button>
                                <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Cancel</button>
                            </div>
                        </div>

                        <!-- Modal to restrict user from editing admin details -->
                        <div id="cant_edit" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                            <div class="modal-header">
                                <div class="navbar-inner-custom">
                                    Warning 
                                </div>
                            </div>
                            <div class="modal-body">
                                <p>You cannot edit/modify the Administrator (Admin) details</p>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-danger" data-dismiss="modal"> <i class="icon-remove icon-white"></i> Close</button>
                            </div>
                        </div>

                        <!-- Modal to restrict user from disabling admin -->
                        <div id="cant_disable" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: 	none;">
                            <div class="modal-header">
                                <div class="navbar-inner-custom">
                                    Warning 
                                </div>
                            </div>
                            <div class="modal-body">
                                <p>You cannot Disable the Administrator (Admin)</p>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-danger" data-dismiss="modal"> <i class="icon-remove icon-white"></i> Close </button>
                            </div>
                        </div>
                        <!-- Modal to display help contents related to Program outcomes -->
                        <div id="help" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true" >
                            <div class="modal-header">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        Department User List guidelines files
                                    </div>
                                </div>
                            </div>

                            <div class="modal-body" id="help_content">

                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Close</button>
                            </div>
                        </div>

                        <!-- Cannot disable modal -->
                        <div id="cannot_disable" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true">
                            <div class="modal-header">
                                <div class="navbar-inner-custom">
                                    Warning 
                                </div>
                            </div>
                            <div class="modal-body">
                                <p> You cannot disable the User, as the User is assigned as a Curriculum Owner. </p>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-danger" data-dismiss="modal"> <i class="icon-remove icon-white"></i> Close</button>
                            </div>
                        </div><br/><br/>
                    </div>
                </div>
            </section>
        </div>		
    </div>	
</div>
<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<!---place js.php here -->
<?php $this->load->view('includes/js'); ?>
<script src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/dept_user.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('twitterbootstrap/js/bootstrap_multiselect_dropdown.js'); ?>" type="text/javascript"></script>
<!-- End of file list_user_vw.php
        Location: .curriculum/dept_users/list_dept_user_vw.php -->