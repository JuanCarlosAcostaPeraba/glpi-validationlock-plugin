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
      // Check if the status is changing to SOLVED or CLOSED
      if (!isset($ticket->input['status'])) {
         return true;
      }

      $new_status = (int)$ticket->input['status'];
      $id = (int)($ticket->fields['id'] ?? $ticket->input['id'] ?? 0);

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

      // Use constants if available, fallback to 1 (standard GLPI)
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

      return count($iterator) > 0;
   }
}
