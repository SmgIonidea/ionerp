<?php
/**
 *
 * Description          :	Displaying the list of users, adding new users, editing existing users
 * 					
 * Created		:	27-03-2013
 *
 * Author		:	
 * 		  
 * Modification History:
 *   Date                   Modified By                	Description
 * 02-09-2013		   Arihant Prasad           File header, indentation, comments and variable 
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
        <?php $this->load->view('includes/sidenav_1'); ?>
        <div class="span10">
            <!-- Contents -->
            <section id="contents">
                <div class="bs-docs-example">
                    <!--content goes here-->
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            User List
                        </div>
                    </div>	

                    <div class="row">
                        <a  class="btn btn-primary pull-right"" href="<?php echo base_url('configuration/users/create_user'); ?>"><i class="icon-plus-sign icon-white"></i> Add </a>
                    </div>
                    <div>
                        <label>
                            Department:<font color="red"> * </font>
                            <select size="1" id="dept_id" name="dept_id" autofocus = "autofocus" aria-controls="example" style="width:35%;" onchange="select_dept();">
                                <option value="0" selected>Select Department </option>
                                <?php foreach ($results as $list_item): ?>
                                    <option value="<?php echo $list_item['dept_id']; ?>"> <?php echo $list_item['dept_name']; ?> </option>
                                <?php endforeach; ?>
                            </select>									
                        </label>
                        <input type="hidden" name="dept_id_h" id="dept_id_h" value="" />
                        <input type="hidden" name="user_id_1" id="user_id_1" value="" />
                    </div><br>


                    <div id="example_wrapper" class="dataTables_wrapper" role="grid">
                        <table class="table table-bordered table-hover" id="example" aria-describedby="example_info">
                            <thead>
                                <tr role="row">
                                    <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending"> First Name </th>
                                    <th class="header" role="columnheader" tabindex="0" aria-controls="example" > Last Name </th>
                                    <th class="header" role="columnheader" tabindex="0" aria-controls="example" > Department </th> 
                                    <th class="header" role="columnheader" tabindex="0" aria-controls="example" > User Group </th>
                                    <th class="header" style="width: 140px;" role="columnheader" tabindex="0" aria-controls="example" > Designation </th>
                                    <th class="header" style="width: 180px;" role="columnheader" tabindex="0" aria-controls="example" > Email </th>
                                    <th class="header" style="width: 40px;"> Edit </th>
                                    <th class="header" style="width: 50px;"> Status </th>
                                </tr>
                            </thead>

                            <tbody role="alert" aria-live="polite" aria-relevant="all">
                                <?php foreach ($users as $user): ?>
                                    <tr class="gradeU even">
                                        <td class=" sorting_1"><?php echo $user['title'] . ' ' . $user['first_name']; ?></td>
                                        <td><?php echo $user['last_name']; ?></td>
                                        <td> <?php echo $user['dept_acronym']; ?> </td> 
                                        <td> <?php echo $user['group_name']; ?> </td>
                                        <td><?php echo $user['designation_name']; ?></td>
                                        <td><?php echo $user['email']; ?></td>

                                        <?php if ($user['id'] != 1) { ?>
                                            <td><center>
                                        <?php echo anchor("configuration/users/edit_user/" . $user['id'], '<i class="icon-pencil"></i>', 'title="Edit"'); ?> 
                                    </center></td>
                                    <td><center>
                                        <?php
                                        echo ($user['active']) ? '<a class="status" href="#disable" id="' . $user['id'] . '" role="button" data-toggle="modal"><i class="icon-ban-circle"></i></a>' : '<a class="status" href="#enable" id="' . $user['id'] . '" role="button" id="' . $user['id'] . '" data-toggle="modal"><i class="icon-ok-circle"></i></a>';
                                        ?>
                                    </center></td>
                                <?php } else { ?>
                                    <td><center><a data-toggle="modal" href="#cant_edit" ><i class="icon-pencil "></i></a></center></td>
                                    <td><center><a data-toggle="modal" href="#cant_disable"><i class="icon-ban-circle"></i></a></center></td>
                                <?php } ?>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table><br><br><br>
                        <div class="row">
                            <a  class="btn btn-primary pull-right"" href="<?php echo base_url('configuration/users/create_user'); ?>"><i class="icon-plus-sign icon-white"></i> Add </a>
                        </div><br><br>

                        <!-- Modal to confirm before enabling a user -->
                        <div id="enable" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true">
                            <div class="modal-header">
                                <div class="navbar-inner-custom">
                                    Enable Confirmation 
                                </div>
                            </div>
                            <div class="modal-body">
                                <p> Are you sure you want to Enable? </p>
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
                                <p> Are you sure you want to Disable? </p>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary disable-ok" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok</button>
                                <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Cancel</button>
                            </div>
                        </div>

                        <div id="cant_edit" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: 	none;">
                            <div class="modal-header">
                                <div class="navbar-inner-custom">
                                    Warning 
                                </div>
                            </div>
                            <div class="modal-body">
                                <p>You cannot edit/modify the Administrator (Admin) details</p>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary" data-dismiss="modal"> <i class="icon-ok icon-white"></i> Ok</button>
                            </div>
                        </div>

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
                                <button class="btn btn-primary" data-dismiss="modal"> <i class="icon-ok icon-white"></i> Ok</button>
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
                                <button class="btn btn-primary" data-dismiss="modal"> <i class="icon-ok icon-white"></i> Ok</button>
                            </div>
                        </div>
                        <br/><br/>
                    </div>



                    <!--<div class="box round first grid">
                        <div class="navbar">
                            <div class="navbar-inner-custom">
                                Bulk Upload Users
                            </div>
                        </div>

                        <div class="block">
                    <?php
                    if (isset($error))
                        echo $error;
                    echo form_open_multipart('configuration/users/bulk_users_upload');
                    ?>
                            <input type="file" name="userfile" size="20"/>
                            <br><br>
                            <input type="submit" class="btn btn-primary " value="Upload"/>
                        </div>
                    </div>-->
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
<script src="<?php echo base_url('twitterbootstrap/js/custom/configuration/user_list.js'); ?>" type="text/javascript"></script>

<!-- End of file list_user_vw.php
        Location: .configuration/users/list_user_vw.php -->