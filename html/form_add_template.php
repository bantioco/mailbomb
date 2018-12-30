<div class="notice mailbomb_notice_table">

    <div class="mailbomb_notice_table_checkbox">

        <div class="notice_table_checkbox_item">
            <label for="mailbomb_add_html_template_btn">
                <input type="checkbox" data-type="add" id="mailbomb_add_html_template_btn" name="mailbomb_add_html_template_btn" value="1"> Ajouter un template html
            </label>
        </div>

        <div class="notice_table_checkbox_item">
            <label for="mailbomb_import_html_template_btn">
                <input type="checkbox" data-type="import" id="mailbomb_import_html_template_btn" name="mailbomb_import_html_template_btn" value="1"> Importer un template
            </label>
        </div>
        
    </div>

    <div class="mailbomb_notice_table_form_add">

        <form method="POST" enctype="multipart/form-data">

            <div>
                <p>Titre du template</p>
                <input type="text" name="mailbomb_add_name_template" id="mailbomb_add_name_template" class="mailbomb_add_name_template" placeholder="Nom du template" required="required">
            </div>

            <br>

            <div>
                <p>Coller le code html</p>
                <textarea name="mailbomb_add_html_template" id="mailbomb_add_html_template" class="mailbomb_add_html_template" placeholder="Contenu html du template"></textarea>
            </div>

            <br>

            <div>
                <p>Ajouter les images dans un dossier, compresser le dossier et importer le *.zip</p>
                <div class="mailbomb_template_add_zipname"></div>
                <label for="mailbomb_add_img_template">ADD ZIP FILE<input type="file" name="mailbomb_add_img_template" id="mailbomb_add_img_template"></label>
            </div>

            <br>

            <div>
                <button type="submit" name="mailbomb_add_template_btn" class="mailbomb-btn">VALIDER</button>
            </div>

        </form>

        <br>

    </div>

    <div class="mailbomb_notice_table_form_import">

        <form method="POST" enctype="multipart/form-data">

            <div>
                <p>Importer un fichier "HTML, PHP"</p>
                <div class="mailbomb_template_import_filename"></div>
                <label for="mailbomb_import_file_template">ADD FILE HTML OR PHP<input type="file" name="mailbomb_import_file_template" id="mailbomb_import_file_template" required="required"></label>
            </div>

            <br>

            <div>
                <p>Ajouter les images dans un dossier, compresser le dossier et importer le *.zip</p>
                <div class="mailbomb_template_import_zipname"></div>
                <label for="mailbomb_import_img_template">ADD ZIP FILE<input type="file" name="mailbomb_import_img_template" id="mailbomb_import_img_template"></label>
            </div>

            <br>

            <div>
                <button type="submit" name="mailbomb_import_template_btn" class="mailbomb-btn">VALIDER</button>
            </div>

            <br>

        </form>

    </div>

</div>