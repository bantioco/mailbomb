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

        $('button[name="users_list_selected_delete"]').prop( 'disabled', state );
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