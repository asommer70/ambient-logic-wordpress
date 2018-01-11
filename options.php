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

            <h2>Shortcodes</h2>

            <pre>
              [ambient_logic lat=[YOUR_LATITUDE] lon=[YOUR_LONGITUDE]]
            </pre>

            <p>For example:</p>

            <pre>[ambient_logic lat='36.2088' lon='-81.6628']</pre>
					</div>
				</div>
			</div>

		<br class="clear">
	</div>
</div>
