describe('Ask question', ()=>{
    it('should start a new topic with correct data', ()=>{
        cy.login('admin@example.com', 'admin');
        
        cy.get('a').contains('Zapytaj').click();

        const postTitle = 'Dlaczego Bill Gates chce nas zaczipować?';
        const primaryCategory = 'Programowanie';
        const secondaryCategory = 'HTML i CSS';
        const questionContent = 'Dlaczego Bill Gates chce nam wszczepić mikroczipy? Po co mu wiedzieć jakie skarpetki dzisiaj ubrałem?';
        const tags = ['szczepionki','mikroczipy','mikrokontrolery'];

        // create post
        cy.get('input#title').type(postTitle);
        cy.get('select#category_1').select(primaryCategory);
        cy.get('select#category_2').select(secondaryCategory);
        cy.get('.cke_wysiwyg_frame.cke_reset').click().type(questionContent);
        cy.get('input#tags').type(tags.join(' '));
        cy.get('input').contains('Zadaj pytanie').click();

        //assertions
        cy.url().should('include', 'dlaczego-bill');
        cy.get('span.entry-title').should('contain', postTitle)
        cy.get('div.entry-content').should('contain', questionContent);
        cy.get('li.qa-q-view-tag-item a').should('contain', tags[0]);
        cy.get('li.qa-q-view-tag-item a').should('contain', tags[1]);
        cy.get('li.qa-q-view-tag-item a').should('contain', tags[2]);
        cy.get('.qa-q-view-who-data').its('a').should('contain', 'admin');

    })

})