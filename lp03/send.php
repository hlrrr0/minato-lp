<?php
  // セッションの開始
  session_start();

  // Composerのオートローダーを読み込む（絶対パスを使用）
  require __DIR__ . '/vendor/autoload.php';
  use Dotenv\Dotenv;

  // .env ファイルをロード
  $dotenv = Dotenv::createImmutable(__DIR__);
  $dotenv->load();

  // 環境変数を取得
  $smtpHost = $_ENV['SMTP_HOST']; // SMTPホスト
  $smtpPort = $_ENV['SMTP_PORT']; // SMTPポート 
  $smtpMail = $_ENV['SMTP_MAIL']; // SMTPメール
  $smtpPass = $_ENV['SMTP_PASSWORD']; // SMTPパス

  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\SMTP;
  use PHPMailer\PHPMailer\Exception;

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
  $LpContents = '借地権・底地権付不動産';
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
  $AdminMailAddress = [
    "hiroki19910602+minato@gmail.com", 
    "a_numaguchi@minato-kaihatsu.com"
  ];
  $FromName = "株式会社港開発お問い合わせ窓口";
  $FromMail = $smtpMail;
  $AdminMailSubject = "【お問い合わせ】{$LpContents}LP";

  // ユーザーメール設定
  $UserMailSubject = "{$LpContents} ご売却のお問い合わせありがとうございます。";

  // メール本文の作成
  $AdminMailBody = <<<EOT
    {$LpContents}LPに
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

  function sendToGoogleSheets($data) {
     // ここにGASのURLを貼り付ける
    $url = "https://script.google.com/macros/s/AKfycbxsM-7w4KZHH0NESmMQ1s_3IgbbE5lkENx_w6_Ml6yOu3nrcl8ixivCwxsApZSXAbhY/exec";
    $options = [
        'http' => [
            'header'  => "Content-Type: application/json",
            'method'  => 'POST',
            'content' => json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK), // 数値の型を維持
        ]
    ];
    $context  = stream_context_create($options);
    return file_get_contents($url, false, $context);
  }


  try {
    // SMTP設定
    $mail->isSMTP();

    // // デバッグ情報を有効化
    // $mail->SMTPDebug = SMTP::DEBUG_SERVER; // 詳細なデバッグ情報を出力
    // $mail->Debugoutput = 'html'; // HTML形式で出力

    $mail->Host       = $smtpHost;
    $mail->SMTPAuth   = true;
    $mail->Username   = $FromMail; // SMTPユーザー名（メールアドレス）
    $mail->Password   = $smtpPass; // SMTPパスワード
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // 暗号化方式
    $mail->Port       = $smtpPort; // SMTPポート
    
    
    // 文字エンコーディングの設定
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';

    // 管理者へのメール送信
    $mail->setFrom($FromMail, $FromName);

    // 宛先を追加
    foreach ($AdminMailAddress as $address) {
      $mail->addAddress(trim($address)); // trim() で余計なスペースを削除
    }
    
    $mail->Subject = $AdminMailSubject;
    $mail->Body    = $AdminMailBody;
    $mail->send();
  } catch (Exception $e) {
    error_log("管理者へのメール送信に失敗しました。Mailer Error: {$mail->ErrorInfo}");
  }
  // フォームデータを送信
  $spreadsheetResponse = sendToGoogleSheets([
    "Name" => (string) $Name,
    "NameKana" => (string) $NameKana,
    "MailAddress" => (string) $MailAddress,
    "TelNo" => (strpos($TelNo, "0") === 0) ? (string) $TelNo : "0" . (string) $TelNo, // 0を付ける
    "Address01" => (string) $Address01,
    "Address02" => (string) $Address02,
    "Address03" => (string) $Address03,
    "Check01" => (string) $Check01,
    "Tsubo" => (string) $Tsubo,
    "Comment" => (string) $Comment
  ]);

  if ($spreadsheetResponse !== "Success") {
      error_log("Google Sheetsへの送信に失敗しました");
  }

  try {
    // ユーザーへのメール送信
    $mail->clearAllRecipients(); // すべての宛先をクリア

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
