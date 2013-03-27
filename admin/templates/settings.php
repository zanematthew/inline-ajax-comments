<div class="wrap">
    <div id="icon-options-general" class="icon32"><br></div>
    <h2>Inline AJax Comments Settings</h2>
    <form action="options.php" method="post" class="form newsletter-settings-form">
        <?php settings_fields('inline_comments_settings'); ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">Additional Styling</th>
                <td>
                    <textarea name="additional_styling" id="additional_styling" rows="10" cols="80" class="code"><?php print wp_kses( get_option('additional_styling'),'' ); ?></textarea>
                </td>
            </tr>
        </table>
        <p class="submit"><input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" /></p>
    </form>
</div>