$(document).ready(function() {
    $(".real-left-nav-menus li").on("click", function() {
        if (!$(this).hasClass("has-drop-down")) {
            $("html").removeClass("nav-open");
            $(".close-layer").remove();
            setTimeout(function() {
                $(".navbar-toggle").removeClass("toggled");
            }, 400);
        }
        mobile_menu_visible = 0;
    });
    $(".collapse-all").on("click", function() {
        var accordionId = '#accordion',
            numPanelOpen = $(accordionId + ' .collapse.in').length;
        if (numPanelOpen > 0) {
            closeAllPanels(accordionId);
        }
    })
    closeAllPanels = function(aId) {
            // console.log("setAllPanelclose");
            $(aId + ' .collapse.in').collapse('hide');
        }
        // openActivePanels = function(aId) {
        //     // $(aId + ' .collapse:not(".in")').collapse('show');
        //     var parentModule = $(aId + ' .active') //the selector of your accordon
        //         .parents('.collapse:not(".in"):first') //get the first element, which would be the div
        //         .collapse('show');
        // }
        // var myAccordionId = '#accordion',
        //     numPanelActive = $(myAccordionId + ' .active').length;
        // if (numPanelActive > 0) {
        //     openActivePanels(myAccordionId)
        // }

});

function makeSlug(string) {
    return string
        .toString()
        .toLowerCase()
        .replace(/&/g, "and") // Replace & with and
        .replace(/\s+/g, "-") // Replace spaces with -
        .replace(/[^\w\-]+/g, "") // Remove all non-word chars
        .replace(/\-\-+/g, "-") // Replace multiple - with single -
        .replace(/^-+/, "") // Trim - from start of text
        .replace(/-+$/, ""); // Trim - from end of text
}

function refreshSelectPicker(timeout) {
    timeout = timeout || 200;

    setTimeout(function() {
        $(".selectpicker").selectpicker("refresh");
    }, timeout);
}

function initializeDatePicker(timeout, format) {
    timeout = timeout || 200;
    format = format || "YYYY-MM-DD";

    setTimeout(function() {
        $(".datepicker").datetimepicker({
            format: format,
            icons: {
                time: "fa fa-clock-o",
                date: "fa fa-calendar",
                up: "fa fa-chevron-up",
                down: "fa fa-chevron-down",
                previous: "fa fa-chevron-left",
                next: "fa fa-chevron-right",
                today: "fa fa-screenshot",
                clear: "fa fa-trash",
                close: "fa fa-remove"
            }
        });
    }, timeout);
}

function initializeTimePicker(timeout, format) {
    timeout = timeout || 200;
    format = format === 12 ? "H:mm A" : "H:mm";

    setTimeout(function() {
        $(".timepicker").datetimepicker({
            format: format,
            icons: {
                time: "fa fa-clock-o",
                date: "fa fa-calendar",
                up: "fa fa-chevron-up",
                down: "fa fa-chevron-down",
                previous: "fa fa-chevron-left",
                next: "fa fa-chevron-right",
                today: "fa fa-screenshot",
                clear: "fa fa-trash",
                close: "fa fa-remove"
            }
        });
    }, timeout);
}

function initializeTinymce(timeout, selector) {
    selector = selector || ".tinymce";
    setTimeout(() => {
        tinymce.init({
            selector: "textarea" + selector,
            setup: editor => {
                editor.on("change keyup", e => {
                    this.$emit("input", editor.getContent());
                });
            },
            height: 300,
            menu: {
                // file  : {title: 'File', items: 'newdocument'},
                // insert: {title: 'Insert', items: 'link media image | template'},
                // view  : {title: 'View', items: 'visualaid'},
                format: {
                    title: "Format",
                    items: "bold italic underline strikethrough superscript subscript | formats | removeformat"
                },
                table: {
                    title: "Table",
                    items: "inserttable tableprops deletetable | cell row column"
                },
                tools: { title: "Tools", items: "spellchecker code" }
            },
            plugins: "link image advlist lists charmap print preview anchor autosave code codesample textcolor colorpicker table searchreplace media print hr preview",
            toolbar: [
                "bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist link unlink charmap code", // image media indent outdent
                "formatselect fontselect fontsizeselect | forecolor backcolor"
            ]
        });
    }, 500);
}