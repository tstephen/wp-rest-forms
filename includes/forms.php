<?php

  function p_form_init() {

  	$labels = array(
  		'name'                => _x( 'Omny Link Forms', 'Post Type General Name', 'p_form' ),
  		'singular_name'       => _x( 'Omny Link Form', 'Post Type Singular Name', 'p_form' ),
  		'menu_name'           => __( 'Omny Link Forms', 'p_form' ),
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
  		'description'         => __( 'A simple, semantic form compatible with Omny Link data and action binding', 'p_form' ),
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

  /**
   * Adds a meta box to the form editing screen
   */
  function p_custom_meta() {
    add_meta_box( 'p_meta', __( 'Generate form control', P_TEXT_DOMAIN ), 'p_meta_callback', 'p_form', 'side', 'high');
  }
  add_action( 'add_meta_boxes', 'p_custom_meta' );

  /**
   * Outputs the content of the meta box
   */
  function p_meta_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'p_nonce' );
    // TODO not sure why this is not being called on init, but it is not
    p_load_scripts();
    ?>

    <p>
      <label for="ctrlType"><?php _e( 'Control Type', P_TEXT_DOMAIN )?></label><br/>
      <select name="ctrlType" id="ctrlType">
        <option value="email"><?php _e( 'Email', P_TEXT_DOMAIN )?></option>';
        <option value="tel"><?php _e( 'Telephone', P_TEXT_DOMAIN )?></option>';
        <option value="number"><?php _e( 'Number', P_TEXT_DOMAIN )?></option>';
        <option value="text" selected><?php _e( 'Text', P_TEXT_DOMAIN )?></option>';
        <option value="textarea"><?php _e( 'Textarea', P_TEXT_DOMAIN )?></option>';
      </select>
    </p>

    <p>
      <label for="ctrlLabel"><?php _e( 'Label', P_TEXT_DOMAIN )?></label><br/>
      <input type="text" name="ctrlLabel" id="ctrlLabel" value="<?php if ( isset ( $p_stored_meta['ctrlLabel'] ) ) echo $p_stored_meta['ctrlLabel'][0]; ?>" />
    </p>

    <p>
      <label for="ctrlBinding"><?php _e( 'Binding', P_TEXT_DOMAIN )?></label><br/>
      <input type="text" name="ctrlBinding" id="ctrlBinding" value="<?php if ( isset ( $p_stored_meta['ctrlBinding'] ) ) echo $p_stored_meta['ctrlBinding'][0]; ?>" />
    </p>

    <p>
      <label for="ctrlPlaceholder"><?php _e( 'Placeholder', P_TEXT_DOMAIN )?></label><br/>
      <input type="text" name="ctrlPlaceholder" id="ctrlPlaceholder" value="<?php if ( isset ( $p_stored_meta['ctrlPlaceholder'] ) ) echo $p_stored_meta['ctrlPlaceholder'][0]; ?>" />
    </p>

    <p>
      <span><?php _e( 'Required?', P_TEXT_DOMAIN )?></span>
      <div class="prfx-row-content">
        <label for="ctrlRequired">
          <input type="checkbox" name="ctrlRequired" id="ctrlRequired" value="yes" <?php if ( isset ( $prfx_stored_meta['ctrlRequired'] ) ) checked( $prfx_stored_meta['ctrlRequired'][0], 'yes' ); ?> />
          <?php _e( '', P_TEXT_DOMAIN )?>
        </label>
      </div>
    </p>

    <p>
      <input type="button" id="addCtrl" class="button"
          onclick="$p.addControl();"
          value="<?php _e( 'Add control', P_TEXT_DOMAIN )?>" />
    </p>


    <?php
  }

?>
