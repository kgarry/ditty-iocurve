$(function(){
	$('.heroRange, .hero').bind({
		mouseover: function() {
			$('.heroRange').addClass('heroRangeActive');
		},
		mouseout: function() {
			$('.heroRange').removeClass('heroRangeActive');
		}
	});

	$('.hero').bind({
		click: function() {
			expose('hero-console', '#', renderHeroConsole());
		}
	});
});

// ----- ---- --- -- - GENERIC FUNCS 

/***
* pass position or let CSS do that?
* move to game.js?
***/
function expose(selector, selectorPre, html) {
	if ($(selectorPre + selector).length < 1) {
		$('#arena').after('<div id='+selector+'></div>');
	}
	var target = $(selectorPre + selector);
	target.html(html);
	var closer = selector + '-close';
	target.append('<div class="closer" id="'+closer+'">X</div>');
	
	var closerSel = '#' + closer;
	closerSel = $(closerSel);
	closerSel.bind("click", function(){
		closerSel.parent().remove();
	});
}

// ----- ---- --- -- - SPECIFIC funcs
function renderHeroConsole() {
	var html = 'Choose Action: <select id="">' +
		'<option>Move</option><option>Terraform</option>' +
		'</select>';
	html += '<br>';
	html += '[Movement: 2/2 ] [Terraform: 1/1] [Attack: N/A]';

	return html;
}
