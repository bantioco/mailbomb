let modAdminmenu = {

    Init: ()=> {
        modAdminmenu.TemplateMenu();
    },

    TemplateMenu: ()=> {

        if( $('.mailbomb_templates_class_edit').is(':visible') && $('.post-type-mailbomb_templates').is(':visible') ){

            $('#toplevel_page_mailbomb-setting').addClass('wp-has-current-submenu wp-menu-open').children('a').addClass('wp-has-current-submenu wp-menu-open');

            $('a[href="edit.php?post_type=mailbomb_templates"]').addClass('current').parent('current');
        }
    }
}
module.exports = modAdminmenu;