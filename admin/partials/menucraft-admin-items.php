<?php
/**
 * Items admin screen.
 *
 * Table skeleton (populated by JS from REST), main create/edit off-canvas
 * with chip selectors for categories/tags/allergens, a variants sub-panel
 * layered on top of the main panel, and a delete confirmation modal.
 *
 * @package MenuCraft
 */

defined( 'ABSPATH' ) || exit;

// This file is `require`d from MenuCraft_Admin::render_items_page(), so
// every variable declared below is a local of that method scope, not a
// PHP global — the prefix rule does not apply.
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
?>
<div class="wrap menucraft-wrap">
	<div class="menucraft-card">
		<header class="menucraft-page-header">
			<div class="menucraft-page-header-row">
				<h1 class="menucraft-page-title"><?php echo esc_html( get_admin_page_title() ); ?></h1>
				<button type="button"
					class="button button-primary menucraft-btn-add"
					data-menucraft-panel-open="menucraft-panel-item-form"
					data-menucraft-panel-mode="create">
					<span class="dashicons dashicons-plus-alt2" aria-hidden="true"></span>
					<?php esc_html_e( 'New Item', 'sas-menu-maker' ); ?>
				</button>
			</div>
			<p class="menucraft-page-description">
				<?php esc_html_e( 'Menu items — food and drinks. Assign categories, tags and allergens, and define size/portion variants for pricing.', 'sas-menu-maker' ); ?>
			</p>
			<hr class="menucraft-page-sep">
		</header>

		<div class="menucraft-page-body">
			<div class="menucraft-filters menucraft-filters-collapsed" data-menucraft-filters="items">
				<div class="menucraft-filters-header"
					data-menucraft-filters-toggle
					role="button"
					tabindex="0"
					aria-expanded="false"
					aria-controls="menucraft-filters-body-items">
					<span class="menucraft-filters-chevron dashicons dashicons-arrow-right" aria-hidden="true"></span>
					<span class="menucraft-filters-title">
						<span class="dashicons dashicons-filter" aria-hidden="true"></span>
						<?php esc_html_e( 'Filters', 'sas-menu-maker' ); ?>
					</span>
					<span class="menucraft-filters-count" data-menucraft-filters-count hidden></span>
					<button type="button"
						class="button-link menucraft-filters-reset"
						data-menucraft-filters-reset>
						<?php esc_html_e( 'Reset', 'sas-menu-maker' ); ?>
					</button>
				</div>
				<div class="menucraft-filters-body" id="menucraft-filters-body-items">
					<div class="menucraft-filter-field menucraft-filter-search">
						<label for="menucraft-filter-search">
							<?php esc_html_e( 'Search', 'sas-menu-maker' ); ?>
						</label>
						<input type="search"
							id="menucraft-filter-search"
							data-menucraft-filter="search"
							placeholder="<?php esc_attr_e( 'Name or description…', 'sas-menu-maker' ); ?>">
					</div>

					<div class="menucraft-filter-field">
						<label><?php esc_html_e( 'Categories', 'sas-menu-maker' ); ?></label>
						<div class="menucraft-chips menucraft-chips-filter"
							data-menucraft-chips="categories"
							data-menucraft-chips-name="filter_categories"
							data-menucraft-chips-empty="<?php esc_attr_e( 'No categories.', 'sas-menu-maker' ); ?>"></div>
					</div>

					<div class="menucraft-filter-field">
						<label><?php esc_html_e( 'Tags', 'sas-menu-maker' ); ?></label>
						<div class="menucraft-chips menucraft-chips-filter"
							data-menucraft-chips="tags"
							data-menucraft-chips-name="filter_tags"
							data-menucraft-chips-empty="<?php esc_attr_e( 'No tags.', 'sas-menu-maker' ); ?>"></div>
					</div>

					<div class="menucraft-filter-field">
						<label><?php esc_html_e( 'Allergens', 'sas-menu-maker' ); ?></label>
						<div class="menucraft-chips menucraft-chips-filter"
							data-menucraft-chips="allergens"
							data-menucraft-chips-name="filter_allergens"
							data-menucraft-chips-empty="<?php esc_attr_e( 'No allergens.', 'sas-menu-maker' ); ?>"></div>
					</div>

					<div class="menucraft-filter-row">
						<div class="menucraft-filter-field">
							<label for="menucraft-filter-status">
								<?php esc_html_e( 'Status', 'sas-menu-maker' ); ?>
							</label>
							<select id="menucraft-filter-status" data-menucraft-filter="status">
								<option value=""><?php esc_html_e( 'All', 'sas-menu-maker' ); ?></option>
								<option value="active"><?php esc_html_e( 'Active', 'sas-menu-maker' ); ?></option>
								<option value="inactive"><?php esc_html_e( 'Inactive', 'sas-menu-maker' ); ?></option>
							</select>
						</div>

						<div class="menucraft-filter-field">
							<label><?php esc_html_e( 'Price', 'sas-menu-maker' ); ?></label>
							<div class="menucraft-filter-range">
								<input type="number"
									step="0.01"
									min="0"
									data-menucraft-filter="price_min"
									data-menucraft-price
									placeholder="<?php esc_attr_e( 'From', 'sas-menu-maker' ); ?>">
								<span aria-hidden="true">–</span>
								<input type="number"
									step="0.01"
									min="0"
									data-menucraft-filter="price_max"
									data-menucraft-price
									placeholder="<?php esc_attr_e( 'To', 'sas-menu-maker' ); ?>">
							</div>
						</div>

						<div class="menucraft-filter-field">
							<label for="menucraft-filter-image">
								<?php esc_html_e( 'Image', 'sas-menu-maker' ); ?>
							</label>
							<select id="menucraft-filter-image" data-menucraft-filter="image">
								<option value=""><?php esc_html_e( 'All', 'sas-menu-maker' ); ?></option>
								<option value="with"><?php esc_html_e( 'With image', 'sas-menu-maker' ); ?></option>
								<option value="without"><?php esc_html_e( 'Without image', 'sas-menu-maker' ); ?></option>
							</select>
						</div>
					</div>
				</div>
			</div>

			<div class="menucraft-bulk-toolbar" data-menucraft-bulk-toolbar hidden>
				<span class="menucraft-bulk-count">
					<strong data-menucraft-bulk-count>0</strong>
					<span><?php esc_html_e( 'selected', 'sas-menu-maker' ); ?></span>
				</span>
				<div class="menucraft-bulk-actions">
					<button type="button"
						class="button button-primary"
						data-menucraft-bulk-open="menucraft-panel-item-bulk">
						<?php esc_html_e( 'Bulk Edit…', 'sas-menu-maker' ); ?>
					</button>
					<button type="button" class="button" data-menucraft-bulk-clear>
						<?php esc_html_e( 'Clear selection', 'sas-menu-maker' ); ?>
					</button>
				</div>
			</div>

			<table class="wp-list-table widefat striped fixed menucraft-table menucraft-items-table"
				data-menucraft-list="items"
				data-menucraft-selectable
				data-menucraft-panel="menucraft-panel-item-form"
				data-menucraft-modal-delete="menucraft-modal-delete-item">
				<thead>
					<tr>
						<th scope="col" class="menucraft-col-select">
							<input type="checkbox"
								data-menucraft-select-all
								aria-label="<?php esc_attr_e( 'Select all', 'sas-menu-maker' ); ?>">
						</th>
						<th scope="col" class="menucraft-col-thumb"><?php esc_html_e( 'Image', 'sas-menu-maker' ); ?></th>
						<th scope="col" class="menucraft-col-name"><?php esc_html_e( 'Name', 'sas-menu-maker' ); ?></th>
						<th scope="col" class="menucraft-col-categories"><?php esc_html_e( 'Categories', 'sas-menu-maker' ); ?></th>
						<th scope="col" class="menucraft-col-price"><?php esc_html_e( 'Price', 'sas-menu-maker' ); ?></th>
						<th scope="col" class="menucraft-col-active"><?php esc_html_e( 'Active', 'sas-menu-maker' ); ?></th>
						<th scope="col" class="menucraft-col-dates"><?php esc_html_e( 'Dates', 'sas-menu-maker' ); ?></th>
						<th scope="col" class="menucraft-col-actions"><?php esc_html_e( 'Actions', 'sas-menu-maker' ); ?></th>
					</tr>
				</thead>
				<tbody data-menucraft-list-body>
					<tr class="menucraft-row-status">
						<td colspan="8"><?php esc_html_e( 'Loading…', 'sas-menu-maker' ); ?></td>
					</tr>
				</tbody>
			</table>
		</div>

		<footer class="menucraft-page-footer">
			<hr class="menucraft-page-sep">
		</footer>
	</div>

	<?php // -------- Main off-canvas panel: create/edit item -------- ?>
	<aside class="menucraft-offcanvas" id="menucraft-panel-item-form" aria-hidden="true">
		<div class="menucraft-offcanvas-backdrop" data-menucraft-panel-close></div>
		<div class="menucraft-offcanvas-panel"
			role="dialog"
			aria-modal="true"
			aria-labelledby="menucraft-panel-item-form-title">
			<form class="menucraft-form"
				data-menucraft-endpoint="items"
				data-menucraft-mode="create">
				<header class="menucraft-offcanvas-header">
					<h2 class="menucraft-offcanvas-title"
						id="menucraft-panel-item-form-title"
						data-menucraft-title-create="<?php esc_attr_e( 'New Item', 'sas-menu-maker' ); ?>"
						data-menucraft-title-edit="<?php esc_attr_e( 'Edit Item', 'sas-menu-maker' ); ?>">
						<?php esc_html_e( 'New Item', 'sas-menu-maker' ); ?>
					</h2>
					<button type="button"
						class="menucraft-offcanvas-close"
						data-menucraft-panel-close
						aria-label="<?php esc_attr_e( 'Close', 'sas-menu-maker' ); ?>">
						<span class="dashicons dashicons-no-alt" aria-hidden="true"></span>
					</button>
				</header>

				<div class="menucraft-offcanvas-body">
					<div class="menucraft-field">
						<label for="menucraft-item-name">
							<?php esc_html_e( 'Name', 'sas-menu-maker' ); ?>
							<span class="menucraft-required" aria-hidden="true">*</span>
						</label>
						<input type="text" id="menucraft-item-name" name="name" required>
					</div>

					<div class="menucraft-field">
						<label for="menucraft-item-desc-short"><?php esc_html_e( 'Short Description', 'sas-menu-maker' ); ?></label>
						<textarea id="menucraft-item-desc-short" name="description_short" rows="2" maxlength="500"></textarea>
					</div>

					<div class="menucraft-field">
						<label for="menucraft-item-desc-long"><?php esc_html_e( 'Long Description', 'sas-menu-maker' ); ?></label>
						<textarea id="menucraft-item-desc-long" name="description_long" rows="5"></textarea>
					</div>

					<div class="menucraft-field">
						<label for="menucraft-item-price">
							<?php esc_html_e( 'Base Price', 'sas-menu-maker' ); ?>
						</label>
						<input type="number"
							id="menucraft-item-price"
							name="price"
							step="0.01"
							min="0"
							data-menucraft-price
							placeholder="<?php esc_attr_e( 'Leave empty when using variants only', 'sas-menu-maker' ); ?>">
					</div>

					<div class="menucraft-field menucraft-field-media">
						<label><?php esc_html_e( 'Image', 'sas-menu-maker' ); ?></label>
						<div class="menucraft-media-picker" data-menucraft-media-picker>
							<div class="menucraft-media-preview"
								data-menucraft-media-preview
								data-empty="<?php esc_attr_e( 'No image selected', 'sas-menu-maker' ); ?>"></div>
							<div class="menucraft-media-actions">
								<button type="button" class="button" data-menucraft-media-choose>
									<?php esc_html_e( 'Choose Image', 'sas-menu-maker' ); ?>
								</button>
								<button type="button" class="button-link menucraft-media-remove" data-menucraft-media-remove hidden>
									<?php esc_html_e( 'Remove', 'sas-menu-maker' ); ?>
								</button>
							</div>
							<input type="hidden" name="media_id" value="0" data-menucraft-media-input>
						</div>
					</div>

					<div class="menucraft-field">
						<label><?php esc_html_e( 'Categories', 'sas-menu-maker' ); ?></label>
						<div class="menucraft-select"
							data-menucraft-select="categories"
							data-menucraft-select-name="category_ids"
							data-menucraft-select-placeholder="<?php esc_attr_e( 'Type to search categories…', 'sas-menu-maker' ); ?>"
							data-menucraft-select-empty="<?php esc_attr_e( 'No categories yet — create some first.', 'sas-menu-maker' ); ?>">
						</div>
					</div>

					<div class="menucraft-field">
						<label><?php esc_html_e( 'Tags', 'sas-menu-maker' ); ?></label>
						<div class="menucraft-select"
							data-menucraft-select="tags"
							data-menucraft-select-name="tag_ids"
							data-menucraft-select-placeholder="<?php esc_attr_e( 'Type to search tags…', 'sas-menu-maker' ); ?>"
							data-menucraft-select-empty="<?php esc_attr_e( 'No tags yet — create some first.', 'sas-menu-maker' ); ?>">
						</div>
					</div>

					<div class="menucraft-field">
						<label><?php esc_html_e( 'Allergens', 'sas-menu-maker' ); ?></label>
						<div class="menucraft-select"
							data-menucraft-select="allergens"
							data-menucraft-select-name="allergen_ids"
							data-menucraft-select-placeholder="<?php esc_attr_e( 'Type to search allergens…', 'sas-menu-maker' ); ?>"
							data-menucraft-select-empty="<?php esc_attr_e( 'No allergens yet — create some first.', 'sas-menu-maker' ); ?>">
						</div>
					</div>

					<div class="menucraft-field">
						<label><?php esc_html_e( 'Variants', 'sas-menu-maker' ); ?></label>
						<div class="menucraft-variants-summary" data-menucraft-variants-summary>
							<span class="menucraft-variants-count" data-menucraft-variants-count>
								<?php esc_html_e( 'None', 'sas-menu-maker' ); ?>
							</span>
							<button type="button"
								class="button"
								data-menucraft-subpanel-open="menucraft-panel-item-variants"
								data-menucraft-subpanel-parent="menucraft-panel-item-form">
								<?php esc_html_e( 'Manage Variants', 'sas-menu-maker' ); ?>
							</button>
						</div>
						<p class="menucraft-field-help">
							<?php esc_html_e( 'Add size or portion variants (Small / Medium / Large …). When variants are set, the item is priced from the smallest variant.', 'sas-menu-maker' ); ?>
						</p>
					</div>

					<div class="menucraft-field">
						<label for="menucraft-item-sort"><?php esc_html_e( 'Sort Order', 'sas-menu-maker' ); ?></label>
						<input type="number" id="menucraft-item-sort" name="sort_order" value="0" step="1" min="0">
					</div>

					<div class="menucraft-field menucraft-field-checkbox">
						<label for="menucraft-item-active">
							<input type="checkbox" id="menucraft-item-active" name="is_active" value="1" checked>
							<?php esc_html_e( 'Active', 'sas-menu-maker' ); ?>
						</label>
					</div>
				</div>

				<footer class="menucraft-offcanvas-footer">
					<button type="button" class="button" data-menucraft-panel-close>
						<?php esc_html_e( 'Cancel', 'sas-menu-maker' ); ?>
					</button>
					<button type="submit"
						class="button button-primary"
						data-menucraft-submit
						data-menucraft-label-create="<?php esc_attr_e( 'Save Item', 'sas-menu-maker' ); ?>"
						data-menucraft-label-edit="<?php esc_attr_e( 'Update Item', 'sas-menu-maker' ); ?>">
						<?php esc_html_e( 'Save Item', 'sas-menu-maker' ); ?>
					</button>
				</footer>
			</form>
		</div>
	</aside>

	<?php // -------- Sub-panel: variants (opened from main item panel) -------- ?>
	<aside class="menucraft-offcanvas menucraft-offcanvas-sub" id="menucraft-panel-item-variants" aria-hidden="true">
		<div class="menucraft-offcanvas-backdrop" data-menucraft-panel-close></div>
		<div class="menucraft-offcanvas-panel"
			role="dialog"
			aria-modal="true"
			aria-labelledby="menucraft-panel-item-variants-title">
			<header class="menucraft-offcanvas-header">
				<div class="menucraft-offcanvas-title-group">
					<span class="menucraft-offcanvas-breadcrumb">
						<?php esc_html_e( 'Item', 'sas-menu-maker' ); ?>
						<span class="menucraft-breadcrumb-sep" aria-hidden="true">›</span>
					</span>
					<h2 class="menucraft-offcanvas-title" id="menucraft-panel-item-variants-title">
						<?php esc_html_e( 'Variants', 'sas-menu-maker' ); ?>
					</h2>
				</div>
				<button type="button"
					class="menucraft-offcanvas-close"
					data-menucraft-panel-close
					aria-label="<?php esc_attr_e( 'Close', 'sas-menu-maker' ); ?>">
					<span class="dashicons dashicons-no-alt" aria-hidden="true"></span>
				</button>
			</header>

			<div class="menucraft-offcanvas-body">
				<p class="menucraft-field-help">
					<?php esc_html_e( 'Each variant has a label (Small / Medium …) and its own price. Empty labels are skipped on save.', 'sas-menu-maker' ); ?>
				</p>

				<div class="menucraft-variants-list" data-menucraft-variants-list>
					<?php // Rendered by JS from the item form's in-memory state. ?>
				</div>

				<div class="menucraft-variants-empty" data-menucraft-variants-empty hidden>
					<?php esc_html_e( 'No variants yet.', 'sas-menu-maker' ); ?>
				</div>

				<button type="button" class="button menucraft-variants-add" data-menucraft-variant-add>
					<span class="dashicons dashicons-plus-alt2" aria-hidden="true"></span>
					<?php esc_html_e( 'Add Variant', 'sas-menu-maker' ); ?>
				</button>
			</div>

			<footer class="menucraft-offcanvas-footer">
				<button type="button" class="button button-primary" data-menucraft-panel-close>
					<?php esc_html_e( 'Done', 'sas-menu-maker' ); ?>
				</button>
			</footer>
		</div>
	</aside>

	<?php // -------- Bulk-edit off-canvas -------- ?>
	<aside class="menucraft-offcanvas" id="menucraft-panel-item-bulk" aria-hidden="true">
		<div class="menucraft-offcanvas-backdrop" data-menucraft-panel-close></div>
		<div class="menucraft-offcanvas-panel"
			role="dialog"
			aria-modal="true"
			aria-labelledby="menucraft-panel-item-bulk-title">
			<form class="menucraft-form" data-menucraft-bulk-form>
				<header class="menucraft-offcanvas-header">
					<h2 class="menucraft-offcanvas-title" id="menucraft-panel-item-bulk-title">
						<?php esc_html_e( 'Bulk Edit', 'sas-menu-maker' ); ?>
						<span class="menucraft-offcanvas-title-badge">
							<strong data-menucraft-bulk-panel-count>0</strong>
							<?php esc_html_e( 'items', 'sas-menu-maker' ); ?>
						</span>
					</h2>
					<button type="button"
						class="menucraft-offcanvas-close"
						data-menucraft-panel-close
						aria-label="<?php esc_attr_e( 'Close', 'sas-menu-maker' ); ?>">
						<span class="dashicons dashicons-no-alt" aria-hidden="true"></span>
					</button>
				</header>

				<div class="menucraft-offcanvas-body">
					<p class="menucraft-field-help">
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
						<div class="menucraft-bulk-op">
							<label class="menucraft-bulk-op-label"><?php echo esc_html( $rel['label'] ); ?></label>
							<select class="menucraft-bulk-mode" name="<?php echo esc_attr( $rel['mode_name'] ); ?>">
								<option value=""><?php esc_html_e( 'No change', 'sas-menu-maker' ); ?></option>
								<option value="replace"><?php esc_html_e( 'Replace with…', 'sas-menu-maker' ); ?></option>
								<option value="add"><?php esc_html_e( 'Add these…', 'sas-menu-maker' ); ?></option>
								<option value="remove"><?php esc_html_e( 'Remove these…', 'sas-menu-maker' ); ?></option>
							</select>
							<div class="menucraft-chips"
								data-menucraft-chips="<?php echo esc_attr( $rel['chips_key'] ); ?>"
								data-menucraft-chips-name="<?php echo esc_attr( $rel['chips_name'] ); ?>"
								data-menucraft-chips-empty="<?php echo esc_attr( $rel['empty'] ); ?>">
							</div>
						</div>
					<?php endforeach; ?>

					<div class="menucraft-bulk-op">
						<label class="menucraft-bulk-op-label" for="menucraft-bulk-base-price-mode">
							<?php esc_html_e( 'Base Price', 'sas-menu-maker' ); ?>
						</label>
						<select class="menucraft-bulk-mode" id="menucraft-bulk-base-price-mode" name="base_price_mode">
							<option value=""><?php esc_html_e( 'No change', 'sas-menu-maker' ); ?></option>
							<option value="replace"><?php esc_html_e( 'Set to…', 'sas-menu-maker' ); ?></option>
							<option value="increase"><?php esc_html_e( 'Increase by…', 'sas-menu-maker' ); ?></option>
							<option value="decrease"><?php esc_html_e( 'Decrease by…', 'sas-menu-maker' ); ?></option>
						</select>
						<input type="number"
							name="base_price_value"
							step="0.01"
							min="0"
							data-menucraft-price
							placeholder="<?php esc_attr_e( 'Amount', 'sas-menu-maker' ); ?>">
						<p class="menucraft-field-help">
							<?php esc_html_e( 'Items without a base price are skipped when increasing or decreasing.', 'sas-menu-maker' ); ?>
						</p>
					</div>

					<div class="menucraft-bulk-op">
						<label class="menucraft-bulk-op-label" for="menucraft-bulk-variant-prices-mode">
							<?php esc_html_e( 'Variant Prices', 'sas-menu-maker' ); ?>
						</label>
						<select class="menucraft-bulk-mode" id="menucraft-bulk-variant-prices-mode" name="variant_prices_mode">
							<option value=""><?php esc_html_e( 'No change', 'sas-menu-maker' ); ?></option>
							<option value="increase"><?php esc_html_e( 'Increase by…', 'sas-menu-maker' ); ?></option>
							<option value="decrease"><?php esc_html_e( 'Decrease by…', 'sas-menu-maker' ); ?></option>
						</select>
						<input type="number"
							name="variant_prices_value"
							step="0.01"
							min="0"
							data-menucraft-price
							placeholder="<?php esc_attr_e( 'Amount', 'sas-menu-maker' ); ?>">
						<p class="menucraft-field-help">
							<?php esc_html_e( 'Applies to every variant of every selected item. Prices are floored at 0.', 'sas-menu-maker' ); ?>
						</p>
					</div>

					<div class="menucraft-bulk-op">
						<label class="menucraft-bulk-op-label" for="menucraft-bulk-is-active-mode">
							<?php esc_html_e( 'Active State', 'sas-menu-maker' ); ?>
						</label>
						<select class="menucraft-bulk-mode" id="menucraft-bulk-is-active-mode" name="is_active_mode">
							<option value=""><?php esc_html_e( 'No change', 'sas-menu-maker' ); ?></option>
							<option value="1"><?php esc_html_e( 'Activate', 'sas-menu-maker' ); ?></option>
							<option value="0"><?php esc_html_e( 'Deactivate', 'sas-menu-maker' ); ?></option>
						</select>
					</div>
				</div>

				<footer class="menucraft-offcanvas-footer">
					<button type="button" class="button" data-menucraft-panel-close>
						<?php esc_html_e( 'Cancel', 'sas-menu-maker' ); ?>
					</button>
					<button type="submit" class="button button-primary" data-menucraft-submit>
						<?php esc_html_e( 'Apply', 'sas-menu-maker' ); ?>
					</button>
				</footer>
			</form>
		</div>
	</aside>

	<?php // -------- Confirm delete modal -------- ?>
	<div class="menucraft-modal" id="menucraft-modal-delete-item" aria-hidden="true" role="dialog" aria-modal="true">
		<div class="menucraft-modal-backdrop" data-menucraft-modal-close></div>
		<div class="menucraft-modal-dialog" aria-labelledby="menucraft-modal-delete-item-title">
			<header class="menucraft-modal-header">
				<h2 class="menucraft-modal-title" id="menucraft-modal-delete-item-title">
					<?php esc_html_e( 'Delete item?', 'sas-menu-maker' ); ?>
				</h2>
			</header>
			<div class="menucraft-modal-body">
				<p>
					<?php
					printf(
						/* translators: %s: item name placeholder replaced by JS. */
						esc_html__( 'Are you sure you want to delete %s? This also removes its variants and assignments. This cannot be undone.', 'sas-menu-maker' ),
						'<strong data-menucraft-modal-target-name>—</strong>'
					);
					?>
				</p>
			</div>
			<footer class="menucraft-modal-footer">
				<button type="button" class="button" data-menucraft-modal-close>
					<?php esc_html_e( 'Cancel', 'sas-menu-maker' ); ?>
				</button>
				<button type="button"
					class="button menucraft-btn-danger"
					data-menucraft-modal-confirm-delete>
					<?php esc_html_e( 'Delete', 'sas-menu-maker' ); ?>
				</button>
			</footer>
		</div>
	</div>
</div>
