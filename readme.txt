=== LH Xprofile forms===
Contributors: shawfactor, calvincanas
Donate link: https://lhero.org/portfolio/lh-xprofile-forms/
Tags: buddypress, frontend, xprofile, forms, xforms
Requires at least: 4.0, BuddyPress 2.0
Tested up to: 6.0
Stable tag: 1.03
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Decouple Xprofile forms from the profile and signup screens via a shortcode

== Description ==

Buddypress forms by default only show on the buddypress user profile screen. However this is not appropriate for all user data. A lot of user data does not need to be maintained by the user and only needs to be populated by the user a single time and does not need to maintained.

Simply post the shortcode [lh_xprofile_form group_id="xprofile group id"] into a post or page. The group_id is the numeric ID of the xprofile group that you can find under Users->Profile Fields 

== Installation ==

1. Upload the entire `lh-xprofile-forms` folder to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.


== Frequently Asked Questions ==

= What could you use this for? =
You could use this plugin to create surveys or with a small amount of programming a much better onboarding experience

= What is something does not work?  =

LH Buddypress Export Xprofile Data, and all [https://lhero.org/](LocalHero) plugins are made to WordPress standards. Therefore they should work with all well coded plugins and themes. However not all plugins and themes are well coded (and this includes many popular ones). 

If something does not work properly, firstly deactivate ALL other plugins and switch to one of the themes that come with core, e.g. twentyfifteen, twentysixteen etc.

If the problem persists please leave a post in the support forum: [https://wordpress.org/support/plugin/lh-xprofile-forms/](https://wordpress.org/support/plugin/lh-xprofile-forms/). I look there regularly and resolve most queries.

= What if I need a feature that is not in the plugin?  =

Please contact me for custom work and enhancements here: [https://shawfactor.com/contact/](https://shawfactor.com/contact/)



== Changelog ==

**1.00 September 22, 2017**  
Initial release.

**1.01 October 08, 2017**  
Slight improve.

**1.02 July 04, 2018**  
Singleton class.

**1.03 June 04, 2020**  
Static functions.