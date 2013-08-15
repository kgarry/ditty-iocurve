var defaultMssg = 'Type your message ...';

function clearInput(obj, orig) {
    if (obj.val() == orig) {
        obj.val('');
    }
}

function uniqID(idlength) {
	var possible = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz';
	if (! idlength) {
		var idlength = 20;
	}
	var uniqid = '';
	for (var i = 0; i < length; i++) {
		uniqid += possible.charAt(Math.floor(Math.random() * possible.length));
//charstoformid[Math.floor(Math.random() * charstoformid.length)];
	}
	
	return uniqid;
}

/***
* could have an id , useful if we wanted to print local send message and update when socket sends it back
***/
function appendMessage(mssg, id) { 
    $('#messagewindow').append('<div class="messageLog" id="' + id + '"><code>' + mssg + '</code></div>');
	$('#'+id).focus();
	$('#'+id).val(id);
//messagewindow').animate({ scrollTop: $('#messagewindow').height() }, "slow");
}

function assureRatchet() {
// check ratchet vs heartbeat
}


/**
*
**/
$(document).ready(function () {
	// setup WS
	if (!("WebSocket" in window)) { 
		$('#chatwindow').val('<div id="nowebsocketsupport">This service will not work here; your browser does not support websockets.</div>');
		console.log('no WS support');

		return false;
	}

	var host='ditty.iocurve.com';
	var port='8081';
	var conn = new WebSocket('ws://' + host + ':' + port);
	console.log(conn);
	
    conn.onopen = function(e) {
		console.log("Connection established!");
    };

    conn.onmessage = function(e) {
		var uniq = uniqID(8);
        appendMessage(e.data, uniq);
		console.log('onmessage: appending message');
    };

	// setup client listeners
	var mssg = $('#message');
	mssg.val(defaultMssg);

    $('#message').mouseup(function() {
        clearInput($(this), defaultMssg);
    });

	$('#message').keyup(function(e) {
		var whichKey = (e.keyCode ? e.keyCode : e.which);
 		if (whichKey == 13) { //Enter key
			appendMessage(mssg.val(), 'local_' + uniqID(18));
			mssg.val('');
        	conn.send(mssg.val()); // needs ratchet
			console.log('sent a mssg via enter key');
		}
    });

    $('#send').mouseup(function() {
        conn.send(mssg.val()); // needs ratchet
		appendMessage(mssg.val(), 'local_'+uniqID(18)); //temp fixme
		mssg.val(defaultMssg);
		console.log('sent a mssg via mouse');
    });

// onDelay 5 seconds assureRatchet().. ?
});
