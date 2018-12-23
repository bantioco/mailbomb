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

            </div>

            <div data-content="mailbomb-test" class="mailbomb-nav-content">

                <div><h1>Test mailbomb</h1></div>

                <div class="mailbomb-test-ajax-result"></div>

                <div>
                    <p>Envoyer un email de test à un ou plusieur utilisateurs.</p>
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
                        <input id="mailbomb_test_submit" type="submit" name="submit" id="submit" class="button button-primary" value="Valider">
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

                <div>
                    <p>Télécharger la trame d'import : <a href="<?php echo plugin_dir_url(__FILE__).'mailbomb_import.csv';?>">mailbomb_import.csv</a></p>
                </div>

                <form id="mailbomb_import_form" method="POST" enctype="multipart/form-data">

                    <div>
                        <div>
                            <label for="mailbomb_import_users" class="mailbomb_label_file">Ajouter un fichier csv</label>
                            <input type="file" id="mailbomb_import_users" name="mailbomb_import_users">
                            <div class="mailbomb_import_filename"></div>
                        </div>

                        <div>
                            <p class="submit">
                                <input id="mailbomb_import_submit" type="submit" name="submit" id="submit" class="button button-primary" value="Valider">
                            </p>
                        </div>
                    </div>

                </form>

            </div>

            <div data-content="mailbomb-parameter" class="mailbomb-nav-content">

                <div>paramètres mailbomb</div>

            </div>

            <div data-content="mailbomb-cron" class="mailbomb-nav-content">

                <div><h1>CRON mailbomb</h1></div>

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