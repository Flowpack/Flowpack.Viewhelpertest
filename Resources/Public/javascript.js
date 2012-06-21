Ext.onReady(function() {
	Ext.QuickTips.init();
	var results = Ext.get('results');
	if (results != null) {
		var successfulTests = Ext.select('.viewhelpertest .testcase.success');
		var failedTests = Ext.select('.viewhelpertest .testcase.failure');
		successfulTests.setVisibilityMode(Ext.Element.DISPLAY);
		Ext.select('.viewhelpertest .testcase.success').hide();
		var collapsed = true;
		results.update('Tests: ' + (successfulTests.elements.length + failedTests.elements.length) + ', <span class="success">Successful: ' + successfulTests.elements.length + '</span>, <span class="failure">Failures: ' + failedTests.elements.length + '</span> - ');
		Ext.DomHelper.append('results', {tag: 'a', href: '#', html: 'expand all', id: 'expandLink'});
		Ext.fly('expandLink').on('click', function(e) {
			e.preventDefault();
			if (collapsed) {
				successfulTests.show();
				this.update('collapse succesful');
			} else {
				successfulTests.hide();
				this.update('expand all');
			}
			collapsed = !collapsed;
		});
	}
});