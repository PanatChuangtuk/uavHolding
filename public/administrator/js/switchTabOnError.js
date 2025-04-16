$(document).ready(function() {
    let hasError = false;
    let firstErrorTab = null;

    $(".tab-pane").each(function() {
        if ($(this).find(".text-danger").length > 0) {
            let tabId = $(this).attr("id");
            firstErrorTab = tabId;
            hasError = true;
            return false; 
        }
    });

    if (hasError && firstErrorTab) {
        $('button[data-bs-target="#' + firstErrorTab + '"]').tab("show");
    }
});
