<div class="wrap">

    <h1>Protect Content Configuration</h1>

    <?php if ($status == 'saved'): ?>
        <div id="message" class="updated notice is-dismissible"><p>Settings successfully saved.</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Hide this notification.</span></button></div>
    <?php endif; ?>

    <div style="margin-top: 20px; padding: 0em 2em 2em; border: 1px solid #e5e5e5; -webkit-box-shadow: 0 1px 1px rgba(0,0,0,.04); box-shadow: 0 1px 1px rgba(0,0,0,.04); background: #fff;">

        <form method="POST" action="<?= admin_url('admin.php'); ?>">
            <input type="hidden" name="action" value="ftpc_save_options" />

            <table class="form-table">
                <tr>
                    <th scope="row"><label for="default_redirect">Default Redirect URL</label></th>
                    <td>
                        <td>
                            <input name="default_redirect" type="text" value="<?= htmlentities($config['default_redirect'], ENT_QUOTES); ?>" class="regular-text" />
                            <p class="description">Specify the URL where unauthorized users will be redirected when they try to access a protected content. You will be able to override this on a <i>per page/ per post</i> basis.</p>
                        </td>
                    </td>
                </tr>

            </table>

            <input type="submit" value="Save!" class="button button-primary" />

        </form>

    </div>

</div>
