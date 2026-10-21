=== Juliepr Remove NBSP ===
Contributors: sashmarin
Tags: nbsp, whitespace, content
Requires at least: 6.5
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Replace non-breaking spaces with ordinary spaces when saving posts and pages.

== Description ==

Juliepr Remove NBSP normalizes non-breaking spaces in post and page titles, content, and excerpts before they are saved to the database.

Supported forms: the Unicode U+00A0 character, &nbsp;, &#160;, and &#xA0;, including numeric forms with leading zeroes. Each occurrence becomes one regular space.

There are no settings. Existing posts and pages are processed on their next save. The replacement applies throughout each field, including markup and code examples. Custom post types, custom fields, attachments, and revision/autosave records are not processed.

The plugin is in English and includes a Russian translation in the native .l10n.php format supported by WordPress 6.5 and later. An editable .po source is included; no .mo file is needed.

== Installation ==

1. Upload the juliepr-remove-nbsp folder to /wp-content/plugins/, or upload the ZIP through the Plugins screen.
2. Activate Juliepr Remove NBSP.
3. Save a post or page. No configuration is required.

== Frequently Asked Questions ==

= Does activation modify all existing posts? =

No. A post or page is normalized when it is next saved.

= Does deactivation restore non-breaking spaces? =

No. The plugin changes stored fields during saving. Deactivation stops future replacements.

= Does it work with Multisite? =

Yes. Activate it per site or network-wide to process saves on the corresponding sites.

== Changelog ==

= 1.0.0 =
* Initial release.
* Normalize Unicode and HTML-encoded non-breaking spaces on post and page saves.
* Include Russian PHP translations without MO files.
