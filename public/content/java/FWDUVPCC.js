/* FWDUVPCC */
(function (window){
var FWDUVPCC = function(
		controller
		){
		var _s = this;
		var prototype = FWDUVPCC.prototype;
		var main = controller.prt;
	
		_s.session;
		_s.remotePlayer;
		_s.currentTime;
		_s.controller_do = controller;
		_s.mediaStatus;
		_s.isReady;
		_s.playerState;
		_s.isSeeking_bl;
		
		const PLAYER_STATE = {
		  IDLE: 'IDLE',
		  BUFFERING: 'BUFFERING',
		  LOADED: 'LOADED',
		  PLAYING: 'PLAYING',
		  PAUSED: 'PAUSED'
		};
	
		//##########################################//
		/* initialize  */
		//##########################################//
		_s.init = function(){
			_s.isReady = false;
			
			var count = 0;
		 	var loadCastInterval = setInterval(function(){
				if(window['chrome'] && window['chrome']['cast'] && window['chrome']['cast'].isAvailable) {
					console.log('Chormecast API has loaded.');
					clearInterval(loadCastInterval);	
					_s.initAPI();
				}
			}, 1000);
			_s.initializeController();
			_s.setupCastingScreen();
		};

		//##########################################//
		/* initialize controller */
		//##########################################//
		_s.initializeController =  function(){
			//controller.addListener(FWDUVPController.CAST, _s.startCastingHandler);
			controller.addListener(FWDUVPController.UNCAST, _s.stopCastingHandler);
		}
		
		_s.stopCastingHandler = function(){
			_s.stopCasting();
		}


		//##########################################//
		/* initialize API */
		//##########################################//
		_s.initAPI = function(){
			
			var options = {};
			_s.isReady = true;
			options.receiverApplicationId = chrome.cast.media.DEFAULT_MEDIA_RECEIVER_APP_ID; 
			options.autoJoinPolicy = chrome.cast.AutoJoinPolicy.ORIGIN_SCOPED;
			cast.framework.CastContext.getInstance().setOptions(options);
		
			FWDUVPlayer.keyboardCurInstance = main;
			
		    _s.setupCastButton();
			_s.checkButtonState();
			_s.setupPlayerController();
		}
		
		//##########################################//
		/* Setup casting screen*/
		//##########################################//
		_s.setupCastingScreen =  function(){
			_s.cs_do = new FWDUVPDisplayObject("div");
			_s.cs_do.hasTransform3d_bl = false;
			_s.cs_do.hasTransform2d_bl = false;
			_s.cs_do.setBackfaceVisibility();
			_s.cs_do.getStyle().fontFamily = "Arial";
			_s.cs_do.getStyle().fontSize= "12px";
			_s.cs_do.getStyle().letterSpacing = '0.6px';
			_s.cs_do.getStyle().whiteSpace= "nowrap";
			_s.cs_do.getStyle().textAlign = "center";
			_s.cs_do.getStyle().padding = "10px";
			_s.cs_do.getStyle().paddingLeft = "12px";
			_s.cs_do.getStyle().paddingRight = "12px";
			_s.cs_do.setX(10);
			_s.cs_do.setY(10);
			_s.cs_do.getStyle().background = "#000000BB";
			_s.cs_do.getStyle().color = "#FFF";
			_s.cs_do.setInnerHTML('<img src="' + main._d.sknPth + 'cc-icon.png"/><span class="fwdcs_do" style="position: relative;top: -6px;left: 10px;margin-right: 10px"">Connecting to Chromecast</span>');
		}
		
		//##########################################//
		/* Setup cast button */
		//##########################################//
		_s.setupCastButton =  function(){
			_s.btn = document.createElement("google-cast-launcher");
			_s.btn.style.display = 'block';
			_s.btn.style.position = 'absolute';
			_s.btn.style.opacity = 0;
			controller.ccBtn_do.screen.removeEventListener("toustart", controller.ccBtn_do.onDown);
			controller.ccBtn_do.screen.removeEventListener("touchend", controller.ccBtn_do.onMouseUp);
			controller.ccBtn_do.screen.appendChild(_s.btn);
			setTimeout(function(){
				_s.btn.style.display = 'block';
			}, 500);
		}
		
		FWDUVPCC.disableButton = function(){
			if(_s.btn) _s.btn.style.width = '0';
			if(_s.controller_do && _s.controller_do.ccBtn_do) _s.controller_do.ccBtn_do.disable();
		}
		
		FWDUVPCC.enableButton = function(){
			if(_s.btn) _s.btn.style.width = '100%';
			if(_s.controller_do && _s.controller_do.ccBtn_do) _s.controller_do.ccBtn_do.enable();
		}
		
		_s.checkButtonState = function(){
			if(!_s.isReady) return;
			if(main.videoType_str != FWDUVPlayer.VIDEO && main.videoType_str != FWDUVPlayer.MP3 && main.videoType_str != FWDUVPlayer.HLS_JS){
				_s.controller_do.removeCCButton();
				_s.stopCasting();
			}else{
				_s.controller_do.addCCButton();
				if(_s.isCasting){
					_s.mainPlaying_bl = main.isPlaying_bl = false;
				}
				main.curTimeInSecond = 0;
				if(_s.isCasting) _s.loadMedia();
			}
		}
		
		_s.isValidFormat = function(){
			if(main.videoType_str == FWDUVPlayer.VIDEO || main.videoType_str == FWDUVPlayer.MP3 || main.videoType_str == FWDUVPlayer.HLS_JS) return true;
			return false;
		}
		
		//##########################################//
		/* Setup remotePlayer controller */
		//##########################################//
		_s.setupPlayerController = function(){
			_s.remotePlayer = new cast.framework.RemotePlayer();
			_s.remotePlayerController = new cast.framework.RemotePlayerController(_s.remotePlayer);
			_s.setVolume();
			_s.remotePlayerController.addEventListener(
				cast.framework.RemotePlayerEventType.IS_CONNECTED_CHANGED,
				function(e){
					if(_s.remotePlayer.isConnected){
						controller.ccBtn_do.setButtonState(0);
						main.main_do.addChild(_s.cs_do);
						_s.mainPlaying_bl = main.isPlaying_bl;
						main.stop();
						_s.loadMedia();
						_s.isCasting = main.isCasting = true;
					}else{
						_s.btn.style.left = '0';
						controller.ccBtn_do.setButtonState(1);
						main.curTimeInSecond = 0;
						main.isCasting = false;
						_s.controller_do.disableSubtitleButton();
					
						if(_s.playerState == PLAYER_STATE.PLAYING && !_s.isMbl
						  && _s.videoSource == main.finalVideoPath_str){
							var curTime;
							curTime = FWDUVPUtils.formatTime(_s.currentTime);
							if(curTime.length == 5) curTime = "00:" + curTime;
							if(curTime.length == 7) curTime = "0" + curTime;
							main.castStartAtTime = curTime;
							_s.stop();
							main.play();
						}else{
							_s.stop();
							main.castStartAtTime = undefined;
						}
						try{
							main.main_do.removeChild(_s.cs_do);
						}catch(e){}
						
						_s.isStopped_bl = false;
						_s.isCasting = false;
						_s.playerState = undefined;
						console.log('Disconnected');
					}
				}
			);
		}
		
		_s.play = function(){
			if(_s.playerState == PLAYER_STATE.IDLE){
				_s.loadMedia(true);
			}else if(_s.remotePlayer.isPaused) {
			  _s.remotePlayerController.playOrPause();
			}
		};
		
		_s.pause = function () {
			_s.playerState = PLAYER_STATE.PAUSED;
			if(!_s.remotePlayer.isPaused) {
			  _s.remotePlayerController.playOrPause();
			}else{
				_s.playerState = PLAYER_STATE.PLAYING;
			}
		};
		
		_s.allowToggle = true;
		_s.togglePlayPause = function () {
			//bug stops the events to be received
			 if(_s.allowToggle){
				_s.remotePlayerController.playOrPause();
			 }
		};
		
		_s.stop = function(){
			if(!_s.isCasting) return;
			clearTimeout(_s.setLoopId_to);
			clearTimeout(_s.loadMediLoopId_to);
			_s.stopToCheckPlaybackComplete();
			_s.remotePlayerController.playOrPause();
			_s.remotePlayerController.stop();
			_s.controller_do.showPlayButton();
			_s.controller_do.disableMainScrubber()
			if(_s.controller_do.ttm) _s.controller_do.ttm.hide();
			if(_s.controller_do.thumbnailsPreview_do) _s.controller_do.thumbnailsPreview_do.hide();
			if(_s.controller_do.rewindButton_do) _s.controller_do.rewindButton_do.disable();
			if(_s.controller_do.downloadButton_do) _s.controller_do.downloadButton_do.disable();
			_s.isStopped_bl = true;
			if(main.lrgPlayBtn) main.lrgPlayBtn.show();
			_s.playerState = PLAYER_STATE.IDLE;
			main.curTimeInSecond = 0;
			_s.updateDisplay();
		}
		
		// Scrubb
		_s.startToScrub = function(){
			_s.isSeeking_bl = false;
			_s.allowToggle = false;
		}
		
		_s.stopToScrub = function(){
			_s.isSeeking_bl = false;
			_s.allowToggle = false;
			clearTimeout(_s.allowToToggle);
			_s.allowToToggle = setTimeout(function(){
				 _s.allowToggle = true;
			},2000);
		}
		
		_s.seek = function(percent){
			seekTime = Math.round(percent * _s.getDuration());
			_s.remotePlayer.currentTime = seekTime;
			_s.remotePlayerController.seek();
		}
		
		_s.getCurrentTime = function () {
			return  Math.round(_s.remotePlayer.currentTime);
		};

		_s.getDuration = function () {
			return Math.round(_s.remotePlayer.duration);
		};
		
		_s.scrubbAtTime = function(duration){
			_s.allowToggle = false;
			clearTimeout(_s.allowToToggle);
			_s.allowToToggle = setTimeout(function(){
				 _s.allowToggle = true;
			},2000);
			_s.remotePlayer.currentTime = duration;
			_s.remotePlayerController.seek();
		}
		
		// Volume
		_s.setVolume = function(){
			_s.remotePlayer.volumeLevel = main.volume;
			_s.remotePlayerController.setVolumeLevel();
		}

		//##########################################//
		/* Setup remove player events */
		//##########################################//
		_s.addPlayerEvents =  function(){
			
			// Triggers when the media info or the remotePlayer state changes
			_s.remotePlayerController.addEventListener(
				cast.framework.RemotePlayerEventType.MEDIA_INFO_CHANGED,
				function(event) {
					var session = cast.framework.CastContext.getInstance().getCurrentSession();
					if (!session) {
						_s.mediaInfo = null;
						_s.updateDisplay();
						return;
					}

					var media = session.getMediaSession();
					if (!media) {
						_s.mediaInfo = null;
						_s.updateDisplay();
						return;
					}

					_s.mediaInfo = media.media;
					
					if(media.playerState == PLAYER_STATE.PAUSED) {
						_s.changePlayPauseState(PLAYER_STATE.PAUSED);
					}else if(media.playerState == PLAYER_STATE.PLAYING){
						_s.changePlayPauseState(PLAYER_STATE.PLAYING);
					}
					
					if(_s.isStopped_bl) _s.updateDisplay();
				}
			);
			
			_s.remotePlayerController.addEventListener(
				cast.framework.RemotePlayerEventType.IS_PAUSED_CHANGED,
				function(){
					if(_s.remotePlayer.isPaused) {
						_s.changePlayPauseState(PLAYER_STATE.PAUSED);
					}else if (_s.playerState !== PLAYER_STATE.PLAYING) {
						_s.changePlayPauseState(PLAYER_STATE.PLAYING);
					}
				}
			 );
			 
			_s.changePlayPauseState = function(state){
				 if(state == PLAYER_STATE.PAUSED) {
					_s.controller_do.showPlayButton();
					if(main.lrgPlayBtn) main.lrgPlayBtn.show();
					_s.playerState = PLAYER_STATE.PAUSED;
				}else if (_s.playerState !== PLAYER_STATE.PLAYING) {
					_s.controller_do.showPauseButton();
					if(main.lrgPlayBtn) main.lrgPlayBtn.hide();
					_s.playerState = PLAYER_STATE.PLAYING;
					_s.controller_do.enableMainScrubber();
					if(_s.controller_do.rewindButton_do) _s.controller_do.rewindButton_do.enable();
					if(_s.controller_do.downloadButton_do) _s.controller_do.downloadButton_do.enable();
					_s.startToCheckPlaybackComplete();
					_s.setLoopId_to = setTimeout(function(){
						_s.allowToLoop = true;
					}, 1000);
				}
				if(!_s.isStopped_bl) _s.updateDisplay();
			}
			
			// Update time
			_s.remotePlayerController.addEventListener(
				cast.framework.RemotePlayerEventType.CURRENT_TIME_CHANGED,
				function (event){
					var time = FWDUVPUtils.formatTime(_s.getCurrentTime()) + "/" + FWDUVPUtils.formatTime(_s.getDuration());
					_s.controller_do.updateTime(time);
					if(_s.getCurrentTime()) _s.currentTime = _s.getCurrentTime();
					if(!_s.isSeeking_bl){
						_s.controller_do.updateMainScrubber(_s.getCurrentTime()/_s.getDuration());
					}
				}
			);
			
			// Update volume
			_s.remotePlayerController.addEventListener(
				cast.framework.RemotePlayerEventType.VOLUME_LEVEL_CHANGED,
				function(){
					_s.controller_do.updateVolume(_s.remotePlayer.volumeLevel);
				}
			 );
		
			// Play complete handler
			_s.startToCheckPlaybackComplete = function(){
				_s.stopToCheckPlaybackComplete();
				_s.pbc_int = setInterval(_s.checkPlaybackComplete);
			}
			
			_s.stopToCheckPlaybackComplete = function(){
				clearInterval(_s.pbc_int);
			}
			
			_s.checkPlaybackComplete = function(){
				if(_s.getDuration() > 0) _s.isStopped_bl = false;
				if(_s.isSeeking_bl) return;
				if(_s.getCurrentTime() == _s.getDuration() || _s.getDuration() == 0){	
					if(!_s.isStopped_bl){
						_s.stop();
						if(_s.allowToLoop){
							if((main._d.stopVideoWhenPlayComplete_bl || main._d.playlist_ar.length == 1)
							|| (main._d.stopAfterLastVideoHasPlayed_bl && main._d.playlist_ar.length - 1 == main.id)){
								_s.stop();
							}else if(main._d.shuffle_bl){
								main.playShuffle();
							}else if(main._d.loop_bl){
								_s.loadMediLoopId_to = setTimeout(function(){
									_s.loadMedia(true);
								}, 500);
							}else{
								main.playNext();
							}
							_s.allowToLoop = false;
							return;
						}
					}
					
				}
			}
		}
	
		// Load subtitle
		_s.loadSubtitle = function(){
			var castSession = cast.framework.CastContext.getInstance().getCurrentSession();
			var media = castSession.getMediaSession();
			tracksInfoRequest = new chrome.cast.media.EditTracksInfoRequest([main.ccSS]);
			media.editTracksInfo(tracksInfoRequest, function(e){},function(e){console.log(e);});
		}
		
		// Style subtitle
		_s.styleSubtitle = function(mediaInfo){
			var textTrackStyle = new chrome.cast.media.TextTrackStyle([main.ccSS]);
			textTrackStyle.backgroundColor = '#00000000', // see http://dev.w3.org/csswg/css-color/#hex-notation
			textTrackStyle.foregroundColor = '#FFFFFFFF', // see http://dev.w3.org/csswg/css-color/#hex-notation
			textTrackStyle.edgeType = 'DROP_SHADOW', // can be: "NONE", "OUTLINE", "DROP_SHADOW", "RAISED", "DEPRESSED"
			textTrackStyle.edgeColor = '#00000066', // see http://dev.w3.org/csswg/css-color/#hex-notation
			textTrackStyle.fontScale = 1, // transforms into "font-size: " + (fontScale*100) +"%"
			textTrackStyle.fontStyle = 'NORMAL', // can be: "NORMAL", "BOLD", "BOLD_ITALIC", "ITALIC",
			textTrackStyle.fontFamily = 'Droid Sans', // specific font family
			textTrackStyle.fontGenericFamily = 'CURSIVE', // can be: "SANS_SERIF", "MONOSPACED_SANS_SERIF", "SERIF", "MONOSPACED_SERIF", "CASUAL", "CURSIVE", "SMALL_CAPITALS",
			textTrackStyle.windowColor = '#00000066', // see http://dev.w3.org/csswg/css-color/#hex-notation
			textTrackStyle.windowRoundedCornerRadius = 10, // radius in px
			textTrackStyle.windowType = 'ROUNDED_CORNERS' // can be: "NONE", "NORMAL", "ROUNDED_CORNERS"
			mediaInfo.textTrackStyle = textTrackStyle;
		}
		
		// Load media
		_s.loadMedia = function(autoplay){
			
			var path1 = location.origin;
			var path2 = location.pathname;
			_s.videoSource = FWDUVPUtils.getValidSource(main.finalVideoPath_str);
			var posterSource = FWDUVPUtils.getValidSource(main.posterPath_str);
			
			var mediaInfo = new chrome.cast.media.MediaInfo(_s.videoSource);
			mediaInfo.metadata = new chrome.cast.media.GenericMediaMetadata();
			var ct = 'video/mp4';
			if(main.videoType_str == FWDUVPlayer.MP3){
				ct = 'audio/mp3';
			}
		
			mediaInfo.contentType = ct;
			//mediaInfo.metadata.title = 'test';
			mediaInfo.metadata.images = [{'url' : posterSource}];
			_s.styleSubtitle(mediaInfo);
			
			var subData = main._d.playlist_ar[main.id].subtitleSource;
			
			if(subData){
				var tracks = [];
				for(var i=0; i<subData.length - 1; i++){
					var track = new chrome.cast.media.Track(i + 1, chrome.cast.media.TrackType.TEXT);
					track.trackContentId = FWDUVPUtils.getValidSource(subData[i]['source']);
					track.trackContentType = 'text/vtt';
					track.subtype = chrome.cast.media.TextTrackType.SUBTITLES;
					track.name = subData[i]['label'];
					track.customData = null;
					tracks[i] = track;
				}
				
				track = new chrome.cast.media.Track(0, chrome.cast.media.TrackType.TEXT);
				track.trackContentId = FWDUVPUtils.getValidSource('content/subtitles/empty.vtt');
				track.subtype = chrome.cast.media.TextTrackType.SUBTITLES;
				track.name = '';
				track.customData = null;
				track.trackContentType = 'text/vtt';
				tracks.unshift(track);
				mediaInfo.tracks = tracks;
			}
			
			var request = new chrome.cast.media.LoadRequest(mediaInfo);
			
			if(_s.mainPlaying_bl || autoplay || main.isThumbClick_bl){
				request.autoplay = true;
			}else{
				request.autoplay = false;
				_s.pause();
			}
		
			_s.playerState = PLAYER_STATE.BUFFERING;
			
			request.currentTime = main.curTimeInSecond;
			_s.setVolume();
			_s.addPlayerEvents();
			cast.framework.CastContext.getInstance().getCurrentSession().loadMedia(request).then(
				function() {
					if(subData){
						var castSession = cast.framework.CastContext.getInstance().getCurrentSession();
						var media = castSession.getMediaSession();
						_s.controller_do.enableSubtitleButton()
						
						tracksInfoRequest = new chrome.cast.media.EditTracksInfoRequest([main.ccSS]);
						media.editTracksInfo(tracksInfoRequest, function(e){
							if(request.autoplay) _s.changePlayPauseState(PLAYER_STATE.PLAYING);
						},function(e){console.log(e);});
					}
					_s.playerState = PLAYER_STATE.LOADED;
				},
				function (errorCode) {
					_s.playerState = PLAYER_STATE.IDLE;
					console.log('Remote media load error: ' + errorCode);
					_s.updateDisplay();
			  }
		  )
		 
		}
		
		//##########################################//
		/* Setup remove remotePlayer */
		//##########################################//
		_s.updateDisplay = function(param){
			var castSession = cast.framework.CastContext.getInstance().getCurrentSession();
		
			if(castSession && castSession.getMediaSession() && castSession.getMediaSession().media){
				var media = castSession.getMediaSession();
				var mediaInfo = media.media;
				
				if(mediaInfo.metadata){
					_s.mediaTitle = mediaInfo.metadata.title;
					mediaEpisodeTitle = mediaInfo.metadata.episodeTitle;
					// Append episode title if present
					_s.mediaTitle = mediaEpisodeTitle ? _s.mediaTitle + ': ' + mediaEpisodeTitle : _s.mediaTitle;
					// Do not display mediaTitle if not defined.
					_s.mediaTitle = (_s.mediaTitle) ? _s.mediaTitle + ' ' : '';
					mediaSubtitle = mediaInfo.metadata.subtitle;
					mediaSubtitle = (mediaSubtitle) ? mediaSubtitle + ' ' : '';
					_s.deviceName = castSession.getCastDevice().friendlyName;
				}
			}
			var ctn = document.getElementsByClassName("fwdcs_do")[0];
			if(_s.deviceName && ctn) ctn.innerHTML = _s.mediaTitle + _s.playerState + ' on ' + _s.deviceName;
		}
		
		_s.stopCasting = function(){
			try{
				var castSession = cast.framework.CastContext.getInstance().getCurrentSession();
				castSession.endSession(true);
			}catch(e){}
		}
		
		_s.init();
	};
	
	/* set prototype */
	FWDUVPCC.setPrototype = function(){
		FWDUVPCC.prototype = null;
		FWDUVPCC.prototype = new FWDUVPEventDispatcher("div");
	};

	FWDUVPCC.prototype = null;
	window.FWDUVPCC = FWDUVPCC;
}(window));