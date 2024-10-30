=== BuddyPress Create Events ===
Tags: buddypress, events
Author URI: 
Plugin URI: 
Requires at least: WP 4.0
Tested up to: WP 4.4 
Stable tag: 1.0.1
License: GPLv2 or later

A simple Events plugin for BuddyPress

== Description ==

This BuddyPress plugin allows members to create, edit and delete Events from their profile. It requires BuddyPress 2.2 or higher.

It:

* provides a tab on each members' profile for front-end creation, editing and deletion
* uses the Google Places API for creating locations
* uses Google Maps to show Event location 
* creates a custom post type called 'event'
* uses WP and BP templates that can be overloaded
* includes a widget


It does NOT have:

* ticketing
* calendars
* recurring events

== Installation ==

1. Unzip and then upload the 'bp-simple-events' folder to the '/wp-content/plugins/' directory

2. Activate the plugin through the 'Plugins' menu in WordPress

3. Go to Settings -> BP Create Events and select which user roles are allowed to create Events. 
Admins are automatically given permission.   Other settings are also available.

Roles can be assigned via the Network Admin > Settings > BP Create Events screen.

But a member _must_ be a member of the main site in order to create Events.
If they are not a member of the main site, they will not see the Events tab.


== Screenshots ==
1. Shows the front-end Create an Event screen on a member profile
2. Shows the Dashboard > Settings screen
