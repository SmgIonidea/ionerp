<?php
//  Note:there should be defferent Department for each program type id or program type
/**
 * Description          :   View page for Facilities and Technical Support	
 * Created              :   14th June, 2016
 * Author               :   Bhagyalaxmi Shivapuji
 * Modification History :
 *   Date                   Modified By                     Description
 * 25-06-2017               Shayista Mulla                Fixed the issues ,added comments and Added new modules as per NBA SAR report for pharmacy
  ---------------------------------------------------------------------------------------------- */
?>

<!--head here -->
<?php $this->load->view('includes/head'); ?>
<style>
    input::-moz-placeholder {
        text-align: left;
    }
    input::-webkit-input-placeholder {
        text-align: left;
    }
</style>
<!--branding here-->
<?php $this->load->view('includes/branding'); ?>
<!-- Navbar here -->
<?php $this->load->view('includes/navbar'); ?>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/tinymce/tinymce.min.js') ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.noty.css'); ?>" media="screen"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/noty_theme_default.css'); ?>" media="screen" />
<div class="container-fluid">
    <div class="row-fluid">
        <!--sidenav.php-->
        <?php $this->load->view('includes/sidenav_6'); ?>
        <div class="span10">
            <div id="loading" class="ui-widget-overlay ui-front">
                <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
            </div>
            <!-- Contents -->
            <section id="contents">
                <div class="bs-docs-example  fixed-height">
                    <!-- content goes here -->
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            <?php echo "Facilities and Technical Support" ?> 
                        </div>
                    </div>							
                    <div class="row-fluid">								
                        <table>
                            <tr>
                                <td>
                                    <label>
                                        Department:<font color='red'>*</font> 
                                        <select id="department" name="department" autofocus = "autofocus" class="input-large" >
                                            <option value="">Select Department</option>
                                            <?php foreach ($dept_result as $listitem) { ?>
                                                <option value="<?php echo htmlentities($listitem['dept_id']); ?>" data-pgm_type_id="<?php echo $listitem['pgm_type_id'] ?>"> <?php echo $listitem['dept_name']; ?></option>
                                            <?php } ?>
                                        </select> <?php echo str_repeat('&nbsp;', 8); ?>
                                    </label>
                                </td>										
                            </tr>
                        </table>
                    </div>
                    <div id="details"></div>
            </section>	
            <div id="Exist" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="invalid_file_extension" data-backdrop="static" data-keyboard="false"></br>
                <div class="container-fluid">
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Warning
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <p>Please select department </p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok </button> 
                </div>
            </div>	
            <div id="delete_confirm" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal4" data-backdrop="static" data-keyboard="false"></br>
                <div class="container-fluid">
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Delete Confirmation
                        </div>
                    </div>
                </div>
                <div class="modal-body">									
                    <p>Are you sure that you want to delete?</p>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-primary" data-dismiss="modal" id="delete_lab"><i class="icon-ok icon-white"></i> Ok </a> 
                    <button class="btn btn-danger" data-dismiss="modal" ><i class="icon-remove icon-white"></i> Cancel </button> 
                </div>
            </div>
            <div id="delete_confirm_adequate" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal4" data-backdrop="static" data-keyboard="false"></br>
                <div class="container-fluid">
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Delete Confirmation
                        </div>
                    </div>
                </div>
                <div class="modal-body">									
                    Are you sure that you want to delete?
                </div>
                <div class="modal-footer">
                    <a class="btn btn-primary" data-dismiss="modal" id="delete_adequate"><i class="icon-ok icon-white"></i> Ok </a> 
                    <button class="btn btn-danger" data-dismiss="modal" ><i class="icon-remove icon-white"></i> Close </button> 
                </div>
            </div>
            <div id="delete_modal" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal4" data-backdrop="static" data-keyboard="false"></br>
                <div class="container-fluid">
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Delete Confirmation
                        </div>
                    </div>
                </div>
                <div class="modal-body">									
                    Are you sure that you want to delete?
                </div>
                <div class="modal-footer">
                    <a class="btn btn-primary" data-dismiss="modal" id="delete_sel"><i class="icon-ok icon-white"></i> Ok </a> 
                    <button class="btn btn-danger" data-dismiss="modal" ><i class="icon-remove icon-white"></i> Close </button> 
                </div>
            </div>
        </div>
    </div>
</div>
<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<!---place js.php here -->
<?php $this->load->view('includes/js'); ?>
<script src="<?php echo base_url('twitterbootstrap/js/custom/nba_sar/facilities_and_tecnhical_support.js'); ?>" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.noty.js'); ?>"></script>
<!-- End of file facilities_and_technical_support_vw.php 
        Location: .nba_sar/modules/facilities_and_technical_support/facilities_and_technical_support_vw.php  -->
