/* FWDUVPFPS */
(function (window){
	var FWDUVPFPS = function(prt){
		var _s = this;
		var prototype = FWDUVPFPS.prototype;

		_s._d = prt._d;
		_s.frequencyOfFingerPrintStamp = _s._d.frequencyOfFingerPrintStamp;
		_s.durationOfFingerPrintStamp = _s._d.durationOfFingerPrintStamp;
	
		//##########################################//
		/* initialize _s */
		//##########################################//
		_s.init = function(){
			_s.setOverflow("visible");
			_s.setupText();
			_s.getStyle().width = '100%';
			_s.getStyle().pointerEvents = 'none';
			_s.txt.setVisible(false);
		};

		_s.setupText = function(){
			_s.txt = new FWDUVPDisplayObject("div");
			_s.txt.getStyle().display = 'inline-block';
			_s.txt.hasTransform3d_bl = false;
			_s.txt.hasTransform2d_bl = false;
			var txt = '<div class="fwduvp-fingerprintstamp-holder">'
			var obj = window['fwduvpFingerPrintStamp'];
			for (var prop in obj){
				txt += prop + ' ' + obj[prop];
			}
			txt += '</div>';
			
			_s.txt.setInnerHTML(txt);
			_s.txt.setBackfaceVisibility();
			_s.addChild(_s.txt);
		}

		_s.start = function(){
			var rd = Math.random();
			if(rd>= .5){
				rd = Math.random() * 1000;
			}else{
				rd = Math.random() * 1000 * -1;
			}
	
			var t = parseInt(_s.frequencyOfFingerPrintStamp - rd);
			
			_s.stop();
			if(prt.isAdd_bl) return;
			_s.show1_to = setTimeout(function(){
				_s.txt.setVisible(true);
			
				_s.txt.setX(Math.max(0,Math.round(Math.random() * prt.tempVidStageWidth - _s.txt.getWidth())));
				_s.txt.setY(Math.max(0,Math.round(Math.random() * prt.tempVidStageHeight - _s.txt.getHeight())));
				_s.show2_to = setTimeout(function(){
					_s.txt.setVisible(false);
					_s.start();
				}, _s.durationOfFingerPrintStamp);
				
			}, t);
		}

		_s.stop = function(){
			clearTimeout(_s.show1_to);
			clearTimeout(_s.show2_to);
			_s.txt.setVisible(false);
		}
		
		_s.init();
	};
	
	/* set prototype */
	FWDUVPFPS.setPrototype = function(){
		FWDUVPFPS.prototype = null;
		FWDUVPFPS.prototype = new FWDUVPDisplayObject("div");
	};


	FWDUVPFPS.prototype = null;
	window.FWDUVPFPS = FWDUVPFPS;
}(window));
