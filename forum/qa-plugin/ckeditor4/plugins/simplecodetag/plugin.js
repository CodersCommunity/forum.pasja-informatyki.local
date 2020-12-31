console.warn('[0] CKEDITOR plugin script!!!');

CKEDITOR.plugins.add('simplecodetag', {
  // TODO: add icon file
  icons: 'simpleCodeTag',
  init(editor) {
    console.warn('[1] cke simpleCodeTag init /editor:', editor);

    editor.addCommand('insertSimpleCodeTag', {
      exec(editor) {
        const selectedText = editor.getSelection().getSelectedText();
        console.warn('[2] cke simpleCodeTag exec /selectedText:' ,selectedText);

        const htmlElement = new CKEDITOR.dom.element( 'code');
        // set content as text to avoid XSS
        htmlElement.setText(selectedText);

        editor.insertElement(htmlElement);
      },
    });

    editor.ui.addButton('SimpleCodeTag', {
      label: 'Insert Code Tag',
      command: 'insertSimpleCodeTag',
    });
  }
});

/* CSS

//color: #242729;
color: rgb(255 255 255);
//background-color: #e4e6e8;
background-color: #2c3e50;
padding: 2px 4px;
border-radius: 2px;

*/
