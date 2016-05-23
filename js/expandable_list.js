/*

ExpandableContent

*/

var option_delete = "<button type='button' name='expandablecontent-link'"+
	"class='expandablecontent-option-delete' onClick='delete_content(\"%LIST-ID%\", \"%ELEMENT-ID%\");'>-</button>";

var option_append = "<button type='button' name='expandablecontent-link'"+
	"class='expandablecontent-option-add' onClick='add_content(\"%LIST-ID%\");'>+</button>";

function full_ID(list_ID, element_ID) {
	return String(list_ID)+'$'+String(element_ID);
}

function delete_content(list_ID, element_ID) {
	document.getElementById(list_ID).removeChild(document.getElementById(full_ID(list_ID, element_ID)));
}

function add_content(list_ID) {
	var list = document.getElementById(list_ID);
	var current_counter = list.firstChild;
	var current_number = parseInt(current_counter.value);
	var current_template = replace_with_ID(list.childNodes[1].value, list_ID, current_number);
	var new_content = createContentElement(list_ID, current_number, current_template);
	list.insertBefore(new_content, document.getElementById('expandablecontent-addbar-'+list.id));
	current_counter.value = String(current_number+1);
}

function replace_with_ID(template, list_ID, element_ID) {
	var full_id = String(list_ID)+'$'+String(element_ID);
	return template.replace(/%FULL-ID%/g, full_id).replace(/%LIST-ID%/g, list_ID).replace(/%ELEMENT-ID%/g, element_ID);
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
	edit_bar.innerHTML = replace_with_ID(option_delete, list_ID, element_ID);
	var content = document.createElement('div');
	content.classList.add('expandablecontent-content');
	content.innerHTML = replace_with_ID(content_html, list_ID, element_ID);
	li.appendChild(edit_bar);
	li.appendChild(content);
	return li;
}

function setup_expandablecontent(container_id, list_id, html_template) {
	// Setup list
	var ul = document.createElement('ul');
	ul.id = String(list_id);
	ul.classList.add('expandablecontent-list');

	var meta = document.createElement('input');
	meta.name = 'expandablecontent-counter';
	meta.type = 'hidden';
	meta.value = '2';
	ul.appendChild(meta);

	var template = document.createElement('input');
	template.name = 'teplate';
	template.type = 'hidden';
	template.value = html_template;
	ul.appendChild(template);

	// Setup content (jeder content ist ein li element mit einer bar und dem content div drin)
	if (html_template != '') {
		ul.appendChild(createContentElement(list_id, 1, html_template));
	}
	var bottom = createAppendBar(ul);
	bottom.classList.add('expandablecontent-last');
	ul.appendChild(bottom);
	document.getElementById(container_id).appendChild(ul);
}