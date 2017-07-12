<?php session_start();
include '../src/php/essentials.php';

$_DATA = $_POST;
if (isset($_DATA['password'])) {
    $username = (isset($_DATA['username']) ? $_DATA['username'] : (isset($_SESSION['user']['username']) ? $_SESSION['user']['username'] : ""));
    $client = new \php\api\client($username);
    if (isset($_DATA['signin'])) {
        if (isset($_DATA['username'])) {
            if ($client->sign_in($_DATA['password'])) {
                $_SESSION['user'] = $client->getPublicData();
                success('Successfully logged in!');
            } else
                error('Bad username or password!');
        }
    } else if (isset($_DATA['signup'])) {
        if ($client->sign_up($_DATA['password'])) {
            $_SESSION['user'] = $client->getPublicData();
            success('You were automatically logged in. Welcome!');
        } else
            error('Username already exists!');
    } else if (isset($_DATA['update'])) {
        if ($client->update($_DATA['password'], $_DATA)) {
            $_SESSION['user'] = $client->getPublicData();
            success('Account details successfully updated!');
        } else
            error('Failed to update account details...');
    }
}

header('Location: /index.php');
exit;
