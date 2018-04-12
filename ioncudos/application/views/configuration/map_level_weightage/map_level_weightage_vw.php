<?php
/**
 * Description		:	Generate or edit map_level_weightage of Faculty

 * Created		:	01/09/2015

 * Author		:	 Bhagyalaxmi Shivapuji

 * Modification History:
 *   Date                	Modified By                         Description
 * 20-10-2016			Neha Kulkarni		Changed the error message statement.
  ------------------------------------------------------------------------------------------ */
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
                <div class="bs-docs-example">
                    <!--content goes here-->
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Define Map Level Weightage Distribution
                        </div><font color="red"><b><div id="etotal"></div></b></font><br/>
                        <table id="expense_table" class="table table-bordered table-hover " id="example" aria-describedby="example_info">
                            <thead aria-live="polite" aria-relevant="all">
                                <tr role="row">
                                    <th class="header" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending">Sl No.</th>
                                    <th class="header" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending">Map Level <font color="red"><b>*</b></font></th>
                                    <th class="header" role="columnheader" tabindex="0" aria-controls="">Map Level Acronym <font color="red"><b>*</b></font></th>
                                    <th  class="header" role="columnheader" tabindex="0" aria-controls="">Status</font></th>               
                                    <th  class="header" role="columnheader" tabindex="0" aria-controls="">Weightage in % </font></th>
                                </tr>
                            </thead>
                            <tbody role="alert" aria-live="polite" aria-relevant="all">

                                <?php
                                $i = 1;
                                foreach ($val['value'] as $topic) {
                                    ?>
                                    <tr id="tog">
                                        <td style="text-align:right;"><?php echo $i; ?></td>
                                        <td><input type="text" id="map_level_name" name="map_level_name[]" class=" required max_marks" value="<?php echo $topic['map_level_name_alias']; ?>"/></td>
                                        <td><input type="text" id="priority" name="priority[]" class="input-mini required max_marks" value="<?php echo $topic ['map_level_short_form']; ?>"/></td>
                                        <td><input type="checkbox"   class="chk"  <?php if ($topic['status'] == '1') echo 'checked="checked"'; ?>  id="status[]" name="status[]" class="input-mini required max_marks" /></td>
                                        <td><input   style="text-align: right;"  type="text" id="weightage"  name="weightage[]" class="input-mini required max_marks weight" value="<?php echo $topic['map_level_weightage']; ?>"   <?php if ($topic['status'] == '0') { ?> readonly <?php } ?> /><span>%</span></td>

                                        </td></tr><?php
                                    $i++;
                                }
                                ?>
                                <tr><td style="text-align: right" colspan="4"><b>Total Weightage :</b></td>
                                    <td><input type="text" id="total" name="total" class="input-mini required max_marks" value="<?php echo $val['total']; ?>"  readonly /><span>%</span></td></td></tr>
                            </tbody>

                        </table> 
                        <div class="row-fluid">
                            <div class="span12">									
                                <button type="button" name="update_map_level" id="update_map_level" class="btn btn-primary pull-right"><i class="icon-file icon-white"></i> Update</button>

                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

<div id="myModal_suc" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal4" data-backdrop="static" data-keyboard="false">
    <div class="modal-header">
        <div class="navbar">
            <div class="navbar-inner-custom">
                Update Confirmation
            </div>
        </div>
    </div>
    <div class="modal-body">									
        The Map Level Weightage Distribution data is updated successfully.</font>
    </div>

    <div class="modal-footer">
        <a class="btn btn-primary" id="ok" data-dismiss="modal" ><i class="icon-ok icon-white"></i> Ok </a> 

    </div>
</div>

<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<!---place js.php here -->
<?php $this->load->view('includes/js'); ?>
<script src="<?php echo base_url('twitterbootstrap/js/custom/configuration/map_level_weightage.js'); ?>"></script>
