// $(document).ready(function() {
//     $(".copy-link").click(function() {
//     var text = $("#text-to-copy").text();
//     var $tempInput = $("<textarea>");
//     $("body").append($tempInput);
//     $tempInput.val(text).select();
//     document.execCommand("copy");
//     $tempInput.remove();
//     });
//     });
document.addEventListener("DOMContentLoaded", function () {

 function copy(){
     var text = document.getElementById("text-to-copy").innerText;
     var elem = document.createElement("textarea");
     document.body.appendChild(elem);
     elem.value = text;
     elem.select();
     document.execCommand("copy");
     document.body.removeChild(elem);
     }
    });