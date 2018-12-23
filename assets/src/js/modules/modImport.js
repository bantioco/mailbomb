let modImport = {

    Init: ()=> {

        $d.off('change', 'input[name="mailbomb_import_users"]').on('change', 'input[name="mailbomb_import_users"]', function( e ){

            let filepath = this.value;
            let filename = filepath.replace(/C:\\fakepath\\/, '');

            let extension = filename.split('.').pop();

            if( extension != 'csv' ){

                $('.mailbomb_import_filename').html( "Le fichier n'est pas de type csv" );

                this.value = '';

                return;
            }

            $('.mailbomb_import_filename').html( filename );
        });
    }
}
module.exports = modImport;