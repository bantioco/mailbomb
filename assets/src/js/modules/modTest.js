let modTest = {

    TestFieldAddIndex: ()=> {

        if( $('.mailbomb-test-field').is(':visible') ){

            $('.mailbomb-test-field').each( function( index, item ){

                $(item).attr('data-index', index);
            });
        }
    },

    TestField: ()=> {

        $d.off('click', '.mailbomb-field-add').on('click', '.mailbomb-field-add', function(){

            let $this = $(this);

            let value = $this.parent().parent().find('input[name="mailbomb_test_send[]"]').val();

            if( value.length > 4 ) {

                modTest.AddTestField( $this );
            }
            else {

                $this.parent().parent().find('input[name="mailbomb_test_send[]"]').val('Email invalide !');

                setTimeout( function(){ 
                    $this.parent().parent().find('input[name="mailbomb_test_send[]"]').val('');
                },1500);
            }
        });

        $d.off('click', '.mailbomb-field-delete').on('click', '.mailbomb-field-delete', function(){

            $(this).parent().parent('.mailbomb-test-field').remove();

            modTest.TestFieldAddIndex();
        });

        $d.off('click', '#mailbomb_test_submit').on('click', '#mailbomb_test_submit', function( e ){

            e.preventDefault();

            $('.modal-bg').fadeIn(300, function(){
                $('.modal-confirm').fadeIn(300);
            });

            $d.off('click', '.btn-valid').on('click', '.btn-valid', function(){

                $('.mailbomb-field-sending').show().addClass('rotate');
                $('.mailbomb-test-ajax-result').html('');

                $('.modal').fadeOut(300, function(){ $('.modal-bg').fadeOut(300); });

                modTest.TestGetEmail( function( ArrayEmails ){

                    if( ArrayEmails.length <= 0 ){

                        $('.mailbomb-field-sending').removeClass('rotate').hide();

                        $('.mailbomb-test-ajax-result').html('Aucuns emails de test détectés ! Vérifiez les valeurs de vos champs !');
                    }

                    console.log( ArrayEmails );
                });

            });
        })
    },

    TestGetEmail: ( callback )=> {

        let ArrayEmails = [];

        let Template    = $('#mailbomb_template_test').val();

        $('input[name="mailbomb_test_send[]"]').each( function( key, item ){

            let Email = $(item).val();
            let Index = $(item).parent().parent().attr('data-index');

            if( Email.length >= 1 ){

                ArrayEmails[key] = Email;

                modTest.TestAjaxPost( Email, Index, Template, function( result ){

                    if( result.success ) modTest.SendSuccess( result );
                });
            }
        });

        return callback( ArrayEmails );
    },

    SendSuccess: ( result )=> {

        //$('.mailbomb-test-ajax-result').html('');

        if( result.data.send ){

            $('.mailbomb-test-ajax-result')
                .prepend('<div class="mailbomb-test-result-item"><div class="mailbomb-ball-green"></div> <div class="mailbomb-result-email">' + result.data.email + '</div></div>' );
        }
        else{
            $('.mailbomb-test-ajax-result')
                .prepend('<div class="mailbomb-test-result-item"><div class="mailbomb-ball-red"></div> <div class="mailbomb-result-email">' + result.data.email + '</div></div>' );
        }

        $('.mailbomb-test-field[data-index="'+result.data.index+'"]').find('.mailbomb-field-sending').hide();
    },

    AddTestField: ( $this )=> {

        let $clone = $this.parent().parent('.mailbomb-test-field').clone();

        $this.hide();
        $this.siblings('.mailbomb-field-delete').show();

        $clone.insertAfter( $this.parent().parent('.mailbomb-test-field') );

        modTest.TestFieldAddIndex();
    },

    TestAjaxPost: ( Email, Index, Template, callback )=> {

        $.post(
            ajaxurl,
            {
                action: 'mailbombTestSend',
                mailbomb_test_email: Email, 
                mailbomb_test_index: Index,
                mailbom_test_template: Template
            },
            function( result ){

                return callback( result );
            }
        );
    }
}
module.exports = modTest;