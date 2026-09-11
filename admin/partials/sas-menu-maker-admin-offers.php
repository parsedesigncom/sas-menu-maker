<?php
/**
 * Offers admin screen.
 *
 * Table populated by JS from REST, main create/edit off-canvas with a
 * chips-plus-rows picker for line items (chip = add a row for that item,
 * row configures variant + quantity), and a delete confirmation modal.
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
				<button type="button"
					class="button button-primary sas-menu-maker-btn-add"
					data-sas-menu-maker-panel-open="sas-menu-maker-panel-offer-form"
					data-sas-menu-maker-panel-mode="create">
					<span class="dashicons dashicons-plus-alt2" aria-hidden="true"></span>
					<?php esc_html_e( 'New Offer', 'sas-menu-maker' ); ?>
				</button>
			</div>
			<p class="sas-menu-maker-page-description">
				<?php esc_html_e( 'Bundle single items or combos at a fixed price with an optional validity window and free-form conditions.', 'sas-menu-maker' ); ?>
			</p>
			<hr class="sas-menu-maker-page-sep">
		</header>

		<div class="sas-menu-maker-page-body">
			<div class="sas-menu-maker-filters sas-menu-maker-filters-collapsed" data-sas-menu-maker-filters="offers">
				<div class="sas-menu-maker-filters-header"
					data-sas-menu-maker-filters-toggle
					role="button"
					tabindex="0"
					aria-expanded="false"
					aria-controls="sas-menu-maker-filters-body-offers">
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
				<div class="sas-menu-maker-filters-body" id="sas-menu-maker-filters-body-offers">
					<div class="sas-menu-maker-filter-field sas-menu-maker-filter-search">
						<label for="sas-menu-maker-filter-offers-search">
							<?php esc_html_e( 'Search', 'sas-menu-maker' ); ?>
						</label>
						<input type="search"
							id="sas-menu-maker-filter-offers-search"
							data-sas-menu-maker-filter="search"
							placeholder="<?php esc_attr_e( 'Name, description or conditions…', 'sas-menu-maker' ); ?>">
					</div>

					<div class="sas-menu-maker-filter-row">
						<div class="sas-menu-maker-filter-field">
							<label for="sas-menu-maker-filter-offers-status">
								<?php esc_html_e( 'Status', 'sas-menu-maker' ); ?>
							</label>
							<select id="sas-menu-maker-filter-offers-status" data-sas-menu-maker-filter="status">
								<option value=""><?php esc_html_e( 'All', 'sas-menu-maker' ); ?></option>
								<option value="active"><?php esc_html_e( 'Active', 'sas-menu-maker' ); ?></option>
								<option value="inactive"><?php esc_html_e( 'Inactive', 'sas-menu-maker' ); ?></option>
							</select>
						</div>

						<div class="sas-menu-maker-filter-field">
							<label for="sas-menu-maker-filter-offers-validity">
								<?php esc_html_e( 'Validity', 'sas-menu-maker' ); ?>
							</label>
							<select id="sas-menu-maker-filter-offers-validity" data-sas-menu-maker-filter="validity">
								<option value=""><?php esc_html_e( 'All', 'sas-menu-maker' ); ?></option>
								<option value="current"><?php esc_html_e( 'Currently valid', 'sas-menu-maker' ); ?></option>
								<option value="upcoming"><?php esc_html_e( 'Upcoming', 'sas-menu-maker' ); ?></option>
								<option value="expired"><?php esc_html_e( 'Expired', 'sas-menu-maker' ); ?></option>
								<option value="always"><?php esc_html_e( 'No date limit', 'sas-menu-maker' ); ?></option>
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
							<label for="sas-menu-maker-filter-offers-image">
								<?php esc_html_e( 'Image', 'sas-menu-maker' ); ?>
							</label>
							<select id="sas-menu-maker-filter-offers-image" data-sas-menu-maker-filter="image">
								<option value=""><?php esc_html_e( 'All', 'sas-menu-maker' ); ?></option>
								<option value="with"><?php esc_html_e( 'With image', 'sas-menu-maker' ); ?></option>
								<option value="without"><?php esc_html_e( 'Without image', 'sas-menu-maker' ); ?></option>
							</select>
						</div>
					</div>
				</div>
			</div>

			<table class="wp-list-table widefat striped fixed sas-menu-maker-table sas-menu-maker-offers-table"
				data-sas-menu-maker-list="offers"
				data-sas-menu-maker-panel="sas-menu-maker-panel-offer-form"
				data-sas-menu-maker-modal-delete="sas-menu-maker-modal-delete-offer">
				<thead>
					<tr>
						<th scope="col" class="sas-menu-maker-col-thumb"><?php esc_html_e( 'Image', 'sas-menu-maker' ); ?></th>
						<th scope="col" class="sas-menu-maker-col-name"><?php esc_html_e( 'Name', 'sas-menu-maker' ); ?></th>
						<th scope="col" class="sas-menu-maker-col-price"><?php esc_html_e( 'Price', 'sas-menu-maker' ); ?></th>
						<th scope="col" class="sas-menu-maker-col-validity"><?php esc_html_e( 'Validity', 'sas-menu-maker' ); ?></th>
						<th scope="col" class="sas-menu-maker-col-items"><?php esc_html_e( 'Items', 'sas-menu-maker' ); ?></th>
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

	<?php // -------- Main off-canvas panel: create/edit offer -------- ?>
	<aside class="sas-menu-maker-offcanvas" id="sas-menu-maker-panel-offer-form" aria-hidden="true">
		<div class="sas-menu-maker-offcanvas-backdrop" data-sas-menu-maker-panel-close></div>
		<div class="sas-menu-maker-offcanvas-panel"
			role="dialog"
			aria-modal="true"
			aria-labelledby="sas-menu-maker-panel-offer-form-title">
			<form class="sas-menu-maker-form"
				data-sas-menu-maker-endpoint="offers"
				data-sas-menu-maker-mode="create">
				<header class="sas-menu-maker-offcanvas-header">
					<h2 class="sas-menu-maker-offcanvas-title"
						id="sas-menu-maker-panel-offer-form-title"
						data-sas-menu-maker-title-create="<?php esc_attr_e( 'New Offer', 'sas-menu-maker' ); ?>"
						data-sas-menu-maker-title-edit="<?php esc_attr_e( 'Edit Offer', 'sas-menu-maker' ); ?>">
						<?php esc_html_e( 'New Offer', 'sas-menu-maker' ); ?>
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
						<label for="sas-menu-maker-offer-name">
							<?php esc_html_e( 'Name', 'sas-menu-maker' ); ?>
							<span class="sas-menu-maker-required" aria-hidden="true">*</span>
						</label>
						<input type="text" id="sas-menu-maker-offer-name" name="name" required>
					</div>

					<div class="sas-menu-maker-field">
						<label for="sas-menu-maker-offer-description"><?php esc_html_e( 'Description', 'sas-menu-maker' ); ?></label>
						<textarea id="sas-menu-maker-offer-description" name="description" rows="3"></textarea>
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
						<label for="sas-menu-maker-offer-price">
							<?php esc_html_e( 'Total Price', 'sas-menu-maker' ); ?>
							<span class="sas-menu-maker-required" aria-hidden="true">*</span>
						</label>
						<input type="number"
							id="sas-menu-maker-offer-price"
							name="price"
							step="0.01"
							min="0"
							required
							data-sas-menu-maker-price
							placeholder="0.00">
						<p class="sas-menu-maker-field-help">
							<?php esc_html_e( 'One fixed price for the whole offer, regardless of the number of items.', 'sas-menu-maker' ); ?>
						</p>
					</div>

					<div class="sas-menu-maker-field-row">
						<div class="sas-menu-maker-field">
							<label for="sas-menu-maker-offer-valid-from"><?php esc_html_e( 'Valid From', 'sas-menu-maker' ); ?></label>
							<input type="datetime-local" id="sas-menu-maker-offer-valid-from" name="valid_from">
						</div>
						<div class="sas-menu-maker-field">
							<label for="sas-menu-maker-offer-valid-until"><?php esc_html_e( 'Valid Until', 'sas-menu-maker' ); ?></label>
							<input type="datetime-local" id="sas-menu-maker-offer-valid-until" name="valid_until">
						</div>
					</div>
					<p class="sas-menu-maker-field-help">
						<?php esc_html_e( 'Leave both empty for an offer with no time limit.', 'sas-menu-maker' ); ?>
					</p>

					<div class="sas-menu-maker-field">
						<label for="sas-menu-maker-offer-conditions"><?php esc_html_e( 'Conditions', 'sas-menu-maker' ); ?></label>
						<textarea id="sas-menu-maker-offer-conditions" name="conditions_text" rows="2"
							placeholder="<?php esc_attr_e( 'e.g. from 20€ order value, regulars only, in-house only…', 'sas-menu-maker' ); ?>"></textarea>
						<p class="sas-menu-maker-field-help">
							<?php esc_html_e( 'Free-form text — no order system yet, so conditions are informational only.', 'sas-menu-maker' ); ?>
						</p>
					</div>

					<div class="sas-menu-maker-field">
						<label><?php esc_html_e( 'Items in Offer', 'sas-menu-maker' ); ?></label>
						<div class="sas-menu-maker-offer-items-summary" data-sas-menu-maker-offer-items-summary>
							<span class="sas-menu-maker-offer-items-count" data-sas-menu-maker-offer-items-count>
								<?php esc_html_e( 'None', 'sas-menu-maker' ); ?>
							</span>
							<button type="button"
								class="button"
								data-sas-menu-maker-subpanel-open="sas-menu-maker-panel-offer-items"
								data-sas-menu-maker-subpanel-parent="sas-menu-maker-panel-offer-form">
								<?php esc_html_e( 'Manage Items', 'sas-menu-maker' ); ?>
							</button>
						</div>
						<p class="sas-menu-maker-field-help">
							<?php esc_html_e( 'Pick items and, where applicable, a specific variant. Combos = multiple lines; single = one line.', 'sas-menu-maker' ); ?>
						</p>
					</div>

					<div class="sas-menu-maker-field">
						<label for="sas-menu-maker-offer-sort"><?php esc_html_e( 'Sort Order', 'sas-menu-maker' ); ?></label>
						<input type="number" id="sas-menu-maker-offer-sort" name="sort_order" value="0" step="1" min="0">
					</div>

					<div class="sas-menu-maker-field sas-menu-maker-field-checkbox">
						<label for="sas-menu-maker-offer-active">
							<input type="checkbox" id="sas-menu-maker-offer-active" name="is_active" value="1" checked>
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
						data-sas-menu-maker-label-create="<?php esc_attr_e( 'Save Offer', 'sas-menu-maker' ); ?>"
						data-sas-menu-maker-label-edit="<?php esc_attr_e( 'Update Offer', 'sas-menu-maker' ); ?>">
						<?php esc_html_e( 'Save Offer', 'sas-menu-maker' ); ?>
					</button>
				</footer>
			</form>
		</div>
	</aside>

	<?php // -------- Sub-panel: offer items picker -------- ?>
	<aside class="sas-menu-maker-offcanvas sas-menu-maker-offcanvas-sub" id="sas-menu-maker-panel-offer-items" aria-hidden="true">
		<div class="sas-menu-maker-offcanvas-backdrop" data-sas-menu-maker-panel-close></div>
		<div class="sas-menu-maker-offcanvas-panel"
			role="dialog"
			aria-modal="true"
			aria-labelledby="sas-menu-maker-panel-offer-items-title">
			<header class="sas-menu-maker-offcanvas-header">
				<div class="sas-menu-maker-offcanvas-title-group">
					<span class="sas-menu-maker-offcanvas-breadcrumb">
						<?php esc_html_e( 'Offer', 'sas-menu-maker' ); ?>
						<span class="sas-menu-maker-breadcrumb-sep" aria-hidden="true">›</span>
					</span>
					<h2 class="sas-menu-maker-offcanvas-title" id="sas-menu-maker-panel-offer-items-title">
						<?php esc_html_e( 'Items', 'sas-menu-maker' ); ?>
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
					<?php esc_html_e( 'Click an item chip to add a line. An item can appear multiple times (e.g. the same item with two different variants). Items with variants require a variant per line.', 'sas-menu-maker' ); ?>
				</p>

				<div class="sas-menu-maker-field">
					<label><?php esc_html_e( 'Available Items', 'sas-menu-maker' ); ?></label>
					<div class="sas-menu-maker-chips sas-menu-maker-chips-picker"
						data-sas-menu-maker-offer-items-chips
						data-sas-menu-maker-chips-empty="<?php esc_attr_e( 'No items yet — create some first.', 'sas-menu-maker' ); ?>">
					</div>
				</div>

				<div class="sas-menu-maker-field">
					<label><?php esc_html_e( 'Selected Lines', 'sas-menu-maker' ); ?></label>
					<div class="sas-menu-maker-offer-items-list" data-sas-menu-maker-offer-items-list>
						<?php // Rendered by JS from itemFormState-equivalent for offers. ?>
					</div>
					<div class="sas-menu-maker-offer-items-empty" data-sas-menu-maker-offer-items-empty hidden>
						<?php esc_html_e( 'No lines yet — click an item chip above.', 'sas-menu-maker' ); ?>
					</div>
				</div>
			</div>

			<footer class="sas-menu-maker-offcanvas-footer">
				<button type="button" class="button button-primary" data-sas-menu-maker-panel-close>
					<?php esc_html_e( 'Done', 'sas-menu-maker' ); ?>
				</button>
			</footer>
		</div>
	</aside>

	<?php // -------- Confirm delete modal -------- ?>
	<div class="sas-menu-maker-modal" id="sas-menu-maker-modal-delete-offer" aria-hidden="true" role="dialog" aria-modal="true">
		<div class="sas-menu-maker-modal-backdrop" data-sas-menu-maker-modal-close></div>
		<div class="sas-menu-maker-modal-dialog" aria-labelledby="sas-menu-maker-modal-delete-offer-title">
			<header class="sas-menu-maker-modal-header">
				<h2 class="sas-menu-maker-modal-title" id="sas-menu-maker-modal-delete-offer-title">
					<?php esc_html_e( 'Delete offer?', 'sas-menu-maker' ); ?>
				</h2>
			</header>
			<div class="sas-menu-maker-modal-body">
				<p>
					<?php
					printf(
						/* translators: %s: offer name placeholder replaced by JS. */
						esc_html__( 'Are you sure you want to delete %s? This also removes its line items. This cannot be undone.', 'sas-menu-maker' ),
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
