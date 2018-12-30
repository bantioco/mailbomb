let modNav = {

    settingNav: ()=> {



        modNav.GetHash(); 

        modNav.WrapShow();

        $d.off('click', '.mailbomb-nav').on('click', '.mailbomb-nav', function(){

            let dataNav = $(this).attr( 'data-nav' );

            modNav.Navigate( dataNav );
        });
    },

    WrapShow: ()=> {
        
        setTimeout(function(){ $('.mailbomb-wrap').addClass('mailbomb-wrap-visible'); }, 1000);
    },

    GetHash: ()=> {

        let hash = window.location.hash;

        let validHash = [
            "#mailbomb-dashboard",
            "#mailbomb-test",
            "#mailbomb-users-list",
            "#mailbomb-export-list",
            "#mailbomb-users-import",
            "#mailbomb-parameter",
            "#mailbomb-cron",
            "#mailbomb-developper"
        ]

        if( hash && ( validHash.indexOf(hash) != -1 ) ) {

            hash = hash.replace('#', '');

            modNav.Navigate( hash );
        }
    },

    Navigate: ( dataNav )=> {

        $('.mailbomb-nav').removeClass('nav-tab-active');
        $('.mailbomb-nav[data-nav="'+dataNav+'"]').addClass('nav-tab-active');

        $('.mailbomb-nav-content').hide();
        $('.mailbomb-nav-content').removeClass('mailbomb-nav-content-active');

        $('.mailbomb-nav-content[data-content="'+dataNav+'"]').show();
        $('.mailbomb-nav-content[data-content="'+dataNav+'"]').addClass('mailbomb-nav-content-active');

    }
}
module.exports = modNav;