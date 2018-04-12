<?php
/**
* Description	:	Static (read only) List View for User Designation Module.
* Created		:	03-04-2013. 
* Modification History:
* Date				Modified By				Description
* 22-08-2013		Abhinay B.Angadi        Added file headers, indentations.
* 29-08-2013		Abhinay B.Angadi		Variable naming, Function naming &
*											Code cleaning.
--------------------------------------------------------------------------------
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
                <?php $this->load->view('includes/static_sidenav_1'); ?>
                <div class="span10">
                    <!-- Contents
    ================================================== -->
                    <section id="contents">
                        <div class="bs-docs-example">
                            <!--content goes here-->	
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Designation List
                                </div>
                            </div>	
                            <div id="example_wrapper" class="dataTables_wrapper" role="grid">
                                <table class="table table-bordered table-hover " id="example" aria-describedby="example_info">
                                    <thead align = "center">
                                        <tr class="gradeU even" role="row">
                                            <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >Designation 
											</th>
                                        </tr>
                                    </thead>
                                    <tbody role="alert" aria-live="polite" aria-relevant="all">
                                        <?php foreach ($records as $records): ?>
                                            <tr>
												<td class="sorting_1 table-left-align"><?php echo $records['designation_name']; ?>
												</td>  
											</tr>
                                        <?php endforeach; ?>	
									</tbody>
                                </table><br><br><br>
                                <br>
                            </div>
                            <br>
                        </div>
                        <!-- Modal -->
                    </section>
                </div>
            </div>
        </div>
        <!---place footer.php here -->
        <?php $this->load->view('includes/footer'); ?> 
        <?php $this->load->view('includes/js'); ?>
        <!---place js.php here -->