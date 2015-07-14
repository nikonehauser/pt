<?php



/**
 * Skeleton subclass for representing a row from the 'tbmt_transfer' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.miltype
 */
class Transfer extends BaseTransfer {
  /**
   * This transfer is open in the way that we collect all transactions
   * in this transfer. Once the transfer is or get executed this transfer
   * is locked an can not be changed again. In this case you have to create
   * a new Transfer object with state STATE_COLLECT.
   */
  const STATE_COLLECT = 0;

  const STATE_RESERVED = 1;

  const STATE_IN_EXECUTION = 2;
  const STATE_DONE = 3;

  /**
   * Adds the given amount to this transfer.
   * @param [type] $intAmount
   */
  public function addAmount($intAmount) {
    $this->setAmount($this->getAmount() + $intAmount);
    $transaction = new Transaction();
    $transaction->setTransfer($this);
    $transaction->setAmount($intAmount);
    return $transaction;
  }
}