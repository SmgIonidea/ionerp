<?php
/**
* Description	:	Administrator's Static List View for Course Domain Module.
* Created		:	07-06-2013.. 
* Modification History:
* Date				Modified By				Description
* 10-09-2013		Abhinay B.Angadi        Added file headers, indentations.
* 11-09-2013		Abhinay B.Angadi		Variable naming, Function naming & Code cleaning.
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
                <?php $this->load->view('includes/static_sidenav_2'); ?>
                <div class="span10">
                    <!-- Contents
    ================================================== -->
                    <section id="contents">
                        <div class="bs-docs-example">
							<?php $this->load->view('includes/crclm_tab'); ?>
                            <!--content goes here-->	
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Course Domain List
                                </div>
                            </div>	
                            <div id="example_wrapper" class="dataTables_wrapper container-fluid" role="grid">
                                <table class="table table-bordered table-hover " id="example" aria-describedby="example_info">
                                    <thead align = "center">
                                        <tr role="row">
                                            <th class="header span2" role="columnheader" tabindex="0" aria-controls="example">Course Domain Name</th>
                                            <th class="header span3" role="columnheader" tabindex="0" aria-controls="example">Department</th>
                                            <th class="header span3" role="columnheader" tabindex="0" aria-controls="example">Course Domain Description</th>
                                        </tr>
                                   </thead>
                                    <tbody role="alert" aria-live="polite" aria-relevant="all">
                                        <?php foreach ($records as $records): ?>
                                            <tr>
                                                <td class="sorting_1 table-left-align"><?php echo $records['crs_domain_name']; ?></td>  
                                                <td class="sorting_1 table-left-align"><?php echo $records['dept_acronym']; ?></td>  
                                                <td class="sorting_1 table-left-align"><?php echo $records['crs_domain_description']; ?></td>  
                                            </tr>
                                        <?php endforeach; ?>	
                                    </tbody>
                                </table>
                            </div>
                            <br>
                           <!--Do not place contents below this line-->	
                    </section>	
                </div>
            </div>
        </div>
        <!---place footer.php here -->
        <?php $this->load->view('includes/footer'); ?> 
        <?php $this->load->view('includes/js'); ?>
        <!---place js.php here -->