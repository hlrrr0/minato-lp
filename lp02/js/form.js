////////////////////
//設定
////////////////////
/* 名前 */
$(function(){
	$("#Name").bind("change blur", function(){
		var id = $(this).val();
		check_Name(id);
	});
});
function check_Name(id){
	$("#err_Name").empty();
	$("#Name").removeClass("required");
	var _result = true;
	var _id = id;
	if(_id == ''){
		$("#Name").addClass("required");
		$("#err_Name").append("<p>※お名前を入力してください。</p>");
		_result = false;
	}
	return _result;
}

/* メールアドレス */
$(function(){
	$("#MailAddress").bind("change blur", function(){
		var id = $(this).val();
		check_MailAddress(id);
	});
});
function check_MailAddress(id){
	$("#err_MailAddress").empty();
	$("#MailAddress").removeClass("required");
	var _result = true;
	var _id = id;
	if(_id == ''){
		$("#MailAddress").addClass("required");
		$("#err_MailAddress").append("<p>※メールアドレスを入力してください。</p>");
		_result = false;
	}
	else if (!_id.match(/.+@.+\..+/)) {
		$("#MailAddress").addClass("required");
		$("#err_MailAddress").append("<p>※正しいメールアドレスを入力してください。</p>");
		_result = false;
	}
	return _result;
}

/* 電話番号 */
$(function(){
	$("#TelNo").bind("change blur", function(){
		var id = $(this).val();
		check_TelNo(id);
	});
});
function check_TelNo(id){
	$("#err_TelNo").empty();
	$("#TelNo").removeClass("required");
	var _result = true;
	var _id = id;
	if(_id == ''){
		$("#TelNo").addClass("required");
		$("#err_TelNo").append("<p>※電話番号を入力してください。</p>");
		_result = false;
	}
	else if(!_id.match(/^0\d{1,3}-?\d{2,4}-?\d{3,4}$/)) {
		$("#TelNo").addClass("required");
		$("#err_TelNo").append("<p>※半角英数で正しい電話番号を入力してください。</p>");
		_result = false;
	}
	else if(_id.length < 10){
		$("#TelNo").addClass("required");
		$("#err_TelNo").append("<p>※半角英数で正しい電話番号を入力してください。</p>");
		_result = false;
	}
	else if(_id.match(/^([0-9]{12,})$/)) {
		$("#TelNo").addClass("required");
		$("#err_TelNo").append("<p>※半角英数で正しい電話番号を入力してください。</p>");
		_result = false;
	}
	return _result;
}

/* 都道府県 */
$(function(){
	$("#Address01").bind("change blur", function(){
		var id = $(this).val();
		check_Address01(id);
	});
});
function check_Address01(id){
	$("#err_Address01").empty();
	$("#Address01").removeClass("required");
	var _result = true;
	var _id = id;
	if(_id == ''){
		$("#Address01").addClass("required");
		$("#err_Address01").append("<p>※都道府県を選択してください。</p>");
		_result = false;
	}
	return _result;
}


////////////////////
//チェックする
////////////////////
$("form").on("submit", function () {
	var error = '';

//個人情報保護方針 
	$("#err_Privacy").empty();
	var cnt = $('#scr_Privacy input:checkbox:checked').length;
	if(cnt == 0) {
		$("#err_Privacy").append("※個人情報の取り扱いに同意いただけないと、送信できません。");
		error = "#scr_Privacy";
	}

//都道府県
	var str = $('#Address01').val();
	$("#err_Address01").empty();
	$("#Address01").removeClass("required");
	if(str == ''){
		$("#Address01").addClass("required");
		$("#err_Address01").append("<p>※都道府県を選択してください。</p>");
		error = "#scr_Address01";
	}

//メールアドレス
	var str = $('#MailAddress').val();
	$("#err_MailAddress").empty();
	$("#MailAddress").removeClass("required");
	if(str == ''){
		$("#MailAddress").addClass("required");
		$("#err_MailAddress").append("<p>※メールアドレスを入力してください。</p>");
		error = "#scr_MailAddress";
	}
	else if (!str.match(/.+@.+\..+/)) {
		$("#MailAddress").addClass("required");
		$("#err_MailAddress").append("<p>※正しいメールアドレスを入力してください。</p>");
		error = "#scr_MailAddress";
	}

//電話番号
	var str = $('#TelNo').val();
	$("#err_TelNo").empty();
	$("#TelNo").removeClass("required");
	if(str == ''){
		$("#TelNo").addClass("required");
		$("#err_TelNo").append("<p>※電話番号を入力してください。</p>");
		error = "#scr_TelNo";
	}
	else if(!str.match(/^0\d{1,3}-?\d{2,4}-?\d{3,4}$/)) {
		$("#TelNo").addClass("required");
		$("#err_TelNo").append("<p>※半角英数で正しい電話番号を入力してください。</p>");
		error = "#scr_TelNo";
	}
	else if(str.length < 10){
		$("#TelNo").addClass("required");
		$("#err_TelNo").append("<p>※半角英数で正しい電話番号を入力してください。</p>");
		error = "#scr_TelNo";
	}
	else if(str.match(/^([0-9]{12,})$/)) {
		$("#TelNo").addClass("required");
		$("#err_TelNo").append("<p>※半角英数で正しい電話番号を入力してください。</p>");
		error = "#scr_TelNo";
	}

//お名前
	var str = $('#Name').val();
	$("#err_Name").empty();
	$("#Name").removeClass("required");
	if(str == ''){
		$("#Name").addClass("required");
		$("#err_Name").append("<p>※お名前を入力してください。</p>");
		error = "#scr_Name";
	}



	if (!error=='') {
		$("html,body").animate({scrollTop:$(error).offset().top});
		return false;
	}
});
