<?php
require 'config/database.php';
require 'Bootstrap.php';

$bootstrap = new Bootstrap();

Session::init();

if (Session::get('id') == null) {

    /*
     *  Get the http.php file from http://www.phpclasses.org/httpclient
     */
    require('./lib/http.php');
    require('./lib/oauth_client.php');

    $client = new oauth_client_class;
    $client->server = 'Google';

// set the offline access only if you need to call an API
// when the user is not present and the token may expire
    $client->offline = false;

    $client->debug = false;
    $client->debug_http = true;
    $client->redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] .
            dirname(strtok($_SERVER['REQUEST_URI'], '?')) . 'login_with_google.php';

    $client->client_id = GOOGLE_CLIENT_ID;
    $application_line = __LINE__;
    $client->client_secret = GOOGLE_CLIENT_SECRET;

    if (strlen($client->client_id) == 0 || strlen($client->client_secret) == 0)
        die('Please go to Google APIs console page ' .
                'http://code.google.com/apis/console in the API access tab, ' .
                'create a new client ID, and in the line ' . $application_line .
                ' set the client_id to Client ID and client_secret with Client Secret. ' .
                'The callback URL must be ' . $client->redirect_uri . ' but make sure ' .
                'the domain is valid and can be resolved by a public DNS.');

    /* API permissions
     */
    $client->scope = 'https://www.googleapis.com/auth/userinfo.email ' .
            'https://www.googleapis.com/auth/userinfo.profile';
    if (($success = $client->Initialize())) {
        if (($success = $client->Process())) {
            if (strlen($client->authorization_error)) {
                $client->error = $client->authorization_error;
                $success = false;
            } elseif (strlen($client->access_token)) {
                $success = $client->CallAPI(
                        'https://www.googleapis.com/oauth2/v1/userinfo', 'GET', array(), array('FailOnAccessError' => true), $user);
            }
        }
        $success = $client->Finalize($success);
    }
    if ($client->exit)
        exit;
    if ($success) {

        $password = Tools::generatePassword(9, false, 'lud');
        $userObj = new User($bootstrap->db);
        $result = $userObj->loadByEmail($user->email);
        if (!$result) {
            $userObj->create(null, $user->name, $user->email, $password, 'google', 0, time(), 0);
            $userObj->save();
        }
        Session::set('id', $userObj->getId());
        header("Location: ".PATH);
        die();
        ?>
        <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
        <html>
            <head>
                <title>Google OAuth client results</title>
            </head>
            <body>
                <?php
                echo '<h1>', HtmlSpecialChars($user->name),
                ' you have logged in successfully with Google!</h1>';
                echo '<pre>', HtmlSpecialChars(print_r($user, 1)), '</pre>';
                ?>
            </body>
        </html>
        <?php
    } else {
        ?>
        <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
        <html>
            <head>
                <title>OAuth client error</title>
            </head>
            <body>
                <h1>OAuth client error</h1>
                <pre>Error: <?php echo HtmlSpecialChars($client->error); ?></pre>
            </body>
        </html>
        <?php
    }
} else {
    $userObj = new User($bootstrap->db);
    $result = $userObj->load(Session::get('id'));
    if ($result) {
        header("Location: http://gate.makerlabs.co.za/index.php");
        die();
    } else {
        Session::destroy();
        header("Location: http://gate.makerlabs.co.za/login.php");
        die();
    }
}
?>