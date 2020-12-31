console.warn('[0] CKEDITOR plugin script!!!');

CKEDITOR.plugins.add( 'simplecodetag', {
  icons: 'simpleCodeTag',
  init: function( editor ) {
    console.log('[1] cke simpleCodeTag init');

    editor.addCommand( 'insertSimpleCodeTag', {
      exec: function( editor ) {
        console.log('cke simpleCodeTag exec');

        editor.insertHtml( 'The simpleCodeTag is inserted!' );
      }
    });
    editor.ui.addButton( 'SimpleCodeTag', {
      label: 'Insert Code Tag',
      command: 'insertSimpleCodeTag',
      // toolbar: 'links'
    });
  }
});

//CKEDITOR.config.extraPlugins += ',simplecodetag'

// do this in page inline script inside after `qa_ckeditor4_config` object creation
//qa_ckeditor4_config.toolbar[7].push('SimpleCodeTag');
//qa_ckeditor4_config.extraPlugins += ',simplecodetag';
