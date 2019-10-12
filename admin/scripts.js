document.addEventListener('DOMContentLoaded', function () {

  var button_preview = document.getElementById("button_preview");
  if (button_preview != null) {

    button_preview.addEventListener('click', function() { showPreview(); });

    var textarea = document.getElementById("text");
    textarea.addEventListener('input', function() { resize_textarea(); });

    if (typeof(textarea) != "undefined") CKEDITOR.replace( 'text' );

    document.getElementById("hide_preview").addEventListener(
      'click', function() {
        document.getElementById("preview_wrapper").style.display = "none";
      });

    var template_files = document.getElementsByClassName("template_files");
    for (i = 0; i < template_files.length; i++) {

      template_files[i].addEventListener('click', function() {

        document.getElementById("subject").value = this.getAttribute("data-subject");
        document.getElementById("text").value = this.getAttribute("data-text");

        resize_textarea();

      });

    }

    var button_save_template = document.getElementById("button_save_template");
    button_save_template.addEventListener('click', function() {
      document.getElementById("save_template").style.display = "block";
    });

    var hide_save_template = document.getElementById("hide_save_template");
    hide_save_template.addEventListener('click', function() {
      document.getElementById("save_template").style.display = "none";
    });

    var button_save_template_action = document.getElementById("button_save_template_action");
    button_save_template_action.addEventListener('click', function() {
      document.getElementById("template_name").value = document.getElementById("input_template_name").value;
      document.getElementById("template_subject").value = document.getElementById("subject").value;
      document.getElementById("template_text").value = document.getElementById("text").value;
      document.getElementById("form_save_template").submit();
    });

    resize_textarea();

  }

  // the config page will need reloading after something was saved
  var needReload = document.getElementById("needReload");

  if (needReload != null) {

    var timer = 3;
    countDown(timer);

  }

});


function resize_textarea() {

  var textarea = document.getElementById("text");

  textarea.style.height = "10px";
  textarea.style.height = (textarea.scrollHeight + 10) + "px";

}


function showPreview() {

  document.getElementById("preview_wrapper").style.display = "block";

  var subject = document.getElementById("subject").value;
  var text = document.getElementById("text").value.replace(/(?:\r\n|\r|\n)/g, '<br>');

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
