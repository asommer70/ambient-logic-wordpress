<?php
/*
Plugin Name: Ambient Logic for WordPress
Plugin URI: http://www.ambient-logic.com/developers
Description: Plugin to customize where and how to embed a sound map from Ambient Logic into your WordPress site.
Version: 0.0.2
Author: Adam Sommer
Author URI: https://thehoick.com/
License: MIT
*/


//
// Add settings link on plugin page.
//
function abmient_logic_settings_link($links) {
  $settings_link = '<a href="options-general.php?page=ambient-logic">Settings</a>';
  array_unshift($links, $settings_link);
  return $links;
}
$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'abmient_logic_settings_link' );


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
    'lon' => '',
    'address' => '',
    'width' => '300',
    'height' => '300',
    'arrow' => 'true',
    'zoom' => '14',
    'scale' => 'true',
    'text' => 'true',
    'score' => 'true',
    'bwmap' => 'false'
  ],  $atts));

  $ambient_logic_api_key = get_option('ambient_logic_api_key');

  // $content .= '<script src="https://s3.amazonaws.com/ambient-logic/Ambient-Logic-Map.js" ';
  $content .= '<script src="/Ambient-Logic-Map-0.2.js" ';

  $content .= 'key="'. $ambient_logic_api_key .'" ';
  if ($address != '') {
    $content .= 'address="'. $address .'" ';
  } else {
    $content .= 'lat='. $lat .' lng='. $lon .' ';
  }

  $content .= 'arrow="'. $arrow .'" ';
  $content .= 'zoom="'. $zoom .'" ';
  $content .= 'scale="'. $scale .'" ';
  $content .= 'text="'. $text .'" ';
  $content .= 'score="'. $score .'" ';
  $content .= 'bwmap="'. $bwmap .'" ';
  $content .= 'w='. $width .' h='. $height .' text="true"> </script>';

  return $content;
}
add_shortcode('ambient_logic', 'ambient_logic_shortcode');

//
// Action hook for calling the API and embedding the HTML from another plugin.
//
function ambient_logic_add_map($map_options) {
  global $wp_query;
  $ambient_logic_api_key = get_option('ambient_logic_api_key');


  // $wp_query->ambient_logic_map .= '<script src="https://s3.amazonaws.com/ambient-logic/Ambient-Logic-Map.js" ';
  $wp_query->ambient_logic_map .= '<script src="/Ambient-Logic-Map-0.2.js" ';

  $wp_query->ambient_logic_map .= 'key="'. $ambient_logic_api_key .'" ';
  if (isset($map_options['address'])) {
    $wp_query->ambient_logic_map .= 'address="'. $address .'" ';
  } else {
    $wp_query->ambient_logic_map .= 'lat='. $map_options['lat'] .' lng='. $map_options['lon'] .' ';
  }

  $wp_query->ambient_logic_map .= 'arrow="'. (isset($map_options['arrow']) ? $map_options['arrow'] : 'true') .'" ';
  $wp_query->ambient_logic_map .= 'zoom="'. (isset($map_options['zoom']) ? $map_options['zoom'] : '14') .'" ';
  $wp_query->ambient_logic_map .= 'scale="'. (isset($map_options['scale']) ? $map_options['scale'] : 'true') .'" ';
  $wp_query->ambient_logic_map .= 'text="'. (isset($map_options['text']) ? $map_options['text'] : 'true') .'" ';
  $wp_query->ambient_logic_map .= 'score="'. (isset($map_options['score']) ? $map_options['score'] : 'true') .'" ';
  $wp_query->ambient_logic_map .= 'bwmap="'. (isset($map_options['bwmap']) ? $map_options['bwmap'] : 'false') .'" ';
  $wp_query->ambient_logic_map .= 'w='. $map_options['width'] .' h='. $map_options['height'] .' text="true"> </script>';
}
add_action( 'ambient_logic_map', 'ambient_logic_add_map', 10, 1 );
