# Ambient Logic for WordPress

Plugin to customize where and how to embed a sound map from [Ambient Logic](http://www.ambient-logic.com/) into your WordPress site.

## Installation

Upload the [zip file](https://github.com/asommer70/ambient-logic-wordpress/releases) to your WordPress site in the Plugins Admin page and install the plugin.

Go to the Ambient Logic Settings page and enter your key.

Place the shortcode on a page where you'd like the Ambient Logic map to appear.

For example:

```
[ambient_logic lat='36.2088' lon='-81.6628']
```

## Action Hook

Ambient Logic maps can also be integrated into themes, plugins, etc using the **ambient_logic_map** action hook. In the *functions.php*, or plugin file, add:

```
do_action(
  'ambient_logic_map',
  $latitude',
  $longitude'
);
```

**Note:** the *$latitude* and *$longitude<* variables have to be defined by you.


In the theme files add the following to display the map:

```
<?php echo $wp_query->ambient_logic_map; ?>
```

For more information on do_action see the [WordPress Documentation](https://developer.wordpress.org/reference/functions/do_action/).
