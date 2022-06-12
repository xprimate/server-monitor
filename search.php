<?php
    $query = trim($_GET['query']);
    $server = $_GET['server'];
    
    if ($query==""){
        
        echo "<h2>Your Input is empty, Please type a username.</h2>";
        return;
    }
    
   
    
    //Get backed up users from file
      $user_server = $server;
    $user_file="/home/ogbonnan/public_html/monitor/data/$user_server"."monitorData.txt";
$file1 = file_get_contents($user_file, true);
//picks the string starting from "user"
$backup_list = stristr($file1, "user");
//End of search string
$stop_string = "backup_date";
 $pattern= "$query"."(.*?)"."$stop_string";
 //checks if the user is found
 $user_search= "~\b".$query."\b~";

if (preg_match($user_search,$backup_list) ){
    preg_match("/$pattern/", $backup_list, $match);
    echo "<h2>$query"." Has the following Backup(s):"."</h2>";
      echo "<p><b>$match[1]</p>";
      
      echo "<a href='http://ogbonna.name.ng/monitor/' title='Go to home page'>Home</a>";
}
else{
  echo "<h2> No Backup </h2>";
  
  echo "<a href='http://ogbonna.name.ng/monitor/' title='Go to home page'>Home</a>";
}
   
    ?>