<?php
/**
 * @link       https://vectoricons.net
 * @since      1.0.0
 *
 * @package    Technify
 * @subpackage Technify/admin/partials
 */

$options       = get_option( $this->plugin_name );
$css_aggregate = Tools::get( $options, 'css_aggregate' );
$css_minify    = Tools::get( $options, 'css_minify' );
$js_aggregate  = Tools::get( $options, 'js_aggregate' );
$js_minify     = Tools::get( $options, 'js_minify' );
$css_enabled   = Tools::get( $options, 'css_enabled', 0 );
$js_enabled    = Tools::get( $options, 'js_enabled', 0 );

?>
<div class="wrap">
    <div id="poststuff" class="clear">
        <div id="post-body" class="metabox-holder">
            <div id="post-body-content">
                <div class="meta-box-sortables ui-sortable">
                    <div class="postbox">
                        <h2 class="hndle ui-sortable-handle"><span><?php echo esc_html(get_admin_page_title()); ?></span></h2>
                        <div class="inside">
                            <div class="technify-ops wrapper">
                                <ul class="ops" data-environment="production">
                                    <li><a href="#compress-js" data-action='compress_js' class="compress-js technify-compressor button button-primary">Compress JS</a></li>
                                    <li><a href="#compress-css" data-action='compress_css' class="compress-css technify-compressor button button-primary">Compress CSS</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="meta-box-sortables ui-sortable">
                    <div class="postbox">
                        <h2 class="hndle ui-sortable-handle"><span><?php esc_attr_e('Purge Cache', $this->plugin_name); ?></span></h2>
                        <div class="inside">

                            <div class="technify-ops wrapper">
                                <form method="post" name="<?php echo $this->plugin_name; ?>" action="options.php">
                                    <input type="hidden" name="technify-nonce" id="technify-nonce" value="<?php echo wp_create_nonce( 'technify_settings' ); ?>" />
                                    <?php
                                        settings_fields( $this->plugin_name );
                                        do_settings_sections( $this->plugin_name );
                                    ?>
                                    <?php /* <input type="hidden" name="action" id="action_technify_settings" value="<?php echo $this->plugin_name; ?>_settings" /> */ ?>
                                    <section>
                                        <div class="form-row">
                                            <label class="form-label" for="<?php echo $this->plugin_name; ?>-css-enabled">
                                                <?php esc_attr_e('Optimize CSS', $this->plugin_name); ?>
                                            </label>
                                            <div class="form-option">
                                                <legend class="screen-reader-text"><span><?php _e('Enable CSS Optimization', $this->plugin_name); ?></span></legend>
                                                <input type="radio" name="<?php echo $this->plugin_name; ?>[css_enabled]" value="1" <?php if ($css_enabled ==  1) : ?>checked="checked"<?php endif; ?> />&nbsp;Yes
                                                <input type="radio" name="<?php echo $this->plugin_name; ?>[css_enabled]" value="0" <?php if ($css_enabled ==  0) : ?>checked="checked"<?php endif; ?> />&nbsp;No
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <h3><?php esc_attr_e('CSS Aggregate', $this->plugin_name); ?></h3>
                                            <div class="form-option">
                                                <textarea rows="10" cols="150" name="<?php echo $this->plugin_name; ?>[css_aggregate]"><?php echo $css_aggregate; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <h3><?php esc_attr_e('CSS Minify', $this->plugin_name); ?></h3>
                                            <div class="form-option">
                                                <textarea rows="10" cols="150" name="<?php echo $this->plugin_name; ?>[css_minify]"><?php echo $css_minify; ?></textarea>
                                            </div>
                                        </div>
                                    </section>
                            </div>
                        </div>
                    </div>
                    <div class="meta-box-sortables ui-sortable">
                        <div class="postbox">
                            <h2 class="hndle ui-sortable-handle"><span><?php esc_attr_e('Purge Cache', $this->plugin_name); ?></span></h2>
                            <div class="inside">
                                    <section>
                                        <div class="form-row">
                                            <label class="form-label" for="<?php echo $this->plugin_name; ?>-js-enabled">
                                                <?php esc_attr_e('Optimize JS', $this->plugin_name); ?>
                                            </label>
                                            <div class="form-option">
                                                <legend class="screen-reader-text"><span><?php _e('Enable JS Optimization', $this->plugin_name); ?></span></legend>
                                                <input type="radio" name="<?php echo $this->plugin_name; ?>[js_enabled]" value="1" <?php if ($js_enabled ==  1) : ?>checked="checked"<?php endif; ?> />&nbsp;Yes
                                                <input type="radio" name="<?php echo $this->plugin_name; ?>[js_enabled]" value="0" <?php if ($js_enabled ==  0) : ?>checked="checked"<?php endif; ?> />&nbsp;No
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <h3><?php esc_attr_e('JS Aggregate', $this->plugin_name); ?></h3>
                                            <div class="form-option">
                                                <textarea rows="10" cols="150" name="<?php echo $this->plugin_name; ?>[js_aggregate]"><?php echo $js_aggregate; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <h3><?php esc_attr_e('JS Minify', $this->plugin_name); ?></h3>
                                            <div class="form-option">
                                                <textarea rows="10" cols="150" name="<?php echo $this->plugin_name; ?>[js_minify]"><?php echo $js_minify; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-option">
                                                <?php submit_button('Save', 'primary','submit', TRUE); ?>
                                            </div>
                                        </div>
                                    </section>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>