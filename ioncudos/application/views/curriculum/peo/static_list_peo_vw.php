<?php
/**
 * Description	:	PEO grid displays the PEO statements and PEO type.
					PEO statements can be deleted individually and can be edited in bulk.
					Notes can be added, edited or viewed.
					PEO statements will be published for final approval.
 * 					
 * Created		:	02-04-2013
 *
 * Author		:	
 * 		  
 * Modification History:
 *    Date               Modified By                		Description
 *  05-09-2013		   Arihant Prasad			File header, function headers, indentation 
												and comments.
 *
  ---------------------------------------------------------------------------------------------- */
?>

    <!--head here -->
    <?php $this->load->view('includes/head'); ?>
        <!--branding here-->
        <?php $this->load->view('includes/branding'); ?>

        <!-- Navbar here -->
        <?php $this->load->view('includes/navbar'); ?>
        <div class="container-fluid">
            <div class="row-fluid">
                <!-- sidenav.php -->
                <?php $this->load->view('includes/static_sidenav_2'); ?>
                <div class="span10">
                    <!-- Contents -->
                    <section id="contents">
                        <div class="bs-docs-example fixed-height" >
                            <!-- content goes here -->
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Program Educational Objectives (PEO) List
                                </div>
                            </div>
                            <div>
                                <label>
                                    Curriculum <font color="red"> * </font>
                                    <select size="1" id="crclm" name="crclm" aria-controls="example" onchange="static_grid(); current_state();">
                                        <option value="" selected> Select the Curriculum </option>
                                        <?php foreach ($results as $list_item): ?>
                                            <option value="<?php echo $list_item['crclm_id']; ?>"> <?php echo $list_item['crclm_name']; ?> </option>
                                        <?php endforeach; ?>
                                    </select>
                                </label>
                            </div>
							<h4><center><b id="peo_current_state"></b></center></h4>
                            <div>
                                <div>
                                    <table class="table table-bordered table-hover" id="example" aria-describedby="example_info">
                                        <thead>
                                            <tr role="row">
                                                <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending"> Program Educational Objectives(PEO) 
												</th>
                                            </tr>
                                        </thead>
                                        <tbody id="peolist">
                                            <!--refer list_peo_table_vw page-->
                                        </tbody>
                                    </table>
                                </div>
                                </br></br>

                                <!-- Modal to display help content -->
                                <div id="help" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true">
                                    <div class="modal-header">
                                        <div class="navbar">
                                            <div class="navbar-inner-custom">
                                                Guidelines for Program Educational Objectives(PEO)
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-body" id="help_content">

                                    </div>

                                    <div class="modal-footer">
                                        <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Close </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>

        <!---place footer.php here -->
        <?php $this->load->view('includes/footer'); ?>
        <!---place js.php here -->
        <?php $this->load->view('includes/js'); ?>
	<script src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/peo_static.js'); ?>" type="text/javascript"> </script>
	
	
<script language="javascript">

</script>

<!-- End of file static_list_peo_vw.php 
                        Location: .curriculum/peo/static_list_peo_vw.php -->