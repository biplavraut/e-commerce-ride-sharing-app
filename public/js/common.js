Number.prototype.myRound = function(decimals) {
    decimals = decimals || 2;
    let multiplier = Math.pow(10, decimals);
    return (
        Math.round((this.valueOf() * multiplier).toFixed(decimals)) / multiplier
    );
};

Number.random = function(min, max) {
    return min + Math.floor(Math.random() * (max + 1 - min));
};

String.prototype.slug = function() {
    return this.valueOf()
        .toLowerCase()
        .replace(/\&\&+/g, "and") // Replace multiple & with single &
        .replace(/\s+/g, "-") // Replace spaces with -
        .replace(/[^\w\-]+/g, "") // Remove all non-word chars
        .replace(/\-\-+/g, "-") // Replace multiple - with single -
        .replace(/^-+/, "") // Trim - from start of text
        .replace(/-+$/, ""); // Trim - from end of text
};

$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

setTimeout(function() {
    $(".hide-after-some-seconds").fadeOut();
}, 5000);

function alertMessage(message, type, duration) {
    type = type || "success";
    duration = (duration || 5) * 1000;

    let $alertMessage = $(".asdh-alert-message.alert-" + type);
    $alertMessage.children("p").html(message);
    $alertMessage.fadeIn();

    setTimeout(function() {
        $alertMessage.fadeOut();
    }, duration);
}

function copyContent(data, message) {
    var dummy = document.createElement("textarea");
    document.body.appendChild(dummy);
    dummy.value = data;
    dummy.select();
    document.execCommand("copy");
    document.body.removeChild(dummy);

    message = message ? message : "Vendor Id copied to clipboard.";

    this.alertMessage(message);
}

/**
 * Check if scroll has reached the bottom of the $element
 * enclose it inside $(window).on('scroll', function() { METHOD_GOES_HERE })
 *
 * @param $element
 * @returns {boolean}
 */
function isAtTheBottomOf($element) {
    let elementTopPosition = $element.position().top;
    let elementHeight = $element.outerHeight();
    let scrollBarTopPosition = $(window).scrollTop();
    let scrollBarHeight =
        window.innerHeight * (window.innerHeight / document.body.offsetHeight);

    return (
        elementTopPosition + elementHeight <= scrollBarTopPosition + scrollBarHeight
    );
}

/**
 * Generate Random String
 * @param length
 * @returns {String}
 */
function randomString(length) {
    var result = "";
    var characters =
        "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    var charactersLength = characters.length;
    for (var i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
}