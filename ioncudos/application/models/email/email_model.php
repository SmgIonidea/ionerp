<?php
/**
* Description	:	Model(Database) Logic for Email Module.
* Created		:	07-06-2013. 
* Modification History:
* Date				Modified By				Description
* 24-09-2013		Abhinay B.Angadi        Added file headers, function headers, indentations & comments.
* 25-09-2013		Abhinay B.Angadi		Variable naming, Function naming & Code cleaning.
-------------------------------------------------------------------------------------------------
*/
?>

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Email_model extends CI_Model {

	function __construct()	{
        // Call the Model constructor
        parent::__construct();
    }
	
	/* Function to fetch the email subject, body and many other details from the email_template.
     * @parameters: state id, curriculum id, entity id.
     * @return: an array of email template details.
     */
	function get_the_email_content($state,$crclm_id,$entity_id)	{
		$email = ' SELECT subject,body,opening,links,footer,signature 
					FROM email_template 
					WHERE state_id = "'.$state.'" 
					AND entity_id = "'.$entity_id.'" ';
		$email = $this->db->query($email);
		$email = $email->result_array();
		if($crclm_id != NULL){
			$crclm_name = ' SELECT crclm_name 
							FROM curriculum 
							WHERE crclm_id = "'.$crclm_id.'" ';
			$crclm_name = $this->db->query($crclm_name);
			$crclm_name = $crclm_name->result_array();
			$data['crclm_name'] = $crclm_name;
		}else {
		$data['crclm_name']=NULL;
		}
		$data['email']=$email;
		return $data;
	}
}
?>