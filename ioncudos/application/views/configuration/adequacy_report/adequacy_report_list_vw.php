<?php
/**
* Description           :	Model(Database) Logic for Course Module(Add).
* Created		:	09-04-2013. 
* Modification History:
* Date				Modified By                 Description
* 28-11-2014                    Jevi V. G.              Added file headers, function headers, indentations & comments.

-------------------------------------------------------------------------------------------------
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
                <?php $this->load->view('includes/sidenav_1'); ?>
                <div class="span10">
                    <!-- Contents
    ================================================== -->
                    <section id="contents">
                        <div class="bs-docs-example">
                            <!--content goes here-->	
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Adequacy Report List
                                </div>
                            </div>	
                           
                            <br><br>
                            <div id="example_wrapper" class="dataTables_wrapper" role="grid">
                                <table class="table table-bordered table-hover " id="example" aria-describedby="example_info">
                                    <thead align = "center">
                                        <tr class="gradeU even" role="row">
                                            <th class="header headerSortDown" style="width: 60px;" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" > Sl. No.</th>
                                            <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >Report Name </th>
                                            <th class="header" style="width: 120px;" role="columnheader" tabindex="0" aria-controls="example" align="center" ><center></center>Manage Reports</th>
										</tr>
                                    </thead>
									<?php $i=1; ?>
                                    <tbody role="alert" aria-live="polite" aria-relevant="all">
                                        <?php foreach ($records as $records): ?>
                                            <tr>
												<td class="sorting_1 table-left-align"><?php echo $i;?></td>
                                                <td class="sorting_1 table-left-align"><?php echo $records['report_name']; ?>
												</td>  
												
                                                <td> <a class="" href="<?php echo base_url('configuration/adequacy_report/adequacy_add_edit') . '/' . $records['report_id']; ?>">
                                                        Add/Edit</a>
												</td>                                         
                                            </tr>
											
                                        <?php $i++; endforeach; ?>
										
                                    </tbody>
                                </table><br><br><br>
                               <br><br>
                            </div><br>
                        </div>
                        			
                        <!--Do not place contents below this line-->	
                    </section>
                </div>
            </div>
        </div>
        <!---place footer.php here -->
        <?php $this->load->view('includes/footer'); ?> 
        <?php $this->load->view('includes/js'); ?>
        <!---place js.php here -->
	