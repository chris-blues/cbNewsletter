document.addEventListener('DOMContentLoaded', function () {

  var button_preview = document.getElementById("button_preview");
  if (button_preview != null) {

    button_preview.addEventListener('click', function() { showPreview(); });

  }

  // the config page will need reloading after something was saved
  var needReload = document.getElementById("needReload");

  if (needReload != null) {

    var timer = 3;
    countDown(timer);

  }

});



function showPreview() {

  document.getElementById("preview").style.display = "block";

  var subject = document.getElementById("subject").value;
  var text = document.getElementById("text").value;

  document.getElementById("preview_subject").innerHTML = subject;
  document.getElementById("preview_text").innerHTML = text;

}

function countDown(timer) {

  var link = needReload.getAttribute("data-link");
  var spanTimer = document.getElementById("timer");

  spanTimer.innerHTML = timer;

  if (timer == 0) {
    window.location = link;
  }

  timer = timer - 1;

  setTimeout(function(){ countDown(timer); }, 1000);

}
