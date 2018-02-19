CKEDITOR.on( "instanceReady", function() {
    const codeBlock = document.getElementById( "cke_43" );
    if ( location.href.includes( "/ask" ) ) {
        codeBlock.addEventListener( "click", function() {
            const category_1 = $('#category_1 option:selected');
            if(category_1.text() === 'Programowanie') {
                const category_2 = $('#category_2 option:selected');			
                switch(category_2.text()){
                    case 'HTML i CSS':
                        CKEDITOR.config.syntaxhighlight_lang = 'xml';
                    break;
                    case 'C i C++':
                        CKEDITOR.config.syntaxhighlight_lang = 'cpp';
                    break;
                    case 'JavaScript, jQuery, AJAX':
                        CKEDITOR.config.syntaxhighlight_lang = 'jscript';
                    break;
                    case 'PHP, Symfony, Zend':
                        CKEDITOR.config.syntaxhighlight_lang = 'php';
                    break;
                    case 'SQL, bazy danych':
                        CKEDITOR.config.syntaxhighlight_lang = 'sql';
                    break;
                    case 'C# i .NET':
                        CKEDITOR.config.syntaxhighlight_lang = 'csharp';
                    break;
                    case 'Java':
                        CKEDITOR.config.syntaxhighlight_lang = 'java';
                    break;
                    case 'Python, Django':
                        CKEDITOR.config.syntaxhighlight_lang = 'python';
                    break;
                    default:
                        CKEDITOR.config.syntaxhighlight_lang = 'plain';
                    break;
                }    			
            }    
        });
    } else if ( location.href.includes( "edit" ) ) {
            codeBlock.addEventListener( "click", function() {
            const questionEditCategory_1 = $('#q_category_1 option:selected');
            if(questionEditCategory_1.text() === 'Programowanie'){
                const questionEditCategory_2 = $('#q_category_2 option:selected');
				switch(category_2.text()){
                    case 'HTML i CSS':
                        CKEDITOR.config.syntaxhighlight_lang = 'xml';
                    break;
                    case 'C i C++':
                        CKEDITOR.config.syntaxhighlight_lang = 'cpp';
                    break;
                    case 'JavaScript, jQuery, AJAX':
                        CKEDITOR.config.syntaxhighlight_lang = 'jscript';
                    break;
                    case 'PHP, Symfony, Zend':
                        CKEDITOR.config.syntaxhighlight_lang = 'php';
                    break;
                    case 'SQL, bazy danych':
                        CKEDITOR.config.syntaxhighlight_lang = 'sql';
                    break;
                    case 'C# i .NET':
                        CKEDITOR.config.syntaxhighlight_lang = 'csharp';
                    break;
                    case 'Java':
                        CKEDITOR.config.syntaxhighlight_lang = 'java';
                    break;
                    case 'Python, Django':
                        CKEDITOR.config.syntaxhighlight_lang = 'python';
                    break;
                    default:
                        CKEDITOR.config.syntaxhighlight_lang = 'plain';
                    break;
                }   		
            }
        });
	} else {
        codeBlock.addEventListener( 'click', function() {
            const category = $('.qa-q-view-where-data').text();
            switch(category){
                case 'HTML i CSS':
                    CKEDITOR.config.syntaxhighlight_lang = 'xml';
                break;
                case 'C i C++':
                    CKEDITOR.config.syntaxhighlight_lang = 'cpp';
                break;
                case 'JavaScript, jQuery, AJAX':
                    CKEDITOR.config.syntaxhighlight_lang = 'jscript';
                break;
                case 'PHP, Symfony, Zend':
                    CKEDITOR.config.syntaxhighlight_lang = 'php';
                break;
                case 'SQL, bazy danych':
                    CKEDITOR.config.syntaxhighlight_lang = 'sql';
                break;
                case 'C# i .NET':
                    CKEDITOR.config.syntaxhighlight_lang = 'csharp';
                break;
                case 'Java':
                    CKEDITOR.config.syntaxhighlight_lang = 'java';
                break;
                case 'Python, Django':
                    CKEDITOR.config.syntaxhighlight_lang = 'python';
                break;
                default:
                    CKEDITOR.config.syntaxhighlight_lang = 'plain';
                break;
            }   
        });
    }		   
});
