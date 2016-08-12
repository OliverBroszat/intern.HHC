/*

ExpandableContent Version 2.0

TODO:
- Question to solve: sould javascript expandable list care about existing data?
>>	YES: We need mustache.js (two versions of mustache)
		In this case, we need to "inject" the data into the generated code (see example)
	NO: We have the expandable list syntax on two different places in out project
		In this case, javascript would load the data via AJAX (time problems?)... 
- ObjectOriented Approach to all functions below
- Add Comments!!!
- Rename everything to expandable LIST (expandable content is something different)
- Create custom elements like <expandablelist>...</expandablelist>
	JS: var expandablelist = document.registerElement('exl-container');
	>> Custom attributes MUST contain a dash
	>> registerElement returns a constructor for this element
	>> My Suggestion: 'exl' for expandablelist, so for every attribute we have 'exl-NAME'
- When having custom element, the expandable-list.js should be included at the beginning of any HTML/PHP code (so that the custom elements are already registered
- Better CSS please! Round buttons, animations, ...

Structure for YES case:

	html/php file (server-side):
	...
	<script src='.../expandable-list.js'></script>
	...
	<exl-container name='AddressContainer' template='exl-templates/address' dataSource='address' size='small'/>
	<exl-container name='MailContainer' template='exl-templates/mail' dataSource='mail' size='small'/>
	<exl-container name='PhoneContainer' template='exl-templates/phone' dataSource='phone' size='normal'/>
	...
	<script>
	var data = <?php echo $data ?>;
	setupExlContainer('AddressContainer', data['Address']);
	setupExlContainer('MailContainer', data['Mail']);
	setupExlContainer('PhoneContainer', data['Phone']);
	</script>
	...

Could be a nice approach. Talk about this!

*/

var option_delete = "<button type='button' name='expandablecontent-link'"+
	"class='expandablecontent-option-delete' onClick='delete_content(\"%%LIST-ID%%\", \"%%ELEMENT-ID%%\");'>-</button>";

var option_append = "<button type='button' name='expandablecontent-link'"+
	"class='expandablecontent-option-add' onClick='add_content(\"%%LIST-ID%%\", []);'>+</button>";

function full_ID(list_ID, element_ID) {
	return String(list_ID)+'$'+String(element_ID);
}

function delete_content(list_ID, element_ID) {
	document.getElementById(list_ID).removeChild(document.getElementById(full_ID(list_ID, element_ID)));
}

function add_content(list_ID, withData) {
	var list = document.getElementById(list_ID);
	var current_counter = list.firstChild;
	var current_number = parseInt(current_counter.value);
	var current_template = replace_with_ID(list.childNodes[1].value, list_ID, current_number);
	// Insert data
	//alert(withData);
	for (key in withData) {
		//alert('Ersetze '+data);
		var regex = new RegExp("%%DATA-" + key + "%%",'g');
		current_template = current_template.replace(regex, withData[key]);
	}
	current_template = current_template.replace(/%%.*?%%/g,'');
	var new_content = createContentElement(list_ID, current_number, current_template);
	list.insertBefore(new_content, document.getElementById('expandablecontent-addbar-'+list.id));
	current_counter.value = String(current_number+1);
}

function replace_with_ID(template, list_ID, element_ID) {
	var full_id = String(list_ID)+'--'+String(element_ID);
	return template.replace(/%%FULL-ID%%/g, full_id).replace(/%%LIST-ID%%/g, list_ID).replace(/%%ELEMENT-ID%%/g, element_ID);
}

function createEditBar(list_ID, element_ID) {
	var bottom_bar = document.createElement('li');
	bottom_bar.classList.add('expandablecontent-bar');
	bottom_bar.innerHTML = option_edit;
}

function createAppendBar(list) {
	var bottom_bar = document.createElement('li');
	bottom_bar.id = 'expandablecontent-addbar-'+list.id;
	bottom_bar.classList.add('expandablecontent-listitem-bar');
	var bar_div = document.createElement('div');
	bar_div.classList.add('expandablecontent-bar');
	bar_div.classList.add('expandablecontent-bar-option-add');
	if (list.classList.contains('small')) {
		bar_div.classList.add('small');
	}
	bar_div.innerHTML = replace_with_ID(option_append, list.id, -1);
	bottom_bar.appendChild(bar_div);
	return bottom_bar;
}

function createContentElement(list_ID, element_ID, content_html) {
	var full_id = list_ID+'$'+element_ID;
	var li = document.createElement('li');
	li.classList.add('expandablecontent-listitem-content');
	li.id = String(full_id);
	var edit_bar = document.createElement('div');
	edit_bar.classList.add('expandablecontent-bar');
	edit_bar.classList.add('expandablecontent-bar-option-delete');
	if (document.getElementById(list_ID).classList.contains('small')) {
		edit_bar.classList.add('small');
	}
	edit_bar.innerHTML = replace_with_ID(option_delete, list_ID, element_ID);
	var content = document.createElement('div');
	content.classList.add('expandablecontent-content');
	content.innerHTML = replace_with_ID(content_html, list_ID, element_ID);
	li.appendChild(edit_bar);
	li.appendChild(content);
	return li;
}

function setup_expandablecontent(container_id, list_id, html_template, withData, blankTemplates) {
	//Get container
	var container = document.getElementById(container_id);
	var small = false;
	if (container.classList.contains('small')) {
		small = true;
	}
	// Setup list
	var ul = document.createElement('ul');
	ul.id = String(list_id);
	ul.classList.add('expandablecontent-list');
	if (small) {
		ul.classList.add('small');
	}
	var meta = document.createElement('div');
	meta.name = 'expandablecontent-counter';
	meta.type = 'hidden';
	meta.value = '2';
	ul.appendChild(meta);

	var template = document.createElement('div');
	template.name = 'template';
	template.type = 'hidden';
	template.value = html_template;
	ul.appendChild(template);

	var bottom = createAppendBar(ul);
	bottom.classList.add('expandablecontent-last');
	ul.appendChild(bottom);
	document.getElementById(container_id).appendChild(ul);
	// Setup content (jeder content ist ein li element mit einer bar und dem content div drin)
	//alert(JSON.stringify(withData));
	for (var i=0; i<withData.length; i++) {
		add_content(ul.id, withData[i]);
	}

	 if (withData.length == 0) { 
	    for (var i=0; i<blankTemplates; i++) { 
	      add_content(ul.id, null); 
	    } 
	}
}