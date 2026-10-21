# Juliepr Remove NBSP

A small WordPress plugin that replaces non-breaking spaces with ordinary spaces when posts and pages are saved. No settings are required.

Requires WordPress 6.5 or later and PHP 7.4 or later.

## Behavior

- Processes titles, content, and excerpts of the built-in `post` and `page` types.
- Replaces the literal UTF-8 U+00A0 character, `&nbsp;`, `&#160;`, and `&#xA0;`. Numeric entities with leading zeroes and uppercase entity variants are also supported.
- Replaces each occurrence with one U+0020 space; consecutive spaces are not collapsed.
- Runs before the database write via `wp_insert_post_data`, including saves through the block editor, classic editor, REST API, and `wp_insert_post()` / `wp_update_post()`.
- Processes the entire stored field, including HTML attributes, block attributes, and code examples containing these forms.
- Leaves other entities, line breaks, tabs, narrow non-breaking spaces (U+202F), custom fields, attachments, custom post types, and revision/autosave records unchanged.
- Existing content is processed on its next save. Activation does not run a bulk cleanup. Deactivation does not undo saved replacements.

## Installation

1. Copy the `juliepr-remove-nbsp` folder to `wp-content/plugins/`, or upload the plugin ZIP in **Plugins → Add New → Upload Plugin**.
2. Activate **Juliepr Remove NBSP**. Multisite network activation is supported; only saves on sites where the plugin is active are processed.
3. Save a post or page to normalize its non-breaking spaces.

## Translation

The plugin metadata is English. A Russian (`ru_RU`) translation is included as a native WordPress `.l10n.php` file, with an editable `.po` source. No `.mo` file is used or required. The WordPress user/site locale controls the language shown in the Plugins screen.

After editing the PO source, rebuild the PHP translation with WP-CLI:

```sh
wp i18n make-php languages/juliepr-remove-nbsp-ru_RU.po
```

The plugin registers its text domain on `init` and uses WordPress just-in-time translation loading.

## Verification

With PHP installed, run the standalone behavior checks (no WordPress installation required):

```sh
php tests/normalization.php
php -l juliepr-remove-nbsp.php
php -l languages/juliepr-remove-nbsp-ru_RU.l10n.php
```

On a test WordPress site, save and reload a post and a page containing all supported forms in the title, content, and excerpt. Check the stored fields or code editor for U+0020 spaces, confirm the block editor reports no invalid blocks, and verify that the plugin description is Russian when the user language is Russian.

## License

GPL-2.0-or-later. Author: [Sergei Ashmarin (sashmarin)](https://github.com/sashmarin).
