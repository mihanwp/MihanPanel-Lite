<h3><?php esc_html_e('User Account Status', 'mihanpanel'); ?></h3>
<table class="form-table">
    <tr>
        <th><?php esc_html_e("Account status", 'mihanpanel'); ?></th>
        <td><?php echo $status_btn; ?></td>
    </tr>
    <tr>
        <th><?php esc_html_e("Change account status", 'mihanpanel'); ?></th>
        <td>
            <button type="button" class="button mw_do_action_btn" value="activate" name="mw_account_status"><?php esc_html_e('Activate', 'mihanpanel'); ?></button>
            <button type="button" class="button mw_do_action_btn" value="deactivate" name="mw_account_status"><?php esc_html_e('Deactivate', 'mihanpanel'); ?></button>
        </td>
    </tr>
</table>
