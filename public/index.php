<?php session_start();

if (isset($_GET['signout'])) {
    unset($_SESSION['user']);
    include '../src/php/essentials.php';
    success('Successfully logged out!');
    header('Location: /index.php');
    exit;
} else if (isset($_GET['manage'])) {
    if (!isset($_SESSION['user'])) {
        include '../src/php/essentials.php';
        info('You must login to access this page!');
        header('Location: /index.php');
        exit;
    }
}

$VIEWS_DIR = '../view';
$_DATA = $_GET;

// render page
include "$VIEWS_DIR/_header.html";
include "$VIEWS_DIR/top_bar.html";

if (isset($_DATA['manage']))
    include "$VIEWS_DIR/account.html";

include "$VIEWS_DIR/_footer.html";
