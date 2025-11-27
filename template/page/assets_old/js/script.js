/*
Author       : Dreamguys
Template Name: Pathivu - Bootstrap Template
Version      : 1.0
*/

(function($) {
    "use strict";
	
	// Stick Sidebar
	
	if ($(window).width() > 767) {
		if($('.theiaStickySidebar').length > 0) {
			$('.theiaStickySidebar').theiaStickySidebar({
			  // Settings
			  additionalMarginTop: 100
			});
		}
	}
	
	// Sidebar
	
	if($(window).width() <= 991){
	var Sidemenu = function() {
		this.$menuItem = $('.main-nav a');
	};
	
	function init() {
		var $this = Sidemenu;
		$('.main-nav a').on('click', function(e) {
			if($(this).parent().hasClass('has-submenu')) {
				e.preventDefault();
			}
			if(!$(this).hasClass('submenu')) {
				$('ul', $(this).parents('ul:first')).slideUp(350);
				$('a', $(this).parents('ul:first')).removeClass('submenu');
				$(this).next('ul').slideDown(350);
				$(this).addClass('submenu');
			} else if($(this).hasClass('submenu')) {
				$(this).removeClass('submenu');
				$(this).next('ul').slideUp(350);
			}
		});
	}

	// Sidebar Initiate
	init();
	}
	
	// Textarea Text Count
	
	var maxLength = 100;
	$('#review_desc').on('keyup change', function () {
		var length = $(this).val().length;
		 length = maxLength-length;
		$('#chars').text(length);
	});
	
	// Select 2
	
	if($('.select').length > 0) {
		$('.select').select2({
			minimumResultsForSearch: -1,
			width: '100%'
		});
	}
	
	// Date Time Picker
	
	if($('.datetimepicker').length > 0) {
		$('.datetimepicker').datetimepicker({
			format: 'DD/MM/YYYY',
			icons: {
				up: "fas fa-chevron-up",
				down: "fas fa-chevron-down",
				next: 'fas fa-chevron-right',
				previous: 'fas fa-chevron-left'
			}
		});
	}
	if($('#event_filter_date_range').length > 0) {
		$('#event_filter_date_range').daterangepicker();
	}
	
	// Floating Label

	if($('.floating').length > 0 ){
		$('.floating').on('focus blur', function (e) {
		$(this).parents('.form-focus').toggleClass('focused', (e.type === 'focus' || this.value.length > 0));
		}).trigger('blur');
	}
	
	// Mobile menu sidebar overlay
	
	$('body').append('<div class="sidebar-overlay"></div>');
	$(document).on('click', '#mobile_btn', function() {
		$('main-wrapper').toggleClass('slide-nav');
		$('.sidebar-overlay').toggleClass('opened');
		$('html').addClass('menu-opened');
		return false;
	});
	
	$(document).on('click', '.sidebar-overlay', function() {
		$('html').removeClass('menu-opened');
		$(this).removeClass('opened');
		$('main-wrapper').removeClass('slide-nav');
	});
	
	$(document).on('click', '#menu_close', function() {
		$('html').removeClass('menu-opened');
		$('.sidebar-overlay').removeClass('opened');
		$('main-wrapper').removeClass('slide-nav');
	});

	 // tooltip

	 var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]')) 
	 var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) { return new bootstrap.Tooltip(tooltipTriggerEl) })
	
	// Add More Hours
	
    $(".hours-info").on('click','.trash', function () {
		$(this).closest('.hours-cont').remove();
		return false;
    });

    $(".add-hours").on('click', function () {
		
		var hourscontent = '<div class="row form-row hours-cont">' +
			'<div class="col-12 col-md-10">' +
				'<div class="row form-row">' +
					'<div class="col-12 col-md-6">' +
						'<div class="form-group">' +
							'<label>Start Time</label>' +
							'<select class="form-control">' +
								'<option>-</option>' +
								'<option>12.00 am</option>' +
								'<option>12.30 am</option>' + 
								'<option>1.00 am</option>' +
								'<option>1.30 am</option>' +
							'</select>' +
						'</div>' +
					'</div>' +
					'<div class="col-12 col-md-6">' +
						'<div class="form-group">' +
							'<label>End Time</label>' +
							'<select class="form-control">' +
								'<option>-</option>' +
								'<option>12.00 am</option>' +
								'<option>12.30 am</option>' +
								'<option>1.00 am</option>' +
								'<option>1.30 am</option>' +
							'</select>' +
						'</div>' +
					'</div>' +
				'</div>' +
			'</div>' +
			'<div class="col-12 col-md-2"><label class="d-md-block d-sm-none d-none">&nbsp;</label><a href="#" class="btn btn-danger trash"><i class="far fa-trash-alt"></i></a></div>' +
		'</div>';
		
        $(".hours-info").append(hourscontent);
        return false;
    });
	
	// Content div min height set
	
	function resizeInnerDiv() {
		var height = $(window).height();	
		var header_height = $(".header").height();
		var footer_height = $(".footer").height();
		var breadcrumb_height = $(".breadcrumb-bar").height();
		var setheight = height - breadcrumb_height - header_height;
		var trueheight = setheight - footer_height;
		$(".content").css("min-height", trueheight - 30);
	}
	
	if($('.content').length > 0 ){
		resizeInnerDiv();
	}

	$(window).resize(function(){
		if($('.content').length > 0 ){
			resizeInnerDiv();
		}
	});
	
	// Date Range Picker
	if($('.bookingrange').length > 0) {
		var start = moment().subtract(6, 'days');
		var end = moment();

		function booking_range(start, end) {
			$('.bookingrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
		}

		$('.bookingrange').daterangepicker({
			startDate: start,
			endDate: end,
			ranges: {
				'Today': [moment(), moment()],
				'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
				'Last 7 Days': [moment().subtract(6, 'days'), moment()],
				'Last 30 Days': [moment().subtract(29, 'days'), moment()],
				'This Month': [moment().startOf('month'), moment().endOf('month')],
				'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
			}
		}, booking_range);

		booking_range(start, end);
	}
	// Chat

	var chatAppTarget = $('.chat-window');
	(function() {
		if ($(window).width() > 991)
			chatAppTarget.removeClass('chat-slide');
		
		$(document).on("click",".chat-window .chat-users-list a.media",function () {
			if ($(window).width() <= 991) {
				chatAppTarget.addClass('chat-slide');
			}
			return false;
		});
		$(document).on("click","#back_user_list",function () {
			if ($(window).width() <= 991) {
				chatAppTarget.removeClass('chat-slide');
			}	
			return false;
		});
	})();
	
	// Preloader
	
	$(window).on('load', function () {
		if($('#loader').length > 0) {
			$('#loader').delay(350).fadeOut('slow');
			$('body').delay(350).css({ 'overflow': 'visible' });
		}
	})

    //scroll header
	
	$(window).on('scroll',function(){
		if ($(this).scrollTop()>150){
			$('.header').addClass('nav-fixed');
			$('.map-right').addClass('map-top');
		} else {
			$('.header').removeClass('nav-fixed');
			$('.map-right').removeClass('map-top');
		}
	});
	
	if($('#event-slider').length > 0 ){
		$('#event-slider').owlCarousel({
			items: 5,
	        margin: 30,
	        dots : false,
			nav: true,
			navText: [
				'<i class="fas fa-caret-left"></i>',
				'<i class="fas fa-caret-right"></i>'
			],
			loop: true,
			responsiveClass:true,
	        responsive: {
	          	0: {
	            	items: 1
	          	},
	          	768 : {
	            	items: 3
	          	},
	          	1170: {
	            	items: 4
	          	}
	        }
	    });
    }
	if($('#event-slider-1').length > 0 ){
		$('#event-slider-1').owlCarousel({
			items: 5,
	        margin: 30,
	        dots : false,
			nav: true,
			navText: [
				'<i class="fas fa-caret-left"></i>',
				'<i class="fas fa-caret-right"></i>'
			],
			loop: true,
			responsiveClass:true,
	        responsive: {
	          	0: {
	            	items: 1
	          	},
	          	768 : {
	            	items: 3
	          	},
	          	1170: {
	            	items: 4
	          	}
	        }
	    });
    }
	
	if($('#speaker-slider').length > 0 ){
		$('#speaker-slider').owlCarousel({
			items: 5,
	        margin: 30,
	        dots : false,
			nav: true,
			navText: [
				'<i class="fas fa-caret-left"></i>',
				'<i class="fas fa-caret-right"></i>'
			],
			loop: true,
			responsiveClass:true,
	        responsive: {
	          	0: {
	            	items: 1
	          	},
	          	768 : {
	            	items: 3
	          	},
	          	1170: {
	            	items: 4
	          	}
	        }
	    });
    }
	
	if($('#testimonial-slider').length > 0 ){
		$('#testimonial-slider').owlCarousel({
			items: 5,
	        margin: 30,
	        dots : false,
			nav: true,
			navText: [
				'<i class="fas fa-caret-left"></i>',
				'<i class="fas fa-caret-right"></i>'
			],
			loop: true,
			responsiveClass:true,
	        responsive: {
	          	0: {
	            	items: 1
	          	},
	          	768 : {
	            	items: 2
	          	},
	          	1170: {
	            	items: 3
	          	}
	        }
	    });
    }
	var bg = document.querySelectorAll(".bg-image");
    for(var i = 0; i < bg.length; i++) {
      var url = bg[i].getAttribute('data-image-src');
      bg[i].style.backgroundImage = "url('" + url + "')";
    }
	// Inspect keyCode
	/*
	$( window ).on( "load", function() {
		document.onkeydown = function(e) {
			if(e.keyCode == 1235) {
			 return false;
			}
			if(e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)){
			 return false;
			}
			if(e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)){
			 return false;
			}
			if(e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)){
			 return false;
			}
		
			if(e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0)){
			 return false;
			}      
		 };
		 
	});

	document.oncontextmenu = function() {return false;};
		$(document).mousedown(function(e){ 
		if( e.button == 22) { 
			return false; 
		} 
		return true; 
	});
	*/
	var carousel = document.querySelectorAll('.swiper-container');
    for(var i = 0; i < carousel.length; i++) {
		  var slider1 = carousel[i];
		  slider1.classList.add('swiper-container-' + i);
		  var controls = document.createElement('div');
		  controls.className = "swiper-controls";
		  var pagi = document.createElement('div');
		  pagi.className = "swiper-pagination";
		  var navi = document.createElement('div');
		  navi.className = "swiper-navigation";
		  var prev = document.createElement('div');
		  prev.className = "swiper-button swiper-button-prev";
		  var next = document.createElement('div');
		  next.className = "swiper-button swiper-button-next";
		  slider1.appendChild(controls);
		  controls.appendChild(navi);
		  navi.appendChild(prev);
		  navi.appendChild(next);
		  controls.appendChild(pagi);
		  var sliderEffect = slider1.getAttribute('data-effect') ? slider1.getAttribute('data-effect') : 'slide';
		  var sliderItems = slider1.getAttribute('data-items') ? slider1.getAttribute('data-items') : 3; // items in all devices
		  var sliderItemsXs = slider1.getAttribute('data-items-xs') ? slider1.getAttribute('data-items-xs') : 1; // start - 575
		  var sliderItemsSm = slider1.getAttribute('data-items-sm') ? slider1.getAttribute('data-items-sm') : Number(sliderItemsXs); // 576 - 767
		  var sliderItemsMd = slider1.getAttribute('data-items-md') ? slider1.getAttribute('data-items-md') : Number(sliderItemsSm); // 768 - 991
		  var sliderItemsLg = slider1.getAttribute('data-items-lg') ? slider1.getAttribute('data-items-lg') : Number(sliderItemsMd); // 992 - 1199
		  var sliderItemsXl = slider1.getAttribute('data-items-xl') ? slider1.getAttribute('data-items-xl') : Number(sliderItemsLg); // 1200 - end
		  var sliderItemsXxl = slider1.getAttribute('data-items-xxl') ? slider1.getAttribute('data-items-xxl') : Number(sliderItemsXl); // 1500 - end
		  var sliderSpeed = slider1.getAttribute('data-speed') ? slider1.getAttribute('data-speed') : 500;
		  var sliderAutoPlay = slider1.getAttribute('data-autoplay') !== 'false';
		  var sliderAutoPlayTime = slider1.getAttribute('data-autoplaytime') ? slider1.getAttribute('data-autoplaytime') : 5000;
		  var sliderAutoHeight = slider1.getAttribute('data-autoheight') === 'true';
		  var sliderMargin = slider1.getAttribute('data-margin') ? slider1.getAttribute('data-margin') : 30;
		  var sliderLoop = slider1.getAttribute('data-loop') === 'true';
		  var sliderCentered = slider1.getAttribute('data-centered') === 'true';
		  var swiper = slider1.querySelector('.swiper:not(.swiper-thumbs)');
		  var swiperTh = slider1.querySelector('.swiper-thumbs');
		  var sliderTh = new Swiper(swiperTh, {
			slidesPerView: 5,
			spaceBetween: 10,
			loop: false,
			threshold: 2,
			slideToClickedSlide: true
		  });
		  if (slider1.getAttribute('data-thumbs') === 'true') {
			var thumbsInit = sliderTh;
			var swiperMain = document.createElement('div');
			swiperMain.className = "swiper-main";
			swiper.parentNode.insertBefore(swiperMain, swiper);
			swiperMain.appendChild(swiper);
			slider1.removeChild(controls);
			swiperMain.appendChild(controls);
		  } else {
			var thumbsInit = null;
		  }
		  var slider = new Swiper(swiper, {
			on: {
			  beforeInit: function() {
				if(slider1.getAttribute('data-nav') !== 'true' && slider1.getAttribute('data-dots') !== 'true') {
				  controls.remove();
				}
				if(slider1.getAttribute('data-dots') !== 'true') {
				  pagi.remove();
				}
				if(slider1.getAttribute('data-nav') !== 'true') {
				  navi.remove();
				}
			  },
			  init: function() {
				if(slider1.getAttribute('data-autoplay') !== 'true') {
				  this.autoplay.stop();
				}
				this.update();
			  }
			},
			autoplay: {
			  delay: sliderAutoPlayTime,
			  disableOnInteraction: false
			},
			speed: sliderSpeed,
			slidesPerView: sliderItems,
			loop: sliderLoop,
			centeredSlides: sliderCentered,
			spaceBetween: Number(sliderMargin),
			effect: sliderEffect,
			autoHeight: sliderAutoHeight,
			grabCursor: true,
			resizeObserver: false,
			breakpoints: {
			  0: {
				slidesPerView: Number(sliderItemsXs)
			  },
			  576: {
				slidesPerView: Number(sliderItemsSm)
			  },
			  768: {
				slidesPerView: Number(sliderItemsMd)
			  },
			  992: {
				slidesPerView: Number(sliderItemsLg)
			  },
			  1200: {
				slidesPerView: Number(sliderItemsXl)
			  },
			  1400: {
				slidesPerView: Number(sliderItemsXxl)
			  }
			},
			pagination: {
			  el: carousel[i].querySelector('.swiper-pagination'),
			  clickable: true
			},
			navigation: {
			  prevEl: slider1.querySelector('.swiper-button-prev'),
			  nextEl: slider1.querySelector('.swiper-button-next'),
			},
			thumbs: {
			  swiper: thumbsInit,
			},
		  });
		}
})(jQuery);