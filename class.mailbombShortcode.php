<?php

class mailbombShortcode
{
    private static $initiated = false;
	
	public static function init() 
    {
        if ( ! self::$initiated ) 
        {
			self::initHooks();
		}
    }

    /**
	 * Initializes WordPress hooks
	 */
    private static function initHooks() 
    {
        self::$initiated = true;

        add_shortcode( 'mailbomb-form', [ 'mailbombShortcode','mailbomb_form_shortcode' ] );
    }

    /**
     * SHORTCODE - [mailbomb-form]
     */
    public static function mailbomb_form_shortcode( $atts )
    {

        $html = '<div class="mailbomb_form">';
            $html .= '<h3>Inscription à la newsletter</h3>';
            $html .= '<form id="_mailbomb_form_post" action="/" method="POST">';
                $html .= '<div class="mailbomb_field"><input id="_mailbomb_email" type="email" name="_mailbomb_email" placeholder="email"></div>';
                $html .= '<div class="mailbomb_field"><button id="_mailbomb_user_submit" type="submit">VALIDER</button></div>';
                $html .= '<input type="hidden" value="on" name="_mailbomb_register">';
            $html .= '</form>';
        $html .= '</div>';

        $html .= '<div class="mailbomb_send_notice">';
            $html .= '<div class="mailbomb_send_notice_success mailbomb_notice_success">Vous êtes désormais inscrit à la newsletter</div>';
            $html .= '<div class="mailbomb_send_notice_warning mailbomb_notice_exist">Vous êtes déjà inscrit à la newsleter</div>';
            $html .= '<div class="mailbomb_send_notice_error mailbomb_notice_invalid_email">L\'email est invalide</div>';
            $html .= '<div class="mailbomb_send_notice_error mailbomb_notice_error">Une erreur est survenue !</div>';
        $html .= '</div>';

        return $html;
    }

}