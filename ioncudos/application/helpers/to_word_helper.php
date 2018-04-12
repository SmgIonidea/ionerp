<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CodeIgniter HTML to Word Helpers
 *
 * @package		CodeIgniter
 * @subpackage	to_word
 * @category	to_word
 * @author		Bhagyalaxmi S S
 * @link		http://phpword.codeplex.com/documentation
 */

// ------------------------------------------------------------------------

/**
 * HTML to Word
 *
 * Lets you convert HTML page to Word.
 *
 * @access	admin, program owner and course coordinator
 * @param	array
 * @return	
 */
 
if ( ! function_exists('html_to_word'))
{
	function html_to_word($content , $dept_name , $filename = 'ioncudos_report' , $orientation = 'P') {
	
	$CI =& get_instance();

		ini_set('memory_limit', '-1');
        $CI->load->library('Html_to_word');
        $phpword_object = $CI->html_to_word;
        $section = $phpword_object->createSection();
        $styleTable = array('borderSize' => 10);
		        $header = $section->createHeader();
        $table_header = $header->addTable($styleTable);
        $table_header->addRow();
        $logoHeader = './uploads/report/your_logo.png';


        $org_details = $CI->organisation_model->get_organisation_by_id(1);
        $org_name = $org_details[0]['org_name'];
        $org_society = $org_details[0]['org_society'];


        //header font styling
        $fontStyleTitle = array('size' => 10);
        $paragraphStyleTitle = array('spaceBefore' => 0, 'align' => 'center');
        $styleTable = array('borderSize' => 10);

        $table_header->addCell(1000)->addImage($logoHeader, array('width' => 75, 'height' => 75, 'align' => 'left'));
        $cell = $table_header->addCell(20000);
		$cell->addText($org_society, $fontStyleTitle, $paragraphStyleTitle);
        $cell->addText($org_name, $fontStyleTitle, $paragraphStyleTitle);
        $cell->addText($dept_name, $fontStyleTitle, $paragraphStyleTitle);
		
		
		 $logoHeader = './uploads/bvbFooter.png';
        $header->addImage($logoHeader, array('width' => 650, 'height' => 20, 'align' => 'center'));
		        // Add footer
        $footer = $section->createFooter();
        $logoFooter = './uploads/bvbFooter.png';
        $footer->addImage($logoFooter, array('width' => 650, 'height' => 20, 'align' => 'center'));
        $footer->addPreserveText('Powered by www.ioncudos.com 					Page {PAGE} of {NUMPAGES}.', array(), array('align' => 'right'));
		  $html_dom = new simple_html_dom ();

        //$html_dom->load('<html><body><img src="'. $export_graph_content. '" /><br>'.$export_content .'</body></html>');
        $html_dom->load('<html><body>' . $content . '</body></html>');     
		// $html_dom->load("<div id='content'>" . $content['word_content'] . "</div>");
        // Note, we needed to nest the html in a couple of dummy elements.
        // Create the dom array of elements which we are going to work on:
        $html_dom_array = $html_dom->find('html', 0)->children();

        // We need this for setting base_root and base_path in the initial_state array
        // (below). We are using a function here (derived from Drupal) to create these
        // paths automatically - you may want to do something different in your
        // implementation. This function is in the included file
        // documentation/support_functions.inc.
        $paths = htmltodocx_paths();
        // Provide some initial settings:
        $initial_state = array(
            // Required parameters:
            'phpword_object' => &$phpword_object, // Must be passed by reference.
            // 'base_root' => 'http://test.local', // Required for link elements - change it to your domain.
            // 'base_path' => '/htmltodocx/documentation/', // Path from base_root to whatever url your links are relative to.
            'base_root' => $paths ['base_root'],
            'base_path' => $paths ['base_path'],
            // Optional parameters - showing the defaults if you don't set anything:
            'current_style' => array(
                'size' => '11'
            ), // The PHPWord style on the top element - may be inherited by descendent elements.
            'parents' => array(
                0 => 'body'
            ), // Our parent is body.
            'list_depth' => 0, // This is the current depth of any current list.
            'context' => 'section', // Possible values - section, footer or header.
            'pseudo_list' => TRUE, // NOTE: Word lists not yet supported (TRUE is the only option at present).
            'pseudo_list_indicator_font_name' => 'Wingdings', // Bullet indicator font.
            'pseudo_list_indicator_font_size' => '7', // Bullet indicator size.
            'pseudo_list_indicator_character' => '', // Gives a circle bullet point with wingdings.
            'table_allowed' => TRUE, // Note, if you are adding this html into a PHPWord table you should set this to FALSE: tables cannot be nested in PHPWord.
            'treat_div_as_paragraph' => TRUE, // If set to TRUE, each new div will trigger a new line in the Word document.
            // Optional - no default:
            'style_sheet' => htmltodocx_styles_example()  // This is an array (the "style sheet") - returned by htmltodocx_styles_example() here (in styles.inc) - see this function for an example of how to construct this array.
        );

        // Convert the HTML and put it into the PHPWord object
        htmltodocx_insert_html($section, $html_dom_array [0]->nodes, $initial_state);
        // Clear the HTML dom object:
        $html_dom->clear();
        unset($html_dom);

        $objWriter = PHPWord_IOFactory::createWriter($phpword_object, 'Word2007');

        //if (empty ( $report_id )) {
        // Download the file:
        ob_clean();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.$filename.'.doc');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');

        flush();
        $objWriter->save('php://output');
        exit();
	}
}

// --------------------------------------------------------------------

/* End of file array_helper.php */
/* Location: ./system/helpers/array_helper.php */

?>