<?php

// function myplugin_activate() {
 
//   $upload = wp_upload_dir();
//   $upload_dir = $upload['basedir'];
//   $upload_dir = $upload_dir . '/mypluginfiles';
//   if (! is_dir($upload_dir)) {
//      mkdir( $upload_dir, 0700 );
//   }
// }

// register_activation_hook( __FILE__, 'myplugin_activate' );


$node_modules_path = plugins_url('/node_modules/', __DIR__);

/*
** Set up wp_ajax requests for frontend UI.
** NOTE: _nopriv_ makes ajaxurl work for logged out users.
*/
add_action( 'wp_ajax_xtabla_actions', 'xtabla_actions' );
// add_action( 'wp_ajax_nopriv_xtabla_actions', 'xtabla_actions' );
function xtabla_actions() {
  include( plugin_dir_path( __DIR__ ) . 'inc/actions.php' );
}

/*
 * Admin scripts and styles
 */
function xtabla_wp_admin_assets( $hook ) {  
  wp_register_style( 'xtabla_admin_styles', plugin_dir_url( __DIR__ ) . '/css/admin.css', false, '1.0.0' );
  wp_register_script('xtabla_admin_js', plugin_dir_url( __DIR__ ) . '/js/admin.js', array('jquery'), '', true );
  
  if ( $hook != 'toplevel_page_xtabla' ) { return; }

  wp_enqueue_style( 'xtabla_admin_styles' );
  wp_localize_script( 'xtabla_admin_js', 'wp_data', array( 
    'ajax_url' => admin_url( 'admin-ajax.php' ),
    'plugin_url' => plugin_dir_url( __DIR__ ),
  ) );
  wp_enqueue_script( 'xtabla_admin_js' );
}
add_action( 'admin_enqueue_scripts', 'xtabla_wp_admin_assets' );


/*
 * Frontend scripts and styles
 */
function xtabla_scripts_and_styles() {
  global $post, $node_modules_path;
  // styles
  wp_register_style('basictable_css', $node_modules_path . '/basictable/basictable.css');
  wp_register_style('xtabla_css', plugins_url('/css/style.css',  __DIR__ ));
  // scripts
  wp_register_script('basictable_js', $node_modules_path . '/basictable/jquery.basictable.min.js', array('jquery'), '', true );
  wp_register_script('xtabla_frontend_js', plugin_dir_url( __DIR__ ) . '/js/frontend.js', array('jquery'), '', true );

  $shortcodePresent = has_shortcode( $post->post_content, 'xtabla');
  if ( $shortcodePresent ) {
    wp_enqueue_style('basictable_css');
    wp_enqueue_style('xtabla_css');
    wp_enqueue_script('basictable_js');
    wp_enqueue_script('xtabla_frontend_js');
  }
}
add_action('wp_enqueue_scripts', 'xtabla_scripts_and_styles');