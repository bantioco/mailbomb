<div class="mailbomb_setting_container">

    <br>

    <div>
        <form id="mailbomb_export_form" method="POST">

            <div class="mailbomb_setting_title">Choix du type de fichier</div>

            <div class="mailbomb-flex">
                <div class="mailbom-item-flex"><label for="export_type_xlsx"><input type="radio" id="export_type_xlsx" name="export_type" value="xlsx" checked></label>XLSX</div>
                <div class="mailbom-item-flex"><label for="export_type_csv"><input type="radio" id="export_type_csv" name="export_type" value="csv">CSV</label></div>
            </div>

            <br>

            <div class="mailbomb_setting_title">Choix par date</div>

            <div class="mailbomb-flex">
                <div class="mailbom-item-flex"><input type="text" id="export_date_start" name="export_date_start" placeholder="Date de début"></div>
                <div class="mailbom-item-flex"><input type="text" id="export_date_end" name="export_date_end" placeholder="Date de fin"></div>
            </div>

            <div>
                <p class="submit">
                    <input id="mailbomb_import_submit" type="submit" name="submit" id="submit" class="mailbomb-btn" value="Valider">
                </p>
            </div>

        </form>
    </div>
</div>