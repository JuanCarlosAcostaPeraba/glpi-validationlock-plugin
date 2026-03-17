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
      $id = (int)$ticket->fields['id'];

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
      $ticket_id = (int)$solution->fields['items_id'];

      if ($this->hasPendingValidation($ticket_id)) {
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

      $iterator = $DB->request([
         'FROM'  => 'glpi_ticketvalidations',
         'WHERE' => [
            'tickets_id' => $ticket_id,
            'status'     => 'WAITING'
         ],
         'LIMIT' => 1
      ]);

      return count($iterator) > 0;
   }
}
