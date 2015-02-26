=== Plugin Name ===
Contributors: tstephenson
Tags: forms, ajax, OmnyLink
Requires at least: 4.0
Tested up to: 4.1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The Omny Link Forms plugin exists to easily design HTML forms that can send their content to the Omny Link workflow server and thus integrate many web APIs on submit. 

== Description ==

The Omny Link Forms plugin exists to easily design HTML forms that can send their content to the Omny Link workflow server and thus integrate many web APIs on submit. 

== Installation ==

The plugin is provided via the WordPress.org repository as well as as a zip file. If the zip file is used it may be installed as follows: 

1. Click the 'Plugins > Add New' menu in WordPress, then click 'Upload Plugin'.
1. Choose the zip file and once uploaded, activate it in the 'Plugins' page of WordPress.
1. Review and update the settings page as necessary. 
1. Author forms within the 'Omny Link Forms' menu in WordPress. Contextual help is provided alongside the editor. 
1. Embed forms in your Posts with the shortcode [p_form id="form_id"]

== Using shortcode options ==

=== Attributes that may be used in p_form shortcode ===

* id (mandatory): The id of the form this shortcode will embed. 
* callback: An optional WordPress action to invoke on submit, may be used in addition or instead of sending Omny Link a message. See http://codex.wordpress.org/AJAX_in_Plugins for how to write WordPress AJAX actions.
* msg_name: The name to identify the JSON message sent to the Omny Link server. This message will be used to identify the correct handler.
* msg_pattern: The message exchange pattern to interact with Omny Link, one of: none, inOnly, inOut. Default is 'none'.
* redirect_to: The url to redirect the user to once the form is submitted. Default is '/'.

=== Passing constants to a form ===

If you want to pass constants to a form you may do so by enclosing a comma-separated list of key=value pairs within the shortcode. For example, this shortcode: 

`[p_form id="123"]productName=book,isbn=9780316017930[/p_form]`

will result in 2 additional JSON properties named productName and isbn with the specified values.

== Frequently Asked Questions ==

= What is my form's id to use in the shortcode? =

When authoring the form you will see in your browser's address bar something like: http://mysite.com/wp-admin/post.php?post=1760&action=edit. The number after post= (here 1760) is your form's id. 

= How can I access the form's content to provide custom post-processing? =

The plugin is configured to send the form content to the Omny Link workflow server on submit. This provides a separation of concerns keeping the WordPress site clean and focused on presentation. However in some cases you may want to perform some limited processing in the WordPress site in response to the form submission. For this case you may register an AJAX action to be called. The form's JSON payload may be accessed as $_POST['json'].

== Screenshots ==

1. This screenshot shows the form editor. 

2. Embedding the shortcode in a page and passing the additional property 'product name'.

== Changelog ==

= 0.9.4 =
This is the first version of the plugin made publically available. 

== Upgrade Notice ==

This is the first version of the plugin made publically available. 
