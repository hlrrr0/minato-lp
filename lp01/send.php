<?php
  // エラーレポートを有効にする（開発環境のみ、本番環境では無効にする）
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  // セッションの開始
  session_start();

  // Composerのオートローダーを読み込む（絶対パスを使用）
  require __DIR__ . '/vendor/autoload.php';

  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\SMTP;
  use PHPMailer\PHPMailer\Exception;

  // // セッションからフォームデータを取得
  // if (!isset($_SESSION['form_data'])) {
  //   header('Location: index.html');
  //   exit;
  // }
  // フォームデータのサニタイズとバリデーション
  $formData = [
    'Name' => $_POST['Name'] ?? '',
    'NameKana' => $_POST['NameKana'] ?? '',
    'MailAddress' => $_POST['MailAddress'] ?? '',
    'TelNo' => $_POST['TelNo'] ?? '',
    'Address01' => $_POST['Address01'] ?? '',
    'Address02' => $_POST['Address02'] ?? '',
    'Address03' => $_POST['Address03'] ?? '',
    'Check01' => $_POST['Check01'] ?? [],
    'Tsubo' => $_POST['Tsubo'] ?? '',
    'Comment' => $_POST['Comment'] ?? '',
  ];


  // セッションに保存
  $_SESSION['form_data'] = $formData;
  $formData = $_SESSION['form_data'];

  // フォームデータの取得とサニタイズ
  $Name = htmlspecialchars(trim($formData['Name']), ENT_QUOTES, 'UTF-8');
  $NameKana = htmlspecialchars(trim($formData['NameKana']), ENT_QUOTES, 'UTF-8');
  $MailAddress = htmlspecialchars(trim($formData['MailAddress']), ENT_QUOTES, 'UTF-8');
  $TelNo = htmlspecialchars(trim($formData['TelNo']), ENT_QUOTES, 'UTF-8');
  $Address01 = htmlspecialchars(trim($formData['Address01']), ENT_QUOTES, 'UTF-8');
  $Address02 = htmlspecialchars(trim($formData['Address02']), ENT_QUOTES, 'UTF-8');
  $Address03 = htmlspecialchars(trim($formData['Address03']), ENT_QUOTES, 'UTF-8');
  // $Check01 = isset($formData['Check01']) ? htmlspecialchars(implode(", ", $formData['Check01']), ENT_QUOTES, 'UTF-8') : '未選択';
  $Check01 = '未選択';
  if (!empty($formData['Check01'])) {
      if (is_array($formData['Check01'])) {
          $Check01 = htmlspecialchars(implode(", ", $formData['Check01']), ENT_QUOTES, 'UTF-8');
      } else {
          $Check01 = htmlspecialchars($formData['Check01'], ENT_QUOTES, 'UTF-8');
      }
  }
  
  $Tsubo = htmlspecialchars(trim($formData['Tsubo']), ENT_QUOTES, 'UTF-8');
  $Comment = htmlspecialchars(trim($formData['Comment']), ENT_QUOTES, 'UTF-8');

  // 日時とIPアドレスを変数に格納
  $SendDate = date("Y/m/d H:i:s");
  $IPAddress = $_SERVER["HTTP_X_FORWARDED_FOR"] ?? $_SERVER["REMOTE_ADDR"];

  // 管理者メール設定
  $AdminMailAddress = "hiroki19910602@gmail.com";
  $FromName = "株式会社港開発お問い合わせ窓口";
  $FromMail = "noreply@baybund.com"; // 実際に存在するメールアドレスを使用
  $AdminMailSubject = "【お問い合わせ】再建築不可物件LP";

  // ユーザーメール設定
  $UserMailSubject = "再建築不可物件ご売却のお問い合わせありがとうございます。";

  // メール本文の作成
  $AdminMailBody = <<<EOT
    再建築不可物件LPに
    お客様からお問い合わせがありました。
    ※本メールは、プログラムから自動で送信しています。

    以下お客様情報です。

    ----------------------------------------------
    ■お客様情報

    【 お名前 】
    {$Name}

    【 フリガナ 】
    {$NameKana}

    【 電話番号 】
    {$TelNo}

    【 メールアドレス 】
    {$MailAddress}

    ----------------------------------------------
    ■売却物件情報

    【 都道府県 】
    {$Address01}

    【 市区町村 】
    {$Address02}

    【 丁目番地 】
    {$Address03}

    【 物件種別 】
    {$Check01}

    【 対象物件の坪数 】
    {$Tsubo}

    【 お問い合わせ内容 】
    {$Comment}

    ----------------------------------------------
    送信された日時：{$SendDate}
    送信者のIPアドレス：{$IPAddress}

    お客様への折り返しのご連絡をよろしくお願いいたします。
    EOT;

    // ユーザーメール本文の作成
  $UserMailBody = <<<EOT
    {$Name}様

    この度はお問い合わせいただき、
    誠にありがとうございます。

    下記の内容を確認させていただき、
    折り返し担当者よりご連絡差し上げます。

    ----------------------------------------------
    ■お客様情報

    【 お名前 】
    {$Name}

    【 フリガナ 】
    {$NameKana}

    【 電話番号 】
    {$TelNo}

    【 メールアドレス 】
    {$MailAddress}

    ----------------------------------------------
    ■売却物件情報

    【 都道府県 】
    {$Address01}

    【 市区町村 】
    {$Address02}

    【 丁目番地 】
    {$Address03}

    【 物件種別 】
    {$Check01}

    【 対象物件の坪数 】
    {$Tsubo}

    【 お問い合わせ内容 】
    {$Comment}

    ----------------------------------------------

    なお、数日経ちましてもご連絡がない場合は、
    何かしらの不具合でメールが届いていない可能性がございます。
    その場合は、大変お手数ではございますが、
    下記の電話番号までご連絡をいただけますと幸いです。

    03-6812-2889

    引き続き、どうぞよろしくお願いいたします。

    ※本メールはプログラムから自動で送信しています。
    　心当たりのない方は、お手数ですが削除していただければ幸いです。

    ──────────────────────
    株式会社港開発
    〒100-0005
    東京都千代田区丸の内１丁目６−５
    丸の内北口ビルディング 15階
    TEL：03-6812-2889
    Mail：{$FromMail}
    ──────────────────────
    EOT;

  // PHPMailerのインスタンスを作成
  $mail = new PHPMailer(true);

  try {
    // SMTP設定
    $mail->isSMTP();
    // $phpmailer->SMTPDebug = SMTP::DEBUG_LOWLEVEL;   // SMTPのデバッグ情報を出力するための設定。デバッグレベルは低いものに設定。
    $mail->Host       = getenv('SMTP_HOST'); // 使用するSMTPサーバー（例: Gmail）
    $mail->SMTPAuth   = true;
    $mail->Username   = $FromMail; // SMTPユーザー名（メールアドレス）
    $mail->Password   = getenv('SMTP_PASSWORD'); // SMTPパスワード（Gmailの場合はアプリパスワード）
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // 暗号化方式
    $mail->Port       = getenv('SMTP_PORT'); // SMTPポート（Gmailの場合は587）
    
    // 文字エンコーディングの設定
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';

    // 管理者へのメール送信
    $mail->setFrom($FromMail, $FromName);
    $mail->addAddress($AdminMailAddress);
    $mail->Subject = $AdminMailSubject;
    $mail->Body    = $AdminMailBody;
    $mail->send();
  } catch (Exception $e) {
    error_log("管理者へのメール送信に失敗しました。Mailer Error: {$mail->ErrorInfo}");
  }

  try {
    // ユーザーへのメール送信
    $mail->clearAddresses();
    $mail->addAddress($MailAddress);
    $mail->Subject = $UserMailSubject;
    $mail->Body    = $UserMailBody;
    $mail->send();
  } catch (Exception $e) {
    error_log("ユーザーへのメール送信に失敗しました。Mailer Error: {$mail->ErrorInfo}");
  }

  // セッションデータのクリア
  unset($_SESSION['form_data']);

  // リダイレクト
  header('Location: ./thanks/');
  exit;
?>
