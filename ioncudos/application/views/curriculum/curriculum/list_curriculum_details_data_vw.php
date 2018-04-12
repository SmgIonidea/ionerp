<?php

/**
 * Description	:	Display list of curriculum's vision,mission,peo an s po
 * 					
 * Created		:	29-06-2015
 *
 * Author		:	Jyoti
 * 		  
 * Modification History:
 *    Date               Modified By                			Description
 *
  ---------------------------------------------------------------------------------------------- */
?>

<?php

$this->load->view('curriculum/curriculum/dept_vision_mission_vw', $dept_info['vision_mission']);
$this->load->view('curriculum/curriculum/dept_peo_vw', $dept_info['peo'], $attendees_data, $justification);
$this->load->view('curriculum/curriculum/dept_po_vw', $dept_info['po']);
?>
						
