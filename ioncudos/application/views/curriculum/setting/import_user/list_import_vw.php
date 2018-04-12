<!DOCTYPE html>
<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: 
 * Modification History:
 * Date                 Modified By			Description
 * 02-02-2015		Jyoti                   Added file headers, function headers & comments. 
 * 04-01-2016		Shayista                Added loading image.
  ---------------------------------------------------------------------------------------------------------------------------------
 */
?>
<html lang="en">
    <!--head here -->
    <?php $this->load->view('includes/head'); ?>
    <body data-spy="scroll" data-target=".bs-docs-sidebar">
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
                        <div id="loading" class="ui-widget-overlay ui-front">
                            <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
                        </div>
                        <div class="bs-docs-example">
                            <?php $this->load->view('includes/crclm_tab'); ?>
                            <!--content goes here-->
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Imported User List ( <?php echo $this->lang->line('course_owner_full'); ?>)
                                </div>
                            </div>				

                            <br />
                            <div id="loading" class="ui-widget-overlay ui-front">
                                <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
                            </div>

                            <div id="example_wrapper" class="dataTables_wrapper" role="grid" style="margin-bottom:88px">
                                <table class="table table-bordered table-hover" id="example" aria-describedby="example_info">
                                    <thead>
                                        <tr role="row">
                                            <th class="header headerSortDown span1" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >Sl No.</th>
                                            <th class="header headerSortDown span2" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >First Name</th>
                                            <th  class="header headerSortDown span2" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >Last Name</th>
                                            <th  class="header headerSortDown span2" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >From Department</th>
                                            <th  class="header headerSortDown span2" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" > Designation</th>
                                            <th  class="header headerSortDown span1" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >Email</th>
                                            <th  class="header headerSortDown span1" style="white-space:nowrap;" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >Assigned Role</th>
                                            <th  class="header headerSortDown span1" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >Remove</th>
                                        </tr>
                                    </thead>
                                    <tbody role="alert" aria-live="polite" aria-relevant="all">
                                        <?php
                                        if ($list_data) {
                                            $i = 1;
                                            foreach ($list_data as $data) {
                                                ?>
                                                <tr><td style="text-align: right;"><?php echo $i++ ?></td><td><?php echo $data['title'] . ' ' . ucfirst($data['first_name']) ?></td><td><?php echo ucfirst($data['last_name']) ?></td><td><?php echo ucfirst($data['dept_acronym']) ?></td><td><?php echo ucfirst($data['designation_name']) ?></td><td><?php echo $data['email'] ?></td>
                                                <!-- <td><a href=""  id="edit_user_id" class="edit_user icon-pencil" rel="tooltip" title="Manage Role" value="<?php echo $data['id'] ?>"></a></td> -->
                                                    <td>Course Owner</td>
                                                    <td><center><a href=""  id="delete_user_id" class="delete_user icon-remove" rel="tooltip" title="Remove User" value="<?php echo $data['id'] ?>"></a></center></td></tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                            <p><b>Note: </b>Inclusion of other Department Users as Course Owner.</p>
                            <br>
                            <form id="import_list_form" name="import_list_form" class="form-inline" action= "<?php echo base_url('curriculum/import_user/save_imported_user_data'); ?>" method="POST">

                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        Add User as <?php echo $this->lang->line('course_owner_full'); ?> from other Department					   
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <div class="span12">				
                                        <div class="span4">					
                                            From Department :<font color='red'>*</font> 
                                            <select id="department" name="department" autofocus = "autofocus" class="required input-large" >	
                                                <option value="0">Select Department</option>
                                                <?php foreach ($dept_id as $dept_info) { ?>
                                                    <option value="<?php echo $dept_info['dept_id'] ?>"><?php echo $dept_info['dept_name'] ?></option>
                                                <?php } ?>
                                            </select>					
                                        </div>

                                        <div class="span4 ">					
                                            <div class="pull-left"> User :<font color='red'>*</font></div>
                                            <select id="user" name="user" class="required input-large">
                                                <option value="0">Select User</option>
                                            </select><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><span id="user_email_div" class="color_maroon"></span></b>
                                        </div>
                                        <div class="span4">					
                                            Send the email to Chairman(HoD): <input id = "send_mail" name = "send_mail" type = "checkbox" value = "1" checked>
                                        </div>
                                    </div>
                                </div>

                                <br>

                                <div class="pull-right"><br>
                                    <button class="import_user_form_submit btn btn-primary" type="submit"><i class="icon-file icon-white"></i> Save</button>
                                    <button class="clear_all btn btn-info" type="reset" id="reset"><i class="icon-refresh icon-white"></i> Reset</button>
                                </div><br><br><br>
                            </form>
                        </div>
                </div>
            </div>
            <!--Modal to delete imported user-->
            <div id="edit_import_user_div" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false" >
                <div class="modal-header">
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Manage User Role(s)
                        </div>
                    </div>
                </div>
                <input type="hidden" id="edit_user_id_val" value="" />
                <form id="edit_user_data_div_form" >		
                    <div class="modal-body" id="edit_user_data_div">


                    </div>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary edit_import_user_btn" aria-hidden="true" ><i class="icon-ok icon-white"></i> Update</button>
                    <button  type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                </div>
            </div>

            <div id="delete_import_user_div" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false" >
                <div class="modal-header">
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Remove User
                        </div>
                    </div>
                </div>
                <div class="modal-body" id="comment">
                    <p> Are you sure that you want to remove this User from your Department ?</p>
                    <input type="hidden" id="delete_user_id_val" value="" />
                </div>
                <div class="modal-footer">
                    <button  class="btn btn-primary delete_import_user_btn" data-dismiss="modal" aria-hidden="true" ><i class="icon-ok icon-white"></i> Ok</button>
                    <button  type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                </div>
            </div>
			
			<div id="user_cannot_delete_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false" >
                <div class="modal-header">
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Message
                        </div>
                    </div>
                </div>
                <div class="modal-body" id="comment">
                    <p> You cannot delete this user as there is a course is allocated to him.</p>
                    <input type="hidden" id="delete_user_id_val" value="" />
                </div>
                <div class="modal-footer">
                    <button  type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>
                </div>
            </div>
			
            <br>
        </div>
        <!---place footer.php here -->
        <?php $this->load->view('includes/footer'); ?> 
        <!---place js.php here -->
        <?php $this->load->view('includes/js'); ?>
    </body>
    <script language="javascript"  type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/import_user.js'); ?>"></script>

</html> 
