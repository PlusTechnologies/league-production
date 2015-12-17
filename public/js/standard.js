
$(function () {
	$('.intro-header') .css({'height': (($(window).height()))+'px'});
	$(window).resize(function(){
		$('.intro-header') .css({'height': (($(window).height()))+'px'});
	});
})



