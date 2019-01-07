=== SweetHome3D-Embed ===
Contributors: stevecohenfr
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=cohensteve@hotmail.fr&lc=FR&item_name=SweetHome3D-Embed%20for%20Wordpress&no_note=0&cn=&currency_code=EUR&bn=PP-DonationsBF:btn_donateCC_LG.gif:NonHosted
Tags: sweethome3d, sh3d, modele, 3d, house, embed, viewer
Requires at least: 4.6
Tested up to: 4.7
Stable tag: 1.0.0
Requires PHP: 5.2.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

This extension allows you to embed your SweetHome3D house in your wordpress articles/pages.

== Description ==

This is my first extension for Wordpress, I hope it will be usefull. If you have suggests, please send me an email or leave a comment.

This extension allows you to embed your SweetHome3D house as HTML5 using a shortcode.

How to use:

*   Use the SweetHome3D plugin [Export to HTML5](http://www.sweethome3d.com/blog/2016/05/05/export_to_html5_plug_in.html) to create your ready-to-embed zip file
*   Export your house as zip in SweetHome3D using menu Tools -> Export to HTML5
*   Unzip the generated zip file
*   It will contains :
    * lib/
    * viewHome.html
    * viewHomeInOverlay.html
    * your_house.zip <- WE NEED THIS
*   Go to your wordpress admin, and in the left menu SweetHome3D, upload your_house.zip
*   Use the generated shortcode in your articles/pages

Disclaimer: This plugin is not part of SweetHome3D project and not linked to its developer(s).

== Installation ==

This section describes how to install the plugin and get it working.


1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. You will see on the left menu a new entry : SweetHome3D
1. You can upload here your house.zip using the steps described in the [Description](#description) section


== Frequently Asked Questions ==

= I got the error `Error: No Home.xml entry in [...]` =

The is because your zip file is invalid. Please verify that you upload the right zip file (not the one that you exported but the one inside it)

Please refer to the [Description](#description) section

= How to change the canvas size ? =

It's not possible for now to change the canvas size, it will use the fullwidth bu default.

I will add more options in next versions

= How to remove the background ?

You can edit your 3D view in SweetHome3D modifying the scenary, for more informations please [read this](http://www.sweethome3d.com/blog/2016/01/01/how_to_add_a_scenery_around_your_home.html)

== Screenshots ==

1. The admin interface
2. Result of embed house in an article

== Changelog ==

= 1.0.0 =
* First release

== Upgrade Notice ==