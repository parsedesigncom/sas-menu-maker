<?php
/**
 * Allergens admin screen.
 *
 * Same UX pattern as Categories/Tags but with a leaner form (code, name,
 * description, sort_order, is_active). No image, no color, no parent.
 *
 * @package MenuCraft
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="wrap menucraft-wrap">
	<div class="menucraft-card">
		<header class="menucraft-page-header">
			<div class="menucraft-page-header-row">
				<h1 class="menucraft-page-title"><?php echo esc_html( get_admin_page_title() ); ?></h1>
				<button type="button"
					class="button button-primary menucraft-btn-add"
					data-menucraft-panel-open="menucraft-panel-allergen-form"
					data-menucraft-panel-mode="create">
					<span class="dashicons dashicons-plus-alt2" aria-hidden="true"></span>
					<?php esc_html_e( 'New Allergen', 'sas-menu-maker' ); ?>
				</button>
			</div>
			<p class="menucraft-page-description">
				<?php esc_html_e( 'Manage allergens (e.g. EU codes A, B, C or free-form labels like gluten, nuts). Items reference them; the menu legend lists them at the bottom.', 'sas-menu-maker' ); ?>
			</p>
			<hr class="menucraft-page-sep">
		</header>

		<div class="menucraft-page-body">
			<table class="wp-list-table widefat striped fixed menucraft-table menucraft-allergens-table"
				data-menucraft-list="allergens"
				data-menucraft-panel="menucraft-panel-allergen-form"
				data-menucraft-modal-delete="menucraft-modal-delete-allergen">
				<thead>
					<tr>
						<th scope="col" class="menucraft-col-code"><?php esc_html_e( 'Code', 'sas-menu-maker' ); ?></th>
						<th scope="col" class="menucraft-col-name"><?php esc_html_e( 'Name', 'sas-menu-maker' ); ?></th>
						<th scope="col" class="menucraft-col-desc"><?php esc_html_e( 'Description', 'sas-menu-maker' ); ?></th>
						<th scope="col" class="menucraft-col-active"><?php esc_html_e( 'Active', 'sas-menu-maker' ); ?></th>
						<th scope="col" class="menucraft-col-dates"><?php esc_html_e( 'Dates', 'sas-menu-maker' ); ?></th>
						<th scope="col" class="menucraft-col-actions"><?php esc_html_e( 'Actions', 'sas-menu-maker' ); ?></th>
					</tr>
				</thead>
				<tbody data-menucraft-list-body>
					<tr class="menucraft-row-status">
						<td colspan="6"><?php esc_html_e( 'Loading…', 'sas-menu-maker' ); ?></td>
					</tr>
				</tbody>
			</table>
		</div>

		<footer class="menucraft-page-footer">
			<hr class="menucraft-page-sep">
		</footer>
	</div>

	<?php // -------- Off-canvas panel: create/edit -------- ?>
	<aside class="menucraft-offcanvas" id="menucraft-panel-allergen-form" aria-hidden="true">
		<div class="menucraft-offcanvas-backdrop" data-menucraft-panel-close></div>
		<div class="menucraft-offcanvas-panel"
			role="dialog"
			aria-modal="true"
			aria-labelledby="menucraft-panel-allergen-form-title">
			<form class="menucraft-form"
				data-menucraft-endpoint="allergens"
				data-menucraft-mode="create">
				<header class="menucraft-offcanvas-header">
					<h2 class="menucraft-offcanvas-title"
						id="menucraft-panel-allergen-form-title"
						data-menucraft-title-create="<?php esc_attr_e( 'New Allergen', 'sas-menu-maker' ); ?>"
						data-menucraft-title-edit="<?php esc_attr_e( 'Edit Allergen', 'sas-menu-maker' ); ?>">
						<?php esc_html_e( 'New Allergen', 'sas-menu-maker' ); ?>
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
						<label for="menucraft-alg-code">
							<?php esc_html_e( 'Code', 'sas-menu-maker' ); ?>
							<span class="menucraft-required" aria-hidden="true">*</span>
						</label>
						<input type="text"
							id="menucraft-alg-code"
							name="code"
							required
							maxlength="20"
							placeholder="<?php esc_attr_e( 'e.g. A, B, gluten', 'sas-menu-maker' ); ?>">
					</div>

					<div class="menucraft-field">
						<label for="menucraft-alg-name">
							<?php esc_html_e( 'Name', 'sas-menu-maker' ); ?>
							<span class="menucraft-required" aria-hidden="true">*</span>
						</label>
						<input type="text" id="menucraft-alg-name" name="name" required>
					</div>

					<div class="menucraft-field">
						<label for="menucraft-alg-description"><?php esc_html_e( 'Description', 'sas-menu-maker' ); ?></label>
						<textarea id="menucraft-alg-description" name="description" rows="4"></textarea>
					</div>

					<div class="menucraft-field">
						<label for="menucraft-alg-sort"><?php esc_html_e( 'Sort Order', 'sas-menu-maker' ); ?></label>
						<input type="number" id="menucraft-alg-sort" name="sort_order" value="0" step="1" min="0">
					</div>

					<div class="menucraft-field menucraft-field-checkbox">
						<label for="menucraft-alg-active">
							<input type="checkbox" id="menucraft-alg-active" name="is_active" value="1" checked>
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
						data-menucraft-label-create="<?php esc_attr_e( 'Save Allergen', 'sas-menu-maker' ); ?>"
						data-menucraft-label-edit="<?php esc_attr_e( 'Update Allergen', 'sas-menu-maker' ); ?>">
						<?php esc_html_e( 'Save Allergen', 'sas-menu-maker' ); ?>
					</button>
				</footer>
			</form>
		</div>
	</aside>

	<?php // -------- Confirm delete modal -------- ?>
	<div class="menucraft-modal" id="menucraft-modal-delete-allergen" aria-hidden="true" role="dialog" aria-modal="true">
		<div class="menucraft-modal-backdrop" data-menucraft-modal-close></div>
		<div class="menucraft-modal-dialog" aria-labelledby="menucraft-modal-delete-allergen-title">
			<header class="menucraft-modal-header">
				<h2 class="menucraft-modal-title" id="menucraft-modal-delete-allergen-title">
					<?php esc_html_e( 'Delete allergen?', 'sas-menu-maker' ); ?>
				</h2>
			</header>
			<div class="menucraft-modal-body">
				<p>
					<?php
					printf(
						/* translators: %s: allergen name placeholder replaced by JS. */
						esc_html__( 'Are you sure you want to delete %s? This cannot be undone.', 'sas-menu-maker' ),
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
