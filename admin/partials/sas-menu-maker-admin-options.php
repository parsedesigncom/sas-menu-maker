<?php
/**
 * Options admin screen.
 *
 * Plugin-wide settings. Loaded and persisted via REST /options.
 * Currency change takes effect after page reload (asset localization
 * happens server-side).
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
				<?php esc_html_e( 'Plugin-wide settings. Changes take effect after saving; some fields (currency prefix) require a page reload to appear across the admin.', 'sas-menu-maker' ); ?>
			</p>
			<hr class="sas-menu-maker-page-sep">
		</header>

		<div class="sas-menu-maker-page-body">
			<form class="sas-menu-maker-form sas-menu-maker-options-form"
				data-sas-menu-maker-options-form>
				<div class="sas-menu-maker-field">
					<label for="sas-menu-maker-option-currency">
						<?php esc_html_e( 'Currency Symbol', 'sas-menu-maker' ); ?>
					</label>
					<input type="text"
						id="sas-menu-maker-option-currency"
						name="currency"
						maxlength="10"
						placeholder="€"
						class="sas-menu-maker-input-narrow">
					<p class="sas-menu-maker-field-help">
						<?php esc_html_e( 'Shown as a non-editable prefix on every price input across the admin (e.g. €, $, CHF).', 'sas-menu-maker' ); ?>
					</p>
				</div>

				<footer class="sas-menu-maker-form-footer">
					<button type="submit"
						class="button button-primary"
						data-sas-menu-maker-submit>
						<?php esc_html_e( 'Save Options', 'sas-menu-maker' ); ?>
					</button>
				</footer>
			</form>
		</div>

		<footer class="sas-menu-maker-page-footer">
			<hr class="sas-menu-maker-page-sep">
		</footer>
	</div>
</div>
