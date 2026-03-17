<?php
/**
 * Plugin definition and registration.
 */

define('PLUGIN_VALIDATIONLOCK_VERSION', '1.0.0');

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
      'name'           => 'Validation Lock',
      'version'        => PLUGIN_VALIDATIONLOCK_VERSION,
      'author'         => 'JuanCarlosAcostaPeraba',
      'license'        => 'GPLv3+',
      'homepage'       => 'https://github.com/JuanCarlosAcostaPeraba/glpi-validationlock-plugin',
      'requirements'   => [
         'glpi' => [
            'min' => '11.0'
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
