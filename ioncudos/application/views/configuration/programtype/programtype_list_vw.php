<?php
/**
 * Description          :	List View for Program Type Module.
 * Created		:	25-03-2013. 
 * Modification History:
 * Date				Modified By			Description
 * 20-08-2013                  Abhinay B.Angadi         Added file headers & indentations.
 * 27-08-2013                  Abhinay B.Angadi		Variable naming, Function naming & Code cleaning.
 * 											
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
            <!-- Contents
            ================================================== -->
            <section id="contents">
                <div class="bs-docs-example">
                    <!--content goes here-->	
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Program Type List
                        </div>
                    </div>
                    <div class="row pull-right">   
                        <a href="<?php echo base_url('configuration/programtype/program_type_add_record'); ?>">
                            <button type="button" class="btn btn-primary pull-right"><i class="icon-plus-sign icon-white"> </i> Add</button>
                        </a> 
                    </div><br><br>
                    <div id="example_wrapper" class="dataTables_wrapper" role="grid">
                        <table class="table table-bordered table-hover " id="example" aria-describedby="example_info">
                            <thead align = "center">
                                <tr class="gradeU even" role="row">
                                    <th class="header headerSortDown" style="width: 100px;" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >Program Type</th>
                                    <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >Description</th>
                                    <th class="header" rowspan="1" colspan="1" style="width: 40px;" role="columnheader" tabindex="0" aria-controls="example" align="center" ><center></center>Edit</th>
                            <th class="header" rowspan="1" colspan="1" style="width: 50px;" role="columnheader" tabindex="0" aria-controls="example" align="center" ><center></center>Status</th>
                            </tr>
                            </thead>
                            <tbody role="alert" aria-live="polite" aria-relevant="all">
                                <?php foreach ($records as $records): ?>
                                    <tr><td class="sorting_1 table-left-align"><?php echo $records['pgmtype_name']; ?></td> 
                                        <td class="sorting_1 table-left-align"><?php echo $records['pgmtype_description']; ?></td>
                                        <td><center><a class="" href="<?php echo base_url('configuration/programtype/program_type_edit_record') . '/' . $records['pgmtype_id']; ?>">
                                        <i class="icon-pencil icon-black"> </i></a></center>
                                </td>
                                <?php if ($records['status'] == 0) { ?>                                      
                                    <td align="center" class="sorting_1"><center></center><div id="hint"><center><a  href="#enable_dialog" rel="tooltip" title="Enable" role="button"  data-toggle="modal"  id="<?php echo $records['pgmtype_id']; ?>" onclick="javascript:currentIDSt(<?php echo $records['pgmtype_id']; ?>);"><i class="icon-ok-circle"></i> </a></center></div>
                                    </td>
                                <?php } else {
                                    ?>
                                    <td align="center" class="sorting_1"><center></center><div id="hint"><center><a class="disable_pgmtype" rel="tooltip" title="Disable" role="button"  data-toggle="modal" id="<?php echo $records['pgmtype_id']; ?>" onclick="javascript:currentIDSt(<?php echo $records['pgmtype_id']; ?>);"><i class="icon-ban-circle icon-red"></i></a></center></div>
                                    </td>
                                <?php } ?>
                                </tr>
                            <?php endforeach; ?>	
                            </tbody>
                        </table><br><br><br>
                        <div class="row pull-right">   
                            <a href="<?php echo base_url('configuration/programtype/program_type_add_record'); ?>">
                                <button type="button" class="btn btn-primary pull-right"><i class="icon-plus-sign icon-white"> </i> Add</button>
                            </a> 
                        </div><br><br><br>
                    </div><br>
                    <!-- Modal -->
                    <div id="enable_dialog" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="enable_dialog" data-backdrop="static" data-keyboard="true">
                        <div class="modal-header">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Enable Confirmation
                                </div>
                            </div>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to Enable?</p>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" Onclick="javascript:enable();"><i class="icon-ok icon-white"></i> Ok</button>
                            <button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                        </div>
                    </div>
                    <div id="disable_dialog" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="disable_dialogLabel" aria-hidden="true" style="display: none;" data-controls-modal="disable_dialog" data-backdrop="static" data-keyboard="true">
                        <div class="modal-header">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Disable Confirmation 
                                </div>
                            </div>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to Disable?</p>
                        </div>						
                        <div class="modal-footer">
                            <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" Onclick="javascript:disable();"><i class="icon-ok icon-white"></i> Ok</button>
                            <button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                        </div>
                    </div>
                    <div id="cannot_disable_dialog" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="cannot_disable_dialog" data-backdrop="static" data-keyboard="true">
                        <div class="modal-header">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Warning 
                                </div>
                            </div>
                        </div>
                        <div class="modal-body">
                            <p>You cannot disable this Program Type, as there are Programs currently running under this Program Type.</p>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" ><i class="icon-ok icon-white"></i> Ok</button>
                        </div>
                    </div><br>

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
<script src="<?php echo base_url('twitterbootstrap/js/custom/configuration/program_type.js'); ?>" type="text/javascript"></script>
