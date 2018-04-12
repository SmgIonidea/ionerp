<?php
/*
  ---------------------------------------------------------------------------------------------------------------------------------
 * Description	: Program Add view Page.	  
 * Modification History:
 * Date				Modified By								Description
 * 20-08-2013        Mritunjay B S                           Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
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
                <div class="bs-docs-example fixed-height" >
                    <!--content goes here-->
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Program List
                        </div>
                    </div>

                    <?php if ($get_sanp == 1) { ?>
                        <div class="row">
                            <a  class="btn btn-primary pull-right" href="<?php echo base_url('configuration/program/prgram_add_view'); ?>"><i class="icon-plus-sign icon-white"></i> Add</a>
                        </div>
                    <?php } else { ?>
                        <div class="pull-right">
                            <button class="btn btn-primary" href="#" onclick="progs();">
                                <i class="icon-plus-sign icon-white"></i> Add</button>
                        </div><br>	
                    <?php } ?>

                    <br>
                    <div>
                        <div>
                            <table class="table table-bordered table-hover" id="example" aria-describedby="example_info">
                                <thead>
                                    <tr role="row">
                                        <th class="header headerSortDown" style="width: 260px;" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >Program Title</th>
                                        <th class="header"  style="width: 100px;" role="columnheader" tabindex="0" aria-controls="example" >Program Acronym</th>
                                        <th class="header"  style="width: 70px;" role="columnheader" tabindex="0" aria-controls="example" >No. of Terms</th>
                                        <th class="header"  style="width: 60px;" role="columnheader" tabindex="0" aria-controls="example" >Department</th>
                                        <th class="header"  style="width: 50px;" role="columnheader" tabindex="0" aria-controls="example" >Minimum Duration(Years)</th>
                                        <th class="header"  style="width: 50px;" role="columnheader" tabindex="0" aria-controls="example" >Total <?php echo $this->lang->line('credits'); ?> </th>
                                        <th class="header" style="width: 50px;" role="columnheader" tabindex="0" aria-controls="example" >Mode</th>
                                        <th class="header" style="width: 30px;">Edit</th>
                                        <th class="header" style="width: 40px;">Status</th>
                                    </tr>
                                </thead>
                                <tbody role="alert" aria-live="polite" aria-relevant="all">
                                    <?php foreach ($pgm_list_data_result as $data_value): ?>
                                        <tr class="gradeU even">
                                            <td class="sorting_1"><?php echo $data_value['pgm_title']; ?></td>
                                            <td><?php echo $data_value['pgm_acronym']; ?></td>
                                            <td style="text-align:right;"><?php echo $data_value['total_terms']; ?></td>
                                            <td ><?php echo $data_value['dept_acronym']; ?></td>
                                            <td style="text-align:right;"><?php echo $data_value['pgm_min_duration']; ?></td>
                                            <td style="text-align:right;"><?php echo $data_value['total_credits']; ?></td>
                                            <td><?php echo $data_value['mode_name']; ?></td>
                                            <td><center><?php echo anchor("configuration/program/program_edit" . "/" . $data_value['pgm_id'], '<i class="icon-pencil"></i>', 'title="Edit"'); ?> </center></td>

                                    <?php
                                    if ($data_value['cur_status'] == 0) {
                                        if ($data_value['status'] == 0) {
                                            ?>
                                            <td>
                                                <div id="hint">
                                                    <center><a href="#myModal" rel="tooltip" title="Enable" data-toggle="modal" class="enable_pgm icon-ok-circle" data-original-title="enable"  id="pgm" value="<?php echo $data_value['pgm_id']; ?>" onclick="javascript:currentIDSt(<?php echo $data_value['pgm_id']; ?>);"></a></center>
                                                </div>
                                            </td>
                                        <?php } else { ?>
                                            <td>
                                                <div id="hint">
                                                    <center><a href="#myModal1" rel="tooltip" title="Disable" data-toggle="modal" class="disable_pgm icon-ban-circle" data-original-title="disable"  id="pgm" value="<?php echo $data_value['pgm_id']; ?>" onclick="javascript:currentIDSt(<?php echo $data_value['pgm_id']; ?>);"></a></center>
                                                </div>
                                            </td>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <td>
                                            <div id="hint">
                                                <center><a href="#myModal2" data-toggle="modal"  rel="tooltip" title="Disable" class="disable_pgm icon-ban-circle" data-original-title="enable"  id="pgm" value="<?php echo $data_value['pgm_id']; ?>" onclick="javascript:currentIDSt(<?php echo $data_value['pgm_id']; ?>);"></a></center>
                                            </div>
                                        </td>
                                    <?php } ?>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table><br><br><br>

                            <?php if ($get_sanp == 1) { ?>
                                <div class="row">
                                    <a  class="btn btn-primary pull-right" href="<?php echo base_url('configuration/program/prgram_add_view'); ?>"><i class="icon-plus-sign icon-white"></i> Add</a>
                                </div>
                            <?php } else { ?>
                                <div class="pull-right">
                                    <button class="btn btn-primary" href="#" onclick="progs();">
                                        <i class="icon-plus-sign icon-white"></i> Add</button>
                                </div><br>	
                            <?php } ?>
                        </div><br><br>

                        <!--Modal-->
                        <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
                            <div class="modal-header">
                                <div class="navbar-inner-custom">
                                    Enable Confirmation
                                </div>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to Enable ?</p>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" Onclick="javascript:enable(); location.reload();"><i class="icon-ok icon-white"></i> Ok</button>
                                <button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                            </div>
                        </div>

                        <div id="myModal1" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
                            <div class="modal-header">
                                <div class="navbar-inner-custom">
                                    Disable Confirmation
                                </div>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to Disable ?</p>
                            </div>						
                            <div class="modal-footer">
                                <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" Onclick="javascript:disable(); location.reload();"><i class="icon-ok icon-white"></i> Ok</button>
                                <button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                            </div>
                        </div>

                        <div id="myModal2" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
                            <div class="modal-header">
                                <div class="navbar-inner-custom">
                                    Warning
                                </div>
                            </div>
                            <div class="modal-body">
                                <p> You cannot disable this Program, as there are Curricula currently running under this Program.</p>
                            </div>						
                            <div class="modal-footer">
                                <button class="btn btn-primary"  data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok </button>
                            </div>
                        </div>

                        <!-- Modal to display saved outcome elements & performance indicators -->
                        <div id="myModal_progs" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_progs" data-backdrop="static" data-keyboard="true"></br>
                            <div class="container-fluid">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        Limit Exceeded
                                    </div>
                                </div>
                            </div>

                            <div class="modal-body" id="selected_pm_modal">
                                Exceeded add Program <span class="badge badge-important"> limit </span>. To add more Programs contact IonCUDOS Team.
                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button> 
                            </div>
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
</body>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/custom/configuration/program.js'); ?>"></script>
</html>