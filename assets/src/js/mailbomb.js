import "../scss/mailbomb.scss";

"use-strict";

jQuery(document).ready(function($) {

    window.$    = $;
    window.$d   = $(document);

    if( $('#mailbomb_setting').is(':visible') ){

        let modModal = require('./modules/modModal.js');
        modModal.Init();

        let modNav = require('./modules/modNav.js');
        modNav.settingNav();

        let modTest = require('./modules/modTest.js');
        modTest.TestField();

        let modImport = require('./modules/modImport.js');
        modImport.Init();
    }
});