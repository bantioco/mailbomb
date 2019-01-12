let modImport = {

    Init: ()=> {

        $d.off('change', 'input[name="mailbomb_import_users"]').on('change', 'input[name="mailbomb_import_users"]', function( e ){

            let filepath = this.value;
            let filename = filepath.replace(/C:\\fakepath\\/, '');

            let extension = filename.split('.').pop();

            if( extension != 'csv' ){

                $('.mailbomb_import_filename').html( "Le fichier n'est pas de type csv" );

                this.value = '';

                return;
            }

            $('.mailbomb_import_filename').html( filename );
        });

        $d.off('submit', '#mailbomb_import_form').on('submit', '#mailbomb_import_form', function( e ){

            e.preventDefault();

            modImport.UserImportParseFile( $(this)[0], function( result ){

                console.log( result )

                if( result ){

                    if( result.data.emails.length >= 1 ){

                        let totalUsers = result.data.emails.length;

                        $('.mailbomb_import_user_ajax').append('<div>Il y a ' + totalUsers + ' utilisateurs à importer.</div>');

                        $.each( result.data.emails, function( index, email ){

                            modImport.UserImportAddPost( email, function( user ){

                                if( user ){

                                    totalUsers--;

                                    if( user.data.send === 'exist' ){
                                        $('.mailbomb_import_user_ajax').html(
                                            '<div>Il reste ' + totalUsers + ' utilisateurs à importer.</div>'+
                                            '<div class="mailbomb_import_user_ajax_email ajax_import_warning"> EXIST : '+user.data.email+'</div>'
                                        );
                                    }

                                    if( user.data.send === 'success' ){
                                        $('.mailbomb_import_user_ajax').html(
                                            '<div>Il reste ' + totalUsers + ' utilisateurs à importer.</div>'+
                                            '<div class="mailbomb_import_user_ajax_email ajax_import_success"> SUCCESS : '+user.data.email+'</div>'
                                        );
                                    }

                                    if( user.data.send === 'error' ){
                                        $('.mailbomb_import_user_ajax').html(
                                            '<div>Il reste ' + totalUsers + ' utilisateurs à importer.</div>'+
                                            '<div class="mailbomb_import_user_ajax_email ajax_import_error"> ERROR : '+user.data.email+'</div>'
                                        );
                                    }

                                    if( totalUsers <= 0 ){

                                        $('.mailbomb_import_user_ajax').html(
                                            '<div>Terminé</div>'
                                        );
                                        setTimeout( function(){ 
                                            $('.nav-tab[data-nav="mailbomb-users-list"]').click();
                                        }, 2000);
                                    }
                                }
                            });
                        });
                    }
                }

            });
        })
    },

    UserImportAddPost: ( email, callback )=> {

        $.post(
            $ajaxUrl,
            {
                action: 'mailbombUsersImportAdd',
                mailbomb_user_email: email
            },
            function( result ){

                return callback( result );
            }
        );
    },

    UserImportParseFile: ( $this, callback )=> {

        let formData = new FormData( $this );
        formData.append('mailbomb_users_import', 'user_import');
        formData.append('action', 'mailbobmUserListParseFile');

        $.ajax({
            url : $ajaxUrl,
            type: "POST",
            data : formData,
            processData: false,
            contentType: false,
            success:function( result ){

                return callback( result );
            },
            error: function(jqXHR, textStatus, errorThrown){
                return callback( errorThrown );   
            }
        });
    }
}
module.exports = modImport;