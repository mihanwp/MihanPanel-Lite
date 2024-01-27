<div class="wrap">
    <h2><?php _e('Blocked IPs List', 'mihanpanel') ?></h2>
    <div class="tablenav top">
        <div class="alignleft actions bulkactions">
            <a href="<?php echo esc_url(add_query_arg(['action' => 'delete_all', 'mwpl_nonce' => wp_create_nonce('mwpl_delete_blocked_ip_all_items')]))?>" class="button action"><?php _e('Delete all', 'mihanpanel')?></a>
        </div>
        
        <div class="tablenav-pages one-page">
            <span class="displaying-num"><?php echo $allItemsCount; ?></span>
        </div>
        <br class="clear">
    </div>
    <table class="wp-list-table widefat fixed striped table-view-list posts">
        <thead>
            <tr>
                <th id="ip"><?php _e('IP', 'mihanpanel') ?></th>
                <th id="failed_attempts_count"><?php _e('Failed attempts count', 'mihanpanel') ?></th>
                <th id="status"><?php _e('Status', 'mihanpanel') ?></th>
                <th id="last_attempt_date"><?php _e('Last attempt date', 'mihanpanel') ?></th>
            </tr>
        </thead>
        <tbody id="the-list">
            <?php if ($blockedIpsList) : ?>
                <?php foreach ($blockedIpsList as $item) : ?>
                    <tr id="<?php esc_attr(printf('item_%d', $item->id)) ?>">
                        <td class="title column-title">
                            <strong>
                                <span class="row-title"><?php echo esc_html($item->ip_address) ?></span>
                            </strong>

                            <div class="row-actions">
                                <span class="trash">
                                    <a href="<?php echo esc_url(add_query_arg(['action' => 'delete', 'id' => $item->id, 'mwpl_nonce' => wp_create_nonce('mwpl_delete_blocked_ip_item')])) ?>"><?php _e('Delete', 'mihanpanel') ?></a>
                                </span>
                            </div>
                        </td>
                        <td class="failed_attempts_count column-failed_attempts_count"><?php echo $item->failed_attempts_count ?></td>
                        <td class="status column-status">
                            <?php if ($item->failed_attempts_count < $failedAttemptsTelorance) : ?>
                                <span><?php _e('Not blocked', 'mihanpanel') ?></span>
                            <?php else : ?>
                                <span><?php _e('Blocked', 'mihanpanel') ?></span>
                            <?php endif; ?>
                        </td>

                        <td class="date column-date">
                            <span><?php echo $item->updated_at; ?></span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="4"><?php _e('No any ip records found', 'mihanpanel') ?></td>
                </tr>
            <?php endif; ?>

        </tbody>
    </table>
</div>