$(function() {
	var resultsPane = $('#results');
	if (resultsPane.length === 0) {
		return;
	}
	var successfulTests = $('.viewhelpertest .testcase.success');
	var failedTests = $('.viewhelpertest .testcase.failure');
	successfulTests.hide();
	var numberOfTests = (successfulTests.length + failedTests.length);

	resultsPane.html('Tests: ' + numberOfTests + ', <span class="success">Successful: ' + successfulTests.length + '</span>, <span class="failure">Failures: ' + failedTests.length + '</span> ');
	if (numberOfTests > 0) {
		var collapsed = true;
		var expandLink = $('<a id="expandLink">').attr('href', '#').text('expand all');
		expandLink.on('click', function(event) {
			event.preventDefault();
			if (collapsed) {
				successfulTests.show();
				expandLink.text('collapse successful');
			} else {
				successfulTests.hide();
				expandLink.text('expand all');
			}
			collapsed = !collapsed;
		});
		resultsPane.append(expandLink);
	}
});