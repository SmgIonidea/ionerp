<?php
/**
 * Description          :	List View for Accreditation Type Module.
 * Created		:	03-04-2013. 
 * Modification History:
 * Date				Modified By				Description
 * 03-09-2013               Abhinay B.Angadi            Added file headers, indentations.
 * 03-09-2013               Abhinay B.Angadi		Variable naming, Function naming & Code cleaning.
  --------------------------------------------------------------------------------------------------------
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
                            List of Accreditation Types & their Generic <?php echo $this->lang->line('student_outcomes_full'); ?> (<?php echo $this->lang->line('sos'); ?>)
                        </div>
                    </div>	
                    <div class="row pull-right">   
                        <a href="<?php echo base_url('configuration/accreditation_type/accreditation_add_record'); ?>" align="right">
                            <button type="button" class="btn btn-primary"><i class="icon-plus-sign icon-white"> 
                                </i> Add</button>
                        </a>
                    </div>
                    <br><br>
                    <div id="example_wrapper" class="dataTables_wrapper" role="grid">
                        <table class="table table-bordered table-hover " id="example" aria-describedby="example_info">
                            <thead >
                                <tr class="gradeU even" role="row">
                                    <th class="header headerSortDown" style="width: 120px;" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending">Accreditation Type</th>
                                    <th class="header headerSortDown" style="width: 90px;" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >Description </th>
                                    <!--<th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >Granted To </th>-->
                                    <th class="header headerSortDown" style="width: 75px;" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >List of POs</th>
                                    <th class="header" style="width: 35px;" role="columnheader" tabindex="0" aria-controls="example">Edit
                                    </th>
                                    <th class="header" style="width: 45px;" role="columnheader" tabindex="0" aria-controls="example">Delete
                                    </th>
                                </tr>
                            </thead>
                            <tbody role="alert" aria-live="polite" aria-relevant="all">
                                <?php foreach ($records as $records): ?>
                                    <tr>
                                        <td class="sorting_1 table-left-align"><?php echo $records['atype_name']; ?>
                                        </td>  
                                        <td class="sorting_1 table-left-align"><?php echo $records['atype_description']; ?></td>
                                        <!--<td class="sorting_1 table-left-align"><?php echo $records['alias_entity_name']; ?>
                                        </td> -->
                                        <td> 
                                            <a data-toggle="modal" href="#myModal_accreditation_type_details_list" class="norap get_accreditation_type_details" id="<?php echo $records['atype_id']; ?>"> View <?php echo $this->lang->line('sos'); ?></a>
                                        </td> 
                                        <td> <center><a class="" href="<?php echo base_url('configuration/accreditation_type/accreditation_type_edit_record') . '/' . $records['atype_id']; ?>">
                                        <i class="icon-pencil icon-black"> </i></a></center></td>


                                <td>
                                    <div id="hint">
                                        <center>
                                            <a href = "#myModaldelete" id="<?php echo $records['atype_id']; ?>"class="get_id icon-remove" data-toggle="modal" data-original-title="Delete" rel="tooltip" 
                                               title="Delete" value="<?php echo $records['atype_id']; ?>">
                                            </a>
                                        </center>
                                    </div>
                                </td>

                                </tr>
                            <?php endforeach; ?>	
                            </tbody>
                        </table>
                        <br><br><br>
                        <div class="pull-right">   
                            <a href="<?php echo base_url('configuration/accreditation_type/accreditation_add_record'); ?>" >
                                <button type="button" class="btn btn-primary"><i class="icon-plus-sign icon-white"> 
                                    </i> Add</button>
                            </a>
                        </div><br>	
                    </div>	
                    <!-- Modal -->
                    <div id="myModaldelete" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                        <div class="modal-header">
                            <div class="navbar-inner-custom">
                                Delete Accreditation Type Confirmation 
                            </div>
                        </div>		
                        <div class="modal-body">
                            <p>Are you sure you want to Delete this Accreditation Type ? <p>The associated Accreditation Type details will also be deleted along with the Accreditation Type. Press Ok if you want to proceed ?
                        </div>
                        <div class="modal-footer">
                            <button class="delete_accreditation_type btn btn-primary "  data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok</button>
                            <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                        </div>
                    </div>

                    <!-- Modal to display send for myModal_accreditation_type_details_list message -->
                    <div id="myModal_accreditation_type_details_list" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_accreditation_type_details_list" data-backdrop="static" data-keyboard="true">
                        <div class="modal-header">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Generic <?php echo $this->lang->line('student_outcomes_full'); ?> (<?php echo $this->lang->line('sos'); ?>) List
                                </div>
                            </div>
                        </div>
                        <div class="modal-body" id="accreditation_type_details_list">
                        </div>				
                        <div class="modal-footer">
                            <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Close </button>	
                        </div>
                    </div>

                    <!--<div id="cant_delete" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: 	none;">
<div class="modal-header">
<div class="navbar-inner-custom">
    Warning 
</div>
</div>
<div class="modal-body">
<p>You cannot delete this Accreditation Type. As there are some sample Program Outcomes (POs) defined keeping this as their references. </p>
</div>
<div class="modal-footer">
<button class="btn btn-danger" data-dismiss="modal"> <i class="icon-remove icon-white"></i> Close</button>
</div>
</div>-->

                    <br>			


                    <!--Do not place contents below this line-->	
                    </section>	
                </div>
        </div>
    </div>
    <!---place footer.php here -->
    <?php $this->load->view('includes/footer'); ?> 
    <!---place js.php here -->
    <?php $this->load->view('includes/js'); ?>
    <script src="<?php echo base_url('twitterbootstrap/js/custom/configuration/accreditation_type.js'); ?>" type="text/javascript"></script>