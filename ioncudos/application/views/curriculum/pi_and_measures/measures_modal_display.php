<?php
/**
 * Description	:	Approved Program Outcome grid along with its corresponding Performance Indicators
  and Measures

 * Created		:	March 30th, 2013

 * Author		:	 

 * Modification History:
 *   Date                Modified By                         Description
 * 11-01-2014		    Arihant Prasad			File header, function headers, indentation 
  and comments.
  ---------------------------------------------------------------------------------------------- */
?>

<div class='control-group'>
    <textarea class="required input-xxlarge readonly valid" col="40" row="2" readonly="readonly" name="pi_statement[]" id="pi_statement_1" type="text" style="margin: 0px; width: 80%; height: 45px;"><?php echo $static_pi_id[0]['pi_statement']; ?></textarea>

    <a id='msr_edit_field' class='btn btn-primary pull-right'><i class='icon-plus-sign icon-white'></i> Add More PIs </a>

</div>

<?php
$msr_data_count[] = array();
$msr_cloneCntr = 1;
$size = sizeof($msr_data);
$msr_cloneCntr1 = '';

if ($size != 0) {
    foreach ($msr_data as $msr_item) {
        $msr_cloneCntr1 .= $msr_cloneCntr . ',';
        ?>
        <!-- For adding or editing (existing) Performance Indicators  -->
        <form class='form-horizontal' id='msr_edit' method='POST' action='<?php echo base_url('curriculum/pi_and_measures/insert_measures'); ?>'>
            <?php
            $msr_statement['value'] = $msr_item['msr_statement'];
            $msr_id['value'] = $msr_item['msr_id'];
            $msr_statement['id'] = 'msr_statement' . $msr_cloneCntr;
            $msr_statement['name'] = 'msr_statement' . $msr_cloneCntr;
            $msr_statement['abbr'] = $msr_cloneCntr;
            $msr_statement['style'] = 'margin: 0px; width: 544px; height: 50px;';
            $msr_statement['class'] = 'required edit_msr loginRegex_one msr_ele_count';
            $msr_statement['autofocus'] = 'autofocus';
            ?>
            <input type="hidden" id="static_pi_id" name="static_pi_id" value="<?php echo $static_pi_id[0]['pi_id']; ?>">

            <div id='add_msr'>
                <div id='msr_remove'>
                    <div id='msr_statement_div' class='bs-docs-example span3' style='width:815px; height:120px;'>
                        <p class='control-label pull-left' for='msr_statement'> Performance Indicator: <font color='red'> * </font></p>

                        <div class='controls msr_clone' id='msr_clone'>
                            <?php echo form_textarea($msr_statement); ?> &nbsp; &nbsp;

                            <input type='hidden' value='<?php echo $msr_item['msr_id']; ?>' name='msr_id[]'>

                            <a id='msr_remove_field<?php echo $msr_cloneCntr; ?>' class='msr_Delete' href='#'><i class='icon-remove'></i></a>
                        </div>
                    </div><br/><br/><br/><br/><br/><br/>
                </div>	
            </div>
            <?php
            array_push($msr_data_count, $msr_cloneCntr);
            $msr_cloneCntr++;
            ?>
        <?php } ?>
        <div id="msr_add_before">
        </div>

        <br><br>
        <div class="row">
            <button type="submit" id="save_pi" name="save_pi" class="btn btn-success pull-right save_measures" style="margin-right: 2px;"><i class="icon-file icon-white"></i> Save PIs</button>
        </div>
        <input type="hidden" name="add_edit_more_msr_counter" id="add_edit_more_msr_counter" value=""/>
    </form>
<?php } else { ?>
    <!-- For adding new PIs -->
    <form class="form-horizontal" id="msr_edit" method="POST" action="<?php echo base_url('curriculum/pi_and_measures/insert_measures'); ?>">
        <?php
        $msr_statement['id'] = 'msr_statement1';
        $msr_statement['name'] = 'msr_statement1';
        $msr_statement['abbr'] = '1';
        $msr_statement['style'] = 'margin: 0px; width: 80%; height: 50px;';
        $msr_statement['class'] = 'required edit_msr loginRegex_one msr_ele_count';
        $msr_statement['autofocus'] = 'autofocus';
        ?>
        <input type="hidden" id="static_pi_id" name="static_pi_id" value="<?php echo $static_pi_id[0]['pi_id']; ?>">

        <div id='add_msr'>
            <div id='msr_remove'>
                <div id='msr_statement_div' class='bs-docs-example span3' style='width:100%; height:120px;'>
                    <p class='control-label pull-left' for='msr_statement'> Performance Indicator: <font color='red'> * </font></p>

                    <div class='controls msr_clone' id='msr_clone'>
                        <?php echo form_textarea($msr_statement); ?> &nbsp; &nbsp;

                        <a id='msr_remove_field<?php echo $msr_cloneCntr; ?>' class='msr_Delete' href='#'><i class='icon-remove'></i></a>
                    </div>
                </div><br/><br/><br/><br/><br/>
            </div>	
        </div>

        <div id='msr_add_before'>
        </div>

        <br><br>
        <div class="row">
            <button type="submit" id="save_pi" name="save_pi" class="btn btn-success pull-right save_measures" style="margin-right: 2px;"><i class="icon-file icon-white"></i> Save PIs</button>
        </div>
        <input type="hidden" name="add_more_msr_counter" id="add_more_msr_counter" value=""/>
    </form>
<?php } ?>
