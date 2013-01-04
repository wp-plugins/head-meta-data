<?php 
/*
	Plugin Name: Head Meta Data
	Plugin URI: http://perishablepress.com/head-metadata-plus/
	Description: Adds assorted &lt;meta&gt; tags and more to the &lt;head&gt; section of all posts &amp; pages.
	Author: Jeff Starr
	Author URI: http://monzilla.biz/
	Version: 20130103
	License: GPL v2
	Usage: Visit the plugin's settings page to configure your options.
	Tags: meta, head, wp_head, customize, author, publisher, language
*/

// NO EDITING REQUIRED - PLEASE SET PREFERENCES IN THE WP ADMIN!

$hmd_plugin  = __('Head Meta Data');
$hmd_options = get_option('hmd_options');
$hmd_path    = plugin_basename(__FILE__); // 'head-meta-data/head-meta-data.php';
$hmd_homeurl = 'http://perishablepress.com/head-metadata-plus/';
$hmd_version = '20130103';

// require minimum version of WordPress
add_action('admin_init', 'hmd_require_wp_version');
function hmd_require_wp_version() {
	global $wp_version, $hmd_path, $hmd_plugin;
	if (version_compare($wp_version, '3.4', '<')) {
		if (is_plugin_active($hmd_path)) {
			deactivate_plugins($hmd_path);
			$msg =  '<strong>' . $hmd_plugin . '</strong> ' . __('requires WordPress 3.4 or higher, and has been deactivated!') . '<br />';
			$msg .= __('Please return to the ') . '<a href="' . admin_url() . '">' . __('WordPress Admin area') . '</a> ' . __('to upgrade WordPress and try again.');
			wp_die($msg);
		}
	}
}

// insert head meta data
add_action('wp_head', 'head_meta_data');
function head_meta_data() { 
	echo hmd_display_content();
}

// shortcode to display head meta data
add_shortcode('head_meta_data','hmd_shortcode');
function hmd_shortcode() {
	$get_meta_data = hmd_display_content();
	$the_meta_data = str_replace(array('>', '<'), array('&gt;','&lt;'), $get_meta_data);
	return $the_meta_data;
}

// display head meta data
function hmd_display_content() {
	global $hmd_options;
	$hmd_output = '';
	$hmd_enable = $hmd_options['hmd_enable']; 
	$hmd_format = $hmd_options['hmd_format'];
	if ($hmd_format == false) {
		$close_tag = '" />' . "\n";
	} else {
		$close_tag = '">' . "\n";
	}
	if ($hmd_enable == true) {
		if ($hmd_options['hmd_abstract']   !== '') $hmd_output  = '<meta name="abstract" content="'       . $hmd_options['hmd_abstract']   . $close_tag;
		if ($hmd_options['hmd_author']     !== '') $hmd_output .= '<meta name="author" content="'         . $hmd_options['hmd_author']     . $close_tag;
		if ($hmd_options['hmd_classify']   !== '') $hmd_output .= '<meta name="classification" content="' . $hmd_options['hmd_classify']   . $close_tag;
		if ($hmd_options['hmd_copyright']  !== '') $hmd_output .= '<meta name="copyright" content="'      . $hmd_options['hmd_copyright']  . $close_tag;
		if ($hmd_options['hmd_designer']   !== '') $hmd_output .= '<meta name="designer" content="'       . $hmd_options['hmd_designer']   . $close_tag;
		if ($hmd_options['hmd_distribute'] !== '') $hmd_output .= '<meta name="distribution" content="'   . $hmd_options['hmd_distribute'] . $close_tag;
		if ($hmd_options['hmd_language']   !== '') $hmd_output .= '<meta name="language" content="'       . $hmd_options['hmd_language']   . $close_tag;
		if ($hmd_options['hmd_publisher']  !== '') $hmd_output .= '<meta name="publisher" content="'      . $hmd_options['hmd_publisher']  . $close_tag;
		if ($hmd_options['hmd_rating']     !== '') $hmd_output .= '<meta name="rating" content="'         . $hmd_options['hmd_rating']     . $close_tag;
		if ($hmd_options['hmd_resource']   !== '') $hmd_output .= '<meta name="resource-type" content="'  . $hmd_options['hmd_resource']   . $close_tag;
		if ($hmd_options['hmd_revisit']    !== '') $hmd_output .= '<meta name="revisit-after" content="'  . $hmd_options['hmd_revisit']    . $close_tag;
		if ($hmd_options['hmd_subject']    !== '') $hmd_output .= '<meta name="subject" content="'        . $hmd_options['hmd_subject']    . $close_tag;
		if ($hmd_options['hmd_template']   !== '') $hmd_output .= '<meta name="template" content="'       . $hmd_options['hmd_template']   . $close_tag;
	}
	return $hmd_output;
}

// insert custom content
add_action('wp_head', 'hmd_custom_content');
function hmd_custom_content() {
	global $hmd_options;
	if ($hmd_options['hmd_custom'] !== '') echo $hmd_options['hmd_custom'] . "\n";
}

// shortcode to display custom content
add_shortcode('hmd_custom','hmd_custom_shortcode');
function hmd_custom_shortcode() {
	global $hmd_options;
	if ($hmd_options['hmd_custom'] !== '') {
		$get_custom_data = $hmd_options['hmd_custom'];
		$the_custom_data = str_replace(array('>', '<'), array('&gt;','&lt;'), $get_custom_data);
		return $the_custom_data;
	}
}

// display settings link on plugin page
add_filter ('plugin_action_links', 'hmd_plugin_action_links', 10, 2);
function hmd_plugin_action_links($links, $file) {
	global $hmd_path;
	if ($file == $hmd_path) {
		$hmd_links = '<a href="' . get_admin_url() . 'options-general.php?page=' . $hmd_path . '">' . __('Settings') .'</a>';
		array_unshift($links, $hmd_links);
	}
	return $links;
}

// delete plugin settings
function hmd_delete_plugin_options() {
	delete_option('hmd_options');
}
if ($hmd_options['default_options'] == 1) {
	register_uninstall_hook (__FILE__, 'hmd_delete_plugin_options');
}

// define default settings
register_activation_hook (__FILE__, 'hmd_add_defaults');
function hmd_add_defaults() {
	// meta subject
	$args = array('orderby'=>'name', 'order'=>'ASC');
	$categories = get_categories($args);
	$num_cats = count($categories);
	$subjects = '';
	$i = 0;
	foreach ($categories as $category) { 
		$subjects .= $category->name;
		if (++$i !== $num_cats) {
			$subjects .= ', ';
		}
	}
	// name, description, language
	$site_name = get_bloginfo('name');
	$site_desc = get_bloginfo('description');
	$site_lang = get_bloginfo('language');
	// template and designer
	$get_theme = wp_get_theme();
	$the_theme = $get_theme->Name;
	$designer  = $get_theme->display('Author', FALSE);;
	// author name
	$user_info = get_userdata(1);
	if ($user_info == true) {
		$admin_name = $user_info->user_login;
	} else {
		$admin_name = 'Perishable';
	}
	$tmp = get_option('hmd_options');
	if(($tmp['default_options'] == '1') || (!is_array($tmp))) {
		$arr = array(
			'default_options' => 0,
			'hmd_abstract'    => $site_desc,
			'hmd_author'      => $admin_name,
			'hmd_classify'    => $subjects,
			'hmd_copyright'   => '&copy; ' . $site_name . ' - All rights Reserved.',
			'hmd_designer'    => $designer,
			'hmd_distribute'  => 'Global',
			'hmd_language'    => $site_lang,
			'hmd_publisher'   => $site_name,
			'hmd_rating'      => 'General',
			'hmd_resource'    => 'Document',
			'hmd_revisit'     => '3',
			'hmd_subject'     => $subjects,
			'hmd_template'    => $the_theme,
			'hmd_enable'      => 1,
			'hmd_custom'      => '<meta name=\'example\' content=\'custom\'>',
			'hmd_format'      => 1,
		);
		update_option('hmd_options', $arr);
	}
}

// whitelist settings
add_action ('admin_init', 'hmd_init');
function hmd_init() {
	register_setting('hmd_plugin_options', 'hmd_options', 'hmd_validate_options');
}

// sanitize and validate input
function hmd_validate_options($input) {

	if (!isset($input['default_options'])) $input['default_options'] = null;
	$input['default_options'] = ($input['default_options'] == 1 ? 1 : 0);

	$input['hmd_abstract']   = wp_filter_nohtml_kses($input['hmd_abstract']);
	$input['hmd_author']     = wp_filter_nohtml_kses($input['hmd_author']);
	$input['hmd_classify']   = wp_filter_nohtml_kses($input['hmd_classify']);
	$input['hmd_copyright']  = wp_filter_nohtml_kses($input['hmd_copyright']);
	$input['hmd_designer']   = wp_filter_nohtml_kses($input['hmd_designer']);
	$input['hmd_distribute'] = wp_filter_nohtml_kses($input['hmd_distribute']);
	$input['hmd_language']   = wp_filter_nohtml_kses($input['hmd_language']);
	$input['hmd_publisher']  = wp_filter_nohtml_kses($input['hmd_publisher']);
	$input['hmd_rating']     = wp_filter_nohtml_kses($input['hmd_rating']);
	$input['hmd_resource']   = wp_filter_nohtml_kses($input['hmd_resource']);
	$input['hmd_revisit']    = wp_filter_nohtml_kses($input['hmd_revisit']);
	$input['hmd_subject']    = wp_filter_nohtml_kses($input['hmd_subject']);
	$input['hmd_template']   = wp_filter_nohtml_kses($input['hmd_template']);

	if (!isset($input['hmd_enable'])) $input['hmd_enable'] = null;
	$input['hmd_enable'] = ($input['hmd_enable'] == 1 ? 1 : 0);

	// dealing with kses
	global $allowedposttags;
	$allowed_atts = array(
		'align'=>array(), 'class'=>array(), 'id'=>array(), 'dir'=>array(), 'lang'=>array(), 'style'=>array(), 'label'=>array(), 'url'=>array(), 
		'xml:lang'=>array(), 'src'=>array(), 'alt'=>array(), 'name'=>array(), 'content'=>array(), 'http-equiv'=>array(), 'profile'=>array()
		);
	$allowedposttags['strong'] = $allowed_atts;
	$allowedposttags['script'] = $allowed_atts;
	$allowedposttags['style'] = $allowed_atts;
	$allowedposttags['small'] = $allowed_atts;
	$allowedposttags['span'] = $allowed_atts;
	$allowedposttags['meta'] = $allowed_atts;
	$allowedposttags['item'] = $allowed_atts;
	$allowedposttags['base'] = $allowed_atts;
	$allowedposttags['link'] = $allowed_atts;
	$allowedposttags['abbr'] = $allowed_atts;
	$allowedposttags['code'] = $allowed_atts;
	$allowedposttags['div'] = $allowed_atts;
	$allowedposttags['img'] = $allowed_atts;
	$allowedposttags['h1'] = $allowed_atts;
	$allowedposttags['h2'] = $allowed_atts;
	$allowedposttags['h3'] = $allowed_atts;
	$allowedposttags['h4'] = $allowed_atts;
	$allowedposttags['h5'] = $allowed_atts;
	$allowedposttags['ol'] = $allowed_atts;
	$allowedposttags['ul'] = $allowed_atts;
	$allowedposttags['li'] = $allowed_atts;
	$allowedposttags['em'] = $allowed_atts;
	$allowedposttags['p'] = $allowed_atts;
	$allowedposttags['a'] = $allowed_atts;

	$input['hmd_custom'] = wp_kses_post($input['hmd_custom'], $allowedposttags);

	if (!isset($input['hmd_format'])) $input['hmd_format'] = null;
	$input['hmd_format'] = ($input['hmd_format'] == 1 ? 1 : 0);

	return $input;
}

// add the options page
add_action ('admin_menu', 'hmd_add_options_page');
function hmd_add_options_page() {
	global $hmd_plugin;
	add_options_page($hmd_plugin, $hmd_plugin, 'manage_options', __FILE__, 'hmd_render_form');
}

// create the options page
function hmd_render_form() {
	global $hmd_plugin, $hmd_options, $hmd_path, $hmd_homeurl, $hmd_version; ?>

	<style type="text/css">
		.mm-panel-overview { padding-left: 140px; background: url(<?php echo plugins_url(); ?>/head-meta-data/hmd-logo.png) no-repeat 15px 0; }

		#mm-plugin-options h2 small { font-size: 60%; }
		#mm-plugin-options h3 { cursor: pointer; }
		#mm-plugin-options h4, 
		#mm-plugin-options p { margin: 15px; line-height: 18px; }
		#mm-plugin-options ul { margin: 15px 15px 25px 40px; }
		#mm-plugin-options li { margin: 10px 0; list-style-type: disc; }
		#mm-plugin-options abbr { cursor: help; border-bottom: 1px dotted #dfdfdf; }

		.mm-table-wrap { margin: 15px; }
		.mm-table-wrap td { padding: 5px 10px; vertical-align: middle; }
		.mm-table-wrap .widefat th { padding: 10px 15px; vertical-align: middle; }
		.mm-table-wrap .widefat td { padding: 10px; vertical-align: middle; }

		.mm-item-caption { margin: 3px 0 0 3px; font-size: 11px; color: #777; line-height: 17px; }
		.mm-code-example { margin: 10px 0 20px 0; }
		.mm-code-example div { margin-left: 15px; }
		.mm-code-example pre { margin-left: 30px; }
		.mm-code { background-color: #fafae0; color: #333; font-size: 14px; }

		#setting-error-settings_updated { margin: 10px 0; }
		#setting-error-settings_updated p { margin: 5px; }
		#mm-plugin-options .button-primary { margin: 0 0 15px 15px; }

		#mm-panel-toggle { margin: 5px 0; }
		#mm-credit-info { margin-top: -5px; }
		#mm-iframe-wrap { width: 100%; height: 250px; overflow: hidden; }
		#mm-iframe-wrap iframe { width: 100%; height: 100%; overflow: hidden; margin: 0; padding: 0; }
	</style>

	<div id="mm-plugin-options" class="wrap">
		<?php screen_icon(); ?>

		<h2><?php echo $hmd_plugin; ?> <small><?php echo 'v' . $hmd_version; ?></small></h2>
		<div id="mm-panel-toggle"><a href="<?php get_admin_url() . 'options-general.php?page=' . $hmd_path; ?>"><?php _e('Toggle all panels'); ?></a></div>

		<form method="post" action="options.php">
			<?php $hmd_options = get_option('hmd_options'); settings_fields('hmd_plugin_options'); ?>

			<div class="metabox-holder">
				<div class="meta-box-sortables ui-sortable">
					<div id="mm-panel-overview" class="postbox">
						<h3><?php _e('Overview'); ?></h3>
						<div class="toggle default-hidden">
							<div class="mm-panel-overview">
								<p>
									<strong><?php echo $hmd_plugin; ?></strong> <?php _e('(HMD) adds assorted <code>&lt;head&gt;</code> tags and more to the <code>&lt;head&gt;</code> section of all posts &amp; pages.'); ?>
									<?php _e('Optionally includes custom text/markup in the <code>&lt;head&gt;</code> section.'); ?>
								</p>
								<ul>
									<li><?php _e('To configure the plugin, visit'); ?> <a id="mm-panel-primary-link" href="#mm-panel-primary"><?php _e('Options'); ?></a>.</li>
									<li><?php _e('For a live preview of the meta tags, visit'); ?> <a id="mm-panel-secondary-link" href="#mm-panel-secondary"><?php _e('Preview'); ?></a>.</li>
									<li><?php _e('To restore default settings, visit'); ?> <a id="mm-restore-settings-link" href="#mm-restore-settings"><?php _e('Restore Default Options'); ?></a>.</li>
									<li><?php _e('For more information check the <code>readme.txt</code> and'); ?> <a href="<?php echo $hmd_homeurl; ?>"><?php _e('HMD Homepage'); ?></a>.</li>
								</ul>
							</div>
						</div>
					</div>
					<div id="mm-panel-primary" class="postbox">
						<h3><?php _e('Options'); ?></h3>
						<div class="toggle<?php if (!isset($_GET["settings-updated"])) { echo ' default-hidden'; } ?>">
							<h4><?php _e('General options'); ?></h4>
							<p><?php _e('Here you may enable/disable meta output, specify custom content, and choose either <abbr title="Hypertext Markup Language">HTML</abbr> or <abbr title="eXtensible Hypertext Markup Language">XHTML</abbr> formatting.'); ?></p>
							<div class="mm-table-wrap">
								<table class="widefat mm-table">
									<tr>
										<th scope="row"><label class="description" for="hmd_options[hmd_enable]"><?php _e('Enable plugin?'); ?></label></th>
										<td><input type="checkbox" name="hmd_options[hmd_enable]" value="1" <?php if (isset($hmd_options['hmd_enable'])) { checked('1', $hmd_options['hmd_enable']); } ?> /> 
										<span class="mm-item-caption"><?php _e('Check this box if you want to enable output of your customized meta tags.'); ?></span></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="hmd_options[hmd_format]"><?php _e('HTML Format?'); ?></label></th>
										<td><input type="checkbox" name="hmd_options[hmd_format]" value="1" <?php if (isset($hmd_options['hmd_format'])) { checked('1', $hmd_options['hmd_format']); } ?> /> 
										<span class="mm-item-caption"><?php _e('Uncheck this box if you want to enable <abbr title="eXtensible Hypertext Markup Language">XHTML</abbr> format. Leave checked for the default (<abbr title="Hypertext Markup Language">HTML</abbr>).'); ?></span></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="hmd_options[hmd_custom]"><?php _e('Include custom content?'); ?></label></th>
										<td><input type="text" size="50" maxlength="200" name="hmd_options[hmd_custom]" value="<?php echo $hmd_options['hmd_custom']; ?>" />
										<div class="mm-item-caption"><?php _e('Here you may specify any text/markup to be displayed in the <code>&lt;head&gt;</code> section. Note: use single quotes for attributes. Leave blank to disable.'); ?></div></td>
									</tr>
								</table>
							</div>
							<h4><?php _e('Meta tags'); ?></h4>
							<p><?php _e('Here you may specify text values for your <code>&lt;meta&gt;</code> tags. Leave blank to disable specific tags.'); ?></p>
							<div class="mm-table-wrap">
								<table class="widefat mm-table">
									<tr>
										<th scope="row"><label class="description" for="hmd_options[hmd_abstract]"><?php _e('Meta abstract'); ?></label></th>
										<td><input type="text" size="50" maxlength="200" name="hmd_options[hmd_abstract]" value="<?php echo $hmd_options['hmd_abstract']; ?>" />
										<div class="mm-item-caption"><?php _e('Provide an abstract summarizing the content of your website. Should be one brief sentence.'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="hmd_options[hmd_author]"><?php _e('Meta author'); ?></label></th>
										<td><input type="text" size="50" maxlength="200" name="hmd_options[hmd_author]" value="<?php echo $hmd_options['hmd_author']; ?>" />
										<div class="mm-item-caption"><?php _e('Indicate the author(s) of the website. May also include email address if you prefer plenty spam.'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="hmd_options[hmd_classify]"><?php _e('Meta classification'); ?></label></th>
										<td><input type="text" size="50" maxlength="200" name="hmd_options[hmd_classify]" value="<?php echo $hmd_options['hmd_classify']; ?>" />
										<div class="mm-item-caption"><?php _e('Classify your website. Examples: Website Design, Digital Photography.'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="hmd_options[hmd_copyright]"><?php _e('Meta copyright'); ?></label></th>
										<td><input type="text" size="50" maxlength="200" name="hmd_options[hmd_copyright]" value="<?php echo $hmd_options['hmd_copyright']; ?>" />
										<div class="mm-item-caption"><?php _e('Indicate the copyright information for the site. May include trademark names, patent numbers, etc.'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="hmd_options[hmd_designer]"><?php _e('Meta designer'); ?></label></th>
										<td><input type="text" size="50" maxlength="200" name="hmd_options[hmd_designer]" value="<?php echo $hmd_options['hmd_designer']; ?>" />
										<div class="mm-item-caption"><?php _e('Indicate the designer(s) of the website.'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="hmd_options[hmd_distribute]"><?php _e('Meta distribution'); ?></label></th>
										<td><input type="text" size="50" maxlength="200" name="hmd_options[hmd_distribute]" value="<?php echo $hmd_options['hmd_distribute']; ?>" />
										<div class="mm-item-caption"><?php _e('Indicate the distribution level on the web. Examples: Global, Regional, Local.'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="hmd_options[hmd_language]"><?php _e('Meta language'); ?></label></th>
										<td><input type="text" size="50" maxlength="200" name="hmd_options[hmd_language]" value="<?php echo $hmd_options['hmd_language']; ?>" />
										<div class="mm-item-caption"><?php _e('Indicate the primary language used for your website. Examples: EN-US, EN, FR.'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="hmd_options[hmd_publisher]"><?php _e('Meta publisher'); ?></label></th>
										<td><input type="text" size="50" maxlength="200" name="hmd_options[hmd_publisher]" value="<?php echo $hmd_options['hmd_publisher']; ?>" />
										<div class="mm-item-caption"><?php _e('Indicate the publisher of the website.'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="hmd_options[hmd_rating]"><?php _e('Meta rating'); ?></label></th>
										<td><input type="text" size="50" maxlength="200" name="hmd_options[hmd_rating]" value="<?php echo $hmd_options['hmd_rating']; ?>" />
										<div class="mm-item-caption"><?php _e('Indicate the rating of the site&rsquo;s content. Examples: General, Mature, Restricted.'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="hmd_options[hmd_resource]"><?php _e('Meta resource-type'); ?></label></th>
										<td><input type="text" size="50" maxlength="200" name="hmd_options[hmd_resource]" value="<?php echo $hmd_options['hmd_resource']; ?>" />
										<div class="mm-item-caption"><?php _e('Indicate the primary resource type for the site. Example: Document.'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="hmd_options[hmd_revisit]"><?php _e('Meta revisit-after'); ?></label></th>
										<td><input type="text" size="50" maxlength="200" name="hmd_options[hmd_revisit]" value="<?php echo $hmd_options['hmd_revisit']; ?>" />
										<div class="mm-item-caption"><?php _e('Frequency (in days) that search engines should revisit your site for re-indexing. Examples: 1, 2, 3.'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="hmd_options[hmd_subject]"><?php _e('Meta subject'); ?></label></th>
										<td><input type="text" size="50" maxlength="200" name="hmd_options[hmd_subject]" value="<?php echo $hmd_options['hmd_subject']; ?>" />
										<div class="mm-item-caption"><?php _e('Indicate the primary subject(s) of the website. Examples: Photography, Sports, Pancakes, etc.'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="hmd_options[hmd_template]"><?php _e('Meta template'); ?></label></th>
										<td><input type="text" size="50" maxlength="200" name="hmd_options[hmd_template]" value="<?php echo $hmd_options['hmd_template']; ?>" />
										<div class="mm-item-caption"><?php _e('Indicate any template that pertains to the site. Examples: Zonked Out WordPress Theme.'); ?></div></td>
									</tr>
								</table>
							</div>
							<input type="submit" class="button-primary" value="<?php _e('Save Settings'); ?>" />
						</div>
					</div>
					<div id="mm-panel-secondary" class="postbox">
						<h3><?php _e('Preview'); ?></h3>
						<div class="toggle<?php if (!isset($_GET["settings-updated"])) { echo ' default-hidden'; } ?>">
							<p><?php _e('Here is a preview of your meta tags and custom content.'); ?></p>
							<div class="mm-code-example">
								<h4><?php _e('Meta tags'); ?></h4>
								<div><?php _e('(display using <code>[head_meta_data]</code> shortcode)'); ?></div>
								<pre><?php echo do_shortcode('[head_meta_data]'); ?></pre>
								<h4><?php _e('Custom content'); ?></h4>
								<div><?php _e('(display using <code>[hmd_custom]</code> shortcode)'); ?></div>
								<pre><?php echo do_shortcode('[hmd_custom]'); ?></pre>
								<h4><?php _e('More infos'); ?></h4>
								<ul>
									<li><?php _e('For more information on document headers:'); ?> <a href="http://m0n.co/c" title="XHTML Document Header Resource" target="_blank">http://m0n.co/c</a></li>
									<li><?php _e('And more specifically the section on meta tags:'); ?> <a href="http://m0n.co/d" title="XHTML Document Header Resource: meta tags" target="_blank">http://m0n.co/d</a></li>
								</ul>
							</div>
						</div>
					</div>
					<div id="mm-restore-settings" class="postbox">
						<h3><?php _e('Restore Default Options'); ?></h3>
						<div class="toggle<?php if (!isset($_GET["settings-updated"])) { echo ' default-hidden'; } ?>">
							<p>
								<input name="hmd_options[default_options]" type="checkbox" value="1" id="mm_restore_defaults" <?php if (isset($hmd_options['default_options'])) { checked('1', $hmd_options['default_options']); } ?> /> 
								<label class="description" for="hmd_options[default_options]"><?php _e('Restore default options upon plugin deactivation/reactivation.'); ?></label>
							</p>
							<p>
								<small>
									<?php _e('<strong>Tip:</strong> leave this option unchecked to remember your settings. Or, to go ahead and restore all default options, check the box, save your settings, and then deactivate/reactivate the plugin.'); ?>
								</small>
							</p>
							<input type="submit" class="button-primary" value="<?php _e('Save Settings'); ?>" />
						</div>
					</div>
					<div id="mm-panel-current" class="postbox">
						<h3><?php _e('Updates &amp; Info'); ?></h3>
						<div class="toggle default-hidden">
							<div id="mm-iframe-wrap">
								<iframe src="http://perishablepress.com/current/index-hmd.html"></iframe>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div id="mm-credit-info">
				<a target="_blank" href="<?php echo $hmd_homeurl; ?>" title="<?php echo $hmd_plugin; ?> Homepage"><?php echo $hmd_plugin; ?></a> by 
				<a target="_blank" href="http://twitter.com/perishable" title="Jeff Starr on Twitter">Jeff Starr</a> @ 
				<a target="_blank" href="http://monzilla.biz/" title="Obsessive Web Design &amp; Development">Monzilla Media</a>
			</div>
		</form>
	</div>
	<script type="text/javascript">
		jQuery(document).ready(function(){
			// toggle panels
			jQuery('.default-hidden').hide();
			jQuery('#mm-panel-toggle a').click(function(){
				jQuery('.toggle').slideToggle(300);
				return false;
			});
			jQuery('h3').click(function(){
				jQuery(this).next().slideToggle(300);
			});
			jQuery('#mm-panel-primary-link').click(function(){
				jQuery('.toggle').hide();
				jQuery('#mm-panel-primary .toggle').slideToggle(300);
				return true;
			});
			jQuery('#mm-panel-secondary-link').click(function(){
				jQuery('.toggle').hide();
				jQuery('#mm-panel-secondary .toggle').slideToggle(300);
				return true;
			});
			jQuery('#mm-restore-settings-link').click(function(){
				jQuery('.toggle').hide();
				jQuery('#mm-restore-settings .toggle').slideToggle(300);
				return true;
			});
			// prevent accidents
			if(!jQuery("#mm_restore_defaults").is(":checked")){
				jQuery('#mm_restore_defaults').click(function(event){
					var r = confirm("<?php _e('Are you sure you want to restore all default options? (this action cannot be undone)'); ?>");
					if (r == true){  
						jQuery("#mm_restore_defaults").attr('checked', true);
					} else {
						jQuery("#mm_restore_defaults").attr('checked', false);
					}
				});
			}
		});
	</script>

<?php } ?>