/**
 * @license Copyright (c) 2003-2014, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */


CKEDITOR.editorConfig = function( config ) {
    config.toolbar =
        [
            {name:'document', items:['Source','-','Preview','Print']},
            {name:'clipboard', items:['Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo']},
            {name:'basicstyles', items:['Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat']},
            '/',
            {name:'paragraph', items:['NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl']},
            {name:'links', items:['Link','Unlink']},
            {name:'insert', items:['Image','Table']},
            {name:'tools', items:['Maximize']}
        ];
		config.allowedContent = true;

	
		config.filebrowserBrowseUrl = '/assets/admin/js/ckeditor/kcfinder/browse.php?opener=ckeditor&type=files';
		config.filebrowserImageBrowseUrl = '/assets/admin/js/ckeditor/kcfinder/browse.php?opener=ckeditor&type=images';
		config.filebrowserFlashBrowseUrl = '/assets/admin/js/ckeditor/kcfinder/browse.php?opener=ckeditor&type=flash';
		config.filebrowserUploadUrl = '/assets/admin/js/ckeditor/kcfinder/upload.php?opener=ckeditor&type=files';
		config.filebrowserImageUploadUrl = '/assets/admin/js/ckeditor/kcfinder/upload.php?opener=ckeditor&type=images';
		config.filebrowserFlashUploadUrl = '/assets/admin/js/ckeditor/kcfinder/upload.php?opener=ckeditor&type=flash';
};


  