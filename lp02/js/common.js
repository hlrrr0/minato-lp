// JavaScript Document
$(document).ready(function() {
	
	// Smooth scroll
	var headerHeight = $('.header').height();
	$('a[href^="#"]').click(function(){
		var speed = 500;
		var href= $(this).attr("href");
		var target = $(href == "#" || href == "" ? 'html' : href);
		var position = "";
		position = target.offset().top - headerHeight;
		$("html, body").animate({scrollTop:position}, speed, "swing");
		return false;
	});
	
	// Window size Check
	windowSizeCheck();
	$(window).on('load resize', function(){
		windowSizeCheck();
	});
	
	// Accordion
	$('.faq__list-item .head').click(function(){
		$(this).toggleClass('is-active');
		$(this).next().toggleClass('is-active')
	});
})

function windowSizeCheck() {
	/* ▼固定スクロールの高さを計算してコンテンツに重ならないようにする処理 */
	$(".wrap").css("padding-top", $('.header').height());
}
