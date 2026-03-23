<?php
/**
 * Plugin class for validationlock.
 */

class PluginValidationlock extends CommonGLPI {

   /**
    * Get plugin name
    *
    * @return string
    */
   static function getTypeName($nb = 0) {
      return __('Validation Lock', 'validationlock');
   }

   /**
    * Configuration from the plugin list
    */
   function getIcon() {
      return 'assets/logo.png';
   }

   /**
    * Copyright details
    */
   static function getCopyright() {
      return 'Copyright (C) 2026 Juan Carlos Acosta Perabá';
   }
}
