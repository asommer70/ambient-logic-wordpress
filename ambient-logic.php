<?php
/*
Plugin Name: Ambient Logic for WordPress
Plugin URI: https://http://www.ambient-logic.com/
Description: Plugin to customize where and how to embed a sound map from Ambient Logic into your WordPress site.
Version: 0.0.1
Author: Adam Sommer
Author URI: https://thehoick.com/
License: MIT
*/


//
// Admin page with a form for entering the API key and documentation for using the shortcode.
//
function ambient_logic_menu() {
  add_options_page(
    'Ambient Logic for WordPress',
    'Ambient Logic',
    'edit_pages',
    'ambient-logic',
    'ambient_logic_options_page'
  );
}
add_action('admin_menu', 'ambient_logic_menu');

function ambient_logic_options_page() {
  if (!current_user_can('edit_pages')) {
    wp_die('You do not have sufficient permission to access this page.');
  }

  $flash = '';
  $ambient_logic_api_key = get_option('ambient_logic_api_key');

  // Handle saving the API key.
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['save_ambient_logic_api_key'])) {
      $new_key = filter_input(INPUT_POST, 'api_key', FILTER_SANITIZE_SPECIAL_CHARS);

      if ($ambient_logic_api_key) {
        update_option( 'ambient_logic_api_key', $new_key );
      } else {
        add_option( 'ambient_logic_api_key', $new_key, '', 'no' );
      }

      $ambient_logic_api_key = $new_key;
      $flash = 'Ambient Logic API Key saved!';
    }
  }

  require(__DIR__ .'/options.php');
}


///
// Handle shortcode for embedding the HTML on a page.
//
function ambient_logic_shortcode($atts, $content = null) {
  extract(shortcode_atts([
    'lat' => '',
    'lon' => ''
  ],  $atts));

  $ambient_logic_api_key = get_option('ambient_logic_api_key');

  // $content .= '<script src="https://s3.amazonaws.com/ambient-logic/Ambient-Logic-Map.js" key="'. $ambient_logic_api_key;
  $content .= '<script src="/Ambient-Logic-Map.js" key="'. $ambient_logic_api_key;
  $content .= '" lat='. $lon .' lng='. $lat .' w=300 h=300 text="true" ></script>';

  return $content;
}
add_shortcode('ambient_logic', 'ambient_logic_shortcode');

//
// Action hook for calling the API and embedding the HTML from another plugin.
//
function ambient_logic_add_map($lat, $lon) {
  global $wp_query;
  $ambient_logic_api_key = get_option('ambient_logic_api_key');

  // $content .= '<script src="https://s3.amazonaws.com/ambient-logic/Ambient-Logic-Map.js" key="'. $ambient_logic_api_key;
  $wp_query->ambient_logic_map .= '<script src="/Ambient-Logic-Map.js" key="'. $ambient_logic_api_key;
  $wp_query->ambient_logic_map .= '" lat='. $lon .' lng='. $lat .' w=300 h=300 text="true" ></script>';
}
add_action( 'ambient_logic_map', 'ambient_logic_add_map', 10, 2 );
