document.addEventListener('DOMContentLoaded', function () {

  var button_preview = document.getElementById("button_preview");
  if (button_preview != null) {

    button_preview.addEventListener('click', function() { showPreview(); });

  }

});



function showPreview() {

  document.getElementById("preview").style.display = "block";

  var subject = document.getElementById("subject").value;
  var text = document.getElementById("text").value;

  document.getElementById("preview_subject").innerHTML = subject;
  document.getElementById("preview_text").innerHTML = text;

}

