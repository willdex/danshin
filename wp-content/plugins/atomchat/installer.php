<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/***

	* WordPress default function
	* wp_enqueue_style, wp_enqueue_script and esc_url
*/

wp_enqueue_style("installer", plugin_dir_url( __FILE__ ).'css/installer.css');

wp_enqueue_script("installer", plugin_dir_url( __FILE__ ).'js/installer.js');
wp_enqueue_script('jquery');
if(!function_exists('curl_init')){
	wp_enqueue_script("jqcurl", plugin_dir_url( __FILE__ ).'js/checkcurl.js');
}
//$atomchat_logo = esc_url(plugin_dir_url(__FILE__).'images/atom_chat_black_icon_logo.png');
$atomchat_logo = esc_url(plugin_dir_url(__FILE__).'images/atomchat_final_logo.svg');

?>
<!DOCTYPE html>
<html>
<head>
	<title>AtomChat | Installer</title>
</head>
<body>
	<div class="container" style="">
	  	<div class="row">
			<div class="ccplugin_outerframe">
				<div class="ccplugin_middleform" >
					<div class="atomchat_logo_div">
						
					</div>
					<div class="module form-module" id="license-form">
						<div class="atomchat_form">
							<!--Skip for now-->
							<div class="delete-icon" onclick="skipFornow()">
							<a style="font-size: 16px;text-decoration: none;color: #000;font-weight: 600;">X</a>
							<p class="hover-text">Add later from AtomChat</p>
							</div>
							<!--End Skip for now-->

							<!--AtomChat new code-->
							<img src="<?php echo esc_url($atomchat_logo); ?>" class="atomchat_logo_image">
							<h2 class="ins-heading">Step 1: License Key</h2>
							<p>To activate the plugin, please enter your AtomChat License Key from your <a href="https://app.atomchat.com" target="_blank">dashboard </a></p>
							<hr style="margin-bottom: 20px;">
							<!--End AtomChat new code-->


							<!-- <h2 >AtomChat Installation Process</h2> -->
							<form action="javascript:atomchatInstall();" method="post" id="ccInstallProcess">
								<label class="ins-label">Enter license key</label>
								<input type="text" name="license" id= "license" placeholder="Enter license key" required="true" />
								<input type="submit" name="submit" id="atomchat_install" value="Activate License" />
								<input type="hidden" name="currentTime" class="login_inputbox currentTime">
							</form>
						</div>
						<div id="" class="col-sm-12" style="color:red;padding-top:1px;text-align: center;padding-bottom: 3em;">
							<a target="_blank" href="https://www.atomchat.com/pricing">Don't have a license key?</a>
						</div>
					</div>

					<div class="col-lg-12" id="" style="">
						<div class="col-sm-2" id="" style=""></div>
						<div class="col-sm-8" id="installer-process" style="display: none;">
							<h3 style="text-align: center;">Installation Process</h3>
							<p>Progress:</p>
							<div class="progress">
								<div id="progressbar" class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" >
								<!-- 40% -->
								</div>
							</div>
						</div>
						<div class="col-sm-2" id="" style=""></div>
					</div>

					<div id="error" class="col-sm-12" style="color:red;padding-top:30px;display:none;text-align: center;">
					If you face any issue with the update, please contact our support team to assist you.
					</div>
					<div id="cancel-btn" class="row col-md-12" style="display:none;text-align: center;padding-top:30px;">
					<a href="" class="btn btn-danger">Cancel</a>
					</div>
				</div>
			</div>
	    </div>
	</div>
</body>

</html>