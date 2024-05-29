(function ($) {
    $(function () {

        /**
         * Extend wpdatatable_config object with new properties and methods
         */
        $.extend(wpdatatable_config, {
            starterTableOption: 0,
            setStarterTableOption: function (starterTableOption) {
                wpdatatable_config.starterTableOption = starterTableOption;
                $('#wpdatatables-starter-option-toggle').prop('checked', starterTableOption);
            }
        });

        /**
         * Load the table for editing
         */
        if (typeof wpdatatable_init_config !== 'undefined' && wpdatatable_init_config.advanced_settings !== '') {

            var advancedSettings = JSON.parse(wpdatatable_init_config.advanced_settings);

            if (advancedSettings !== null) {

                var starterTableOption = advancedSettings.starterTableOption;

                if (typeof starterTableOption !== 'undefined') {
                    wpdatatable_config.setStarterTableOption(starterTableOption);
                }

            }

        }

        /**
         * Toggle Starter Table Option
         */
        $('#wpdatatables-starter-option-toggle').change(function () {
            wpdatatable_config.setStarterTableOption($(this).is(':checked') ? 1 : 0);
        });

    });

})(jQuery);