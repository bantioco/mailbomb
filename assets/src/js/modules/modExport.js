let modExport = {

    Flatpickr: ( selector )=> {

        let flatpickr = require("flatpickr");

        $( selector ).flatpickr({
            enableTime: true,
            dateFormat: "Y-m-d H:i:s"
        });
    },

    Export: ()=> {

        $d.off('submit', '#mailbomb_export_form').on('submit', '#mailbomb_export_form', function( e ){

            e.preventDefault();

            let datas = {
                'file_format': $('input[name="export_type"]:checked').val(),
                'date_start' : $('input[name="export_date_start"]').val(),
                'date_end': $('input[name="export_date_end"]').val()
            }

            modExport.UserExportPost( datas, function( result ){

                if( result.success && result.data && result.data.file ) window.location.href = result.data.file;
            });
        });
    },

    UserExportPost: ( datas, callback )=> {

        $.post(
            $ajaxUrl+'?f=userExport',
            {
                action: 'mailbombUsersExport',
                file_format: datas.file_format,
                date_start: datas.date_start,
                date_end: datas.date_end,
                mailbomb_export_user: 1
            },
            function( result ){

                return callback( result );
            }
        );
    },
}
module.exports = modExport;