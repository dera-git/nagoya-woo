<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$plugin_dir_url =  plugin_dir_url( __DIR__ ); ?>

<style>
.pho-upgrade-btn > a:focus {
	box-shadow: none !important;
}
.premium-box {width: 100%;}
.premium-box-head {background: #eae8e7; width: 100%; height:500px; text-align: center;}
.pho-upgrade-btn {display: block; text-align: center;}
.pho-upgrade-btn a{display: inline-block;  margin-top: 75px;}
.pho-upgrade-btn a:focus {outline: none; box-shadow: none; }
.main-heading  {text-align: center; background: #fff; margin-bottom: -70px;}
.main-heading img {margin-top: -200px;}

.premium-box-container {margin: 0 auto;}
.premium-box-container .description {text-align: center; display: block; padding: 35px 0;}
.premium-box-container .description:nth-child(odd) {background: #fff;}
.premium-box-container .description:nth-child(even) {background: #eae8e7;}

.premium-box-container .pho-desc-head {width: 768px; margin: 0 auto; position: relative;}
.premium-box-container .pho-desc-head:after {background:url(<?php echo $plugin_dir_url; ?>images/head-arrow.png) no-repeat;
 position: absolute; right: -30px; top: -6px; width: 69px; height: 98px; content: "";} 

.premium-box-container .pho-desc-head h2 {color: #02c277; font-weight: bolder; font-size: 28px; text-transform: capitalize;margin: 0; line-height:35px;}
.pho-plugin-content {margin: 0 auto; width: 768px; overflow: hidden;}
.pho-plugin-content p {line-height: 32px; font-size: 18px; color: #212121; }
.pho-plugin-content img {width: auto; max-width: 100%;}
.description .pho-plugin-content ol { margin: 0; padding-left: 25px; text-align: left;}
.description .pho-plugin-content ol li {font-size: 16px; line-height: 28px; color: #212121; padding-left: 5px;}
.description .pho-plugin-content .pho-images-bg { width: 750px; margin: 0 auto; border-radius: 5px 5px 0 0; 
padding: 70px 0 40px; height: auto;}
.premium-box-container .description:nth-child(odd) .pho-images-bg {background: #f1f1f1 url(<?php echo $plugin_dir_url; ?>images/image-frame-odd.png) no-repeat 100% top;}
.premium-box-container .description:nth-child(even) .pho-images-bg {background: #f1f1f1 url(<?php echo $plugin_dir_url; ?>images/image-frame-even.png) no-repeat 100% top;}

</style>

<div class="premium-box">

    <div class="premium-box-head">
        <div class="pho-upgrade-btn">
        <a href="https://www.phoeniixx.com/product/woocommerce-custom-fields-for-variation/" target="_blank"><img src="<?php echo $plugin_dir_url; ?>images/premium-btn.png" /></a>
		<a target="blank" href="http://customvariationpro.phoeniixxdemo.com/shop/"><img src="<?php echo $plugin_dir_url; ?>images/button2.png" /></a>
        </div>
    </div>
    <div class="main-heading"><h1><img src="<?php echo $plugin_dir_url; ?>images/premium-head.png" /></h1></div>

        <div class="premium-box-container">
				<div class="description">
                <div class="pho-desc-head"><h2>General Settings</h2></div>
                
                    <div class="pho-plugin-content">
                        <p>These are the options you could find in the general settings. You can set the Custom option Position Before or After add to cart button.</p>
                        <div class="pho-images-bg">
                        <img src="<?php echo $plugin_dir_url; ?>images/general-settings.png" />
                        </div>
                    </div>
				</div> <!-- description end -->
				
				<div class="description">
                <div class="pho-desc-head"><h2>Custom Input Fields</h2></div>
                
                    <div class="pho-plugin-content">
                        <p>There are 10 Custom Input Fields - Custom Text Area , Custom Text Field , Checkbox , Radio Button , File Upload , Dropdown, Range Picker , Color Picker , Date and Time Picker , Quantity.</p>
                        <div class="pho-images-bg">
                        <img src="<?php echo $plugin_dir_url; ?>images/custom input fields.png" />
                        </div>
                    </div>
				</div> <!-- description end -->
			
            <div class="description">
                <div class="pho-desc-head"><h2>Section Style</h2></div>
                
                    <div class="pho-plugin-content">
                        <p>There is an option to set the Section Style as Normal or as Accordion.</p>
                        <div class="pho-images-bg">
                        <img src="<?php echo $plugin_dir_url; ?>images/section style.png" />
                        </div>
                    </div>
            </div> <!-- description end -->
            
            <div class="description">
                <div class="pho-desc-head"><h2>Option Description Type</h2></div>
                
                    <div class="pho-plugin-content">
                        <p>With this feature you can choose any option to show the option description. You can show it on a Message or in Tooltip. You could also hide the option description.</p>
                        <div class="pho-images-bg">
                        <img src="<?php echo $plugin_dir_url; ?>images/option description type.png" />
                        </div>
                    </div>
            </div> <!-- description end -->
            
            <div class="description">
                <div class="pho-desc-head"><h2>Option Price Type</h2></div>
                
                    <div class="pho-plugin-content">
                        <p>You could also set the Fixed Price or Percentage of the base price.</p>
                        <div class="pho-images-bg">
                        <img src="<?php echo $plugin_dir_url; ?>images/option price type.png" />
                        </div>
                    </div>
            </div> <!-- description end -->
			
			<div class="description">
                <div class="pho-desc-head"><h2>Option Label Position</h2></div>
                
                    <div class="pho-plugin-content">
                        <p>With this feature you could set the option label position also i.e before options or after options.</p>
                        <div class="pho-images-bg">
                        <img src="<?php echo $plugin_dir_url; ?>images/option label position.png" />
                        </div>
                    </div>
            </div> <!-- description end -->
            
            <div class="description">
                <div class="pho-desc-head"><h2>Required Fields</h2></div>
                
                    <div class="pho-plugin-content">
                        <p>You could make the fields madatory by tick on the Required Fields option.</p>
                        <div class="pho-images-bg">
                        <img src="<?php echo $plugin_dir_url; ?>images/required fields.png" />
                        </div>
                    </div>
            </div> <!-- description end -->
            
            <div class="description">
                <div class="pho-desc-head"><h2>Add  image</h2></div>
                
                    <div class="pho-plugin-content">
                        <p>In the Checkbox and Radio Button input field you can add the images.</p>
                        <div class="pho-images-bg">
                        <img src="<?php echo $plugin_dir_url; ?>images/image-option.png" />
                        </div>
                    </div>
            </div> <!-- description end -->
            
            <div class="description">
                <div class="pho-desc-head"><h2>Range Type</h2></div>
                
                    <div class="pho-plugin-content">
                        <p>In the Range Picker field you could also select the range type. There are 2 types - Fixed Price and Step * Price type. You can select any of them as per your choice.</p>
                        <div class="pho-images-bg">
                        <img src="<?php echo $plugin_dir_url; ?>images/range type.png" />
                        </div>
                    </div>
            </div> <!-- description end -->
            
            <div class="description">
                <div class="pho-desc-head"><h2>Conditional Logic</h2></div>
                
                    <div class="pho-plugin-content">
                        <p>You could select the conditions for the custom option fields.</p>
                        <div class="pho-images-bg">
                        <img src="<?php echo $plugin_dir_url; ?>images/conditional logic.png" />
                        </div>
                    </div>
            </div> <!-- description end -->
			
			<div class="description">
                <div class="pho-desc-head"><h2>Styling Options</h2></div>
                
                    <div class="pho-plugin-content">
                        <p>There are various advanced styling options.</p>
                        <div class="pho-images-bg">
                        <img src="<?php echo $plugin_dir_url; ?>images/Styling Options.png" />
                        </div>
                    </div>
            </div> <!-- description end -->
        </div> <!-- premium-box-container end -->
        
        <div class="pho-upgrade-btn">
        <a href="https://www.phoeniixx.com/product/woocommerce-custom-fields-for-variation/" target="_blank"><img src="<?php echo $plugin_dir_url; ?>images/premium-btn.png" /></a>
		<a target="blank" href="http://customvariationpro.phoeniixxdemo.com/shop/"><img src="<?php echo $plugin_dir_url; ?>images/button2.png" /></a>
        </div>

</div> <!-- premium-box end -->