<?php

/**
 * @file
 * API documentation for commerce_pos.
 */


/**
 * Allows modules to define custom POS Transaction Base classes.
 *
 * The base classes will be attached to each POS transaction object so that
 * the base classes' methods can be invoked via the transaction object's
 * doAction method.
 *
 * Modules that define their own base class should extend the
 * CommercePosTransactionBase class.
 *
 * Modules implementing this hook should return an associative array of arrays,
 * keyed by a unique machine name for the Base class.
 *
 * Each Base class array can contain the following key/value pairs:
 *
 * - class: The PHP class name of the Base class.
 * - types: (optional) An array of each type of transaction that the base class
 *   will be attached to.
 */
function hook_commerce_pos_transaction_base_info() {
  return array(
    'commerce_pos_transaction_base_actions' => array(
      'class' => 'CommercePosTransactionBaseActions',
      'types' => array(
        CommercePosService::TRANSACTION_TYPE_SALE,
        CommercePosService::TRANSACTION_TYPE_RETURN,
      ),
    ),
  );
}

/**
 * Allows modules to act upon the main POS sales form AJAX submission.
 *
 * While this is technically possible already through the use of hook_form_alter,
 * this hook allows other modules to set $form_state['transaction_updated'] to
 * TRUE to force the form to reload the transaction and recalculate order
 * totals.
 *
 * @param array $form_state
 *   The Drupal form API form state variable.
 * @param array $triggering_element
 *   The element that triggered the AJAX submission. Available directly in the
 *   $form_state variable, but provided for ease-of-use.
 */
function hook_commerce_pos_sale_form_ajax_alter(&$form_state, $triggering_element) {

}

/**
 * Allows modules to add to the list of AJAX commands being returned when a
 * POS transaction has been finalized via the "Pay" form.
 *
 * Modules should return an array of AJAX commands, to be merged in with the
 * other commands.
 */
function hook_commerce_pos_pay_finish_commands(CommercePosTransaction $transaction) {
  $commands = array();
  $commands[] = ajax_command_alert(t('This is an example.'));
  return $commands;
}

/**
 * Allows modules to specify the default state for a POS transaction's order.
 *
 * The state is used in price calculation rules to determine applicable taxes.
 *
 * The administrative_area of the order's billing information will be set to
 * whatever $administrative_area is set to.
 *
 * @param $administrative_area
 *   The administrative_area to use on the transaction order.
 * @param CommercePosTransaction $transaction
 *   The POS transaction object containing the order.
 */
function hook_commerce_pos_transaction_state_alter(&$administrative_area, CommercePosTransaction $transaction) {
  if (empty($administrative_area)) {
    $administrative_area = 90;
  }
}
