<?php
/**
 * Hook implementations for validationlock.
 */

/**
 * Intercept ticket updates to block resolution/closure if there are pending validations.
 *
 * @param Ticket $item
 */
function plugin_validationlock_pre_item_update(Ticket $item) {
   // We only care about tickets
   if (!($item instanceof Ticket)) {
      return;
   }

   // Business logic is handled by TicketValidator class
   $validator = new PluginValidationlockTicketValidator();
   
   if (!$validator->validateStatusChange($item)) {
      // Cancel the update
      $item->input = [];
      return false;
   }
}

/**
 * Intercept ITILSolution additions to block them if there are pending validations.
 *
 * @param ITILSolution $item
 */
function plugin_validationlock_pre_item_add(ITILSolution $item) {
   // Validate if the solution belongs to a Ticket and if it has pending validations
   if ($item->fields['itemtype'] !== 'Ticket') {
      return;
   }

   $validator = new PluginValidationlockTicketValidator();
   
   if (!$validator->validateSolutionAddition($item)) {
      // Cancel the addition
      $item->input = [];
      return false;
   }
}
