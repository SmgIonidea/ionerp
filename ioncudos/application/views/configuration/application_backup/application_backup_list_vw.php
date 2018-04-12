<?php
/**
 * Description          :	View page for Apllication backup Module.
 * Created              :	28-03-2017
 * Author               :	Shayista Mulla  
 * Modification History :
 * Date                  	Modified by                      Description
  --------------------------------------------------------------------------------------------------------------- */
?>
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
                <div id="loading" class="ui-widget-overlay ui-front">
                    <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
                </div>
                <div class="bs-docs-example">
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Application Data Backup List
                        </div>
                    </div>
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td>
                                    <b>Steps:</b>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    1) Click on <font color="#8E2727"><b>"Data Backup"</b></font> button to download data in the backend.
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    2) Click on <font color="#8E2727"><b>"Download"</b></font> link in the table to download the data to your local system.
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    3) For <font color="#8E2727"><b>"Level 3"</b></font> support, share the data downloaded to your local system with the IonCUDOS team.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="pull-right">
                        <button id="backup" name="backup" class="btn btn-success" type="button"><i class="icon-download-alt icon-white"></i> Data Backup</button>
                    </div><br/><br/>
                    <?php
                    $dir = './db_backup/';
                    $files = scandir($dir);
                    $files = array_slice($files, 2);
                    ?>
                    <table class="table table-bordered table-hover" id="backup_list_table" aria-describedby="example_info" style="width:100%;overflow:auto;">
                        <thead align = "center">
                            <tr class="gradeU even" role="row">
                                <th class="header" tabindex="0" aria-controls="example"  style="width: 10px;">Sl No.</th>
                                <th class="header" tabindex="0" aria-controls="example"  style="width: 50px;">File Name</th>
                                <th class="header" tabindex="0" aria-controls="example"  style="width: 80px;">Download</th>
                                <th class="header" tabindex="0" aria-controls="example"  style="width: 40px;">Date & Time</th>
                                <th class="header" tabindex="0" aria-controls="example"  style="width: 40px;">Delete</th>
                            </tr>
                        </thead>
                        <tbody aria-live="polite" aria-relevant="all">
                            <?php
                            $sl_no = 1;
                            foreach ($files as $file) {
                                ?>
                                <tr>
                                    <td style="text-align:right"><?php echo $sl_no++; ?></td>
                                    <td><?php echo $file; ?></td>
                                    <td title="Click to download the file."><a href="<?php echo base_url() . 'db_backup/' . $file ?>"><i class="icon-download-alt"></i> Download</a></td>
                                    <td><?php $date_time = explode("_", $file);
                            echo $date_time[1] . '  ' . str_replace("-", ":", substr($date_time[2], 0, 8)); ?></td>
                                    <td><center>
                                <a role="button" data-toggle="modal" href="#myModal_delete" class="icon-remove get_file_name" data-file="<?php echo $file; ?>"></a>
                            </center></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table><br/><br/>
                    <div class="modal hide fade in" id="myModal_delete" role="dialog" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="false" style="display: none;" data-controls-modal="myModal_help" data-backdrop="static" data-keyboard="true">
                        <div class="modal-header">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Delete Confirmation
                                </div>
                            </div>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to Delete?
                        </div>
                        <div class="modal-footer">
                            <button type="button" data-dismiss="modal" class="btn btn-danger btn-sm pull-right modal_close" style="margin-right: 2px;"><i class="icon-remove icon-white"></i> Cancel</button>
                            <button type="button" id="delete_selected" class="btn btn-primary btn-sm pull-right" style="margin-right: 2px;"><i class="icon-ok icon-white"></i> Ok</button>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <input type="hidden" name="file_name" id="file_name"/>
</div>
<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<!---place js.php here -->
<?php $this->load->view('includes/js'); ?>
<script src="<?php echo base_url('twitterbootstrap/js/custom/configuration/application_backup.js'); ?>" type="text/javascript"></script>
<!-- End of file application_backup_list_vw.php 
                        Location: .configuration/application_setting/application_backup_list_vw.php  -->