<?php

//ChildThemeWP.com Options Form
function childthemewpdotcom_theme_options_page()
{ 
?>
<div>
	<style>
		table.childthemewpdotcom {table-layout: fixed ;  width: 100%; vertical-align:top; }
		table.childthemewpdotcom td { width:50%; vertical-align:top; padding:0px 20px; }
		#childthemewpdotcom_settings { padding:0px 20px; }
	</style> 
	<div id="childthemewpdotcom_settings">
		<h1>Child Theme Options</h1>
	</div>
	<table class="childthemewpdotcom">
		<tr>
			<td>
                <form method="post" action="options.php">
                	<h2>Parent Theme Stylesheet Include or Exclude</h2>
                	<?php settings_fields( "childthemewpdotcom_theme_options_group" ); ?>
					<p><label><input size="76" type="checkbox" name="childthemewpdotcom_setting_x" id="childthemewpdotcom_setting_x"
					<?php if((esc_attr(get_option("childthemewpdotcom_setting_x")) == "Yes")) {   echo " checked='checked' ";  }  ?>
					value="Yes" > 
					TICK To DISABLE The Parent Stylesheet style.css In Your Site HTML<br><br>
                    ONLY TICK This Box If When You Inspect Your Source Code It Contains Your Parent Stylesheet style.css Two Times. Ticking This Box Will Only Include It Once.</label></p>
					<?php submit_button(); ?>
				</form>	
			</td>
			<td>
				<h2>More From The Author</h2>
                <p><b>Would you like your website speed to be faster?</b> I used WP Engine to build one of the fastest WordPress websites in the World <a href="https://shareasale.com/r.cfm?b=779590&u=1897845&m=41388&urllink=&afftrack=">WP Engine - Get 3 months free on annual plans</a> [affiliate link]</p>
				<p><b>Find out about how I built one fo the fastest WordPress websites in the World</b> <a href="https://www.wpspeedupoptimisation.com?ref=ChildThemeWP" target="_blank">I followed these steps</a></p>
			</td>
		</tr>
	</table>
</div>
<?php
} 
