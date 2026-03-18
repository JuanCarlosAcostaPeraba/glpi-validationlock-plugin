<?php
/**
 * TicketValidator class for validationlock.
 */

class PluginValidationlockTicketValidator extends CommonGLPI {

   /**
    * Validate status change of a ticket.
    *
    * @param Ticket $ticket
    * @return bool
    */
   public function validateStatusChange(Ticket $ticket) {
      // DEBUG: Log constants
      if (class_exists('CommonITILValidation')) {
         Toolbox::logInFile('validationlock', "DEBUG: WAITING constant: " . CommonITILValidation::WAITING . "\n");
         Toolbox::logInFile('validationlock', "DEBUG: ACCEPTED constant: " . CommonITILValidation::ACCEPTED . "\n");
         Toolbox::logInFile('validationlock', "DEBUG: REFUSED constant: " . CommonITILValidation::REFUSED . "\n");
      }

      // Check if the status is changing to SOLVED or CLOSED
      if (!isset($ticket->input['status'])) {
         return true;
      }

      $new_status = (int)$ticket->input['status'];
      $id = (int)($ticket->fields['id'] ?? $ticket->input['id'] ?? 0);

      Toolbox::logInFile('validationlock', "Checking status change to $new_status for Ticket ID: $id\n");

      if (in_array($new_status, [CommonITILObject::SOLVED, CommonITILObject::CLOSED])) {
         if ($this->hasPendingValidation($id)) {
            $msg = __('Cannot resolve or close ticket while there are pending validations.', 'validationlock');
            Session::addMessageAfterRedirect($msg, false, ERROR);
            return false;
         }
      }

      return true;
   }

   /**
    * Validate solution addition to a ticket.
    *
    * @param ITILSolution $solution
    * @return bool
    */
   public function validateSolutionAddition(ITILSolution $solution) {
      $ticket_id = (int)($solution->input['items_id'] ?? 0);

      Toolbox::logInFile('validationlock', "Checking solution addition for Ticket ID: $ticket_id\n");

      if ($ticket_id > 0 && $this->hasPendingValidation($ticket_id)) {
         $msg = __('Cannot add a solution while there are pending validations.', 'validationlock');
         Session::addMessageAfterRedirect($msg, false, ERROR);
         return false;
      }

      return true;
   }

   /**
    * Check if a ticket has pending validations.
    *
    * @param int $ticket_id
    * @return bool
    */
   public function hasPendingValidation($ticket_id) {
      global $DB;

      if ($ticket_id <= 0) {
         return false;
      }

      // Use constants if available, fallback to 2 (detected in user env) or 1 (standard GLPI)
      $waiting_status = 1;
      if (class_exists('CommonITILValidation')) {
         $waiting_status = CommonITILValidation::WAITING;
      }

      $iterator = $DB->request([
         'FROM'  => 'glpi_ticketvalidations',
         'WHERE' => [
            'tickets_id' => $ticket_id,
            'status'     => $waiting_status
         ],
         'LIMIT' => 1
      ]);

      $has_pending = count($iterator) > 0;
      
      // Fallback for some environments where strings might still be used
      if (!$has_pending) {
         $iterator = $DB->request([
            'FROM'  => 'glpi_ticketvalidations',
            'WHERE' => [
               'tickets_id' => $ticket_id,
               'status'     => 'waiting'
            ],
            'LIMIT' => 1
         ]);
         $has_pending = count($iterator) > 0;
      }

      Toolbox::logInFile('validationlock', "RESULT: Ticket $ticket_id has pending validations: " . ($has_pending ? 'YES' : 'NO') . " (Using WAITING status: $waiting_status)\n");

      return $has_pending;
   }
}
