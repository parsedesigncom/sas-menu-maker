<?php
/**
 * Categories admin screen.
 *
 * Table skeleton is rendered here and populated by JS from the REST
 * endpoint. The off-canvas panel doubles as create and edit surface
 * (mode is toggled by JS via data-sas-menu-maker-mode). Delete uses a
 * separate centered modal for confirmation.
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
					data-sas-menu-maker-panel-open="sas-menu-maker-panel-category-form"
					data-sas-menu-maker-panel-mode-target="sas-menu-maker-panel-category-form"
					data-sas-menu-maker-panel-mode="create">
					<span class="dashicons dashicons-plus-alt2" aria-hidden="true"></span>
					<?php esc_html_e( 'New Category', 'sas-menu-maker' ); ?>
				</button>
			</div>
			<p class="sas-menu-maker-page-description">
				<?php esc_html_e( 'Group menu items into categories such as Coffee, Snacks or Desserts.', 'sas-menu-maker' ); ?>
			</p>
			<hr class="sas-menu-maker-page-sep">
		</header>

		<div class="sas-menu-maker-page-body">
			<table class="wp-list-table widefat striped fixed sas-menu-maker-table sas-menu-maker-categories-table"
				data-sas-menu-maker-list="categories"
				data-sas-menu-maker-panel="sas-menu-maker-panel-category-form"
				data-sas-menu-maker-modal-delete="sas-menu-maker-modal-delete-category">
				<thead>
					<tr>
						<th scope="col" class="sas-menu-maker-col-thumb"><?php esc_html_e( 'Image', 'sas-menu-maker' ); ?></th>
						<th scope="col" class="sas-menu-maker-col-name"><?php esc_html_e( 'Name', 'sas-menu-maker' ); ?></th>
						<th scope="col" class="sas-menu-maker-col-color"><?php esc_html_e( 'Color', 'sas-menu-maker' ); ?></th>
						<th scope="col" class="sas-menu-maker-col-desc"><?php esc_html_e( 'Description', 'sas-menu-maker' ); ?></th>
						<th scope="col" class="sas-menu-maker-col-active"><?php esc_html_e( 'Active', 'sas-menu-maker' ); ?></th>
						<th scope="col" class="sas-menu-maker-col-dates"><?php esc_html_e( 'Dates', 'sas-menu-maker' ); ?></th>
						<th scope="col" class="sas-menu-maker-col-actions"><?php esc_html_e( 'Actions', 'sas-menu-maker' ); ?></th>
					</tr>
				</thead>
				<tbody data-sas-menu-maker-list-body>
					<tr class="sas-menu-maker-row-status" data-sas-menu-maker-list-loading>
						<td colspan="7"><?php esc_html_e( 'Loading…', 'sas-menu-maker' ); ?></td>
					</tr>
				</tbody>
			</table>
		</div>

		<footer class="sas-menu-maker-page-footer">
			<hr class="sas-menu-maker-page-sep">
		</footer>
	</div>

	<?php // -------- Off-canvas panel: create/edit (mode set via JS) -------- ?>
	<aside class="sas-menu-maker-offcanvas" id="sas-menu-maker-panel-category-form" aria-hidden="true">
		<div class="sas-menu-maker-offcanvas-backdrop" data-sas-menu-maker-panel-close></div>
		<div class="sas-menu-maker-offcanvas-panel"
			role="dialog"
			aria-modal="true"
			aria-labelledby="sas-menu-maker-panel-category-form-title">
			<form class="sas-menu-maker-form"
				data-sas-menu-maker-endpoint="categories"
				data-sas-menu-maker-mode="create">
				<header class="sas-menu-maker-offcanvas-header">
					<h2 class="sas-menu-maker-offcanvas-title"
						id="sas-menu-maker-panel-category-form-title"
						data-sas-menu-maker-title-create="<?php esc_attr_e( 'New Category', 'sas-menu-maker' ); ?>"
						data-sas-menu-maker-title-edit="<?php esc_attr_e( 'Edit Category', 'sas-menu-maker' ); ?>">
						<?php esc_html_e( 'New Category', 'sas-menu-maker' ); ?>
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
						<label for="sas-menu-maker-cat-name">
							<?php esc_html_e( 'Name', 'sas-menu-maker' ); ?>
							<span class="sas-menu-maker-required" aria-hidden="true">*</span>
						</label>
						<input type="text" id="sas-menu-maker-cat-name" name="name" required>
					</div>

					<div class="sas-menu-maker-field">
						<label for="sas-menu-maker-cat-description"><?php esc_html_e( 'Description', 'sas-menu-maker' ); ?></label>
						<textarea id="sas-menu-maker-cat-description" name="description" rows="4"></textarea>
					</div>

					<div class="sas-menu-maker-field">
						<label for="sas-menu-maker-cat-color"><?php esc_html_e( 'Color', 'sas-menu-maker' ); ?></label>
						<input type="color" id="sas-menu-maker-cat-color" name="color" value="#3858e9">
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
						<label for="sas-menu-maker-cat-sort"><?php esc_html_e( 'Sort Order', 'sas-menu-maker' ); ?></label>
						<input type="number" id="sas-menu-maker-cat-sort" name="sort_order" value="0" step="1" min="0">
					</div>

					<div class="sas-menu-maker-field sas-menu-maker-field-checkbox">
						<label for="sas-menu-maker-cat-active">
							<input type="checkbox" id="sas-menu-maker-cat-active" name="is_active" value="1" checked>
							<?php esc_html_e( 'Active', 'sas-menu-maker' ); ?>
						</label>
					</div>

					<div class="sas-menu-maker-field sas-menu-maker-field-checkbox">
						<label for="sas-menu-maker-cat-default">
							<input type="checkbox" id="sas-menu-maker-cat-default" name="is_default" value="1">
							<?php esc_html_e( 'Set as default', 'sas-menu-maker' ); ?>
						</label>
						<p class="sas-menu-maker-field-help">
							<?php esc_html_e( 'When set, this category is pre-selected in the frontend filter so the menu opens focused on this section. Only one category can be default at a time.', 'sas-menu-maker' ); ?>
						</p>
					</div>
				</div>

				<footer class="sas-menu-maker-offcanvas-footer">
					<button type="button" class="button" data-sas-menu-maker-panel-close>
						<?php esc_html_e( 'Cancel', 'sas-menu-maker' ); ?>
					</button>
					<button type="submit"
						class="button button-primary"
						data-sas-menu-maker-submit
						data-sas-menu-maker-label-create="<?php esc_attr_e( 'Save Category', 'sas-menu-maker' ); ?>"
						data-sas-menu-maker-label-edit="<?php esc_attr_e( 'Update Category', 'sas-menu-maker' ); ?>">
						<?php esc_html_e( 'Save Category', 'sas-menu-maker' ); ?>
					</button>
				</footer>
			</form>
		</div>
	</aside>

	<?php // -------- Confirm delete modal -------- ?>
	<div class="sas-menu-maker-modal" id="sas-menu-maker-modal-delete-category" aria-hidden="true" role="dialog" aria-modal="true">
		<div class="sas-menu-maker-modal-backdrop" data-sas-menu-maker-modal-close></div>
		<div class="sas-menu-maker-modal-dialog" aria-labelledby="sas-menu-maker-modal-delete-category-title">
			<header class="sas-menu-maker-modal-header">
				<h2 class="sas-menu-maker-modal-title" id="sas-menu-maker-modal-delete-category-title">
					<?php esc_html_e( 'Delete category?', 'sas-menu-maker' ); ?>
				</h2>
			</header>
			<div class="sas-menu-maker-modal-body">
				<p>
					<?php
					printf(
						/* translators: %s: category name placeholder replaced by JS. */
						esc_html__( 'Are you sure you want to delete %s? This cannot be undone.', 'sas-menu-maker' ),
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
