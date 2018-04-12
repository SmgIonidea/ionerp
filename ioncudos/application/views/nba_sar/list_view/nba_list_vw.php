<?php
/**
 * Description          :	View for NBA SAR Report list.
 * Created              :	3-8-2015
 * Author               :
 * Modification History :
 * Date	                        Modified by                      Description
 * 10-8-2015                    Jevi V. G.              Added file headers, public function headers, indentations & comments.
 * 25-5-2016                    Arihant Prasad          Identation and rework
  --------------------------------------------------------------------------------------------------------------- */
?>
<!DOCTYPE html>
<html lang="en">
        <?php
        $CI = & get_instance();
        $CI->load->library('Encryption');
        $this->load->view('includes/head');
        ?>
        <body data-spy="scroll" data-target=".bs-docs-sidebar">
                <!--branding here-->
                <?php $this->load->view('includes/branding'); ?>
                <!-- Navbar here -->
                <?php $this->load->view('includes/navbar'); ?> 
                <div class="container-fluid">
                        <div class="row-fluid">
                                <!--sidenav.php-->
                                <?php $this->load->view('includes/sidenav_6'); ?>
                                <div class="span10">
                                        <!-- Contents -->
                                        <div class="bs-docs-example">
                                                <div class="navbar">
                                                        <div class="navbar-inner-custom">
                                                                NBA - SAR Report
                                                        </div>
                                                </div>
                                                <div class="row">
                                                        <a href="<?php echo base_url('nba_sar/nba_list/add_nba_details'); ?>" class="btn btn-primary pull-right"><i class="icon-plus-sign icon-white"></i> Add </a>
                                                </div><br />
                                                <div id="example_wrapper" class="dataTables_wrapper" role="grid" style="margin-bottom: 88px">
                                                        <table class="table table-bordered table-hover" id="example" aria-describedby="example_info">
                                                                <thead>
                                                                        <tr role="row">
                                                                                <th class="header span2" role="columnheader" tabindex="0" aria-controls="example"> Department </th>
                                                                                <th class="header span1" role="columnheader" tabindex="0" aria-controls="example"> Program </th>
                                                                                <th class="header headerSortDown span1" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending"> Report </th>
                                                                                <th class="header" rowspan="1" colspan="1" style="width: 60px;" role="columnheader" tabindex="0" aria-controls="example" align="center" ><center></center> Delete </th>
                                                                </tr>
                                                                </thead>
                                                                <tbody role="alert" aria-live="polite" aria-relevant="all">
                                                                        <?php foreach ($nba_data as $details) { ?>
                                                                                <tr class="gradeU even">
                                                                                        <td class="sorting_1"><?php echo $details['dept_name']; ?> </td>
                                                                                        <td class="sorting_1"><?php echo $details['pgm_title']; ?> </td>
                                                                                        <td class="sorting_1 table-left-align"><a class="nba_report" abbr="<?php echo $CI->encryption->encode($details['nba_id']); ?>" href="#">	<?php echo 'NBA Report'; ?> </a></td>
                                                                                        <td>
                                                                                                <div id="hint">
                                                                                                        <center>
                                                                                                                <a href = "#myModaldelete" id="<?php echo $details['nba_id']; ?>" class="get_id icon-remove" data-toggle="modal" data-original-title="Delete" rel="tooltip" title="Delete" value="<?php echo $details['nba_id']; ?>">
                                                                                                                </a>
                                                                                                        </center>
                                                                                                </div>
                                                                                        </td>
                                                                                </tr>
                                                                        <?php } ?>
                                                                </tbody>
                                                        </table>                  
                                                        <br><br><br>
                                                        <div class="row pull-right">
                                                                <a href="<?php echo base_url('nba_sar/nba_list/add_nba_details'); ?>" class="btn btn-primary pull-right"><i class="icon-plus-sign icon-white"></i> Add</a>
                                                        </div><br><br>
                                                </div>
                                                <!-- Delete Modal -->
                                                <div id="myModaldelete" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                        <div class="modal-header">
                                                                <div class="navbar-inner-custom">
                                                                        Delete Confirmation 
                                                                </div>
                                                        </div>
                                                        <div class="modal-body">
                                                                <p>Are you sure you want to Delete?</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                                <button class="delete_nba btn btn-primary "  data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok</button>
                                                                <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                                                        </div>
                                                </div>
                                        </div>
                                </div>
                        </div>
                </div>
                <form id="node_form" method="post" action="<?php echo base_url(); ?>nba_sar/nba_sar" >
                        <input type="hidden" name="node_id" id="node_id" value="" />
                </form>
                <!---place footer.php here -->
                <?php $this->load->view('includes/footer'); ?> 
                <!---place js.php here -->
                <?php $this->load->view('includes/js'); ?>
        </body>
        <script language="javascript" type="text/javascript" src="<?php echo base_url('assets/nba_js/nba_list.js'); ?>"></script>
</html>
<!-- End of file nba_list_vw.php 
        Location: .nba_sar/list_view/nba_list_vw.php -->




