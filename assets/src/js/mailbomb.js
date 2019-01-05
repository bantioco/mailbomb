import "../scss/mailbomb.scss";

"use-strict";

jQuery(document).ready(function($) {

    window.$    = $;
    window.$d   = $(document);


    let modAdminmenu = require('./modules/modAdminmenu.js');
        modAdminmenu.Init();

    if( $('#mailbomb_setting').is(':visible') ){

        let modDashboard = require('./modules/modDashboard.js');
        modDashboard.NewsletterSubmit();

        let modModal = require('./modules/modModal.js');
        modModal.Init();

        let modNav = require('./modules/modNav.js');
        modNav.settingNav();

        let modTest = require('./modules/modTest.js');
        modTest.TestField();

        let modImport = require('./modules/modImport.js');
        modImport.Init();

        let modUserlist = require('./modules/modUserlist.js');
        modUserlist.Init();

        let modExport = require('./modules/modExport.js');
        modExport.Flatpickr( '#export_date_start' );
        modExport.Flatpickr( '#export_date_end' );
    }

    if( $('#mailbomb_template_view_box').is(':visible') ){

        let modTemplate = require('./modules/modTemplate.js');
        modTemplate.ReplaceImg();
    }

    if( $('.mailbomb_templates_class_table').is(':visible') ){

        let modTemplate = require('./modules/modTemplate.js');
        modTemplate.Init();

        let modCodemirror = require('./modules/modCodemirror.js');
        modCodemirror.TextAreaAddTemplate();
    }

    /*
    if( $('#mailbomb_setting').is(':visible') ){
        let modCodemirror = require('./modules/modCodemirror.js');
        modCodemirror.TextAreaDevelopperCheckbox();
    }
    */
});