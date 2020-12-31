CKEDITOR.plugins.add('simplecodetag', {
  // TODO: add icon file
  icons: 'simpleCodeTag',
  init(editor) {
    editor.addCommand('insertSimpleCodeTag', {
      exec(editor) {
        const selectedText = editor.getSelection().getSelectedText();
        const htmlElement = new CKEDITOR.dom.element( 'code');

        // set content as text to avoid XSS
        htmlElement.setText(selectedText);

        // use inline styling, because CKEditor is loaded inside iframe,
        // so styles declared outside it (like qa-styles.css) cannot be applied
        htmlElement.setStyles({
          color: 'white',
          background: '#2c3e50',
          padding: '2px 4px',
          borderRadius: '2px',
        });

        editor.insertElement(htmlElement);
      },
    });

    const CTRL_K = CKEDITOR.CTRL + 75;
    editor.setKeystroke(CTRL_K, 'insertSimpleCodeTag');

    editor.ui.addButton('SimpleCodeTag', {
      label: 'Insert Code Tag',
      command: 'insertSimpleCodeTag',
    });
  }
});
