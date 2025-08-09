Typewriting Author ARC Plugin
A lightweight WooCommerce plugin for managing ARC (Advance Reader Copy) signups via MailerLite forms, mapped to each product author.
Admins can paste unique MailerLite form HTML per author in the settings page, and the frontend shortcode outputs a dropdown to let users choose an author and reveal the correct form.

Features
Settings page under Settings → Author ARC Forms.

Map MailerLite form HTML to each WooCommerce product_author term.

[author_arc_form] shortcode outputs:

Dropdown of all product authors.

Hidden form containers revealed based on selection.

Works with both old and new MailerLite embed codes.

Simple JS toggle handler (assets/js/form-handler.js).

Installation
Upload the plugin to /wp-content/plugins/.

Activate it in Plugins.

Go to Settings → Author ARC Forms and paste the MailerLite HTML for each author.

Add the shortcode [author_arc_form] anywhere in your site (page, post, widget, or template).

Requirements
WordPress + WooCommerce.

product_author taxonomy must be registered and used.

MailerLite embed codes.

Example usage
html
Αντιγραφή
Επεξεργασία
[author_arc_form]
Users select an author, and the corresponding MailerLite ARC signup form appears.
