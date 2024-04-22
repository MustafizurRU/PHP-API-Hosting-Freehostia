<?php
include("connection.php");
include("auth.php");

//Auth Start
$auth = new authObj();
$isAuthorized = $auth->authenticate();
if(!$isAuthorized)
{
	header("HTTP/1.0 401");
	exit;
}
//Auth End


$db = new dbObj();
$connection = $db->getConnstring();

$request_method = $_SERVER["REQUEST_METHOD"];
switch($request_method)
{
	case 'GET':
		if(!empty($_GET["id"]))
		{
			$id = intval($_GET["id"]);
			getUser($id);
		}
		else
		{
			getUsers();
		}
		break;
	case 'POST':
		$data = json_decode(file_get_contents('php://input'), true);
		insertUser($data["name"], $data["email"], $data["password"]);
		break;
	case 'PUT':
		$id = intval($_GET["id"]);
		$data = json_decode(file_get_contents('php://input'), true);
		updateUser($id, $data["name"]);
		break;
	case 'DELETE':
		$id = intval($_GET["id"]);
		deleteUser($id);
		break;
	default:
		header("HTTP/1.0 405 Method Not Implemented");
		break;
}
	
function getUsers()
{
	global $connection;
	$sql = "SELECT * from user";
	$result = $connection->query($sql);
	$response = array();
	if ($result->num_rows > 0) {
	    while ($row = $result->fetch_assoc()) {
	        array_push($response, $row);
	    }
	}
	header('Content-Type: application/json');
	echo json_encode($response);
}

function getUser($id)
{
	global $connection;
	$sql = "SELECT * from user WHERE id='".$id."'";
	$result = $connection->query($sql);
	$row = $result->fetch_assoc();
	header('Content-Type: application/json');
	echo json_encode($row);
}


function insertUser($name, $email, $password)
{
	global $connection;
	$sql = "INSERT INTO user (name, email,password) VALUES ('".$name."','".$email."','".$password."')";
	$response = array();
	if($connection->query($sql))
	{
		//Success
		header("HTTP/1.0 201");
		$response = array(
	            'status' => 1,
	            'status_message' => 'User Added Successfully.'
        	);		
	}
	else
	{
		//Failed
		header("HTTP/1.0 400");
		$response = array(
	            'status' => 0,
	            'status_message' => 'User Addition Failed.'
        	);
	}
	header('Content-Type: application/json');
	echo json_encode($response);
}

function updateUser($id, $name)
{
	global $connection;
	$sql = "UPDATE user SET name='".$name."' WHERE id='".$id."'";
	$response = array();
	if($connection->query($sql))
	{
		//Success
		$response = array(
	            'status' => 1,
	            'status_message' => 'User Updated Successfully.'
        	);		
	}
	else
	{
		//Failed
		header("HTTP/1.0 400");
		$response = array(
	            'status' => 0,
	            'status_message' => 'User Updation Failed.'
        	);
	}
	header('Content-Type: application/json');
	echo json_encode($response);
}


function deleteUser($id)
{
	global $connection;
	$sql = "DELETE from user WHERE id='".$id."'";
	$response = array();
	if($connection->query($sql))
	{
		//Success
		header("HTTP/1.0 204");	
	}
	else
	{
		//Failed
		header("HTTP/1.0 400");
		$response = array(
	            'status' => 0,
	            'status_message' => 'User Deletion Failed.'
        	);
	}
	header('Content-Type: application/json');
	echo json_encode($response);
}










?>