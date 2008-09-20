/**
 * Sjonsite - Admin Javascript Library
 *
 * @author Sjon <sjonscom@gmail.com>
 * @package Sjonsite
 * @copyright Sjon's dotCom 2008
 * @license Mozilla Public License 1.1
 * @version $Id$
 */

$(document).ready(function() {

	$('.wymeditor').wymeditor({
		basePath: '/js/jquery-wymeditor-0.5.a/',
		skinPath: '/js/jquery-wymeditor-0.5.a/skin/',
		jQueryPath: '/js/jquery-1.2.6.pack.js',
		wymPath: '/js/jquery-wymeditor-0.5.a.pack.js',
		iframeBasePath: '/js/jquery-wymeditor-0.5.a/iframe/',
		stylesheet: '/css/wymeditor.css',
		lang: 'en',
		logoHtml: '',
		postInit: function (wym) {
			//render the containers box as a panel and remove the span containing the '>'
			jQuery(wym._box).find(wym._options.containersSelector).removeClass('wym_dropdown').addClass('wym_panel').find('h2 > span').remove();
			//adjust the editor's height
			jQuery(wym._box).find(wym._options.iframeSelector).css('height', '400px');
		},
		toolsItems: [
			{'name': 'Bold', 'title': 'Strong', 'css': 'wym_tools_strong'},
			{'name': 'Italic', 'title': 'Emphasis', 'css': 'wym_tools_emphasis'},
			{'name': 'Superscript', 'title': 'Superscript', 'css': 'wym_tools_superscript'},
			{'name': 'Subscript', 'title': 'Subscript', 'css': 'wym_tools_subscript'},
			{'name': 'InsertOrderedList', 'title': 'Ordered_List', 'css': 'wym_tools_ordered_list'},
			{'name': 'InsertUnorderedList', 'title': 'Unordered_List', 'css': 'wym_tools_unordered_list'},
			{'name': 'Indent', 'title': 'Indent', 'css': 'wym_tools_indent'},
			{'name': 'Outdent', 'title': 'Outdent', 'css': 'wym_tools_outdent'},
			{'name': 'Undo', 'title': 'Undo', 'css': 'wym_tools_undo'},
			{'name': 'Redo', 'title': 'Redo', 'css': 'wym_tools_redo'},
			{'name': 'CreateLink', 'title': 'Link', 'css': 'wym_tools_link'},
			{'name': 'Unlink', 'title': 'Unlink', 'css': 'wym_tools_unlink'},
			{'name': 'InsertImage', 'title': 'Image', 'css': 'wym_tools_image'},
			{'name': 'InsertTable', 'title': 'Table', 'css': 'wym_tools_table'},
			{'name': 'Paste', 'title': 'Paste_From_Word', 'css': 'wym_tools_paste'},
			{'name': 'ToggleHtml', 'title': 'HTML', 'css': 'wym_tools_html'},
			{'name': 'Preview', 'title': 'Preview', 'css': 'wym_tools_preview'}
		],
		containersItems: [
			{'name': 'P', 'title': 'Paragraph', 'css': 'wym_containers_p'},
			//{'name': 'H1', 'title': 'Heading_1', 'css': 'wym_containers_h1'},
			{'name': 'H2', 'title': 'Heading_2', 'css': 'wym_containers_h2'},
			{'name': 'H3', 'title': 'Heading_3', 'css': 'wym_containers_h3'},
			{'name': 'H4', 'title': 'Heading_4', 'css': 'wym_containers_h4'},
			{'name': 'H5', 'title': 'Heading_5', 'css': 'wym_containers_h5'},
			{'name': 'H6', 'title': 'Heading_6', 'css': 'wym_containers_h6'},
			{'name': 'PRE', 'title': 'Preformatted', 'css': 'wym_containers_pre'},
			{'name': 'BLOCKQUOTE', 'title': 'Blockquote', 'css': 'wym_containers_blockquote'},
			{'name': 'TH', 'title': 'Table_Header', 'css': 'wym_containers_th'}
		],
		classesItems: [
		]
	});

	/** yes, i need to read more docs if the only thing i want is a diffrent height **/
	$('.wymeditor-small').wymeditor({
		basePath: '/js/jquery-wymeditor-0.5.a/',
		skinPath: '/js/jquery-wymeditor-0.5.a/skin/',
		jQueryPath: '/js/jquery-1.2.6.pack.js',
		wymPath: '/js/jquery-wymeditor-0.5.a.pack.js',
		iframeBasePath: '/js/jquery-wymeditor-0.5.a/iframe/',
		stylesheet: '/css/wymeditor.css',
		lang: 'en',
		logoHtml: '',
		postInit: function (wym) {
			//render the containers box as a panel and remove the span containing the '>'
			jQuery(wym._box).find(wym._options.containersSelector).removeClass('wym_dropdown').addClass('wym_panel').find('h2 > span').remove();
			//adjust the editor's height
			jQuery(wym._box).find(wym._options.iframeSelector).css('height', '100px');
		},
		toolsItems: [
			{'name': 'Bold', 'title': 'Strong', 'css': 'wym_tools_strong'},
			{'name': 'Italic', 'title': 'Emphasis', 'css': 'wym_tools_emphasis'},
			{'name': 'Superscript', 'title': 'Superscript', 'css': 'wym_tools_superscript'},
			{'name': 'Subscript', 'title': 'Subscript', 'css': 'wym_tools_subscript'},
			{'name': 'InsertOrderedList', 'title': 'Ordered_List', 'css': 'wym_tools_ordered_list'},
			{'name': 'InsertUnorderedList', 'title': 'Unordered_List', 'css': 'wym_tools_unordered_list'},
			{'name': 'Indent', 'title': 'Indent', 'css': 'wym_tools_indent'},
			{'name': 'Outdent', 'title': 'Outdent', 'css': 'wym_tools_outdent'},
			{'name': 'Undo', 'title': 'Undo', 'css': 'wym_tools_undo'},
			{'name': 'Redo', 'title': 'Redo', 'css': 'wym_tools_redo'},
			{'name': 'CreateLink', 'title': 'Link', 'css': 'wym_tools_link'},
			{'name': 'Unlink', 'title': 'Unlink', 'css': 'wym_tools_unlink'},
			{'name': 'InsertImage', 'title': 'Image', 'css': 'wym_tools_image'},
			{'name': 'InsertTable', 'title': 'Table', 'css': 'wym_tools_table'},
			{'name': 'Paste', 'title': 'Paste_From_Word', 'css': 'wym_tools_paste'},
			{'name': 'ToggleHtml', 'title': 'HTML', 'css': 'wym_tools_html'},
			{'name': 'Preview', 'title': 'Preview', 'css': 'wym_tools_preview'}
		],
		containersItems: [
			{'name': 'P', 'title': 'Paragraph', 'css': 'wym_containers_p'},
			//{'name': 'H1', 'title': 'Heading_1', 'css': 'wym_containers_h1'},
			{'name': 'H2', 'title': 'Heading_2', 'css': 'wym_containers_h2'},
			{'name': 'H3', 'title': 'Heading_3', 'css': 'wym_containers_h3'},
			//{'name': 'H4', 'title': 'Heading_4', 'css': 'wym_containers_h4'},
			//{'name': 'H5', 'title': 'Heading_5', 'css': 'wym_containers_h5'},
			//{'name': 'H6', 'title': 'Heading_6', 'css': 'wym_containers_h6'},
			//{'name': 'PRE', 'title': 'Preformatted', 'css': 'wym_containers_pre'},
			//{'name': 'BLOCKQUOTE', 'title': 'Blockquote', 'css': 'wym_containers_blockquote'},
			//{'name': 'TH', 'title': 'Table_Header', 'css': 'wym_containers_th'}
		],
		classesItems: [
		]
	});

});
