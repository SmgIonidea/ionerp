<?php
/**
 * Description          :   View for NBA SAR Report - Section 3.1 (TIER 1) - Course CO to PO mapping tables.
 * Created              :   28-04-2017	
 * Author               :   Shayista Mulla
 * Modification History :
 * Date                     Modified by                      Description
  --------------------------------------------------------------------------------------------------------------- */
?>
<div class="row-fluid">
    <div class="span12 cl">
        <b><?php echo $this->lang->line('entity_clo_full')?> (<?php echo $this->lang->line('entity_clo')?>) - <?php echo $this->lang->line('student_outcomes_full'); ?> (<?php echo $this->lang->line('sos'); ?>) matrices :</b>
    </div>
</div>
<div class="row-fluid" id="co_po_vw">
    <?php echo $without_pso; ?>
</div>

<div class="row-fluid">
    <div class="span12 cl">
        <b><?php echo $this->lang->line('entity_clo_full')?> (<?php echo $this->lang->line('entity_clo')?>) - <?php echo $this->lang->line('entity_psos_full')?> (<?php echo $this->lang->line('entity_psos')?>) matrices :</b>
    </div>
</div>
<div class="row-fluid" id="co_po_vw">
    <?php echo $with_pso; ?>
</div>
<!-- End of file t1ug_c3_1_course_co_po_vw.php 
        Location: .nba_sar/ug/tier1/criterion_3/t1ug_c3_1_course_co_po_vw.php -->