<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Topic list view page, provides the fecility to view the Topic Contents.
 * Modification History:
 * Date				Modified By				Description
 * 05-09-2013		Mritunjay B S       	Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */
?>
<!DOCTYPE html>
<html lang="en">
    <!--head here -->
    <?php $this->load->view('includes/head'); ?>
    <body data-spy="scroll" data-target=".bs-docs-sidebar">
        <!--branding here-->
        <?php $this->load->view('includes/branding'); ?>
        <!-- Navbar here -->
        <?php $this->load->view('includes/navbar'); ?> 
        <div class="container-fluid">
            <div class="row-fluid">
                <!--sidenav.php-->
                <?php $this->load->view('includes/sidenav_2'); ?>
                <div class="span10">
                    <!-- Contents
            ================================================== -->
                    <section id="contents">
                        <div class="bs-docs-example fixed-height" >
                            <!--content goes here-->
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    <a class="brand-custom" name= "topic_title_heading" id="topic_title_heading" type="text" style="text-decoration: none"> <?php echo $this->lang->line('entity_topic'); ?> List along with Add <?php echo $this->lang->line('entity_tlo_full'); ?> (<?php echo $this->lang->line('entity_tlo'); ?>) Link</a>
                                </div>
                            </div><br>
                            <form name="add_form" id="add_form" method="post" action="<?php echo base_url('curriculum/topicadd'); ?>" class="form-inline">
                                <fieldset>
                                    <!-- Form Name -->
                                    <!-- Select Basic -->
                                    <div class="control-group ">
                                        <label class="control-label">Curriculum<font color="red">*</font>:</label>
                                        <select id="curriculum" name="curriculum" class="input-xlarge span3" onchange="generate_checkbox();" onLoad="select_term();">
                                            <option value="">Curriculum</option>
                                            <?php foreach ($crclm_name_data as $listitem): ?>
                                                <option value="<?php echo $listitem['crclm_id']; ?>"> <?php echo $listitem['crclm_name']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
									<div id="entity_data">
									
									</div>
                                    <!-- Select Basic -->
                                </fieldset>
                        </div>
                </div>
            </div>
        </div>
</form>
<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<!---place js.php here -->
<?php $this->load->view('includes/js'); ?>

</body>
<script type="text/javascript">
var base_url = $('#get_base_url').val();
function generate_checkbox()
{
var crclm_id = $('#curriculum').val();
 var post_data = {
        'curriculum_id': crclm_id
    }
    $.ajax({type: "POST",
        url: base_url + 'curriculum/import_curriculum/import_entity_list', 
        data: post_data,
        success: function(msg) {
		//$('#entity_data').innerHTML = msg;
		document.getElementById('entity_data').innerHTML = msg;
				
				}
        
    });
alert (crclm_id);
}
</script>
</html>



