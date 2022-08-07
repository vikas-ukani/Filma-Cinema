/* FWDUVPATB */
(function (window){
var FWDUVPATB = function(
		controller
		){
		var _s = this;
		var prototype = FWDUVPATB.prototype;

		_s.useHEX = controller.useHEX;
		_s.main = controller.prt;
		_s.timeBackgroundColor = controller._d.atbTimeBackgroundColor;
		_s.timeTextColorNormal = controller._d.atbTimeTextColorNormal;
		_s.timeTextColorSelected = controller._d.atbTimeTextColorSelected;
		_s.buttonTextNormalColor = controller._d.atbButtonTextNormalColor;
		_s.buttonTextSelectedColor = controller._d.atbButtonTextSelectedColor;
		_s.buttonBackgroundNormalColor = controller._d.atbButtonBackgroundNormalColor;
		_s.buttonBackgroundSelectedColor = controller._d.atbButtonBackgroundSelectedColor;
		_s.isMbl = FWDUVPUtils.isMobile;
		_s.pa = 0;
		_s.pb = 1;
	
		//##########################################//
		/* initialize _s */
		//##########################################//
		_s.init = function(){
			_s.setOverflow("visible");
			_s.mainHld = new FWDUVPDisplayObject("div");
			_s.addChild(_s.mainHld);
			if(controller.repeatBackground_bl){
				_s.mainHld.getStyle().background = "url('" + controller.controllerBkPath_str +  "')";
			}else{
				_s.bk_do = new FWDUVPDisplayObject("img");
				var img = new Image();
				img.src = controller.controllerBkPath_str;
				_s.bk_do.setScreen(img);
				_s.mainHld.addChild(_s.bk_do);
			}
			_s.setupLeftAndRight();
			_s.setupMainScrubber();
		};

		_s.resize = function(){
			_s.setWidth(controller.sW);
			_s.setHeight(controller.sH);
			_s.mainHld.setWidth(controller.sW);
			_s.mainHld.setHeight(controller.sH);

			if(_s.bk_do){
				_s.bk_do.setWidth(controller.sW);
				_s.bk_do.setHeight(controller.sH);
			}
			if(_s.isShowed_bl){
				var offset = 0;
				if(controller.isMainScrubberOnTop_bl) offset += controller.mainScrubber_do.h - controller.mainScrubberOffestTop - 1;
				_s.mainHld.setY(-_s.h - 1 - offset);
			}
			
			_s.positionText();
			_s.positionButtons();
			_s.resizeProgress();
			_s.resizeMainScrubber();
		}

		_s.setupLeftAndRight = function(){

			_s.leftTxt = new FWDUVPDisplayObject("div");
			_s.leftTxt.hasTransform3d_bl = false;
			_s.leftTxt.hasTransform2d_bl = false;
			_s.leftTxt.setBackfaceVisibility();
			_s.leftTxt.getStyle().fontFamily = "Arial";
			_s.leftTxt.getStyle().fontSize= "12px";
			_s.leftTxt.getStyle().whiteSpace= "nowrap";
			_s.leftTxt.getStyle().textAlign = "center";
			_s.leftTxt.getStyle().padding = "4px";
			_s.leftTxt.getStyle().paddingLeft = "4px";
			_s.leftTxt.getStyle().paddingRIght = "4px";
			_s.leftTxt.getStyle().color = _s.timeTextColorNormal;
			_s.leftTxt.getStyle().backgroundColor = _s.timeBackgroundColor;
			_s.leftTxt.getStyle().fontSmoothing = "antialiased";
			_s.leftTxt.getStyle().webkitFontSmoothing = "antialiased";
			_s.leftTxt.getStyle().textRendering = "optimizeLegibility";
			_s.leftTxt.setInnerHTML("00:00");
			_s.mainHld.addChild(_s.leftTxt);

			_s.rightTxt = new FWDUVPDisplayObject("div");
			_s.rightTxt.hasTransform3d_bl = false;
			_s.rightTxt.hasTransform2d_bl = false;
			_s.rightTxt.setBackfaceVisibility();
			_s.rightTxt.getStyle().fontFamily = "Arial";
			_s.rightTxt.getStyle().fontSize= "12px";
			_s.rightTxt.getStyle().whiteSpace= "nowrap";
			_s.rightTxt.getStyle().textAlign = "center";
			_s.rightTxt.getStyle().padding = "4px";
			_s.rightTxt.getStyle().paddingLeft = "6px";
			_s.rightTxt.getStyle().paddingRIght = "6px";
			_s.rightTxt.getStyle().color = _s.timeTextColorNormal;
			_s.rightTxt.getStyle().backgroundColor = _s.timeBackgroundColor;
			_s.rightTxt.getStyle().fontSmoothing = "antialiased";
			_s.rightTxt.getStyle().webkitFontSmoothing = "antialiased";
			_s.rightTxt.getStyle().textRendering = "optimizeLegibility";
			_s.rightTxt.setInnerHTML("00:00");
			_s.mainHld.addChild(_s.rightTxt);
		}
		

		_s.setLeftLabel = function(label){
			_s.leftTxt.setInnerHTML(label);
		}

		_s.setRightLabel = function(label){
			_s.rightTxt.setInnerHTML(label);
		}

		_s.setupInitLabels = function(){
			_s.pa = 0;
			_s.pb = 1;
			_s.updateTime();
			_s.positionText();
			setTimeout(_s.positionText, 300);
		}

		_s.updateTime = function(){
			var hasHours = FWDUVPUtils.formatTime(_s.duration).length > 5;
			var totalTime = FWDUVPUtils.formatTime(_s.duration);
			_s.rightTime = FWDUVPUtils.formatTime(_s.duration * _s.pb);
			_s.leftTime = FWDUVPUtils.formatTime(_s.duration * _s.pa);
			if(_s.rightTime.length < 6 && hasHours) _s.rightTime = "00:" + _s.rightTime; 

			if(_s.rightTime.length > 5 && _s.leftTime.length < 6) _s.leftTime = "00:" + _s.leftTime;
			_s.setLeftLabel(_s.leftTime);
			_s.setRightLabel(_s.rightTime);
		}

		_s.positionText = function(){
			_s.leftTxt.setX(controller.startSpaceBetweenButtons);
			_s.leftTxt.setY(Math.round((controller.sH - _s.leftTxt.getHeight())/2));
			_s.rightTxt.setX(controller.sW - controller.startSpaceBetweenButtons - _s.rightTxt.getWidth());
			_s.rightTxt.setY(Math.round((controller.sH - _s.rightTxt.getHeight())/2));
		}

		//################################################//
		/* Setup main scrubber */
		//################################################//
		_s.setupMainScrubber = function(){
			//setup background bar
			_s.mainScrubber_do = new FWDUVPDisplayObject("div");
			_s.mainScrubber_do.setOverflow('visible');
			_s.mainScrubber_do.setY(parseInt((controller.sH - controller.mainScrbH)/2));
			_s.mainScrubber_do.setHeight(controller.mainScrbH);
		
			var mainScrubberBkLeft_img = new Image();
			mainScrubberBkLeft_img.src = controller.mainScrubberBkLeft_img.src;
			mainScrubberBkLeft_img.width = controller.mainScrubberBkLeft_img.width;
			mainScrubberBkLeft_img.height = controller.mainScrubberBkLeft_img.height;
			_s.mainScrubberBkLeft_do = new FWDUVPDisplayObject("img");
			_s.mainScrubberBkLeft_do.setScreen(mainScrubberBkLeft_img);

			var rightImage = new Image();
			rightImage.src = controller._d.mainScrubberBkRightPath_str;
			_s.mainScrubberBkRight_do = new FWDUVPDisplayObject("img");
			_s.mainScrubberBkRight_do.setScreen(rightImage);
			_s.mainScrubberBkRight_do.setWidth(_s.mainScrubberBkLeft_do.w);
			_s.mainScrubberBkRight_do.setHeight(_s.mainScrubberBkLeft_do.h);
			
			var middleImage = new Image();
			middleImage.src = controller.mainScrubberBkMiddlePath_str;
			
			_s.mainScrubberBkMiddle_do = new FWDUVPDisplayObject("div");	
			_s.mainScrubberBkMiddle_do.getStyle().background = "url('" + controller.mainScrubberBkMiddlePath_str + "') repeat-x";
			
			_s.mainScrubberBkMiddle_do.setHeight(controller.mainScrbH);
			_s.mainScrubberBkMiddle_do.setX(controller.scrbsBkLARW);

			_s.mainScrubber_do.addChild(_s.mainScrubberBkLeft_do);
			_s.mainScrubber_do.addChild(_s.mainScrubberBkMiddle_do);
			_s.mainScrubber_do.addChild(_s.mainScrubberBkRight_do);
			_s.mainHld.addChild(_s.mainScrubber_do);

			//setup progress bar
			_s.mainScrubberDrag_do = new FWDUVPDisplayObject("div");
			_s.mainScrubberDrag_do.setHeight(controller.mainScrbH);
			
			_s.mainScrubberMiddleImage = new Image();
			_s.mainScrubberMiddleImage.src = controller.mainScrubberDragMiddlePath_str;
			
			if(_s.useHEX){
				_s.mainScrubberDragMiddle_do = new FWDUVPDisplayObject("div");
				_s.mainScrubberMiddleImage.onload = function(){
					var testCanvas = FWDUVPUtils.getCanvasWithModifiedColor(_s.mainScrubberMiddleImage, controller.nBC, true);
					_s.mainSCrubberMiddleCanvas = testCanvas.canvas;
					_s.mainSCrubberDragMiddleImageBackground = testCanvas.image;
					_s.mainScrubberDragMiddle_do.getStyle().background = "url('" + _s.mainSCrubberDragMiddleImageBackground.src + "') repeat-x";
				}
			}else{
				_s.mainScrubberDragMiddle_do = new FWDUVPDisplayObject("div");	
				_s.mainScrubberDragMiddle_do.getStyle().background = "url('" + controller.mainScrubberDragMiddlePath_str + "') repeat-x";
			}
		
			_s.mainScrubberDragMiddle_do.setHeight(controller.mainScrbH);
			_s.mainScrubber_do.addChild(_s.mainScrubberDragMiddle_do);
			

			// Setup a to b loop buttons
			FWDUVPTextButton.setPrototype();
			_s.left_do = new FWDUVPTextButton(
				'A',
				 _s.buttonTextNormalColor,
				 _s.buttonTextSelectedColor,
				 _s.buttonBackgroundNormalColor,
				 _s.buttonBackgroundSelectedColor,
				 controller._d.handPath_str,
				 controller._d.grabPath_str
				 );
			_s.mainScrubber_do.addChild(_s.left_do);
			_s.left_do.addListener(FWDUVPTextButton.MOUSE_DOWN, _s.aDown);
			_s.left_do.addListener(FWDUVPTextButton.MOUSE_UP, _s.aUp);

			FWDUVPTextButton.setPrototype();
			_s.right_do = new FWDUVPTextButton(
				'B',
				 _s.buttonTextNormalColor,
				 _s.buttonTextSelectedColor,
				 _s.buttonBackgroundNormalColor,
				 _s.buttonBackgroundSelectedColor,
				 controller._d.handPath_str,
				 controller._d.grabPath_str
				 );
			_s.mainScrubber_do.addChild(_s.right_do);
			_s.right_do.addListener(FWDUVPTextButton.MOUSE_DOWN, _s.bDown);
			_s.right_do.addListener(FWDUVPTextButton.MOUSE_UP, _s.bUp);
		}

		_s.bDown = function(e){
			_s.scrub = true
			var vc = FWDUVPUtils.getViewportMouseCoordinates(e.e);	
			_s.lastPresedX = vc.screenX;
			_s.leftXPositionOnPress = _s.right_do.getX();
			if(_s.isMbl){
				window.addEventListener("touchmove", _s.bMoveHandler);
			}else{
				window.addEventListener("mousemove", _s.bMoveHandler);
			}
			FWDAnimation.to(_s.rightTxt.screen, .8, {css:{color:_s.timeTextColorSelected}, ease:Expo.easeOut});
			_s.dispatchEvent(FWDUVPATB.START_TO_SCRUB);
		}

		_s.bUp = function(e){
			_s.scrub = false;
			if(_s.isMbl){
				window.removeEventListener("touchmove", _s.bMoveHandler);
			}else{
				window.removeEventListener("mousemove", _s.bMoveHandler);
			}
			FWDAnimation.to(_s.rightTxt.screen, .8, {css:{color:_s.timeTextColorNormal}, ease:Expo.easeOut});
			_s.dispatchEvent(FWDUVPATB.STOP_TO_SCRUB);
		}

		_s.bMoveHandler = function(e){
			if(e.preventDefault) e.preventDefault();
			var vc = FWDUVPUtils.getViewportMouseCoordinates(e);	
			_s.finalHandlerX = Math.round(_s.leftXPositionOnPress + vc.screenX - _s.lastPresedX);
			if(_s.finalHandlerX <= Math.round(_s.left_do.x + _s.left_do.getWidth() + 2)){
				_s.finalHandlerX = Math.round(_s.left_do.x + _s.left_do.getWidth() + 2);
			}else if(_s.finalHandlerX > _s.mainScrubber_do.w - _s.right_do.getWidth()){
				_s.finalHandlerX = _s.mainScrubber_do.w - _s.right_do.getWidth();
			}
			_s.right_do.setX(_s.finalHandlerX);
			_s.pb = _s.right_do.x/(_s.mainScrubber_do.w - _s.right_do.getWidth());
			_s.updateTime();
			_s.resizeProgress();
		}

		_s.aDown = function(e){
			_s.scrub = true;
			var vc = FWDUVPUtils.getViewportMouseCoordinates(e.e);	
			_s.lastPresedX = vc.screenX;
			_s.leftXPositionOnPress = _s.left_do.getX();
			if(_s.isMbl){
				window.addEventListener("touchmove", _s.aMoveHandler);
			}else{
				window.addEventListener("mousemove", _s.aMoveHandler);
			}
			FWDAnimation.to(_s.leftTxt.screen, .8, {css:{color:_s.timeTextColorSelected}, ease:Expo.easeOut});
			_s.dispatchEvent(FWDUVPATB.START_TO_SCRUB);
		}

		_s.aUp = function(e){
			_s.scrub = false;
			if(_s.isMbl){
				window.removeEventListener("touchmove", _s.aMoveHandler);
			}else{
				window.removeEventListener("mousemove", _s.aMoveHandler);
			}
			FWDAnimation.to(_s.leftTxt.screen, .8, {css:{color:_s.timeTextColorNormal}, ease:Expo.easeOut});
			_s.dispatchEvent(FWDUVPATB.STOP_TO_SCRUB);
		}

		_s.aMoveHandler = function(e){
			if(e.preventDefault) e.preventDefault();
			var vc = FWDUVPUtils.getViewportMouseCoordinates(e);	
			_s.finalHandlerX = Math.round(_s.leftXPositionOnPress + vc.screenX - _s.lastPresedX);
			if(_s.finalHandlerX <= 0){
				_s.finalHandlerX = 0;
			}else if(_s.finalHandlerX > Math.round(_s.right_do.x - _s.left_do.getWidth() - 2)){
				_s.finalHandlerX = Math.round(_s.right_do.x - _s.left_do.getWidth() - 2);
			}
			_s.left_do.setX(_s.finalHandlerX);
			_s.pa = _s.left_do.x/_s.mainScrubber_do.w;
			_s.updateTime();
			_s.resizeProgress();
		}

		_s.resizeMainScrubber = function(){
			_s.maiScrbW = controller.sW - controller.startSpaceBetweenButtons * 6 - _s.leftTxt.getWidth() - _s.rightTxt.getWidth();
			_s.mainScrubber_do.setWidth(_s.maiScrbW);
			_s.mainScrubber_do.setX(_s.leftTxt.getWidth() + controller.startSpaceBetweenButtons * 3);
			_s.mainScrubber_do.setY(parseInt((controller.sH - controller.mainScrbH)/2));
			_s.mainScrubberBkMiddle_do.setWidth(_s.maiScrbW - controller.scrbsBkLARW * 2);
			_s.mainScrubberBkRight_do.setX(_s.maiScrbW - controller.scrbsBkLARW);
		}

		_s.positionButtons = function(){
			_s.left_do.setX(_s.pa * _s.mainScrubber_do.w);
			_s.right_do.setX(_s.pb * (_s.mainScrubber_do.w - _s.right_do.getWidth()));
		}

		_s.resizeProgress = function(){
			_s.mainScrubberDragMiddle_do.setX(_s.left_do.x + _s.left_do.getWidth() + 1);
			_s.mainScrubberDragMiddle_do.setWidth(_s.right_do.x - (_s.left_do.x + _s.left_do.getWidth() + 2));
		}

		//################################################//
		/* Hide and show */
		//################################################//
		_s.show = function(animate){
			if(_s.isShowed_bl) return;
			_s.duration = _s.main.totalTimeInSeconds;
			_s.setupInitLabels();
			
			_s.positionText();
			_s.positionButtons();
			_s.resizeProgress();
			_s.resizeMainScrubber();
			setTimeout(function(){
				_s.positionText();
				_s.positionButtons();
				_s.resizeProgress();
				_s.resizeMainScrubber();
			}, 300);
			_s.isShowed_bl = true;
			var offset = 0;
			if(controller.isMainScrubberOnTop_bl) offset += controller.mainScrubber_do.h - controller.mainScrubberOffestTop - 1;
			if(animate){
				FWDAnimation.to(_s.mainHld, .8, {y:-_s.h - 1 - offset, ease:Expo.easeInOut});
			}else{
				FWDAnimation.killTweensOf(_s.mainHld);
				_s.mainHld.setY(-_s.h - 1);
			}
			setTimeout(_s.positionButtons, 200);
			
		};

		_s.hide = function(animate){
			if(!_s.isShowed_bl) return;
			_s.isShowed_bl = false;
			if(animate){
				FWDAnimation.to(_s.mainHld, .8, {y:0, ease:Expo.easeInOut});
			}else{
				FWDAnimation.killTweensOf(_s.mainHld);
				_s.mainHld.setY(0);
			}
			setTimeout(_s.positionButtons, 200);
		};
	
		
		_s.init();
	};
	
	/* set prototype */
	FWDUVPATB.setPrototype = function(){
		FWDUVPATB.prototype = null;
		FWDUVPATB.prototype = new FWDUVPTransformDisplayObject("div");
	};

	FWDUVPATB.START_TO_SCRUB = "startToScrub";
	FWDUVPATB.SCRUB = "scrub";
	FWDUVPATB.STOP_TO_SCRUB = "stopToScrub";

	FWDUVPATB.prototype = null;
	window.FWDUVPATB = FWDUVPATB;
}(window));

/* FWDUVPTextButton */
(function (window){
var FWDUVPTextButton = function(
		label,
		colorN,
		colorS,
		bkColorN,
		bkColorS,
		cursor,
		cursor2
		){
		
		var _s = this;
		var prototype = FWDUVPTextButton.prototype;
		
		_s.nImg_img = null;
		_s.sImg_img = null;
		
		_s.dumy_do = null;
		_s.cursor = cursor;
		_s.cursor2 = cursor2;
	
		_s.label_str = label;
		_s.colorN = colorN;	
		_s.colorS = colorS;
		_s.bkColorN = bkColorN;
		_s.bkColorS = bkColorS;
	
		_s.isDisabled_bl = false;
		_s.isMbl = FWDUVPUtils.isMobile;
		
		//##########################################//
		/* initialize _s */
		//##########################################//
		_s.init = function(){
			_s.setupMainContainers();
			
		};
		
		//##########################################//
		/* setup main containers */
		//##########################################//
		_s.setupMainContainers = function(){
			
			_s.hasTransform3d_bl = false;
			_s.hasTransform2d_bl = false;
			_s.setBackfaceVisibility();
			_s.getStyle().display = "inline-block";
			_s.getStyle().clear = "both";
			_s.getStyle().fontFamily = "Arial";
			_s.getStyle().fontSize= "12px";
			_s.getStyle().whiteSpace= "nowrap";
			_s.getStyle().padding = "3px 4px";
			_s.getStyle().color = _s.colorN;
			_s.getStyle().backgroundColor = _s.bkColorN;
			_s.getStyle().fontSmoothing = "antialiased";
			_s.getStyle().webkitFontSmoothing = "antialiased";
			_s.getStyle().textRendering = "optimizeLegibility";	
			_s.setInnerHTML(_s.label_str);
			
			_s.dumy_do = new FWDUVPDisplayObject("div");
			if(FWDUVPUtils.isIE){
				_s.dumy_do.setBkColor("#00FF00");
				_s.dumy_do.setAlpha(0.0001);
			}
			_s.dumy_do.getStyle().cursor = 'grab';
			_s.dumy_do.getStyle().width = "100%";
			_s.dumy_do.getStyle().height = "50px";
			_s.addChild(_s.dumy_do);
			
			if(_s.hasPointerEvent_bl){
				_s.screen.addEventListener("pointerup", _s.onMouseUp);
				_s.screen.addEventListener("pointerover", _s.onMouseOver);
				_s.screen.addEventListener("pointerout", _s.onMouseOut);
			}else if(_s.screen.addEventListener){	
				if(!_s.isMbl){
					_s.screen.addEventListener("mouseover", _s.onMouseOver);
					_s.screen.addEventListener("mouseout", _s.onMouseOut);
					_s.screen.addEventListener("mousedown", _s.onMouseDown);
				}
				_s.screen.addEventListener("touchstart", _s.onMouseDown);
			}
		};
		
		_s.onMouseOver = function(e){
			if(_s.isDisabled_bl) return;
			_s.setSelectedState();
		};
			
		_s.onMouseOut = function(e){
			if(_s.isDisabled_bl || _s.grabed) return;
			_s.setNormalState();
		};


		_s.onMouseDown = function(e){
			if(_s.isDisabled_bl) return;
		
			_s.grabed = true;
			if(!_s.isMbl){
				window.addEventListener('mouseup', _s.checkUp)
			}else{
				window.addEventListener('touchend', _s.checkUp)
			}
			_s.dumy_do.getStyle().cursor = 'grabbing';
			document.getElementsByTagName("body")[0].style.cursor = 'grabbing';

			_s.dispatchEvent(FWDUVPTextButton.MOUSE_DOWN, {e:e});
		};

		_s.checkUp = function(e){
			var vc = FWDUVPUtils.getViewportMouseCoordinates(e);	
			if(!FWDUVPUtils.hitTest(_s.screen, vc.screenX, vc.screenY)){
				_s.setNormalState();	
				if(!_s.isMbl){
					window.removeEventListener('mouseup', _s.checkUp);
				}else{
					window.addEventListener('touchend', _s.checkUp);
				}
			}
			_s.grabed = false;
			_s.dumy_do.getStyle().cursor = 'grab';
			document.getElementsByTagName("body")[0].style.cursor = 'auto';
			_s.dispatchEvent(FWDUVPTextButton.MOUSE_UP);
		}

		//####################################//
		/* Set normal / selected state */
		//####################################//
		_s.setNormalState = function(animate){
			FWDAnimation.to(_s.screen, .8, {css:{color:_s.colorN, backgroundColor:_s.bkColorN}, ease:Expo.easeOut});
		};
		
		_s.setSelectedState = function(animate){
			FWDAnimation.to(_s.screen, .8, {css:{color:_s.colorS, backgroundColor:_s.bkColorS}, ease:Expo.easeOut});
		};

		_s.disable = function(){
			_s.onMouseOver();
			_s.dumy_do.setButtonMode(false);
			FWDAnimation.to(_s, .8, {alpha:.4, ease:Expo.easeOut});
			_s.isDisabled_bl = true;
		}
		
		_s.enable = function(){
			_s.isDisabled_bl = false;
			_s.onMouseOut();
			_s.dumy_do.setButtonMode(true);
			FWDAnimation.to(_s, .8, {alpha:1, ease:Expo.easeOut});
			
		}
		
	
		_s.init();
	};
	
	/* set prototype */
	FWDUVPTextButton.setPrototype = function(){
		FWDUVPTextButton.prototype = null;
		FWDUVPTextButton.prototype = new FWDUVPDisplayObject("div");
	};
	
	FWDUVPTextButton.MOUSE_UP = 'mouseUp';
	FWDUVPTextButton.MOUSE_DOWN = 'mouseDown';
	
	FWDUVPTextButton.prototype = null;
	window.FWDUVPTextButton = FWDUVPTextButton;
}(window));