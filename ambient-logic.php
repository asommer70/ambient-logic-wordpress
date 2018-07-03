<?php
/*
Plugin Name: Ambient Logic for WordPress
Plugin URI: http://www.ambient-logic.com/developers
Description: Plugin to customize where and how to embed a map from Ambient Logic into your WordPress site.
Version: 0.0.3
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
  $ambient_logic_map_options = json_decode(get_option('ambient_logic_map_options'));

  // Handle saving the API key.
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['save_ambient_logic_api_key'])) {
      $new_key = filter_input(INPUT_POST, 'api_key', FILTER_SANITIZE_SPECIAL_CHARS);

      if (isset($ambient_logic_api_key)) {
        update_option( 'ambient_logic_api_key', $new_key );
      } else {
        add_option( 'ambient_logic_api_key', $new_key, '', 'no' );
      }

      $ambient_logic_api_key = $new_key;
      $flash = 'Ambient Logic API Key saved!';

    } else if (isset($_POST['save_ambient_logic_map_settings'])) {
      $new_map_options = [
        'address' => filter_input(INPUT_POST, 'address', FILTER_SANITIZE_SPECIAL_CHARS),
        'mapID' => filter_input(INPUT_POST, 'mapID', FILTER_SANITIZE_SPECIAL_CHARS),
        'height' => filter_input(INPUT_POST, 'height', FILTER_SANITIZE_SPECIAL_CHARS),
        'width' => filter_input(INPUT_POST, 'width', FILTER_SANITIZE_SPECIAL_CHARS),
        'textOn' => filter_input(INPUT_POST, 'textOn', FILTER_SANITIZE_SPECIAL_CHARS),
        'showArrow' => filter_input(INPUT_POST, 'showArrow', FILTER_SANITIZE_SPECIAL_CHARS),
        'showScore' => filter_input(INPUT_POST, 'showScore', FILTER_SANITIZE_SPECIAL_CHARS),
        'showScaleline' => filter_input(INPUT_POST, 'showScaleline', FILTER_SANITIZE_SPECIAL_CHARS),
        'bwMap' => filter_input(INPUT_POST, 'bwMap', FILTER_SANITIZE_SPECIAL_CHARS),
        'horizontal' => filter_input(INPUT_POST, 'horizontal', FILTER_SANITIZE_SPECIAL_CHARS),
        'zoom' => filter_input(INPUT_POST, 'zoom', FILTER_SANITIZE_SPECIAL_CHARS)
      ];

      if (isset($ambient_logic_map_options)) {
        update_option( 'ambient_logic_map_options', json_encode($new_map_options));
      } else {
        add_option( 'ambient_logic_map_options', json_encode($new_map_options), '', 'no' );
      }

      // Get the saved options to match the decoded JSON from a GET request.
      $ambient_logic_map_options = json_decode(get_option('ambient_logic_map_options'));
      $flash = 'Ambient Logic Map Settings saved!';
    }
  }

  require(__DIR__ .'/admin/index.php');
}


//
// Add admin CSS and JavaScripts.
//
function ambient_logic_admin_css_and_js() {
  wp_enqueue_style('admin_css', plugins_url('ambient-logic-wordpress/assets/css/admin.css'));

  wp_enqueue_script('admin_js', plugins_url('ambient-logic-wordpress/assets/js/admin.js'), ['jquery'], '', true);
}
add_action('admin_head', 'ambient_logic_admin_css_and_js');


//
// Handle shortcode for embedding the HTML on a page.
//
function ambient_logic_shortcode($atts, $content = null) {
  extract(shortcode_atts([
    'lat' => '',
    'lon' => '',
    'address' => ''
  ],  $atts));

  $ambient_logic_api_key = get_option('ambient_logic_api_key');
  $ambient_logic_map_options = json_decode(get_option('ambient_logic_map_options'));

  $map_id = (isset($ambient_logic_map_options->mapID) && !empty($ambient_logic_map_options->mapID) ? $ambient_logic_map_options->mapID : 'ambientLogic-89178');
  $height = (isset($ambient_logic_map_options->height) && !empty($ambient_logic_map_options->height) ? $ambient_logic_map_options->height : 400);
  $width =  (isset($ambient_logic_map_options->width) && !empty($ambient_logic_map_options->width) ? $ambient_logic_map_options->width: 400);
  $text_on = (isset($ambient_logic_map_options->textOn) ? 'true' : 'false');
  $show_arrow = (isset($ambient_logic_map_options->showArrow) ? 'true' : 'false');
  $show_score = (isset($ambient_logic_map_options->showScore) ? 'true' : 'false');
  $show_scale_line = (isset($ambient_logic_map_options->showScaleline) ? 'true' : 'false');
  $bw_map = (isset($ambient_logic_map_options->bwMap) ? 'true' : 'false');
  $zoom = (isset($ambient_logic_map_options->zoom) && !empty($ambient_logic_map_options->zoom) ? $ambient_logic_map_options->zoom : 14);
  $horizontal = (isset($ambient_logic_map_options->horizontal) && !empty($ambient_logic_map_options->horizontal) ? $ambient_logic_map_options->horizontal : 'false');

  // Setup the <script> tag for the Ambient Logic JavaScript SDK.
  // $content .= "\n\n<script type='text/javascript'>\n";
  // $content .= " var mapOptions = {\n";
  // $content .= "   address: '$address',\n";
  // $content .= "   mapID: '$map_id',\n";
  // $content .= "   h: $height,\n";
  // $content .= "   w: $width,\n";
  // $content .= "   textOn: $text_on,\n";
  // $content .= "   showArrow: $show_arrow,\n";
  // $content .= "   showScore: $show_score,\n";
  // $content .= "   showScaleline: $show_scale_line,\n";
  // $content .= "   bwMap: $bw_map,\n";
  // $content .= "   zoom: $zoom,\n";
  // $content .= "   horizontal: $horizontal,\n";
  // $content .= "   key: '$ambient_logic_api_key'\n";
  // $content .= "}\n\n";

  // $content .=  "AmbientLogic(mapOptions).build(function(error, data) {\n";
  // $content .=  "    if (error) {\n";
  // $content .=  "      console.log('front-end error:', error, 'data:', data);\n";
  // $content .=  "    } ";
  // $content .= "  });\n";
  // $content .= "</script>\n";

  // $content .= "<div class='$map_id'></div>\n\n";

  ob_start();
  require(__DIR__ .'/template.php');
  $content = ob_get_clean();

  return $content;
}
add_shortcode('ambient_logic', 'ambient_logic_shortcode');


//
// Allow do_action for embedding a map into a template.
//
function ambient_logic_add_map() {
  global $wp_query;
  global $wpdb;
  $ambient_logic_api_key = get_option('ambient_logic_api_key');
  $ambient_logic_map_options = json_decode(get_option('ambient_logic_map_options'));


  $address = '';
  if (isset($_GET['address'])) {
    // Sanitize the address.
    $address = filter_input(INPUT_GET, 'address', FILTER_SANITIZE_SPECIAL_CHARS);
  } else {
    $address = $ambient_logic_map_options->address;
  }

  // Setup the <script> tag for the Ambient Logic JavaScript SDK.
  ?>
  <script type="text/javascript">
    var mapOptions = {
      address: <?php echo (isset($address) && !empty($address) ? '"'. $address .'"' : "\"89 Saint Dunstan's Rd, Asheville NC\""); ?>,
      mapID: <?php echo (isset($ambient_logic_map_options->mapID) && !empty($ambient_logic_map_options->mapID) ? '"'. $ambient_logic_map_options->mapID .'"' : '"ambientLogic-89178"'); ?>,
      h: <?php echo (isset($ambient_logic_map_options->height) && !empty($ambient_logic_map_options->height) ? $ambient_logic_map_options->height : 400); ?>,
      w: <?php echo (isset($ambient_logic_map_options->width) && !empty($ambient_logic_map_options->width) ? $ambient_logic_map_options->width: 400); ?>,
      textOn: <?php echo (isset($ambient_logic_map_options->textOn) ? 'true' : 'false'); ?>,
      showArrow: <?php echo (isset($ambient_logic_map_options->showArrow) ? 'true' : 'false'); ?>,
      showScore: <?php echo (isset($ambient_logic_map_options->showScore) ? 'true' : 'false'); ?>,
      showScaleline: <?php echo (isset($ambient_logic_map_options->showScaleline) ? 'true' : 'false'); ?>,
      bwMap: <?php echo (isset($ambient_logic_map_options->bwMap) ? 'true' : 'false'); ?>,
      zoom: <?php echo (isset($ambient_logic_map_options->zoom) && !empty($ambient_logic_map_options->zoom) ? $ambient_logic_map_options->zoom : 14); ?> ,
      horizontal: <?php echo (isset($ambient_logic_map_options->horizontal) && !empty($ambient_logic_map_options->horizontal) ? $ambient_logic_map_options->horizontal : 'false'); ?>,
      key: "<?php echo $ambient_logic_api_key; ?>"
    };


    AmbientLogic(mapOptions).build(function(error, data) {
      if (error) {
        console.log('front-end error:', error, 'data:', data);
      }
    });
  </script>
  <?php
}
add_action( 'ambient_logic_map', 'ambient_logic_add_map', 10, 1 );

//
// Include the JavaScript for Ambient Logic.
//
function ambient_logic_enqueus_js() {
  wp_enqueue_script(
    'ambient_logic_map',
    plugins_url('ambient-logic-wordpress/assets/js/Ambient-Logic-Map.js'),
		array(),
    '0.0.1',
    false
	);
}
add_action('wp_enqueue_scripts', 'ambient_logic_enqueus_js');

// ///
// // Handle shortcode for embedding the HTML on a page.
// //
// function ambient_logic_shortcode($atts, $content = null) {
//   extract(shortcode_atts([
//     'lat' => '',
//     'lon' => '',
//     'address' => '',
//     'width' => '300',
//     'height' => '300',
//     'arrow' => 'true',
//     'zoom' => '14',
//     'scale' => 'true',
//     'text' => 'true',
//     'score' => 'true',
//     'bwmap' => 'false'
//   ],  $atts));

//   $ambient_logic_api_key = get_option('ambient_logic_api_key');

//   // $content .= '<script src="https://s3.amazonaws.com/ambient-logic/Ambient-Logic-Map.js" ';
//   $content .= '<script src="/Ambient-Logic-Map-0.2.js" ';

//   $content .= 'key="'. $ambient_logic_api_key .'" ';
//   if ($address != '') {
//     $content .= 'address="'. $address .'" ';
//   } else {
//     $content .= 'lat='. $lat .' lng='. $lon .' ';
//   }

//   $content .= 'arrow="'. $arrow .'" ';
//   $content .= 'zoom="'. $zoom .'" ';
//   $content .= 'scale="'. $scale .'" ';
//   $content .= 'text="'. $text .'" ';
//   $content .= 'score="'. $score .'" ';
//   $content .= 'bwmap="'. $bwmap .'" ';
//   $content .= 'w='. $width .' h='. $height .' text="true"> </script>';

//   return $content;
// }
// add_shortcode('ambient_logic', 'ambient_logic_shortcode');

//
// Action hook for calling the API and embedding the HTML from another plugin.
//
// function ambient_logic_add_map($map_options) {
//   global $wp_query;
//   $ambient_logic_api_key = get_option('ambient_logic_api_key');


//   // $wp_query->ambient_logic_map .= '<script src="https://s3.amazonaws.com/ambient-logic/Ambient-Logic-Map.js" ';
//   $wp_query->ambient_logic_map .= '<script src="/Ambient-Logic-Map-0.2.js" ';

//   $wp_query->ambient_logic_map .= 'key="'. $ambient_logic_api_key .'" ';
//   if (isset($map_options['address'])) {
//     $wp_query->ambient_logic_map .= 'address="'. $address .'" ';
//   } else {
//     $wp_query->ambient_logic_map .= 'lat='. $map_options['lat'] .' lng='. $map_options['lon'] .' ';
//   }

//   $wp_query->ambient_logic_map .= 'arrow="'. (isset($map_options['arrow']) ? $map_options['arrow'] : 'true') .'" ';
//   $wp_query->ambient_logic_map .= 'zoom="'. (isset($map_options['zoom']) ? $map_options['zoom'] : '14') .'" ';
//   $wp_query->ambient_logic_map .= 'scale="'. (isset($map_options['scale']) ? $map_options['scale'] : 'true') .'" ';
//   $wp_query->ambient_logic_map .= 'text="'. (isset($map_options['text']) ? $map_options['text'] : 'true') .'" ';
//   $wp_query->ambient_logic_map .= 'score="'. (isset($map_options['score']) ? $map_options['score'] : 'true') .'" ';
//   $wp_query->ambient_logic_map .= 'bwmap="'. (isset($map_options['bwmap']) ? $map_options['bwmap'] : 'false') .'" ';
//   $wp_query->ambient_logic_map .= 'w='. $map_options['width'] .' h='. $map_options['height'] .' text="true"> </script>';
// }
// add_action( 'ambient_logic_map', 'ambient_logic_add_map', 10, 1 );
