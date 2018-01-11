<div class="wrap">

	<h1>Ambient Logic for WordPress</h1>

  <div class="flash">
    <?php echo $flash; ?>
  </div>

	<div id="poststuff">

		<div id="post-body" class="metabox-holder">

			<!-- main content -->
			<div id="post-body-content">
				<div class="meta-box-sortables ui-sortable">
					<div class="postbox">

						<div class="inside">

							<h2>Ambient Logic API Key</h2>

							<form action="#" method="post">

								<input
									type="text"
									name="api_key"
									id="api_key"
									placeholder="API Key"
									value="<?php echo $ambient_logic_api_key; ?>"
								/>

								<br><br>
								<input class="button-secondary"
									type="submit"
									name="save_ambient_logic_api_key"
									value="Save API Key"
								/>
							</form>

            <br><br>

            <hr/>
            <br/>

            <h2>Shortcode</h2>

            <pre>
              [ambient_logic lat=[YOUR_LATITUDE] lon=[YOUR_LONGITUDE]]
            </pre>

            <p>For example:</p>

            <pre>[ambient_logic lat='36.2088' lon='-81.6628']</pre>

            <br/>
            <hr/>
            <br/>

            <h2>Action Hook</h2>

            <p>
              Ambient Logic maps can also be integrated into themes, plugins, etc
              using the <strong>ambient_logic_map</strong> action hook.  In the <em>functions.php</em>, or plugin
              file, add:
            </p>

            <pre>
              do_action(
                'ambient_logic_map',
                $latitude',
                $longitude'
              );
            </pre>

            <p>
              <strong>Note:</strong> the <em>$latitude</em> and <em>$longitude</em> variables have to be defined by you.
            </p>

            <p>In the theme files add the following to display the map:</p>

            <pre>
              &lt;?php echo $wp_query->ambient_logic_map; ?&gt;
            </pre>

            <p>
              For more information on <strong>do_action</strong> see the
              <a href="https://developer.wordpress.org/reference/functions/do_action/">WordPress Documentation</a>.
            </p>
					</div>
				</div>
			</div>

		<br class="clear">
	</div>
</div>
