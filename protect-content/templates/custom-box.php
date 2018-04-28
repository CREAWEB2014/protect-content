<p>
    <input name="<?= $admin->get_post_field_name('protect'); ?>" id="<?= $admin->get_post_field_name('protect'); ?>" value="1" type="checkbox" <?= $protect == '1' ? 'checked' : ''; ?>>
    <label for="<?= $admin->get_post_field_name('protect'); ?>"><b>Protect this content</b></label>
</p>

<div id="<?= $admin->get_post_field_name('protect_ui'); ?>" style="display: none;">

    <p>
        <label for="<?= $admin->get_post_field_name('redirect_url'); ?>"><b>Redirect URL:</b></label>
        <input style="width: 99%; margin-bottom: 5px;" name="<?= $admin->get_post_field_name('redirect_url'); ?>" id="<?= $admin->get_post_field_name('redirect_url'); ?>" class="code" value="<?= $redirect_url; ?>" type="text">
        <span class="howto">Specify the URL where unauthorized users will be redirected when they try to access a protected content.</span>
    </p>

    <p>
        <label for="<?= $admin->get_post_field_name('users'); ?>"><b>Authorized Users:</b></label>

        <select style="width: 99%; margin-bottom: 5px;" name="<?= $admin->get_post_field_name('users'); ?>[]" id="<?= $admin->get_post_field_name('users'); ?>"  multiple="multiple">
            <?php if (!empty($users)) : ?>
                <?php $full_users = get_users(['include' => $users]); ?>
                <?php foreach($full_users as $user) : ?>
                    <option value="<?= $user->ID; ?>" selected="selected"><?= $user->first_name; ?> <?= $user->last_name; ?> (<?= $user->user_login; ?> / #<?= $user->ID; ?>)</option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>

        <span class="howto">The users authorized to access this content.</span>
    </p>

</div>

<script type='text/javascript'>

jQuery( document ).ready( function( $ ) {

    function update_ui() {

        if ($('#<?= $admin->get_post_field_name('protect'); ?>').is(":checked")) {
            $('#<?= $admin->get_post_field_name('protect_ui'); ?>').css('display', 'block');
        } else {
            $('#<?= $admin->get_post_field_name('protect_ui'); ?>').css('display', 'none');
        }

    }

    $('#<?= $admin->get_post_field_name('protect'); ?>').change(function() {
        update_ui();
    });

    $('#<?= $admin->get_post_field_name('users'); ?>').select2({
        ajax: {
            url: ajaxurl,
            dataType: 'json',
            data: function (params) {

                var exclude = [];
                $('#<?= $admin->get_post_field_name('users'); ?> option:selected').each(function() {
                    exclude.push($(this).val())
                });

                return {
                    search: params.term,
                    page: params.page || 1,
                    exclude: exclude,
                    action: 'ftpc_user_search'
                };

            }
        }
    });

    update_ui();

});

</script>
