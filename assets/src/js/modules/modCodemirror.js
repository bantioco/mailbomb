let modCodemirror = {

    TextAreaAddTemplate: ()=> {

        $('.mailbomb_notice_table_form_add').show();

        var myCodeMirror = CodeMirror.fromTextArea( document.getElementById( 'mailbomb_add_html_template' ), {
            lineNumbers: true
        });

        let checkboxState = $('input[name="mailbomb_add_html_template_btn"]').prop('checked');

        if( !checkboxState ) $('.mailbomb_notice_table_form_add').hide();
    }
}
module.exports = modCodemirror;