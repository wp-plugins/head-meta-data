=== Head Meta Data ===

Plugin Name: Head Meta Data
Plugin URI: https://perishablepress.com/head-metadata-plus/
Description: Adds a complete set of &lt;meta&gt; tags to the &lt;head&gt; section of all posts &amp; pages.
Tags: meta, head, wp_head, customize, author, publisher, language, custom content, header
Author: Jeff Starr
Author URI: http://monzilla.biz/
Donate link: http://m0n.co/donate
Contributors: specialk
Requires at least: 3.9
Tested up to: 4.2
Stable tag: trunk
Version: 20150507
Text Domain: hmd
Domain Path: /languages/
License: GPL v2 or later

Head Meta Data adds a complete set of &lt;meta&gt; tags to the &lt;head&gt; section of all posts &amp; pages.

== Description ==

Head Meta Data (formerly "Head Metadata Plus") improves the definition and semantic quality of your web pages by adding a complete set of `<meta>` tags to the `<head>` section of your web pages.

**Example**

	<head>
	
		<meta name="abstract" content="Obsessive Web & Graphic Design.">
		<meta name="author" content="Perishable">
		<meta name="classification" content="Website Design">
		<meta name="copyright" content="Copyright Perishable Press - All rights Reserved.">
		<meta name="designer" content="Monzilla Media">
		<meta name="language" content="EN-US">
		<meta name="publisher" content="Perishable Press">
		<meta name="rating" content="General">
		<meta name="resource-type" content="Document">
		<meta name="revisit-after" content="3">
		<meta name="subject" content="WordPress, Web Design, Code & Tutorials">
		<meta name="template" content="Volume Theme">
	
	</head>

**Features**

* Plug-n-play functionality
* Born of simplicity, no frills
* Easy to configure from the WP Admin
* Adds complete set of meta tags to `<head>` section of posts and pages
* Customize each `<meta>` tag with your own info
* Adds any custom text/markup to `<head>` section of posts and pages
* Customize additional content with any text/markup
* Supports Twitter Cards and Open Graph tags via custom content
* Disable any field by leaving it blank
* Auto-generates known information from your site
* Choose HTML or XHTML format for meta tags
* Enable/disable plugin output from the settings page
* Includes live preview of your meta tags and custom content
* Option to reset default settings

This plugin is designed to complete a site's head construct by including some of the more obscure meta tags, such as "author", "copyright", "designer", and so forth. As a matter of practicality, the more widely used tags such as "description" and "keywords" have been omitted, as they are already included via wide variety of plugins (such as "All in One SEO") in a more dynamic way. Even so, adding "description", "keyword", or any other tags is easy from the plugin's settings page. Note: the metadata output via this plugin applies to the entire site.

== Installation ==

Typical plugin install: upload, activate, and customize in the WP Admin. Visit the "Head Meta Data" settings page for more information.

[More info on installing WP plugins](http://codex.wordpress.org/Managing_Plugins#Installing_Plugins)

== Upgrade Notice ==

To upgrade Head Meta Data, remove old version and replace with new version. Nothing else needs done.

== Screenshots ==

Screenshots available at the [HMD Homepage](https://perishablepress.com/head-metadata-plus/).

== Changelog ==

**20150507**

* Tested with WP 4.2 + 4.3 (alpha)
* Changed a few "http" links to "https"

**20150315**

* Tested with latest version of WP (4.1)
* Increased minimum version to WP 3.8
* Added $hmd_wp_vers for version check
* Streamline/fine-tune plugin code
* Added Text Domain and Domain Path to file header
* Added .pot template for localization
* Removed deprecated screen_icon()

**20140923**

* Tested with latest version of WordPress (4.0)
* Increased minimum WP version requirement to 3.7
* Added conditional check on min-version function

**20140123**

* Tested with latest WordPress (3.8)
* Added trailing slash to load_plugin_textdomain()

**20131107**

* Added uninstall.php file
* Added "rate this plugin" links
* Added support for i18n

**20131104**

* Added line to prevent direct script access
* Changed default value for copyright meta
* Improved support for custom content
* Fixed bug reported [here](http://wordpress.org/support/topic/strange-custom-tag)
* Replaced wp_kses_post with wp_kses
* Added "href", "property", "title", "rel", "type", "charset", "media", "rev" to list of allowed attributes
* Removed closing "?>" tag in head-meta-data.php
* Tested with latest version of WordPress (3.7)

**20130705**

* General code check n clean, plus Overview and Updates admin panels now toggled open by default.

**20130103**

* Added margins to submit buttons (required in WP 3.5)

**20121102**

* Rebuilt plugin, changed name from "Head MetaData Plus" to "Head Meta Data".

**20060502**

* Initial release.

== Frequently Asked Questions ==

To ask a question, visit the [HMD Homepage](https://perishablepress.com/head-metadata-plus/) or [contact me](https://perishablepress.com/contact/).

== Donations ==

I created this plugin with love for the WP community. To show support, you can [make a donation](http://m0n.co/donate) or purchase one of my books: 

* [The Tao of WordPress](https://wp-tao.com/)
* [Digging into WordPress](https://digwp.com/)
* [.htaccess made easy](https://htaccessbook.com/)
* [WordPress Themes In Depth](https://wp-tao.com/wordpress-themes-book/)

Links, tweets and likes also appreciated. Thanks! :)
