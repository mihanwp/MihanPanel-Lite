<?php
global $user_id;
$user_id = isset($user_id) ? $user_id : 0;
?>
<h3><?php esc_html_e('User Account Status', 'mihanpanel'); ?></h3>
<table class="form-table">
    <tr>
        <th><?php esc_html_e("Account status", 'mihanpanel'); ?></th>
        <td><?php echo $status_btn; ?></td>
    </tr>
    <tr>
        <th><?php esc_html_e("Change account status", 'mihanpanel'); ?></th>
        <td>
			<?php if(!$is_active): ?>
            <button type="button" class="button mw_do_action_btn mwp-change-account-status" data-uid="<?php echo esc_attr($user_id); ?>" data-status="activate">
				<?php esc_html_e('Activate', 'mihanpanel'); ?>
			</button>
            <?php else: ?>
			<button type="button" class="button mw_do_action_btn mwp-change-account-status" data-uid="<?php echo esc_attr($user_id); ?>" data-status="deactivate">
				<?php esc_html_e('Deactivate', 'mihanpanel'); ?>
			</button>
			<?php endif; ?>
		</td>
    </tr>
</table>
