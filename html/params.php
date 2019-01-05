<div class="mailbomb_setting_container">

    <div>

        <div class="mailbomb_setting_title">Pagination liste utilisateurs</div>

        <div>
            <form method="POST">
                <span class="mailbomb_large_span">Items par page - Users list :</span> <input type="number" class="mailbomb_input_large" name="users_list_items_per_page" min="1" max="100" value="<?php echo $numberItems;?>" > 
                <input type="hidden" name="users_list_items_per_page_id" value="<?php echo $itemsPerPageId;?>">
                <input id="users_list_items_per_page_submit" type="submit" name="submit" class="mailbomb-btn" value="Valider">
            </form>
        </div>

    </div>

    <br><br>

    <div>

        <div class="mailbomb_setting_title">Template par defaut</div>

        <div>
            <form method="POST">
                
                <span class="mailbomb_large_span">Template par défaut - Newsletter :</span>

                <select class="mailbomb_input_large" name="mailbomb_template_default_newsletter" id="mailbomb_template_default_newsletter">
                    <?php 
                        foreach( $mailbombTemplates as $template ):

                            $selected = "";
                            if( $defaultTemplateNewsletter === $template->template_name ) $selected = 'selected';
                    ?>
                            <option <?php echo $selected;?> value="<?php echo $template->template_name;?>"><?php echo $template->template_name;?></option>
                    <?php endforeach;?>
                </select>

                <input type="hidden" name="mailbomb_template_default_newsletter_id" value="<?php echo $defaultTemplateNewsletterId;?>">
                
                <input id="mailbomb_template_default_newsletter_submit" type="submit" name="submit" class="mailbomb-btn" value="Valider">
            </form>

        </div>

        <br>

        <div>
            <form method="POST">
                
                <span class="mailbomb_large_span">Template par défaut - Inscription :</span>

                <select class="mailbomb_input_large" name="mailbomb_template_default_register" id="mailbomb_template_default_register">
                    <?php 
                        foreach( $mailbombTemplates as $template ):

                            $selected = "";
                            if( $defaultTemplateRegister === $template->template_name ) $selected = 'selected';
                    ?>
                            <option <?php echo $selected;?> value="<?php echo $template->template_name;?>"><?php echo $template->template_name;?></option>
                    <?php endforeach;?>
                </select>

                <input type="hidden" name="mailbomb_template_default_register_id" value="<?php echo $defaultTemplateRegisterId;?>">
                
                <input id="mailbomb_template_register_submit" type="submit" name="submit" class="mailbomb-btn" value="Valider">
            </form>

        </div>

        <br>

        <div>
            <form method="POST">
                
                <span class="mailbomb_large_span">Template par défaut - Désinscription :</span>

                <select class="mailbomb_input_large" name="mailbomb_template_default_unregistered" id="mailbomb_template_default_unregistered">
                    <?php 
                        foreach( $mailbombTemplates as $template ):

                            $selected = "";
                            if( $defaultTemplateUnregistered === $template->template_name ) $selected = 'selected';
                    ?>
                            <option <?php echo $selected;?> value="<?php echo $template->template_name;?>"><?php echo $template->template_name;?></option>
                    <?php endforeach;?>
                </select>

                <input type="hidden" name="mailbomb_template_default_unregistered_id" value="<?php echo $defaultTemplateUnregisteredId;?>">
                
                <input id="mailbomb_template_unregistered_submit" type="submit" name="submit" class="mailbomb-btn" value="Valider">
            </form>

        </div>


    </div>

    <br>

</div>