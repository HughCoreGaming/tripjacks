<?php
class Zend_View_Helper_SetupEditor {
 
    function setupEditor( $textareaId ) {
        return "<script type=\"text/javascript\">
    CKEDITOR.replace( '". $textareaId ."' ,
		{
		skin : 'v2',
		enterMode : CKEDITOR.ENTER_BR,
		shiftEnterMode: CKEDITOR.ENTER_P,
		toolbar : [
			  ['Source','-','Bold','Italic','Underline','-','BulletedList','-','Link','Unlink'],
			  ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
			   '/',
			  [ 'Image','Table','HorizontalRule','Smiley','SpecialChar','PageBreak' ],
			  ['Font','FontSize'],
			  ['TextColor','-','About']
			  ],		
		contentsCss : 'body {overflow:scroll;visibility:none;border:none;margin:0px;color:#000; background-color#FFF; font-family: Arial; font-size:12px;padding:5px;} p, ol, ul {margin-top: 0px; margin-bottom: 0px;}',
		sharedSpaces :
		{
		top : 'theEditor'	
		},
		
		coreStyles_bold	: { element : 'b' },
		coreStyles_italic	: { element : 'i' },
		coreStyles_underline	: { element : 'u'},
		coreStyles_strike	: { element : 'strike' },
                
                removePlugins: 'elementspath' ,
                removePlugins: 'resize' ,
                
		fontSize_sizes : '8px/8;9px/9;10px/10;11px/11;12px/12;14px/14;16px/16;18px/18;20px/20;22px/22;24px/24;26px/26;28px/28',
						fontSize_style :
							{
								element		: 'font',
								attributes	: { 'size' : '#(size)' },
								styles		: { 'font-size' : '#(size)px' }
							} 
		
	
		} );
        </script>";
    }
}
