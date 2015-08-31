=== Page Comments Off Please ===
Contributors: Joe Melberg 
Donate link: http://techism.com/donate
Tags: disable, page comments, page, checkbox, uncheck, discussion, check-box, please, turn off
Requires at least: 2.8
Tested up to: 3.7.1
Stable tag: 2.0.2
Version: 2.0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

Page Comments Off Please - Unchecks Discussion comment checkboxes by default on pages, posts or both! Plus a simple one-click toggle to turn off comments on existing pages too!  This plugin lets you manage page comment / discussion checkboxes with ease!

== Installation ==

1. Unzip `page_comments_off_please.zip` inside the `/wp-content/plugins/` directory (or install via the built-in WordPress plugin installer)
2. Activate the plugin through the 'Plugins' admin menu in WordPress and the checkboxes will be off next time you go to create a page.
3. Use the settings page in ( Settings -> Page Comments )  in order to change the default behavior to to disable all page comments.

== Frequently Asked Questions ==
= What is the main function of the plugin? =

This plugin is designed so that when you create a new page, you don't have to uncheck the discussion checkboxes for allow-comments and pingbacks-trackbacks.  This has a button to disable comments on old pages, but that is mainly for people who just forgot to install this to begin with.  It's best to use this from the start and include it in your new-site setup routine.  This is targeted toward CMS users and Web Designers.

= Is this different from the old plugin? =

Only a Little.  If you just install it and leave the settings alone, it will continue to work the way you are familiar.  You may be interested in the mass-disable function on the settings page for when you already have comments on your pages.

= How do I set the preference to turn the default comment status for pages off or on? =

It is located under Settings->Page Comments.  Enjoy!

= What is 'Legacy Mode' for? =

This is provided as a backup method for some themes or wordpress customizations prohibit the normal method of operation. If the plugin is not working, try legacy mode :)

= Does the mass-page-comment toggle link affect my posts? =

No.  This only affects pages, regardless of the Page Comment Default checkboxes above.  

== Screenshots ==

1. Adding a new page, the discussion check-boxes are unchecked by default.
2. New admin panel allows you to set your old posts comments to off, and to disable post comments.  Legacy-Mode provides backward-compatibility.

== Changelog ==

= 2.0.2 = 
* Scripts only load when really needed, and using proper wp practices.

= 2.0.1 =
* Made confirmation dialog more verbose.
* Fixed mass-page toggle problem for users with custom db structures.

= 2.0 =
* Replaced shaky Javascript hack with more integrated wp-php functions.
* Added Settings Page
* Added more prominent donate link :)
* Retained original functionality as 'legacy mode'
* Added mass comment-disable for all pages.
* Made plugin into Object Class.

= 1.0 =
* Initial version.  Enjoy!

== Upgrade Notice ==
= 2.0.2 = 
* Better Javascript integration and clarified dialogs.

= 2.0 = 
* New settings panel, control posts or pages, toggle existing page comments off.

= 1.0 =
* This is the first version of the plugin. It is very simple and well-tested, but not flexible at all.
