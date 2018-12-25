<?php

include_once dirname(dirname(__FILE__)) .'\\'. 'autoload.php';


if (isset($_POST['type']) && $_POST['type'] == "like") {

	// Infomation result for responsive
	$result = ['status' => 200];

	// New connect and call API
	$user = new UserController();
    $info = $user->GetUser(str_replace('%40', '@', $_POST['username']));

    // Valid user comment
    if ((strcmp(gettype($info), 'string') == 0) && strcmp('Tài khoản không hợp lệ', $info) == 0) {

    	$result['status'] = 404;
    	$result['respText'] = $info;
    } else {	

    	$status = new StatusController();

    	// User like comment
    	$message = $status->LikeForStatus($info['id'], $_POST['id_status']);

    	$message = 'Thành công';
    	$result['respText'] = $message;
    }

	echo json_encode($result);
} else if (isset($_POST['type']) && $_POST['type'] == "unlike") {

	// Infomation result for responsive
	$result = ['status' => 200];

	// New connect and call API
	$user = new UserController();
    $info = $user->GetUser(str_replace('%40', '@', $_POST['username']));

    // Valid user comment
    if ((strcmp(gettype($info), 'string') == 0) && strcmp('Tài khoản không hợp lệ', $info) == 0) {

    	$result['status'] = 404;
    	$result['respText'] = $info;
    } else {	

    	$status = new StatusController();

    	// User like comment
    	$message = $status->UnLikeForStatus($info['id'], $_POST['id_status']);

    	$message = 'Thành công';
    	$result['respText'] = $message;
    }

	echo json_encode($result);
} else {

	echo "Are your kidding me?";
}