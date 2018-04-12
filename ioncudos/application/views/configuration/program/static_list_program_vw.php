<?php
/*
--------------------------------------------------------------------------------------------------------------------------------
* Description	: Program List Static view Page.	  
* Modification History:
* Date				Modified By				Description
* 20-08-2013                    Mritunjay B S                           Added file headers, function headers & comments. 
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
			<?php $this->load->view('includes/static_sidenav_1'); ?>
                <div class="span10">
                    <!-- Contents
                    ================================================== -->
                    <section id="contents">
                        <div class="bs-docs-example fixed-height" >
                            <!--content goes here-->
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Program List
                                </div>
                            </div>	
                            <br>
                            <br/>
                            <div>
                                <div id="example_wrapper" class="dataTables_wrapper" role="grid">
                                    <table class="table table-bordered table-hover" id="example" aria-describedby="example_info">
                                        <thead>
                                            <tr role="row">
                                                <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >Program Title</th>
                                                <th class="header" role="columnheader" tabindex="0" aria-controls="example">Department</th>
                                                <th class="header" role="columnheader" tabindex="0" aria-controls="example">Minimum Duration(years)</th>
                                                <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Credits</th>
                                                <th class="header" role="columnheader" tabindex="0" aria-controls="example">Mode</th>
                                                <th class="header span1">Details</th>
                                            </tr>
                                        </thead>
                                        <tbody role="alert" aria-live="polite" aria-relevant="all">
                                            <?php foreach ($pgm_list_data_result as $data_value): ?>
                                                <tr class="gradeU even">
                                                    <td class="sorting_1"><?php echo $data_value['pgm_title']; ?></td>
                                                    <td><?php echo $data_value['dept_acronym']; ?></td>
                                                    <td><?php echo $data_value['pgm_min_duration']; ?></td>
                                                    <td><?php echo $data_value['total_credits']; ?></td>
                                                    <td><?php echo $data_value['mode_name']; ?>
                                                    <td><center><?php echo anchor("configuration/program/static_program_edit" . "/" . $data_value['pgm_id'], '<i class="icon-list"></i>', 'title="Details"'); ?> </center></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                </br></br>
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
</html>
<script>
$("#hint a").tooltip();
</script>