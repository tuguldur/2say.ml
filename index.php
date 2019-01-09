
  <?php
//connect db and set char utf8
$db = @mysqli_connect('localhost', 'root', '', 'data');
mysqli_set_charset($db, 'utf8');
//config.php
function getIp()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Say</title>
    <link rel="icon" href="/img/favicon.png" />
    <!-- Compiled and minified CSS -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css"
    />
    <link
      href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="css/MDC/mdc.ripple.min.css" />
    <link rel="stylesheet" href="css/MDC/mdc.button.min.css" />
    <!-- Minified JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js"></script>
    <script src="js/jquery.cookie.js"></script>
    <link rel="stylesheet" href="css/style.css" />
  </head>

  <body>
    <!-- TOOLBAR -->
    <div id="toolbar">
      <div id="toolbar-search">
        <form id="search-form">
          <div id="search">
            <button
              type="submit"
              class="btn-floating waves-effect waves-black btn-flat search-button"
            >
              <i class="material-icons">search</i>
            </button>
            <div id="searchTerm">
              <input
                type="search"
                id="search-input"
                placeholder="Search"
                spellcheck="false"
                autocomplete="off"
              />
            </div>
            <button
              class="btn-floating waves-effect waves-black btn-flat search-button"
              id="clearSearch"
            >
              <i class="material-icons">cancel</i>
            </button>
          </div>
        </form>
      </div>
    </div>
    <div class="message">
      <!-- MAIN -->
      <div class="message__has-scroll"></div>
      <div class="message_loaded" style="display:none"></div>
      <!-- ACTION BUTTONS -->
      <div class="actions">
        <a
          class="btn-floating btn-large waves-effect waves-light red modal-trigger"
          data-target="send-modal"
          id="action-menu"
          ><i class="material-icons">add</i></a
        >
        <!-- Tap Target Structure -->
        <div class="tap-target" data-activates="action-menu">
          <div class="tap-target-content">
            <h5>I am here</h5>
            <p>Click here to write what you want to say to someone you love.</p>
          </div>
        </div>
      </div>
    </div>
    <!-- MAIN END -->
    <!-- MODAL DON'T TOUCH -->
    <div id="data-modal" class="modal" style="display: none">
      <div class="header-action">
        <a
          class="btn-floating modal-close waves-effect waves-black btn-flat"
          id="close_button"
          ><i class="material-icons">close</i></a
        >
      </div>
      <div class="modal-content" hidden></div>
      <div id="data-preload">
        <div class="preloader-wrapper small active">
          <div class="spinner-layer spinner-gray-only">
            <div class="circle-clipper left"><div class="circle"></div></div>
            <div class="gap-patch"><div class="circle"></div></div>
            <div class="circle-clipper right"><div class="circle"></div></div>
          </div>
        </div>
      </div>
    </div>

    <div id="send-modal" class="modal" style="display: none">
      <div class="overlay" style="display: none"></div>
      <div class="modal-title">Add a post</div>
      <div class="modal-body">
        <form id="messageForm">
          <div id="label">Write a message</div>
          <div id="row-container">
            <div id="input-container">
              <input id="input" autofocus autocomplete="off" />
              <input type="hidden" id="client-ip" value="<?php echo getIp(); ?>" />
              <div id="underline"></div>
            </div>
          </div>
        </form>
      </div>
      <div class="color-select">
        <div id="label-color">Select color</div>
        <div class="select-container">
          <a href="#" data-color="black" class="active"
            ><div class="select-circle black"></div
          ></a>
          <a href="#" data-color="#f44336"
            ><div class="select-circle" style="background-color:#f44336;"></div
          ></a>
          <a href="#" data-color="#03a9f4"
            ><div class="select-circle" style="background-color:#03a9f4;"></div
          ></a>
          <a href="#" data-color="#9c27b0"
            ><div class="select-circle" style="background-color:#9c27b0;"></div
          ></a>
          <a href="#" data-color="#ff9800"
            ><div class="select-circle" style="background-color:#ff9800;"></div
          ></a>
          <a href="#" data-color="#009688"
            ><div class="select-circle" style="background-color:#009688;"></div
          ></a>
          <a href="#" data-color="#e91e63"
            ><div class="select-circle" style="background-color:#e91e63;"></div
          ></a>
          <a href="#" data-color="#121ca8"
            ><div class="select-circle" style="background-color:#121ca8;"></div
          ></a>
          <a href="#" data-color="#8f2c08;"
            ><div class="select-circle" style="background-color:#8f2c08;"></div
          ></a>
          <a href="#" data-color="#10d610"
            ><div class="select-circle" style="background-color:#10d610;"></div
          ></a>
        </div>
      </div>
      <div class="modal-button-container">
        <div class="submit-load" style="display: none">
          <div class="preloader-wrapper extra-small active">
            <div class="spinner-layer spinner-gray-only">
              <div class="circle-clipper left"><div class="circle"></div></div>
              <div class="gap-patch"><div class="circle"></div></div>
              <div class="circle-clipper right"><div class="circle"></div></div>
            </div>
          </div>
        </div>
        <button class="mdc-button secondary-button cancel-button modal-close">
          Cancel
        </button>
        <button
          class="mdc-button secondary-button"
          id="sendButton"
          form="messageForm"
          disabled
        >
          Send
        </button>
      </div>
    </div>
    <footer class="footer">
      <div class="footer__content">
        <div class="footer__row footer-legal">

          <ul class="footer-legal__nav-list">
            <li class="footer-legal__nav-list-item">
              <a href="#" target="_blank" class="footer-legal__nav-list-link">
                Үйлчилгээний нөхцөл
              </a>
            </li>
            <li class="footer-legal__nav-list-item">
              <a href="#" target="_blank" class="footer-legal__nav-list-link">
                Нууцлалын бодлого
              </a>
            </li>

            <li class="footer-legal__nav-list-item">
              <a href="/" class="footer-legal__nav-list-link copy">
                &copy; 2018 Tuguldur
              </a>
            </li>
          </ul>
        </div>
      </div>
    </footer>

    <script src="js/MDC/mdc.ripple.min.js"></script>
    <script src="js/index.js"></script>
  </body>
</html>
