(function($) {
  "use strict";

  $(document).ready(function() {
    // VARS
    var searchInput = $("#search-input");
    var messageInput = $("#input");
    var clearSearch = $("#clearSearch");
    var messageMain = $(".message .message__has-scroll");
    var messageLoaded = $(".message .message_loaded");
    var sendButton = $("#sendButton");
    var input_ip = $("#client-ip").val();
    var color = "black";
    // CALL FUNCTION
    getData();
    checkCookie();

    function checkCookie() {
      if ($.cookie("new-user") != 1) {
        $(".tap-target").tapTarget("open");
        var $toastContent = $("<span>This website uses cookies</span>").add(
          $(
            '<button class="btn-flat toast-action" style="margin-left:0" onClick="Materialize.Toast.removeAll();">OK</button>'
          )
        );
        Materialize.toast($toastContent, 10000, "", function() {
          $.cookie("new-user", "1", { expires: 1 });
        });
      } else {
      }
    }
    function getData() {
      $.ajax({
        type: "GET",
        url: "message.php",
        data: "data",
        success: function(e) {
          messageMain.html(e);
        },
        error: function() {
          messageMain.html(
            "<div class='data-error'><p><i class='material-icons vertical-align-middle'>info</i> Something went wrong</p></div>"
          );
        }
      });
    }

    messageInput
      .on("blur", function() {
        $("#label,#underline").removeClass("active");
      })
      .on("focus", function() {
        $("#label,#underline ").addClass("active");
      });
    $("#search-input")
      .on("blur", function() {
        $("#search-form").removeClass("active");
      })
      .on("focus", function() {
        $("#search-form").addClass("active");
      });
    // RIPPLE
    var buttons = document.querySelectorAll(".mdc-button");
    for (var i = 0, button; (button = buttons[i]); i++) {
      mdc.ripple.MDCRipple.attachTo(button);
    }
    //  CONFIG
    $(".modal").modal({
      dismissible: false,
      inDuration: 0,
      outDuration: 0,
      endingTop: "50%"
    });

    // MESSAGE
    messageInput.keyup(function() {
      var trigger = false;
      if (!$(this).val()) {
        trigger = true;
      }
      trigger
        ? sendButton.attr("disabled", true)
        : sendButton.removeAttr("disabled");
    });
    $(".select-container > a").click(function() {
      color = $(this).attr("data-color");
      $(".select-container > a").removeClass();
      $(this).addClass("active");
      messageInput.css("color", color);
    });
    // MESSAGE FORM
    $("#messageForm").submit(function(event) {
      var input_data = messageInput.val();
      var cancelButton = $(".cancel-button");

      $(".submit-load,.overlay").show();
      $("#sendButton,.cancel-button").attr("disabled", true);
      if (messageInput.val().length > 0) {
        $.ajax({
          data: {
            data: input_data,
            ip: input_ip,
            color: color
          },
          type: "post",
          url: "message.php",
          success: function(data) {
            if (data == "success") {
              $("#send-modal").modal("close");
              Materialize.toast("Message Sent!", 4000);
              var messege =
                "<span class='message__item' data-key='NULL' style='color:" +
                color +
                "'><div>" +
                input_data +
                "</div></span>";
              $(".message .message__has-scroll").prepend(messege);
              $(".submit-load,.overlay").hide();
              messageInput.val("");
              $(".cancel-button").attr("disabled", false);
              getData();
            } else {
              $("#send-modal").modal("close");
              Materialize.toast("Failed to send message!", 4000);
              $(".submit-load,.overlay").hide();
              messageInput.val("");
              $(".cancel-button").attr("disabled", false);
            }
          },
          error: function() {
            Materialize.toast("Something went wrong!", 4000);
            setTimeout(location.reload.bind(location), 4800);
          }
        });
      }

      event.preventDefault();
    });
    // SEARCH
    searchInput.keyup(function() {
      var trigger = false;
      if (!$(this).val()) {
        trigger = true;
      }
      trigger ? clearSearch.hide() : clearSearch.show();
    });
    $("#search-form").submit(function(e) {
      var value = searchInput.val();
      if (searchInput.val()) {
        messageMain.hide();
        $.ajax({
          data: {
            search: value
          },
          type: "post",
          url: "message.php",
          success: function(data) {
            messageLoaded.show().html(data);
          }
        });
      } else {
        console.log("else working");
        messageLoaded.hide();
        messageMain.show();
      }
      e.preventDefault();
    });
    // HIDE CANCEL BUTTON
    clearSearch.click(function() {
      searchInput.val("");
      $(this).hide();
      messageMain.show();
      messageLoaded.hide().html("");
    });
    // SUBMIT DEMO!
    searchInput.submit(function() {
      console.log("Just submited");
    });
    // MESSEGE GET DATA AND DISPLAY ON THE MODAL
    $(document).on("click", ".message__item", function() {
      var key = $(this).attr("data-key");
      $("#data-modal").modal("open");
      if (key !== "NULL") {
        $.ajax({
          data: {
            view: key,
            ipv: input_ip
          },
          type: "post",
          url: "message.php",
          success: function(data) {
            $("#data-modal .modal-content").removeAttr("hidden");
            $("#data-modal #data-preload").hide();
            $("#data-modal .modal-content").html(data);
            $(".tooltipped").tooltip();
          }
        });
      } else {
        $("#data-modal .modal-content").removeAttr("hidden");
        $("#data-modal #data-preload").hide();
        $("#data-modal .modal-content").html(
          "<div class='data-error'><p><i class='material-icons vertical-align-middle'>info</i> Something went wrong</p></div>"
        );
      }
    });
    // SHARE LINK
    $(document).on("click", ".copy-link-input", function() {
      this.select();
    });
    $(document).on("click", ".data-copy-button", function() {
      var copyInput = $(".copy-link-input");
      $(copyInput)
        .focus()
        .select();
      document.execCommand("copy");
      $("#data-modal").modal("close");
      Materialize.toast("Link copied to clipboard", 4000);
    });
  }); //END READY FUNCTION
})(jQuery);
