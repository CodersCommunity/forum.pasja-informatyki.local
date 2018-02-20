document.addEventListener("DOMContentLoaded", function(event) {
    if (typeof CKEDITOR != 'undefined') {
        CKEDITOR.on( 'instanceReady', function() {
            const categories = [
                {category : 'HTML i CSS', language: 'xml'},
                {category : 'C i C++', language: 'cpp'},
                {category : 'JavaScript, jQuery, AJAX', language: 'jscript'},
                {category : 'PHP, Symfony, Zend', language: 'php'},
                {category : 'SQL, bazy danych', language: 'sql'},
                {category : 'C# i .NET', language: 'csharp'},
                {category : 'Java', language: 'java'},
                {category : 'Python, Django', language: 'python'},
            ]
            const codeBlock = document.getElementById( 'cke_43' );
		
            if ( location.href.includes( '/ask' ) ) {
                codeBlock.addEventListener( 'click', () => {
                    const firstCategory = $( '#category_1 option:selected' );
				
                    if(firstCategory.text() === 'Programowanie') {
                        const secondCategory = $( '#category_2 option:selected' );			
					    const findCategory = categories.find( function(object) {
                            return object.category == secondCategory.text();
                        });
					
				        if(findCategory != undefined){
					        CKEDITOR.config.syntaxhighlight_lang = findCategory.language;
				        } else {
						    CKEDITOR.config.syntaxhighlight_lang = 'plain';
					    }
			
                    }
				
			    });
			
		    } else if ( location.href.includes( 'edit' ) ) {
                codeBlock.addEventListener( 'click', () => {
					
                    const questionEditFirstCategory = $( '#q_category_1 option:selected' );
                    if(questionEditFirstCategory.text() === 'Programowanie'){
                        const questionEditSecondCategory = $( '#q_category_2 option:selected' );
                	    const findCategory = categories.find( function(object) {
                            return object.category == questionEditSecondCategory.text();
                        });
			
        			    if(findCategory != undefined){
				            CKEDITOR.config.syntaxhighlight_lang = findCategory.language;
				        } else {
				            CKEDITOR.config.syntaxhighlight_lang = 'plain';
				        }
                    }
                });
				
	        } else {
                codeBlock.addEventListener( 'click', () => {
				
                    const category = $( '.qa-q-view-where-data' );
			        const findCategory = categories.find( function(object) {
                        return object.category == category.text();
                    });
				
				    if(findCategory != undefined){
				        CKEDITOR.config.syntaxhighlight_lang = findCategory.language;
				    } else {
					    CKEDITOR.config.syntaxhighlight_lang = 'plain';
				    }
				
                });
            }		   
        });
    }
}
