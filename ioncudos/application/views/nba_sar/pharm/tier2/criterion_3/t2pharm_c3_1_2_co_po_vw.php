<?php
/**
 * Description          :   View for NBA SAR Report - Section 3.1.2 (Pharmacy TIER 2) - CO - PO matrices of courses selected in 3.1.1
 * Created              :   05-06-2017
 * Author               :   Shayista Mulla
 * Modification History :
 * Date                     Modified by                      Description
  --------------------------------------------------------------------------------------------------------------- */
?>
<div class="row-fluid">
    <div class="span12 cl">
        <b><?php echo $this->lang->line('entity_clo_singular'); ?> - <?php echo $this->lang->line('so'); ?>  matrices :</b>
    </div>
</div>
<div class="row-fluid" id="co_po_vw">
    <?php echo $without_pso; ?>
</div>

<div class="row-fluid">
    <div class="span12 cl">
        <b><?php echo $this->lang->line('entity_clo_singular'); ?> - <?php echo $this->lang->line('so'); ?> matrices with <?php echo $this->lang->line('entity_pso'); ?> :</b>
    </div>
</div>
<div class="row-fluid" id="co_po_vw">
    <?php echo $with_pso; ?>
</div>
<!-- End of file t2pharm_c3_1_2_co_po_vw.php 
        Location: .nba_sar/pharm/tier2/criterion_3/t2pharm_c3_1_2_co_po_vw.php -->