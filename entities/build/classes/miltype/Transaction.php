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

  /* TRANSACTION REASONS NUMBERS
  ---------------------------------------------*/
  const REASON_ADVERTISED_LVL1     = 0;
  const REASON_ADVERTISED_LVL2     = 1;
  const REASON_ADVERTISED_INDIRECT = 2;
  const REASON_VL_BONUS = 3;
  const REASON_OL_BONUS = 4;
  const REASON_PM_BONUS = 5;

  const REASON_IT_BONUS = 6;
  const REASON_CEO1_BONUS = 7;
  const REASON_CEO2_BONUS = 8;
  const REASON_LAWYER_BONUS = 9;

  const REASON_CUSTOM_BONUS = 1001;
  const REASON_REMAINING_MEMBER_FEE = 1002;
  const REASON_TRANSFER_TO_ROOT = 1003;

  static public $MEMBER_FEE;
  static public $BASE_CURRENCY;
  static public $REASON_TO_AMOUNT = [];
  static public $BONUS_REASONS = [
    self::REASON_VL_BONUS,
    self::REASON_OL_BONUS,
    self::REASON_PM_BONUS,
  ];

  static public function getAmountForReason($reason) {
    return self::$REASON_TO_AMOUNT[$reason];
  }

  static public function initAmounts(array $amountsByReasons, $memberFee, $baseCurrency) {
    self::$REASON_TO_AMOUNT = $amountsByReasons;
    self::$MEMBER_FEE = $memberFee;
    self::$BASE_CURRENCY = $baseCurrency;
  }

  static public function isBonusReason($reason) {
    return isset(self::$BONUS_REASONS[$reason]);
  }

  static public $BONUS_TRANSACTION_FORM_FIELDS = [
    'recipient_id'  => [\Tbmt\TYPE_INT, ''],
    'recipient_num' => [\Tbmt\TYPE_INT, ''],
    'amount'        => \Tbmt\TYPE_INT,
    'purpose'       => \Tbmt\TYPE_STRING,
  ];

  static public $BONUS_TRANSACTION_FORM_FILTERS = [
    'recipient_num'  => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'amount' => [
      'filter' => \FILTER_VALIDATE_INT,
      'options' => [
        'min_range' => 1
      ],
      'errorLabel' => 'error.greater_zero'
    ],
    'purpose' => \Tbmt\Validator::FILTER_NOT_EMPTY,
  ];

  static public function initBonusTransactionForm(array $data = array()) {
    return \Tbmt\Arr::initMulti($data, self::$BONUS_TRANSACTION_FORM_FIELDS);
  }

  static public function validateBonusTransactionForm(array $data = array()) {
    $data = self::initBonusTransactionForm($data);

    $res = \Tbmt\Validator::getErrors($data, self::$BONUS_TRANSACTION_FORM_FILTERS);
    if ( $res !== false )
      return [false, $res, null];

    $recipient = \MemberQuery::create()
      ->filterByDeletionDate(null, Criteria::ISNULL)
      ->findOneByNum($data['recipient_num']);
    if ( $recipient == null ) {
      return [false, ['recipient_num' => \Tbmt\Localizer::get('error.member_num')], null];
    }

    if ( !$recipient->hadPaid() )
      return [false, ['recipient_num' => \Tbmt\Localizer::get('error.member_num_unpaid')], null];

    return [true, $data, $recipient];
  }

  static public function createBonusTransaction(Member $login, Member $recipient, array $data, PropelPDO $con) {
    $currentTransfer = $recipient->getCurrentTransferBundle(self::$BASE_CURRENCY, $con);
    $when = time();

    if ( !$con->beginTransaction() )
      throw new Exception('Could not begin transaction');

    try {
      $amount = $data['amount'];
      $recipient->addOutstandingTotal($amount, self::$BASE_CURRENCY);
      $transaction = $currentTransfer->addAmount($amount)
        ->setReason(Transaction::REASON_CUSTOM_BONUS)
        ->setPurpose($data['purpose'])
        ->setRelatedId($login->getId())
        ->setDate($when)
        ->save($con);

      $currentTransfer->save($con);
      $recipient->save($con);

      if ( !$con->commit() )
        throw new Exception('Could not commit transaction');

    } catch (Exception $e) {
        $con->rollBack();
        throw $e;
    }
  }
}
