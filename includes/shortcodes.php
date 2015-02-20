<?php

  // shortcode [p_form id="id of form post" title="Form label"]
  function p_form_shortcode( $atts ) {
    $a = shortcode_atts( array(
      'id' => '',
      'msg_name' => '',
      'redirect_to' => '/',
      'title' => '',
    ), $atts );
    $options = new FormsOptions();
    ob_start();

    $form = get_post($a['id']);
    if ($a['msg_name']=='') $a['msg_name'] = $form->post_name;

    $form_content = $form->post_content;
    $form_content = apply_filters('the_content', $form_content);
    $form_content = str_replace(']]>', ']]&gt;', $form_content);

    $temp_content .= '<form class="p-form" id="'.$form->post_name.'">';
    $temp_content .= '<div class="p-messages"></div>';

    $temp_content .= $form_content;

    $temp_content .= '<script type="text/javascript">';
    $temp_content .= 'var $params = {';
    foreach ($_REQUEST as $k => $v) {
      $temp_content .= $k.':"'.$v.'",';
    }
    $temp_content .= 'admin_email:"'.get_option( 'admin_email' ).'"};</script>';

    $temp_content .= '<button data-p-action="if (document.getElementById(\''.$form->post_name.'\').checkValidity()) $p.sendMessage(\'inOnly\', \''.$options->get_message_namespace().'.'.$a['msg_name'].'\', $p.'.str_replace('-','_',$form->post_name).',\''.$a['redirect_to'].'\'); else $p.showFormError(\''.$form->post_name.'\',\'Please correct form errors\');" form="'.$form->post_name.'" type="button">Submit</button>';
    $temp_content .= '</form>';

    ob_end_clean();
    return $temp_content;
  }
  add_shortcode( 'p_form', 'p_form_shortcode' );

?>
