let modTemplate = {

    Init: ()=> {

        modTemplate.DisabledCheckbox();

        modTemplate.FormTemplateFile();

        modTemplate.FormTemplateLoad();

        modTemplate.FormAddTemplate();
    },

    DisabledCheckbox: ()=> {

        if( $('.mailbomb_templates_class_table').is(':visible') ){

            let MailbombTemplate = [
                'mailbomb-test',
                'mailbomb-newsletter',
                'mailbomb-register',
                'mailbomb-unregistered'
            ];

            $('#the-list').find('tr').each( function( index, tr ){

                let templateName = $(tr).find('.post_name').text();

                if( templateName.length > 1 ){

                    $(tr).attr( 'data-name', templateName );

                    if( MailbombTemplate.indexOf( templateName ) != -1 ) {

                        $(tr).find('input[name="post[]"]').prop('disabled', true).css('cursor', 'not-allowed'); 

                        $(tr).find('.row-actions').children('.trash').remove();
                    }
                }
            });

            
        }
    },

    ReplaceImg: ()=> {

        let MailbombTemplate = [
            'mailbomb-test',
            'mailbomb-newsletter',
            'mailbomb-register',
            'mailbomb-unregistered'
        ];

        if( MailbombTemplate.indexOf( $('#post_name').val() ) === -1 ) {

            $('#mailbomb_template_loader').show();

            let loadContent = $('#mailbomb_template_view_box').find('.inside').html();

            $('input#mailbomb_content_template_field').val( loadContent.trim() );
        }

        if( !$('.mailbomb_img_replaced').is(':visible') ){

            let PostId = $('input#post_ID').val();

            if( PostId.length >= 1 ){

                modTemplate.ReplaceImgPost( PostId, function( data ){

                    if( data.success && data.data ){

                        modTemplate.ReplaceImgHtml( data.data.img, function( result ){

                            if( result ){

                                $('#mailbomb_template_loader').remove();

                                let content = $('#mailbomb_template_view_box').find('.inside').html();

                                $('input#mailbomb_content_template_field').val( content.trim() );

                                modTemplate.UpdateMailbombPost( PostId );
                            }
                        });
                    }
                });
            }
        }
        else{
            $('#mailbomb_template_loader').remove();
        }

        return;
    },

    UpdateMailbombPost: ( PostId )=> {

        if( parseInt( $('input#post_ID').val() ) === parseInt( PostId ) ) $('#publish').click();
    },

    ReplaceImgHtml: ( ArrayImg, callback )=> {

        let NumberImg   = ArrayImg.length;
        let CountImg    = 0;

        if( NumberImg >= 1 ){

            $.each( ArrayImg, function( index, img ){

                let htmlImg = $('#mailbomb_template_view_box').find('img');

                if( htmlImg ){

                    $.each( htmlImg, function( key, item ){

                        if( $( item ).attr("src").toLowerCase().indexOf( img.media_filename.toLowerCase() ) != -1 ) {
                            $( item ).attr( "src", img.media_dir );

                            $( item ).addClass('mailbomb_img_replaced');

                            CountImg++;
                        }
                    });
                }
            });

            return callback( CountImg );
        }

        return false;
    },

    ReplaceImgPost: ( postid, callback )=> {

        $.post(
            $ajaxUrl,
            {
                action: 'mailbombTemplateReplaceImg',
                mailbomb_post_id: postid,
            },
            function( result ){

                return callback( result );
            }
        );
    },

    FormTemplateFile: ()=> {

        $d.off('change', 'input[name="mailbomb_add_img_template"]').on('change', 'input[name="mailbomb_add_img_template"]', function( e ){

            let filepath = this.value;
            let filename = filepath.replace(/C:\\fakepath\\/, '');

            let extension = filename.split('.').pop();

            if( extension != 'zip' ){

                $('.mailbomb_template_add_zipname').html( "Le fichier n'est pas de type zip" );

                this.value = '';

                return;
            }

            $('.mailbomb_template_add_zipname').html( "PRÊT POUR L'IMPORT : "+filename );
        });


        $d.off('change', 'input[name="mailbomb_import_file_template"]').on('change', 'input[name="mailbomb_import_file_template"]', function( e ){

            let filepath = this.value;
            let filename = filepath.replace(/C:\\fakepath\\/, '');

            let extension = filename.split('.').pop();

            console.log( extension )

            if( (extension != 'html') && (extension != 'php') ){

                $('.mailbomb_template_import_filename').html( "Le fichier n'est pas de type html ou php" );

                this.value = '';

                return;
            }

            $('.mailbomb_template_import_filename').html( "PRÊT POUR L'IMPORT : "+filename );
        });


        $d.off('change', 'input[name="mailbomb_import_img_template"]').on('change', 'input[name="mailbomb_import_img_template"]', function( e ){

            let filepath = this.value;
            let filename = filepath.replace(/C:\\fakepath\\/, '');

            let extension = filename.split('.').pop();

            if( extension != 'zip' ){

                $('.mailbomb_template_import_zipname').html( "Le fichier n'est pas de type zip" );

                this.value = '';

                return;
            }

            $('.mailbomb_template_import_zipname').html( "PRÊT POUR L'IMPORT : "+filename );
        });

    },

    FormTemplateLoad: ()=> {

        let AddState = $('input[name="mailbomb_add_html_template_btn"]').prop('checked');

        modTemplate.FormTemplateAddView( AddState );


        let ImportState = $('input[name="mailbomb_import_html_template_btn"]').prop('checked');

        modTemplate.FormTemplateImportView( ImportState );
    },

    FormAddTemplate: ()=> {

        $d.off('change', 'input[name="mailbomb_add_html_template_btn"]').on('change', 'input[name="mailbomb_add_html_template_btn"]', function(){

            let state = $(this).prop('checked');

            modTemplate.FormTemplateImportView( false );

            $('input[name="mailbomb_import_html_template_btn"]').prop('checked', false);

            modTemplate.FormTemplateAddView( state );
        });

        $d.off('change', 'input[name="mailbomb_import_html_template_btn"]').on('change', 'input[name="mailbomb_import_html_template_btn"]', function(){

            let state = $(this).prop('checked');

            modTemplate.FormTemplateAddView( false );

            $('input[name="mailbomb_add_html_template_btn"]').prop('checked', false);

            modTemplate.FormTemplateImportView( state );
        });
    },

    FormTemplateAddView: ( state )=> {

        if( state ){

            $('.mailbomb_notice_table_form_add').slideDown( 300 );
        }
        else{

            $('.mailbomb_notice_table_form_add').slideUp( 300 );
        }
    },

    FormTemplateImportView: ( state )=> {

        if( state ){

            $('.mailbomb_notice_table_form_import').slideDown( 300 );
        }
        else{

            $('.mailbomb_notice_table_form_import').slideUp( 300 );
        }
    }
}
module.exports = modTemplate;