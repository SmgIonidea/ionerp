<?php
/** 
* Description	:	Approver's List View for TLO(Topic Learning Outcomes) to 
*					CO(Course Outcomes) Module. Selected Curriculum, Term, 
*					Course, Topic & its corresponding TLOs to CLOs mapping grid  
*					is displayed for approval process.
* Created		:	29-04-2013. 
* Modification History:
* Date				Modified By				Description
* 18-09-2013		Abhinay B.Angadi        Added file headers, indentations variable naming, 
*											function naming & Code cleaning.
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
                <?php $this->load->view('includes/sidenav_2'); ?>
                <div class="span10">
                    <!-- Contents -->
                    <section id="contents">
                        <div class="bs-docs-example">
                            <!--content goes here-->
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Mapping of <?php echo $this->lang->line('entity_tlo_full'); ?> (<?php echo $this->lang->line('entity_tlo'); ?>) to Course Outcomes (CO) <?php echo $this->lang->line('entity_topic'); ?>wise
                                </div>
                            </div>
                            <form class="form-horizontal">
                                <div class="row-fluid">
                                    <div class="span12">
                                        <div class="row-fluid">
                                            <table>
											<tr>
											<td>
                                                <p>Curriculum: <font color='red'>*</font>
													<select size="1" id="crclm" name="crclm" aria-controls="example" onChange = "select_term();">
														<option value="Curriculum" selected>Curriculum</option>
														<?php foreach ($results as $listitem): ?>
															<option value="<?php echo $listitem['crclm_id']; ?>"> <?php echo $listitem['crclm_name']; ?> </option>
														<?php endforeach; ?>
													</select> 
                                                </p>
											</td>
											<td>
                                                <p>Term: <font color='red'>*</font>
													<select size="1" id="term" name="term" aria-controls="example" onChange = "select_course();">
													</select> 
												</p>
											</td>
											<td>
												<p>Course: <font color='red'>*</font>
													<select size="1" id="course" name="course" aria-controls="example" onChange = "select_topic();">
													</select> 
												</p>
											</td>
											<td>
                                                <p><?php echo $this->lang->line('entity_topic'); ?>: 
													<select size="1" id="topic" name="topic" aria-controls="example" onChange = "func_grid();display_reviewer();">
													</select> 
												</p>
											</td>
											</tr>
											</table>	
                                            </div>
                                            <div id="table1" class="bs-docs-example span8" style="width: 775px;" >
                                            </div>
                                            <div class="span3">
                                                <div data-spy="scroll" class="bs-docs-example span3" style="width:260px; height:325px;">	
                                                    <div >
                                                        <p>  Course Outcomes (CO) </p>
                                                        <textarea id="text1" rows="5" cols="5" disabled>
                                                        </textarea>
                                                    </div>	
                                                    </br>
                                                    <div>
                                                        <p> <?php echo $this->lang->line('entity_tlo_full'); ?> (<?php echo $this->lang->line('entity_tlo'); ?>) </p>
                                                        <textarea id="text2" rows="5" cols="5" disabled>
                                                        </textarea>
                                                    </div>
                                                </div><!--span4 ends here-->
                                            </div></br>	</br> </br>
                                        </div><!--row-fluid ends here-->
                                        </form>			
                                        <div class="pull-right">
                                            <b class="btn btn-success" ><i class="icon-ok icon-white"></i> Accept </b>
                                            <b class="btn btn-danger" ><i class="icon-repeat icon-white"></i> Rework </b>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <!--Do not place contents below this line-->
                    </section>			
                </div>					
            </div>
        </div>
        <!---place footer.php here -->
        <?php $this->load->view('includes/footer'); ?> 
        <!---place js.php here -->
		<?php $this->load->view('includes/js'); ?>
    
<script type="text/javascript">
	function select_term()
	{
		var data_val = document.getElementById('crclm').value;
		var post_data = {
			'crclm_id': data_val
		}
		$.ajax({type: "POST",
			url: "<?php echo base_url('curriculum/tloclo_map_review/select_term'); ?>",
			data: post_data,
			success: function(msg) {
				document.getElementById('term').innerHTML = msg;
			}
		});
	}

	function select_course()
	{
		var data_val1 = document.getElementById('term').value;
		var post_data = {
			'term_id': data_val1
		}
		$.ajax({type: "POST",
			url: "<?php echo base_url('curriculum/tloclo_map_review/select_course'); ?>",
			data: post_data,
			success: function(msg) {
				document.getElementById('course').innerHTML = msg;
			}
		});
	}

	function select_topic()
	{
		var data_val = document.getElementById('course').value;
		var post_data = {
			'crs_id': data_val
		}
		$.ajax({type: "POST",
			url: "<?php echo base_url('curriculum/tloclo_map_review/select_topic'); ?>",
			data: post_data,
			success: function(msg) {
				document.getElementById('topic').innerHTML = msg;
			}
		});
	}

	function func_grid()
	{
		var data_val = document.getElementById('term').value;
		var data_val1 = document.getElementById('crclm').value;
		var data_val2 = document.getElementById('course').value;
		var data_val3 = document.getElementById('topic').value;
		var post_data = {
			'crclm_term_id': data_val,
			'crclm_id': data_val1,
			'crs_id': data_val2,
			'topic_id': data_val3,
		}
		$.ajax({type: "POST",
			url: "<?php echo base_url('curriculum/tloclo_map_review/tlo_details'); ?>",
			data: post_data,
			success: function(msg) {
				document.getElementById('table1').innerHTML = msg;
			}
		});
	}

	function writetext2(clo, tlo) {
		document.getElementById('text1').innerHTML = clo;
		document.getElementById('text2').innerHTML = tlo;
	}

	function uncheck() {
		//alert(globalid);
		$('#' + globalid).prop('checked', false);
	}

	function check() {
		$('#' + globalid).prop('checked', true);
	}

	$('.check').live("click", function() {
		if ($(this).is(':checked')) {
			document.getElementById(this.id).checked = false;
		}
		else {
			document.getElementById(this.id).checked = true;
		}
	});

//comments
	$('.comment').live('click', function() {
		$('a[rel=popover]').not(this).popover('destroy');
		$('a[rel=popover]').popover({
			html: 'true',
			placement: 'top'
		})
		$('.close_btn').live('click', function() {
			$('a[rel=popover]').not(this).popover('destroy');
		});
	});//]]
	$('.cmt_submit').live('click', function() {
		$('a[rel=popover]').not(this).popover('hide');
		var po_id = document.getElementById('tlo_id').value;
		var clo_id = document.getElementById('clo_id').value;
		var crclm_id = document.getElementById('crclm_id').value;
		var crs_id = document.getElementById('course').value;
		var clo_po_cmt = document.getElementById('clo_po_cmt').value;
		var post_data = {
			'po_id': po_id,
			'clo_id': clo_id,
			'crclm_id': crclm_id,
			'crs_id': crs_id,
			'clo_po_cmt': clo_po_cmt,
		}
		$.ajax({type: "POST",
			url: "<?php echo base_url('curriculum/cloadd/clo_po_cmt_insert'); ?>",
			data: post_data,
			success: function(msg) {
			}
		});
	});

	function review()
	{
		var data_val = document.getElementById('crclm').value;
		var post_data = {
			'crclm_id': data_val,
		}
		$.ajax({type: "POST",
			url: "<?php echo base_url('curriculum/tlo_clo_map/approve_details'); ?>",
			data: post_data,
			success: function(msg) {
				$('#myModal4').modal('show');
				//alert('Mapping has been sent for approval');
			}
		});
	}
</script>