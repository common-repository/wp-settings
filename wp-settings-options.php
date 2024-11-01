<?php ?>
<div class="wrap">
	<div class="postbox">
		<h2><?php _e('WP Settings', 'wp-settings'); ?></h2>
	    <p><?php echo ilc_admin_tabs(); ?></p>
		<p><?php echo wpsettings_GetWPInfo(); ?></p> <!--this calls the method in wp-info.php-->
	</div>
</div>