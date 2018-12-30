let modCodemirror = {

    TextAreaAddTemplate: ()=> {

        $('.mailbomb_notice_table_form_add').show();

        var myCodeMirror = CodeMirror.fromTextArea( document.getElementById( 'mailbomb_add_html_template' ), {
            lineNumbers: true
        });

        let checkboxState = $('input[name="mailbomb_add_html_template_btn"]').prop('checked');

        if( !checkboxState ) $('.mailbomb_notice_table_form_add').hide();
    },

    TextAreaDevelopperCheckbox: ()=> {

        var myCodeMirror = CodeMirror.fromTextArea( document.getElementById( 'mailbomb_developper_view_checkbox' ), {
            lineNumbers: true,
            readOnly: true,
            viewportMargin: Infinity
        });
    }
}
module.exports = modCodemirror;