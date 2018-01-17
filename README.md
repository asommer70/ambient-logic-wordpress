# Ambient Logic for WordPress

Plugin to customize where and how to embed a sound map from [Ambient Logic](http://www.ambient-logic.com/) into your WordPress site.

## Installation

Upload the [zip file](https://github.com/asommer70/ambient-logic-wordpress/releases) to your WordPress site in the Plugins Admin page and install the plugin.

Go to the Ambient Logic Settings page and enter your key.

Place the shortcode on a page where you'd like the Ambient Logic map to appear.

For example:

```
[ambient_logic lat='36.2088' lon='-81.6628' bwmap="true" zoom="12"]
```

There are additional options for the shortcode:

```
lat="36.1956595"
lon="-81.7125616"
address="120 Honey Bear Campground Boone, NC 28607"
width="400"
height="500"
zoom="14"
arrow="true"
scale="true"
text="true"
score="true"
bwmap="false"
```

**Note:** if you supply and *address* and *lat/lon* the *address* option will take precedence.

**"zoom":** This is a number that allows you to set the zoom level of the map. Valid values are 10 (zoomed out to the county level) to 16 (zoomed into the address level). E.g. "zoom = 12"

**"arrow":** This is an argument that determines if you show an arrow on the right side of the legend identifying where your Ambient Noise Score lies.

**"scale:"** This is an argument that determines if you show the scale line on the map.

**"text":** This is a Boolean argument that determines if the legend text will be included to the left of the color bar. Default is “true”. E.g. “text = true”.

**"score":** This is an argument that determines if numerical Ambient Noise Score will be shown on the marker on the map.

**"bwmap":** This is an argument that determines if a simplified black and white map is used in place of the Open Street Map as a background layer. This type of map is sometimes better in areas where the Open Street Map layer has too many elements on it and becomes confusing.


## Action Hook

Ambient Logic maps can also be integrated into themes, plugins, etc using the **ambient_logic_map** action hook. In the *functions.php*, or plugin file, add:

```
$options = [
  'lat' => $latitude,
  'lon' => $longitude,
  'width' => '400',
  'height' => '500'
];

do_action( 'ambient_logic_map', $options );
```

**Note:** the *$latitude* and *$longitude<* variables have to be defined by you.

The **$options** array can have the same options as the shortcode:

```
$options = [
  'lat' => $latitude,
  'lon' => $longitude,
  'width' => '400',
  'height' => '500',
  'zoom' => '14',
  'arrow' => 'true',
  'scale' => 'true',
  'text' => 'true',
  'score' => 'true',
  'bwmap' => 'false'
];
```

In the theme files add the following to display the map:

```
<?php echo $wp_query->ambient_logic_map; ?>
```

For more information on do_action see the [WordPress Documentation](https://developer.wordpress.org/reference/functions/do_action/).
