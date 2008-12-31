window.addEvent('load', function() {
	$('add_new_package').addEvent('click', swapPackagePopupForTextField);
});

function swapPackagePopupForTextField() {
	var input = new Element('input', {'type':'text', 'name':'package', 'id':'package_tf'});
	var packagePopup = $('package');
	input.injectAfter(packagePopup);
	packagePopup.setStyle('display', 'none');
	packagePopup.setAttribute("name", "package_");
	
	$('add_new_package').set('text', "Old package");
	$('add_new_package').removeEvents();
	$('add_new_package').addEvent('click', swapPackageTextFieldForPopup);
}

function swapPackageTextFieldForPopup() {
	$('package_tf').getParent().removeChild($('package_tf'));
	var packagePopup = $('package');
	packagePopup.setStyle('display', '');
	packagePopup.setAttribute('name', 'package');
	
	var a = $('add_new_package');
	a.set('text', 'New package');
	a.removeEvent();
	a.addEvent('click', swapPackagePopupForTextField);
}