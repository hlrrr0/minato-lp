<?php
	if ($_SERVER ['REQUEST_METHOD'] !== 'POST') {
		header('Location: ./');
		exit;
	}

	if (isset($_POST['Check01'])) {
		$Check01 = implode(',',$_POST['Check01']);
	}
	else {
		$Check01 = '';
	}
?>
<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','GTM-KBXMTGHC');</script>
	<!-- End Google Tag Manager -->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title>入力内容確認</title>
<link rel="stylesheet" href="css/form.css">
</head>
<body id="confirm">
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KBXMTGHC"
	height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->
<div class="wrap">
	<div class="contents">
		<section class="confirm">
			<h1 class="confirm__heading">入力内容確認</h1>
			<div class="confirm__inner">
				<div class="form">
					<p class="confirm__text">以下の内容でよろしければ、「送信する」ボタンを押してください。</p>
					<form action="send.php" method="POST">
						<h4 class="form__heading">お客様情報</h4>
						<dl class="form__list">
							<div class="form__list-item">
								<dt class="head">お名前</dt>
								<dd class="body"><?php echo htmlspecialchars($_POST['Name'], ENT_QUOTES, "UTF-8"); ?></dd>
							</div>
							<div class="form__list-item">
								<dt class="head">フリガナ</dt>
								<dd class="body"><?php echo htmlspecialchars($_POST['NameKana'], ENT_QUOTES, "UTF-8"); ?></dd>
							</div>
							<div class="form__list-item">
								<dt class="head">電話番号</dt>
								<dd class="body"><?php echo htmlspecialchars($_POST['TelNo'], ENT_QUOTES, "UTF-8"); ?></dd>
							</div>
							<div class="form__list-item">
								<dt class="head">メールアドレス</dt>
								<dd class="body"><?php echo htmlspecialchars($_POST['MailAddress'], ENT_QUOTES, "UTF-8"); ?></dd>
							</div>
						</dl>
						<h4 class="form__heading">ご売却物件情報</h4>
						<dl class="form__list">
							<div class="form__list-item">
								<dt class="head">都道府県</dt>
								<dd class="body"><?php echo $_POST['Address01']; ?></dd>
							</div>
							<div class="form__list-item">
								<dt class="head">市区町村</dt>
								<dd class="body"><?php echo htmlspecialchars($_POST['Address02'], ENT_QUOTES, "UTF-8"); ?></dd>
							</div>
							<div class="form__list-item">
								<dt class="head">丁目番地</dt>
								<dd class="body"><?php echo htmlspecialchars($_POST['Address03'], ENT_QUOTES, "UTF-8"); ?></dd>
							</div>
							<div class="form__list-item">
								<dt class="head">物件種別</dt>
								<dd class="body"><?php echo $Check01; ?></dd>
							</div>
							<div class="form__list-item">
								<dt class="head">対象物件の坪数</dt>
								<dd class="body"><?php echo htmlspecialchars($_POST['Tsubo'], ENT_QUOTES, "UTF-8"); ?></dd>
							</div>
							<div class="form__list-item">
								<dt class="head">お問い合わせ内容</dt>
								<dd class="body"><?php echo nl2br(htmlspecialchars($_POST['Comment'], ENT_QUOTES, "UTF-8")); ?></dd>
							</div>
						</dl>
						<p class="confirm__text">※メールアドレスに誤りがないか、今一度ご確認ください。</p>
						<div class="form__button">
							<div class="form__button-submit"><button type="submit">入力内容を送信する</button></div>
							<div class="form__button-return"><button type="button" onClick="history.back()">前画面に戻る</button></div>
						</div>
						<input type="hidden" name="Name" value="<?php echo htmlspecialchars($_POST['Name'], ENT_QUOTES, "UTF-8"); ?>">
						<input type="hidden" name="NameKana" value="<?php echo htmlspecialchars($_POST['NameKana'], ENT_QUOTES, "UTF-8"); ?>">
						<input type="hidden" name="TelNo" value="<?php echo htmlspecialchars($_POST['TelNo'], ENT_QUOTES, "UTF-8"); ?>">
						<input type="hidden" name="MailAddress" value="<?php echo htmlspecialchars($_POST['MailAddress'], ENT_QUOTES, "UTF-8"); ?>">
						<input type="hidden" name="Check01" value="<?php echo $Check01; ?>">
						<input type="hidden" name="Address01" value="<?php echo $_POST['Address01']; ?>">
						<input type="hidden" name="Address02" value="<?php echo htmlspecialchars($_POST['Address02'], ENT_QUOTES, "UTF-8"); ?>">
						<input type="hidden" name="Address03" value="<?php echo htmlspecialchars($_POST['Address03'], ENT_QUOTES, "UTF-8"); ?>">
						<input type="hidden" name="Tsubo" value="<?php echo htmlspecialchars($_POST['Tsubo'], ENT_QUOTES, "UTF-8"); ?>">
						<input type="hidden" name="Comment" value="<?php echo htmlspecialchars($_POST['Comment'], ENT_QUOTES, "UTF-8"); ?>">
					</form>
				</div>
			</div>
		<!-- /section --></section>
	<!-- /.contents --></div>
<!-- /.wrap --></div>
<script src="js/font.js"></script>
</body>
</html>
