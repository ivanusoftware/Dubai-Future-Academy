 // Fancybox for gallery
 export default function dffGalleryFancybox() {
    jQuery('.gallery-fancybox').fancybox({
        buttons: [
            "zoom",
            "slideShow",
            "fullScreen",
            "thumbs",
            "close"
        ]
    });
}