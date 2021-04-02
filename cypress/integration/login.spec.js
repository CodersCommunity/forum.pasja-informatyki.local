describe('Login as admin', ()=>{
    it('should login', ()=>{
        cy.login('admin@example.com', 'admin');
    })

})