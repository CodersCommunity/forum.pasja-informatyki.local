// ***********************************************
// This example commands.js shows you how to
// create various custom commands and overwrite
// existing commands.
//
// For more comprehensive examples of custom
// commands please read more here:
// https://on.cypress.io/custom-commands

Cypress.Commands.add('login', (email, password) => {
        cy.visit('/');
        
        // expand login menu
        cy.get('#qam-account-toggle').click();
        
        // fill in email and password
        cy.get('input#qa-userid').type(`${email}`);
        cy.get('input#qa-password').type(`${password}`);
        
        // click on login button
        cy.get('#qa-login').click();
 })
