<?php

  // shortcode [p_form id="id of form post" title="Form label"]
  function p_form_shortcode( $atts, $content = null ) {
    $a = shortcode_atts( array(
      'button_text' => 'Submit',
      'callback' => null,
      'id' => '',
      'msg_name' => '',
      'msg_pattern' => 'none',
      'proxy' => 'false',
      'redirect_to' => '',
      'title' => '',
    ), $atts );
    $options = new FormsOptions();
    if ($options->is_proxy_required()) $a['proxy'] = 'true';
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
    if (!empty( $content )) {
      $form_consts = explode(',', $content);
      foreach($form_consts as $c) {
        $arr = explode('=',$c);
        //$temp_content .= ('<input id="'.$arr[0].'" value="'.$arr[1].'" type="hidden"/>');
        $temp_content .= $arr[0].':"'.$arr[1].'",';
      }
    }
    $temp_content .= 'admin_email:"'.get_option( 'admin_email' ).'"};</script>';

    if (empty($a['redirect_to'])) { 
error_log('no redirect');
      $a['redirect_to'] = 'undefined';
    } else { 
error_log('with redirect');
      $a['redirect_to'] = '\''.$a['redirect_to'].'\'';
    }
    $temp_content .= '<div class="p-messages"></div>';
    $temp_content .= '<button data-p-action="if (document.getElementById(\''.$form->post_name.'\').checkValidity()) $p.sendMessage(\''.$a['msg_pattern'].'\', \''.$options->get_message_namespace().'.'.$a['msg_name'].'\', $p.'.str_replace('-','_',$form->post_name).','.$a['redirect_to'].',\''.$a['callback'].'\','.$a['proxy'].'); else $p.showFormError(\''.$form->post_name.'\',\'Please correct highlighted fields\');" id="btn-'.$form->post_name.'" form="'.$form->post_name.'" type="button">'.$a['button_text'].'</button>';
    $temp_content .= '</form>';

    ob_end_clean();
    return $temp_content;
  }
  add_shortcode( 'p_form', 'p_form_shortcode' );

?>
