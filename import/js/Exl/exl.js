/**
 * Created by PhpStorm.
 * User: Alexander Schaefr
 * Date: 14.08.2016
 * Time: 03:30
 *
 * ExpandableContent Version 2.0
 *
 */

/*
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

---------------------------------------------------------------------------------------------------------------

$options =  array('extension' => '.html');
$root = $root = get_template_directory();
$m = new Mustache_Engine(array(
    'loader' => new Mustache_Loader_FilesystemLoader($root . '/views', $options),
));
echo $m->render('test', array('name' => 'Peter'));



$.get('template.mst', function(template) {
	var rendered = Mustache.render(template, {name: "Luke"});
	$('#target').html(rendered);
});

var view = {
  title: "Joe",
  calc: function () {
    return 2 + 4;
  }
};

var output = Mustache.render("{{title}} spends {{calc}}", view);
console.log(output);
document.getElementById('text').innerHTML = output;

---------------------------------------------------------------------------------------------------------------

*/


function ExlClass() {

	if (!this instanceof ExlClass) {
		return new Exl();
	}
	this.setupDefaultConfiguration = function() {
		this.config = generateDefaultConfiguration();
	}
	this.setupDefaultConfiguration();
	registerCustomHTMLTags();

	/**
	 * registerCustomHTMLTags
	 *
	 * Registers custom HTML5 elements for better reading :-)
	 *
	 */
	function registerCustomHTMLTags() {

		document.registerElement('exl-container');
		document.registerElement('exl-wrapper');
		document.registerElement('exl-list');
		document.registerElement('exl-listitem');
		document.registerElement('exl-content');

	}

	/**
	 * 
	 * generateDefaultConfiguration
	 * 
	 * Sets up a config object to store path variables and other options
	 * 
	*/
	function generateDefaultConfiguration() {

		// Set up defaul configuration
		var pathToThisFile = document.currentScript.src;
		var pathWithoutFile = pathToThisFile.match(/(.*)[\/\\]/)[1]||'';
		exlConfig = {
			'root_path': pathWithoutFile+'/',
			'template_path': pathWithoutFile+'/'+'exlTemplates/'
		};
		console.log('ROOT PATH: '+exlConfig['root_path']);
		console.log('TMPL PATH: '+exlConfig['template_path']);
		return exlConfig;

	}

	/**
	 * loadFileByUrl
	 * 
	 * Makes a synchronous (!!!) AJAX call to load a resources and return this resource
	 * NOTE: synchronous calls are deprecated! Should be fixed ASAP.
	 * 
	*/
	function loadFileByUrl(fileUrl) {
		var loadedFile = null;
		jQuery.ajax({
	        url: fileUrl,
	        success: function(result) {
				loadedFile = result;
			},
	        async: false
	    });
	    return loadedFile;
	}

	/**
	 * getTemplateByName
	 *
	 * Load a mustache template object from resources/templates/expandable-list/
	 *
	 * @param templateName The name of the template you want to load
	 * @returns null
	 */
	function getTemplateByName(templateName) {

		var templateUrl = Exl.config['template_path']+templateName+'.html';
		return loadFileByUrl(templateUrl);

	}


	/**
	 * getWrapperTemplate
	 *
	 * Loads the exl wrapper template from exl root path
	 *
	 * @returns null
	 */
	function getWrapperTemplate() {

		var templateUrl = Exl.config['root_path']+'__exl-template.html';
		return loadFileByUrl(templateUrl);

	}


	/**
	 * getContentTEmplateForExlContainer
	 *
	 * Loads a mustache template that is given in an 'exl-container' node's 'template' attribute
	 *
	 * @param exlContainer The 'exl-container' DOM element
	 * @returns null
	 */
	function getContentTemplateForExlContainer(exlContainer) {

		var templateName = exlContainer.getAttribute('template');
		var template = getTemplateByName(templateName);
		return template;

	}


	/**
	 * getRenderedContantArrayForExlContainer
	 *
	 * Renders a exlContainer's content template with given data and stores all rendered templates in an array together with its respective ID
	 *
	 * @param exlContainer The 'exl-container' DOM element
	 * @param data An array containing data for the content template
	 * @returns {Array} Contains fully rendered content template with IDs
	 */
	function getRenderedContentArrayForExlContainer(exlContainer, data) {

		contentTemplate = getContentTemplateForExlContainer(exlContainer);
		var exl_content = new Array();
		var i=0;
		for (var index in data) {
			var dataset = data[index];
			dataset['exl-content-id'] = i;
			dataset['exl-content-full-id'] = exlContainer.id+'-'+i;
			var content = Mustache.render(contentTemplate, dataset);
			exl_content.push({
				'exl-content-id': i,
				'exl-content-rendered': content
			});
			i++;
		}
		return exl_content;

	}


	/**
	 * getExlDataArrayForContainerWithData
	 *
	 * Sets up the full data structure which is passed to mustache when rendering the expandable list. If 'data' contains actual data rows, the list's content template will be pre-filled with the given data.
	 *
	 * @param exlContainer
	 * @param data
	 * @returns Can be used to render __exl-template.html with mustache
	 */
	function getExlDataArrayForContainerWithData(exlContainer, data) {

		var size = 'normal';
		if (exlContainer.classList.contains('small')) {
			size = 'small';
		}
		var renderedExlContent = getRenderedContentArrayForExlContainer(exlContainer, data)
		var exlData = {
			'exl-id': exlContainer.id,
			'exl-size': size,
			'exl-data': data,
	        'exl-number-of-items': renderedExlContent.length,
			'exl-content-template': getContentTemplateForExlContainer(exlContainer),
			'exl-content': renderedExlContent
		}
		return exlData;

	}


	/**
	 * createEmptyExlContent
	 *
	 * Generates an empty content template that is ready-to-install in an exl-listitem node
	 *
	 * @param contentTemplate The content template name
	 * @param exlContainerID 'id' attribute of ext-container tag
	 * @param newItemID The list item's new ID
	 * @returns {Element} exl-content node
	 */
	function createEmptyExlContent(contentTemplate, exlContainerID, newItemID) {

		var newExlContent = document.createElement('exl-content');
		newExlContent.id = 'exl-content-'+exlContainerID+'-'+newItemID;
		newExlContent.classList.add('exl', 'content');

		var renderedContentTemplate = Mustache.render(contentTemplate, {});
		newExlContent.innerHTML = renderedContentTemplate;
		return newExlContent;

	}


	/**
	 * createNewDeleteButton
	 *
	 * Generates a new button element, that will delete a specific list item in a given exl-container. This button will be included in a new exl-listitem node.
	 *
	 * @param exlContainerID exlContainer's ID
	 * @param itemID ID of the listitem to delete
	 * @returns {Element} HTML button element
	 */
	function createNewDeleteButton(exlContainerID, itemID) {

		var newDeleteButton = document.createElement('button');
		newDeleteButton.type = 'button';
		newDeleteButton.id = 'exl-button-delete-'+exlContainerID+'-'+itemID;
		newDeleteButton.classList.add('exl', 'button', 'delete');
		newDeleteButton.setAttribute('onClick', 'Exl.deleteListItemForContainerWithID("'+exlContainerID+'", '+itemID+');');
		newDeleteButton.innerHTML = '-';
		return newDeleteButton;

	}


	/**
	 * createEmptyListItem
	 *
	 * Generates a new exl-listitem node without any data stored inside
	 *
	 * @param contentTemplate content template
	 * @param exlContainerID ID of the respective exl-container
	 * @param newItemID New listitem's ID
	 * @returns {Element} Empty exl-listitem node
	 */
	function createEmptyListItem(contentTemplate, exlContainerID, newItemID) {

		var newListItem = document.createElement('exl-listitem');
		newListItem.id = 'exl-listitem-'+exlContainerID+'-'+newItemID;
		newListItem.classList.add('exl', 'listitem');

		var newExlContent = createEmptyExlContent(contentTemplate, exlContainerID, newItemID);
		var newDeleteButton = createNewDeleteButton(exlContainerID, newItemID);
		newListItem.appendChild(newExlContent);
		newListItem.appendChild(newDeleteButton);

		return newListItem;
	}


	/**
	 * updateItemCounterForID
	 *
	 * Increments the number of total added exl-list elements.
	 *
	 * @param exlContainerID The exl-container's ID
	 */
	function updateItemCounterForID(exlContainerID) {
	    var itemCounterNode = document.getElementById('exl-item-counter-'+exlContainerID);
	    var currentValue = parseInt(itemCounterNode.value);
	    currentValue = currentValue + 1;
	    itemCounterNode.value = currentValue;
	}


	/**
	 * addNewListItemForContainerWithID
	 *
	 * Automatically adds a new exl-listitem node to the exl-list for a given exl-container ID
	 *
	 * @param exlContainerID The exl-container's ID
	 */
	this.addNewListItemForContainerWithID = function(exlContainerID) {

		var container = document.getElementById(exlContainerID);
		var contentTemplate = document.getElementById('exl-content-template-'+exlContainerID).value;

		var exlListNode = document.getElementById('exl-list-'+exlContainerID);
		var currentMaxID = parseInt(document.getElementById('exl-item-counter-'+exlContainerID).value);
		var newItemID = currentMaxID + 1;

		var newListItem = createEmptyListItem(contentTemplate, exlContainerID, newItemID);
		exlListNode.appendChild(newListItem);
	    updateItemCounterForID(exlContainerID, +1);

	}


	/**
	 * deleteListItemForContainerWithID
	 *
	 * Deletes a exl-listitem node from the exl-list node of a given exl-container
	 *
	 * @param exlContainerID The exl-container's ID
	 * @param listItemID The exl-listitem's ID
	 */
	this.deleteListItemForContainerWithID = function(exlContainerID, listItemID) {
		var exlList = document.getElementById('exl-list-'+exlContainerID);
		var fullItemID = 'exl-listitem-'+exlContainerID+'-'+listItemID;
		var listItem = document.getElementById(fullItemID);
		// TODO: NOTE: element.removeChild() should actually not be used due to poor browser support
		exlList.removeChild(listItem);

	}


	/**
	 * setupExlContainerWithData
	 *
	 * Sets up an exl container based on a given dataset to fill placeholders in the container's template. 'data' can be empty. In this case, no template will be prefilled. This function will insert generated HTML code into the exl-container tag.
	 *
	 * @param exlContainer exl-container DOM element
	 * @param data Datastructure as json
	 */
	this.setupExlContainerWithData = function (exlContainer, data) {
		var dataSourceName = exlContainer.getAttribute('source');
		var containerData = data[dataSourceName];
		var exlFormattedData = getExlDataArrayForContainerWithData(exlContainer, containerData);
		var exlWrapperTemplate = getWrapperTemplate('__exl-template');
	    var fullResult = Mustache.render(exlWrapperTemplate, exlFormattedData);
	    exlContainer.innerHTML = fullResult;
	}

	/**
	 * setupExlContainerWithID
	 *
	 * Set up a single exl-container by ID and datastructure
	 *
	 * @param exlContainerID The exl-container's ID
	 * @param data Datastructure as json
	 */
	this.setupExlContainerWithID = function(exlContainerID, data) {
		var exlContainer = document.getElementById(exlContainerID);
		this.setupExlContainerWithData(exlContainer, data);
	}


	/**
	 * setupAllExlContainersWithData
	 *
	 * This function does the following for each <exl-containter> Tag:
	 * - Get the container's dataset (given by the tag's 'source' attribute)
	 * - Call setupExlContainerWithData passing the container's dataset
	 *
	 * @param data Datastructure as json
	 */
	this.setupAllExlContainersWithData = function(data) {
		var elements = document.getElementsByTagName('exl-container');

		for (var i=0; i<elements.length; i++) {
			var container = elements[i];
			this.setupExlContainerWithData(container, data);
		}
	}

}

var Exl = new ExlClass();
console.log(Exl);
console.log(Exl.initializeExpandableList);
console.log(Exl.config)





