<?php
/**
 * Allergens admin screen.
 *
 * Same UX pattern as Categories/Tags but with a leaner form (code, name,
 * description, sort_order, is_active). No image, no color, no parent.
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
					data-sas-menu-maker-panel-open="sas-menu-maker-panel-allergen-form"
					data-sas-menu-maker-panel-mode="create">
					<span class="dashicons dashicons-plus-alt2" aria-hidden="true"></span>
					<?php esc_html_e( 'New Allergen', 'sas-menu-maker' ); ?>
				</button>
			</div>
			<p class="sas-menu-maker-page-description">
				<?php esc_html_e( 'Manage allergens (e.g. EU codes A, B, C or free-form labels like gluten, nuts). Items reference them; the menu legend lists them at the bottom.', 'sas-menu-maker' ); ?>
			</p>
			<hr class="sas-menu-maker-page-sep">
		</header>

		<div class="sas-menu-maker-page-body">
			<table class="wp-list-table widefat striped fixed sas-menu-maker-table sas-menu-maker-allergens-table"
				data-sas-menu-maker-list="allergens"
				data-sas-menu-maker-panel="sas-menu-maker-panel-allergen-form"
				data-sas-menu-maker-modal-delete="sas-menu-maker-modal-delete-allergen">
				<thead>
					<tr>
						<th scope="col" class="sas-menu-maker-col-code"><?php esc_html_e( 'Code', 'sas-menu-maker' ); ?></th>
						<th scope="col" class="sas-menu-maker-col-name"><?php esc_html_e( 'Name', 'sas-menu-maker' ); ?></th>
						<th scope="col" class="sas-menu-maker-col-desc"><?php esc_html_e( 'Description', 'sas-menu-maker' ); ?></th>
						<th scope="col" class="sas-menu-maker-col-active"><?php esc_html_e( 'Active', 'sas-menu-maker' ); ?></th>
						<th scope="col" class="sas-menu-maker-col-dates"><?php esc_html_e( 'Dates', 'sas-menu-maker' ); ?></th>
						<th scope="col" class="sas-menu-maker-col-actions"><?php esc_html_e( 'Actions', 'sas-menu-maker' ); ?></th>
					</tr>
				</thead>
				<tbody data-sas-menu-maker-list-body>
					<tr class="sas-menu-maker-row-status">
						<td colspan="6"><?php esc_html_e( 'Loading…', 'sas-menu-maker' ); ?></td>
					</tr>
				</tbody>
			</table>
		</div>

		<footer class="sas-menu-maker-page-footer">
			<hr class="sas-menu-maker-page-sep">
		</footer>
	</div>

	<?php // -------- Off-canvas panel: create/edit -------- ?>
	<aside class="sas-menu-maker-offcanvas" id="sas-menu-maker-panel-allergen-form" aria-hidden="true">
		<div class="sas-menu-maker-offcanvas-backdrop" data-sas-menu-maker-panel-close></div>
		<div class="sas-menu-maker-offcanvas-panel"
			role="dialog"
			aria-modal="true"
			aria-labelledby="sas-menu-maker-panel-allergen-form-title">
			<form class="sas-menu-maker-form"
				data-sas-menu-maker-endpoint="allergens"
				data-sas-menu-maker-mode="create">
				<header class="sas-menu-maker-offcanvas-header">
					<h2 class="sas-menu-maker-offcanvas-title"
						id="sas-menu-maker-panel-allergen-form-title"
						data-sas-menu-maker-title-create="<?php esc_attr_e( 'New Allergen', 'sas-menu-maker' ); ?>"
						data-sas-menu-maker-title-edit="<?php esc_attr_e( 'Edit Allergen', 'sas-menu-maker' ); ?>">
						<?php esc_html_e( 'New Allergen', 'sas-menu-maker' ); ?>
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
						<label for="sas-menu-maker-alg-code">
							<?php esc_html_e( 'Code', 'sas-menu-maker' ); ?>
							<span class="sas-menu-maker-required" aria-hidden="true">*</span>
						</label>
						<input type="text"
							id="sas-menu-maker-alg-code"
							name="code"
							required
							maxlength="20"
							placeholder="<?php esc_attr_e( 'e.g. A, B, gluten', 'sas-menu-maker' ); ?>">
					</div>

					<div class="sas-menu-maker-field">
						<label for="sas-menu-maker-alg-name">
							<?php esc_html_e( 'Name', 'sas-menu-maker' ); ?>
							<span class="sas-menu-maker-required" aria-hidden="true">*</span>
						</label>
						<input type="text" id="sas-menu-maker-alg-name" name="name" required>
					</div>

					<div class="sas-menu-maker-field">
						<label for="sas-menu-maker-alg-description"><?php esc_html_e( 'Description', 'sas-menu-maker' ); ?></label>
						<textarea id="sas-menu-maker-alg-description" name="description" rows="4"></textarea>
					</div>

					<div class="sas-menu-maker-field">
						<label for="sas-menu-maker-alg-sort"><?php esc_html_e( 'Sort Order', 'sas-menu-maker' ); ?></label>
						<input type="number" id="sas-menu-maker-alg-sort" name="sort_order" value="0" step="1" min="0">
					</div>

					<div class="sas-menu-maker-field sas-menu-maker-field-checkbox">
						<label for="sas-menu-maker-alg-active">
							<input type="checkbox" id="sas-menu-maker-alg-active" name="is_active" value="1" checked>
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
						data-sas-menu-maker-label-create="<?php esc_attr_e( 'Save Allergen', 'sas-menu-maker' ); ?>"
						data-sas-menu-maker-label-edit="<?php esc_attr_e( 'Update Allergen', 'sas-menu-maker' ); ?>">
						<?php esc_html_e( 'Save Allergen', 'sas-menu-maker' ); ?>
					</button>
				</footer>
			</form>
		</div>
	</aside>

	<?php // -------- Confirm delete modal -------- ?>
	<div class="sas-menu-maker-modal" id="sas-menu-maker-modal-delete-allergen" aria-hidden="true" role="dialog" aria-modal="true">
		<div class="sas-menu-maker-modal-backdrop" data-sas-menu-maker-modal-close></div>
		<div class="sas-menu-maker-modal-dialog" aria-labelledby="sas-menu-maker-modal-delete-allergen-title">
			<header class="sas-menu-maker-modal-header">
				<h2 class="sas-menu-maker-modal-title" id="sas-menu-maker-modal-delete-allergen-title">
					<?php esc_html_e( 'Delete allergen?', 'sas-menu-maker' ); ?>
				</h2>
			</header>
			<div class="sas-menu-maker-modal-body">
				<p>
					<?php
					printf(
						/* translators: %s: allergen name placeholder replaced by JS. */
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
