let modExport = {

    Flatpickr: ( selector )=> {

        let flatpickr = require("flatpickr");

        $( selector ).flatpickr({
            enableTime: true,
            dateFormat: "Y-m-d H:i:s"
        });
    }
}
module.exports = modExport;