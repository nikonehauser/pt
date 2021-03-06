<?php

/**
 * @see file tests/Bonusstufen.doc
 */
class BonusSpreadingTest extends Tbmt_Tests_DatabaseTestCase {

  static public function setUpBeforeClass() {
    $con = Propel::getConnection();
    DbEntityHelper::truncateDatabase($con);
  }

  public function setUp() {
    parent::setUp();
    DbEntityHelper::resetBonusMembers();
  }

  /**
   * Vertriebsleiter => VL
   * Organisationsleiter => OL
   * Promoter => PM
   *
   */

  public function testVariant1() {
    /**
     * VL -  OL - PM - VS2 - VS1 – Neues Mitglied
     * 1$ -  1$ - 1$ - 15$ -  5$
     * ------------------------------------------*/

    /* Setup
    ---------------------------------------------*/
    DbEntityHelper::setCon(self::$propelCon);

    list(
        list($IT, $VL, $OL, $PM, $VS2, $VS1),
        list($trfIT, $trfVL, $trfOL, $trfPM, $trfVS2, $trfVS1),
    ) = DbEntityHelper::setUpBonusMembers();

    $new = DbEntityHelper::createSignupMember($VS1);

    $trfIT += Transaction::getAmountForReason(Transaction::REASON_IT_BONUS);
    $trfVL += Transaction::getAmountForReason(Transaction::REASON_VL_BONUS);
    $trfOL += Transaction::getAmountForReason(Transaction::REASON_OL_BONUS);
    $trfPM += Transaction::getAmountForReason(Transaction::REASON_PM_BONUS);
    $trfVS2 += Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_INDIRECT);
    $trfVS1 += Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_LVL1);

    $this->assertTransferTotal($trfIT, $IT);
    $this->assertTransferTotal($trfVL, $VL);
    $this->assertTransferTotal($trfOL, $OL);
    $this->assertTransferTotal($trfPM, $PM);
    $this->assertTransferTotal($trfVS2, $VS2);
    $this->assertTransferTotal($trfVS1, $VS1);

    // Setting up another tree should NOT change the bonuses for
    // the previous tree except of special types member like it specialist.
    list(
      list(, , , , , $newVS1),
      list($newTrfIT)
    ) = DbEntityHelper::setUpBonusMembers(false);

    DbEntityHelper::createSignupMember($newVS1);

    $this->assertTransferTotal($newTrfIT + Transaction::getAmountForReason(Transaction::REASON_IT_BONUS), $IT);
    $this->assertTransferTotal($trfVL, $VL);
    $this->assertTransferTotal($trfOL, $OL);
    $this->assertTransferTotal($trfPM, $PM);
    $this->assertTransferTotal($trfVS2, $VS2);
    $this->assertTransferTotal($trfVS1, $VS1);
  }

  public function testVariant2() {
    /**
     * VL -  OL - PM - VS2 – Neues Mitglied
     * 1$ -  1$ - 1$ - 20$
     * ------------------------------------------*/

    /* Setup
    ---------------------------------------------*/
    DbEntityHelper::setCon(self::$propelCon);

    list(
        list($IT, $VL, $OL, $PM, $VS2, $VS1),
        list($trfIT, $trfVL, $trfOL, $trfPM, $trfVS2, $trfVS1),
    ) = DbEntityHelper::setUpBonusMembers();

    $new = DbEntityHelper::createSignupMember($VS2);

    $trfIT += Transaction::getAmountForReason(Transaction::REASON_IT_BONUS);
    $trfVL += Transaction::getAmountForReason(Transaction::REASON_VL_BONUS);
    $trfOL += Transaction::getAmountForReason(Transaction::REASON_OL_BONUS);
    $trfPM += Transaction::getAmountForReason(Transaction::REASON_PM_BONUS);
    $trfVS2 += Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_LVL2);

    $this->assertTransferTotal($trfIT, $IT);
    $this->assertTransferTotal($trfVL, $VL);
    $this->assertTransferTotal($trfOL, $OL);
    $this->assertTransferTotal($trfPM, $PM);
    $this->assertTransferTotal($trfVS2, $VS2);
    $this->assertTransferTotal($trfVS1, $VS1);
  }

  public function testVariant3() {
    /**
     * VL -  PM - VS2 – VS1 - Neues Mitglied
     * 2$ -  1$ - 15$ -  5$
     * ------------------------------------------*/

    /* Setup
    ---------------------------------------------*/
    DbEntityHelper::setCon(self::$propelCon);

    list(
        list($IT, $VL, $OL, $PM, $VS2, $VS1),
        list($trfIT, $trfVL, $trfOL, $trfPM, $trfVS2, $trfVS1),
    ) = DbEntityHelper::setUpBonusMembers(true, [
        'OL' => false
    ]);

    $new = DbEntityHelper::createSignupMember($VS1);

    $trfIT += Transaction::getAmountForReason(Transaction::REASON_IT_BONUS);
    $trfVL += Transaction::getAmountForReason(Transaction::REASON_VL_BONUS) +
      Transaction::getAmountForReason(Transaction::REASON_OL_BONUS);
    // $trfOL += 1;
    $trfPM += Transaction::getAmountForReason(Transaction::REASON_PM_BONUS);
    $trfVS2 += Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_INDIRECT);
    $trfVS1 += Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_LVL1);

    $this->assertTransferTotal($trfIT, $IT);
    $this->assertTransferTotal($trfVL, $VL);
    // $this->assertTransferTotal($trfOL, $OL);
    $this->assertTransferTotal($trfPM, $PM);
    $this->assertTransferTotal($trfVS2, $VS2);
    $this->assertTransferTotal($trfVS1, $VS1);
  }

  public function testVariant4() {
    /**
     * VL -  VS2 – VS1 - Neues Mitglied
     * 3$ -  15$ -  5$
     * ------------------------------------------*/

    /* Setup
    ---------------------------------------------*/
    DbEntityHelper::setCon(self::$propelCon);

    list(
        list($IT, $VL, $OL, $PM, $VS2, $VS1),
        list($trfIT, $trfVL, $trfOL, $trfPM, $trfVS2, $trfVS1),
    ) = DbEntityHelper::setUpBonusMembers(true, [
        'OL' => false,
        'PM' => false
    ]);

    $new = DbEntityHelper::createSignupMember($VS1);

    $trfIT += Transaction::getAmountForReason(Transaction::REASON_IT_BONUS);
    $trfVL += Transaction::getAmountForReason(Transaction::REASON_VL_BONUS)
      + Transaction::getAmountForReason(Transaction::REASON_OL_BONUS)
      + Transaction::getAmountForReason(Transaction::REASON_PM_BONUS);
    // $trfOL += 1;
    // $trfPM += Transaction::getAmountForReason(Transaction::REASON_PM_BONUS);
    $trfVS2 += Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_INDIRECT);
    $trfVS1 += Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_LVL1);

    $this->assertTransferTotal($trfIT, $IT);
    $this->assertTransferTotal($trfVL, $VL);
    // $this->assertTransferTotal($trfOL, $OL);
    // $this->assertTransferTotal($trfPM, $PM);
    $this->assertTransferTotal($trfVS2, $VS2);
    $this->assertTransferTotal($trfVS1, $VS1);

  }

  public function testVariant5() {
    /**
     * VL -  OL - VS2 – VS1 - Neues Mitglied
     * 1$ -  2$ - 15$ -  5$
     * ------------------------------------------*/

    /* Setup
    ---------------------------------------------*/
    DbEntityHelper::setCon(self::$propelCon);

    list(
        list($IT, $VL, $OL, $PM, $VS2, $VS1),
        list($trfIT, $trfVL, $trfOL, $trfPM, $trfVS2, $trfVS1),
    ) = DbEntityHelper::setUpBonusMembers(true, [
        'PM' => false
    ]);

    $new = DbEntityHelper::createSignupMember($VS1);

    $trfIT += Transaction::getAmountForReason(Transaction::REASON_IT_BONUS);
    $trfVL += Transaction::getAmountForReason(Transaction::REASON_VL_BONUS);
    $trfOL += Transaction::getAmountForReason(Transaction::REASON_OL_BONUS) +
      Transaction::getAmountForReason(Transaction::REASON_PM_BONUS);
    // $trfPM += Transaction::getAmountForReason(Transaction::REASON_PM_BONUS);
    $trfVS2 += Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_INDIRECT);
    $trfVS1 += Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_LVL1);

    $this->assertTransferTotal($trfIT, $IT);
    $this->assertTransferTotal($trfVL, $VL);
    $this->assertTransferTotal($trfOL, $OL);
    // $this->assertTransferTotal($trfPM, $PM);
    $this->assertTransferTotal($trfVS2, $VS2);
    $this->assertTransferTotal($trfVS1, $VS1);

  }

  public function testVariant6() {
    /**
     * VL  -  VS1 - Neues Mitglied
     * 18$ -  5$
     * ------------------------------------------*/

    /* Setup
    ---------------------------------------------*/
    DbEntityHelper::setCon(self::$propelCon);

    list(
        list($IT, $VL, $OL, $PM, $VS2, $VS1),
        list($trfIT, $trfVL, $trfOL, $trfPM, $trfVS2, $trfVS1),
    ) = DbEntityHelper::setUpBonusMembers(true, [
        'OL' => false,
        'PM' => false,
        'VS2' => false,
    ]);

    $new = DbEntityHelper::createSignupMember($VS1);

    $trfIT += Transaction::getAmountForReason(Transaction::REASON_IT_BONUS);
    $trfVL += Transaction::getAmountForReason(Transaction::REASON_VL_BONUS)
      + Transaction::getAmountForReason(Transaction::REASON_OL_BONUS)
      + Transaction::getAmountForReason(Transaction::REASON_PM_BONUS)
      + Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_INDIRECT);
    // $trfOL += 1;
    // $trfPM += Transaction::getAmountForReason(Transaction::REASON_PM_BONUS);
    // $trfVS2 += Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_INDIRECT);
    $trfVS1 += Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_LVL1);

    $this->assertTransferTotal($trfIT, $IT);
    $this->assertTransferTotal($trfVL, $VL);
    // $this->assertTransferTotal($trfOL, $OL);
    // $this->assertTransferTotal($trfPM, $PM);
    // $this->assertTransferTotal($trfVS2, $VS2);
    $this->assertTransferTotal($trfVS1, $VS1);

  }

  public function testVariant7() {
    /**
     * VL -  OL  – VS1 - Neues Mitglied
     * 1$ -  17$ -  5$
     * ------------------------------------------*/

    /* Setup
    ---------------------------------------------*/
    DbEntityHelper::setCon(self::$propelCon);

    list(
        list($IT, $VL, $OL, $PM, $VS2, $VS1),
        list($trfIT, $trfVL, $trfOL, $trfPM, $trfVS2, $trfVS1),
    ) = DbEntityHelper::setUpBonusMembers(true, [
        'PM' => false,
        'VS2' => false,
    ]);

    $new = DbEntityHelper::createSignupMember($VS1);

    $trfIT += Transaction::getAmountForReason(Transaction::REASON_IT_BONUS);
    $trfVL += Transaction::getAmountForReason(Transaction::REASON_VL_BONUS);
    $trfOL += Transaction::getAmountForReason(Transaction::REASON_OL_BONUS)
      + Transaction::getAmountForReason(Transaction::REASON_PM_BONUS)
      + Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_INDIRECT);
    // $trfPM += Transaction::getAmountForReason(Transaction::REASON_PM_BONUS);
    // $trfVS2 += Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_INDIRECT);
    $trfVS1 += Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_LVL1);

    $this->assertTransferTotal($trfIT, $IT);
    $this->assertTransferTotal($trfVL, $VL);
    $this->assertTransferTotal($trfOL, $OL);
    // $this->assertTransferTotal($trfPM, $PM);
    // $this->assertTransferTotal($trfVS2, $VS2);
    $this->assertTransferTotal($trfVS1, $VS1);

  }

  public function testVariant8() {
    /**
     * PM -  VS2  – VS1 - Neues Mitglied
     * 1$ -  15$  - 5$
     * ------------------------------------------*/

    /* Setup
    ---------------------------------------------*/
    DbEntityHelper::setCon(self::$propelCon);

    list(
        list($IT, $VL, $OL, $PM, $VS2, $VS1),
        list($trfIT, $trfVL, $trfOL, $trfPM, $trfVS2, $trfVS1),
    ) = DbEntityHelper::setUpBonusMembers(true, [
        'VL' => false,
        'OL' => false,
    ]);

    $new = DbEntityHelper::createSignupMember($VS1);

    $trfIT += Transaction::getAmountForReason(Transaction::REASON_IT_BONUS);
    // $trfVL += Transaction::getAmountForReason(Transaction::REASON_VL_BONUS);
    // $trfOL += Transaction::getAmountForReason(Transaction::REASON_OL_BONUS);
    $trfPM += Transaction::getAmountForReason(Transaction::REASON_PM_BONUS);
    $trfVS2 += Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_INDIRECT);
    $trfVS1 += Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_LVL1);

    $this->assertTransferTotal($trfIT, $IT);
    // $this->assertTransferTotal($trfVL, $VL);
    // $this->assertTransferTotal($trfOL, $OL);
    $this->assertTransferTotal($trfPM, $PM);
    $this->assertTransferTotal($trfVS2, $VS2);
    $this->assertTransferTotal($trfVS1, $VS1);

  }

  public function testVariant9() {
    /**
     * PM  -  VS1 - Neues Mitglied
     * 16$ -  5$
     * ------------------------------------------*/

    /* Setup
    ---------------------------------------------*/
    DbEntityHelper::setCon(self::$propelCon);

    list(
        list($IT, $VL, $OL, $PM, $VS2, $VS1),
        list($trfIT, $trfVL, $trfOL, $trfPM, $trfVS2, $trfVS1),
    ) = DbEntityHelper::setUpBonusMembers(true, [
        'VL' => false,
        'OL' => false,
        'VS2' => false,
    ]);

    $new = DbEntityHelper::createSignupMember($VS1);

    $trfIT += Transaction::getAmountForReason(Transaction::REASON_IT_BONUS);
    // $trfVL += Transaction::getAmountForReason(Transaction::REASON_VL_BONUS);
    // $trfOL += Transaction::getAmountForReason(Transaction::REASON_OL_BONUS);
    $trfPM += Transaction::getAmountForReason(Transaction::REASON_PM_BONUS) +
      Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_INDIRECT);
    // $trfVS2 += Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_INDIRECT);
    $trfVS1 += Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_LVL1);

    $this->assertTransferTotal($trfIT, $IT);
    // $this->assertTransferTotal($trfVL, $VL);
    // $this->assertTransferTotal($trfOL, $OL);
    $this->assertTransferTotal($trfPM, $PM);
    // $this->assertTransferTotal($trfVS2, $VS2);
    $this->assertTransferTotal($trfVS1, $VS1);

  }

  public function testVariant10() {
    /**
     * PM - Neues Mitglied
     * 21$
     * ------------------------------------------*/

    /* Setup
    ---------------------------------------------*/
    DbEntityHelper::setCon(self::$propelCon);

    list(
        list($IT, $VL, $OL, $PM, $VS2, $VS1),
        list($trfIT, $trfVL, $trfOL, $trfPM, $trfVS2, $trfVS1),
    ) = DbEntityHelper::setUpBonusMembers(true, [
        'VL' => false,
        'OL' => false,
    ]);

    $new = DbEntityHelper::createSignupMember($PM);

    $trfIT += Transaction::getAmountForReason(Transaction::REASON_IT_BONUS);
    // $trfVL += Transaction::getAmountForReason(Transaction::REASON_VL_BONUS);
    // $trfOL += Transaction::getAmountForReason(Transaction::REASON_OL_BONUS);
    $trfPM += Transaction::getAmountForReason(Transaction::REASON_PM_BONUS) +
      Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_LVL2);
    // $trfVS2 += Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_INDIRECT);
    // $trfVS1 += Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_LVL1);

    $this->assertTransferTotal($trfIT, $IT);
    // $this->assertTransferTotal($trfVL, $VL);
    // $this->assertTransferTotal($trfOL, $OL);
    $this->assertTransferTotal($trfPM, $PM);
    $this->assertTransferTotal($trfVS2, $VS2);
    $this->assertTransferTotal($trfVS1, $VS1);

  }

  public function testVariant11() {
    /**
     * VL - Neues Mitglied
     * 23$
     * ------------------------------------------*/

    /* Setup
    ---------------------------------------------*/
    DbEntityHelper::setCon(self::$propelCon);

    list(
        list($IT, $VL, $OL, $PM, $VS2, $VS1),
        list($trfIT, $trfVL, $trfOL, $trfPM, $trfVS2, $trfVS1),
    ) = DbEntityHelper::setUpBonusMembers(true, [
    ]);

    $new = DbEntityHelper::createSignupMember($VL);

    $trfIT += Transaction::getAmountForReason(Transaction::REASON_IT_BONUS);
    $trfVL += Transaction::getAmountForReason(Transaction::REASON_VL_BONUS) +
      Transaction::getAmountForReason(Transaction::REASON_OL_BONUS) +
      Transaction::getAmountForReason(Transaction::REASON_PM_BONUS) +
      Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_LVL2);
    // $trfOL += Transaction::getAmountForReason(Transaction::REASON_OL_BONUS);
    // $trfPM += Transaction::getAmountForReason(Transaction::REASON_PM_BONUS);
    // $trfVS2 += Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_INDIRECT);
    // $trfVS1 += Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_LVL1);

    $this->assertTransferTotal($trfIT, $IT);
    $this->assertTransferTotal($trfVL, $VL);
    $this->assertTransferTotal($trfOL, $OL);
    $this->assertTransferTotal($trfPM, $PM);
    $this->assertTransferTotal($trfVS2, $VS2);
    $this->assertTransferTotal($trfVS1, $VS1);

  }

  public function testVariant12() {
    /**
     * OL - Neues Mitglied
     * 22$
     * ------------------------------------------*/

    /* Setup
    ---------------------------------------------*/
    DbEntityHelper::setCon(self::$propelCon);

    list(
        list($IT, $VL, $OL, $PM, $VS2, $VS1),
        list($trfIT, $trfVL, $trfOL, $trfPM, $trfVS2, $trfVS1),
    ) = DbEntityHelper::setUpBonusMembers(true, [
        'VL' => false,
    ]);

    $new = DbEntityHelper::createSignupMember($OL);

    $trfIT += Transaction::getAmountForReason(Transaction::REASON_IT_BONUS);
    // $trfVL += Transaction::getAmountForReason(Transaction::REASON_VL_BONUS);
    $trfOL += Transaction::getAmountForReason(Transaction::REASON_OL_BONUS) +
      Transaction::getAmountForReason(Transaction::REASON_PM_BONUS) +
      Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_LVL2);
    // $trfPM += Transaction::getAmountForReason(Transaction::REASON_PM_BONUS);
    // $trfVS2 += Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_INDIRECT);
    // $trfVS1 += Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_LVL1);

    $this->assertTransferTotal($trfIT, $IT);
    // $this->assertTransferTotal($trfVL, $VL);
    $this->assertTransferTotal($trfOL, $OL);
    $this->assertTransferTotal($trfPM, $PM);
    $this->assertTransferTotal($trfVS2, $VS2);
    $this->assertTransferTotal($trfVS1, $VS1);

  }

  private function assertTransferTotal($total, Member $member) {
    $transfer = DbEntityHelper::getCurrentTransferBundle($member);
    $this->assertEquals($total, $transfer->getAmount(), 'Incorrect transfer total');
    $this->assertEquals($total, $member->getOutstandingTotal(), 'Incorrect outstanding total');
  }
}