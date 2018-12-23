let modNotice = {

    Init: ()=> {

        modNotice.NoticeHide();

        let param = modNotice.GetParam('mailbomb');

        if( param ){

            if( param === "exist"){
                $('.mailbomb_send_notice').show();
                $('.mailbomb_send_notice').find('.mailbomb_notice_exist').fadeIn(300);
            }

            else if( param === "success"){
                $('.mailbomb_send_notice').show();
                $('.mailbomb_send_notice').find('.mailbomb_notice_success').fadeIn(300);
            }

            else if( param === "invalid_email"){
                $('.mailbomb_send_notice').show();
                $('.mailbomb_send_notice').find('.mailbomb_notice_invalid_email').fadeIn(300);
            }

            else if( param === "error"){
                $('.mailbomb_send_notice').show();
                $('.mailbomb_send_notice').find('.mailbomb_notice_error').fadeIn(300);
            }
        }
    },

    NoticeHide: ()=> {

        $('.mailbomb_send_notice').hide();
        $('.mailbomb_send_notice').find('.mailbomb_notice_exist').hide();
        $('.mailbomb_send_notice').find('.mailbomb_notice_success').hide();
        $('.mailbomb_send_notice').find('.mailbomb_notice_invalid_email').hide();
        $('.mailbomb_send_notice').find('.mailbomb_notice_error').hide();
    },

    GetParam: ( param )=> {

        var vars = {};

        window.location.href.replace( location.hash, '' ).replace( 
            /[?&]+([^=&]+)=?([^&]*)?/gi, // regexp
            function( m, key, value ) { // callback
                vars[key] = value !== undefined ? value : '';
            }
        );
    
        if ( param ) {
            return vars[param] ? vars[param] : null;	
        }
        return vars;
    }
}
module.exports = modNotice;