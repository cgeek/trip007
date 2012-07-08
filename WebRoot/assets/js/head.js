define(function(require, exports, module){
	var $ = require('jquery');
	require('plugins')($);
	require('bootstrap')($);
	//IE678 placeholder
	$('input[placeholder], textarea[placeholder]').placeholder();

	$('[rel=tooltip]').tooltip('hide');

	$("[rel=popover]").popover({
		offset: 10,
		html:true,
		live:true
	});
});
