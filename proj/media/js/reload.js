function period_reload() {
	sleep(5);
	window.location.href = window.location.href;
}

$(document).ready(function() {
  period_reload();
});
