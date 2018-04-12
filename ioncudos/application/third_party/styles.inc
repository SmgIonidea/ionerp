<?php

// Example styles
// This is an example of how a "style sheet" should be structured
// to asign PHPWord styles to HTML elements, classes, and inline
// styles.
function htmltodocx_styles_example() {
 
 // Set of default styles - 
 // to set initially whatever the element is:
 // NB - any defaults not set here will be provided by PHPWord.
 $styles['default'] = 
   array (
     'size' => 12,
   );
 
 // Element styles:
 // The keys of the elements array are valid HTML tags;
 // The arrays associated with each of these tags is a set
 // of PHPWord style definitions.
 $styles['elements'] = 
   array (
     'h1' => array (
       'bold' => TRUE,
       'size' => 20,
       ),
     'h2' => array (
       'bold' => TRUE,
       'size' => 15,
       'spaceAfter' => 150,
       ),
     'h3' => array (
       'size' => 14,
       'bold' => TRUE,
       'spaceAfter' => 100,
       ),
	 'h4' => array (
       'size' => 12,
       'bold' => TRUE,
       'spaceAfter' => 100,
       ),
	  'h5' => array (
       'size' => 10,
       'bold' => TRUE,
       'spaceAfter' => 100,
       ),
	  'h6' => array (
       'size' => 10,
       'bold' => TRUE,
       'spaceAfter' => 100,
       ),
     'li' => array (
		'size' => 12
       ),
     'ol' => array (
       'spaceBefore' => 200,
       ),
     'ul' => array (
       'spaceAfter' => 150,
       ),
     'b' => array (
       'bold' => TRUE,
       ),
     'em' => array (
       'italic' => TRUE,
       ),
     'i' => array (
       'italic' => TRUE,
       ),
     'strong' => array (
       'bold' => TRUE,
       ),
     'b' => array (
       'bold' => TRUE,
       ),
     'sup' => array (
       'superScript' => TRUE,
       'size' => 6,
       ), // Superscript not working in PHPWord 
     'u' => array (
       'underline' => PHPWord_Style_Font::UNDERLINE_SINGLE,
       ),
     'a' => array (
       'color' => '0000FF',
       'underline' => PHPWord_Style_Font::UNDERLINE_SINGLE,
       ),
     'table' => array (
       // Note that applying a table style in PHPWord applies the relevant style to
       // ALL the cells in the table. So, for example, the borderSize applied here
       // applies to all the cells, and not just to the outer edges of the table:
       'borderColor' => '000000',  
       'borderSize' => 10,	 
	   'cellMarginTop'=>80,
	   'cellMarginLeft'=>80,
	   'cellMarginRight'=>80
       ),
     'th' => array (
       'borderColor' => '000000',
       'borderSize' => 10,
	   'size' => 12,
	   'bold' => TRUE
       ),
     'td' => array (
       'borderColor' => '000000',
       'borderSize' => 10,
	   'size' => 12
       ),
	 'ul' => array (
	   'size' => 12
       ),
	 'li' => array (
	   'size' => 12
       ),
     );
     
 // Classes:
 // The keys of the classes array are valid CSS classes;
 // The array associated with each of these classes is a set
 // of PHPWord style definitions.
 // Classes will be applied in the order that they appear here if
 // more than one class appears on an element.
 $styles['classes'] = 
   array (
     'underline' => array (
       'underline' => PHPWord_Style_Font::UNDERLINE_SINGLE,
       ),
      'purple' => array (
       'color' => '901391',
      ),
	  'font_b' => array (
       'size' => '12',
      ),
	  'font_h' => array (
       'size' => '12',
      ),
	  'row_color' => array (
       'bgColor' => 'C2C2C2',
      ),	  
	  'h_class' => array(
		'bold' => FALSE,
	   ),
		'ul_class' => array(
			'spaceAfter' => 0,
			'spaceBefore' => 0,
		),
	'upper_class' => array(
			'spaceBefore' => 200,
			'spaceAfter' => 0
		),
	 's_class' => array(
		'bold' => TRUE,
	  ),
	  'orange' => array (
	   'bgColor' => 'afc6f6'
      ),
	  'light_orange' => array (
	   'bgColor' => 'FDE5BC'
      ),
	   'orange_color' => array (
	   'color' => 'FC6827',
      ),
       'blue_color' => array (
	   'color' => '0000FF',
      ),
       'light_blue' => array (
	   'bgColor' => 'FDE5BC'
      ),
	  'no_border' =>array (
       'borderColor' => 'ffffff'
       ),
	  'right_border' =>array (
		'borderRightColor' => 'ffffff'
       ),
	    'top_border' =>array (
		'borderTopColor' => 'ffffff'
       ),
	   'bottom_border' =>array (
		'borderBottomColor' => 'ffffff'
       ),
	   'left_border' =>array (
       'borderLeftColor' => 'ffffff'
       ),
      'green' => array (
       'color' => '00A500',
      ),
     );
 
 // Inline style definitions, of the form:
 // array(css attribute-value - separated by a colon and a single space => array of
 // PHPWord attribute value pairs.    
 $styles['inline'] = 
   array(
     'text-decoration: underline' => array (
       'underline' => PHPWord_Style_Font::UNDERLINE_SINGLE,
     ),
	 'space-before: 600' => array(
		'spaceBefore' => 600
	 ),
     'vertical-align: left' => array (
       'align' => 'left',
     ),
     'vertical-align: middle' => array (
       'align' => 'center',
     ),
	 'text-align: both' => array ( 
        'align' => 'both', 
      ),
	  'text-align: center' => array ( 
        'align' => 'center', 
      ), 
	   'text-align: right' => array ( 
        'align' => 'right', 
      ), 
	   'borderColor: FFFFFF' => array (
        'borderColor' => 'FFFFFF',
      ),
	   'color: FF0000' => array (
        'color' => 'FF0000',
      ),
     'vertical-align: right' => array (
       'align' => 'right',
     ),
	 'colspan: 24' => array (
        'gridSpan' => 24,
     ),
	 'colspan: 15' => array (
        'gridSpan' => 15,
     ),
	 'colspan: 14' => array (
        'gridSpan' => 14,
     ),
	 'colspan: 13' => array (
        'gridSpan' => 13,
     ),
	 'colspan: 12' => array (
        'gridSpan' => 12,
     ),
	 'colspan: 4' => array (
        'gridSpan' => 4,
     ),
	 'colspan: 3' => array (
        'gridSpan' => 3,
     ),
	 'colspan: 2' => array (
        'gridSpan' => 2,
     ),	 
	 'colspan: 1' => array (
        'gridSpan' => 1,
     )
   );
   
 return $styles;
}