document.addEventListener("DOMContentLoaded", () => {
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
            const codeBlock = document.getElementById( 'cke_42' );
            let clickHandler;
            if ( location.href.includes( '/ask' ) ) {
                clickHandler = () => {
                    const firstCategory = document.querySelector( '#category_1' );
                    const selectedFirstOption = firstCategory.children[ firstCategory.selectedIndex ];                       	
                    if(selectedFirstOption.textContent === 'Programowanie') {
                        const secondCategory = document.querySelector( '#category_2' );
                        const selectedSecondOption = secondCategory.children[ secondCategory.selectedIndex ];			
                        const findCategory = categories.find( function(object) {
                            return object.category == selectedSecondOption.textContent;
                        });
	                 
                            CKEDITOR.config.syntaxhighlight_lang = findCategory ? findCategory.language : 'plain';
		    } else {		
                            CKEDITOR.config.syntaxhighlight_lang = 'plain';		
                    }
                     		
                };
	         	
           } else if ( location.href.includes( 'edit' ) ) {
                clickHandler = () => {
	               			
                    let findCategory;	
                    const firstCategory = document.querySelector( '#q_category_1' );
                    if(firstCategory) {
                        const selectedFirstOption = firstCategory.children[ firstCategory.selectedIndex ];                       	
						if(selectedFirstOption.textContent === 'Programowanie') {
                            const secondCategory = document.querySelector( '#q_category_2' );
                            const selectedSecondOption = secondCategory.children[ secondCategory.selectedIndex ];
                            findCategory = categories.find( ( object ) => {
                                return object.category == selectedSecondOption.textContent;
                            });
                        }
                    } else {
                       const category = document.querySelector( '.qa-q-view-where-data' );
                       findCategory = categories.find( ( object ) => {
                            return object.category == category.textContent;
                        });
                    }
                    CKEDITOR.config.syntaxhighlight_lang = findCategory ? findCategory.language : 'plain';
                };
	        		
	    } else {
                clickHandler = () => {
				
                    const category = document.querySelector( '.qa-q-view-where-data' );
                    const findCategory = categories.find( function(object) {
                        return object.category == category.textContent;
                    });
		     	
                   CKEDITOR.config.syntaxhighlight_lang = findCategory ? findCategory.language : 'plain';
				
                };
            }
        codeBlock.addEventListener( 'click', clickHandler );	
        });
    }
});
