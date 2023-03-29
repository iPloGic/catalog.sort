function initListSortingControl(arParams) {
	window.jsListSortingParameterControl = new ListSortingParameterControl(arParams);
}

function ListSortingParameterControl(arParams) {
	this.params = arParams || {};
	this.data = arParams.data || {};
	this.data = JSON.parse(this.data);
	this.message = JSON.parse(arParams.propertyParams.JS_MESSAGES) || {};
	this.path = this.getPath();
	this.fields = [];

	let obj = this;

	this.init();

	BX.bindDelegate(
		document.body,
		'change',
		{ class: "ipl-catalog-sort-type" },
		function() {
			obj.changeType(this.getAttribute('data-group'), this.value);
		}
	);

	BX.bindDelegate(
		document.body,
		'change',
		{ class: "ipl-catalog-sort-field" },
		function() {
			obj.changeField(
				this.getAttribute('data-group'),
				this.getAttribute('data-name'),
				this.value
			);
		}
	);

	BX.bindDelegate(
		document.body,
		'input',
		{ class: "ipl-catalog-sort-field" },
		function() {
			obj.changeField(
				this.getAttribute('data-group'),
				this.getAttribute('data-name'),
				this.value
			);
		}
	);

	BX.bindDelegate(
		document.body,
		'click',
		{ class: "ipl-catalog-sort-add-group" },
		function(e) {
			obj.add();
		}
	);

	BX.bindDelegate(
		document.body,
		'click',
		{ class: "ipl-catalog-sort-delete-group" },
		function() {
			obj.delete(this.getAttribute('data-group'));
		}
	);

	this.show();
}

ListSortingParameterControl.prototype.init = function() {
	this.container = BX.create('div', {attrs: {className: 'ipl-catalog-sort-wrapper'}});
	this.params.oCont.appendChild(this.container);
}

ListSortingParameterControl.prototype.show = function() {
	data = {
		action: 'show',
	};
	this.sendRequest(data, true);
}

ListSortingParameterControl.prototype.changeType = function(group, value) {
	data = {
		action: 'change_type',
		group: group,
		type: value,
	};
	this.sendRequest(data, true);
}

ListSortingParameterControl.prototype.changeField = function(group, name, value) {
	if(value === null) {
		value = "";
	}
	this.fields[group][name] = value;
	this.params.oInput.value = JSON.stringify(this.fields);
}

ListSortingParameterControl.prototype.add = function() {
	data = {
		action: 'add',
	};
	this.sendRequest(data, true);
}

ListSortingParameterControl.prototype.delete = function(group) {
	data = {
		action: 'delete',
		group: group,
	};
	this.sendRequest(data, true);
}

ListSortingParameterControl.prototype.sendRequest = function(data, sendHtml) {
	//sendHtml = sendHtml || true;
	data.fields = this.params.oInput.value;
	data.component_path = this.data.componentPath;
	data.sessid = BX.bitrix_sessid();
	BX.ajax({
		timeout: 60,
		method: 'POST',
		dataType: 'html',
		url: this.path + '/sorting.php',
		data: data,
		onsuccess: BX.proxy(function(_result){
			this.pushResult(_result, sendHtml);
		}, this)
	})
}

ListSortingParameterControl.prototype.pushResult = function(_result, sendHtml) {
	let result = JSON.parse(_result)
	this.fields = result.fields;
	this.params.oInput.value = JSON.stringify(result.fields);
	if(sendHtml) {
		this.container.innerHTML = result.html;
	}
}

ListSortingParameterControl.prototype.getPath = function() {
	let path = this.params.propertyParams.JS_FILE.split('/');
	path.pop();
	return path.join('/');
}