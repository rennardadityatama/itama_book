(function($) {
	"use strict";
	var timelineBlocks = $('.cd-timeline-block'),
		offset = 0.8;
	hideBlocks(timelineBlocks, offset);
	$(window).on('scroll', function(){
		(!window.requestAnimationFrame) 
			? setTimeout(function(){ showBlocks(timelineBlocks, offset); }, 100)
			: window.requestAnimationFrame(function(){ showBlocks(timelineBlocks, offset); });
	});
	function hideBlocks(blocks, offset) {
		blocks.each(function(){
			( $(this).offset().top > $(window).scrollTop()+$(window).height()*offset ) && $(this).find('.cd-timeline-img, .cd-timeline-content').addClass('is-hidden');
		});
	}
	function showBlocks(blocks, offset) {
		blocks.each(function(){
			( $(this).offset().top <= $(window).scrollTop()+$(window).height()*offset && $(this).find('.cd-timeline-img').hasClass('is-hidden') ) && $(this).find('.cd-timeline-img, .cd-timeline-content').removeClass('is-hidden').addClass('bounce-in');
		});
	}

	$(document).ready(function() {
		$('a.sidebar-link, a[href*="index.php?c="]').on('click', function(e) {
			var href = $(this).attr('href');
			// Cek jika bukan link javascript:void(0) atau #
			if (href && href !== '#' && href.indexOf('javascript:') === -1) {
				$('.loader-wrapper').fadeIn(200);
			}
		});
	});

	// Hide loader setelah halaman loaded
	$(window).on('load', function() {
		setTimeout(function() {
			$('.loader-wrapper').fadeOut(300);
		}, 300);
	});
	
})(jQuery);