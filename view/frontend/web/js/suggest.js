define([
	'jquery',
	'magepowOwl'
	], function ($) {
		'use strict';

		$.widget('magepow.suggest', {
			options: {
				itemsNumber: 0
			},

			_create: function () {
				var options = this.options;
				var items0 = (1 > options.itemsNumber) ? options.itemsNumber : 1;
				var items600 = (2 > options.itemsNumber) ? options.itemsNumber : 2;
				var items1000 = (3 > options.itemsNumber) ? options.itemsNumber : 3;

				$('.ajax-cart-owl-carousel').owlCarousel({
					nav: true,
					margin: 25,
					stagePadding: 25,
					autoplay: true,
					autoplayHoverPause: true,
					loop: true,
					onInitialized: this._hideLoading,
					responsive: {
						0: {
							items: items0
						},
						600: {
							items: items600
						},
						1000: {
							items: items1000
						}
					}
				});
			},

			_hideLoading: function () {
				$('.ajax-owl-loading').hide();
				$(window).resize();
			}
		});

		return $.magepow.suggest;
	});