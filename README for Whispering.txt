-----------
Add Whispering in Chat v2, by Christian Mehler
--------------


Changes to chat.js: 

function Chat (filetxt, user) {
file = filetxt;
usernameid = user;
this.init = chatInit;
this.update = updateChat;
this.send = sendChat;
this.getState = getStateOfChat;
this.trim = trimstr;
this.getUsers = getuserlist;
}

function updateChat(){

$.ajax({

type: "GET",
url: "update.php",
data: { 
'state': state,
'file' : file,
'nickname': usernameid
},
dataType: "json",
...

function getuserlist(room, username) {

roomid = room;
usernameid = username;

$.ajax({
type: "GET",
url: "userlist.php",
data: { 
'room' : room,
'username': username,
'current' : numOfUsers

},
dataType: "json",
cache: false,
success: function(data) {

if (numOfUsers != data.numOfUsers) {
numOfUsers = data.numOfUsers;
var list = "

Current Chatters
";
for (var i = 0; i < data.userlist.length; i++) { 
list += '
'+ data.userlist[i] +"
";
}
$('#userlist').html($("
"+ list +"
"));
}

setTimeout('getuserlist(roomid, usernameid)', 1);

},
});

}

function wisper(to)
{
$('#sendie').val('@'+to+' '+$('#sendie').val());
}





---------
Changes to process.php (other saving):
---------

fwrite(fopen($file, 'a'), $nickname . "~\t~" . $message = str_replace("\n", " ", $message) . "\n"); 


---------
Changes to update.php
---------

- at the beginning add
$nickname = htmlentities(strip_tags($_GET['nickname']), ENT_QUOTES);


-later
...
if ($state == $count) {

$log['state'] = $state;
$log['t'] = "continue";

} else {

$text= array();
$log['state'] = $state + getlines(getfile($file)) - $state;

foreach (getfile($file) as $line_num => $line) {
if ($line_num >= $state) {
$line = explode("~\t~", $line);
if(substr($line[1], 0, 1)!='@')
$text[] = ''.$line[0].''.$line[1];
elseif($line[0]==$nickname || substr($line[1], 0, strlen('@'.$nickname))=='@'.$nickname)
$text[] = ''.$line[0].' '.$line[1].'';
}

$log['text'] = $text; 
}
}

echo json_encode($log);	

?>