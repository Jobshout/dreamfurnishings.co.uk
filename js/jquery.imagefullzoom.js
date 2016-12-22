/*!
 * jQuery Image Zoom v1
 * http://richard.parnaby-king.co.uk
 *
 * Copyright 2014 Richard Parnaby-King
 */
(function($){
	"use strict";
	//global options
	var opts = {
		scaleAmount:1.2, //(float) amount to zoom in / out by
		zoomInText:'+', //(string) Text to show in span for zoom in button
		zoomOutText:'-'  //(string) Text to show in span for zoom out button
	};
	$.fn.imageZoom = function(options){
		opts = $.extend(opts,options);
		this.each(function() {
			//local variables
			var $this = $(this),
				link = $this.find('a'),
				bigImage = link.attr('href'),
				image = $this.find('img'),
				sWidth = image.width(),
				sHeight = image.height(),
				currentScale = 1,
				mouseDown = false,
				ix,iy;
			link.on('click.imageZoom', function(e){e.preventDefault();});
			$this.addClass('imageZoom-holder');
			$this.width(sWidth);
			$this.height(sHeight);	
			image.attr('src',bigImage);
			image.width(sWidth);
			image.height(sHeight);
			
			
			//add buttons to image
			$this.append('<div class="imageZoom-buttons"><span class="zoomInButton">'+opts.zoomInText+'</span><span class="zoomOutButton">'+opts.zoomOutText+'</span></div>');
			var buttons = $this.find('.imageZoom-buttons');
			
			//when click on zoom in/out buttons, scale image accordingly
			buttons.find('.zoomInButton').on('click.imageZoom',function(){
				currentScale *= opts.scaleAmount;
				image.css({
					'-webkit-transform' : 'scale(' + currentScale + ')',
					'-moz-transform'    : 'scale(' + currentScale + ')',
					'-ms-transform'     : 'scale(' + currentScale + ')',
					'-o-transform'      : 'scale(' + currentScale + ')',
					'transform'         : 'scale(' + currentScale + ')'
				});
			});
			buttons.find('.zoomOutButton').on('click.imageZoom',function(){
				currentScale /= opts.scaleAmount;
				image.css({
					'-webkit-transform' : 'scale(' + currentScale + ')',
					'-moz-transform'    : 'scale(' + currentScale + ')',
					'-ms-transform'     : 'scale(' + currentScale + ')',
					'-o-transform'      : 'scale(' + currentScale + ')',
					'transform'         : 'scale(' + currentScale + ')'
				});
			});
			
			//when click/drag on image, move image
			image.on('mousedown',function(e){
				ix = e.pageY,
				iy = e.pageX;
				mouseDown = true;
				return false;
			});
			image.on('mouseup',function(e){
				mouseDown = false;
			});
			image.on('mousemove',function(e){
				if(mouseDown == true) {
					var offsetLeft = parseInt(	image.css('left').replace(/[^0-9\.\-]+/,'')	|0	) - (	iy - e.pageX	),
						offsetTop = parseInt(	image.css('top').replace(/[^0-9\.\-]+/,'')	|0	) - (	ix - e.pageY	);
				
					//move image
					image.css(	'left',	offsetLeft	);
					image.css(	'top',	offsetTop	);
					iy = e.pageX;
					ix = e.pageY;
				}
			});
		});
	};
})(jQuery);
