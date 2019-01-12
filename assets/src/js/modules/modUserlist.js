let modUserlist = {

    Init: ()=> {
        modUserlist.userSelectAll();
        modUserlist.CheckboxAction();
    },

    userSelectAll: ()=> {

        let checkboxState = $('#users_list_selected_all').prop('checked');

        modUserlist.CheckboxAllAction( checkboxState );

        $d.off('change', '#users_list_selected_all').on('change', '#users_list_selected_all', function(){

            let state = $(this).prop('checked');

            modUserlist.CheckboxAllAction( state );
        });
    },

    CheckboxAction: ()=> {

        $d.off('change', 'input[name="user_list_selected_delete[]"]').on('change', 'input[name="user_list_selected_delete[]"]', function(){

            modUserlist.CheckCheckboxState();
        });
    },

    CheckCheckboxState: ()=> {

        let $checkbox = $('input[name="user_list_selected_delete[]"]');

        modUserlist.DeleteBtnAction( true );

        let checked = false;

        $.each( $checkbox, function( index, item ){

            if( $(item).prop('checked') ) {

                modUserlist.DeleteBtnAction( false );

                return;
            }
        });
    },

    DeleteBtnAction: ( state )=> {

        $('input[name="users_list_selected_delete"]').prop( 'disabled', state );

        if( !state ){
            $('#_users_list_selected_delete').addClass( 'mailbomb-btn-red mailbomb-btn-user-delete-active' ).removeClass( 'mailbomb-btn-grey' );
        }
        else{
            $('#_users_list_selected_delete').addClass( 'mailbomb-btn-grey' ).removeClass( 'mailbomb-btn-red mailbomb-btn-user-delete-active' );
        }

        $d.off('click', '.mailbomb-btn-user-delete-active').on('click', '.mailbomb-btn-user-delete-active', function(){

            $('.modal-bg').fadeIn( 300, function(){

                $('.modal-confirm').fadeIn( 300 );

                modUserlist.UserListDeleteSubmit();
            });
        });
    },

    UserListDeleteSubmit: ()=> {

        if( $('.mailbomb-nav-content[data-content="mailbomb-users-list"]').is(':visible') ){

            $d.off('click', '.btn-valid').on('click', '.btn-valid', function(){

                $('.modal-bg').click();

                $('#_users_list_selected_delete').hide();

                $('.mailbomb_user_list_loader').fadeIn( 500 ).find('.mailbomb_text_loader').addClass('mailbomb_animation_show_hide');

                let TotalUsers = $('input[name="user_list_selected_delete[]"]:checked').length;
                let countUser = 0;

                let DeleteLoader = 
                '<div class="mailbomb_user_delete_loader">'+
                    '<div class="mailbomb_user_delete_result"></div>'+
                    '<span>Suppression en cours...</span><span class="mailbomb_number_irems">'+TotalUsers+'</span>'+
                '</div>';

                $('.mailbomb_user_list_delete_js').html( DeleteLoader );

                $('input[name="user_list_selected_delete[]"]:checked').each( function( key, item ){

                    let UserId = $(item).val();

                    modUserlist.UserListDeletePost( UserId, function( result ){

                        countUser++;

                        if( result ){

                            if( !result.success ){
                                let html = '<div class="mailbomb_user_delete_error">'+( TotalUsers - countUser )+' - ERREUR : '+result.data.email+'</div>';
                                $('.mailbomb_user_list_delete_js').find('.mailbomb_user_delete_result').html( html );
                            }
                            else{
                                let html = '<div class="mailbomb_user_delete_success">'+( TotalUsers - countUser )+' - SUCCES : '+result.data.email+'</div>';
                                $('.mailbomb_user_list_delete_js').find('.mailbomb_user_delete_result').html( html );
                            }
                        }


                        $('.mailbomb_number_irems').html( ( TotalUsers - countUser ) );

                        if( countUser >= TotalUsers ){

                            $('#_users_list_selected_delete').show();
        
                            $('.mailbomb_user_list_delete_js').html('');
        
                            $('.mailbomb_user_list_loader').fadeOut( 100 ).find('.mailbomb_text_loader').removeClass('mailbomb_animation_show_hide');
        
                            setTimeout( function(){ window.location.reload(); }, 1000);
                        }
                    });
                    
                });
            });
        }
    },

    UserListDeletePost: ( UserId, callback )=> {

        $.post(
            $ajaxUrl,
            {
                action: 'mailbombUserDelete',
                mailbomb_user_id: UserId
            },
            function( result ){

                return callback( result );
            }
        );

    },

    CheckboxAllAction: ( state )=> {

        if( state ) $('input[name="user_list_selected_delete[]"]').prop('checked', true);
        
        else $('input[name="user_list_selected_delete[]"]').prop('checked', false);

        if( state ) modUserlist.DeleteBtnAction( false );

        else modUserlist.DeleteBtnAction( true );

        return;
    }
}
module.exports = modUserlist;