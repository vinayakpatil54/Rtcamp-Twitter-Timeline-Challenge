(function($){	
	//declare an array to store the keys of the options object
	$.dssOptionsList = [];
	
	//main function
	$.fn.divSlideShow = function(customOptions){	
		//default options
		var options = 
		{ 
			width:200, 
			height:100, 
			arrow:"begin", 			
			delay:5000, 
			loop:1,
			leftArrowClass:"",
			rightArrowClass:"",
			//leftArrowClass:"ui-icon ui-icon-circle-triangle-w",
			//rightArrowClass:"ui-icon ui-icon-circle-triangle-e",
			slideContainerClass:"", 
			controlClass:"",
			controlActiveClass:"",
			//controlClass:"ui-state-default ui-corner-all", 
			//controlActiveClass:"ui-state-hover",
			controlHoverClass:"",
			controlContainerClass:"",
			separatorClass:""
			
		};
		//override options
		if(customOptions)
			$.extend(options, customOptions);
			
		//store the keys of the options object
		for(var key in options)
			$.dssOptionsList.push(key);
		
		//limit loop amount
		if(options.loop > 30)
			options.loop = 30;
		return this.each(function(){
			for(key in options)
				$(this).attr(key, options[key])
			//make slideshow
			$.divSlideShow(this);
		});
	};
	
	$.divSlideShow = function(slideShow)
	{		
		var options = $.divSlideShow.getOptionsObject(slideShow)		
		
		var numSlide = $(slideShow).children('.slide').length;			
		$(slideShow).css( { 'width':options.width ,  'overflow':'hidden', 'display':'block' } );		
		
		//wrap all slides with inner conatiner
		$(slideShow).children()			
			.wrap('<div class="dssSlide"></div>')			
		$(slideShow).children('.dssSlide').wrapAll('<div class="dssSlideContainer" page=0 max='+numSlide+'/>');
		$(slideShow).find('.dssSlide')
			.css( {'float':'left', 'width':options.width, 'height':options.height, 'overflow-y':'auto' } );

		//add controls		
		var leftArrow = '<div class="dssControl" direction=-1><span class="'+options.leftArrowClass+'">&lt</span></<div>';
		var rightArrow = '<div class="dssControl" direction=1><span class="'+options.rightArrowClass+'">&gt;</span></<div>';
		$(slideShow).append('<div class="dssSeparator"></div>').find('.dssSeparator').addClass(options.separatorClass);		
		
		if( options.arrow == "begin" )
		{
			$(slideShow).append(leftArrow);
			$(slideShow).append(rightArrow);
		}
		if( options.arrow == "split" )
			$(slideShow).append(leftArrow);
				
		for(var i = 0; i < numSlide; i++) //number buttons
			$(slideShow).append('<div class="dssControl" page='+i+'>'+(i+1)+'</div>');
			
		if( options.arrow == "split" )
			$(slideShow).append(rightArrow);	
		if( options.arrow == "end" )			
		{
			$(slideShow).append(leftArrow);
			$(slideShow).append(rightArrow);
		}
		
		//styles for inner container and controls
		$(slideShow).find('.dssSlideContainer').css( {'width':options.width * numSlide, 'height':options.height, 'overflow':'hidden'} ).addClass(options.slideContainerClass);
		$(slideShow).find('.dssControl')
			.addClass(options.controlClass)
			.css('float','left')
			.hover(function(){ $(this).toggleClass(options.controlHoverClass);})
			.wrapAll('<div class="dssControlContainer"/>');
		$(slideShow).find('.dssControlContainer')
			.css({'height':'100%', 'overflow':'hidden'})
			.addClass(options.controlContainerClass);
		
		//auto-slide: queue a sequence of animation with delay
		for(i = 1; i < Math.floor(numSlide*options.loop); i++)
		{
			$(slideShow).find('.dssSlideContainer').delay(options.delay);				
			$.divSlideShow.slideTo(slideShow, i % numSlide, true);
		}
		//initialize controls look
		$.divSlideShow.manageControls(slideShow, 0);
		
		//controls click handler
		$(slideShow).find('.dssControl').click(function(){				
			//get max pages
			var max = $(slideShow).find('.dssSlideContainer').children().length;
			
			//get target page
			var dir = parseInt($(this).attr('direction'));
			if(dir) //left or right arrow
			{
				var currentPage = parseInt($(slideShow).find('.dssSlideContainer').attr('page'));
				var gotoPage = (currentPage + dir < max && currentPage + dir >= 0) ? currentPage + dir : currentPage;	
			}				
			else //numbers
				var gotoPage = parseInt($(this).attr('page'));
			
			//go~
			$.divSlideShow.slideTo(slideShow, gotoPage);
		});		
	};
	

	$.divSlideShow.slideTo = function(slideShow, gotoPage, queue)
	{
		//remove auto-slide
		if( !queue )
			$(slideShow).find('.dssSlideContainer').clearQueue();
		
		var options = $.divSlideShow.getOptionsObject(slideShow)
		
		//validate gotoPage
		var max = $(slideShow).find('.dssSlideContainer').children().length;
		if(gotoPage >= max)
			gotoPage = max - 1;
		if(gotoPage < 0)
			gotoPage = 0;
		
		//get width
		var width = $(slideShow).find('.dssSlideContainer .dssSlide').width();		
		
		//manage control look and store current page as attribute, to be executed just before animation
		$(slideShow).find('.dssSlideContainer').queue(function(){
			$.divSlideShow.manageControls(slideShow, gotoPage);
			$(this).attr('page', gotoPage);
			$(this).dequeue();
		});
		
		//animate
		$(slideShow).find('.dssSlideContainer').animate(	{'margin-left':-gotoPage*width}	);		
		
	};
	
	$.divSlideShow.getOptionsObject = function(slideShow)
	{
		var options = {};
		var optionsList = $.dssOptionsList;
		for (var i in optionsList)
		{
			var attribute = $(slideShow).attr(optionsList[i]);
			if( isNaN( parseInt(attribute) ) )
				options[optionsList[i]] = attribute;
			else
				options[optionsList[i]] = parseInt(attribute);			
		}
		return options;
	}
		

	$.divSlideShow.manageControls = function(slideShow, page)
	{
		var options = $.divSlideShow.getOptionsObject(slideShow)	
		var max = $(slideShow).find('.dssSlideContainer').children().length;
		
		$(slideShow).find('.dssControl').each(function(){
			if( $(this).attr('direction') == '1')//right arrow
			{
				/*if( page < max - 1 )
					$(this).css('visibility','visible');
				else 
					$(this).css('visibility','hidden');*/
			}
			else if( $(this).attr('direction') == '-1')//left arrow
			{
				/*if( page > 0 )
					$(this).css('visibility','visible');
				else 
					$(this).css('visibility','hidden');*/
			}
			else//number
			{				
				if( $(this).attr('page') != page )
					$(this).toggleClass(options.controlActiveClass, false);
				else 
					$(this).toggleClass(options.controlActiveClass, true); 
			}
		});
	};

})(jQuery);