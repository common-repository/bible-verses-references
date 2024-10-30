=== Bible Verses References ===
Contributors: bboyguil
Donate link: https://www.paypal.com/donate/?hosted_button_id=XPG3QWEM8U7YN
Tags: Bible, biblia, Scripture, Christian, verses, references, theology, teologia, versículo, cristianismo
Requires at least: 5.0.0
Tested up to: 6.4.3
Stable tag: 1.1.2
Requires PHP: 7.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin fetches all the biblical references present in your posts and pages and adds the text of the verse in a floating window when the user hovers over the reference.

== Description ==

If you have a blog where you write about theology, there are certainly several biblical references to support your argument. You can't quote all the texts in full because search engines can punish your blog's SEO for containing copied texts.

If you leave only the Biblical references, they are often ignored by users, as many do not have the time or willingness to look up each of the Biblical references.

Thinking about solving this problem, what this plugin does is a scan of all references present in your text and adds an `ABBR` tag containing the text of the quoted verse.

When the user hovers over the mouse, a popup will open containing the biblical text. That way, the usability of your Christian blog will be much better and your users will thank you.

== Supported reference formats ==

- **João 3:16** parses a single verse
- **1 John 5:11-12** parses two verses separated with hyphen
- **I John 5:11,14-15** parses a verse followed by a range separated with comm
- **Romanos 7:25-8:1** parses two chapter/verse pairs separated with hyphen
- **Romanos 12:1,2** parses two versions separated with comma
- **Rm 12:1-2,4,13**
- **Rm 12:1-2,4,13;13:2;14:2-4,6**

== Installation ==

= From inside the panel (recommended) =

1. Navigate to `Dashboard` ▸ `Plugins` ▸ `Add New`;
2. Search for `WP Custom Code`;
3. Click `Install`, then `Activate`.

= Manual Installation =

1. Download the plugin as a .zip file;
2. Unzip the downloaded file and upload the `bible-verses-reference` folder to the `/wp-content/plugins/` directory
1. Navigate to `Dashboard` ▸ `Plugins` and activate the plugin.

== Frequently Asked Questions ==

= What is the bible translation version of the plugin? =

The version used is João Ferreira de Almeida

== Screenshots ==

1. In this example, the plugin captured the biblical reference in this format.
2. As you can see, several formats are easily identified by the plugin.
3. Select the translation for the biblical text. We provide 3 translations in several languages.

== About the operation ==

The plugin extracts, through regex, the biblical references present in the posts, then a tag is added highlighting the biblical reference to indicate to the user the need to hover. As soon as the user hovers over the reference or taps on the cell phone, a request is made to our API, where the biblical text is returned.

At the moment, there is no need for extra configuration, nor is it necessary to edit your posts to flag or edit the references, as seen in other plugins with the same proposal.

Operation is completely automated.

== Upgrade Notice ==

We will have new features in the next update.
We are working on implementing new Bible versions and new customization features.

== Changelog ==
= 1.1.2 =
* chore: writing error in the translation function;
* translation: complete translation in pt_BR;

= 1.1.1 =
* improvements: now the popup arrow is not misaligned;
* improvements: greater precision when searching for references;
* chore: add icon to indicate reference with available popup;

= 1.1.0 =
* refactor: regex update for get more reference;
* feat: Added settings page to select translation;
* chore: update translation pt_BR.

= 1.0.0 =
* Initial version