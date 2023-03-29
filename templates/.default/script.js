(function() {
	'use strict';

	if (!!window.JCCatalogSortingComponent)
		return;

	window.JCCatalogSortingComponent = function(params) {
		this.componentPath = params.componentPath || '';
		this.fields = params.fields || [];

		let obj = this;

		BX.bindDelegate(
			document.body,
			'change',
			{ class: "catalog_sort_select" },
			function() {
				obj.changeSorting(this.value);
			}
		);
	};

	window.JCCatalogSortingComponent.prototype.changeSorting = function(key) {
		let sort_element = document.querySelector('input[name="sortby"]');
		let order_element = document.querySelector('input[name="sortorder"]');
		let form = document.forms.elements_sort_form;
		sort_element.value = this.fields[key]["FIELD"];
		order_element.value = this.fields[key]["DIR"];
		form.submit();
	}
})();