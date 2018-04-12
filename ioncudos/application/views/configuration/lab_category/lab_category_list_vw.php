<?php
/**
 * Description          :	List View for lab category Module.
 * Created		:	07-10-2015  
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
                            Lab Category List
                        </div>
                    </div>
                    <div class="row pull-right">   
                        <a href="<?php echo base_url('configuration/lab_category/lab_category_add_record'); ?>"><button type="button" class="btn btn-primary pull-right"><i class="icon-plus-sign icon-white"> </i> Add</button>
                        </a> 
                    </div><br><br>
                    <div id="example_wrapper" class="dataTables_wrapper" role="grid">
                        <table class="table table-bordered table-hover " id="example" aria-describedby="example_info">
                            <thead align = "center">
                                <tr class="gradeU even" role="row">
                                    <th class="header headerSortDown" style="width: 50px;" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >Sl No.</th>
                                    <th class="header headerSortDown" style="width: 130px;" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >Categories</th>
                                    <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >Description</th>
                                    <th class="header" rowspan="1" colspan="1" style="width: 40px;" role="columnheader" tabindex="0" aria-controls="example" align="center" ><center></center>Edit</th>
                            <th class="header" rowspan="1" colspan="1" style="width: 50px;" role="columnheader" tabindex="0" aria-controls="example" align="center" ><center></center>Delete</th>
                            </tr>
                            </thead>
                            <tbody role="alert" aria-live="polite" aria-relevant="all">
                                <?php $slNo = 0; ?>
                                <?php foreach ($records as $records): ?>
                                    <tr><td class="sorting_1" style="text-align:right;"><?php echo ++$slNo ?></td>
                                        <td class="sorting_1 table-left-align"><?php echo $records['mt_details_name']; ?></td> 
                                        <td class="sorting_1 table-left-align"><?php echo $records['mt_details_name_desc']; ?></td>
                                        <td><center><a class="" href="<?php echo base_url('configuration/lab_category/lab_category_edit_record') . '/' . $records['mt_details_id']; ?>">
                                        <i class="icon-pencil icon-black"> </i></a></center>
                                </td>
                                <td><center><a href="" rel="tooltip" title="Enable" role="button"  data-toggle="modal"onclick="javascript:deleteRecord(<?php echo $records['mt_details_id']; ?>);" >
                                        <i class=" get_id icon-remove"> </i></a></center></a>
                                </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table><br><br><br>
                        <div class="row pull-right">   
                            <a href="<?php echo base_url('configuration/lab_category/lab_category_add_record'); ?>">
                                <button type="button" class="btn btn-primary pull-right"><i class="icon-plus-sign icon-white"> </i> Add</button>
                            </a> 
                        </div><br><br><br>

                        <!--Modal for delete conformation-->
                        <div id="enable_dialog" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="enable_dialog" data-backdrop="static" data-keyboard="true">
                            <div class="modal-header">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        Delete Confirmation
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="storeId" id="storeId" value=""/>
                                <p>Are you sure that you want to Delete?</p>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" Onclick="javascript:deleteLabCategory();"><i class="icon-ok icon-white"></i> Ok</button>
                                <button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                            </div>
                        </div>

                        <!--Modal for cannot delete warning-->
                        <div id="enable_dialog1" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="enable_dialog1" data-backdrop="static" data-keyboard="true">
                            <div class="modal-header">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        Warning
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body">
                                <p>You cannot delete this Lab Category, as experiments are associated with it.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="reset" class="cancel btn btn-primary" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok</button>
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
<script src="<?php echo base_url('twitterbootstrap/js/custom/configuration/lab_category.js'); ?>" type="text/javascript"></script>
