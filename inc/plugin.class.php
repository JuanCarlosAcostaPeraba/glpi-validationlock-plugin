<?php
/**
 * Plugin class for validationlock.
 */

class PluginValidationlockPlugin extends CommonDBTM {
   /**
    * Description of the plugin.
    * 
    * @return string
    */
   static function getTypeName($nb = 0) {
      return __('Validation Lock', 'validationlock');
   }
}
