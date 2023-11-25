jQuery(function($){
	"use strict";
	
	ts_register_carousel( null, $ );
	
	/* Elementor Lazy Load */
	if( $('.ts-elementor-lazy-load').length ){
		var ts_elementor_lazy_load_loaded_widget = []; /* prevent double load same widget */
		$(window).on('scroll ts_elementor_lazy_load', function(){
			var scroll_top = $(this).scrollTop();
			var window_height = $(this).height();
			var number_request = 0;
			$('.ts-elementor-lazy-load:not(.loaded)').each(function(i, e){
				if( $(e).offset().top > scroll_top + window_height + 600 ){
					return false;
				}
				var timeout = number_request * 200 + 10; /* dont show many requests same time */
				number_request++;
				var el = $(e);
				var widget_id = el.closest('.elementor-element[data-id]').attr('data-id');
				var widget = $('.elementor-element[data-id="' + widget_id + '"]'); /* may added many */
				widget.find('.ts-elementor-lazy-load').addClass('loaded');
				var post_id = el.parents('[data-elementor-id]').attr('data-elementor-id');
				
				setTimeout(function(){
					if( ts_elementor_lazy_load_loaded_widget.includes( widget_id ) ){
						return;
					}
					ts_elementor_lazy_load_loaded_widget.push( widget_id );
					
					$.ajax({
						type : "POST",
						timeout : 30000,
						url : themesky_params.ajax_uri,
						data : {action: 'ts_elementor_lazy_load', widget_id: widget_id, post_id: post_id},
						error: function(xhr,err){
							
						},
						success: function( response ){
							if( response ){
								widget.replaceWith( response.data );
								
								/* Generate slider */
								ts_register_carousel( null, $ );
								
								widget = $('.elementor-element[data-id="' + widget_id + '"]'); /* new widget */
								/* Countdown */
								if( widget.find('.counter-wrapper').length ){
									ts_counter( widget.find('.counter-wrapper') );
								}
								
								/* Blog Masonry */
								if( widget.find('.ts-blogs-wrapper.ts-masonry').length ){
									ts_register_masonry( null, $ );
								}
								
								/* Tabs shop more button */
								var el_tabs = widget.find('.ts-product-in-category-tab-wrapper');
								if( el_tabs.length ){
									ts_product_in_category_tab_shop_more_handle( el_tabs, el_tabs.data('atts') );
								}
								
								$(window).trigger('ts_elementor_lazy_load_loaded', [widget_id]);
							}
						}
					});
				}, timeout);
			});
		});
		
		if( $('#main .ts-elementor-lazy-load').length && $('#main .ts-elementor-lazy-load:first').offset().top < $(window).scrollTop() + $(window).height() ){
			$(window).trigger('ts_elementor_lazy_load');
		}
		
		if( $('.menu-item.ts-megamenu .ts-elementor-lazy-load').length ){
			$('.menu-item.ts-megamenu').on('mouseenter', function(){
				$(window).trigger('ts_elementor_lazy_load');
			});
		}
	}
	
	/*** Load Products In Category Tab ***/
	var ts_product_in_category_tab_data = [];
	
	/* Change tab */
	$(document).on('click', '.ts-product-in-category-tab-wrapper .column-tabs .tab-item, .ts-product-in-product-type-tab-wrapper .column-tabs .tab-item', function(){
		var element = $(this).parents('.ts-product-in-category-tab-wrapper');
		var is_product_type_tab = false;
		if( element.length == 0 ){
			element = $(this).parents('.ts-product-in-product-type-tab-wrapper');
			is_product_type_tab = true;
		}
		
		var element_top = element.offset().top;
		if( element_top > $(window).scrollTop() ){
			var admin_bar_height = $('#wpadminbar').length ? $('#wpadminbar').outerHeight() : 0;
			var sticky_height = $('.header-sticky.is-sticky').length ? $('.header-sticky.is-sticky').outerHeight() : 0;
			$('body, html').animate({
				scrollTop: element_top - sticky_height - admin_bar_height - 20
			}, 500);
		}
		
		if( $(this).hasClass('current') || element.find('.column-products').hasClass('loading') ){
			return;
		}
		
		element.removeClass('generated-slider');
		
		var element_id = element.attr('id');
		var atts = element.data('atts');
		if( !is_product_type_tab ){
			var product_cat = $(this).data('product_cat');
			var shop_more_link = $(this).data('link');
			var is_general_tab = $(this).hasClass('general-tab')?1:0;
		}
		else{
			var product_cat = atts.product_cats;
			var is_general_tab = 0;
			atts.product_type = $(this).data('product_type');
			element.find('.column-products').removeClass('recent sale featured best_selling top_rated mixed_order').addClass(atts.product_type);
			element.find('.view-more-wrapper').hide();
			$('.view-more-wrapper.' + $(this).data('id')).show();
		}
		
		if( !is_product_type_tab && element.find('a.shop-more-button').length > 0 ){
			element.find('a.shop-more-button').attr('href', shop_more_link);
		}
		
		element.find('.column-tabs .tab-item').removeClass('current');
		$(this).addClass('current');
		
		/* Check cache */
		var tab_data_index = element_id + '-' + product_cat.toString().split(',').join('-');
		if( is_product_type_tab ){
			tab_data_index += '-' + atts.product_type;
		}
		if( ts_product_in_category_tab_data[tab_data_index] != undefined ){
			element.find('.column-products .products').remove();
			element.find('.column-products').append( ts_product_in_category_tab_data[tab_data_index] ).hide().fadeIn(600);
			
			/* Shop more button handle */
			if( !is_product_type_tab ){
				ts_product_in_category_tab_shop_more_handle( element, atts );
			}
			
			/* Generate slider */
			ts_register_carousel( element.parent(), $ );
			
			return;
		}
		
		element.find('.column-products').addClass('loading');
		
		$.ajax({
			type : "POST",
			timeout : 30000,
			url : themesky_params.ajax_uri,
			data : {action: 'ts_get_product_content_in_category_tab', atts: atts, product_cat: product_cat, is_general_tab: is_general_tab},
			error: function(xhr,err){
				
			},
			success: function(response) {
				if( response ){
					element.find('.column-products .products').remove();
					element.find('.column-products').append( response ).hide().fadeIn(600);
					
					/* save cache */
					if( element.find('.counter-wrapper').length == 0 ){
						ts_product_in_category_tab_data[tab_data_index] = response;
					}
					else{
						ts_counter( element.find('.counter-wrapper') );
					}
					/* Shop more button handle */
					if( !is_product_type_tab ){
						ts_product_in_category_tab_shop_more_handle( element, atts );
					}
					/* Generate slider */
					ts_register_carousel( element.parent(), $ );
				}
				element.find('.column-products').removeClass('loading');
			}
		});
	});
	
	function ts_product_in_category_tab_shop_more_handle(element, atts){
		var hide_shop_more = element.find('.hide-shop-more').length;
		element.find('.hide-shop-more').remove();
		
		if( element.find('.tab-item.current').hasClass('general-tab') && atts.show_shop_more_general_tab == 0 ){
			hide_shop_more = true;
		}
		
		if( element.find('.products .product').length == 0 ){
			hide_shop_more = true;
		}
		
		if( atts.show_shop_more_button == 1 ){
			if( hide_shop_more ){
				element.find('.view-more-wrapper').addClass('hidden');
			}
			else{
				element.find('.view-more-wrapper').removeClass('hidden');
			}
		}
	}
	
	$('.ts-product-in-category-tab-wrapper').each(function(){
		var element = $(this);
		var atts = element.data('atts');
		ts_product_in_category_tab_shop_more_handle( element, atts );
	});
	
	/*** Blog ***/
	$(document).on('click', '.ts-blogs-wrapper a.load-more', function(){
		var element = $(this).parents('.ts-blogs-wrapper');
		var atts = element.data('atts');
		var is_masonry = typeof $.fn.isotope == 'function' && element.hasClass('ts-masonry') ? true : false;
		
		var button = $(this);
		if( button.hasClass('loading') ){
			return false;
		}
		
		button.addClass('loading');
		var paged = button.attr('data-paged');
		var total_pages = button.attr('data-total_pages');
		
		$.ajax({
			type : "POST",
			timeout : 30000,
			url : themesky_params.ajax_uri,
			data : {action: 'ts_blogs_load_items', paged: paged, atts : atts},
			error: function(xhr,err){
				
			},
			success: function(response) {
				if( paged == total_pages ){
					button.parent().remove();
				}
				else{
					button.removeClass('loading');
					button.attr('data-paged', ++paged);
				}
				if( response != 0 && response != '' ){
					if( is_masonry ){										
						element.find('.blogs').isotope('insert', $(response));
						setTimeout(function(){
							element.find('.blogs').isotope('layout');
						}, 500);
					}
					else{
						element.find('.blogs').append(response);
					}
					
					ts_register_carousel( element.parent(), $ );
				}
				else{ /* No results */
					button.parent().remove();
				}
			}
		});
		
		return false;
	});
	
	/* Copy coupon */
	$('.ts-copy-button').on('click', function(){
		if( typeof navigator.clipboard != 'undefined' ){
			navigator.clipboard.writeText( $(this).data('copy') );
			var this_button = $(this);
			this_button.addClass('loading');
			setTimeout(function(){
				this_button.removeClass('loading');
			}, 2000);
		}
	});
	
	/*** Counter ***/
	ts_counter( $('.product .counter-wrapper, .ts-countdown .counter-wrapper') );
	
	/*** Widgets ***/
	
	/* Product Categories widget */
	$('.ts-product-categories-widget .icon-toggle').on('click', function(){
		var parent_li = $(this).parent('li.cat-parent');
		if( !parent_li.hasClass('active') ){
			parent_li.addClass('active');
			parent_li.find('ul.children:first').slideDown();
		}
		else{
			parent_li.find('ul.children').slideUp();
			parent_li.removeClass('active');
			parent_li.find('li.cat-parent').removeClass('active');
		}
	});
	
	$('.ts-product-categories-widget').each(function(){
		var element = $(this);
		element.find('ul.children').parent('li').addClass('cat-parent');
		element.find('li.current.cat-parent > .icon-toggle').trigger('click');
		element.find('li.current').parents('ul.children').siblings('.icon-toggle').trigger('click');
	});
	
	$('.ts-product-categories-widget .all-categories > span').on('click', function(){
		$(this).toggleClass('active');
		$(this).siblings().slideToggle();
	});
	
	/* Product Filter By Availability */
	$('.product-filter-by-availability-wrapper > ul input[type="checkbox"]').on('change', function(){
		$(this).parent('li').siblings('li').find('input[type="checkbox"]').attr('checked', false);
		var val = '';
		if( $(this).is(':checked') ){
			val = $(this).val();
		}
		var form = $(this).closest('ul').siblings('form');
		if( val != '' ){
			form.find('input[name="stock"]').val(val);
		}
		else{
			form.find('input[name="stock"]').remove();
		}
		form.submit();
	});
	
	/* Product Filter By Price */
	$('.product-filter-by-price-wrapper li').on('click', function(){
		var form = $(this).parent().siblings('form');
		if( !$(this).hasClass('chosen') ){
			var min_price = $(this).data('min');
			var max_price = $(this).data('max');
			
			if( min_price !== '' ){
				form.find('input[name="min_price"]').val(min_price);
			}
			else{
				form.find('input[name="min_price"]').remove();
			}
			if( max_price !== '' ){
				form.find('input[name="max_price"]').val(max_price);
			}
			else{
				form.find('input[name="max_price"]').remove();
			}
		}
		else{
			form.find('input[name="min_price"]').remove();
			form.find('input[name="max_price"]').remove();
		}
		form.submit();
	});
	
	/* Product Filter By Brand */
	$('.product-filter-by-brand-wrapper ul input[type="checkbox"]').on('change', function(){
		var wrapper = $(this).parents('.product-filter-by-brand-wrapper');
		var query_type = wrapper.find('> .query-type').val();
		var checked = $(this).is(':checked');
		var val = new Array();
		if( query_type == 'or' ){
			wrapper.find('ul input[type="checkbox"]').attr('checked', false);
			if( checked ){
				$(this).off('change');
				$(this).attr('checked', true);
				val.push( $(this).val() );
			}
		}
		else{
			wrapper.find('ul input[type="checkbox"]:checked').each(function(index, ele){
				val.push( $(ele).val() );
			});
		}
		val = val.join(',');
		var form = wrapper.find('form');
		if( val != '' ){
			form.find('input[name="product_brand"]').val( val );
		}
		else{
			form.find('input[name="product_brand"]').remove();
		}
		form.submit();
	});
	
	/* Video */
	$(document).on('click', '.ts-videos-elementor-widget .elementor-wrapper:not(.elementor-open-lightbox) .elementor-custom-embed-image-overlay', function(){
		var wrapper = $(this).closest('.elementor-wrapper');
		var video = wrapper.find('.elementor-video');
		$(this).remove();
		if( wrapper.hasClass('e-hosted-video') ){
			video[0].play();
		}
		else{
			var video_url = video.attr('src');
			var symbol = video_url.indexOf('?') > -1 ? '&' : '?';
			video_url += symbol + 'autoplay=1';
			if( $('body').hasClass('e--ua-chrome') ){
				video_url += '&mute=1&muted=1';
			}
			video.attr('src', video_url);
		}
	});
});

function zeroise( str, max ){
	str = str.toString();
	return str.length < max ? zeroise('0' + str, max) : str;
}

function ts_counter( elements ){
	if( elements.length ){
		var interval = setInterval(function(){
			elements.each(function(index, element){
				var wrapper = jQuery(element);
				var second = parseInt( wrapper.find('.seconds .number').text() );
				if( second > 0 ){
					second--;
					second = ( second < 10 )? zeroise(second, 2) : second.toString();
					wrapper.find('.seconds .number').text(second);
					return;
				}
				
				var delta = 0;
				var time_day = 60 * 60 * 24;
				var time_hour = 60 * 60;
				var time_minute = 60;
				
				var day = parseInt( wrapper.find('.days .number').text() );
				var hour = parseInt( wrapper.find('.hours .number').text() );
				var minute = parseInt( wrapper.find('.minutes .number').text() );
				
				if( day != 0 || hour != 0  || minute != 0 || second != 0 ){
					delta = (day * time_day) + (hour * time_hour) + (minute * time_minute) + second;
					delta--;
					
					day = Math.floor(delta / time_day);
					delta -= day * time_day;
					
					hour = Math.floor(delta / time_hour);
					delta -= hour * time_hour;
					
					minute = Math.floor(delta / time_minute);
					delta -= minute * time_minute;
					
					second = delta > 0?delta:0;
					
					day = ( day < 10 )? zeroise(day, 2) : day.toString();
					hour = ( hour < 10 )? zeroise(hour, 2) : hour.toString();
					minute = ( minute < 10 )? zeroise(minute, 2) : minute.toString();
					second = ( second < 10 )? zeroise(second, 2) : second.toString();
					
					wrapper.find('.days .number').text(day);
					wrapper.find('.hours .number').text(hour);
					wrapper.find('.minutes .number').text(minute);
					wrapper.find('.seconds .number').text(second);
				}
				
			});
		}, 1000);
	}
}

class TS_Carousel{
	register( $scope, $ ){
		var carousel = this;
		
		/* [wrapper selector, slider selector, slider options (remove dynamic columns at last)] */
		var data = [
			['.ts-product-wrapper', '.products', { breakpoints:{0:{slidesPerView:1},320:{slidesPerView:2},520:{slidesPerView:3},700:{slidesPerView:4},910:{slidesPerView:5}} }]
			,['.ts-product-deals-wrapper', '.products', { breakpoints:{0:{slidesPerView:1},320:{slidesPerView:2},550:{slidesPerView:3},700:{slidesPerView:4},910:{slidesPerView:5}} }]
			,['.ts-product-category-wrapper', '.products', { breakpoints:{0:{slidesPerView:2},340:{slidesPerView:3},480:{slidesPerView:4},650:{slidesPerView:5},700:{slidesPerView:6},900:{slidesPerView:7}} }]
			,['.ts-product-brand-wrapper', '.content-wrapper', { breakpoints:{0:{slidesPerView:1},300:{slidesPerView:2},690:{slidesPerView:3}} }]
			,['.ts-products-widget-wrapper', null, { spaceBetween: 10, breakpoints:{0:{slidesPerView:1}} }]
			,['.ts-blogs-wrapper', '.content-wrapper > .blogs', { breakpoints:{0:{slidesPerView:1},550:{slidesPerView:2},690:{slidesPerView:3}} }]
			,['.ts-logo-slider-wrapper', '.items', { breakpoints:{0:{slidesPerView:1},300:{slidesPerView:2},400:{slidesPerView:3},640:{slidesPerView:4},840:{slidesPerView:5},950:{slidesPerView:6},1150:{slidesPerView:7}} }]
			,['.ts-team-members', '.items', { breakpoints:{0:{slidesPerView:1},350:{slidesPerView:2},590:{slidesPerView:3},650:{slidesPerView:4}} }]
			,['.ts-instagram-wrapper', '.items', { spaceBetween: 0, breakpoints: {0:{slidesPerView:1},300:{slidesPerView:2},400:{slidesPerView:3},580:{slidesPerView:4},700:{slidesPerView:5},840:{slidesPerView:6}} }]
			,['.ts-testimonial-wrapper', '.items', { breakpoints:{0:{slidesPerView:1},520:{slidesPerView:2},980:{slidesPerView:3}} }]
			,['.ts-blogs-widget-wrapper', null, { spaceBetween: 10, breakpoints: {0:{slidesPerView:1}} }]
			,['.ts-recent-comments-widget-wrapper', null, { spaceBetween: 10, breakpoints: {0:{slidesPerView:1}} }]
			,['.ts-product-in-category-tab-wrapper, .ts-product-in-product-type-tab-wrapper', '.products', { breakpoints:{0:{slidesPerView:1},320:{slidesPerView:2},610:{slidesPerView:3},700:{slidesPerView:4}} }]
			,['.ts-videos-elementor-widget', '.videos', { breakpoints: {0:{slidesPerView:1},600:{slidesPerView:2}} }]
			,['.ts-blogs-wrapper .thumbnail.gallery', 'figure', { autoplay: true, effect: 'fade', spaceBetween: 10, simulateTouch: false, allowTouchMove: false, breakpoints:{0:{slidesPerView:1}} }]
		];
		
		$.each(data, function(index, value){
			carousel.run( value, $ );
		});
	}
	
	run( data, $ ){
		$(data[0]).each(function(index){
			if( ! $(this).hasClass('ts-slider') || $(this).hasClass('generated-slider') ){
				return;
			}
			$(this).addClass('generated-slider');
			
			var element = $(this);
			var show_nav = typeof element.attr('data-nav') != 'undefined' && element.attr('data-nav') == 1?true:false;
			var show_dots = typeof element.attr('data-dots') != 'undefined' && element.attr('data-dots') == 1?true:false;
			var show_scrollbar = typeof element.attr('data-scrollbar') != 'undefined' && element.attr('data-scrollbar') == 1?true:false;
			var auto_play = typeof element.attr('data-autoplay') != 'undefined' && element.attr('data-autoplay') == 1?true:false;
			var columns = typeof element.attr('data-columns') != 'undefined'?parseInt(element.attr('data-columns')):5;
			var disable_responsive = typeof element.attr('data-disable_responsive') != 'undefined' && element.attr('data-disable_responsive') == 1?true:false;
			var prev_nav_text = typeof element.attr('data-prev_nav_text') != 'undefined'?element.attr('data-prev_nav_text'):'';
			var next_nav_text = typeof element.attr('data-next_nav_text') != 'undefined'?element.attr('data-next_nav_text'):'';
				
			if( typeof data[1] != 'undefined' && data[1] != null ){
				var swiper = element.find(data[1]);
			}
			else{
				var swiper = element;
			}
			
			if( swiper.find('> *').length <= 1 ){
				element.removeClass('loading').find('.loading').removeClass('loading');
				return;
			}
			
			var unique_class = 'swiper-' + Math.floor(Math.random() * 10000) + '-' + index;
			
			swiper.addClass('swiper ' + unique_class);
			swiper.find('> *').addClass('swiper-slide');
			swiper.wrapInner('<div class="swiper-wrapper"></div>');
			
			if( $('body').hasClass('rtl') ){
				swiper.attr('dir', 'rtl');
			}
			
			var slider_options = {
					loop: true
					,spaceBetween: 0
					,breakpointsBase: 'container'
					,breakpoints:{0:{slidesPerView:1},320:{slidesPerView:2},580:{slidesPerView:3},810:{slidesPerView:columns}}
					,on: {
						init: function(){
							element.removeClass('loading').find('.loading').removeClass('loading');
							$(window).trigger('ts_slider_middle_navigation_position', [swiper]);
						}
						,resize: function(){
							$(window).trigger('ts_slider_middle_navigation_position', [swiper]);
						}
					}
				};
			
			if( show_nav ){
				swiper.append('<div class="swiper-button-prev">' + prev_nav_text + '</div><div class="swiper-button-next">' + next_nav_text + '</div>');
				
				slider_options.navigation = {
					prevEl: '.swiper-button-prev'
					,nextEl: '.swiper-button-next'
				};
			}
			
			if( show_dots ){
				swiper.append('<div class="swiper-pagination"></div>');
				
				slider_options.pagination = {
					el: '.swiper-pagination'
					,clickable: true
				};
			}
			
			if( show_scrollbar ){
				swiper.append('<div class="swiper-scrollbar"></div>');
				
				slider_options.scrollbar = {
					el: '.swiper-scrollbar'
					,draggable: true
				};
				
				slider_options.loop = false;
			}
			
			if( auto_play ){
				slider_options.autoplay = {
					delay: 5000
					,disableOnInteraction: false
					,pauseOnMouseEnter: true
				};
			}
			
			if( typeof data[2] != 'undefined' && data[2] != null ){
				$.extend( slider_options, data[2] );
				
				if( typeof data[2].breakpoints != 'undefined' ){ /* change breakpoints => add dynamic columns at last */
					switch( data[0] ){
						case '.ts-product-deals-wrapper':
						case '.ts-blogs-wrapper':
						case '.ts-product-wrapper':
							slider_options.breakpoints[1200] = {slidesPerView:columns};
							if( element.hasClass('layout-list') ){
								 slider_options.breakpoints = {0:{slidesPerView:columns}};
							}
						break;
						case '.ts-product-brand-wrapper':
							slider_options.breakpoints[1000] = {slidesPerView:columns};
						break;
						case '.ts-product-category-wrapper':
							slider_options.breakpoints[1000] = {slidesPerView:columns};
							if( element.hasClass('show-icon') ){
								 slider_options.breakpoints = {0:{slidesPerView:2},320:{slidesPerView:3},410:{slidesPerView:4},650:{slidesPerView:5},700:{slidesPerView:6},800:{slidesPerView:7},900:{slidesPerView:8},1100:{slidesPerView:columns}};
							}
							if( element.hasClass('style-horizontal') ){
								 slider_options.breakpoints = {0:{slidesPerView:1},320:{slidesPerView:2},600:{slidesPerView:3},700:{slidesPerView:4},950:{slidesPerView:5},1091:{slidesPerView:columns}};
							}
							if( element.hasClass('columns-10') && element.hasClass('style-vertical') ){
								 slider_options.breakpoints = {0:{slidesPerView:2},320:{slidesPerView:3},600:{slidesPerView:4},700:{slidesPerView:6},950:{slidesPerView:8},1200:{slidesPerView:columns}};
							}
						break;
						case '.ts-team-members':
							slider_options.breakpoints[800] = {slidesPerView:columns};
						break;
						case '.ts-testimonial-wrapper':
							slider_options.breakpoints[1200] = {slidesPerView:columns};
						break;
						case '.ts-instagram-wrapper':
							slider_options.breakpoints[1200] = {slidesPerView:columns};
						break;
						case '.ts-product-in-category-tab-wrapper, .ts-product-in-product-type-tab-wrapper':
							slider_options.breakpoints[1200] = {slidesPerView:columns};
							if( element.hasClass('item-layout-list') ){
								slider_options.breakpoints = {0:{slidesPerView:1},620:{slidesPerView:2},1200:{slidesPerView:columns}};
							}
						break;
						case '.ts-videos-elementor-widget':
							slider_options.breakpoints[700] = {slidesPerView:columns};
							if( element.hasClass('partial-view') ){
								slider_options.centeredSlides = true;
							}
						break;
						default:
					}
				}
			}
			
			if( element.hasClass('use-logo-setting') ){ /* Product Brands - Logos */
				var break_point = element.data('break_point');
				var item = element.data('item');
				if( break_point.length > 0 ){
					slider_options.breakpoints = {};
					for( var i = 0; i < break_point.length; i++ ){
						slider_options.breakpoints[break_point[i]] = {slidesPerView: item[i]};
					}
				}
			}
			
			if( disable_responsive ){
				if( columns > 2){
					slider_options.breakpoints = {0:{slidesPerView:1},320:{slidesPerView:2},520:{slidesPerView:columns}};
				}
				else{
					slider_options.breakpoints = {0:{slidesPerView:columns}};
				}
			}
			
			new Swiper( '.' + unique_class, slider_options );
		});
	}
}

function ts_register_carousel( $scope, $ ){
	var carousel = new TS_Carousel();
	carousel.register( $scope, $ );
}

function ts_register_masonry( $scope, $ ){
	if( typeof $.fn.isotope == 'function' ){
		setTimeout(function(){
			/* Blog */
			$('.ts-blogs-wrapper.ts-masonry .blogs').isotope();
			$('.ts-blogs-wrapper.ts-masonry').removeClass('loading');
		}, 200);
	}
}

jQuery(window).on('elementor/frontend/init', function(){
	var elements = ['ts-products', 'ts-product-deals', 'ts-product-categories', 'ts-product-brands', 'ts-blogs'
					,'ts-logos', 'ts-team-members', 'ts-testimonial', 'ts-instagram'
					,'ts-products-in-category-tabs', 'ts-products-in-product-type-tabs', 'ts-videos'
					,'wp-widget-ts_products', 'wp-widget-ts_blogs', 'wp-widget-ts_recent_comments', 'wp-widget-ts_instagram'];
	jQuery.each(elements, function(index, name){
		elementorFrontend.hooks.addAction( 'frontend/element_ready/' + name + '.default', ts_register_carousel );
	});
	
	elements = ['ts-blogs'];
	jQuery.each(elements, function(index, name){
		elementorFrontend.hooks.addAction( 'frontend/element_ready/' + name + '.default', ts_register_masonry );
	});
});