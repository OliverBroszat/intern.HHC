/*

ExpandableContent

*/

option_edit = "<a href='' "+
	"class='expandablecontent-option-delete'>Löschen</a>";

option_append = "<a href=''"+
	"class='expandablecontent-option-add'>Einfügen</a>";

function createEditBar(withID) {
	var bottom_bar = document.createElement('li');
	bottom_bar.classList.add('expandablecontent-bar');
	bottom_bar.innerHTML = option_edit;
}

function createAppendBar(withID) {
	var bottom_bar = document.createElement('li');
	bottom_bar.classList.add('expandablecontent-listitem-bar');
	var bar_div = document.createElement('div');
	bar_div.classList.add('expandablecontent-bar');
	bar_div.innerHTML = option_append;
	bottom_bar.appendChild(bar_div);
	return bottom_bar;
}

function createContentElement(withID, html) {
	var li = document.createElement('li');
	li.classList.add('expandablecontent-listitem-content')
	var edit_bar = document.createElement('div');
	edit_bar.classList.add('expandablecontent-bar');
	edit_bar.innerHTML = option_edit;
	var content = document.createElement('div');
	content.classList.add('expandablecontent-content');
	content.innerHTML = html;
	li.appendChild(edit_bar);
	li.appendChild(content);
	return li;
}

function setup_expandablecontent(container_id, list_id, html_template) {
	// Setup list
	var ul = document.createElement('ul');
	ul.id = String(list_id);
	ul.classList.add('expandablecontent-list');
	// Setup content (jeder content ist ein li element mit einer bar und dem content div drin)
	if (html_template != '') {
		ul.appendChild(createContentElement(html_template));
	}
	var bottom = createAppendBar()
	bottom.classList.add('expandablecontent-last');
	ul.appendChild(bottom);
	document.getElementById(container_id).appendChild(ul);
}