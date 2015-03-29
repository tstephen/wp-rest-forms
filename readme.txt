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

Alternately, a simple, 'Standalone' mode exists that will simply send submitted forms as email. 

== Installation ==

The plugin is provided via the WordPress.org repository as well as as a zip file. If the zip file is used it may be installed as follows:

1. Click the 'Plugins > Add New' menu in WordPress, then click 'Upload Plugin'.
1. Choose the zip file and once uploaded, activate it in the 'Plugins' page of WordPress.
1. Review and update the settings page as necessary.
1. Author forms within the 'Omny Link Forms' menu in WordPress. Contextual help is provided alongside the editor.
1. Embed forms in your Posts with the shortcode [p_form id="form_id"]

== Configuring settings ==

You can access the plugin's settings under in the Omny sub-menu of WordPress' settings menu in the Dashboard. The following options exist: 

1. Mail addressee: A comma-separated list of email addresses that forms will be sent to when configured in Standalone mode. 
1. Enable debug output: If checked the plugin will write various messages to the PHP error log describing what it is doing. It is adviseable to turn this off once forms are working as it canbe quite verbose. 
1. API Server: This should only be changed if you are running your own Omny server, in which case refer to the developer documentation that accompanies that. 
2. API Key and API Secret: As with other API services, before you can send forms for processing by the Omny server you need to obtain a Key and Secret. Apply for these at http://omny.link/apply-for-key. Take care to protect these.
3. Message namespace: Along with the API key and secret you will be provided a message namespace. This is used to route the messages you send to the Ommny server to the correct business processes.
4. Event Settings: The plugin may also report details of various activities in WordPress to the Omny server for further handling. Check those you wish to publish. 

=== Other settings ===

In addition, when used in Standalone mode, the plugin will send mail from the mail address specified in the General settings. Mail sent in this way relies on the wp_mail function so please ensure your WordPress installation is correctly configured to send mail before installing this plugin. 

== Using shortcode options ==

=== Attributes that may be used in p_form shortcode ===

* id (mandatory): The id of the form this shortcode will embed.
* button_text: The text for the form's button. If omitted it will be 'Submit'. This will become the key to look up the button text in the future when the plugin is localised.
* callback: An optional WordPress action to invoke on submit, may be used in addition or instead of sending Omny Link a message. See http://codex.wordpress.org/AJAX_in_Plugins for how to write WordPress AJAX actions.
* msg_name: The name to identify the JSON message sent to the Omny Link server. This message will be used to identify the correct handler.
* msg_pattern: The message exchange pattern to interact with Omny Link, one of: none, inOnly, inOut. Default is 'none', which triggers the standalone mode mentioned above.
* redirect_to: The url to redirect the user to once the form is submitted. Default is no redirect (by setting the param to undefined).

Standalone mode example:

* To embed form with id 123, send no data to the Omny Server and redirect to page 'thanks-for-your-message' and including the slug of the page the form is embedded in use:

`[p_form id="123" redirect_to="/thanks-for-your-message"]source_page=contact-us[/p_form]`

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

=== 0.10.0 ===

- New dashboard panel to pick form fields from domain model 

=== 0.9.7 ===

- Add option to override the button text to the shortcode.

=== 0.9.6 ===
- **NOTE** Changed redirect to be off by default
- Add support for proxying requests to server so that requests secured with app credentials rather than individual can be made without exposing the credentials in the browser.

=== 0.9.5 ===
Bug fix that prevented use of empty shortcode (i.e. had to use the form [p_form...]...[/p_form]

=== 0.9.4 ===
This is the first version of the plugin made publically available.

== Upgrade Notice ==

This is the first version of the plugin made publically available.
