<?php

namespace WPDataTableStarterAddon;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use stdClass;
use WPDataTable;

/**
 * Class Plugin
 *
 * Main entry point of the wpDataTables Starter Addon
 *
 */
final class Plugin
{
    /**
     * Addon Version
     *
     * @since 1.0.0
     * @var string The addon version.
     */
    const VERSION = '1.0.0';

    /**
     * Minimum wpdatatables Version
     *
     * @since 1.0.0
     * @var string Minimum wpdatatables version required to run the addon.
     */

    const MINIMUM_WPDATATABLES_VERSION = '3.4.2.16';

    /**
     * Minimum PHP Version
     *
     * @since 1.0.0
     * @var string Minimum PHP version required to run the addon.
     */
    const MINIMUM_PHP_VERSION = '7.4';

    /**
     * Instance
     *
     * @since 1.0.0
     * @access private
     * @static
     * @var \WPDataTableStarterAddon\Plugin The single instance of the class.
     */
    private static ?Plugin $_instance = null;

    /**
     * Instance
     *
     * Ensures only one instance of the class is loaded or can be loaded.
     *
     * @return Plugin|null An instance of the class.
     * @since 1.0.0
     * @access public
     * @static
     */
    public static function instance(): ?Plugin
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor
     *
     * Perform some compatibility checks to make sure basic requirements are meet.
     * If all compatibility checks pass, initialize the functionality.
     *
     * @since 1.0.0
     * @access public
     */
    public function __construct()
    {
        if ($this->isCompatible()) {
            $this->registerActions();
            $this->loadAddonTextdomain();
            $this->initHooks();
        }
    }

    /**
     * Compatibility Checks
     *
     * Checks whether the site meets the addon requirement.
     *
     * @since 1.0.0
     * @access public
     */
    public function isCompatible(): bool
    {
        include_once ABSPATH . 'wp-admin/includes/plugin.php';

        // Check if wpDataTables is installed and activated
        if (!defined('WDT_ROOT_PATH')) {
            add_action('admin_notices', [$this, 'adminNoticeMissingMainPlugin']);
            deactivate_plugins(WPDATATABLES_STARTER_ADDON_BASENAME);
            return false;
        }

        // Check for required wpDataTables version
        if (!version_compare(WDT_CURRENT_VERSION, self::MINIMUM_WPDATATABLES_VERSION, '>=')) {
            add_action('admin_notices', [$this, 'adminNoticeMinimumWPDataTablesVersion']);
            deactivate_plugins(WPDATATABLES_STARTER_ADDON_BASENAME);
            return false;
        }

        // Check for required PHP version
        if (version_compare(PHP_VERSION, self::MINIMUM_PHP_VERSION, '<')) {
            add_action('admin_notices', [$this, 'adminNoticeMinimumPHPVersion']);
            deactivate_plugins(WPDATATABLES_STARTER_ADDON_BASENAME);
            return false;
        }

        return true;

    }

    /**
     * Set text domain for the addon, for string translations to work
     * @return void
     *
     * @since 1.0.0
     * @access public
     */
    public function loadAddonTextdomain()
    {
        load_plugin_textdomain(
            'wpdatatable-starter-addon',
            false,
            dirname(plugin_basename(__FILE__)) . '/languages'
        );
    }

    /**
     * Register actions for activation and deactivation of addon
     * @return void
     */
    public function registerActions()
    {
        register_activation_hook(__FILE__, [$this, 'activatePlugin']);
        register_deactivation_hook(__FILE__, [$this, 'deactivatePlugin']);
    }

    /**
     * Activate plugin
     *
     * @since 1.0.0
     * @access public
     */
    public function activatePlugin()
    {
        // Add custom code after plugin activation
    }

    /**
     * Deactivate plugin
     *
     * @since 1.0.0
     * @access public
     */
    public function deactivatePlugin()
    {
        // Add custom code after plugin deactivation
    }


    /**
     * Init all hooks necessary for Starter addon
     *
     * @since 1.0.0
     * @access public
     */
    public function initHooks()
    {
        // Add JS and CSS for tables edit page on backend
        add_action('wpdatatables_enqueue_on_edit_page', [$this, 'enqueueScriptsBackend']);

        // Add JS and CSS for tables on frontend
        add_action('wpdatatables_enqueue_on_frontend', [$this, 'enqueueScriptsFrontend']);

        // Add "Starter Addon Settings" tab on table configuration page
        add_action('wpdatatables_add_table_configuration_tab', [$this, 'addStarterAddonSettingsTab']);

        // Add tab panel for "Starter Addon Settings" tab on table configuration page
        add_action('wpdatatables_add_table_configuration_tabpanel', [$this, 'addStarterAddonSettingsTabPanel']);

        // Extend table config before saving table to DB
        add_filter('wpdatatables_filter_insert_table_array', [$this, 'extendTableConfig'], 10, 1);

        // Extend WPDataTable Object with new properties
        add_action('wpdatatables_extend_wpdatatable_object', [$this, 'extendTableObject'], 10, 2);

        // Extend table description before returning it to the front-end
        add_filter('wpdatatables_filter_table_description', [$this, 'extendJSONDescription'], 10, 3);

        // Add custom class to main table element
        add_filter('wpdatatables_add_class_to_table_html_element', [$this, 'addStarterClass'], 10, 2);

        // For more hooks checkout our developers documentation on  https://wpdatatables.com/developers/
    }


    /**
     * Admin notice
     *
     * Warning when the site doesn't have wpdatatable installed or activated.
     *
     * @since 1.0.0
     * @access public
     */
    public function adminNoticeMissingMainPlugin()
    {

        $message = sprintf(
            esc_html__('"%1$s" requires "%2$s" to be installed and activated.', 'wpdatatable-starter-addon'),
            '<strong>' . esc_html__('Starter Addon for wpDataTables', 'wpdatatable-starter-addon') . '</strong>',
            '<strong>' . esc_html__('wpDataTables', 'wpdatatable-starter-addon') . '</strong>'
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);

    }

    /**
     * Admin notice
     *
     * Warning when the site doesn't have a minimum required Elementor version.
     *
     * @since 1.0.0
     * @access public
     */
    public function adminNoticeMinimumWPDataTablesVersion()
    {

        $message = sprintf(
            esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'wpdatatable-starter-addon'),
            '<strong>' . esc_html__('Starter Addon for wpDataTables', 'wpdatatable-starter-addon') . '</strong>',
            '<strong>' . esc_html__('wpDataTables', 'wpdatatable-starter-addon') . '</strong>',
            self::MINIMUM_WPDATATABLES_VERSION
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);

    }

    /**
     * Admin notice
     *
     * Warning when the site doesn't have a minimum required PHP version.
     *
     * @since 1.0.0
     * @access public
     */
    public function adminNoticeMinimumPHPVersion()
    {

        $message = sprintf(
            esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'wpdatatable-starter-addon'),
            '<strong>' . esc_html__('Starter Addon for wpDataTables', 'wpdatatable-starter-addon') . '</strong>',
            '<strong>' . esc_html__('PHP', 'wpdatatable-starter-addon') . '</strong>',
            self::MINIMUM_PHP_VERSION
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);

    }

    /**
     * Enqueue files
     *
     * Enqueue Starter add-on files on back-end
     *
     * @since 1.0.0
     * @access public
     */
    public function enqueueScriptsBackend()
    {
        wp_enqueue_style(
            'wpdatatables-starter-addon-css',
            WPDATATABLES_STARTER_ADDON_ROOT_URL . 'assets/css/wpdatatables-starter-addon.css',
            array(),
            self::VERSION
        );
        wp_enqueue_script(
            'wpdatatables-starter-addon-backend',
            WPDATATABLES_STARTER_ADDON_ROOT_URL . 'assets/js/wpdatatables-starter-addon-backend.js',
            array(),
            self::VERSION,
            true
        );

        wp_enqueue_script(
            'wpdatatables-starter-addon-frontend',
            WPDATATABLES_STARTER_ADDON_ROOT_URL . 'assets/js/wpdatatables-starter-addon-frontend.js',
            array(),
            self::VERSION,
            true
        );
    }

    /**
     * Enqueue files
     *
     * Enqueue Starter add-on files on front-end
     *
     * @since 1.0.0
     * @access public
     */
    public function enqueueScriptsFrontend($wpDataTable)
    {
        if (isset($wpDataTable->starterTableOption) && $wpDataTable->starterTableOption) {
            wp_enqueue_script(
                'wpdatatables-starter-addon-frontend',
                WPDATATABLES_STARTER_ADDON_ROOT_URL . 'assets/js/wpdatatables-starter-addon-frontend.js',
                array(),
                self::VERSION,
                true
            );

            wp_enqueue_style(
                'wpdatatables-starter-addon-css',
                WPDATATABLES_STARTER_ADDON_ROOT_URL . 'assets/css/wpdatatables-starter-addon.css',
                array(),
                self::VERSION
            );
        }
    }

    /**
     *
     * Function that extend table config before saving table to the database
     *
     * @param $tableConfig - array that contains table configuration
     * @return mixed
     *
     * @since 1.0.0
     * @access public
     */
    public function extendTableConfig($tableConfig)
    {
        $table = json_decode(stripslashes_deep($_POST['table']));

        $advancedSettings = json_decode($tableConfig['advanced_settings']);

        if (isset($table->starterTableOption))
            $advancedSettings->starterTableOption = $table->starterTableOption;

        $tableConfig['advanced_settings'] = json_encode($advancedSettings);

        return $tableConfig;
    }

    /**
     *
     * Function that extend $wpDataTable object with new properties
     *
     * @param $wpDataTable WPDataTable
     * @param $tableData stdClass
     *
     * @since 1.0.0
     * @access public
     */
    public function extendTableObject(WPDataTable $wpDataTable, stdClass $tableData)
    {
        if (!empty($tableData->advanced_settings)) {
            $advancedSettings = json_decode($tableData->advanced_settings);

            if (isset($advancedSettings->starterTableOption)) {
                $wpDataTable->starterTableOption = $advancedSettings->starterTableOption;
            }
        }
    }

    /**
     *
     * Function that extend table description before returning it to the front-end
     *
     * @param $tableDescription stdClass
     * @param int $tableId
     * @param $wpDataTable WPDataTable
     * @return stdClass
     *
     * @since 1.0.0
     * @access public
     */
    public function extendJSONDescription(stdClass $tableDescription, int $tableId, WPDataTable $wpDataTable): stdClass
    {
        if (isset($wpDataTable->starterTableOption)) {
            $tableDescription->starterTableOption = $wpDataTable->starterTableOption;
        }

        return $tableDescription;
    }

    /**
     * Function that extend table CSS class
     *
     * @param $classes string
     * @param $tableId int
     * @return string
     *
     * @since 1.0.0
     * @access public
     */
    public function addStarterClass(string $classes, int $tableId): string
    {
        $classes .= ' wdtStarterTable ';

        return $classes;
    }

    /**
     * Add Starter Addon Settings tab on table configuration page
     *
     * @since 1.0.0
     * @access public
     */
    public function addStarterAddonSettingsTab()
    {
        ob_start();
        include WPDATATABLES_STARTER_ADDON_ROOT_PATH . 'templates/wpdatatables-starter-addon-settings-tab.inc.php';
        $starterSettingsTab = ob_get_contents();
        ob_end_clean();

        echo $starterSettingsTab;
    }

    /**
     * Add tab panel for Starter Addon Settings tab on table configuration page
     *
     * @since 1.0.0
     * @access public
     */
    public function addStarterAddonSettingsTabPanel()
    {
        ob_start();
        include WPDATATABLES_STARTER_ADDON_ROOT_PATH . 'templates/wpdatatables-starter-addon-settings-tabpanel.inc.php';
        $starterAddonSettingsTabPanel = ob_get_contents();
        ob_end_clean();

        echo $starterAddonSettingsTabPanel;
    }
}
