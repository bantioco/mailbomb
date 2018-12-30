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
                <input id="mailbomb_import_submit" type="submit" name="submit" id="submit" class="mailbomb-btn" value="Valider">
            </p>
        </div>
    </div>

</form>