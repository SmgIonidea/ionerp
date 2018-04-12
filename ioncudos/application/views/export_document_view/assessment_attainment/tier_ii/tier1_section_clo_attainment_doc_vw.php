<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//print_r($ajax_result['co_attainment_note']);exit;

echo $ajax_result['page_title_detail'].'<br>';

echo $ajax_result['selected_elements'].'<br>';

echo '<h4 class="ul_class" style="text-align:center; font-size:14px;"> Course Outcomes ('.$this->lang->line('entity_clo').') Attainment</h4><br>';

echo "<img src=".$co_attainment_graph." width='680' height='330' />";

echo '<br><br><br><br>'.$ajax_result['table'].'<br><br>';

echo $ajax_result['co_attainment_note'].'<br><br>';

if($ajax_result['table_finalize_flag'] === "Finalized") {
    echo '<h4 class="ul_class" style="text-align:center; font-size:14px;"> Course - CIA Course Outcomes (COs) Attainment is Finalized</h4><br><br>';
    
    echo $ajax_result['table_finalize'];
}