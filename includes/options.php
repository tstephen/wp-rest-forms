<?php
define('P_API_URL', 'https://api.knowprocess.com');
class FormsOptions {

    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'p_add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'p_page_init' ) );

        // Set class property
        $this->options = get_option( P_ID.'_options' );
    }

    public function is_debug_on() {
      return isset( $this->options['debug'] ) && $this->options['debug'] == True;
    }

    public function get_mail_addressee() {
      return $this->options['mail_addressee'];
    }

    public function get_message_namespace() {
      return $this->options['message_namespace'];
    }

    public function get_api_url() {
      if (isset( $this->options['api_url'] )) {
        return ends_with($this->options['api_url'], '/') ? $this->options['api_url'] : $this->options['api_url'].'/';
      } else {
        return P_API_URL;
      }
    }

    public function get_api_key() {
      return isset( $this->options['api_key'] ) ? $this->options['api_key'] : '';
    }

    public function get_api_secret() {
      return isset( $this->options['api_secret'] ) ? $this->options['api_secret'] : '';
    }
    /**
     * Add options page
     */
    public function p_add_plugin_page() {
        // This page will be under "Settings"
        add_options_page(
            P_NAME.' Settings Admin',
            P_NAME.' Settings',
            'manage_options',
            'p_settings_admin',
            array( $this, 'p_create_admin_page' )
        );
    }


    /**
     * Options page callback
     */
    public function p_create_admin_page() {
        ?>
        <div class="wrap">
            <?php screen_icon(); ?>
            <h2><?php echo P_NAME; ?> Settings</h2>
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( P_ID.'_option_group' );
                do_settings_sections( 'p_settings_admin' );
                submit_button();
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function p_page_init() {
        register_setting(
            P_ID.'_option_group', // Option group
            P_ID.'_options', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'p_setting_section_general', // ID
            'General Settings', // Title
            array( $this, 'p_print_general_section_info' ), // Callback
            'p_settings_admin' // Page
        );

        add_settings_field(
            'api_url', // ID
            'API Server URL', // Label
            array( $this, 'p_api_url_callback' ), // Callback
            'p_settings_admin',
            'p_setting_section_general'
        );

        add_settings_field(
            'api_key', // ID
            'API Key', // Label
            array( $this, 'p_api_key_callback' ), // Callback
            'p_settings_admin',
            'p_setting_section_general'
        );

        add_settings_field(
            'api_secret', // ID
            'API Secret', // Label
            array( $this, 'p_api_secret_callback' ), // Callback
            'p_settings_admin',
            'p_setting_section_general'
        );

        add_settings_field(
            'mail_addressee', // ID
            'Mail Addressee', // Label
            array( $this, 'p_mail_addressee_callback' ), // Callback
            'p_settings_admin',
            'p_setting_section_general'
        );

        add_settings_field(
            'debug', // ID
            'Enable debug output', // Label
            array( $this, 'p_debug_callback' ), // Callback
            'p_settings_admin', // Page
            'p_setting_section_general' // Section
        );

        add_settings_field(
            'message_namespace', // ID
            'Message namespace', // Label
            array( $this, 'p_message_namespace_callback' ), // Callback
            'p_settings_admin', // Page
            'p_setting_section_general' // Section
        );

        add_settings_section(
            'p_setting_section_events', // ID
            'Event Settings', // Label
            array( $this, 'p_print_event_section_info' ), // Callback
            'p_settings_admin' // Page
        );

        add_settings_field(
            'post_published', // ID
            'When a post is published', // Label
            array( $this, 'p_post_published_callback' ), // Callback
            'p_settings_admin', // Page
            'p_setting_section_events' // Section
        );
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input ) {
        $new_input = array();
        if( isset( $input['api_url'] ) )
            $new_input['api_url'] = sanitize_text_field( $input['api_url'] );
        if( isset( $input['api_key'] ) )
            $new_input['api_key'] = sanitize_text_field( $input['api_key'] );
        if( isset( $input['api_secret'] ) )
            $new_input['api_secret'] = sanitize_text_field( $input['api_secret'] );

error_log('XXXXXX'. $input['mail_addressee']);
        if( isset( $input['mail_addressee'] ) )
            $new_input['mail_addressee'] = sanitize_text_field( $input['mail_addressee'] );

        if( isset( $input['debug'] ) )
            $new_input['debug'] = true ;

        if( isset( $input['message_namespace'] ) )
            $new_input['message_namespace'] = sanitize_text_field( $input['message_namespace'] );

        if( isset( $input['post_published'] ) )
            $new_input['post_published'] = true ;

        return $new_input;
    }

    /**
     * Print the leader text for the general section.
     */
    public function p_print_general_section_info() {
        print 'Enter your settings below:';
    }

    /**
     * Output checkbox for 'debug' option.
     */
    public function p_debug_callback() {
        printf(
            '<input type="checkbox" id="debug" name="'.P_ID.'_options[debug]" %s />',
            isset( $this->options['debug'] ) && $this->options['debug'] == True ? 'checked' : ''
        );
    }

    /**
     * Output textbox for 'mail_addressee' option.
     */
    public function p_mail_addressee_callback() {
        printf(
            '<input type="text" id="mail_addressee" name="'.P_ID.'_options[mail_addressee]" value="%s" />',
            isset( $this->options['mail_addressee'] ) ? esc_attr( $this->options['mail_addressee']) : ''
        );
    }

    /**
     * Output textbox for 'message_namespace' option.
     */
    public function p_message_namespace_callback() {
        printf(
            '<input type="text" id="message_namespace" name="'.P_ID.'_options[message_namespace]" value="%s" />',
            isset( $this->options['message_namespace'] ) ? esc_attr( $this->options['message_namespace']) : ''
        );
    }

    /**
     * Output textbox for 'api_url' option.
     */
    public function p_api_url_callback() {
        printf(
            '<input type="text" id="api_url" name="'.P_ID.'_options[api_url]" value="%s" />',
            isset( $this->options['api_url'] ) ? esc_attr( $this->options['api_url']) : P_API_URL
        );
    }


    /**
     * Output textbox for 'api_url' option.
     */
    public function p_api_key_callback() {
        printf(
            '<input type="text" id="api_key" name="'.P_ID.'_options[api_key]" value="%s" />',
            isset( $this->options['api_key'] ) ? esc_attr( $this->options['api_key']) : ''
        );
    }
    

    /**
     * Output textbox for 'api_secret' option.
     */
    public function p_api_secret_callback() {
        printf(
            '<input type="text" id="api_secret" name="'.P_ID.'_options[api_secret]" value="%s" />',
            isset( $this->options['api_secret'] ) ? esc_attr( $this->options['api_secret']) : ''
        );
    }
    
    /**
     * Output checkbox for 'post_published' option.
     */
    public function p_post_published_callback() {
        //error_log('Write option named: '.$this->options['post_published']);
        printf(
            '<input type="checkbox" id="post_published" name="'.P_ID.'_options[post_published]" %s />',
            isset( $this->options['post_published'] ) && $this->options['post_published'] == True ? 'checked' : ''
        );
    }

    /**
     * Print the leader text for the events section.
     */
    public function p_print_event_section_info() {
        print 'Check the events you would like to publish:';
    }
}

function starts_with($haystack, $needle) {
    return $needle === "" || strpos($haystack, $needle) === 0;
}
function ends_with($haystack, $needle) {
    return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
}
