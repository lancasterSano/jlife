/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';

	// config.uiColor = '#fffccc';
    // config.resize_enabled = false;

    // config.toolbar = 'MyToolbar';

    // config.toolbar_MyToolbar = [
    //     ['NewPage','Preview','Ajaxsave'],
    //     ['Cut','Copy','Paste','PasteText','PasteFromWord','-','Scayt'],
    //     ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
    //     ['Table','HorizontalRule','Smiley','SpecialChar'],
    //     '/',
    //     ['Styles','Format'],
    //     ['Bold','Italic','Strike'],
    //     ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
    //     ['Link','Unlink','Anchor'],
    //     ['Maximize','-','About']
    // ];
    config.toolbar = 'Custom'; 
    config.toolbar_Custom = //индивидуальная настройка режима Basic
    [
        [
            'Source',
            '-',
            /*'Ajaxsave', */'NewPage', 'Preview', 'Print','-', 'Templates'
        ],

        [
            'Cut', 'Copy', 'Paste', 'PasteText', /*'PasteFromWord',*/
            '-',
            'Undo', 'Redo'
        ],
        
        [
            'Find', 'Replace',
            '-',
            'SelectAll'
        ],

        [
            'Link', 'Unlink', 'Anchor'
        ],
        
        [
            'HorizontalRule', 'PageBreak'
        ],
        
        [
            'Image', /*'oembed',*/ 'Youtube', 'Table', 'SpecialChar', 'Smiley', 'EqnEditor'
        ],

        [
            'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript',
            '-',
            'RemoveFormat'
        ],

        [
            'Styles', 'Format', 'Font', 'FontSize'
        ],
        
        [
            'TextColor', 'BGColor'
        ],
        
        '/',

        [
            'NumberedList', 'BulletedList',
            '-',
            'Outdent', 'Indent', 'Blockquote', 
            '-',
            'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock',
            '-',
            'BidiLtr', 'BidiRtl'
        ],
        
        [
            'Maximize', 'ShowBlocks'
        ]
    ];

    // config.fillEmptyBlocks = false;
    config.extraPlugins = 'ajaxsave,oembed,eqneditor,youtube'; // Примечание: перечислять через запятую, без пробелов, с учетом регистра
    // config.allowedContent = true;
};
