<?php
/**
 * Description          :	List View for Assessment Method Module.
 * Created		:	14-08-2014. 
 * Author		:	Jyoti
 * Modification History:
 * Date				Modified By				Description
 * 
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
        <?php $this->load->view('includes/sidenav_1'); ?>
        <div class="span10">
            <!-- Contents
================================================== -->
            <section id="contents">
                <div class="bs-docs-example fixed-height"">
                    <!--content goes here-->	
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Assessment Method List
                        </div>
                    </div>	
                    <div class="row pull-right">   
                        <button id="add_ao_mtd1"  disabled class="btn btn-primary pull-right add_ao_mtd" ><i class="icon-plus-sign icon-white"></i> Add  </button>
                    </div>
                    <div>
                        <label>
                            Program Title:<font color="red"> * </font>
                            <select size="1" id="pgmid" name="pgmid" autofocus = "autofocus" aria-controls="example" style="width:35%;" onchange="select_pgm_list();">
                                <option value="0" selected>Select Program Title</option>
                                <?php foreach ($results as $list_item): ?>
                                    <option value="<?php echo $list_item['pgm_id']; ?>"> <?php echo $list_item['pgm_title']; ?> </option>
                                <?php endforeach; ?>
                            </select>									
                        </label>
                        <input type="hidden" name="pgm_id_h" id="pgm_id_h" value="" />
                    </div><br>

                    <div id="example_wrapper" class="dataTables_wrapper" role="grid">
                        <table class="table table-bordered table-hover " id="example" aria-describedby="example_info">
                            <thead >
                                <tr class="gradeU even" role="row">
                                    <th class="header headerSortDown" style ="width: 50px;" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >Sl No.</th>
                                    <th class="header headerSortDown" style ="width: 240px;" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending">Assessment Method Name</th>
                                    <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >Description </th>
                                    <th class="header headerSortDown" style ="width: 70px;" role="columnheader" tabindex="0" aria-controls="example">Rubrics </th>
                                    <th class="header" style ="width: 40px;" role="columnheader" tabindex="0" aria-controls="example">Edit</th>
                                    <th class="header" style ="width: 50px;" role="columnheader" tabindex="0" aria-controls="example">Delete
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="">

                            </tbody>
                        </table>
                        <br><br><br>
                        <div class="pull-right">   
                            <button id="add_ao_mtd2"  disabled class="btn btn-primary pull-right add_ao_mtd" ><i class="icon-plus-sign icon-white"></i> Add  </button>
                        </div><br>	
                    </div>	
                    <!-- Modal -->
                    <div id="myModaldelete" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                        <div class="modal-header">
                            <div class="navbar-inner-custom">
                                Delete Confirmation 
                            </div>
                        </div>		
                        <div class="modal-body">
                            <p>Are you sure you want to Delete?</p>
                        </div>
                        <div class="modal-footer">
                            <button class="delete_ao_method btn btn-primary "  data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok</button>
                            <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                        </div>
                    </div>
                    <div id="cant_delete" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: 	none;">
                        <div class="modal-header">
                            <div class="navbar-inner-custom">
                                Warning 
                            </div>
                        </div>
                        <div class="modal-body">
                            <p>You cannot delete this Assessment Method, as it is refered under Course Assessment. </p>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-danger" data-dismiss="modal"> <i class="icon-remove icon-white"></i> Close</button>
                        </div>
                    </div>
                    <div id="rubrics_modal" style="width:50%;"class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-header">
                            <div class="navbar-inner-custom">
                                Assessment Method & Rubrics
                            </div>
                        </div>
                        <div class="modal-body">
                            <div id="rubrics_data"></div>
                        </div>
                        <div class="modal-footer">
                            <button id="cancel" class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i>Close</button>
                        </div>
                    </div>
                    <br>			

                    <!--Do not place contents below this line-->	
                    </section>	
                </div>
        </div>
    </div>
    <!---place footer.php here -->
    <?php $this->load->view('includes/footer'); ?> 
    <!---place js.php here -->
    <?php $this->load->view('includes/js'); ?>
    <script src="<?php echo base_url('twitterbootstrap/js/custom/configuration/ao_method.js'); ?>" type="text/javascript"></script>
