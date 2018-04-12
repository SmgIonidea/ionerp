<?php
/**
 * Description	:	View for NBA SAR Report - Section 9 (TIER I) - Student Support Systems
 * Created		:	13-09-2016
 * Author		:	Bhagyalaxmi S S
 * Date					Author				Description
---------------------------------------------------------------------------------------------------------*/
?>
<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class T1ug_c10_governance_institutional_support_and_financial extends CI_Controller {
	
	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'nba_sar/nba_sar_list_model' );
	}
	
	/**
	 * Show all navigation groups
	 *
	 * @return void
	 */
	public function index() {

			echo "hiii";	
	}
	
	/* Function to fetch the State the Vision and Mission of the Institute */
	public function vision_and_mission_of_institute(){
	
	}	
	
	/* Function to fetch the Feedback analysis and reward /corrective measures */
	public function institutional_strategic_plan(){
	
	}	
	/* Function to fetch the Feedback on facilities */
	public function governing_body_administrative_set_up(){
	
	}	
	/* Function to fetch the Decentralization in working and grievance redressal mechanism */
	public function decentralization_in_working_and_grievance_redressal_mechanism(){
	
	}	
	/* Function to fetch the Delegation of financial powers */
	public function delegation_of_financial_powers(){
	
	}	
	/* Function to fetch the Transparency and availability of correct/unambiguous information in public domain */
	public function transparency_and_availability(){
	
	}
		
	/* Function to fetch the Budget Allocation, Utilization, and Public Accounting at Institute level */
	public function budget_allocation_utilization_and_public_accounting(){
	
	}
	
	/* Function to fetch the Adequacy of budget allocation*/
	public function adequacy_budget_allocation(){
	
	}
	/* Function to fetch the Utilization of allocated funds */
	public function utilization_allocated_funds(){
	
	}
	/* Function to fetch the Availability of the audited statements on the institute’s website */
	public function availability_of_audited_statements(){
	
	}	
	/* Function to fetch the Program Specific Budget Allocation, Utilization*/
	public function program_specific_budget_allocation_utilization(){
	
	}	
	/* Function to fetch the Adequacy of budget allocation */
	public function adequacy_ofbudget_allocation(){
	
	}	
	/* Function to fetch the Utilization of allocated funds*/
	public function utilization_ofallocated_funds(){
	
	}	
	/* Function to fetch the Library and Internet */
	public function library_and_internet(){
	
	}	
	/* Function to fetch the Quality of learning resources */
	public function quality_oflearning_resources(){
	
	}	
	/* Function to fetch the Internet */
	public function internet(){
	
	}
	
}