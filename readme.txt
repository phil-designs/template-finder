=== PhilDesigns Template Finder ===
Contributors: phildesigns
Tags: templates, page templates, theme, pages
Requires at least: 6.7
Tested up to: 7.0
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Find all pages using a specific page template, with links to view and edit each page directly from the WordPress admin.

== Description ==

Template Finder adds a simple tool under **Tools → Template Finder** in the WordPress admin. Select any registered page template from a dropdown and instantly see every page assigned to that template — including drafts, private pages, and scheduled posts.

Each result shows the page title, its parent (if any), its publish status, and direct links to view and edit the page.

= Features =

* Lists every page template registered by your active theme
* Supports the Default Template option
* Displays all post statuses: Published, Draft, Pending, Private, and Scheduled
* Shows parent page for child pages
* View and Edit links for each result

== Installation ==

1. Upload the `template-finder` folder to `/wp-content/plugins/`
2. Activate the plugin through the **Plugins** menu in WordPress
3. Go to **Tools → Template Finder**
4. Select a template from the dropdown and click **Search**

== Frequently Asked Questions ==

= Where do I find Template Finder? =

Go to **Tools → Template Finder** in the WordPress admin menu.

= Does it work with custom post types? =

No — the current version searches pages only (`post_type = page`).

= Will it show pages that have been assigned a template that no longer exists in the theme? =

No. Only templates currently registered by the active theme appear in the dropdown. Pages assigned to a removed template will not appear in any search result.

== Screenshots ==

1. The Template Finder admin page showing results for a selected template.

== Changelog ==

= 1.0.0 =
* Initial release.

== Upgrade Notice ==

= 1.0.0 =
Initial release.
