<h2>Documentation</h2>

<h3>Shortcode</h3>

<pre style="tab-size: 2;">
  [ambient_logic lat=[YOUR_LATITUDE] lon=[YOUR_LONGITUDE]]
</pre>

<p>For example:</p>

<pre style="tab-size: 2;">[ambient_logic lat="36.1956595" lon="-81.7125616"]</pre>

<p>
  There are additional options for the shortcode:
</p>

<pre style="tab-size: 2;">
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
</pre>

<p>
  <strong>Note:</strong> if you supply and <em>address</em> and <em>lat/lon</em> the
  <strong>address</strong> option will take precedence.
</p>

<p>
  <strong>"zoom":</strong> This is a number that allows you to set the zoom level of the map.
   Valid values are 10 (zoomed out to the county level) to 16 (zoomed into the address level).
   E.g. "zoom = 12"
</p>

<p>
  <strong>"arrow":</strong> This is an argument that determines if you show an arrow on
  the right side of the legend identifying where your Ambient Noise Score lies.
</p>

<p>
  <strong>"scale:"</strong> This is an argument that determines
  if you show the scale line on the map.
</p>

<p>
  <strong>"text":</strong> This is a Boolean argument that determines if the legend text will be
   included to the left of the color bar. Default is “true”. E.g. “text = true”.
</p>

<p>
  <strong>"score":</strong> This is an argument that determines if numerical Ambient Noise
  Score will be shown on the marker on the map.
</p>

<p>
  <strong>"bwmap":</strong> This is an argument that determines if a simplified black and white map
  is used in place of the Open Street Map as a background layer. This type of map is sometimes better
  in areas where the Open Street Map layer has too many elements on it and becomes confusing.
</p>

<br/>
<hr/>
<br/>

<h3>Action Hook</h3>

<p>
  Ambient Logic maps can also be integrated into themes, plugins, etc
  using the <strong>ambient_logic_map</strong> action hook.  In the <em>functions.php</em>, or plugin
  file, add:
</p>

<pre style="tab-size: 2;">
  $options = [
    'lat' => $latitude,
    'lon' => $longitude,
    'width' => '400',
    'height' => '500'
  ];

  do_action( 'ambient_logic_map', $options );
</pre>

<p>
  <strong>Note:</strong> the <em>$latitude</em> and <em>$longitude</em> variables have to be defined by you.
</p>


<p>The <strong>$options</strong> array can have the same options as the shortcode:</p>

<pre style="tab-size: 2;">
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
</pre>


<p>In the theme files add the following to display the map:</p>

<pre style="tab-size: 2;">
  &lt;?php echo $wp_query->ambient_logic_map; ?&gt;
</pre>

<p>
  For more information on <strong>do_action</strong> see the
  <a href="https://developer.wordpress.org/reference/functions/do_action/">WordPress Documentation</a>.
</p>
