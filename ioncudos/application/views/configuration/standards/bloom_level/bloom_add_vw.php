<?php
/**
 * Description	:	To add new Bloom's level, level of learning, characteristics of learning and 
  bloom action verb.
 * 					
 * Created		:	25-03-2013
 *
 * Author		:	
 * 		  
 * Modification History:
 *   Date                Modified By                			Description
 * 27-08-2013		   Arihant Prasad			File header, indentation, comments and variable naming.
 *
  ------------------------------------------------------------------------------------------------- */
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
        <?php $this->load->view('includes/sidenav_1'); ?>
        <div class="span10">
            <!-- Contents -->
            <section id="contents">
                <div class="bs-docs-example fixed-height">
                    <!--content goes here-->
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Add Bloom's Level
                        </div>
                    </div><br>
                    <form class="form-horizontal" method="POST" id="form" name="form" action= "<?php echo base_url('configuration/bloom_level/bloom_insert_record'); ?>">
                        <div class="control-group">
                            <p class="control-label"> Bloom's Domain: <font color="red"> * </font></p>
                            <div class="controls">
                                <select id="add_bloom_domain" name="add_bloom_domain" autofocus = "autofocus" class="required">
                                    <option value=""> Select Bloom Domain</option>
                                    <?php foreach ($bloom_domain_list as $list_item): ?>
                                        <option value="<?php echo $list_item['bld_id']; ?>"> <?php echo $list_item['bld_name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="span5">
                                <div class="control-group">
                                    <p class="control-label"> Bloom's Level: <font color="red"> * </font></p>
                                    <div class="controls">
                                        <?php echo form_input($level); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="span5">

                                <div class="control-group">
                                    <p class="control-label"> Level Of Learning: <font color="red"> * </font></p>
                                    <div class="controls"> 
                                        <?php echo form_input($learning); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="control-group">
                            <p class="control-label"> Characteristics Of Learning: <font color="red"> * </font></p>
                            <div class="controls"> 
                                <?php echo form_textarea($description); ?>
                                <br/> <span id='char_span_support' class='margin-left5'>0 of 2000. </span>
                            </div>
                        </div>
                        <div class="control-group">
                            <p class="control-label"> Bloom's Action Verbs: <font color="red"> * </font></p>
                            <div class="controls">
                                <?php echo form_textarea($bloom_actionverbs); ?>
                                <br/> <span id='char_span_support1' class='margin-left5'>0 of 2000. </span>
                            </div>
                        </div>

                        <div class="control-group">
                            <p class=""><b>Note:</b> Enter The Action Verbs as comma(,) separated values. <b>Example:</b> List, Identify, Outline.</p>
                        </div>
                        <br><br>
                        <input type="hidden" id ="array_counter" name="array_counter" value="1"/>
                        <!-- Modal to display the uniqueness message -->
                        <div id="myModal_warning" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_warning" data-backdrop="static" data-keyboard="false">
                            </br>
                            <div class="container-fluid">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        Uniqueness Warning 
                                    </div>
                                </div>
                            </div>

                            <div class="modal-body" id="comments">
                                <p> Bloom's Level with this Level Name already Exists. </p>
                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-primary close1" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok </button>
                            </div>
                        </div>
                        <div class="pull-right">       
                            <button class="submit1 btn btn-primary" type="submit"><i class="icon-file icon-white"></i> Save </button>
                            <button class="btn btn-info" type="reset"><i class="icon-refresh icon-white"></i> Reset </button>
                    </form>
                    <a href= "<?php echo base_url('configuration/bloom_level'); ?>" class="btn btn-danger"><i class="icon-remove icon-white"></i> Cancel</a>
                </div>
                <br>
                <!--Do not place contents below this line-->	
                </div>
            </section>
        </div>
    </div>
</div>
<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<!---place js.php here -->
<?php $this->load->view('includes/js'); ?>
<script>
//function to keep Add Bloom's domain selected. 
    if ($.cookie('remember_bloom_domain') != null) {
        // set the option to selected that corresponds to what the cookie is set to
        $('#add_bloom_domain option[value="' + $.cookie('remember_bloom_domain') + '"]').prop('selected', true);
    }
</script>
<script src="<?php echo base_url('twitterbootstrap/js/custom/configuration/blooms_level_add.js'); ?>" type="text/javascript"></script>

<!-- End of file bloom_add_vw.php 
                        Location: .configuration/standards/bloom_level/bloom_add_vw.php -->