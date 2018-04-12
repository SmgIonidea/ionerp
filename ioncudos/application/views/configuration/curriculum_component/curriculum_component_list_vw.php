<?php
/**
 * Description          :	List View for curriculum component Module.
 * Created		:	22-10-2015  
 * Author		:	Shayista Mulla
 * Modification History:
 * Date				Modified By				Description
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
            <!-- Contents  -->
            <section id="contents">
                <div class="bs-docs-example">
                    <!--content goes here-->	
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Curriculum Component List
                        </div>
                    </div>
                    <div class="row pull-right">   
                        <a href="<?php echo base_url('configuration/curriculum_component/curriculum_component_add_record'); ?>"><button type="button" class="btn btn-primary pull-right"><i class="icon-plus-sign icon-white"> </i> Add</button>
                        </a> 
                    </div><br><br>
                    <div id="example_wrapper" class="dataTables_wrapper" role="grid">
                        <table class="table table-bordered table-hover " id="example" aria-describedby="example_info">
                            <thead align = "center">
                                <tr class="gradeU even" role="row">
                                    <th class="header headerSortDown" style="width: 40px;" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >Sl No.</th>
                                    <th class="header headerSortDown" style="width: 120px;" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >Curriculum Component</th>
                                    <th class="header headerSortDown span3" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >Description</th>
                                    <th class="header" rowspan="1" colspan="1" style="width: 30px;" role="columnheader" tabindex="0" aria-controls="example" align="center" ><center></center>Edit</th>
                            <th class="header" rowspan="1" colspan="1" style="width: 40px;" role="columnheader" tabindex="0" aria-controls="example" align="center" ><center></center>Delete</th>
                            </tr>
                            </thead>
                            <tbody role="alert" aria-live="polite" aria-relevant="all">
                                <?php $slNo = 0; ?>
                                <?php foreach ($records as $records): ?>
                                    <tr><td class="sorting_1 table-left-align"><?php echo ++$slNo ?></td>
                                        <td class="sorting_1 table-left-align"><?php echo $records['crclm_component_name']; ?></td> 
                                        <td class="sorting_1 table-left-align"><?php echo $records['crclm_component_desc']; ?></td>
                                        <td><center><a class="" href="<?php echo base_url('configuration/curriculum_component/curriculum_component_edit_record') . '/' . $records['cc_id']; ?>">
                                        <i class="icon-pencil icon-black"> </i></a></center>
                                </td>
                                <td><center><a href="#enable_dialog" rel="tooltip" title="Delete" role="button"  data-toggle="modal"onclick="javascript:storeId(<?php echo $records['cc_id']; ?>);" >
                                        <i class=" get_id icon-remove"> </i></a></a></center>
                                </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table><br><br><br>
                        <div class="row pull-right">   
                            <a href="<?php echo base_url('configuration/curriculum_component/curriculum_component_add_record'); ?>">
                                <button type="button" class="btn btn-primary pull-right"><i class="icon-plus-sign icon-white"> </i> Add</button>
                            </a> 
                        </div><br><br><br>
                        <div id="enable_dialog" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="enable_dialog" data-backdrop="static" data-keyboard="true">
                            <div class="modal-header">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        Delete Confirmation
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to Delete?</p>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" Onclick="javascript:deleteRecord();"><i class="icon-ok icon-white"></i> Ok</button>
                                <button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                            </div>
                        </div>

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
<script src="<?php echo base_url('twitterbootstrap/js/custom/configuration/curriculum_component.js'); ?>" type="text/javascript"></script>
