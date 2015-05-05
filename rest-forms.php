<?php
/*
 * Plugin Name: Omny Link Forms
 * Plugin URI: http://knowprocess.com/wp-plugins/rest-forms
 * Description: Integrates web APIs with your WordPress app.
 * Author: Tim Stephenson
 * Version: 0.10.0
 * Author URI: http://omny.link
 * License: GPLv2 or later
 */

  define("P_ID", 'rest-forms');
  define('P_VERSION', '0.10.0');
  define("P_NAME", 'Omny Link Forms');
  define("P_TEXT_DOMAIN", 'p-textdomain');

  require_once("includes/options.php");
  if ($p_options == null) $p_options = new FormsOptions();
  define("P_DEBUG", $p_options->is_debug_on());
  if (P_DEBUG) error_log(P_NAME.' debug logging is on');

  require_once("includes/ajax_support.php");
  require_once("includes/contacts_widget.php");
  require_once("includes/events.php");
  require_once("includes/shortcodes.php");
  //require_once("includes/tasks_widget.php");
  require_once("includes/forms.php");

  if ( is_admin() ) {
    // admin only actions
  } else {
    // front end only
  }
  add_action( 'wp_enqueue_scripts', 'p_load_scripts' );
  add_action( 'wp_head', 'p_load_styles' );

  function p_footer() {
    if (P_DEBUG) echo 'Running '.P_NAME.' plugin in debug mode!';
    if ($p_options == null) $p_options = new FormsOptions();
    if ($p_options->is_fully_configured()) {
    ?>
      <script type="text/javascript">jQuery(document).ready(function(){
        $p.server='<?php echo $p_options->get_api_url(); ?>';
        $p.tenant='<?php echo $p_options->get_message_namespace(); ?>';
        $p.proxyApi = '<?php echo $p_options->is_proxy_required() ?>'==1 ? true : false;
        $.ajaxSetup({xhrFields: {withCredentials: true}});
      });</script>
    <?php
    }
  }
  add_action( 'wp_footer', 'p_footer' );

  function p_load_styles() {
    if ( is_admin() ) {
      /* Currently empty */
    } else {
      wp_enqueue_style(
        P_ID.'-frontend',
        plugins_url( 'css/frontend-'.P_VERSION.'.css', __FILE__ ),
        array(),
        null /* Force no version as query string */
      );
    }
  }

  function p_load_scripts() {
    if (P_DEBUG) error_log('Loading scripts for '.P_ID.' plugin');
    // used for both admin and front end
    wp_enqueue_script(
      P_ID.'-client',
      plugins_url( 'js/'.P_ID.'-'.P_VERSION.(P_DEBUG ? '' : '.min').'.js', __FILE__ ),
      array( 'jquery', 'jquery-ui-autocomplete' ),
      null, /* Force no version as query string */
      true /* Force load in footer */
    );
    wp_enqueue_script(
      'i18n',
      plugins_url( 'js/i18n.js', __FILE__ ),
      array(),
      null, /* Force no version as query string */
      true /* Force load in footer */
    );

    if ( is_admin() ) {
      ;
    } else {
      wp_enqueue_script(
        'moustache.js',
        plugins_url( 'js/moustache.js', __FILE__ ),
        array(),
        null, /* Force no version as query string */
        true /* Force load in footer */
      );
      wp_enqueue_script(
        'autoNumeric',
        plugins_url( 'js/autoNumeric'.(P_DEBUG ? '' : '.min').'.js', __FILE__ ),
        array(),
        null, /* Force no version as query string */
        true /* Force load in footer */
      );
    }
  }

?>
