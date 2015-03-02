<?php

  function p_register_async_callback() {
    if (P_DEBUG) error_log('Call to p_register_async_callback');
    $user_id = get_current_user_id();
  	$user_name = $_POST['log'];
    $user_pass = $_POST['pwd'];
    if ($user_id == 0) {
      $user_id = username_exists( $user_name );
      if ( !$user_id and email_exists($user_name) == false ) {
        if ($user_pass == null) {
	         $user_pass = wp_generate_password( $length=12, $include_standard_special_chars=false );
	      }
        $user_id = wp_create_user( $user_name, $user_pass, $user_name );
        if (P_DEBUG) error_log('Created user id '.$user_id.' with username: '.$user_name);

        // Now store any user info we have received
        foreach($_POST as $key=>$value) {
          if ($key != 'log' && $key != 'action') {
            if(P_DEBUG) error_log( "Storing user field: $key=$value" );
            update_user_meta( $user_id, $key, $value );
          }
        }

        $xmlResponse = new WP_Ajax_Response(array(
           'what'=>'Registration',
           'action'=>'p_register_async',
           'id'=>1,
           'data'=>'Created user id '.$user_id.' with username: '.$user_name));
        $xmlResponse->send();
      } else {
        if (P_DEBUG) error_log('Either username '.$user_name.' or email '.$user_name.' already exists');
        $xmlResponse = new WP_Ajax_Response(array(
           'what'=>'Registration',
           'action'=>'p_register_async',
           'id'=>new WP_Error('already_registered',$user_name." already registered")));
        $xmlResponse->send();
      }
    } else {
      if (P_DEBUG) error_log('Attempt to register '.$user_name.' when already logged in with id: '.$user_id);
      $xmlResponse = new WP_Ajax_Response(array(
         'what'=>'Registration',
         'action'=>'p_register_async',
         'id'=>new WP_Error('already_logged_in',"You're already logged in with user id: ".$user_id.", cannot register as: ".$user_name)));
      $xmlResponse->send();
    }
  	die(); // this is required to return a proper result
  }
  if (P_DEBUG) error_log('Adding register ajax action');
  add_action( 'wp_ajax_p_register_async', 'p_register_async_callback' );
  add_action( 'wp_ajax_nopriv_p_register_async', 'p_register_async_callback' );

  function p_send_mail_callback() {
    if (P_DEBUG) error_log('Call to p_send_mail_callback');

    $options = get_option( P_ID.'_options' );
    $to = $options['mail_addressee'];
    if (empty($to)) {
      if (P_DEBUG) error_log('Skip mail because no addressees set');
    } else {
      if (P_DEBUG) error_log('Sending mail to: '.$to);
      $subject = 'Trial request';
      $json = urldecode($_POST['json']);
      if (P_DEBUG) error_log('JSON received'.$json);
      $a = json_decode($json, true);
      //if (P_DEBUG) error_log('parsed: '.$a);
      $message = '<div align="center"><table style="width:6.25in" border="0" cellpadding="0" cellspacing="0" width="600"><tbody>';
      $message .= '<tr><td colspan="3" style="border:none;border-bottom:solid #d4d4d4 1.0pt;padding:7.5pt 7.5pt 7.5pt 7.5pt"><p  style="text-align:center" align="center"><strong><i><span style="font-size:15.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#4696d1">'.$a['pn'].'Trial Request</span></i></strong><i><span style="font-size:15.0pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#4696d1"><u></u><u></u></span></i></p></td></tr><tr style="height:3.75pt"><td colspan="3" style="background:#e2ecf1;padding:7.5pt 7.5pt 7.5pt 7.5pt;height:3.75pt"></td></tr>';
      foreach ($a as $key => $value) {
        $message .= '<tr><td style="border-top:none;border-left:none;border-bottom:solid #d4d4d4 1.0pt;border-right:solid #d4d4d4 1.0pt;padding:7.5pt 7.5pt 7.5pt 7.5pt"><div ><strong><span style="font-size:9pt;font-family:Arial,sans-serif">'.$key.':</span></strong><span style="font-size:9pt;font-family:Arial,sans-serif"></span></div></td><td style="border:none;border-bottom:solid #d4d4d4 1.0pt;padding:7.5pt 7.5pt 7.5pt 7.5pt"><div><span style="font-size:9pt;font-family:Arial,sans-serif">'.$value.'</span></div></td><td style="padding:7.5pt 7.5pt 7.5pt 7.5pt"></td></tr><tr><td style="border-top:none;border-left:none;border-bottom:solid #d4d4d4 1.0pt;border-right:solid #d4d4d4 1.0pt;padding:7.5pt 7.5pt 7.5pt 7.5pt">';
      }
      $message .= '</tbody></table></div>';
      //if (P_DEBUG) error_log('Constructed message'.$message);
      $headers = array('Content-Type: text/html; charset=UTF-8');
      wp_mail( $to, $subject, $message, $headers );
  	}
    die(); // this is required to return a proper result
  }
  if (P_DEBUG) error_log('Adding send mail ajax action');
  add_action( 'wp_ajax_p_send_mail', 'p_send_mail_callback' );
  add_action( 'wp_ajax_nopriv_p_send_mail', 'p_send_mail_callback' );
?>
