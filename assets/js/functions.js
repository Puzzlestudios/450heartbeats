( function( $ ) {
	
	$.Heartbeats = function () {
		this.scope 			= $(document);
		this.init();
	};

	$.Heartbeats.prototype = {

		init: function() {

			this.initMenuToggle();
			this.initJetListingGrid();
			this.initFontAnimation();
			this.initSelect2();
		},
		
		initMenuToggle: function(){
			$('.uc_animated_hamburger_icon').bind('click', function(ev){
				ev.preventDefault();
				$('.uc_animated_hamburger_icon').toggleClass("pushed");
			  	$('.hb-menu-section').toggleClass("menu-toggled");
			  	$('body').toggleClass("navigation-toggled");
			});
		},
		
		initJetListingGrid: function(){
			var hb = this;
			
			hb.initJetListingGridAnimated();
			
			$(document).on({
				'jet-filter-content-rendered': function(){
					hb.initJetListingGridAnimated();
				},
				ajaxComplete: function(e){
					hb.initJetListingGridAnimated();
				},
				scroll: function(){
					hb.initJetListingGridAnimated();
				}
			});
			
			if(document.getElementsByClassName('jet-remove-all-filters__button').length > 0){ 
				$('body').on('click', '.jet-remove-all-filters__button', function(ev){
					$("select.jet-select__control").select2("destroy");
					hb.initSelect2();
				});
			}
		},
		
		initJetListingGridAnimated: function(){
			
			var $items = $(".jet-listing-grid__item");
			
			if($items.length == 0){ return; }
			
			if($items.parents().closest('.elementor-widget-jet-listing-grid').hasClass('.showcases-home-loop')){ return; }
			
			console.log($items.parents().closest('.elementor-widget-jet-listing-grid').hasClass('.showcases-home-loop'));
			
			var hb = this;
			$items.each(function(i) {
				i %= 3;
				var $elem = $(this);
				
		   		if (hb.isInView( $elem )){
			       	$elem.delay(200 * i).queue(function() {
						$elem.addClass("show");
				   	});
			    }
			    
			});

		},
		
		initFontAnimation: function(){
			var hb = this;
			if(document.getElementsByClassName('font-animation').length == 0){ return; }
			
			gsap.registerPlugin(ScrollTrigger);
			gsap.core.globals("ScrollTrigger", ScrollTrigger);
			var textToFill = document.querySelectorAll('.font-animation .elementor-widget-container');
		
			textToFill.forEach(function(e) {
			  	var words = e.textContent.split(" ");
			  	e.innerHTML = '';
			  	for (var i=0; i < words.length; i++) {
			    		var word = '<div>'+words[i]+'</div>';
			    		e.innerHTML += word;
			  	}
			});
		
			document.querySelectorAll('.font-animation .elementor-widget-container div').forEach(function(e) {
				var letters = e.textContent.split('');
			  	e.innerHTML = '';
			  	for (var i=0; i < letters.length; i++) {
					var letter = letters[i] === ' ' ? '&nbsp;' : '<span>'+letters[i]+'</span>';
					e.innerHTML += letter;
			  	}
			});
		
			gsap.to('.font-animation .elementor-widget-container span', {
			    	stagger: 0.6,
			    	className: '+=fill',
			    	scrollTrigger: {
					trigger: '.font-animation .elementor-widget-container span',
			        	start: 'top 90%',
			        	scrub: 0.5,
			        	markers: false,
			    	}
			});
		
			/* FADEIN TO SCROLL */
		
			let itemScroll = document.querySelectorAll('.item-scroll');
		
			if ('IntersectionObserver' in window) {
			  	let config = {
			        	root: null,
			        	rootMargin: '0px',
			        	threshold: 0
		      	};
		
		  		let observer = new IntersectionObserver(onChange, config);
			  	itemScroll.forEach(item => observer.observe(item));
		
			  	function onChange(changes, observer) {
			    		changes.forEach(change => {
			      		if (change.intersectionRatio > 0) {
			        			hb.loadItem(change.target);
			        			observer.unobserve(change.target);
			      		}
			    		});
			  	}
			} else {
			  itemScroll.forEach(item => hb.loadItem(item));
			}
		},
		
		initSelect2: function(){
			var hb = this;
			
			var selector = $('select.jet-select__control').select2({
				theme: 'material',
				minimumResultsForSearch: -1,
				allowClear: true,
				placeholder: "Auswählen",
			});
			$('select.jet-select__control').off('select2:clear').on('select2:clear', (e) => {
				$(e.currentTarget).val("");
				hb.select2ToggleActiveClass(e);
				
				$('select.jet-select__control').select2("close");
		 	}).on('select2:unselecting', function() {
			    $(this).data('unselecting', true);
			}).on('select2:opening', function(e) {
			    if ($(this).data('unselecting')) {
			        $(this).removeData('unselecting');
			        e.preventDefault();
			    }
			}).on('select2:select', function (e) {
			    hb.select2ToggleActiveClass(e);
			    //console.log('change')
			});
		},
		
		select2ToggleActiveClass: function(e){
			var data = e.params.data;
		    var $select2container = $(e.currentTarget).data('select2').$container;
		    if(data.id != '' && !$select2container.hasClass('active-select') && data.selected == true){
		    		$select2container.addClass('active-select');
		    }else if(data.selected != true){
		    		$select2container.removeClass('active-select');
		    }
		},
		
		loadItem: function(item){
			item.classList.add('animate');
		},
		
		
		isInView: function(elem){
			var elementTop 		= $(elem).offset().top - ($(elem).outerHeight() - ($(elem).outerHeight() * 0.1 ));
		  	var elementBottom 	= elementTop + $(elem).outerHeight();
			
		  	var viewportTop 		= $(window).scrollTop();
		  	var viewportBottom 	= viewportTop + $(window).height();
			
		  	return elementBottom > viewportTop && elementTop < viewportBottom;
		}
	}


})(jQuery);

jQuery(document).ready(function ($) {
	$.Heartbeats = new $.Heartbeats();
});