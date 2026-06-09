# Template Finder

Tags: jQuery, javascript, php, css
Requires at least: 3.6.0
Tested up to: 6.9.1
License: GPL2

## Description

Find all pages using a specific page template, with frontend and backend links.

## Tested on 
* Firefox 
* Safari
* Chrome
* Opera
* MS Edge

## Website 
http://www.phildesigns.com/

## Installation 
1. Upload ‘template-finder’ to the '/wp-content/plugins/' directory on both sites
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to "Migration Toolkit>Bulk Author Assign" upload an CSV of urls from site A and assign them a specific author like "exporter"
4. Use the Wordpress export tool to export the xml file using only that authors pages and posts
5. If the file is large use the "Migration Toolkit>WXR File Splitter" to breakup the file into manageable chunks
6. On site B upload the XML files using the Wordpress import tool
7. Go to "Migration Toolkit>URL Migration Checker" and upload your original CSV file of urls to check that the pages and posts have been properly migrated.

## Change log 

Version 1.0.0
• Initial release.
