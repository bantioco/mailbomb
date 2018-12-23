<br>

<div>
<div>Ajouter la checkbox mailbomb à votre formulaire d'inscription</div>
<div class="mailbomb-code"><pre style="white-space: pre-wrap"><code class="language-html">&lt;input id="_mailbomb_email" type="email" name="_mailbomb_email" placeholder="email"&gt;
&lt;input type="checkbox" name="_mailbomb_register"&gt;</code></pre></div>
</div>

<br>

<div>
<div>Ajouter le formulaire complet</div>
<div class="mailbomb-code"><pre style="white-space: pre-wrap"><code class="language-html">&lt;div class="mailbomb_form"&gt;
    &lt;form id="_mailbomb_form_post" action="/" method="POST"&gt;
        &lt;div class="mailbomb_field"&gt;&lt;input id="_mailbomb_email" type="email" name="_mailbomb_email" placeholder="email"&gt;&lt;/div&gt;
        &lt;div class="mailbomb_field"&gt;&lt;button id="_mailbomb_user_submit" type="submit"&gt;VALIDER&lt;/button&gt;&lt;/div&gt;
        &lt;input type="hidden" value="on" name="_mailbomb_register"&gt;
    &lt;/form&gt;
&lt;/div&gt;</code></pre></div>
</div>

<br>

<div>
<div>Ajouter les notices d'information dans le footer</div>
<div class="mailbomb-code"><pre style="white-space: pre-wrap"><code class="language-html">&lt;div class="mailbomb_send_notice"&gt;
    &lt;div class="mailbomb_send_notice_success mailbomb_notice_success"&gt;Vous êtes désormais inscrit à la newsletter&lt;/div&gt;
    &lt;div class="mailbomb_send_notice_warning mailbomb_notice_exist"&gt;Vous êtes déjà inscrit à la newsleter&lt;/div&gt;
    &lt;div class="mailbomb_send_notice_error mailbomb_notice_invalid_email"&gt;L'email est invalide&lt;/div&gt;
    &lt;div class="mailbomb_send_notice_error mailbomb_notice_error"&gt;Une erreur est survenue !&lt;/div&gt;
&lt;/div&gt;</code></pre></div>

</div>