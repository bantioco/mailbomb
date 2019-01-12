let modDashboard = {

    NewsletterSubmit: ()=> {

        $d.off('submit', '#mailbomb_send_newsletter_form').on('submit', '#mailbomb_send_newsletter_form', function( e ){

            e.preventDefault();

            $('.modal-bg').fadeIn( 300, function(){

                $('.modal-confirm').fadeIn( 300 );
            });

            if( $('.mailbomb-nav-content[data-content="mailbomb-dashboard"]').is(':visible') ){

                $d.off('click', '.btn-valid').on('click', '.btn-valid', function(){

                    $('.modal-bg').click();

                    modDashboard.NewsletterPostGetUsers( function( users ){

                        if( users ){
        
                            if( users.success ){
        
                                $('#mailbomb_send_newsletter_form').hide();
        
                                users = users.data.users;
        
                                modDashboard.NewsletterLoopUsers( users );
                            }
                        }
                    });
                });
            }
        });
    },

    NewsletterLoopUsers: ( users )=> {

        let numberUsers = users.length;

        $('.mailbomb_send_newsletter_ajax').append(
            '<div>La newsletter va être envoyée à '+numberUsers+' abonnés.</div>'+
            '<div class="newsletter_prepare_loader">Préparation en cours...</div>'
        );

        $.each( users, function( index, user ){

            let userId      = user.id;
            let userEmail   = user.email;

            if( userEmail ){

                modDashboard.NewsletterPost( userId, userEmail, function( result ){

                    numberUsers --;

                    if( result ){

                        if( result.data.send === "success" ){

                            $('.mailbomb_send_newsletter_ajax').html(
                                '<div>Envoi en cours, il reste '+numberUsers+' abonnés.</div>'+
                                '<div class="mailbomb_import_user_ajax_email ajax_import_success">'+numberUsers+' - SUCCESS - '+result.data.email+'</div>'
                            );
                        }

                        else if( result.data.send === "error" ){

                            $('.mailbomb_send_newsletter_ajax').html(
                                '<div>Envoi en cours, il reste '+numberUsers+' abonnés.</div>'+
                                '<div class="mailbomb_import_user_ajax_email ajax_import_error">'+numberUsers+' - ERROR - '+result.data.email+'</div>'
                            );
                        }

                        if( numberUsers <= 0 ){

                            $('.mailbomb_send_newsletter_ajax').html('<div>Terminé</div>');

                            $('#mailbomb_send_newsletter_form').show();
                
                            setTimeout( function(){ window.location.reload(); }, 1500);
                        }

                        console.log( result );
                    }
                });
            }
        });
    },

    NewsletterPost: ( userId, userEmail , callback )=> {

        $.post(
            $ajaxUrl,
            {
                action: 'mailbombNewsletterSend',
                user_id: userId,
                user_email: userEmail
            },
            function( result ){

                return callback( result );
            }
        );

    },

    NewsletterPostGetUsers: ( callback )=> {

        $.post(
            $ajaxUrl,
            {
                action: 'mailbombNewsletterGetUsers',
                users_get: '1'
            },
            function( result ){

                return callback( result );
            }
        );
    }
}

module.exports = modDashboard;