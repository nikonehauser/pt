<?php

namespace Tbmt;

require VENDOR_DIR.'phpmailer'.DIRECTORY_SEPARATOR.'phpmailer'.DIRECTORY_SEPARATOR.'PHPMailerAutoload.php';

class MailHelper {

  static public function sendPasswordResetLink(\Member $member) {
    $email = $member->getEmail();
    $locale = Localizer::get('mail.password_reset');

    $num = $member->getNum();
    $now = time();
    $email = $member->getEmail();

    $href = Router::toModule('manage', 'do_reset_password', [
      'num' => $num,
      'exp' => time(),
      'hash' => Cryption::getPasswordResetToken($num, $now, $email)
    ]);

    return self::send(
      $email,
      \Tbmt\view\Factory::buildMemberFullNameString($member),
      $locale['subject'],
      Localizer::insert($locale['body'], [
        'link' => $href
      ], false)
    );
  }

  static function send($address, $name, $subject, $body) {
    $mail = new \PHPMailer(true);
    $mail->SMTPSecure = Config::get('mail.smtp_secure');
    $mail->isSMTP();

    $mail->Host = Config::get('mail.smtp_host');
    $mail->Port = Config::get('mail.smtp_port');
    $mail->SMTPAuth = true;
    $mail->Username = Config::get('mail.username');
    $mail->Password = Config::get('mail.password');
    $mail->Timeout = Config::get('mail.timeout');
    $mail->CharSet = Config::get('mail.charset', TYPE_STRING, 'utf-8');

    $mail->setFrom(Config::get('mail.sender_mail'), Config::get('mail.sender_name'));
    $mail->addReplyTo(Config::get('mail.reply_mail'), 'Do not Reply');
    $mail->addAddress($address, $name);

    $mail->Subject = Config::get('mail.subject_prefix').' '.$subject;
    $mail->Body = $body;

    $boolResult = $mail->send();
    if(!$boolResult)
      throw new Exception('Mailer Error: '.$mail->ErrorInfo);

    return $boolResult;
  }
}

?>