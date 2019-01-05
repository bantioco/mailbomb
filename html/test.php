<div class="mailbomb_setting_container">

    <div class="mailbomb-test-ajax-result"></div>

    <div>
        <p>Choisir un template.</p>
    </div>

    <div>

        <select class="mailbomb_input_large" name="mailbomb_template_test" id="mailbomb_template_test">
            <?php 
                foreach( $mailbombTemplates as $template ):

                    $selected = "";
                    if( $defaultTemplate === $template->template_name ) $selected = 'selected';
            ?>
                    <option <?php echo $selected;?> value="<?php echo $template->template_name;?>"><?php echo $template->template_name;?></option>
            <?php endforeach;?>
        </select>

    </div>

    <br>

    <div>
        <p>Envoyer un email de test à un ou plusieurs utilisateurs.</p>
    </div>

    <div data-index="0" class="mailbomb-flex mailbomb-test-field">
        <div class="mailbom-item-flex"><input type="email" name="mailbomb_test_send[]" placeholder="Enter l'email utilisateur"></div>
        <div class="mailbom-item-flex">
            <div class="mailbomb-field-add">+</div>
            <div class="mailbomb-field-delete">-</div>
        </div>

        <div class="mailbom-item-flex">
            <div class="mailbomb-field-sending"></div>
        </div>
    </div>

    <div>
        <p class="submit">
            <button id="mailbomb_test_submit" type="submit" name="submit" class="mailbomb-btn">Valider</button>
        </p>
    </div>
</div>