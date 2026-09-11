<?php
/**
 * Help & Docs admin screen.
 *
 * One accordion per topic — only one item may be open at a time (JS
 * closes siblings on `toggle`). Every text fragment lives in its own
 * short __() / esc_html_e() call so translation tools stay within their
 * per-string length limits.
 *
 * @package SAS_Menu_Maker
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="wrap sas-menu-maker-wrap">
	<div class="sas-menu-maker-card">
		<header class="sas-menu-maker-page-header">
			<div class="sas-menu-maker-page-header-row">
				<h1 class="sas-menu-maker-page-title"><?php echo esc_html( get_admin_page_title() ); ?></h1>
			</div>
			<p class="sas-menu-maker-page-description">
				<?php esc_html_e( 'Short guides for every feature.', 'sas-menu-maker' ); ?>
				<?php esc_html_e( 'Click a title to open a section. Only one is open at a time.', 'sas-menu-maker' ); ?>
			</p>
			<hr class="sas-menu-maker-page-sep">
		</header>

		<div class="sas-menu-maker-page-body">
			<div class="sas-menu-maker-accordion" data-sas-menu-maker-accordion>

				<?php // ---------- Onboarding / Getting started ---------- ?>
				<details class="sas-menu-maker-accordion-item">
					<summary class="sas-menu-maker-accordion-summary">
						<span class="sas-menu-maker-accordion-chevron" aria-hidden="true"></span>
						<span class="sas-menu-maker-accordion-title"><?php esc_html_e( 'Getting started', 'sas-menu-maker' ); ?></span>
					</summary>
					<div class="sas-menu-maker-accordion-body">

						<h3><?php esc_html_e( 'What this plugin does', 'sas-menu-maker' ); ?></h3>
						<p><?php esc_html_e( 'SAS Menu Maker lets you build the menu of a restaurant, café or bar.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'You add items with a price, image, allergens and tags.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'Then you show the menu on any page with a shortcode or a block.', 'sas-menu-maker' ); ?></p>

						<h3><?php esc_html_e( 'The best order to set things up', 'sas-menu-maker' ); ?></h3>
						<p><?php esc_html_e( 'You can create things in any order, but this order is the easiest:', 'sas-menu-maker' ); ?></p>
						<ol>
							<li><?php esc_html_e( 'Allergens', 'sas-menu-maker' ); ?></li>
							<li><?php esc_html_e( 'Categories', 'sas-menu-maker' ); ?></li>
							<li><?php esc_html_e( 'Tags', 'sas-menu-maker' ); ?></li>
							<li><?php esc_html_e( 'Items', 'sas-menu-maker' ); ?></li>
							<li><?php esc_html_e( 'Offers', 'sas-menu-maker' ); ?></li>
							<li><?php esc_html_e( 'Options', 'sas-menu-maker' ); ?></li>
							<li><?php esc_html_e( 'Put the menu on a page (shortcode or block)', 'sas-menu-maker' ); ?></li>
						</ol>
						<p><?php esc_html_e( 'The reason: items depend on categories, tags and allergens. Offers depend on items.', 'sas-menu-maker' ); ?></p>

						<h3><?php esc_html_e( 'Step 1 — Allergens', 'sas-menu-maker' ); ?></h3>
						<p><?php esc_html_e( 'Small letter codes (A, B, C…) that mark ingredients guests must know about.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'Each allergen has a code and a readable name (e.g. G — Gluten).', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'Where to find:', 'sas-menu-maker' ); ?> <strong><?php esc_html_e( 'SAS Menu Maker → Allergens', 'sas-menu-maker' ); ?></strong></p>
						<p><?php esc_html_e( 'On the frontend the codes appear next to each item title and a small legend at the end of the menu explains them.', 'sas-menu-maker' ); ?></p>

						<h3><?php esc_html_e( 'Step 2 — Categories', 'sas-menu-maker' ); ?></h3>
						<p><?php esc_html_e( 'The main groups of your menu.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'For example: Starters, Main courses, Desserts, Drinks.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'Where to find:', 'sas-menu-maker' ); ?> <strong><?php esc_html_e( 'SAS Menu Maker → Categories', 'sas-menu-maker' ); ?></strong></p>
						<p><?php esc_html_e( 'On the frontend every category becomes a filter button that visitors can tap.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'Tip: for large menus, mark one category as "Default". Its filter is pre-selected so the page opens focused on that section instead of listing every item.', 'sas-menu-maker' ); ?></p>

						<h3><?php esc_html_e( 'Step 3 — Tags', 'sas-menu-maker' ); ?></h3>
						<p><?php esc_html_e( 'Extra labels that cross the categories.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'For example: Vegan, Vegetarian, New, Spicy.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'Where to find:', 'sas-menu-maker' ); ?> <strong><?php esc_html_e( 'SAS Menu Maker → Tags', 'sas-menu-maker' ); ?></strong></p>
						<p><?php esc_html_e( 'On the frontend tags become their own filter buttons and appear as small pills on each item.', 'sas-menu-maker' ); ?></p>

						<h3><?php esc_html_e( 'Step 4 — Items', 'sas-menu-maker' ); ?></h3>
						<p><?php esc_html_e( 'The actual food and drinks on your menu.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'Each item has a name, a short description and a price.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'If the same dish comes in several sizes, add variants (e.g. Small, Medium, Large) with their own prices.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'You can also add an image, a longer description, and assign categories, tags and allergens.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'Where to find:', 'sas-menu-maker' ); ?> <strong><?php esc_html_e( 'SAS Menu Maker → Items', 'sas-menu-maker' ); ?></strong></p>

						<h3><?php esc_html_e( 'Step 5 — Offers (optional)', 'sas-menu-maker' ); ?></h3>
						<p><?php esc_html_e( 'A special deal that combines several items at a fixed total price.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'For example: "Lunch menu — pizza + drink for 9,90 €".', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'You can set start and end dates and add conditions like "from 20€ order value".', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'Requires items to exist first.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'Where to find:', 'sas-menu-maker' ); ?> <strong><?php esc_html_e( 'SAS Menu Maker → Offers', 'sas-menu-maker' ); ?></strong></p>

						<h3><?php esc_html_e( 'Step 6 — Options', 'sas-menu-maker' ); ?></h3>
						<p><?php esc_html_e( 'Plugin-wide settings.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'Right now only the currency symbol lives here (€, $, CHF…).', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'Where to find:', 'sas-menu-maker' ); ?> <strong><?php esc_html_e( 'SAS Menu Maker → Options', 'sas-menu-maker' ); ?></strong></p>

						<h3><?php esc_html_e( 'Step 7 — Show the menu on your site', 'sas-menu-maker' ); ?></h3>
						<p><?php esc_html_e( 'Two ways, same result:', 'sas-menu-maker' ); ?></p>
						<ul>
							<li><?php esc_html_e( 'Shortcode:', 'sas-menu-maker' ); ?> <code>[sas_menu]</code></li>
							<li><?php esc_html_e( 'Block: search "SAS Menu" in the block inserter.', 'sas-menu-maker' ); ?></li>
						</ul>
						<p><?php esc_html_e( 'For a separate offers page:', 'sas-menu-maker' ); ?></p>
						<ul>
							<li><?php esc_html_e( 'Shortcode:', 'sas-menu-maker' ); ?> <code>[sas_menu_offers]</code></li>
							<li><?php esc_html_e( 'Block: search "SAS Menu — Offers" in the block inserter.', 'sas-menu-maker' ); ?></li>
						</ul>
						<p><?php esc_html_e( 'The other sections below explain the attributes and sidebar settings in detail.', 'sas-menu-maker' ); ?></p>

						<h3><?php esc_html_e( 'Tips for daily use', 'sas-menu-maker' ); ?></h3>
						<ul>
							<li><?php esc_html_e( 'Turn any item off with the Active toggle instead of deleting it.', 'sas-menu-maker' ); ?></li>
							<li><?php esc_html_e( 'Use the filters and search on the Items page to find things fast.', 'sas-menu-maker' ); ?></li>
							<li><?php esc_html_e( 'Bulk-edit on the Items page changes many items at once (prices, tags, active state).', 'sas-menu-maker' ); ?></li>
							<li><?php esc_html_e( 'Items that are used in an offer cannot be deleted until the offer is changed.', 'sas-menu-maker' ); ?></li>
						</ul>

					</div>
				</details>

				<?php // ---------- Shortcode ---------- ?>
				<details class="sas-menu-maker-accordion-item">
					<summary class="sas-menu-maker-accordion-summary">
						<span class="sas-menu-maker-accordion-chevron" aria-hidden="true"></span>
						<span class="sas-menu-maker-accordion-title"><?php esc_html_e( 'Shortcode', 'sas-menu-maker' ); ?></span>
					</summary>
					<div class="sas-menu-maker-accordion-body">

						<h3><?php esc_html_e( 'What is the shortcode?', 'sas-menu-maker' ); ?></h3>
						<p><?php esc_html_e( 'The shortcode shows your menu on any page or post.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'Add it to the content of a page or post:', 'sas-menu-maker' ); ?></p>
						<pre class="sas-menu-maker-help-code">[sas_menu]</pre>
						<p><?php esc_html_e( 'That is enough for a full menu with filters.', 'sas-menu-maker' ); ?></p>

						<h3><?php esc_html_e( 'How visitors filter', 'sas-menu-maker' ); ?></h3>
						<p><?php esc_html_e( 'Above the list your visitors see filter buttons.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'They can filter by category, by tag, and by allergen.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'Click a button to keep only matching items.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'Selected buttons turn dark.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'Click again to remove that filter.', 'sas-menu-maker' ); ?></p>

						<h3><?php esc_html_e( 'Change the look with attributes', 'sas-menu-maker' ); ?></h3>
						<p><?php esc_html_e( 'You can add attributes to the shortcode.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'All attributes are optional.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'If you leave them out, the default design is used.', 'sas-menu-maker' ); ?></p>

						<h4><code>image</code></h4>
						<p><?php esc_html_e( 'Where the picture sits on each item.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'Possible values:', 'sas-menu-maker' ); ?> <code>left</code>, <code>right</code>, <code>top</code></p>
						<p><?php esc_html_e( 'Default:', 'sas-menu-maker' ); ?> <code>left</code></p>
						<p><?php esc_html_e( 'Example:', 'sas-menu-maker' ); ?></p>
						<pre class="sas-menu-maker-help-code">[sas_menu image="top"]</pre>

						<h4><code>variants</code></h4>
						<p><?php esc_html_e( 'Where the sizes and prices are shown.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'Possible values:', 'sas-menu-maker' ); ?> <code>inline</code>, <code>modal</code></p>
						<p><?php esc_html_e( 'Default:', 'sas-menu-maker' ); ?> <code>inline</code></p>
						<p><code>inline</code> — <?php esc_html_e( 'shown directly on the item card.', 'sas-menu-maker' ); ?></p>
						<p><code>modal</code> — <?php esc_html_e( 'hidden on the card, shown only in the details window.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'Example:', 'sas-menu-maker' ); ?></p>
						<pre class="sas-menu-maker-help-code">[sas_menu variants="modal"]</pre>

						<h4><code>categories_title</code>, <code>tags_title</code></h4>
						<p><?php esc_html_e( 'Change the label above each filter group.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'Use your own words.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'Example:', 'sas-menu-maker' ); ?></p>
						<pre class="sas-menu-maker-help-code">[sas_menu categories_title="Speisen" tags_title="Merkmale"]</pre>

						<h4><code>allergens_title</code></h4>
						<p><?php esc_html_e( 'Change the label of the allergen legend at the end of the menu.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'Example:', 'sas-menu-maker' ); ?></p>
						<pre class="sas-menu-maker-help-code">[sas_menu allergens_title="Allergene"]</pre>

						<h4><code>columns</code></h4>
						<p><?php esc_html_e( 'Show the items as a grid with more than one column.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'Without this attribute the items stay in a single-column list.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'Format:', 'sas-menu-maker' ); ?> <code>&lt;screen-width&gt;__&lt;columns&gt;</code></p>
						<p><?php esc_html_e( 'Screen width is in pixels.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'Separate rules with a space.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'Example:', 'sas-menu-maker' ); ?></p>
						<pre class="sas-menu-maker-help-code">[sas_menu columns="720__1 1024__2 1400__3"]</pre>
						<p><?php esc_html_e( 'This means:', 'sas-menu-maker' ); ?></p>
						<ul>
							<li><?php esc_html_e( 'Screens up to 720 pixels wide: 1 column.', 'sas-menu-maker' ); ?></li>
							<li><?php esc_html_e( 'Screens up to 1024 pixels wide: 2 columns.', 'sas-menu-maker' ); ?></li>
							<li><?php esc_html_e( 'Screens up to 1400 pixels wide: 3 columns.', 'sas-menu-maker' ); ?></li>
							<li><?php esc_html_e( 'Wider screens keep the highest column count.', 'sas-menu-maker' ); ?></li>
						</ul>

						<h4><code>class</code></h4>
						<p><?php esc_html_e( 'Add your own CSS class to the outer wrapper.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'A web developer can then style this menu from the theme CSS.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'You can add more than one class, separated by a space.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'Example:', 'sas-menu-maker' ); ?></p>
						<pre class="sas-menu-maker-help-code">[sas_menu class="my-menu"]</pre>

						<h4><code>allergens_legend</code></h4>
						<p><?php esc_html_e( 'A small allergen list is printed at the end of the menu.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'It explains what each code letter means.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'Possible values:', 'sas-menu-maker' ); ?> <code>show</code>, <code>hide</code></p>
						<p><?php esc_html_e( 'Default:', 'sas-menu-maker' ); ?> <code>show</code></p>
						<p><?php esc_html_e( 'Use', 'sas-menu-maker' ); ?> <code>hide</code> <?php esc_html_e( 'if you do not want the list.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'Note: with', 'sas-menu-maker' ); ?> <code>hide</code> <?php esc_html_e( 'the code letters next to each item are hidden as well.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'Without the legend, the letters would have no meaning for visitors.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'Example:', 'sas-menu-maker' ); ?></p>
						<pre class="sas-menu-maker-help-code">[sas_menu allergens_legend="hide"]</pre>

						<h3><?php esc_html_e( 'Combine attributes', 'sas-menu-maker' ); ?></h3>
						<p><?php esc_html_e( 'You can use several attributes together, in any order.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'Example:', 'sas-menu-maker' ); ?></p>
						<pre class="sas-menu-maker-help-code">[sas_menu image="top" columns="600__1 1200__2" class="lunch-menu"]</pre>

						<h3><?php esc_html_e( 'Tips', 'sas-menu-maker' ); ?></h3>
						<p><?php esc_html_e( 'Use straight double quotes around values, like this:', 'sas-menu-maker' ); ?> <code>image="top"</code></p>
						<p><?php esc_html_e( 'Some editors change straight quotes into curly ones. That breaks the shortcode.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'If a value has no spaces, the quotes are optional.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'You can place several shortcodes on the same page.', 'sas-menu-maker' ); ?></p>

					</div>
				</details>

				<?php // ---------- Offers shortcode ---------- ?>
				<details class="sas-menu-maker-accordion-item">
					<summary class="sas-menu-maker-accordion-summary">
						<span class="sas-menu-maker-accordion-chevron" aria-hidden="true"></span>
						<span class="sas-menu-maker-accordion-title"><?php esc_html_e( 'Offers shortcode', 'sas-menu-maker' ); ?></span>
					</summary>
					<div class="sas-menu-maker-accordion-body">

						<h3><?php esc_html_e( 'What is the offers shortcode?', 'sas-menu-maker' ); ?></h3>
						<p><?php esc_html_e( 'A second shortcode that shows your offers.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'It looks similar to the menu shortcode but has no filter buttons.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'Add it to any page or post:', 'sas-menu-maker' ); ?></p>
						<pre class="sas-menu-maker-help-code">[sas_menu_offers]</pre>

						<h3><?php esc_html_e( 'What is shown by default', 'sas-menu-maker' ); ?></h3>
						<p><?php esc_html_e( 'Only active offers.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'An offer shows if it is running now, or if it starts within the next 7 days.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'Expired offers are hidden.', 'sas-menu-maker' ); ?></p>

						<h3><?php esc_html_e( 'Change the look with attributes', 'sas-menu-maker' ); ?></h3>

						<h4><code>image</code>, <code>columns</code>, <code>class</code></h4>
						<p><?php esc_html_e( 'Same meaning as in the menu shortcode.', 'sas-menu-maker' ); ?></p>

						<h4><code>validity</code></h4>
						<p><?php esc_html_e( 'Possible values:', 'sas-menu-maker' ); ?> <code>preview</code>, <code>all</code></p>
						<p><?php esc_html_e( 'Default:', 'sas-menu-maker' ); ?> <code>preview</code></p>
						<p><code>preview</code> — <?php esc_html_e( 'active offers running now or starting within 7 days.', 'sas-menu-maker' ); ?></p>
						<p><code>all</code> — <?php esc_html_e( 'every active offer, regardless of dates.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'Example:', 'sas-menu-maker' ); ?></p>
						<pre class="sas-menu-maker-help-code">[sas_menu_offers validity="all"]</pre>

						<h4><code>show_items</code></h4>
						<p><?php esc_html_e( 'Where to show the list of included items.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'Possible values:', 'sas-menu-maker' ); ?> <code>inline</code>, <code>modal</code>, <code>hide</code></p>
						<p><?php esc_html_e( 'Default:', 'sas-menu-maker' ); ?> <code>inline</code></p>
						<p><code>inline</code> — <?php esc_html_e( 'shown directly on the offer card.', 'sas-menu-maker' ); ?></p>
						<p><code>modal</code> — <?php esc_html_e( 'shown only inside the details window.', 'sas-menu-maker' ); ?></p>
						<p><code>hide</code> — <?php esc_html_e( 'not shown at all.', 'sas-menu-maker' ); ?></p>

						<h4><code>show_desc</code></h4>
						<p><?php esc_html_e( 'Where to show the offer description.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'Possible values:', 'sas-menu-maker' ); ?> <code>inline</code>, <code>modal</code>, <code>hide</code></p>
						<p><?php esc_html_e( 'Default:', 'sas-menu-maker' ); ?> <code>inline</code></p>
						<p><?php esc_html_e( 'Set to', 'sas-menu-maker' ); ?> <code>modal</code> <?php esc_html_e( 'for a minimal card (image + title + price only) with everything else inside the details window.', 'sas-menu-maker' ); ?></p>

						<h4><code>show_dates</code></h4>
						<p><?php esc_html_e( 'Toggle the "Valid X – Y" line under each offer.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'Possible values:', 'sas-menu-maker' ); ?> <code>show</code>, <code>hide</code></p>
						<p><?php esc_html_e( 'Default:', 'sas-menu-maker' ); ?> <code>show</code></p>

						<h4><code>conditions</code></h4>
						<p><?php esc_html_e( 'Where to show the small conditions text (e.g. "from 20€ order value").', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'Possible values:', 'sas-menu-maker' ); ?> <code>modal</code>, <code>inline</code>, <code>hide</code></p>
						<p><?php esc_html_e( 'Default:', 'sas-menu-maker' ); ?> <code>modal</code></p>

						<h3><?php esc_html_e( 'When is the card clickable?', 'sas-menu-maker' ); ?></h3>
						<p><?php esc_html_e( 'Only when the modal actually has something to show.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'That is the case if any of these applies:', 'sas-menu-maker' ); ?></p>
						<ul>
							<li><?php esc_html_e( 'conditions set to modal AND the offer has a conditions text', 'sas-menu-maker' ); ?></li>
							<li><?php esc_html_e( 'show_items set to modal AND the offer has items', 'sas-menu-maker' ); ?></li>
							<li><?php esc_html_e( 'show_desc set to modal AND the offer has a description', 'sas-menu-maker' ); ?></li>
						</ul>
						<p><?php esc_html_e( 'Otherwise the card is static and shows everything it has directly.', 'sas-menu-maker' ); ?></p>

					</div>
				</details>

				<?php // ---------- Group shortcode ---------- ?>
				<details class="sas-menu-maker-accordion-item">
					<summary class="sas-menu-maker-accordion-summary">
						<span class="sas-menu-maker-accordion-chevron" aria-hidden="true"></span>
						<span class="sas-menu-maker-accordion-title"><?php esc_html_e( 'Group shortcode (one category or tag)', 'sas-menu-maker' ); ?></span>
					</summary>
					<div class="sas-menu-maker-accordion-body">

						<h3><?php esc_html_e( 'What is the group shortcode?', 'sas-menu-maker' ); ?></h3>
						<p><?php esc_html_e( 'Shows every item that belongs to one category or one tag.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'Perfect for a dedicated "Drinks" page or a "Vegan" list.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'On top of the list you see the category or tag as a hero header: image, name, description — whatever you have defined.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'There is no filter bar (nothing to filter — the group is the filter).', 'sas-menu-maker' ); ?></p>

						<h3><?php esc_html_e( 'Pick a category or a tag', 'sas-menu-maker' ); ?></h3>
						<p><?php esc_html_e( 'You must set exactly one of these:', 'sas-menu-maker' ); ?></p>
						<pre class="sas-menu-maker-help-code">[sas_menu_group category="pizza"]</pre>
						<pre class="sas-menu-maker-help-code">[sas_menu_group tag="vegan"]</pre>
						<p><?php esc_html_e( 'You can use the slug (as above) or the numeric id.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'If both category and tag are set, category wins.', 'sas-menu-maker' ); ?></p>

						<h3><?php esc_html_e( 'Which items are shown', 'sas-menu-maker' ); ?></h3>
						<p><?php esc_html_e( 'Every active item that has the chosen category (or the chosen tag).', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'An item that lives in several categories still shows up as long as one of them is the chosen one.', 'sas-menu-maker' ); ?></p>

						<h3><?php esc_html_e( 'Header and layout attributes', 'sas-menu-maker' ); ?></h3>

						<h4><code>show_header</code></h4>
						<p><?php esc_html_e( 'Toggle the hero header (image + title + description).', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'Values:', 'sas-menu-maker' ); ?> <code>show</code>, <code>hide</code></p>
						<p><?php esc_html_e( 'Default:', 'sas-menu-maker' ); ?> <code>show</code></p>

						<h4><code>collapsed</code></h4>
						<p><?php esc_html_e( 'When yes, the group opens with only the title + description visible as a click strip.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'Clicking reveals the image and the item list.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'Useful when several groups share a single page and you want short "menu sections" that expand on demand.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'Values:', 'sas-menu-maker' ); ?> <code>no</code>, <code>yes</code></p>
						<p><?php esc_html_e( 'Default:', 'sas-menu-maker' ); ?> <code>no</code></p>
						<p><?php esc_html_e( 'Example:', 'sas-menu-maker' ); ?></p>
						<pre class="sas-menu-maker-help-code">[sas_menu_group category="drinks" collapsed="yes"]</pre>

						<h4><code>image</code>, <code>variants</code>, <code>columns</code>, <code>class</code>, <code>allergens_legend</code></h4>
						<p><?php esc_html_e( 'Same meaning as in the main menu shortcode.', 'sas-menu-maker' ); ?></p>

						<h3><?php esc_html_e( 'Combine several groups on one page', 'sas-menu-maker' ); ?></h3>
						<p><?php esc_html_e( 'Stack multiple shortcodes to build a full menu page from focused sections:', 'sas-menu-maker' ); ?></p>
						<pre class="sas-menu-maker-help-code">[sas_menu_group category="starters"]
[sas_menu_group category="mains"]
[sas_menu_group category="desserts" collapsed="yes"]</pre>

					</div>
				</details>

				<?php // ---------- Group block ---------- ?>
				<details class="sas-menu-maker-accordion-item">
					<summary class="sas-menu-maker-accordion-summary">
						<span class="sas-menu-maker-accordion-chevron" aria-hidden="true"></span>
						<span class="sas-menu-maker-accordion-title"><?php esc_html_e( 'Group block (Gutenberg)', 'sas-menu-maker' ); ?></span>
					</summary>
					<div class="sas-menu-maker-accordion-body">

						<h3><?php esc_html_e( 'What is the group block?', 'sas-menu-maker' ); ?></h3>
						<p><?php esc_html_e( 'The visual companion to the group shortcode.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'Pick a category or a tag from a dropdown and the block shows every matching item, headed by that taxonomy\'s image and description.', 'sas-menu-maker' ); ?></p>

						<h3><?php esc_html_e( 'How to add', 'sas-menu-maker' ); ?></h3>
						<p><?php esc_html_e( 'Open a page or post in the block editor.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'Click the plus button, search for:', 'sas-menu-maker' ); ?> <code>SAS Menu</code></p>
						<p><?php esc_html_e( 'Pick the block named:', 'sas-menu-maker' ); ?> <strong><?php esc_html_e( 'SAS Menu — Group', 'sas-menu-maker' ); ?></strong></p>

						<h3><?php esc_html_e( 'Sidebar panels', 'sas-menu-maker' ); ?></h3>
						<ul>
							<li><?php esc_html_e( 'Source: choose Category or Tag, then pick the specific one.', 'sas-menu-maker' ); ?></li>
							<li><?php esc_html_e( 'Header: toggle the hero header on/off and switch between full and collapsed mode.', 'sas-menu-maker' ); ?></li>
							<li><?php esc_html_e( 'Layout: image position, variants placement, allergen legend toggle.', 'sas-menu-maker' ); ?></li>
							<li><?php esc_html_e( 'Grid layout: turn on and set the columns per screen width.', 'sas-menu-maker' ); ?></li>
							<li><?php esc_html_e( 'Alignment & size: font size, card content alignment, border radius.', 'sas-menu-maker' ); ?></li>
							<li><?php esc_html_e( 'Colors: Container, Header, Items, Tags, Legend — each with its own picker set.', 'sas-menu-maker' ); ?></li>
						</ul>

						<h3><?php esc_html_e( 'Multiple groups on one page', 'sas-menu-maker' ); ?></h3>
						<p><?php esc_html_e( 'You can add several SAS Menu — Group blocks on the same page, each pointing to a different category or tag.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'Set the header of some to "Start collapsed" so long pages stay readable.', 'sas-menu-maker' ); ?></p>

					</div>
				</details>

				<?php // ---------- Gutenberg block ---------- ?>
				<details class="sas-menu-maker-accordion-item">
					<summary class="sas-menu-maker-accordion-summary">
						<span class="sas-menu-maker-accordion-chevron" aria-hidden="true"></span>
						<span class="sas-menu-maker-accordion-title"><?php esc_html_e( 'Menu block (Gutenberg)', 'sas-menu-maker' ); ?></span>
					</summary>
					<div class="sas-menu-maker-accordion-body">

						<h3><?php esc_html_e( 'What is the menu block?', 'sas-menu-maker' ); ?></h3>
						<p><?php esc_html_e( 'The menu block does the same thing as the shortcode.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'You use it inside the block editor without typing any code.', 'sas-menu-maker' ); ?></p>

						<h3><?php esc_html_e( 'How to add the block', 'sas-menu-maker' ); ?></h3>
						<p><?php esc_html_e( 'Open a page or post in the block editor.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'Click the plus button to add a new block.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'Search for:', 'sas-menu-maker' ); ?> <code>SAS Menu</code></p>
						<p><?php esc_html_e( 'Pick the block named:', 'sas-menu-maker' ); ?> <strong><?php esc_html_e( 'SAS Menu', 'sas-menu-maker' ); ?></strong></p>
						<p><?php esc_html_e( 'You will see a live preview right away.', 'sas-menu-maker' ); ?></p>

						<h3><?php esc_html_e( 'How to configure the block', 'sas-menu-maker' ); ?></h3>
						<p><?php esc_html_e( 'Click the block once to select it.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'The settings appear on the right in the sidebar.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'You can change:', 'sas-menu-maker' ); ?></p>
						<ul>
							<li><?php esc_html_e( 'Layout: image position, variant display, allergen legend.', 'sas-menu-maker' ); ?></li>
							<li><?php esc_html_e( 'Filter titles: labels above each filter group.', 'sas-menu-maker' ); ?></li>
							<li><?php esc_html_e( 'Grid layout: turn on and set how many columns per screen width.', 'sas-menu-maker' ); ?></li>
						</ul>
						<p><?php esc_html_e( 'For an extra CSS class, use the standard "Advanced" section at the bottom of the sidebar.', 'sas-menu-maker' ); ?></p>

						<h3><?php esc_html_e( 'Block-only styling', 'sas-menu-maker' ); ?></h3>
						<p><?php esc_html_e( 'The block has extra options that are not available in the shortcode.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'These live in the sidebar on the right when the block is selected.', 'sas-menu-maker' ); ?></p>

						<h4><?php esc_html_e( 'Font size', 'sas-menu-maker' ); ?></h4>
						<p><?php esc_html_e( 'Choose Small, Medium or Large.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'This scales the whole menu at once — text, chips, prices, everything.', 'sas-menu-maker' ); ?></p>

						<h4><?php esc_html_e( 'Alignment', 'sas-menu-maker' ); ?></h4>
						<p><?php esc_html_e( 'Two separate settings:', 'sas-menu-maker' ); ?></p>
						<ul>
							<li><?php esc_html_e( 'Filter alignment: pushes the filter chips to left, center or right.', 'sas-menu-maker' ); ?></li>
							<li><?php esc_html_e( 'Item content alignment: aligns the text inside each item card.', 'sas-menu-maker' ); ?></li>
						</ul>

						<h4><?php esc_html_e( 'Border radius', 'sas-menu-maker' ); ?></h4>
						<p><?php esc_html_e( 'One slider for the whole block.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'The value in pixels is applied everywhere: container, filter, items, image and tag pills.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'Leave empty (reset button) to keep the default rounded shapes.', 'sas-menu-maker' ); ?></p>

						<h4><?php esc_html_e( 'Colors', 'sas-menu-maker' ); ?></h4>
						<p><?php esc_html_e( 'The sidebar has separate color panels for the different parts of the menu.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'Container, filter, items, tags, allergen legend — each has its own colors.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'Any color you leave empty keeps its default.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'The color picker also shows your theme colors, so you can match the site look with one click.', 'sas-menu-maker' ); ?></p>

						<h3><?php esc_html_e( 'Same content as the shortcode', 'sas-menu-maker' ); ?></h3>
						<p><?php esc_html_e( 'The block uses the shortcode under the hood for the actual menu.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'You can mix and match:', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'Use the block for easy visual editing, and the shortcode when you edit content in code or via a page builder that only accepts shortcodes.', 'sas-menu-maker' ); ?></p>

					</div>
				</details>

				<?php // ---------- Offers block ---------- ?>
				<details class="sas-menu-maker-accordion-item">
					<summary class="sas-menu-maker-accordion-summary">
						<span class="sas-menu-maker-accordion-chevron" aria-hidden="true"></span>
						<span class="sas-menu-maker-accordion-title"><?php esc_html_e( 'Offers block (Gutenberg)', 'sas-menu-maker' ); ?></span>
					</summary>
					<div class="sas-menu-maker-accordion-body">

						<h3><?php esc_html_e( 'What is the offers block?', 'sas-menu-maker' ); ?></h3>
						<p><?php esc_html_e( 'The visual companion to the offers shortcode.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'It does the same thing but with settings in the sidebar instead of shortcode attributes.', 'sas-menu-maker' ); ?></p>

						<h3><?php esc_html_e( 'How to add', 'sas-menu-maker' ); ?></h3>
						<p><?php esc_html_e( 'Open a page or post in the block editor.', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'Click the plus button, search for:', 'sas-menu-maker' ); ?> <code>SAS Menu</code></p>
						<p><?php esc_html_e( 'Pick the block named:', 'sas-menu-maker' ); ?> <strong><?php esc_html_e( 'SAS Menu — Offers', 'sas-menu-maker' ); ?></strong></p>

						<h3><?php esc_html_e( 'Sidebar panels', 'sas-menu-maker' ); ?></h3>
						<ul>
							<li><?php esc_html_e( 'Content & layout: which offers, image position, validity dates toggle.', 'sas-menu-maker' ); ?></li>
							<li><?php esc_html_e( 'Card vs. modal: choose per piece where description, items and conditions live.', 'sas-menu-maker' ); ?></li>
							<li><?php esc_html_e( 'Grid layout: turn on and set the columns per screen width.', 'sas-menu-maker' ); ?></li>
							<li><?php esc_html_e( 'Alignment & size: font size, card content alignment, border radius.', 'sas-menu-maker' ); ?></li>
							<li><?php esc_html_e( 'Colors: container, cards, offer details — each with its own picker set.', 'sas-menu-maker' ); ?></li>
						</ul>

						<h3><?php esc_html_e( 'Same content as the shortcode', 'sas-menu-maker' ); ?></h3>
						<p><?php esc_html_e( 'Under the hood the block uses [sas_menu_offers].', 'sas-menu-maker' ); ?></p>
						<p><?php esc_html_e( 'Use whichever is easier: the block for visual editing, the shortcode inside page builders or code.', 'sas-menu-maker' ); ?></p>

					</div>
				</details>

			</div>
		</div>

		<footer class="sas-menu-maker-page-footer">
			<hr class="sas-menu-maker-page-sep">
		</footer>
	</div>
</div>
