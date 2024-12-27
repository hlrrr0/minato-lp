<?php
	// データが空っぽのときはリダイレクト処理
	// Name、MailAddress、TelNoが必須
	// ↑上記が存在しない場合は適宜修正必要
	if (!isset($_POST['Name']) || $_POST['Name']=='' || !isset($_POST['MailAddress']) || $_POST['MailAddress']=='' || !isset($_POST['TelNo']) || $_POST['TelNo']=='') {
		header('Location: ./');
		exit;
	}

/* 初期設定 */
//管理者メールアドレス（届け先）
$AdminMailAddress = "hiroki19910602@gmail.com";
//メールの送信元名前（ユーザーに見せる名称）
$FromName = "（テスト）株式会社港開発お問い合わせ窓口";
//メールの送信元アドレス（ユーザーに見せるアドレス）
$FromMail = "hiroki19910602+test@gmail.com";

//管理者メール設定
//件名
$AdminMailSubject = "【お問い合わせ】再建築不可物件LP";
//本文
$AdminMailBody = '再建築不可物件LPに
お客様からお問い合わせがありました。
※本メールは、プログラムから自動で送信しています。

以下お客様情報です。

----------------------------------------------
■お客様情報

【 お名前 】
{%%Name%%}

【 フリガナ 】
{%%NameKana%%}

【 電話番号 】
{%%TelNo%%}

【 メールアドレス 】
{%%MailAddress%%}

----------------------------------------------
■売却物件情報

【 都道府県 】
{%%Address01%%}

【 市区町村 】
{%%Address02%%}

【 丁目番地 】
{%%Address03%%}

【 物件種別 】
{%%Check01%%}

【 対象物件の坪数 】
{%%Tsubo%%}

【 お問い合わせ内容 】
{%%Comment%%}

----------------------------------------------
送信された日時：{%%SendDate%%}
送信者のIPアドレス：{%%IPAddress%%}

お客様への折り返しのご連絡をよろしくお願いいたします。
';

//ユーザーへのメール設定
//件名
$UserMailSubject = "再建築不可物件ご売却のお問い合わせありがとうございます。";
//本文
$UserMailBody = '{%%Name%%}様

この度はお問い合わせいただき、
誠にありがとうございます。

下記の内容を確認させていただき、
折り返し担当者よりご連絡差し上げます。

----------------------------------------------
■お客様情報

【 お名前 】
{%%Name%%}

【 フリガナ 】
{%%NameKana%%}

【 電話番号 】
{%%TelNo%%}

【 メールアドレス 】
{%%MailAddress%%}

----------------------------------------------
■売却物件情報

【 都道府県 】
{%%Address01%%}

【 市区町村 】
{%%Address02%%}

【 丁目番地 】
{%%Address03%%}

【 物件種別 】
{%%Check01%%}

【 対象物件の坪数 】
{%%Tsubo%%}

【 お問い合わせ内容 】
{%%Comment%%}

----------------------------------------------

なお、数日経ちましてもご連絡がない場合は、
何かしらの不具合でメールが届いていない可能性がございます。
その場合は、大変お手数ではございますが、
下記の電話番号までご連絡をいただけますと幸いです。

080-3830-1494

引き続き、どうぞよろしくお願いいたします。

※本メールはプログラムから自動で送信しています。
　心当たりのない方は、お手数ですが削除していただければ幸いです。

──────────────────────
株式会社港開発
〒105-0003
東京都港区西新橋一丁目23番3号
TEL：080-3830-1494
Mail：m.misato160401@gmail.com
──────────────────────
';


//文字列の置換処理
	$StrFrom[] = "{%%Name%%}";
	$StrTo[] = $_POST["Name"];
	$StrFrom[] = "{%%NameKana%%}";
	$StrTo[] = $_POST["NameKana"];
	$StrFrom[] = "{%%MailAddress%%}";
	$StrTo[] = $_POST["MailAddress"];
	$StrFrom[] = "{%%TelNo%%}";
	$StrTo[] = $_POST["TelNo"];
	$StrFrom[] = "{%%Check01%%}";
	$StrTo[] = $_POST["Check01"];
	$StrFrom[] = "{%%Radio01%%}";
	$StrTo[] = $_POST["Radio01"];
	$StrFrom[] = "{%%Address01%%}";
	$StrTo[] = $_POST["Address01"];
	$StrFrom[] = "{%%Address02%%}";
	$StrTo[] = $_POST["Address02"];
	$StrFrom[] = "{%%Address03%%}";
	$StrTo[] = $_POST["Address03"];
	$StrFrom[] = "{%%Tsubo%%}";
	$StrTo[] = $_POST["Tsubo"];
	$StrFrom[] = "{%%Comment%%}";
	$StrTo[] = $_POST["Comment"];
	$StrFrom[] = "{%%SendDate%%}";
	$StrTo[] = date("Y/m/d H:i:s");
	$StrFrom[] = "{%%IPAddress%%}";
//	$StrTo[] = $_SERVER["REMOTE_ADDR"];
	$StrTo[] = $_SERVER["HTTP_X_FORWARDED_FOR"];

$AdminMailBody = str_replace($StrFrom,$StrTo,$AdminMailBody);
$UserMailBody = str_replace($StrFrom,$StrTo,$UserMailBody);

//メール送信処理
mb_language("Japanese");
mb_internal_encoding("UTF-8");

//管理者に送信
//$headers_admin = "From: " . $FromMail;
$headers_admin = "From: " . mb_encode_mimeheader($FromName) . "<" . $FromMail . ">";
mb_send_mail($AdminMailAddress, $AdminMailSubject, $AdminMailBody, $headers_admin);

//ユーザーに送信
//$headers_user = "From: " . $FromMail;
$headers_user = "From: " . mb_encode_mimeheader($FromName) . "<" . $FromMail . ">";
mb_send_mail($_POST["MailAddress"], $UserMailSubject, $UserMailBody, $headers_user);

// 結果の表示
printf("%d cells updated.", $result->getUpdates()->getUpdatedCells());

header('Location: ./thanks/');
exit;
