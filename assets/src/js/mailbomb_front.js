import "../scss/mailbomb_front.scss";

"use-strict";

jQuery(document).ready(function($) {

    window.$    = $;
    window.$d   = $(document);

    let modNotice = require('./modules/modNotice.js');
        modNotice.Init();
});