<?php defined('ABSPATH') or die('Access denied.'); ?>
<!-- Starter Addon settings -->
<div role="tabpanel" class="tab-pane" id="starter-addon-settings">
    <!-- .row -->
    <div class="row">
        <!-- Starter Addon checkbox-->
        <div class="col-sm-4 m-b-16 wpdatatables-starter-option-block">
            <h4 class="c-title-color m-b-4 m-t-0">
                <?php esc_html_e('Starter Addon option', 'wpdatatable-starter-addon'); ?>
                <i class="wpdt-icon-info-circle-thin" data-toggle="tooltip" data-placement="right"
                   title="<?php esc_attr_e('Enable this to turn the starter functionality on for this table.', 'wpdatatable-starter-addon'); ?>"></i>
            </h4>
            <div class="toggle-switch" data-ts-color="blue">
                <input id="wpdatatables-starter-option-toggle" type="checkbox" hidden="hidden">
                <label for="wpdatatables-starter-option-toggle"
                       class="ts-label"><?php esc_html_e('Enable starter functionality for table', 'wpdatatable-starter-addon'); ?></label>
            </div>
        </div>
        <!-- /Starter Addon checkbox-->
    </div>
    <!-- /.row -->
</div>
<!-- /Starter Addon settings -->