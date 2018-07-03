  <script type="text/javascript">
    var mapOptions = {
      address: <?php echo (isset($address) && !empty($address) ? '"'. $address .'"' : "\"89 Saint Dunstan's Rd, Asheville NC\""); ?>,
      mapID: <?php echo (isset($map_id) && !empty($map_id) ? '"'. $map_id .'"' : '"ambientLogic-89178"'); ?>,
      h: <?php echo (isset($height) && !empty($height) ? $height : 400); ?>,
      w: <?php echo (isset($width) && !empty($width) ? $width: 400); ?>,
      textOn: <?php echo (isset($text_on) ? 'true' : 'false'); ?>,
      showArrow: <?php echo (isset($show_arrow) ? 'true' : 'false'); ?>,
      showScore: <?php echo (isset($show_score) ? 'true' : 'false'); ?>,
      showScaleline: <?php echo (isset($show_scale_line) ? 'true' : 'false'); ?>,
      bwMap: <?php echo (isset($bw_map) ? 'true' : 'false'); ?>,
      zoom: <?php echo (isset($zoom) && !empty($zoom) ? $zoom : 14); ?> ,
      horizontal: <?php echo (isset($horizontal) && !empty($horizontal) ? $horizontal : 'false'); ?>,
      key: "<?php echo $ambient_logic_api_key; ?>"
    };


    AmbientLogic(mapOptions).build(function(error, data) {
      if (error) {
        console.log('front-end error:', error, 'data:', data);
      }
    });
  </script>

<div class="<?php echo $map_id; ?>"></div>
