<?php
/**
 * Description          :	Course type list will display the grid containing different course type(s) such as basic, elective, open elective, etc.
 * 					
 * Created		:	25-03-2013
 *
 * Author		:	
 * 		  
 * Modification History:
 *   Date                   Modified By                			Description
 * 26-08-2013		   Arihant Prasad			File header, indentation, comments and variable naming.
 * 27-03-2014		   Jevi V. G				Added description field for course_type		
 * 29-09-2014		   Jyoti                                Added CIA,TEE and CIA Ocassion field in view for course_type
 * 29-10-2014		   Shayista Mulla			Added curriculum component name column in the the table 
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
                            Course Type List
                        </div>
                    </div>	

                    <div class="row pull-right">   
                        <a href="<?php echo base_url('configuration/course_type/course_add_record'); ?>" align="right">
                            <button type="button" class="btn btn-primary"><i class="icon-plus-sign icon-white"> </i> Add </button>
                        </a>
                    </div>
                    <br><br>
                    <div id="example_wrapper" class="dataTables_wrapper" role="grid">
                        <table class="table table-bordered table-hover " id="example" aria-describedby="example_info">
                            <thead align = "center">
                                <tr class="gradeU even" role="row">
                                    <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" 	aria-sort="ascending" width="170px"> Curriculum Component</th>
                                    <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" width="300px"> Course Type </th>
                                    <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" width="600px"> Description </th>
                                    <th class="header"  style="width: 40px;" role="columnheader" tabindex="0" aria-controls="example"> Edit </th>
                                    <th class="header"  style="width: 50px;" role="columnheader" tabindex="0" aria-controls="example"> Delete </th>
                                </tr>
                            </thead>
                            <tbody role="alert" aria-live="polite" aria-relevant="all">
                                <?php foreach ($records as $records): ?>
                                    <tr>
                                        <td class="sorting_1 table-left-align">
                                            <?php foreach ($crclm_component_name_data as $list_item): ?>
                                                <?php if ($records['crclm_component_id'] == $list_item['cc_id']) { ?>
                                                    <?php echo $list_item['crclm_component_name']; ?> 
                                                <?php } ?>
                                            <?php endforeach; ?></td>
                                        <td class="sorting_1 table-left-align"><?php echo $records['crs_type_name']; ?></td>  
                                        <td class="sorting_1 table-left-align"><?php echo $records['crs_type_description']; ?></td>
                                        <td> <center><a class="" data-toggle="modal" data-original-title="Edit" rel="tooltip" title="Edit" href="<?php echo base_url('configuration/course_type/course_edit') . '/' . $records['crs_type_id']; ?>"><i class="icon-pencil icon-black"> </i></a></center></td>
                                <?php
                                if ($records['is_course_type'] == 0) {
                                    ?>
                                    <td>
                                        <div id="hint">
                                            <center>
                                                <a href = "#myModaldelete" id="<?php echo $records['crs_type_id']; ?>" class=" get_id icon-remove" data-toggle="modal" data-original-title="Delete" rel="tooltip" title="Delete" value="<?php echo $records['crs_type_id']; ?>">
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
                                                <a href = "#cant_delete" class=" get_id icon-remove" data-toggle="modal" data-original-title="Delete" rel="tooltip" title="Delete">
                                                </a>
                                            </center>
                                        </div>
                                    </td>
                                <?php } ?>

                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table><br><br><br>
                        <div class="pull-right">   
                            <a href="<?php echo base_url('configuration/course_type/course_add_record'); ?>" >
                                <button type="button" class="btn btn-primary"><i class="icon-plus-sign icon-white"> </i> Add </button>
                            </a>
                        </div><br><br>
                    </div>		

                    <!-- Modal to confirm before deleting a course type -->
                    <div id="myModaldelete" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true">
                        <div class="modal-header">
                            <div class="navbar-inner-custom">
                                Delete Confirmation
                            </div>
                        </div>
                        <div class="modal-body">
                            <p> Are you sure you want to Delete? </p>
                        </div>
                        <div class="modal-footer">
                            <button class="delete_course btn btn-primary " data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok </button>
                            <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel </button>
                        </div>
                    </div>

                    <div id="cant_delete" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: 	none;">
                        <div class="modal-header">
                            <div class="navbar-inner-custom">
                                Warning 
                            </div>
                        </div>
                        <div class="modal-body">
                            <p>You cannot delete this Course Type, as it is assigned to a Course. </p>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary" data-dismiss="modal"> <i class="icon-ok icon-white"></i> Ok</button>
                        </div>
                    </div>
                    <br>

                    <!--Do not place contents below this line-->	
                    </section>	
                </div>
        </div>
    </div>
</div>
<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<?php $this->load->view('includes/js'); ?>
<!---place js.php here -->
<script src="<?php echo base_url('twitterbootstrap/js/custom/configuration/course_type.js'); ?>" type="text/javascript"></script>

<!-- End of file course_list_vw.php 
                                Location: .configuration/standards/course_type/course_list_vw.php -->

