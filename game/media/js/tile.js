function showHUD(thisX) {
	var controls = '<div>Hi I like Dinosaurs!' + 
		'<div class="closeParent" onclick="$(this).parent().remove()">[Close x]</div></div>';
	return controls;
}

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
			$('#controls').append(showHUD());
		}
	});

	/*$('.closeParent').bind({
                click: function() {
                        $(this).parent().remove();
console.log('innnnnnnnnnnnnnnn');
                }
        });*/
});
