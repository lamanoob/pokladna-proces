$('#request').ready(function(){
  $('#request').click(function(){
    toggleFullScreen();
    });
  });

function toggleFullScreen() {
  if ((document.fullScreenElement && document.fullScreenElement !== null) ||    // alternative standard method
      (!document.mozFullScreen && !document.webkitIsFullScreen)) {               // current working methods
    if (document.documentElement.requestFullScreen) {
      document.documentElement.requestFullScreen();
    } else if (document.documentElement.mozRequestFullScreen) {
      document.documentElement.mozRequestFullScreen();
    } else if (document.documentElement.webkitRequestFullScreen) {
      document.documentElement.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
    }
  } else {
    if (document.cancelFullScreen) {
      document.cancelFullScreen();
    } else if (document.mozCancelFullScreen) {
      document.mozCancelFullScreen();
    } else if (document.webkitCancelFullScreen) {
      document.webkitCancelFullScreen();
    }
  }
}

document.addEventListener("keydown", function(e) {
 if (e.keyCode == 27) {
    if (document.cancelFullScreen) {
      document.cancelFullScreen();
    } else if (document.mozCancelFullScreen) {
      document.mozCancelFullScreen();
    } else if (document.webkitCancelFullScreen) {
      document.webkitCancelFullScreen();
    }
 }
 
}, false);



/*
var someElement = $('#login'); 
var myButton = $('request');
/*
if (fullScreenApi.supportsFullScreen) {
  alert('aaa');
  myButton.click(function() {
    fullScreenApi.requestFullScreen(someElement);
    });  
  }
else{
  alert('bbbb');
  }
                * /
// do something interesting with fullscreen support
var fsButton = $('#request'),
	fsElement = $('#login'),
	fsStatus = $('#fsstatus');


if (window.fullScreenApi.supportsFullScreen) {
	fsStatus.innerHTML = 'YES: Your browser supports FullScreen';
	fsStatus.className = 'fullScreenSupported';
	
	// handle button click
	fsButton.click(function() {
		window.fullScreenApi.requestFullScreen(fsElement);
	});
	
	fsElement.bind(fullScreenApi.fullScreenEventName, function() {
		if (fullScreenApi.isFullScreen()) {
			fsStatus.innerHTML = 'Whoa, you went fullscreen';
		} else {
			fsStatus.innerHTML = 'Back to normal';
		}
	});
	
} else {
	fsStatus.innerHTML = 'SORRY: Your browser does not support FullScreen';
}
*/