<?php
/* 
Author: Kenrick Beckett
Author URL: http://kenrickbeckett.com
Name: Chat Engine 2.0

*/
require_once("../dbcon.php");

//Start Array
$data = array();
// Get data to work with
		$current = cleanInput($_GET['current']);
		$room = cleanInput($_GET['room']);
		$username = cleanInput($_GET['username']);
		$now = time();
// INSERT your data (if is not already there)
       	$findUser = "SELECT * FROM `chat_users_rooms` WHERE `username` = '$username' AND `room` ='$room' ";
		
		if(!hasData($findUser))
				{
					$insertUser = "INSERT INTO `chat_users_rooms` (`id`, `username`, `room`, `mod_time`) VALUES ( NULL , '$username', '$room', '$now')";
					mysql_query($insertUser) or die(mysql_error());
				}		
		 	$findUser2 = "SELECT * FROM `chat_users` WHERE `username` = '$username'";
			if(!hasData($findUser2))
				{
					$insertUser2 = "INSERT INTO `chat_users` (`id` ,`username` , `status` ,`time_mod`)
					VALUES (NULL , '$username', '1', '$now')";
					mysql_query($insertUser2);
					$data['check'] = 'true';
				}			
		$finish = time() + 7;
		$getRoomUsers = mysql_query("SELECT * FROM `chat_users_rooms` WHERE `room` = '$room'");
		$check = mysql_num_rows($getRoomUsers);
        	
	    while(true)
		{
			usleep(10000);
			mysql_query("UPDATE `chat_users` SET `time_mod` = '$now' WHERE `username` = '$username'");
			$olduser = time() - 5;
			$eraseuser = time() - 30;
			mysql_query("DELETE FROM `chat_users_rooms` WHERE `mod_time` <  '$olduser'");
			mysql_query("DELETE FROM `chat_users` WHERE `time_mod` <  '$eraseuser'");
			$check = mysql_num_rows(mysql_query("SELECT * FROM `chat_users_rooms` WHERE `room` = '$room' "));
			$now = time();
			if($now <= $finish)
			{
				mysql_query("UPDATE `chat_users_rooms` SET `mod_time` = '$now' WHERE `username` = '$username' AND `room` ='$room'  LIMIT 1") ;
				if($check != $current){
				 break;
				}
			}
			else
			{
				 break;	
		    }
        }		 		
// Get People in chat
		if(mysql_num_rows($getRoomUsers) != $current)
		{
			$data['numOfUsers'] = mysql_num_rows($getRoomUsers);
			// Get the user list (Finally!!!)
			$data['userlist'] = array();
			while($user = mysql_fetch_array($getRoomUsers))
			{
				$data['userlist'][] = $user['username'];
			}
			$data['userlist'] = array_reverse($data['userlist']);
		}
		else
		{
			$data['numOfUsers'] = $current;	
			while($user = mysql_fetch_array($getRoomUsers))
			{
				$data['userlist'][] = $user['username'];
			}
			$data['userlist'] = array_reverse($data['userlist']);
		}
		echo json_encode($data);

?>