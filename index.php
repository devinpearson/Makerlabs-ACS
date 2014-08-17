<?php

require 'config/database.php';
require 'Bootstrap.php';
require 'lib/TimeSlot.php';

try {
    $bootstrap = new Bootstrap();
    $bootstrap->initSession();
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
//level from the form post
        $level = $_POST["level"];

        $my_access_token = ACCESS_TOKEN;
        $my_device = DEVICE_ID;
        $output_pin = "r1";

        $url = SPARK_PATH . $my_device . "/relay2";
        $fields = array();
        $fields['access_token'] = $my_access_token;
        //$fields['args'] = $output_pin . "," . $level;
        $fields['args'] = "r2,HIGH";
        $accessLogObj = new AccessLog($bootstrap->db);
        $service = json_decode(Tools::curl_download($url, $fields));
        if (is_null($service)) {
            //echo "CoRe not ReSpOnDinG!";
            $accessLogObj->create(null, "Onsite device not responding", $bootstrap->userObj->getId(), time());
        } else {
            //var_dump($service);
            if ($service->return_value == 1) {
                //echo "Open Sesame";

                $accessLogObj->create(null, "Opened Gate", $bootstrap->userObj->getId(), time());
            } else {
                //echo "something aint right";
                $accessLogObj->create(null, "Failed to Open", $bootstrap->userObj->getId(), time());
            }
        }
        $accessLogObj->save();
    }
    // Check time slots
    $timeSlotObj = new TimeSlot($bootstrap->db);
    if ($timeSlotObj->isItTime(time())) {
        //echo "<br />It is time!<br />";
    } else {
        //echo "<br />Its not time!<br />";
    }
    $isAdmin = ($bootstrap->userObj->getAccessLevel() > 1) ? 1 : 0;
    $bootstrap->smarty->assign('isAdmin', $isAdmin);
    $bootstrap->smarty->assign('menuSelected', 'home');
    
    $bootstrap->smarty->assign('url', PATH."index.php");
    $bootstrap->smarty->display('test.tpl');
} catch (SmartyException $e) {
    echo 'Templating engine problem';
    die();
}