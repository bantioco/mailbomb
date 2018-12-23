let modModal = {

    Init:()=> {
        modModal.Close();
    },

    Close: ()=> {

        $d.off('click', '.modal-bg, .btn-cancel').on('click', '.modal-bg, .btn-cancel', function(){

            $('.modal').fadeOut(300, function(){ $('.modal-bg').fadeOut(300); });
        });
    }
}
module.exports = modModal;