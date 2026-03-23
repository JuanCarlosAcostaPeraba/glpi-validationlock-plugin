<?php
/**
 * Plugin definition and registration.
 */

define('PLUGIN_VALIDATIONLOCK_VERSION', '1.0.2');

/**
 * Init function for the plugin.
 */
function plugin_init_validationlock() {
   global $PLUGIN_HOOKS;

   $PLUGIN_HOOKS['csrf_compliant']['validationlock'] = true;

   // Hooks for intercepting actions
   $PLUGIN_HOOKS['pre_item_update']['validationlock'] = [
      'Ticket' => 'plugin_validationlock_pre_item_update'
   ];

   $PLUGIN_HOOKS['pre_item_add']['validationlock'] = [
      'ITILSolution' => 'plugin_validationlock_pre_item_add'
   ];
}

/**
 * Plugin contribution information.
 */
function plugin_version_validationlock() {
   return [
      'name'           => __('Validation Lock', 'validationlock'),
      'version'        => '1.0.2',
      'author'         => 'Juan Carlos Acosta Perabá',
      'license'        => 'GPLv3+',
      'homepage'       => 'https://github.com/JuanCarlosAcostaPeraba/glpi-validationlock-plugin',
      'id'             => 'validationlock',
      'minGlpiVersion' => '11.0', // Compatible with GLPI 11.0
      'requirements'   => [
         'glpi' => [
            'min' => '11.0',
            'max' => '11.9',
         ]
      ]
   ];
}

/**
 * Check if the plugin can be activated.
 */
function plugin_validationlock_check_prerequisites() {
   return true;
}

/**
 * Check configuration for the plugin.
 */
function plugin_validationlock_check_config() {
   return true;
}

/**
 * Install the plugin.
 */
function plugin_validationlock_install() {
   // Attempt to compile locales automatically if msgfmt is available
   $locales_dir = dirname(__FILE__) . '/locales';
   $po_files = glob($locales_dir . '/*.po');
   if ($po_files) {
      foreach ($po_files as $po_file) {
         $mo_file = str_replace('.po', '.mo', $po_file);
         // Use @ to suppress warnings if passthru is disabled on the server
         @passthru("msgfmt -f -o " . escapeshellarg($mo_file) . " " . escapeshellarg($po_file));
      }
   }
   return true;
}

/**
 * Uninstall the plugin.
 */
function plugin_validationlock_uninstall() {
   return true;
}
