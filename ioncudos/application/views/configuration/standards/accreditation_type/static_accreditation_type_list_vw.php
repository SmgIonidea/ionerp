<?php
/**
* Description	:	Static (read only) List View for Accreditation Type Module.
* Created		:	03-04-2013. 
* Modification History:
* Date				Modified By				Description
* 03-09-2013		Abhinay B.Angadi        Added file headers, indentations.
* 03-09-2013		Abhinay B.Angadi		Variable naming, Function naming & Code cleaning.
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
                <?php $this->load->view('includes/static_sidenav_1'); ?>
                <div class="span10">
                    <!-- Contents
    ================================================== -->
                    <section id="contents">
                        <div class="bs-docs-example">
                            <!--content goes here-->	
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    <?php echo $this->lang->line('student_outcomes_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?> Type List
                                </div>
                            </div>
                            <div id="example_wrapper" class="dataTables_wrapper" role="grid">
                                <table class="table table-bordered table-hover " id="example" aria-describedby="example_info">
                                    <thead >
                                        <tr class="gradeU even" role="row">
                                            <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending"> <?php echo $this->lang->line('student_outcomes_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?> Type
											</th>
											<th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >Description </th>
                                        </tr>
                                    </thead>
									<tbody role="alert" aria-live="polite" aria-relevant="all">
                                        <?php foreach ($records as $records): ?>
                                            <tr>
                                                <td class="sorting_1 table-left-align"><?php echo $records['po_type_name']; ?>
												</td>  
												<td class="sorting_1 table-left-align"><?php echo $records['po_type_description'];?></td>
                                            </tr>
										<?php endforeach; ?>	
                                    </tbody>
                                </table>
                                <br>
                            </div>	
                            <!-- Modal -->
                            <br><br>
                            <!--Do not place contents below this line-->	
                            </section>	
                        </div>
                </div>
            </div>
            <!---place footer.php here -->
            <?php $this->load->view('includes/footer'); ?> 
            <!---place js.php here -->
			<?php $this->load->view('includes/js'); ?>