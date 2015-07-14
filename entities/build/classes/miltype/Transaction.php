<?php



/**
 * Skeleton subclass for representing a row from the 'tbmt_transaction' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.miltype
 */
class Transaction extends BaseTransaction {
  const REASON_ADVERTISED_LVL1     = 0;
  const REASON_ADVERTISED_LVL2     = 1;
  const REASON_ADVERTISED_INDIRECT = 2;

  const AMOUNT_ADVERTISED_LVL1     = 5;
  const AMOUNT_ADVERTISED_LVL2     = 20;
  const AMOUNT_ADVERTISED_INDIRECT = 15;
}