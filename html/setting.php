<div id="mailbomb_setting" class="wrap mailbomb-wrap">
    <h1>Mail bomb</h1>
    <div class="mailbomb-container">

        <div class="nav-tab-wrapper">
            
            <a class="nav-tab mailbomb-nav nav-tab-active" href="#mailbomb-dashboard" data-nav="mailbomb-dashboard">Tableau de bord</a>
            <a class="nav-tab mailbomb-nav" href="#mailbomb-test" data-nav="mailbomb-test">Test</a>
            <a class="nav-tab mailbomb-nav" href="#mailbomb-users-list" data-nav="mailbomb-users-list">Liste utilisateurs</a>
            <a class="nav-tab mailbomb-nav" href="#mailbomb-export-list" data-nav="mailbomb-export-list">Exporter la liste utilisateurs</a>
            <a class="nav-tab mailbomb-nav" href="#mailbomb-users-import" data-nav="mailbomb-users-import">Importer des utilisateurs</a>
            <a class="nav-tab mailbomb-nav" href="#mailbomb-parameter" data-nav="mailbomb-parameter">Paramètres</a>
            <a class="nav-tab mailbomb-nav" href="#mailbomb-cron" data-nav="mailbomb-cron">Taches planifiées</a>
            <a class="nav-tab mailbomb-nav" href="#mailbomb-developper" data-nav="mailbomb-developper">Développeur</a>
            
        </div>

        <div data-content="mailbomb-dashboard" class="mailbomb-nav-content mailbomb-nav-content-active">

            <div><h1>Dashboard mailbomb</h1></div>

            <!--
            <br>

            <div class="mailbomb_logo_container">
                <div class="mailbomb_logo_img"><img width="350" src="<?php //echo home_url().'/wp-content/plugins/mailbomb/assets/src/images/mailbomb-plane-t.png';?>" alt=""></div>

                <ul class="mailbomb_logo_bomb">
                    <li class="bomb_item bomb_item_1">@</li>
                    <li class="bomb_item bomb_item_2">@</li>
                    <li class="bomb_item bomb_item_3">@</li>
                    <li class="bomb_item bomb_item_4">@</li>
                    <li class="bomb_item bomb_item_5">@</li>
                </ul>
            </div>
            -->

            <br>

            <div class="mailbomb_users_total_dashboard">
                <span>[ Nombre total d'utilisateurs : <?php echo $totalUsers;?> ] </span>
            </div>

            <div>
                <p class="">
                    <button class="mailbomb-btn" id="mailbomb_send_newsletter">Envoyer la newsletter</button>
                </p>
            </div>

            <br>
            <br>

            <div>
                <div>Ajouter le formulaire à vos pages</div>
                <div>
                <pre style="white-space: pre-wrap"><code class="language-html">[mailbomb-form]</code></pre>
                </div>
            </div>

            <br>
            <br>

            <div>

                <p>
                    <div>Ajouter un template html <small>" Copier le code html puis valider "</small></div>
                </p>

                <form method="POST">

                    <div>
                        <input type="text" name="mailbomb_add_name_template" id="mailbomb_add_name_template" class="mailbomb_add_name_template" placeholder="Nom du template" required="required">
                    </div>

                    <div>
                        <textarea name="mailbomb_add_html_template" id="mailbomb_add_html_template" class="mailbomb_add_html_template" placeholder="Contenu html du template" required="required"></textarea>
                    </div>

                    <br>

                    <div>
                        <button type="submit" name="mailbomb_add_template_btn" class="mailbomb-btn">VALIDER</button>
                    </div>

                </form>

            </div>

            <br>

            <div>
                <p><div>Importer un template</div></p>

                <form method="POST">

                    <div>
                        <input type="file" name="mailbomb_add_name_template" id="mailbomb_add_name_template" class="mailbomb_add_name_template" placeholder="Nom du template" required="required">
                    </div>

                    <br>

                    <div>
                        <button type="submit" name="mailbomb_import_template_btn" class="mailbomb-btn">VALIDER</button>
                    </div>

                    <br>

                </form>

            </div>

            <br>
            <br>                    

        </div>

        <div data-content="mailbomb-test" class="mailbomb-nav-content">

            <div><h1>Test mailbomb</h1></div>

            <div class="mailbomb-test-ajax-result"></div>

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

        <div data-content="mailbomb-users-list" class="mailbomb-nav-content">

            <div><h1>Users list mailbomb</h1></div>

            <br>

            <?php require_once('users_list.php'); ?>

        </div>

        <div data-content="mailbomb-export-list" class="mailbomb-nav-content">

            <div>Export mailbomb</div>

        </div>

        <div data-content="mailbomb-users-import" class="mailbomb-nav-content">

            <div><h1>Import mailbomb</h1></div>

            <br>

            <?php require('users_import.php'); ?>

        </div>

        <div data-content="mailbomb-parameter" class="mailbomb-nav-content">

            <div><h1>Paramètres mailbomb</h1></div>

            <br>

            <div>
                <div>
                <form method="POST">
                    <span class="mailbomb_large_span">Items par page - Users list :</span> <input type="number" class="mailbomb_input_large" name="users_list_items_per_page" min="1" max="100" value="<?php echo $numberItems;?>" > 
                    <input type="hidden" name="users_list_items_per_page_id" value="<?php echo $itemsPerPageId;?>">
                    <input id="users_list_items_per_page_submit" type="submit" name="submit" class="mailbomb-btn" value="Valider">
                </form>
                </div>

                <br>

                <div>
                    <form method="POST">
                        
                        <span class="mailbomb_large_span">Template par défaut ( filename .php ) - Newsletter :</span>

                        <select class="mailbomb_input_large" name="mailbomb_template_default" id="mailbomb_template_default">
                            <?php 
                                foreach( $mailbombTemplates as $template ):

                                    $selected = "";
                                    if( $defaultTemplate === $template->template_name ) $selected = 'selected';
                            ?>
                                    <option <?php echo $selected;?> value="<?php echo $template->template_name;?>"><?php echo $template->template_name;?></option>
                            <?php endforeach;?>
                        </select>

                        <input type="hidden" name="mailbomb_template_default_id" value="<?php echo $defaultTemplateParamsId;?>">
                        
                        <input id="mailbomb_template_default_submit" type="submit" name="submit" class="mailbomb-btn" value="Valider">
                    </form>
                </div>
            </div>

            <br>

        </div>

        <div data-content="mailbomb-cron" class="mailbomb-nav-content">

            <div><h1>WP CRON mailbomb</h1></div>

            <br>

            <div>
                <form method="POST">
                    <table class="mailbomb_cron_table">
                        <thead>
                            <th class="mailbomb_cron_th">JOURS</th>
                            <th class="mailbomb_cron_th">HEURES</th>
                            <th class="mailbomb_cron_th">MINUTES</th>
                            <th class="mailbomb_cron_th">MOIS</th>
                            <th class="mailbomb_cron_th">TEMPLATE</th>
                            <th class="mailbomb_cron_th"></th>
                        </thead>

                        <tbody>

                            <tr>

                                <td class="mailbomb_cron_td">

                                    <div>
                                        <select name="mailbomb_cron_day" id="mailbomb_cron_day">
                                            <option value="all_day">Tous les jours</option>
                                            <option value="01">01</option>
                                            <option value="01">02</option>
                                            <option value="01">03</option>
                                            <option value="01">04</option>
                                            <option value="01">05</option>
                                            <option value="01">06</option>
                                            <option value="01">07</option>
                                            <option value="01">08</option>
                                            <option value="01">09</option>
                                            <option value="01">10</option>
                                            <option value="01">11</option>
                                            <option value="01">12</option>
                                            <option value="01">13</option>
                                            <option value="01">14</option>
                                            <option value="01">15</option>
                                            <option value="01">16</option>
                                            <option value="01">17</option>
                                            <option value="01">18</option>
                                            <option value="01">19</option>
                                            <option value="01">20</option>
                                            <option value="01">21</option>
                                            <option value="01">22</option>
                                            <option value="01">23</option>
                                            <option value="01">24</option>
                                            <option value="01">25</option>
                                            <option value="01">26</option>
                                            <option value="01">27</option>
                                            <option value="01">28</option>
                                        </select>
                                    </div>
                                </td>

                                <td class="mailbomb_cron_td">
                                    <div>
                                        <select name="mailbomb_cron_hours" id="mailbomb_cron_hours">
                                            <option value="all_hours">Toutes les heures</option>
                                            <option value="01">00</option>
                                            <option value="01">01</option>
                                            <option value="01">02</option>
                                            <option value="01">03</option>
                                            <option value="01">04</option>
                                            <option value="01">05</option>
                                            <option value="01">06</option>
                                            <option value="01">07</option>
                                            <option value="01">08</option>
                                            <option value="01">09</option>
                                            <option value="01">10</option>
                                            <option value="01">11</option>
                                            <option value="01">12</option>
                                            <option value="01">13</option>
                                            <option value="01">14</option>
                                            <option value="01">15</option>
                                            <option value="01">16</option>
                                            <option value="01">17</option>
                                            <option value="01">18</option>
                                            <option value="01">19</option>
                                            <option value="01">20</option>
                                            <option value="01">21</option>
                                            <option value="01">22</option>
                                            <option value="01">23</option>
                                            <option value="01">24</option>
                                        </select>
                                    </div>
                                </td>

                                <td class="mailbomb_cron_td">
                                    <div>
                                        <select name="mailbomb_cron_hours" id="mailbomb_cron_hours">
                                            <option value="all_minutes">Toutes les minutes</option>
                                            <option value="01">00</option>
                                            <option value="01">01</option>
                                            <option value="01">02</option>
                                            <option value="01">03</option>
                                            <option value="01">04</option>
                                            <option value="01">05</option>
                                            <option value="01">06</option>
                                            <option value="01">07</option>
                                            <option value="01">08</option>
                                            <option value="01">09</option>
                                            <option value="01">10</option>
                                            <option value="01">11</option>
                                            <option value="01">12</option>
                                            <option value="01">13</option>
                                            <option value="01">14</option>
                                            <option value="01">15</option>
                                            <option value="01">16</option>
                                            <option value="01">17</option>
                                            <option value="01">18</option>
                                            <option value="01">19</option>
                                            <option value="01">20</option>
                                            <option value="01">21</option>
                                            <option value="01">22</option>
                                            <option value="01">23</option>
                                            <option value="01">24</option>
                                        </select>
                                    </div>
                                </td>

                                <td class="mailbomb_cron_td">

                                    <div>
                                        <select name="mailbomb_cron_month" id="mailbomb_cron_month">
                                            <option value="all_month">Tous les mois</option>
                                            <option value="01">01</option>
                                            <option value="01">02</option>
                                            <option value="01">03</option>
                                            <option value="01">04</option>
                                            <option value="01">05</option>
                                            <option value="01">06</option>
                                            <option value="01">07</option>
                                            <option value="01">08</option>
                                            <option value="01">09</option>
                                            <option value="01">10</option>
                                            <option value="01">11</option>
                                            <option value="01">12</option>

                                        </select>
                                    </div>

                                </td>

                                <td class="mailbomb_cron_td"></td>

                                <td class="mailbomb_cron_td mailbomb_cron_td_center"><button type="submit" name="submit" class="mailbomb-btn">VALIDER</button></td>

                            </tr>

                        </tbody>

                    </table>
                </form>

            </div>

        </div>

        <div data-content="mailbomb-developper" class="mailbomb-nav-content">

            <div><h1>Developpeur mailbomb</h1></div>

            <?php require_once('developper.php'); ?>

        </div>

    </div>
</div>

<div class="modal-bg"></div>

<div class="modal modal-confirm modal-small">

    <div>Confirmer</div>

    <div class="mailbomb-flex">
        <div class="mailbomb-flex"><div class="btn-valid">OUI</div></div>
        <div class="mailbomb-flex"><div class="btn-cancel">NON</div></div>
    </div>

</div>