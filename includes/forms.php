<?php

  // Process form pseudo code into an html form
  function p_form_generator($id, $form) {
    error_log('p_form_generator received: '.$form);
    $new_form = '<form action="#" id="'.$id.'">';
    $new_form.='<div class="p-messages"></div>';
    $lines = explode(PHP_EOL,$form);
    foreach ($lines as $line) {
      if (strpos($line, '[') == false) {
        //error_log('BBBBBBB'.$line);
        $new_form.=$line;
      } else {
        error_log('AAAAAAAAAAAAneed to process: '.$line);
        preg_match("/.*\[(?P<type>[\w-_]+)(?P<required>[\**]) (?P<name>[\w-_]+) \"(?P<label>[\w-_]+)\"/", $line, $matches);

        // loop through the matches with foreach
        foreach($matches as $key=>$value) {
          //$new_form.= ($key.' -> '.$value);
          //error_log('DDDDDDD '.$key.' -> '.$value);
        }
        /*error_log('EEEEE '.$matches['type'].' -> '.$matches['name']);
        switch($matches['type']) {
        case 'radi':
        case 'radio':
          error_log('radio buttons not yet supported');
          break;
        case 'submi':
        case 'submit':
          $new_form.=('<button class="decorate" data-p-action="$p.sendMessage($p.'.$id.')"></button>');
          break;
        case 'textare':
        case 'textarea':
          $new_form.=('<textarea class="decorate" data-p-bind="$p.'.$id.'.'.str_replace('-','_',$matches['name']).'"></textarea>');
          break;
        case 'text':
        default:
          $new_form.=('<input class="decorate" '
            .'data-p-bind="$p.'.$id.'.'.str_replace('-','_',$matches['name']).'"'
            .($matches['required'] == '*' ? ' required' : '')
            .' type="'.$matches['type'].'"/>');
        }
        */
        $start = strpos($line, '[')+1;
        $line = substr($line, $start, strpos($line, ']')-$start);
error_log('GGGGGGGGGGGG '.$line);
        $tokens = explode(' ',$line);
error_log('ctrl type: '.$tokens[0]);
error_log('FFFFFFFctrl binding: '.$tokens[1]);
error_log('FFFFFFFctrl name: '.$tokens[2]);
        switch($tokens[0]) {
        case 'submit':
          $new_form.=('<button class="btn decorate" data-p-action="if(document.getElementById(\''.$id.'\').checkValidity()) $p.sendMessage(\'inOnly\', \'jem.'.$id.'\', JSON.stringify($p.'.$id.'));">'.str_replace('"','',$tokens[1]).'</button>');
          break;
        case 'textarea':
        case 'textarea*':
          $new_form.=('<textarea class="decorate"'
            .'data-p-bind="$p.'.$id.'.'.str_replace('-','_',$tokens[1]).'"'
            .(strpos($tokens[0], '*') ? ' required' : '')
            .'></textarea>');
          break;
        case 'email':
        case 'email*':
        case 'tel':
        case 'tel*':
        case 'text':
        case 'text*':
        default:
          $new_form.=('<input class="decorate"'
            .'data-p-bind="$p.'.$id.'.'.str_replace('-','_',$tokens[1]).'"'
            .(strpos($tokens[0], '*') ? ' required' : '')
            .' type="'.str_replace('*','',$tokens[0]).'"/>');
        }
      }
    }
    return $new_form.'</form>';
  }

function my_the_content_filter($content) {
  $scode_pos = strpos($content, '[contact-form-7');
  if ($scode_pos > -1) {
    error_log('YYYYYYYYYYY have found shortcode to replace');
    $id_pos = strpos($content, 'id=',$scode_pos);
    $id_len = strpos($content, ' ', $id_pos) - ($id_pos+4);
    $id = substr($content, $id_pos+4, $id_len-1);
    error_log('found id: '.$id);

$content_post = get_post($id);
$form = $content_post->post_content;
$form = apply_filters('the_content', $form );
$form = str_replace(']]>', ']]&gt;', $form );
// There is no delimiter after the form and before the mail fields
// so we have to assume the button is the end
$form = substr($form, 0, strpos($form, ']', strpos($form, '[submit'))+1);

    //$tmp = str_replace('[contact-form-7', '[p-page', $content);
    //$form = '<form>'
    //  .'</form>';
    $tmp = preg_replace('/(\[)contact-form-7(.*)]/', p_form_generator('form'.$id, $form), $content);
    error_log('ZZZZZZZZZZZZZZZZZZZ Returning '.$tmp);
    return $tmp;
  } else {
    error_log(strpos($content, '[contact-form-7').'   XXXXXXXXXXXXXXXXXXhave NOT found shortcode to replace');
    //error_log($content);
  }
  // otherwise returns the database content
  return $content;
}

add_filter( 'the_content', 'my_the_content_filter' );

function p_form_init() {

	$labels = array(
		'name'                => _x( 'Syncapt Forms', 'Post Type General Name', 'p_form' ),
		'singular_name'       => _x( 'Syncapt Form', 'Post Type Singular Name', 'p_form' ),
		'menu_name'           => __( 'Syncapt Form', 'p_form' ),
		'parent_item_colon'   => __( 'Parent Form:', 'p_form' ),
		'all_items'           => __( 'All Forms', 'p_form' ),
		'view_item'           => __( 'View Form', 'p_form' ),
		'add_new_item'        => __( 'Add New Form', 'p_form' ),
		'add_new'             => __( 'Add New', 'p_form' ),
		'edit_item'           => __( 'Edit Form', 'p_form' ),
		'update_item'         => __( 'Update Form', 'p_form' ),
		'search_items'        => __( 'Search Form', 'p_form' ),
		'not_found'           => __( 'Not found', 'p_form' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'p_form' ),
	);
	$args = array(
		'label'               => __( 'p_form', 'p_form' ),
		'description'         => __( 'A simple, semantic form compatible with Syncapt data and action binding', 'p_form' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'author', 'revisions', ),
		'taxonomies'          => array( 'category', 'post_tag' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 20,
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',
	);
	register_post_type( 'p_form', $args );

}

// Hook into the 'init' action
add_action( 'init', 'p_form_init', 0 );
?>
