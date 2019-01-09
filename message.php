<?php
$db = @mysqli_connect('localhost', 'root', '', 'data');
mysqli_set_charset($db, 'utf8');

function GenKey($length)
{
    $chars = "1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
    $clen = strlen($chars) - 1;
    $id = '';

    for ($i = 0; $i < $length; $i++) {
        $id .= $chars[mt_rand(0, $clen)];
    }
    return ($id);
}
function time_ago_in_php($timestamp)
{

    date_default_timezone_set("Asia/Ulaanbaatar");
    $time_ago = strtotime($timestamp);
    $current_time = time();
    $time_difference = $current_time - $time_ago;
    $seconds = $time_difference;

    $minutes = round($seconds / 60); // value 60 is seconds
    $hours = round($seconds / 3600); //value 3600 is 60 minutes * 60 sec
    $days = round($seconds / 86400); //86400 = 24 * 60 * 60;
    $weeks = round($seconds / 604800); // 7*24*60*60;
    $months = round($seconds / 2629440); //((365+365+365+365+366)/5/12)*24*60*60
    $years = round($seconds / 31553280); //(365+365+365+365+366)/5 * 24 * 60 * 60

    if ($seconds <= 60) {

        return "Саяхан";

    } else if ($minutes <= 60) {

        if ($minutes == 1) {

            return "Нэг минутын өмнө";

        } else {

            return "$minutes минутын өмнө";

        }

    } else if ($hours <= 23) {

        if ($hours == 1) {

            return "Нэг цагийн өмнө";

        } else {

            return "$hours цагийн өмнө";

        }

    } else if ($days <= 7) {

        if ($days == 1) {

            return "Өчигдөр";

        } else {

            return "$days өдрийн өмнө";

        }

    } else if ($weeks <= 4.3) {

        if ($weeks == 1) {

            return "Долоо хоногийн өмнө";

        } else {

            return "$weeks долоо хоногийн өмнө";

        }

    } else if ($months <= 12) {

        if ($months == 1) {

            return "Сарын өмнө";

        } else {

            return "$months сарын өмнө";

        }

    } else {

        if ($years == 1) {

            return "Нэг жилийн өмнө";

        } else {

            return "$years жилийн өмнө";

        }
    }
}

$key = GenKey(10);
// FUNCTIONS
function getData()
{
    global $db;
    $messege_query = "SELECT * FROM messege ORDER BY id DESC";
    $run_query = mysqli_query($db, $messege_query) or die(mysqli_error($db));
    $i = 0;
    $delay = 0.005;
    if (mysqli_num_rows($run_query) > 0) {
        while ($row = mysqli_fetch_array($run_query)) {
            $messege = htmlspecialchars($row["data"]);
            $data_key = $row["data-key"];
            $data_color = $row["data-color"];
            $animation_delay = ($delay * $i);
            echo ("<span class='message__item' data-key='$data_key' style='animation-delay:".$animation_delay."s;color:$data_color;'><div>$messege</div></span>");
            $i++;
        }
    }
}
function data()
{
    global $db, $key;
    $messege = $_POST['data'];
    $ip = $_POST['ip'];
    $color = $_POST['color'];
    if (strlen($messege) >= 3 && strlen($messege) <= 420 && strlen($color) <= 10) {
        $query = "INSERT INTO `messege` (`id`, `data`, `time`, `ip`, `data-key`,`data-color`,`views`) VALUES (NULL, '$messege', CURRENT_TIMESTAMP, '$ip', '$key','$color','0')";
        mysqli_query($db, $query);
        echo ("success");
    } else {
        echo ("error");
    }

}
function view()
{
    global $db;
    $view_key = $_POST['view'];
    $ipv = $_POST['ipv'];
    $ip = "";
    $query = "SELECT * FROM `messege` WHERE `data-key`='$view_key' LIMIT 1";
    $results = mysqli_query($db, $query) or die(mysqli_error($db));
    $server = $_SERVER['SERVER_NAME'];
    if (mysqli_num_rows($results) == 1) {
        while ($row = mysqli_fetch_array($results)) {
            $messege = htmlspecialchars($row["data"]);
            $date = time_ago_in_php($row["time"]);
            $date_pure = $row["time"];
            $views = number_format($row["views"]);
            $ip = $row["ip"];
            $key = $row["data-key"];
            $color = $row["data-color"];
            echo "
            <div class='data-message'>
            <div class='data-message-container' style='color:$color'>$messege</div>
            </div>
            <div class='data-link'>
            <p class='data-title'>Share a link</p>
            <div class='data-copy-link'>
            <input readonly value='$server/view?v=$key' class='copy-link-input' />
            <a class='waves-effect waves-black btn-flat data-copy-button'>COPY</a>
            </div>
           </div>
			<div class='data-date'>
				<span class='data-view'>$views үзэлт</span>
                <span class='data-publish-date tooltipped' data-position='bottom' data-delay='50' data-tooltip='$date_pure'>$date</span>
			</div>
            ";
        }

    } else {
        error();
    }
    if ($ip !== $ipv) {
        $update_query = " UPDATE `messege` SET `views` = `views`+1 WHERE `data-key`='$view_key'";
        $results = mysqli_query($db, $update_query) or die(mysqli_error($db));
    }

}

function search()
{
    global $db;
    $search = $_POST["search"];
    $query = "SELECT *	FROM `messege` WHERE `data` LIKE '%{$search}%'";
    $run_query = mysqli_query($db, $query) or die(mysqli_error($db));
    if (mysqli_num_rows($run_query) > 0) {
        while ($row = mysqli_fetch_array($run_query)) {
            $messege = htmlspecialchars($row["data"]);
            $data_key = $row["data-key"];
            $color = $row["data-color"];
            echo "<span class='message__item' data-key='$data_key' style='color:$color'><div>$messege</div></span>";
        }
    } else {
        echo "<div class='data-error'><p><i class='material-icons vertical-align-middle'>info</i> No search results found</p></div>";
    }
}
function error()
{
    echo "<div class='data-error'><p><i class='material-icons vertical-align-middle'>info</i> Something went wrong</p></div>";
}

if (isset($_GET["data"])) {
    getData();
} elseif (!empty($_POST["data"]) || !empty($_POST["ip"] || !empty($_POST["color"]))) {
    data();
} elseif (!empty($_POST["view"]) || !empty($_POST["ipv"])) {
    view();
} elseif (!empty($_POST["search"])) {
    search();
} else {
    error();
}
