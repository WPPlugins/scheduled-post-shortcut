# Scheduled Post Shortcut

[![Deployment status from DeployBot](https://pressware.deploybot.com/badge/34534835910180/64915.svg)](http://deploybot.com)

[![License](https://img.shields.io/badge/license-GPL--2.0%2B-red.svg)](https://github.com/pressware/scheduled-post-shortcut/blob/master/license.txt)

Easily access your scheduled posts from the WordPress dashboard and with
keyboard shortcuts.

## Description

If you're someone who blogs with any type of regularity, then you likely have a
number of posts sitting in the scheduled queue in your WordPress back-end.

The problem? It's a little tedious to access them. You have to navigate through
the *Posts* menu, view *All Posts* then click on the *Scheduled* tab. And if
you're an author who spends a lot of time in WordPress, then this can become
a bit of a chore.

Scheduled Post Shortcut aims to make this easier. The plugin introduces a new
*Scheduled* menu item under the *Posts* menu that will take you directly to the
*Scheduled Posts* page so that you can manage your content.

This is yet another plugin that aims to streamline your blogging process. Should
you opt to uninstall this plugin, then no data will be lost or affected.

## Installation

You can install this plugin by searching for 'scheduled-post-shortcuts' in from
the WordPress Plugin screen.

If you download a copy of the plugin and want to install it in another way,
please see the methods below.

### Using The WordPress Dashboard

1. Navigate to the 'Add New' Plugin page
2. Select `scheduled-post-shortcuts.zip` from your computer
3. Click 'Upload'
4. Activate the plugin on the WordPress Plugin Dashboard

### Using FTP

1. Extract `scheduled-post-shortcuts.zip` to your computer
2. Upload the `scheduled-post-shortcuts` directory to your `wp-content/plugins`
   directory
3. Activate the plugin on the WordPress Plugins dashboard

## FAQ

### Is this plugin supported?

[We](https://pressware.co) will soon offer the option to purchase
support for all of our plugins. If you've purchased a license for a plugin,
then you will receive support.

We offer no guarantee for support for those who have not purchased a license.

### What if I have a feature request or bug report?

That's great!

1. Please visit the [Pressware](https://pressware.co)
homepage,
2. Locate the "Support" link,
3. Send us an email.

We read every email we receive and will do our best to accommodate our
customers.

We don't guarantee a response to all users; however, if you have purchased
a support license, then you receive priority support. Further, we seek to
respond to each customer's questions, comments, and feedback directly.

## Running the Tests

Scheduled Post Shortcut includes a server-side test suite that verifies the
plugin's functionality works as expected. In order to use these tests, your
development environment should include:

- PHP >= 5.6.10
- [WP-CLI](http://wp-cli.org/)
- [Composer](https://getcomposer.org/)

Once the tools are installed, run the following commands from within the root
directory of the plugin:

1. `$ composer update`
2. `$ wp scaffold plugin-tests scheduled-post-shortcut`
3. `$ cd $(wp plugin path scheduled-posts-shortcuts --dir)`
4. `$ bash bin/install-wp-tests.sh wordpress_test root root localhost latest`
5. `$ vendor/bin/phpunit`

This will make sure that PHPUnit, WP-CLI, and the necessary scaffolding is set
up and the tests are executed against the plugin without interfering with your
installation of WordPress.

## Notes

Thank you for choosing Pressware's plugins to streamline and improve
your blogging!

- You can find all of our available plugins on our [homepage](https://pressware.co).
- If you're curious about the changes found in this plugin, view our [CHANGELOG](https://github.com/pressware/scheduled-post-shortcuts/blob/master/CHANGELOG.md)
- To find previous versions of this plugin, visit [this page](https://github.com/pressware/scheduled-posts-shortcuts/releases)
- If you opt to download this version of the source code, note that this is a
  _development_ copy and we offer no support for it. **Use at your own risk.**
