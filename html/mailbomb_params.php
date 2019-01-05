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

            <?php require_once('dashboard.php'); ?>                  

        </div>

        <div data-content="mailbomb-test" class="mailbomb-nav-content">

            <div><h1>Test mailbomb</h1></div>

            <?php require_once('test.php'); ?>

        </div>

        <div data-content="mailbomb-users-list" class="mailbomb-nav-content">

            <div><h1>Users list mailbomb</h1></div>

            <br>

            <?php require_once('users_list.php'); ?>

        </div>

        <div data-content="mailbomb-export-list" class="mailbomb-nav-content">

            <div><h1>Export mailbomb</h1></div>

            <br>

            <?php require_once('users_export.php'); ?>

        </div>

        <div data-content="mailbomb-users-import" class="mailbomb-nav-content">

            <div><h1>Import mailbomb</h1></div>

            <br>

            <?php require_once('users_import.php'); ?>

        </div>

        <div data-content="mailbomb-parameter" class="mailbomb-nav-content">

            <div><h1>Paramètres mailbomb</h1></div>

            <br><br>

            <?php require_once('params.php'); ?>

        </div>

        <div data-content="mailbomb-cron" class="mailbomb-nav-content">

            <div><h1>WP CRON mailbomb</h1></div>

            <br>

            <?php require_once('cron.php'); ?>

        </div>

        <div data-content="mailbomb-developper" class="mailbomb-nav-content">

            <div><h1>Developpeur mailbomb</h1></div>

            <?php require_once('developper.php'); ?>

        </div>

    </div>

</div>

<div class="modal-bg"></div>

<div class="modal modal-confirm modal-small">

    <div class="modal-title">Confirmer</div>

    <br>

    <div class="mailbomb-flex">
        <div class="mailbomb-flex modal-btn-container"><div class="btn-valid">OUI</div></div>
        <div class="mailbomb-flex modal-btn-container"><div class="btn-cancel">NON</div></div>
    </div>

</div>