<?php
/**
 * Items admin screen.
 *
 * Table skeleton (populated by JS from REST), main create/edit off-canvas
 * with chip selectors for categories/tags/allergens, a variants sub-panel
 * layered on top of the main panel, and a delete confirmation modal.
 *
 * @package SAS_Menu_Maker
 */

defined( 'ABSPATH' ) || exit;

// This file is `require`d from SAS_Menu_Maker_Admin::render_items_page(), so
// every variable declared below is a local of that method scope, not a
// PHP global — the prefix rule does not apply.
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
?>
<div class="wrap sas-menu-maker-wrap">
	<div class="sas-menu-maker-card">
		<header class="sas-menu-maker-page-header">
			<div class="sas-menu-maker-page-header-row">
				<h1 class="sas-menu-maker-page-title"><?php echo esc_html( get_admin_page_title() ); ?></h1>
				<button type="button"
					class="button button-primary sas-menu-maker-btn-add"
					data-sas-menu-maker-panel-open="sas-menu-maker-panel-item-form"
					data-sas-menu-maker-panel-mode="create">
					<span class="dashicons dashicons-plus-alt2" aria-hidden="true"></span>
					<?php esc_html_e( 'New Item', 'sas-menu-maker' ); ?>
				</button>
			</div>
			<p class="sas-menu-maker-page-description">
				<?php esc_html_e( 'Menu items — food and drinks. Assign categories, tags and allergens, and define size/portion variants for pricing.', 'sas-menu-maker' ); ?>
			</p>
			<hr class="sas-menu-maker-page-sep">
		</header>

		<div class="sas-menu-maker-page-body">
			<div class="sas-menu-maker-filters sas-menu-maker-filters-collapsed" data-sas-menu-maker-filters="items">
				<div class="sas-menu-maker-filters-header"
					data-sas-menu-maker-filters-toggle
					role="button"
					tabindex="0"
					aria-expanded="false"
					aria-controls="sas-menu-maker-filters-body-items">
					<span class="sas-menu-maker-filters-chevron dashicons dashicons-arrow-right" aria-hidden="true"></span>
					<span class="sas-menu-maker-filters-title">
						<span class="dashicons dashicons-filter" aria-hidden="true"></span>
						<?php esc_html_e( 'Filters', 'sas-menu-maker' ); ?>
					</span>
					<span class="sas-menu-maker-filters-count" data-sas-menu-maker-filters-count hidden></span>
					<button type="button"
						class="button-link sas-menu-maker-filters-reset"
						data-sas-menu-maker-filters-reset>
						<?php esc_html_e( 'Reset', 'sas-menu-maker' ); ?>
					</button>
				</div>
				<div class="sas-menu-maker-filters-body" id="sas-menu-maker-filters-body-items">
					<div class="sas-menu-maker-filter-field sas-menu-maker-filter-search">
						<label for="sas-menu-maker-filter-search">
							<?php esc_html_e( 'Search', 'sas-menu-maker' ); ?>
						</label>
						<input type="search"
							id="sas-menu-maker-filter-search"
							data-sas-menu-maker-filter="search"
							placeholder="<?php esc_attr_e( 'Name or description…', 'sas-menu-maker' ); ?>">
					</div>

					<div class="sas-menu-maker-filter-field">
						<label><?php esc_html_e( 'Categories', 'sas-menu-maker' ); ?></label>
						<div class="sas-menu-maker-chips sas-menu-maker-chips-filter"
							data-sas-menu-maker-chips="categories"
							data-sas-menu-maker-chips-name="filter_categories"
							data-sas-menu-maker-chips-empty="<?php esc_attr_e( 'No categories.', 'sas-menu-maker' ); ?>"></div>
					</div>

					<div class="sas-menu-maker-filter-field">
						<label><?php esc_html_e( 'Tags', 'sas-menu-maker' ); ?></label>
						<div class="sas-menu-maker-chips sas-menu-maker-chips-filter"
							data-sas-menu-maker-chips="tags"
							data-sas-menu-maker-chips-name="filter_tags"
							data-sas-menu-maker-chips-empty="<?php esc_attr_e( 'No tags.', 'sas-menu-maker' ); ?>"></div>
					</div>

					<div class="sas-menu-maker-filter-field">
						<label><?php esc_html_e( 'Allergens', 'sas-menu-maker' ); ?></label>
						<div class="sas-menu-maker-chips sas-menu-maker-chips-filter"
							data-sas-menu-maker-chips="allergens"
							data-sas-menu-maker-chips-name="filter_allergens"
							data-sas-menu-maker-chips-empty="<?php esc_attr_e( 'No allergens.', 'sas-menu-maker' ); ?>"></div>
					</div>

					<div class="sas-menu-maker-filter-row">
						<div class="sas-menu-maker-filter-field">
							<label for="sas-menu-maker-filter-status">
								<?php esc_html_e( 'Status', 'sas-menu-maker' ); ?>
							</label>
							<select id="sas-menu-maker-filter-status" data-sas-menu-maker-filter="status">
								<option value=""><?php esc_html_e( 'All', 'sas-menu-maker' ); ?></option>
								<option value="active"><?php esc_html_e( 'Active', 'sas-menu-maker' ); ?></option>
								<option value="inactive"><?php esc_html_e( 'Inactive', 'sas-menu-maker' ); ?></option>
							</select>
						</div>

						<div class="sas-menu-maker-filter-field">
							<label><?php esc_html_e( 'Price', 'sas-menu-maker' ); ?></label>
							<div class="sas-menu-maker-filter-range">
								<input type="number"
									step="0.01"
									min="0"
									data-sas-menu-maker-filter="price_min"
									data-sas-menu-maker-price
									placeholder="<?php esc_attr_e( 'From', 'sas-menu-maker' ); ?>">
								<span aria-hidden="true">–</span>
								<input type="number"
									step="0.01"
									min="0"
									data-sas-menu-maker-filter="price_max"
									data-sas-menu-maker-price
									placeholder="<?php esc_attr_e( 'To', 'sas-menu-maker' ); ?>">
							</div>
						</div>

						<div class="sas-menu-maker-filter-field">
							<label for="sas-menu-maker-filter-image">
								<?php esc_html_e( 'Image', 'sas-menu-maker' ); ?>
							</label>
							<select id="sas-menu-maker-filter-image" data-sas-menu-maker-filter="image">
								<option value=""><?php esc_html_e( 'All', 'sas-menu-maker' ); ?></option>
								<option value="with"><?php esc_html_e( 'With image', 'sas-menu-maker' ); ?></option>
								<option value="without"><?php esc_html_e( 'Without image', 'sas-menu-maker' ); ?></option>
							</select>
						</div>
					</div>
				</div>
			</div>

			<div class="sas-menu-maker-bulk-toolbar" data-sas-menu-maker-bulk-toolbar hidden>
				<span class="sas-menu-maker-bulk-count">
					<strong data-sas-menu-maker-bulk-count>0</strong>
					<span><?php esc_html_e( 'selected', 'sas-menu-maker' ); ?></span>
				</span>
				<div class="sas-menu-maker-bulk-actions">
					<button type="button"
						class="button button-primary"
						data-sas-menu-maker-bulk-open="sas-menu-maker-panel-item-bulk">
						<?php esc_html_e( 'Bulk Edit…', 'sas-menu-maker' ); ?>
					</button>
					<button type="button" class="button" data-sas-menu-maker-bulk-clear>
						<?php esc_html_e( 'Clear selection', 'sas-menu-maker' ); ?>
					</button>
				</div>
			</div>

			<table class="wp-list-table widefat striped fixed sas-menu-maker-table sas-menu-maker-items-table"
				data-sas-menu-maker-list="items"
				data-sas-menu-maker-selectable
				data-sas-menu-maker-panel="sas-menu-maker-panel-item-form"
				data-sas-menu-maker-modal-delete="sas-menu-maker-modal-delete-item">
				<thead>
					<tr>
						<th scope="col" class="sas-menu-maker-col-select">
							<input type="checkbox"
								data-sas-menu-maker-select-all
								aria-label="<?php esc_attr_e( 'Select all', 'sas-menu-maker' ); ?>">
						</th>
						<th scope="col" class="sas-menu-maker-col-thumb"><?php esc_html_e( 'Image', 'sas-menu-maker' ); ?></th>
						<th scope="col" class="sas-menu-maker-col-name"><?php esc_html_e( 'Name', 'sas-menu-maker' ); ?></th>
						<th scope="col" class="sas-menu-maker-col-categories"><?php esc_html_e( 'Categories', 'sas-menu-maker' ); ?></th>
						<th scope="col" class="sas-menu-maker-col-price"><?php esc_html_e( 'Price', 'sas-menu-maker' ); ?></th>
						<th scope="col" class="sas-menu-maker-col-active"><?php esc_html_e( 'Active', 'sas-menu-maker' ); ?></th>
						<th scope="col" class="sas-menu-maker-col-dates"><?php esc_html_e( 'Dates', 'sas-menu-maker' ); ?></th>
						<th scope="col" class="sas-menu-maker-col-actions"><?php esc_html_e( 'Actions', 'sas-menu-maker' ); ?></th>
					</tr>
				</thead>
				<tbody data-sas-menu-maker-list-body>
					<tr class="sas-menu-maker-row-status">
						<td colspan="8"><?php esc_html_e( 'Loading…', 'sas-menu-maker' ); ?></td>
					</tr>
				</tbody>
			</table>
		</div>

		<footer class="sas-menu-maker-page-footer">
			<hr class="sas-menu-maker-page-sep">
		</footer>
	</div>

	<?php // -------- Main off-canvas panel: create/edit item -------- ?>
	<aside class="sas-menu-maker-offcanvas" id="sas-menu-maker-panel-item-form" aria-hidden="true">
		<div class="sas-menu-maker-offcanvas-backdrop" data-sas-menu-maker-panel-close></div>
		<div class="sas-menu-maker-offcanvas-panel"
			role="dialog"
			aria-modal="true"
			aria-labelledby="sas-menu-maker-panel-item-form-title">
			<form class="sas-menu-maker-form"
				data-sas-menu-maker-endpoint="items"
				data-sas-menu-maker-mode="create">
				<header class="sas-menu-maker-offcanvas-header">
					<h2 class="sas-menu-maker-offcanvas-title"
						id="sas-menu-maker-panel-item-form-title"
						data-sas-menu-maker-title-create="<?php esc_attr_e( 'New Item', 'sas-menu-maker' ); ?>"
						data-sas-menu-maker-title-edit="<?php esc_attr_e( 'Edit Item', 'sas-menu-maker' ); ?>">
						<?php esc_html_e( 'New Item', 'sas-menu-maker' ); ?>
					</h2>
					<button type="button"
						class="sas-menu-maker-offcanvas-close"
						data-sas-menu-maker-panel-close
						aria-label="<?php esc_attr_e( 'Close', 'sas-menu-maker' ); ?>">
						<span class="dashicons dashicons-no-alt" aria-hidden="true"></span>
					</button>
				</header>

				<div class="sas-menu-maker-offcanvas-body">
					<div class="sas-menu-maker-field">
						<label for="sas-menu-maker-item-name">
							<?php esc_html_e( 'Name', 'sas-menu-maker' ); ?>
							<span class="sas-menu-maker-required" aria-hidden="true">*</span>
						</label>
						<input type="text" id="sas-menu-maker-item-name" name="name" required>
					</div>

					<div class="sas-menu-maker-field">
						<label for="sas-menu-maker-item-desc-short"><?php esc_html_e( 'Short Description', 'sas-menu-maker' ); ?></label>
						<textarea id="sas-menu-maker-item-desc-short" name="description_short" rows="2" maxlength="500"></textarea>
					</div>

					<div class="sas-menu-maker-field">
						<label for="sas-menu-maker-item-desc-long"><?php esc_html_e( 'Long Description', 'sas-menu-maker' ); ?></label>
						<textarea id="sas-menu-maker-item-desc-long" name="description_long" rows="5"></textarea>
					</div>

					<div class="sas-menu-maker-field">
						<label for="sas-menu-maker-item-price">
							<?php esc_html_e( 'Base Price', 'sas-menu-maker' ); ?>
						</label>
						<input type="number"
							id="sas-menu-maker-item-price"
							name="price"
							step="0.01"
							min="0"
							data-sas-menu-maker-price
							placeholder="<?php esc_attr_e( 'Leave empty when using variants only', 'sas-menu-maker' ); ?>">
					</div>

					<div class="sas-menu-maker-field sas-menu-maker-field-media">
						<label><?php esc_html_e( 'Image', 'sas-menu-maker' ); ?></label>
						<div class="sas-menu-maker-media-picker" data-sas-menu-maker-media-picker>
							<div class="sas-menu-maker-media-preview"
								data-sas-menu-maker-media-preview
								data-empty="<?php esc_attr_e( 'No image selected', 'sas-menu-maker' ); ?>"></div>
							<div class="sas-menu-maker-media-actions">
								<button type="button" class="button" data-sas-menu-maker-media-choose>
									<?php esc_html_e( 'Choose Image', 'sas-menu-maker' ); ?>
								</button>
								<button type="button" class="button-link sas-menu-maker-media-remove" data-sas-menu-maker-media-remove hidden>
									<?php esc_html_e( 'Remove', 'sas-menu-maker' ); ?>
								</button>
							</div>
							<input type="hidden" name="media_id" value="0" data-sas-menu-maker-media-input>
						</div>
					</div>

					<div class="sas-menu-maker-field">
						<label><?php esc_html_e( 'Categories', 'sas-menu-maker' ); ?></label>
						<div class="sas-menu-maker-select"
							data-sas-menu-maker-select="categories"
							data-sas-menu-maker-select-name="category_ids"
							data-sas-menu-maker-select-placeholder="<?php esc_attr_e( 'Type to search categories…', 'sas-menu-maker' ); ?>"
							data-sas-menu-maker-select-empty="<?php esc_attr_e( 'No categories yet — create some first.', 'sas-menu-maker' ); ?>">
						</div>
					</div>

					<div class="sas-menu-maker-field">
						<label><?php esc_html_e( 'Tags', 'sas-menu-maker' ); ?></label>
						<div class="sas-menu-maker-select"
							data-sas-menu-maker-select="tags"
							data-sas-menu-maker-select-name="tag_ids"
							data-sas-menu-maker-select-placeholder="<?php esc_attr_e( 'Type to search tags…', 'sas-menu-maker' ); ?>"
							data-sas-menu-maker-select-empty="<?php esc_attr_e( 'No tags yet — create some first.', 'sas-menu-maker' ); ?>">
						</div>
					</div>

					<div class="sas-menu-maker-field">
						<label><?php esc_html_e( 'Allergens', 'sas-menu-maker' ); ?></label>
						<div class="sas-menu-maker-select"
							data-sas-menu-maker-select="allergens"
							data-sas-menu-maker-select-name="allergen_ids"
							data-sas-menu-maker-select-placeholder="<?php esc_attr_e( 'Type to search allergens…', 'sas-menu-maker' ); ?>"
							data-sas-menu-maker-select-empty="<?php esc_attr_e( 'No allergens yet — create some first.', 'sas-menu-maker' ); ?>">
						</div>
					</div>

					<div class="sas-menu-maker-field">
						<label><?php esc_html_e( 'Variants', 'sas-menu-maker' ); ?></label>
						<div class="sas-menu-maker-variants-summary" data-sas-menu-maker-variants-summary>
							<span class="sas-menu-maker-variants-count" data-sas-menu-maker-variants-count>
								<?php esc_html_e( 'None', 'sas-menu-maker' ); ?>
							</span>
							<button type="button"
								class="button"
								data-sas-menu-maker-subpanel-open="sas-menu-maker-panel-item-variants"
								data-sas-menu-maker-subpanel-parent="sas-menu-maker-panel-item-form">
								<?php esc_html_e( 'Manage Variants', 'sas-menu-maker' ); ?>
							</button>
						</div>
						<p class="sas-menu-maker-field-help">
							<?php esc_html_e( 'Add size or portion variants (Small / Medium / Large …). When variants are set, the item is priced from the smallest variant.', 'sas-menu-maker' ); ?>
						</p>
					</div>

					<div class="sas-menu-maker-field">
						<label for="sas-menu-maker-item-sort"><?php esc_html_e( 'Sort Order', 'sas-menu-maker' ); ?></label>
						<input type="number" id="sas-menu-maker-item-sort" name="sort_order" value="0" step="1" min="0">
					</div>

					<div class="sas-menu-maker-field sas-menu-maker-field-checkbox">
						<label for="sas-menu-maker-item-active">
							<input type="checkbox" id="sas-menu-maker-item-active" name="is_active" value="1" checked>
							<?php esc_html_e( 'Active', 'sas-menu-maker' ); ?>
						</label>
					</div>
				</div>

				<footer class="sas-menu-maker-offcanvas-footer">
					<button type="button" class="button" data-sas-menu-maker-panel-close>
						<?php esc_html_e( 'Cancel', 'sas-menu-maker' ); ?>
					</button>
					<button type="submit"
						class="button button-primary"
						data-sas-menu-maker-submit
						data-sas-menu-maker-label-create="<?php esc_attr_e( 'Save Item', 'sas-menu-maker' ); ?>"
						data-sas-menu-maker-label-edit="<?php esc_attr_e( 'Update Item', 'sas-menu-maker' ); ?>">
						<?php esc_html_e( 'Save Item', 'sas-menu-maker' ); ?>
					</button>
				</footer>
			</form>
		</div>
	</aside>

	<?php // -------- Sub-panel: variants (opened from main item panel) -------- ?>
	<aside class="sas-menu-maker-offcanvas sas-menu-maker-offcanvas-sub" id="sas-menu-maker-panel-item-variants" aria-hidden="true">
		<div class="sas-menu-maker-offcanvas-backdrop" data-sas-menu-maker-panel-close></div>
		<div class="sas-menu-maker-offcanvas-panel"
			role="dialog"
			aria-modal="true"
			aria-labelledby="sas-menu-maker-panel-item-variants-title">
			<header class="sas-menu-maker-offcanvas-header">
				<div class="sas-menu-maker-offcanvas-title-group">
					<span class="sas-menu-maker-offcanvas-breadcrumb">
						<?php esc_html_e( 'Item', 'sas-menu-maker' ); ?>
						<span class="sas-menu-maker-breadcrumb-sep" aria-hidden="true">›</span>
					</span>
					<h2 class="sas-menu-maker-offcanvas-title" id="sas-menu-maker-panel-item-variants-title">
						<?php esc_html_e( 'Variants', 'sas-menu-maker' ); ?>
					</h2>
				</div>
				<button type="button"
					class="sas-menu-maker-offcanvas-close"
					data-sas-menu-maker-panel-close
					aria-label="<?php esc_attr_e( 'Close', 'sas-menu-maker' ); ?>">
					<span class="dashicons dashicons-no-alt" aria-hidden="true"></span>
				</button>
			</header>

			<div class="sas-menu-maker-offcanvas-body">
				<p class="sas-menu-maker-field-help">
					<?php esc_html_e( 'Each variant has a label (Small / Medium …) and its own price. Empty labels are skipped on save.', 'sas-menu-maker' ); ?>
				</p>

				<div class="sas-menu-maker-variants-list" data-sas-menu-maker-variants-list>
					<?php // Rendered by JS from the item form's in-memory state. ?>
				</div>

				<div class="sas-menu-maker-variants-empty" data-sas-menu-maker-variants-empty hidden>
					<?php esc_html_e( 'No variants yet.', 'sas-menu-maker' ); ?>
				</div>

				<button type="button" class="button sas-menu-maker-variants-add" data-sas-menu-maker-variant-add>
					<span class="dashicons dashicons-plus-alt2" aria-hidden="true"></span>
					<?php esc_html_e( 'Add Variant', 'sas-menu-maker' ); ?>
				</button>
			</div>

			<footer class="sas-menu-maker-offcanvas-footer">
				<button type="button" class="button button-primary" data-sas-menu-maker-panel-close>
					<?php esc_html_e( 'Done', 'sas-menu-maker' ); ?>
				</button>
			</footer>
		</div>
	</aside>

	<?php // -------- Bulk-edit off-canvas -------- ?>
	<aside class="sas-menu-maker-offcanvas" id="sas-menu-maker-panel-item-bulk" aria-hidden="true">
		<div class="sas-menu-maker-offcanvas-backdrop" data-sas-menu-maker-panel-close></div>
		<div class="sas-menu-maker-offcanvas-panel"
			role="dialog"
			aria-modal="true"
			aria-labelledby="sas-menu-maker-panel-item-bulk-title">
			<form class="sas-menu-maker-form" data-sas-menu-maker-bulk-form>
				<header class="sas-menu-maker-offcanvas-header">
					<h2 class="sas-menu-maker-offcanvas-title" id="sas-menu-maker-panel-item-bulk-title">
						<?php esc_html_e( 'Bulk Edit', 'sas-menu-maker' ); ?>
						<span class="sas-menu-maker-offcanvas-title-badge">
							<strong data-sas-menu-maker-bulk-panel-count>0</strong>
							<?php esc_html_e( 'items', 'sas-menu-maker' ); ?>
						</span>
					</h2>
					<button type="button"
						class="sas-menu-maker-offcanvas-close"
						data-sas-menu-maker-panel-close
						aria-label="<?php esc_attr_e( 'Close', 'sas-menu-maker' ); ?>">
						<span class="dashicons dashicons-no-alt" aria-hidden="true"></span>
					</button>
				</header>

				<div class="sas-menu-maker-offcanvas-body">
					<p class="sas-menu-maker-field-help">
						<?php esc_html_e( 'Only fields with a mode selected are applied. Others are left untouched.', 'sas-menu-maker' ); ?>
					</p>

					<?php
					$bulk_relations = array(
						array(
							'label'      => __( 'Categories', 'sas-menu-maker' ),
							'chips_key'  => 'categories',
							'mode_name'  => 'categories_mode',
							'chips_name' => 'categories_ids',
							'empty'      => __( 'No categories yet.', 'sas-menu-maker' ),
						),
						array(
							'label'      => __( 'Tags', 'sas-menu-maker' ),
							'chips_key'  => 'tags',
							'mode_name'  => 'tags_mode',
							'chips_name' => 'tags_ids',
							'empty'      => __( 'No tags yet.', 'sas-menu-maker' ),
						),
						array(
							'label'      => __( 'Allergens', 'sas-menu-maker' ),
							'chips_key'  => 'allergens',
							'mode_name'  => 'allergens_mode',
							'chips_name' => 'allergens_ids',
							'empty'      => __( 'No allergens yet.', 'sas-menu-maker' ),
						),
					);
					?>
					<?php foreach ( $bulk_relations as $rel ) : ?>
						<div class="sas-menu-maker-bulk-op">
							<label class="sas-menu-maker-bulk-op-label"><?php echo esc_html( $rel['label'] ); ?></label>
							<select class="sas-menu-maker-bulk-mode" name="<?php echo esc_attr( $rel['mode_name'] ); ?>">
								<option value=""><?php esc_html_e( 'No change', 'sas-menu-maker' ); ?></option>
								<option value="replace"><?php esc_html_e( 'Replace with…', 'sas-menu-maker' ); ?></option>
								<option value="add"><?php esc_html_e( 'Add these…', 'sas-menu-maker' ); ?></option>
								<option value="remove"><?php esc_html_e( 'Remove these…', 'sas-menu-maker' ); ?></option>
							</select>
							<div class="sas-menu-maker-chips"
								data-sas-menu-maker-chips="<?php echo esc_attr( $rel['chips_key'] ); ?>"
								data-sas-menu-maker-chips-name="<?php echo esc_attr( $rel['chips_name'] ); ?>"
								data-sas-menu-maker-chips-empty="<?php echo esc_attr( $rel['empty'] ); ?>">
							</div>
						</div>
					<?php endforeach; ?>

					<div class="sas-menu-maker-bulk-op">
						<label class="sas-menu-maker-bulk-op-label" for="sas-menu-maker-bulk-base-price-mode">
							<?php esc_html_e( 'Base Price', 'sas-menu-maker' ); ?>
						</label>
						<select class="sas-menu-maker-bulk-mode" id="sas-menu-maker-bulk-base-price-mode" name="base_price_mode">
							<option value=""><?php esc_html_e( 'No change', 'sas-menu-maker' ); ?></option>
							<option value="replace"><?php esc_html_e( 'Set to…', 'sas-menu-maker' ); ?></option>
							<option value="increase"><?php esc_html_e( 'Increase by…', 'sas-menu-maker' ); ?></option>
							<option value="decrease"><?php esc_html_e( 'Decrease by…', 'sas-menu-maker' ); ?></option>
						</select>
						<input type="number"
							name="base_price_value"
							step="0.01"
							min="0"
							data-sas-menu-maker-price
							placeholder="<?php esc_attr_e( 'Amount', 'sas-menu-maker' ); ?>">
						<p class="sas-menu-maker-field-help">
							<?php esc_html_e( 'Items without a base price are skipped when increasing or decreasing.', 'sas-menu-maker' ); ?>
						</p>
					</div>

					<div class="sas-menu-maker-bulk-op">
						<label class="sas-menu-maker-bulk-op-label" for="sas-menu-maker-bulk-variant-prices-mode">
							<?php esc_html_e( 'Variant Prices', 'sas-menu-maker' ); ?>
						</label>
						<select class="sas-menu-maker-bulk-mode" id="sas-menu-maker-bulk-variant-prices-mode" name="variant_prices_mode">
							<option value=""><?php esc_html_e( 'No change', 'sas-menu-maker' ); ?></option>
							<option value="increase"><?php esc_html_e( 'Increase by…', 'sas-menu-maker' ); ?></option>
							<option value="decrease"><?php esc_html_e( 'Decrease by…', 'sas-menu-maker' ); ?></option>
						</select>
						<input type="number"
							name="variant_prices_value"
							step="0.01"
							min="0"
							data-sas-menu-maker-price
							placeholder="<?php esc_attr_e( 'Amount', 'sas-menu-maker' ); ?>">
						<p class="sas-menu-maker-field-help">
							<?php esc_html_e( 'Applies to every variant of every selected item. Prices are floored at 0.', 'sas-menu-maker' ); ?>
						</p>
					</div>

					<div class="sas-menu-maker-bulk-op">
						<label class="sas-menu-maker-bulk-op-label" for="sas-menu-maker-bulk-is-active-mode">
							<?php esc_html_e( 'Active State', 'sas-menu-maker' ); ?>
						</label>
						<select class="sas-menu-maker-bulk-mode" id="sas-menu-maker-bulk-is-active-mode" name="is_active_mode">
							<option value=""><?php esc_html_e( 'No change', 'sas-menu-maker' ); ?></option>
							<option value="1"><?php esc_html_e( 'Activate', 'sas-menu-maker' ); ?></option>
							<option value="0"><?php esc_html_e( 'Deactivate', 'sas-menu-maker' ); ?></option>
						</select>
					</div>
				</div>

				<footer class="sas-menu-maker-offcanvas-footer">
					<button type="button" class="button" data-sas-menu-maker-panel-close>
						<?php esc_html_e( 'Cancel', 'sas-menu-maker' ); ?>
					</button>
					<button type="submit" class="button button-primary" data-sas-menu-maker-submit>
						<?php esc_html_e( 'Apply', 'sas-menu-maker' ); ?>
					</button>
				</footer>
			</form>
		</div>
	</aside>

	<?php // -------- Confirm delete modal -------- ?>
	<div class="sas-menu-maker-modal" id="sas-menu-maker-modal-delete-item" aria-hidden="true" role="dialog" aria-modal="true">
		<div class="sas-menu-maker-modal-backdrop" data-sas-menu-maker-modal-close></div>
		<div class="sas-menu-maker-modal-dialog" aria-labelledby="sas-menu-maker-modal-delete-item-title">
			<header class="sas-menu-maker-modal-header">
				<h2 class="sas-menu-maker-modal-title" id="sas-menu-maker-modal-delete-item-title">
					<?php esc_html_e( 'Delete item?', 'sas-menu-maker' ); ?>
				</h2>
			</header>
			<div class="sas-menu-maker-modal-body">
				<p>
					<?php
					printf(
						/* translators: %s: item name placeholder replaced by JS. */
						esc_html__( 'Are you sure you want to delete %s? This also removes its variants and assignments. This cannot be undone.', 'sas-menu-maker' ),
						'<strong data-sas-menu-maker-modal-target-name>—</strong>'
					);
					?>
				</p>
			</div>
			<footer class="sas-menu-maker-modal-footer">
				<button type="button" class="button" data-sas-menu-maker-modal-close>
					<?php esc_html_e( 'Cancel', 'sas-menu-maker' ); ?>
				</button>
				<button type="button"
					class="button sas-menu-maker-btn-danger"
					data-sas-menu-maker-modal-confirm-delete>
					<?php esc_html_e( 'Delete', 'sas-menu-maker' ); ?>
				</button>
			</footer>
		</div>
	</div>
</div>
