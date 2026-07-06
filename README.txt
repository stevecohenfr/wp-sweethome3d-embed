=== Embed-SweetHome3D ===
Contributors: stevecohenfr
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=cohensteve@hotmail.fr&lc=FR&item_name=SweetHome3D-Embed%20for%20Wordpress&no_note=0&cn=&currency_code=EUR&bn=PP-DonationsBF:btn_donateCC_LG.gif:NonHosted
Tags: sweethome3d, sh3d, 3d, house, viewer
Requires at least: 6.4
Tested up to: 6.8
Requires PHP: 8.1
Stable tag: 2.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Embed your SweetHome3D houses as interactive HTML5/WebGL models in your posts and pages, through a Gutenberg block or the [sh3d] shortcode.

== Description ==

This plugin lets you embed a house designed in [SweetHome3D](http://www.sweethome3d.com) as an interactive HTML5/WebGL model, directly inside your WordPress posts and pages.

You can insert a model either with the **SweetHome3D block** in the block editor, or with the classic **[sh3d]** shortcode.

Features:

*   Gutenberg block with a live preview and a model picker
*   `[sh3d id=1]` shortcode (fully backwards compatible with 1.0.x)
*   Configurable size (max width) and aspect ratio (4:3, 16:9, 3:2, 1:1)
*   Optional auto-rotation and navigation panel
*   Several models on the same page
*   Uploaded archives are validated (a valid SweetHome3D model must contain a `Home.xml` entry)

How to use:

*   In SweetHome3D, export your house with the [Export to HTML5](http://www.sweethome3d.com/blog/2016/05/05/export_to_html5_plug_in.html) plug-in (menu Tools -> Export to HTML5)
*   Unzip the generated file. It contains a `lib/` folder, `viewHome.html`, `viewHomeInOverlay.html` and **your_house.zip** &mdash; that inner ZIP is the file you need
*   In your WordPress admin, open the **SweetHome3D** menu and upload `your_house.zip`
*   Insert the **SweetHome3D block**, or copy the generated `[sh3d id=X]` shortcode into your content

Disclaimer: This plugin is not part of the SweetHome3D project and is not affiliated with its developer(s).

== Installation ==

1. Upload the plugin files to `/wp-content/plugins/embed-sweethome3d`, or install it through the WordPress plugins screen.
1. Activate the plugin through the 'Plugins' screen in WordPress.
1. A new **SweetHome3D** entry appears in the left admin menu.
1. Upload your `house.zip` there (see the [Description](#description) section).

Requirements: PHP 8.1+ and the PHP `zip` extension (used to validate uploaded models).

== Frequently Asked Questions ==

= I get the error `Error: No Home.xml entry in [...]` =

Your ZIP file is not a valid SweetHome3D model. Upload the inner `your_house.zip` (the one contained inside the HTML5 export), not the exported archive itself. Since version 2.0.0 the plugin validates this at upload time.

= How do I change the viewer size? =

In the block, use the "Max width" and "Aspect ratio" settings. With the shortcode, use the attributes, e.g. `[sh3d id=1 width=800 ratio=16:9]`.

= Which shortcode attributes are available? =

`id` (required), `width` (max width in px, 0 = responsive), `ratio` (4:3, 16:9, 3:2, 1:1), `rotation` (auto-rotation in rounds per minute, 0 = off) and `nav` (none or default).

= How do I remove the background? =

Edit the scenery of your 3D view in SweetHome3D. See [this article](http://www.sweethome3d.com/blog/2016/01/01/how_to_add_a_scenery_around_your_home.html).

== Screenshots ==

1. The admin interface
2. A house embedded in an article

== Changelog ==

= 2.0.0 =
* Full rewrite for modern WordPress (requires WP 6.4+ and PHP 8.1+).
* New: Gutenberg block with live preview.
* New: configurable size, aspect ratio, auto-rotation and navigation panel.
* New: support for multiple models on the same page.
* New: uploaded archives are validated (Home.xml check).
* Security: fixed SQL injection vectors, added capability and nonce checks throughout.
* Assets are now only loaded on pages that actually display a model.
* Existing `[sh3d id=X]` shortcodes and stored models keep working.

= 1.0.1 =
* Minor fixes.

= 1.0.0 =
* First release.

== Upgrade Notice ==

= 2.0.0 =
Major rewrite. Requires WordPress 6.4+ and PHP 8.1+. Existing shortcodes and models are preserved.
