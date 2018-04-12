<?php
/* -----------------------------------------------------------------------------------------------------------------------------
 * Description	: View for Department Adding, Editing and Disabling/Enabling operations performed through this file.	  
 * Modification History:
 * Date							Modified By								Description
 * 20-08-2013                   Mritunjay B S                           Added file headers, function headers & comments. 
  -----------------------------------------------------------------------------------------------------------------------------
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
                <div class="bs-docs-example">
                    <!--content goes here-->
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Department List
                        </div>
                    </div>
                    <a href="<?php echo base_url('configuration/add_department'); ?>" class="btn btn-primary pull-right" ><i class="icon-plus-sign icon-white"></i> Add</a>
                    <br>
                    <br>
                    <div id="example_wrapper" class="dataTables_wrapper" role="grid">
                        <table class="table table-bordered table-hover " id="example" aria-describedby="example_info">
                            <thead align = "center">
                                <tr role="row">
                                    <th class="header headerSortDown span2" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending"> Name </th>
                                    <th class="header" style="width: 30px;" role="columnheader" tabindex="0" aria-controls="example"> Acronym </th>
                                    <th class="header span2" role="columnheader" tabindex="0" aria-controls="example"> Head of Department </th>										
                                    <th class="header span2" role="columnheader" tabindex="0" aria-controls="example"> Professional Bodies</th>
                                    <th class="header" style="width: 40px;" role="columnheader" tabindex="0" aria-controls="example"> Number of Journals</th>
                                    <th class="header" style="width: 40px;" role="columnheader" tabindex="0" aria-controls="example"> Number of Magazines</th>	
                                    <th class="header span2" role="columnheader" tabindex="0" aria-controls="example"> Faculty Details </th>
                                    <th class="header span2" role="columnheader" tabindex="0" aria-controls="example"> Programs Offered </th>
                                    <th class="header" style="width: 30px;"> Edit </th>
                                    <th class="header" style="width: 40px;"> Status </th>
                                </tr>
                            </thead>
                            <tbody role="alert" aria-live="polite" aria-relevant="all">
                                <?php
                                foreach ($result1 as $data_value):
                                    if ($data_value['no_of_journals'] == 0) {
                                        $journal = " ";
                                    } else {
                                        $journal = $data_value['no_of_journals'];
                                    }
                                    if ($data_value['no_of_magazines'] == 0) {
                                        $mag = " ";
                                    } else {
                                        $mag = $data_value['no_of_magazines'];
                                    }
                                    ?>
                                    <tr class="gradeU even">
                                        <td title ="<?php echo $data_value['dept_description']; ?>" class="cursor_pointer sorting_1 table-left-align"><?php echo $data_value['dept_name']; ?> </td>
                                        <td class="sorting_1"><?php echo $data_value['dept_acronym']; ?> </td>
                                        <td class="sorting_1"><?php echo $data_value['title'] . " " . $data_value['first_name'] . " " . $data_value['last_name']; ?> </td>
                                        <td class="sorting_1"><?php echo $data_value['professional_bodies']; ?> </td>	 
                                        <td class="sorting_1" style="text-align: right;"><?php echo $journal; ?> </td>
                                        <td class="sorting_1" style="text-align: right;"><?php echo $mag; ?> </td>	


                                        <?php if ($data_value['ispgm'] != 0) { ?>
                                            <td><a data-toggle="modal" href="#facultyModal" id="<?php echo $data_value['dept_id']; ?>" class="get_faculty_details" style="text-decoration: none;"> View faculty details</a></td>
                                            <td><a data-toggle="modal" href="#myModal" id="<?php echo $data_value['dept_id']; ?>" class="get_programes_dept1" style="text-decoration: none;"><i class="icon-filter icon-black"></i> Program(s)</a></td>
                                        <?php } else { ?>
                                            <td>N\A</td>
                                            <td>N\A</td>
                                        <?php } ?>

                                        <?php if ($data_value['dept_id'] != 72) { ?>
                                            <td><center><a class="" title="Edit" href="<?php echo base_url('configuration/add_department/department_edit') . '/' . $data_value['dept_id']; ?>"><i class="icon-pencil "></i></a></center></td>	
                                    <!-- Status -->
                                    <?php if ($data_value['status'] == 0) { ?>
                                        <td><center>
                                            <a data-toggle="modal" href="#myModalenable" class="get_id" id="<?php echo $data_value['dept_id']; ?>"
                                               rel="tooltip" title="Enable" role="button"><i id="<?php echo 'enable' . $data_value['dept_id']; ?>" class="icon-ok-circle"></i> </a></center></td>
                                        <?php } else { ?>
                                        <td>
                                        <center><a data-toggle="modal" href="#" rel="tooltip" title="Disable" class="disable_check"    id="<?php echo $data_value['dept_id']; ?>"><i class="icon-ban-circle"></i></a>
                                        </center>
                                        </td>
                                    <?php } ?>
                                    <!-- Status -->
                                <?php } else { ?>
                                    <td><center><a data-toggle="modal" href="#cant_edit" ><i class="icon-pencil "></i></a></center></td>
                                    <td><center><a data-toggle="modal" href="#cant_disable"><i class="icon-ban-circle"></i></a></center></td>
                                <?php } ?>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                        <br>
                        <br><br>
                        <div class="row pull-right">
                            <a href="<?php echo base_url('configuration/add_department'); ?>" class="btn btn-primary pull-right" ><i class="icon-plus-sign icon-white"></i> Add</a>
                        </div><br><br>
                        <!--Do not place contents below this line-->	
                    </div><br><br>
                    </section>

                    <!-- modal to display list of programs offered by the department -->
                    <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true">
                        <div class="modal-header">
                            <div class="navbar-inner-custom">
                                List of Programs Offered by the Department
                            </div>
                        </div>
                        <div class="modal-body">
                            <table class="table table-bordered table-hover">
                                <tbody id="div111">

                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-danger" data-dismiss="modal"> <i class="icon-remove icon-white"></i> Close</button>
                        </div>
                    </div>

                    <!-- modal to display faculty details related to the department -->
                    <div id="facultyModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="facultyModal" data-backdrop="static" data-keyboard="true">
                        <div class="modal-header">
                            <div class="navbar-inner-custom">
                                Faculty Details
                            </div>
                        </div>

                        <div class="modal-body">
                            <table class="table table-bordered table-hover">
                                <tbody id="faculty_list">

                                </tbody>
                            </table>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-danger" data-dismiss="modal"> <i class="icon-remove icon-white"></i> Close</button>
                        </div>
                    </div>

                    <!-- modal to confirm before enabling a department -->
                    <div id="myModalenable" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                        <div class="modal-header">
                            <div class="navbar-inner-custom">
                                Enable Confirmation 
                            </div>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to enable?</p>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary enable_dept btn " data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok </button>
                            <button class="btn btn-danger" data-dismiss="modal"> <i class="icon-remove icon-white"></i> Close</button>
                        </div>
                    </div>

                    <!-- modal to confirm before disabling a department -->
                    <div id="myModaldisable" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                        <div class="modal-header">
                            <div class="navbar-inner-custom">
                                Disable Confirmation 
                            </div>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to disable?</p>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary disable_dept btn " data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok </button>
                            <button class="btn btn-danger" data-dismiss="modal"> <i class="icon-remove icon-white"></i> Close</button>
                        </div>
                    </div>

                    <!--################### Modal #####################-->
                    <div id="Cntdisable" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                        <div class="modal-header">
                            <div class="navbar-inner-custom">
                                Warning
                            </div>           
                        </div>
                        <div class="modal-body" id="comment">
                            <p> You cannot disable the Department, as there are Programs being running under this Department. </p>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok </button>
                        </div>
                    </div>

                    <div id="cant_edit" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: 	none;">
                        <div class="modal-header">
                            <div class="navbar-inner-custom">
                                Warning 
                            </div>
                        </div>
                        <div class="modal-body">
                            <p>You cannot edit the General Department, as it is a Default Department.</p>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-danger" data-dismiss="modal"> <i class="icon-remove icon-white"></i> Close</button>
                        </div>
                    </div>

                    <div id="cant_disable" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: 	none;">
                        <div class="modal-header">
                            <div class="navbar-inner-custom">
                                Warning 
                            </div>
                        </div>
                        <div class="modal-body">
                            <p>You cannot disable the General Department, as it is a Default Department.</p>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-danger" data-dismiss="modal"> <i class="icon-remove icon-white"></i> Close</button>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>

<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<!---place js.php here -->
<?php $this->load->view('includes/js'); ?>
<script language="javascript" type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/custom/configuration/department.js'); ?>"></script>
