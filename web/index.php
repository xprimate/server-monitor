
<!DOCTYPE html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Server Monitor</title>
	<meta content="text/html" charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
	  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
     <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script src="monitor.js"></script>
	
	<style>
	.icon{
		max-width: 50px;
		max-height: 50px;
	}
	
}
.hiddenRow {
    padding: 0 4px !important;
    background-color: #eeeeee;
    font-size: 13px;
}
	
	</style>

</head>
<html><div class="">
<?php


//echo "<h2>Please bear with us, maintenance in progress...</h2>";
///////////////////////////////////////////////////////////////////////////////
//  MODIFY THE FGET PATH FOR "monitorData.txt" FOR EACH USER NAME
// THAT IS '/home/[username]/public_html/monitor/monitorData.txt
// CHANGE THE SERVER NAME

//Table Heading
echo $data = "<small><table  class='table'><thead><tr ><th>Server</th><th>HTTP</th><th style='position: relative; left: 20px;'>MySQL</th><th style='position: relative; left: 30px;'>Load</th><th style='position: relative; left: 65px;'>Backups</th><th style='position: relative; left: 80px;'>HmUsed</th><th th style='position: relative; left: 70px;'>BckUsed</th><th style='position: relative; left: 40px;'>BootUsed</th><th th style='position: relative; left: 40px;'>VarUsed</th><th th style='position: relative; left: 40px;'>SwapUsed</th><th style='position: relative; left: 20px;'>MailQueu</th><th th style='position: relative; right: 5px;'>DeliMail(24hr)</th><th style='position: relative; right: 35px;'>UsedRAM</th><th style='position: relative; right: 30px;'>UsedCPU</th></tr></thead>";

////////////////////////////////////////////EMERALD1 //////////////////////////
//Reset and initialiaze variables
$timeout =1;$file1 = "";$buffer1 = "";$data="";$fp="";$errno="";$errstr="";$timeout="";$load="";$loadColor="";$availableBackupDates="";$disk_space="";$disk_free="";$disk_used_precent="";$disk_used_precent_color="";$backupValue="";$backupValueColor="";$bootValue="";$bootValueColor="";$swap ="";$mailQueue="";$deliMail=""; $homeValueColor="";$homeValue="";$httpStatus="";$mysqlStatus="";$varValue="";$varValueColor="";
 
//Get the shell output file from "monitorData.txt"
 $file1 = file_get_contents('/home/ogbonnan/public_html/monitor/data/E1monitorData.txt', true);
$buffer1 = preg_split("/:/", $file1, -1, PREG_SPLIT_NO_EMPTY);
$data .= "<small><table  class='table'>";
//Server name
    // Data toggle
$data .="<tr  class='parent' id='2801'><td ><span class='btn btn-default'><b>E1</b></span></td>";
//HTTP status
$httpStatus = stream_socket_client("67.220.184.130:80", $errno, $errstr, 30);
	if (!$httpStatus) {
		$data .= "<td><b>Offline</b></td>";
	  fclose($httpStatus);
	} else {
		$data .= "<td><b>Online</b></td>";
		fclose($httpStatus);
	}
//MySQL status
// Find the correct MySql port number.
$mysqlStatus = stream_socket_client("67.220.184.130:3306", $errno, $errstr, 30);

	if (!$mysqlStatus) {
		$data .= "<td style='color:red;'><b>Offline</b></td>";
	  fclose($mysqlStatus);
	} else {
		$data .= "<td><b>Online</b></td>";
		fclose($mysqlStatus);
	}
//SERVER LOAD
$load = substr($buffer1[15], 0, 4);
if($load >= 20){
$loadColor = 'red';
}
$data .= "<td style='color: $loadColor;'><b>$load</b></td>";
//Available backups Dates
$availableBackupDates =availableBackupDates();
$data .= "<td><b>$availableBackupDates</b></td>";
//Home Diskspace used
$homeValue = preg_replace('/[^0-9]/', '', $buffer1[19]); // remove the percentage from the value;
if ($homeValue >= 75 ){
    $homeValueColor = 'red';
}
$data .= "<td style='position: relative; right: 80px; style='color: $homeValueColor;'><b>".$homeValue."%</b></td>";


//Backup Disk Space used
$backupValue = preg_replace('/[^0-9]/', '', $buffer1[1]); // remove the percentage from the value;
if ($backupValue >= 90 ){
    $backupValueColor = 'red';
}
$data .= "<td style='color: $backupValueColor; position: relative; right: 85px;'><b>".$backupValue ."%</b></td>";
$backupValue = "";
//Boot partition used
$bootValue = preg_replace('/[^0-9]/', '', $buffer1[17]); // remove the percentage from the value;
if ($bootValue >= 90 ){
    $bootValueColor = 'red';
}
$data .= "<td class='text-center' style='color: $bootValueColor; position: relative; right: 110px;'><b>".$bootValue . "%</b></td>";

//Var Partition used
$varValue = preg_replace('/[^0-9]/', '', $buffer1[21]); // remove the percentage from the value;
if ($varValue >= 90 ){
    $varValueColor = 'red';
}
$data .= "<td class='text-center' style='color: $varValueColor; position: relative; right: 85px;'><b>".$varValue . "%</b></td>";

//Swap Diskspace used
if (!empty($buffer1[3])){
$swap = $buffer1[3];
$data .= "<td class='text-center' style='position: relative; right: 55px;'><b>$swap</b></td>";
}
else{$swap = "Error";}

//Mail Queu
if (!empty($buffer1[5])){
$mailQueue = $buffer1[5];
$data .= "<td style='position: relative; right: 20px;'><b>". $mailQueue . "</b></td>";
}
else{$mailQueue = "Error";}
//Delivered mail
if (!empty($buffer1[9])){
$deliMail = $buffer1[9];
$data .= "<td><b>$deliMail</b></td>";
}
else{$deliMail = "Error";}
//free memory
$data .= "<td><b>". $buffer1[11] . "</b></td>";
//CPU usage
$data .= "<td><b>".  $buffer1[13] . "</b></td></tr>";
//close table
//$data .= "</table></small>";
//$data .= '
//  </div>
//</div>
//';

//Toggle Row
//$data .= "<tr class='child-2479' style='display: none;'>" ."<td> <label for='username'><b>Backup Search :</b></label><input type='text' id='tags' onkeyup='showResult(this.value)' class='border-primary rounded' placeholder='type username...'></td></tr>";
//echo $data;
$data .= "<tr class='child-2801' style='display: none;'>" ."<td><form  action='search.php' method='GET' name='E1' onsubmit='return sanitize()' > <label for='username'><b>Backup Search :</b></label>
<input type='text' name='query' id='userinput' onfocus='removeSpace()' class='border-primary rounded' placeholder='type username...' /><input type='submit' value='Search' />
<h6>ns3.doveserver.com IP: 67.220.184.130 <br> ns4.doveserver.com IP: 67.220.184.131 </h6>
<input type='hidden' name='server' value='E1'></form></td></tr>";
echo $data;

////////////////////////////////////////////EMERALD2 //////////////////////////
 //Reset and initialiaze variables
$timeout =1;$file1 = "";$buffer1 = "";$data="";$fp="";$errno="";$errstr="";$timeout="";$load="";$loadColor="";$availableBackupDates="";$disk_space="";$disk_free="";$disk_used_precent="";$disk_used_precent_color="";$backupValue="";$backupValueColor="";$bootValue="";$bootValueColor="";$swap ="";$mailQueue="";$deliMail=""; $homeValueColor="";$homeValue="";$httpStatus="";$mysqlStatus="";$varValue="";$varValueColor="";
 
//Get the shell output file from "monitorData.txt"
 $file1 = file_get_contents('/home/ogbonnan/public_html/monitor/data/E2monitorData.txt', true);
$buffer1 = preg_split("/:/", $file1, -1, PREG_SPLIT_NO_EMPTY);
$data .= "<small><table  class='table table-condensed'  style='border-collapse:collapse;'  >";
//Server name
  // Data toggle
$data .="<tr  class='parent' id='2802'><td ><span class='btn btn-default'><b>E2</b></span></td>";

//HTTP Status
$httpStatus = stream_socket_client("67.220.187.98:80", $errno, $errstr, 30);
	if (!$httpStatus) {
		$data .= "<td><b>Offline</b></td>";
	  fclose($httpStatus);
	} else {
		$data .= "<td><b>Online</b></td>";
		fclose($httpStatus);
	}
//MySQL status
// Find the correct MySql port number.
$mysqlStatus  = stream_socket_client("67.220.187.98:3306", $errno, $errstr, 30);

	if (!$mysqlStatus) {
		$data .= "<td style='color:red;'><b>Offline</b></td>";
	  fclose($mysqlStatus);
	} else {
		$data .= "<td><b>Online</b></td>";
		fclose($mysqlStatus);
	}
//SERVER LOAD
$load = substr($buffer1[15], 0, 4);
if($load >= 20){
$loadColor = 'red';
}
$data .= "<td style='color: $loadColor;'><b>$load</b></td>";
//Available backups Dates
$availableBackupDates =availableBackupDates();
$data .= "<td><b>$availableBackupDates</b></td>";
//Home Diskspace used
$homeValue = preg_replace('/[^0-9]/', '', $buffer1[19]); // remove the percentage from the value;
if ($homeValue >= 75 ){
    $homeValueColor = 'red';
}
$data .= "<td style='position: relative; right: 80px; style='color: $homeValueColor;'><b>".$homeValue."%</b></td>";


//Backup Disk Space used
$backupValue = preg_replace('/[^0-9]/', '', $buffer1[1]); // remove the percentage from the value;
if ($backupValue >= 90 ){
    $backupValueColor = 'red';
}
$data .= "<td style='color: $backupValueColor; position: relative; right: 85px;'><b>".$backupValue ."%</b></td>";
$backupValue = "";
//Boot partition used
$bootValue = preg_replace('/[^0-9]/', '', $buffer1[17]); // remove the percentage from the value;
if ($bootValue >= 90 ){
    $bootValueColor = 'red';
}
$data .= "<td class='text-center' style='color: $bootValueColor; position: relative; right: 110px;'><b>".$bootValue . "%</b></td>";

//Var Partition used
$varValue = preg_replace('/[^0-9]/', '', $buffer1[21]); // remove the percentage from the value;
if ($varValue >= 90 ){
    $varValueColor = 'red';
}
$data .= "<td class='text-center' style='color: $varValueColor; position: relative; right: 85px;'><b>".$varValue . "%</b></td>";

//Swap Diskspace used
if (!empty($buffer1[3])){
$swap = $buffer1[3];
$data .= "<td class='text-center' style='position: relative; right: 55px;'><b>$swap</b></td>";
}
else{$swap = "Error";}

//Mail Queu
if (!empty($buffer1[5])){
$mailQueue = $buffer1[5];
$data .= "<td style='position: relative; right: 20px;'><b>". $mailQueue . "</b></td>";
}
else{$mailQueue = "Error";}
//Delivered mail
if (!empty($buffer1[9])){
$deliMail = $buffer1[9];
$data .= "<td><b>$deliMail</b></td>";
}
else{$deliMail = "Error";}
//free memory
$data .= "<td><b>". $buffer1[11] . "</b></td>";
//CPU usage
$data .= "<td><b>".  $buffer1[13] . "</b></td>";
//close table
//$data .= "</table></small>";
//$data .= '
//  </div>
//</div>
//';

//Toggle Row
$data .= "</tr>";
$data .= "<tr class='child-2802' style='display: none;'>" ."<td><form action='search.php' method='GET' name='E2' onsubmit='return sanitize()' > <label for='username'><b>Backup Search :</b></label>
<input type='text' name='query' id='userinput' onfocus='removeSpace()' class='border-primary rounded' placeholder='type username...' /><input type='submit' value='Search' />
<h6>ns19.doveserver.com IP: 67.220.187.98 <br> ns20.doveserver.com IP: 67.220.187.99 </h6>
<input type='hidden' name='server' value='E2'></form></td></tr>";

echo $data;

////////////////////////////////EMERALD3///////////////////////////////
 //Reset and initialiaze variables
$timeout =1;$file1 = "";$buffer1 = "";$data="";$fp="";$errno="";$errstr="";$timeout="";$load="";$loadColor="";$availableBackupDates="";$disk_space="";$disk_free="";$disk_used_precent="";$disk_used_precent_color="";$backupValue="";$backupValueColor="";$bootValue="";$bootValueColor="";$swap ="";$mailQueue="";$deliMail=""; $homeValueColor="";$homeValue="";$httpStatus="";$mysqlStatus="";$varValue="";$varValueColor="";
 
//Get the shell output file from "monitorData.txt"
 $file1 = file_get_contents('/home/ogbonnan/public_html/monitor/data/E3monitorData.txt', true);
$buffer1 = preg_split("/:/", $file1, -1, PREG_SPLIT_NO_EMPTY);
$data .= "<small><table  class='table'>";
//Server name
//Datatoggle
$data .="<tr  class='parent' id='2803'><td ><span class='btn btn-default'><b>E3</b></span></td>";
//HTTP status
$httpStatus = stream_socket_client("67.220.184.242:80", $errno, $errstr, 30);
	if (!$httpStatus) {
		$data .= "<td><b>Offline</b></td>";
	  fclose($httpStatus);
	} else {
		$data .= "<td><b>Online</b></td>";
		fclose($httpStatus);
	}
//MySQL status
// Find the correct MySql port number.
$mysqlStatus = stream_socket_client("67.220.184.242:3306", $errno, $errstr, 30);

	if (!$mysqlStatus) {
		$data .= "<td style='color:red;'><b>Offline</b></td>";
	  fclose($mysqlStatus);
	} else {
		$data .= "<td><b>Online</b></td>";
		fclose($mysqlStatus);
	}
//SERVER LOAD
$load = substr($buffer1[15], 0, 4);
if($load >= 20){
$loadColor = 'red';
}
$data .= "<td style='color: $loadColor;'><b>$load</b></td>";
//Available backups Dates
$availableBackupDates =availableBackupDates();
$data .= "<td><b>$availableBackupDates</b></td>";
//Home Diskspace used
$homeValue = preg_replace('/[^0-9]/', '', $buffer1[19]); // remove the percentage from the value;
if ($homeValue >= 75 ){
    $homeValueColor = 'red';
}
$data .= "<td style='position: relative; right: 80px; style='color: $homeValueColor;'><b>".$homeValue."%</b></td>";


//Backup Disk Space used
$backupValue = preg_replace('/[^0-9]/', '', $buffer1[1]); // remove the percentage from the value;
if ($backupValue >= 90 ){
    $backupValueColor = 'red';
}
$data .= "<td style='color: $backupValueColor; position: relative; right: 85px;'><b>".$backupValue ."%</b></td>";
$backupValue = "";
//Boot partition used
$bootValue = preg_replace('/[^0-9]/', '', $buffer1[17]); // remove the percentage from the value;
if ($bootValue >= 90 ){
    $bootValueColor = 'red';
}
$data .= "<td class='text-center' style='color: $bootValueColor; position: relative; right: 110px;'><b>".$bootValue . "%</b></td>";

//Var Partition used
$varValue = preg_replace('/[^0-9]/', '', $buffer1[21]); // remove the percentage from the value;
if ($varValue >= 90 ){
    $varValueColor = 'red';
}
$data .= "<td class='text-center' style='color: $varValueColor; position: relative; right: 85px;'><b>".$varValue . "%</b></td>";

//Swap Diskspace used
if (!empty($buffer1[3])){
$swap = $buffer1[3];
$data .= "<td class='text-center' style='position: relative; right: 55px;'><b>$swap</b></td>";
}
else{$swap = "Error";}

//Mail Queu
if (!empty($buffer1[5])){
$mailQueue = $buffer1[5];
$data .= "<td style='position: relative; right: 20px;'><b>". $mailQueue . "</b></td>";
}
else{$mailQueue = "Error";}
//Delivered mail
if (!empty($buffer1[9])){
$deliMail = $buffer1[9];
$data .= "<td><b>$deliMail</b></td>";
}
else{$deliMail = "Error";}
//free memory
$data .= "<td><b>". $buffer1[11] . "</b></td>";
//CPU usage
$data .= "<td><b>".  $buffer1[13] . "</b></td>";

//echo $data;

$data .= "</tr>";
$data .= "<tr class='child-2803' style='display: none;'>" ."<td><form action='search.php' method='GET' name='E3' > <label for='username'><b>Backup Search :</b></label>
<input type='text' name='query' id='userinput' onfocus='removeSpace()' class='border-primary rounded' placeholder='type username...' /><input type='submit' value='Search' />
<h6>ns27.doveserver.com  IP: 67.220.184.242 <br> ns28.doveserver.com IP: 67.220.184.243 </h6>
<input type='hidden' name='server' value='E3'></form></td></tr>";

echo $data;

/////////////////////// EMERALD4 ////////////////////////////////////////
 //Reset and initialiaze variables
$timeout =1;$file1 = "";$buffer1 = "";$data="";$fp="";$errno="";$errstr="";$timeout="";$load="";$loadColor="";$availableBackupDates="";$disk_space="";$disk_free="";$disk_used_precent="";$disk_used_precent_color="";$backupValue="";$backupValueColor="";$bootValue="";$bootValueColor="";$swap ="";$mailQueue="";$deliMail=""; $homeValueColor="";$homeValue="";$httpStatus="";$mysqlStatus="";$varValue="";$varValueColor="";
 
//Get the shell output file from "monitorData.txt"
 $file1 = file_get_contents('/home/ogbonnan/public_html/monitor/data/E4monitorData.txt', true);
$buffer1 = preg_split("/:/", $file1, -1, PREG_SPLIT_NO_EMPTY);
$data .= "<small><table  class='table'>";
//Server name
$data .="<tr  class='parent' id='2804'><td ><span class='btn btn-default'><b>E4</b></span></td>";//HTTP status
$httpStatus = stream_socket_client("23.227.135.226:80", $errno, $errstr, 30);
	if (!$httpStatus) {
		$data .= "<td><b>Offline</b></td>";
	  fclose($httpStatus);
	} else {
		$data .= "<td><b>Online</b></td>";
		fclose($httpStatus);
	}
//MySQL status
// Find the correct MySql port number.
$mysqlStatus = stream_socket_client("23.227.135.226:3306", $errno, $errstr, 30);

	if (!$mysqlStatus) {
		$data .= "<td style='color:red;'><b>Offline</b></td>";
	  fclose($mysqlStatus);
	} else {
		$data .= "<td><b>Online</b></td>";
		fclose($mysqlStatus);
	}
//SERVER LOAD
$load = substr($buffer1[15], 0, 4);
if($load >= 20){
$loadColor = 'red';
}
$data .= "<td style='color: $loadColor;'><b>$load</b></td>";
//Available backups Dates
$availableBackupDates =availableBackupDates();
$data .= "<td><b>$availableBackupDates</b></td>";
//Home Diskspace used
$homeValue = preg_replace('/[^0-9]/', '', $buffer1[19]); // remove the percentage from the value;
if ($homeValue >= 75 ){
    $homeValueColor = 'red';
}
$data .= "<td style='position: relative; right: 80px; style='color: $homeValueColor;'><b>".$homeValue."%</b></td>";


//Backup Disk Space used
$backupValue = preg_replace('/[^0-9]/', '', $buffer1[1]); // remove the percentage from the value;
if ($backupValue >= 90 ){
    $backupValueColor = 'red';
}
$data .= "<td style='color: $backupValueColor; position: relative; right: 85px;'><b>".$backupValue ."%</b></td>";
$backupValue = "";
//Boot partition used
$bootValue = preg_replace('/[^0-9]/', '', $buffer1[17]); // remove the percentage from the value;
if ($bootValue >= 90 ){
    $bootValueColor = 'red';
}
$data .= "<td class='text-center' style='color: $bootValueColor; position: relative; right: 110px;'><b>".$bootValue . "%</b></td>";

//Var Partition used
$varValue = preg_replace('/[^0-9]/', '', $buffer1[21]); // remove the percentage from the value;
if ($varValue >= 90 ){
    $varValueColor = 'red';
}
$data .= "<td class='text-center' style='color: $varValueColor; position: relative; right: 85px;'><b>".$varValue . "%</b></td>";

//Swap Diskspace used
if (!empty($buffer1[3])){
$swap = $buffer1[3];
$data .= "<td class='text-center' style='position: relative; right: 55px;'><b>$swap</b></td>";
}
else{$swap = "Error";}

//Mail Queu
if (!empty($buffer1[5])){
$mailQueue = $buffer1[5];
$data .= "<td style='position: relative; right: 20px;'><b>". $mailQueue . "</b></td>";
}
else{$mailQueue = "Error";}
//Delivered mail
if (!empty($buffer1[9])){
$deliMail = $buffer1[9];
$data .= "<td><b>$deliMail</b></td>";
}
else{$deliMail = "Error";}
//free memory
$data .= "<td><b>". $buffer1[11] . "</b></td>";
//CPU usage
$data .= "<td><b>".  $buffer1[13] . "</b></td>";
//close table
//$data .= "</table></small>";
//$data .= '
//  </div>
//</div>
//';

$data .= "<tr class='child-2804' style='display: none;'>" ."<td><form  action='search.php' method='GET' name='E4' onsubmit='return sanitize()' > <label for='username'><b>Backup Search :</b></label>
<input type='text' name='query' id='userinput' onfocus='removeSpace()' class='border-primary rounded' placeholder='type username...' /><input type='submit' value='Search' />
<h6>ns23.smartwebng.com  IP: 23.227.135.226 <br> ns24.smartwebng.com IP: 23.227.135.227 </h6>
<input type='hidden' name='server' value='E4'></form></td></tr>";
echo $data;

////////////////////////// EMERALD5 /////////////////////////////////////
 //Reset and initialiaze variables
$timeout =1;$file1 = "";$buffer1 = "";$data="";$fp="";$errno="";$errstr="";$timeout="";$load="";$loadColor="";$availableBackupDates="";$disk_space="";$disk_free="";$disk_used_precent="";$disk_used_precent_color="";$backupValue="";$backupValueColor="";$bootValue="";$bootValueColor="";$swap ="";$mailQueue="";$deliMail=""; $homeValueColor="";$homeValue="";$httpStatus="";$mysqlStatus="";$varValue="";$varValueColor="";
 
//Get the shell output file from "monitorData.txt"
 $file1 = file_get_contents('/home/ogbonnan/public_html/monitor/data/E5monitorData.txt', true);
$buffer1 = preg_split("/:/", $file1, -1, PREG_SPLIT_NO_EMPTY);
$data .= "<small><table  class='table'>";
//Server name
//Data toggle
$data .="<tr  class='parent' id='2805'><td ><span class='btn btn-default'><b>E5</b></span></td>";//HTTP status
$httpStatus = stream_socket_client("209.205.209.18:80", $errno, $errstr, 30);
	if (!$httpStatus) {
		$data .= "<td><b>Offline</b></td>";
	  fclose($httpStatus);
	} else {
		$data .= "<td><b>Online</b></td>";
		fclose($httpStatus);
	}
//MySQL status
// Find the correct MySql port number.
$mysqlStatus = stream_socket_client("209.205.209.18:3306", $errno, $errstr, 30);

	if (!$mysqlStatus) {
		$data .= "<td style='color:red;'><b>Offline</b></td>";
	  fclose($mysqlStatus);
	} else {
		$data .= "<td><b>Online</b></td>";
		fclose($mysqlStatus);
	}
//SERVER LOAD
$load = substr($buffer1[15], 0, 4);
if($load >= 20){
$loadColor = 'red';
}
$data .= "<td style='color: $loadColor;'><b>$load</b></td>";
//Available backups Dates
$availableBackupDates =availableBackupDates();
$data .= "<td><b>$availableBackupDates</b></td>";
//Home Diskspace used
$homeValue = preg_replace('/[^0-9]/', '', $buffer1[19]); // remove the percentage from the value;
if ($homeValue >= 75 ){
    $homeValueColor = 'red';
}
$data .= "<td style='position: relative; right: 80px; style='color: $homeValueColor;'><b>".$homeValue."%</b></td>";


//Backup Disk Space used
$backupValue = preg_replace('/[^0-9]/', '', $buffer1[1]); // remove the percentage from the value;
if ($backupValue >= 90 ){
    $backupValueColor = 'red';
}
$data .= "<td style='color: $backupValueColor; position: relative; right: 85px;'><b>".$backupValue ."%</b></td>";
$backupValue = "";
//Boot partition used
$bootValue = preg_replace('/[^0-9]/', '', $buffer1[17]); // remove the percentage from the value;
if ($bootValue >= 90 ){
    $bootValueColor = 'red';
}
$data .= "<td class='text-center' style='color: $bootValueColor; position: relative; right: 110px;'><b>".$bootValue . "%</b></td>";

//Var Partition used
$varValue = preg_replace('/[^0-9]/', '', $buffer1[21]); // remove the percentage from the value;
if ($varValue >= 90 ){
    $varValueColor = 'red';
}
$data .= "<td class='text-center' style='color: $varValueColor; position: relative; right: 85px;'><b>".$varValue . "%</b></td>";

//Swap Diskspace used
if (!empty($buffer1[3])){
$swap = $buffer1[3];
$data .= "<td class='text-center' style='position: relative; right: 55px;'><b>$swap</b></td>";
}
else{$swap = "Error";}

//Mail Queu
if (!empty($buffer1[5])){
$mailQueue = $buffer1[5];
$data .= "<td style='position: relative; right: 20px;'><b>". $mailQueue . "</b></td>";
}
else{$mailQueue = "Error";}
//Delivered mail
if (!empty($buffer1[9])){
$deliMail = $buffer1[9];
$data .= "<td><b>$deliMail</b></td>";
}
else{$deliMail = "Error";}
//free memory
$data .= "<td><b>". $buffer1[11] . "</b></td>";
//CPU usage
$data .= "<td><b>".  $buffer1[13] . "</b></td></tr>";
//close table
//$data .= "</table></small>";
//$data .= '
//  </div>
//</div>
//';

$data .= "<tr class='child-2805' style='display: none;'>" ."<td><form  action='search.php' method='GET' name='E5' onsubmit='return sanitize()' > <label for='username'><b>Backup Search :</b></label>
<input type='text' name='query' id='userinput' onfocus='removeSpace()' class='border-primary rounded' placeholder='type username...' /><input type='submit' value='Search' />
<h6>ns33.doveserver.com  IP: 209.205.209.18 <br> ns34.doveserver.com IP: 209.205.209.19 </h6>
<input type='hidden' name='server' value='E5'></form></td></tr>";
echo $data;

/////////////////////// EMERALD6 ////////////////////////////////////////
 //Reset and initialiaze variables
$timeout =1;$file1 = "";$buffer1 = "";$data="";$fp="";$errno="";$errstr="";$timeout="";$load="";$loadColor="";$availableBackupDates="";$disk_space="";$disk_free="";$disk_used_precent="";$disk_used_precent_color="";$backupValue="";$backupValueColor="";$bootValue="";$bootValueColor="";$swap ="";$mailQueue="";$deliMail=""; $homeValueColor="";$homeValue="";$httpStatus="";$mysqlStatus="";$varValue="";$varValueColor="";
 
//Get the shell output file from "monitorData.txt"
 $file1 = file_get_contents('/home/ogbonnan/public_html/monitor/data/E6monitorData.txt', true);
$buffer1 = preg_split("/:/", $file1, -1, PREG_SPLIT_NO_EMPTY);
$data .= "<small><table  class='table'>";
//Server name
//Data toggle
$data .="<tr  class='parent' id='2806'><td ><span class='btn btn-default'><b>E6</b></span></td>";
//HTTP status
$httpStatus = stream_socket_client("209.205.211.242:80", $errno, $errstr, 30);
	if (!$httpStatus) {
		$data .= "<td><b>Offline</b></td>";
	  fclose($httpStatus);
	} else {
		$data .= "<td><b>Online</b></td>";
		fclose($httpStatus);
	}
//MySQL status
// Find the correct MySql port number.
$mysqlStatus = stream_socket_client("209.205.211.242:3306", $errno, $errstr, 30);

	if (!$mysqlStatus) {
		$data .= "<td style='color:red;'><b>Offline</b></td>";
	  fclose($mysqlStatus);
	} else {
		$data .= "<td><b>Online</b></td>";
		fclose($mysqlStatus);
	}
//SERVER LOAD
$load = substr($buffer1[15], 0, 4);
if($load >= 20){
$loadColor = 'red';
}
$data .= "<td style='color: $loadColor;'><b>$load</b></td>";
//Available backups Dates
$availableBackupDates =availableBackupDates();
$data .= "<td><b>$availableBackupDates</b></td>";
//Home Diskspace used
$homeValue = preg_replace('/[^0-9]/', '', $buffer1[19]); // remove the percentage from the value;
if ($homeValue >= 75 ){
    $homeValueColor = 'red';
}
$data .= "<td style='position: relative; right: 80px; style='color: $homeValueColor;'><b>".$homeValue."%</b></td>";


//Backup Disk Space used
$backupValue = preg_replace('/[^0-9]/', '', $buffer1[1]); // remove the percentage from the value;
if ($backupValue >= 90 ){
    $backupValueColor = 'red';
}
$data .= "<td style='color: $backupValueColor; position: relative; right: 85px;'><b>".$backupValue ."%</b></td>";
$backupValue = "";
//Boot partition used
$bootValue = preg_replace('/[^0-9]/', '', $buffer1[17]); // remove the percentage from the value;
if ($bootValue >= 90 ){
    $bootValueColor = 'red';
}
$data .= "<td class='text-center' style='color: $bootValueColor; position: relative; right: 110px;'><b>".$bootValue . "%</b></td>";

//Var Partition used
$varValue = preg_replace('/[^0-9]/', '', $buffer1[21]); // remove the percentage from the value;
if ($varValue >= 90 ){
    $varValueColor = 'red';
}
$data .= "<td class='text-center' style='color: $varValueColor; position: relative; right: 85px;'><b>".$varValue . "%</b></td>";

//Swap Diskspace used
if (!empty($buffer1[3])){
$swap = $buffer1[3];
$data .= "<td class='text-center' style='position: relative; right: 55px;'><b>$swap</b></td>";
}
else{$swap = "Error";}

//Mail Queu
if (!empty($buffer1[5])){
$mailQueue = $buffer1[5];
$data .= "<td style='position: relative; right: 20px;'><b>". $mailQueue . "</b></td>";
}
else{$mailQueue = "Error";}
//Delivered mail
if (!empty($buffer1[9])){
$deliMail = $buffer1[9];
$data .= "<td><b>$deliMail</b></td>";
}
else{$deliMail = "Error";}
//free memory
$data .= "<td><b>". $buffer1[11] . "</b></td>";
//CPU usage
$data .= "<td><b>".  $buffer1[13] . "</b></td></td>";
//close table
//$data .= "</table></small>";
//$data .= '
//  </div>
//</div>
//';
$data .= "<tr class='child-2806' style='display: none;'>" ."<td><form  action='search.php' method='GET' name='E6' onsubmit='return sanitize()' > <label for='username'><b>Backup Search :</b></label>
<input type='text' name='query' id='userinput' onfocus='removeSpace()' class='border-primary rounded' placeholder='type username...' /><input type='submit' value='Search' />
<h6>ns35.doveserver.com  IP: 209.205.211.242 <br> ns36.doveserver.com IP: 209.205.211.243 </h6>
<input type='hidden' name='server' value='E6'></form></td></tr>";
echo $data;
//////////////////////////// EMERALD7 //////////////////////////////////
 //Reset and initialiaze variables
$timeout =1;$file1 = "";$buffer1 = "";$data="";$fp="";$errno="";$errstr="";$timeout="";$load="";$loadColor="";$availableBackupDates="";$disk_space="";$disk_free="";$disk_used_precent="";$disk_used_precent_color="";$backupValue="";$backupValueColor="";$bootValue="";$bootValueColor="";$swap ="";$mailQueue="";$deliMail=""; $homeValueColor="";$homeValue="";$httpStatus="";$mysqlStatus="";$varValue="";$varValueColor="";
 
//Get the shell output file from "monitorData.txt"
 $file1 = file_get_contents('/home/ogbonnan/public_html/monitor/data/E7monitorData.txt', true);
$buffer1 = preg_split("/:/", $file1, -1, PREG_SPLIT_NO_EMPTY);
$data .= "<small><table  class='table'>";
//Server name
//Data toggle
$data .="<tr  class='parent' id='2807'><td ><span class='btn btn-default'><b>E7</b></span></td>";
//HTTP status
$httpStatus = stream_socket_client("209.205.208.10:80", $errno, $errstr, 30);
	if (!$httpStatus) {
		$data .= "<td><b>Offline</b></td>";
	  fclose($httpStatus);
	} else {
		$data .= "<td><b>Online</b></td>";
		fclose($httpStatus);
	}
//MySQL status
// Find the correct MySql port number.
$mysqlStatus = stream_socket_client("209.205.208.10:3306", $errno, $errstr, 30);

	if (!$mysqlStatus) {
		$data .= "<td style='color:red;'><b>Offline</b></td>";
	  fclose($mysqlStatus);
	} else {
		$data .= "<td><b>Online</b></td>";
		fclose($mysqlStatus);
	}
//SERVER LOAD
$load = substr($buffer1[15], 0, 4);
if($load >= 20){
$loadColor = 'red';
}
$data .= "<td style='color: $loadColor;'><b>$load</b></td>";
//Available backups Dates
$availableBackupDates =availableBackupDates();
$data .= "<td><b>$availableBackupDates</b></td>";
//Home Diskspace used
$homeValue = preg_replace('/[^0-9]/', '', $buffer1[19]); // remove the percentage from the value;
if ($homeValue >= 75 ){
    $homeValueColor = 'red';
}
$data .= "<td style='position: relative; right: 80px; style='color: $homeValueColor;'><b>".$homeValue."%</b></td>";


//Backup Disk Space used
$backupValue = preg_replace('/[^0-9]/', '', $buffer1[1]); // remove the percentage from the value;
if ($backupValue >= 90 ){
    $backupValueColor = 'red';
}
$data .= "<td style='color: $backupValueColor; position: relative; right: 85px;'><b>".$backupValue ."%</b></td>";
$backupValue = "";
//Boot partition used
$bootValue = preg_replace('/[^0-9]/', '', $buffer1[17]); // remove the percentage from the value;
if ($bootValue >= 90 ){
    $bootValueColor = 'red';
}
$data .= "<td class='text-center' style='color: $bootValueColor; position: relative; right: 110px;'><b>".$bootValue . "%</b></td>";

//Var Partition used
$varValue = preg_replace('/[^0-9]/', '', $buffer1[21]); // remove the percentage from the value;
if ($varValue >= 90 ){
    $varValueColor = 'red';
}
$data .= "<td class='text-center' style='color: $varValueColor; position: relative; right: 85px;'><b>".$varValue . "%</b></td>";

//Swap Diskspace used
if (!empty($buffer1[3])){
$swap = $buffer1[3];
$data .= "<td class='text-center' style='position: relative; right: 55px;'><b>$swap</b></td>";
}
else{$swap = "Error";}

//Mail Queu
if (!empty($buffer1[5])){
$mailQueue = $buffer1[5];
$data .= "<td style='position: relative; right: 20px;'><b>". $mailQueue . "</b></td>";
}
else{$mailQueue = "Error";}
//Delivered mail
if (!empty($buffer1[9])){
$deliMail = $buffer1[9];
$data .= "<td><b>$deliMail</b></td>";
}
else{$deliMail = "Error";}
//free memory
$data .= "<td><b>". $buffer1[11] . "</b></td>";
//CPU usage
$data .= "<td><b>".  $buffer1[13] . "</b></td></tr>";
//close table
//$data .= "</table></small>";
//$data .= '
//  </div>
//</div>
//';
$data .= "<tr class='child-2807' style='display: none;'>" ."<td><form  action='search.php' method='GET' name='E7' onsubmit='return sanitize()' > <label for='username'><b>Backup Search :</b></label>
<input type='text' name='query' id='userinput' onfocus='removeSpace()' class='border-primary rounded' placeholder='type username...' /><input type='submit' value='Search' />
<h6>ns37.doveserver.com  IP:209.205.208.10 <br> ns38.doveserver.com IP: 209.205.208.11 </h6>
<input type='hidden' name='server' value='E7'></form></td></tr>";
echo $data;

//////////////////////////// EMERALD8 //////////////////////////////////
 //Reset and initialiaze variables
$timeout =1;$file1 = "";$buffer1 = "";$data="";$fp="";$errno="";$errstr="";$timeout="";$load="";$loadColor="";$availableBackupDates="";$disk_space="";$disk_free="";$disk_used_precent="";$disk_used_precent_color="";$backupValue="";$backupValueColor="";$bootValue="";$bootValueColor="";$swap ="";$mailQueue="";$deliMail=""; $homeValueColor="";$homeValue="";$httpStatus="";$mysqlStatus="";$varValue="";$varValueColor="";
 
//Get the shell output file from "monitorData.txt"
 $file1 = file_get_contents('/home/ogbonnan/public_html/monitor/data/E8monitorData.txt', true);
$buffer1 = preg_split("/:/", $file1, -1, PREG_SPLIT_NO_EMPTY);
$data .= "<small><table  class='table'>";
//Server name
//Data toggle
$data .="<tr  class='parent' id='2828'><td ><span class='btn btn-default'><b>E8</b></span></td>";
//HTTP status
$httpStatus = stream_socket_client("23.227.137.226:80", $errno, $errstr, 30);
	if (!$httpStatus) {
		$data .= "<td title='Apache server'><b>Offline</b></td>";
	  fclose($httpStatus);
	} else {
		$data .= "<td title='Apache server'><b>Online</b></td>";
		fclose($httpStatus);
	}
//MySQL status
// Find the correct MySql port number.
$mysqlStatus = stream_socket_client("23.227.137.226:3306", $errno, $errstr, 30);

	if (!$mysqlStatus) {
		$data .= "<td title='Database server' style='color:red;'><b>Offline</b></td>";
	  fclose($mysqlStatus);
	} else {
		$data .= "<td title='Database server'><b>Online</b></td>";
		fclose($mysqlStatus);
	}
//SERVER LOAD
$load = substr($buffer1[15], 0, 4);
if($load >= 20){
$loadColor = 'red';
}
$data .= "<td title='Server load' style='color: $loadColor;'><b>$load</b></td>";
//Available backups Dates
$availableBackupDates =availableBackupDates();
$data .= "<td title='Available Backup'><b>$availableBackupDates</b></td>";
//Home Diskspace used
$homeValue = preg_replace('/[^0-9]/', '', $buffer1[19]); // remove the percentage from the value;
if ($homeValue >= 75 ){
    $homeValueColor = 'red';
}
$data .= "<td title='Home Disk-space' style='position: relative; right: 80px; style='color: $homeValueColor;'><b>".$homeValue."%</b></td>";


//Backup Disk Space used
$backupValue = preg_replace('/[^0-9]/', '', $buffer1[1]); // remove the percentage from the value;
if ($backupValue >= 90 ){
    $backupValueColor = 'red';
}
$data .= "<td title='Backup Disk-space' style='color: $backupValueColor; position: relative; right: 85px;'><b>".$backupValue ."%</b></td>";
$backupValue = "";
//Boot partition used
$bootValue = preg_replace('/[^0-9]/', '', $buffer1[17]); // remove the percentage from the value;
if ($bootValue >= 90 ){
    $bootValueColor = 'red';
}
$data .= "<td title='Boot Diskspace' class='text-center' style='color: $bootValueColor; position: relative; right: 110px;'><b>".$bootValue . "%</b></td>";

//Var Partition used
$varValue = preg_replace('/[^0-9]/', '', $buffer1[21]); // remove the percentage from the value;
if ($varValue >= 90 ){
    $varValueColor = 'red';
}
$data .= "<td title='Var Disk-space' class='text-center' style='color: $varValueColor; position: relative; right: 85px;'><b>".$varValue . "%</b></td>";

//Swap Diskspace used
if (!empty($buffer1[3])){
$swap = $buffer1[3];
$data .= "<td title='Swap Disk-space' class='text-center' style='position: relative; right: 55px;'><b>$swap</b></td>";
}
else{$swap = "Error";}

//Mail Queu
if (!empty($buffer1[5])){
$mailQueue = $buffer1[5];
$data .= "<td title='Mail queue' style='position: relative; right: 20px;'><b>". $mailQueue . "</b></td>";
}
else{$mailQueue = "Error";}
//Delivered mail
if (!empty($buffer1[9])){
$deliMail = $buffer1[9];
$data .= "<td title='Delivered mail in past 24h'><b>$deliMail</b></td>";
}
else{$deliMail = "Error";}
//free memory
$data .= "<td title='RAM Space'><b>". $buffer1[11] . "</b></td>";
//CPU usage
$data .= "<td title='Used CPU'><b>".  $buffer1[13] . "</b></td></tr>";
//close table
//$data .= "</table></small>";
//$data .= '
//  </div>
//</div>
//';
$data .= "<tr class='child-2828' style='display: none;'>" ."<td><form  action='search.php' method='GET' name='E8' onsubmit='return sanitize()' > <label for='username'><b>Backup Search :</b></label>
<input type='text' name='query' id='userinput' onfocus='removeSpace()' class='border-primary rounded' placeholder='type username...' /><input type='submit' value='Search' />
<h6>ns45.doveserver.com  IP: 23.227.137.226 <br> ns46.doveserver.com IP: 23.227.137.227 </h6>
<input type='hidden' name='server' value='E8'></form></td></tr>";
echo $data;



////////////////////// STANDARD1 //////////////////////////////////////
 //Reset and initialiaze variables
$timeout =1;$file1 = "";$buffer1 = "";$data="";$fp="";$errno="";$errstr="";$timeout="";$load="";$loadColor="";$availableBackupDates="";$disk_space="";$disk_free="";$disk_used_precent="";$disk_used_precent_color="";$backupValue="";$backupValueColor="";$bootValue="";$bootValueColor="";$swap ="";$mailQueue="";$deliMail=""; $homeValueColor="";$homeValue="";$httpStatus="";$mysqlStatus="";$varValue="";$varValueColor="";
 
//Get the shell output file from "monitorData.txt"
 $file1 = file_get_contents('/home/ogbonnan/public_html/monitor/data/D1monitorData.txt', true);
$buffer1 = preg_split("/:/", $file1, -1, PREG_SPLIT_NO_EMPTY);
$data .= "<small><table  class='table'>";
//Server name
//Data toggle
$data .="<tr  class='parent' id='2808'><td ><span class='btn btn-default'><b>D1</b></span></td>";
//HTTP status
$httpStatus = stream_socket_client("67.220.184.146:80", $errno, $errstr, 30);
	if (!$httpStatus) {
		$data .= "<td><b>Offline</b></td>";
	  fclose($httpStatus);
	} else {
		$data .= "<td><b>Online</b></td>";
		fclose($httpStatus);
	}
//MySQL status
// Find the correct MySql port number.
$mysqlStatus = stream_socket_client("67.220.184.146:3306", $errno, $errstr, 30);

	if (!$mysqlStatus) {
		$data .= "<td style='color:red;'><b>Offline</b></td>";
	  fclose($mysqlStatus);
	} else {
		$data .= "<td><b>Online</b></td>";
		fclose($mysqlStatus);
	}
//SERVER LOAD
$load = substr($buffer1[15], 0, 4);
if($load >= 20){
$loadColor = 'red';
}
$data .= "<td style='color: $loadColor;'><b>$load</b></td>";
//Available backups Dates
$availableBackupDates =availableBackupDates();
$data .= "<td><b>$availableBackupDates</b></td>";
//Home Diskspace used
$homeValue = preg_replace('/[^0-9]/', '', $buffer1[19]); // remove the percentage from the value;
if ($homeValue >= 75 ){
    $homeValueColor = 'red';
}
$data .= "<td style='position: relative; right: 80px; style='color: $homeValueColor;'><b>".$homeValue."%</b></td>";


//Backup Disk Space used
$backupValue = preg_replace('/[^0-9]/', '', $buffer1[1]); // remove the percentage from the value;
if ($backupValue >= 90 ){
    $backupValueColor = 'red';
}
$data .= "<td style='color: $backupValueColor; position: relative; right: 85px;'><b>".$backupValue ."%</b></td>";
$backupValue = "";
//Boot partition used
$bootValue = preg_replace('/[^0-9]/', '', $buffer1[17]); // remove the percentage from the value;
if ($bootValue >= 90 ){
    $bootValueColor = 'red';
}
$data .= "<td class='text-center' style='color: $bootValueColor; position: relative; right: 110px;'><b>".$bootValue . "%</b></td>";

//Var Partition used
$varValue = preg_replace('/[^0-9]/', '', $buffer1[21]); // remove the percentage from the value;
if ($varValue >= 90 ){
    $varValueColor = 'red';
}
$data .= "<td class='text-center' style='color: $varValueColor; position: relative; right: 85px;'><b>".$varValue . "%</b></td>";

//Swap Diskspace used
if (!empty($buffer1[3])){
$swap = $buffer1[3];
$data .= "<td class='text-center' style='position: relative; right: 55px;'><b>$swap</b></td>";
}
else{$swap = "Error";}

//Mail Queu
if (!empty($buffer1[5])){
$mailQueue = $buffer1[5];
$data .= "<td style='position: relative; right: 20px;'><b>". $mailQueue . "</b></td>";
}
else{$mailQueue = "Error";}
//Delivered mail
if (!empty($buffer1[9])){
$deliMail = $buffer1[9];
$data .= "<td><b>$deliMail</b></td>";
}
else{$deliMail = "Error";}
//free memory
$data .= "<td><b>". $buffer1[11] . "</b></td>";
//CPU usage
$data .= "<td><b>".  $buffer1[13] . "</b></td></tr>";
//close table
//$data .= "</table></small>";
//$data .= '
//  </div>
//</div>
//';
$data .= "<tr class='child-2808' style='display: none;'>" ."<td><form  action='search.php' method='GET' name='D1' onsubmit='return sanitize()' > <label for='username'><b>Backup Search :</b></label>
<input type='text' name='query' id='userinput' onfocus='removeSpace()' class='border-primary rounded' placeholder='type username...' /><input type='submit' value='Search' />
<h6>ns5.doveserver.com  IP: 67.220.184.146 <br> ns6.doveserver.com IP: 67.220.184.147 </h6>
<input type='hidden' name='server' value='D1'></form></td></tr>";
echo $data;

///////////////////// STANDARD 2 //////////////////////////////////////
 //Reset and initialiaze variables
$timeout =1;$file1 = "";$buffer1 = "";$data="";$fp="";$errno="";$errstr="";$timeout="";$load="";$loadColor="";$availableBackupDates="";$disk_space="";$disk_free="";$disk_used_precent="";$disk_used_precent_color="";$backupValue="";$backupValueColor="";$bootValue="";$bootValueColor="";$swap ="";$mailQueue="";$deliMail=""; $homeValueColor="";$homeValue="";$httpStatus="";$mysqlStatus="";$varValue="";$varValueColor="";
 
//Get the shell output file from "monitorData.txt"
 $file1 = file_get_contents('/home/ogbonnan/public_html/monitor/data/D2monitorData.txt', true);
$buffer1 = preg_split("/:/", $file1, -1, PREG_SPLIT_NO_EMPTY);
$data .= "<small><table  class='table'>";
//Server name
//Data Toggle
$data .="<tr  class='parent' id='2809'><td ><span class='btn btn-default'><b>D2</b></span></td>";
//HTTP status
$httpStatus = stream_socket_client("209.205.201.162:80", $errno, $errstr, 30);
	if (!$httpStatus) {
		$data .= "<td><b>Offline</b></td>";
	  fclose($httpStatus);
	} else {
		$data .= "<td><b>Online</b></td>";
		fclose($httpStatus);
	}
//MySQL status
// Find the correct MySql port number.
$mysqlStatus = stream_socket_client("209.205.201.162:3306", $errno, $errstr, 30);

	if (!$mysqlStatus) {
		$data .= "<td style='color:red;'><b>Offline</b></td>";
	  fclose($mysqlStatus);
	} else {
		$data .= "<td><b>Online</b></td>";
		fclose($mysqlStatus);
	}
//SERVER LOAD
$load = substr($buffer1[15], 0, 4);
if($load >= 20){
$loadColor = 'red';
}
$data .= "<td style='color: $loadColor;'><b>$load</b></td>";
//Available backups Dates
$availableBackupDates =availableBackupDates();
$data .= "<td><b>$availableBackupDates</b></td>";
//Home Diskspace used
$homeValue = preg_replace('/[^0-9]/', '', $buffer1[19]); // remove the percentage from the value;
if ($homeValue >= 75 ){
    $homeValueColor = 'red';
}
$data .= "<td style='position: relative; right: 80px; style='color: $homeValueColor;'><b>".$homeValue."%</b></td>";


//Backup Disk Space used
$backupValue = preg_replace('/[^0-9]/', '', $buffer1[1]); // remove the percentage from the value;
if ($backupValue >= 90 ){
    $backupValueColor = 'red';
}
$data .= "<td style='color: $backupValueColor; position: relative; right: 85px;'><b>".$backupValue ."%</b></td>";
$backupValue = "";
//Boot partition used
$bootValue = preg_replace('/[^0-9]/', '', $buffer1[17]); // remove the percentage from the value;
if ($bootValue >= 90 ){
    $bootValueColor = 'red';
}
$data .= "<td class='text-center' style='color: $bootValueColor; position: relative; right: 110px;'><b>".$bootValue . "%</b></td>";

//Var Partition used
$varValue = preg_replace('/[^0-9]/', '', $buffer1[21]); // remove the percentage from the value;
if ($varValue >= 90 ){
    $varValueColor = 'red';
}
$data .= "<td class='text-center' style='color: $varValueColor; position: relative; right: 85px;'><b>".$varValue . "%</b></td>";

//Swap Diskspace used
if (!empty($buffer1[3])){
$swap = $buffer1[3];
$data .= "<td class='text-center' style='position: relative; right: 55px;'><b>$swap</b></td>";
}
else{$swap = "Error";}

//Mail Queu
if (!empty($buffer1[5])){
$mailQueue = $buffer1[5];
$data .= "<td style='position: relative; right: 20px;'><b>". $mailQueue . "</b></td>";
}
else{$mailQueue = "Error";}
//Delivered mail
if (!empty($buffer1[9])){
$deliMail = $buffer1[9];
$data .= "<td><b>$deliMail</b></td>";
}
else{$deliMail = "Error";}
//free memory
$data .= "<td><b>". $buffer1[11] . "</b></td>";
//CPU usage
$data .= "<td><b>".  $buffer1[13] . "</b></td></tr>";
//close table
//$data .= "</table></small>";
//$data .= '
//  </div>
//</div>
//';
$data .= "<tr class='child-2809' style='display: none;'>" ."<td><form  action='search.php' method='GET' name='D2' onsubmit='return sanitize()' > <label for='username'><b>Backup Search :</b></label>
<input type='text' name='query' id='userinput' onfocus='removeSpace()' class='border-primary rounded' placeholder='type username...' /><input type='submit' value='Search' />
<h6>ns7.doveserver.com  IP: 209.205.201.162 <br> ns8.doveserver.com IP: 209.205.201.163 </h6>
<input type='hidden' name='server' value='D2'></form></td></tr>";
echo $data;

/////////////////////// STANDARD 3 //////////////////////////////////////
 //Reset and initialiaze variables
$timeout =1;$file1 = "";$buffer1 = "";$data="";$fp="";$errno="";$errstr="";$timeout="";$load="";$loadColor="";$availableBackupDates="";$disk_space="";$disk_free="";$disk_used_precent="";$disk_used_precent_color="";$backupValue="";$backupValueColor="";$bootValue="";$bootValueColor="";$swap ="";$mailQueue="";$deliMail=""; $homeValueColor="";$homeValue="";$httpStatus="";$mysqlStatus="";$varValue="";$varValueColor="";
 
//Get the shell output file from "monitorData.txt"
 $file1 = file_get_contents('/home/ogbonnan/public_html/monitor/data/D3monitorData.txt', true);
$buffer1 = preg_split("/:/", $file1, -1, PREG_SPLIT_NO_EMPTY);
$data .= "<small><table  class='table'>";
//Server name
//Data Toggle
$data .="<tr  class='parent' id='2810'><td ><span class='btn btn-default'><b>D3</b></span></td>";
//HTTP status
$httpStatus = stream_socket_client("67.220.183.250:80", $errno, $errstr, 30);
	if (!$httpStatus) {
		$data .= "<td><b>Offline</b></td>";
	  fclose($httpStatus);
	} else {
		$data .= "<td><b>Online</b></td>";
		fclose($httpStatus);
	}
//MySQL status
// Find the correct MySql port number.
$mysqlStatus = stream_socket_client("67.220.183.250:3306", $errno, $errstr, 30);

	if (!$mysqlStatus) {
		$data .= "<td style='color:red;'><b>Offline</b></td>";
	  fclose($mysqlStatus);
	} else {
		$data .= "<td><b>Online</b></td>";
		fclose($mysqlStatus);
	}
//SERVER LOAD
$load = substr($buffer1[15], 0, 4);
if($load >= 20){
$loadColor = 'red';
}
$data .= "<td style='color: $loadColor;'><b>$load</b></td>";
//Available backups Dates
$availableBackupDates =availableBackupDates();
$data .= "<td><b>$availableBackupDates</b></td>";
//Home Diskspace used
$homeValue = preg_replace('/[^0-9]/', '', $buffer1[19]); // remove the percentage from the value;
if ($homeValue >= 75 ){
    $homeValueColor = 'red';
}
$data .= "<td style='position: relative; right: 80px; style='color: $homeValueColor;'><b>".$homeValue."%</b></td>";


//Backup Disk Space used
$backupValue = preg_replace('/[^0-9]/', '', $buffer1[1]); // remove the percentage from the value;
if ($backupValue >= 90 ){
    $backupValueColor = 'red';
}
$data .= "<td style='color: $backupValueColor; position: relative; right: 85px;'><b>".$backupValue ."%</b></td>";
$backupValue = "";
//Boot partition used
$bootValue = preg_replace('/[^0-9]/', '', $buffer1[17]); // remove the percentage from the value;
if ($bootValue >= 90 ){
    $bootValueColor = 'red';
}
$data .= "<td class='text-center' style='color: $bootValueColor; position: relative; right: 110px;'><b>".$bootValue . "%</b></td>";

//Var Partition used
$varValue = preg_replace('/[^0-9]/', '', $buffer1[21]); // remove the percentage from the value;
if ($varValue >= 90 ){
    $varValueColor = 'red';
}
$data .= "<td class='text-center' style='color: $varValueColor; position: relative; right: 85px;'><b>".$varValue . "%</b></td>";

//Swap Diskspace used
if (!empty($buffer1[3])){
$swap = $buffer1[3];
$data .= "<td class='text-center' style='position: relative; right: 55px;'><b>$swap</b></td>";
}
else{$swap = "Error";}

//Mail Queu
if (!empty($buffer1[5])){
$mailQueue = $buffer1[5];
$data .= "<td style='position: relative; right: 20px;'><b>". $mailQueue . "</b></td>";
}
else{$mailQueue = "Error";}
//Delivered mail
if (!empty($buffer1[9])){
$deliMail = $buffer1[9];
$data .= "<td><b>$deliMail</b></td>";
}
else{$deliMail = "Error";}
//free memory
$data .= "<td><b>". $buffer1[11] . "</b></td>";
//CPU usage
$data .= "<td><b>".  $buffer1[13] . "</b></td></tr>";
//close table
//$data .= "</table></small>";
//$data .= '
//  </div>
//</div>
//';
$data .= "<tr class='child-2810' style='display: none;'>" ."<td><form  action='search.php' method='GET' name='D3' onsubmit='return sanitize()' > <label for='username'><b>Backup Search :</b></label>
<input type='text' name='query' id='userinput' onfocus='removeSpace()' class='border-primary rounded' placeholder='type username...' /><input type='submit' value='Search' />
<h6>ns11.doveserver.com  IP: 67.220.183.250 <br> ns12.doveserver.com IP: 67.220.183.251 </h6>
<input type='hidden' name='server' value='D3'></form></td></tr>";
echo $data;

/////////////////////// STANDARD4 //////////////////////////////////////
//Reset and initialiaze variables
$timeout =1;$file1 = "";$buffer1 = "";$data="";$fp="";$errno="";$errstr="";$timeout="";$load="";$loadColor="";$availableBackupDates="";$disk_space="";$disk_free="";$disk_used_precent="";$disk_used_precent_color="";$backupValue="";$backupValueColor="";$bootValue="";$bootValueColor="";$swap ="";$mailQueue="";$deliMail=""; $homeValueColor="";$homeValue="";$httpStatus="";$mysqlStatus="";$varValue="";$varValueColor="";

//Get the shell output file from "monitorData.txt"
 $file1 = file_get_contents('/home/ogbonnan/public_html/monitor/data/D4monitorData.txt', true);
$buffer1 = preg_split("/:/", $file1, -1, PREG_SPLIT_NO_EMPTY);
//Server name
//Data Toggle
$data .="<tr  class='parent' id='2811'><td ><span class='btn btn-default'><b>D4</b></span></td>";
//http Status
$httpStatus = stream_socket_client("209.205.207.130:80", $errno, $errstr, 30);
	if (!$httpStatus) {
		$data .= "<td><b>Offline</b></td>";
	  fclose($httpStatus);
	} else {
		$data .= "<td><b>Online</b></td>";
		fclose($httpStatus);
	}
//MySQL status
// Find the correct MySql port number.
$mysqlStatus = stream_socket_client("209.205.207.130:3306", $errno, $errstr, 30);
	if (!$mysqlStatus) {
		$data .= "<td style='color:red;'><b>Offline</b></td>";
	  fclose($mysqlStatus);
	} else {
		$data .= "<td><b>Online</b></td>";
		fclose($mysqlStatus);
	}
//SERVER LOAD
$load = substr($buffer1[15], 0, 4);
if($load >= 20){
$loadColor = 'red';
}
$data .= "<td style='color: $loadColor;'><b>$load</b></td>";
//Available backups Dates
$availableBackupDates =availableBackupDates();
$data .= "<td><b>$availableBackupDates</b></td>";
//Home Diskspace used
$homeValue = preg_replace('/[^0-9]/', '', $buffer1[19]); // remove the percentage from the value;
if ($homeValue >= 75 ){
    $homeValueColor = 'red';
}
$data .= "<td style='position: relative; right: 80px; style='color: $homeValueColor;'><b>".$homeValue."%</b></td>";


//Backup Disk Space used
$backupValue = preg_replace('/[^0-9]/', '', $buffer1[1]); // remove the percentage from the value;
if ($backupValue >= 90 ){
    $backupValueColor = 'red';
}
$data .= "<td style='color: $backupValueColor; position: relative; right: 85px;'><b>".$backupValue ."%</b></td>";
$backupValue = "";
//Boot partition used
$bootValue = preg_replace('/[^0-9]/', '', $buffer1[17]); // remove the percentage from the value;
if ($bootValue >= 90 ){
    $bootValueColor = 'red';
}
$data .= "<td class='text-center' style='color: $bootValueColor; position: relative; right: 110px;'><b>".$bootValue . "%</b></td>";

//Var Partition used
$varValue = preg_replace('/[^0-9]/', '', $buffer1[21]); // remove the percentage from the value;
if ($varValue >= 90 ){
    $varValueColor = 'red';
}
$data .= "<td class='text-center' style='color: $varValueColor; position: relative; right: 85px;'><b>".$varValue . "%</b></td>";

//Swap Diskspace used
if (!empty($buffer1[3])){
$swap = $buffer1[3];
$data .= "<td class='text-center' style='position: relative; right: 55px;'><b>$swap</b></td>";
}
else{$swap = "Error";}

//Mail Queu
if (!empty($buffer1[5])){
$mailQueue = $buffer1[5];
$data .= "<td style='position: relative; right: 20px;'><b>". $mailQueue . "</b></td>";
}
else{$mailQueue = "Error";}
//Delivered mail
if (!empty($buffer1[9])){
$deliMail = $buffer1[9];
$data .= "<td><b>$deliMail</b></td>";
}
else{$deliMail = "Error";}
//free memory
$data .= "<td><b>". $buffer1[11] . "</b></td>";
//CPU usage
$data .= "<td><b>".  $buffer1[13] . "</b></td></tr>";
//close table
//$data .= "</table></small>";
//$data .= '
//  </div>
//</div>
//';
$data .= "<tr class='child-2811' style='display: none;'>" ."<td><form  action='search.php' method='GET' name='D4' onsubmit='return sanitize()' > <label for='username'><b>Backup Search :</b></label>
<input type='text' name='query' id='userinput' onfocus='removeSpace()' class='border-primary rounded' placeholder='type username...' /><input type='submit' value='Search' />
<h6>ns15.doveserver.com  IP: 209.205.207.130 <br> ns16.doveserver.com IP: 209.205.207.131 </h6>
<input type='hidden' name='server' value='D4'></form></td></tr>";
echo $data;
//////////////// STANDARD5 ////////////////////////////////////////
 //Reset and initialiaze variables
$timeout =1;$file1 = "";$buffer1 = "";$data="";$fp="";$errno="";$errstr="";$timeout="";$load="";$loadColor="";$availableBackupDates="";$disk_space="";$disk_free="";$disk_used_precent="";$disk_used_precent_color="";$backupValue="";$backupValueColor="";$bootValue="";$bootValueColor="";$swap ="";$mailQueue="";$deliMail=""; $homeValueColor="";$homeValue="";$httpStatus="";$mysqlStatus="";$varValue="";$varValueColor="";
 
//Get the shell output file from "monitorData.txt"
 $file1 = file_get_contents('/home/ogbonnan/public_html/monitor/data/D5monitorData.txt', true);
$buffer1 = preg_split("/:/", $file1, -1, PREG_SPLIT_NO_EMPTY);
$data .= "<small><table  class='table'>";
//Server name
//Data toggle
$data .="<tr  class='parent' id='2812'><td ><span class='btn btn-default'><b>D5</b></span></td>";
//HTTP status
$httpStatus = stream_socket_client("67.220.185.18:80", $errno, $errstr, 30);
	if (!$httpStatus) {
		$data .= "<td><b>Offline</b></td>";
	  fclose($httpStatus);
	} else {
		$data .= "<td><b>Online</b></td>";
		fclose($httpStatus);
	}
//MySQL status
// Find the correct MySql port number.
$mysqlStatus = stream_socket_client("67.220.185.18:3306", $errno, $errstr, 30);

	if (!$mysqlStatus) {
		$data .= "<td style='color:red;'><b>Offline</b></td>";
	  fclose($mysqlStatus);
	} else {
		$data .= "<td><b>Online</b></td>";
		fclose($mysqlStatus);
	}
//SERVER LOAD
$load = substr($buffer1[15], 0, 4);
if($load >= 20){
$loadColor = 'red';
}
$data .= "<td style='color: $loadColor;'><b>$load</b></td>";
//Available backups Dates
$availableBackupDates =availableBackupDates();
$data .= "<td><b>$availableBackupDates</b></td>";
//Home Diskspace used
$homeValue = preg_replace('/[^0-9]/', '', $buffer1[19]); // remove the percentage from the value;
if ($homeValue >= 75 ){
    $homeValueColor = 'red';
}
$data .= "<td style='position: relative; right: 80px; style='color: $homeValueColor;'><b>".$homeValue."%</b></td>";


//Backup Disk Space used
$backupValue = preg_replace('/[^0-9]/', '', $buffer1[1]); // remove the percentage from the value;
if ($backupValue >= 90 ){
    $backupValueColor = 'red';
}
$data .= "<td style='color: $backupValueColor; position: relative; right: 85px;'><b>".$backupValue ."%</b></td>";
$backupValue = "";
//Boot partition used
$bootValue = preg_replace('/[^0-9]/', '', $buffer1[17]); // remove the percentage from the value;
if ($bootValue >= 90 ){
    $bootValueColor = 'red';
}
$data .= "<td class='text-center' style='color: $bootValueColor; position: relative; right: 110px;'><b>".$bootValue . "%</b></td>";

//Var Partition used
$varValue = preg_replace('/[^0-9]/', '', $buffer1[21]); // remove the percentage from the value;
if ($varValue >= 90 ){
    $varValueColor = 'red';
}
$data .= "<td class='text-center' style='color: $varValueColor; position: relative; right: 85px;'><b>".$varValue . "%</b></td>";

//Swap Diskspace used
if (!empty($buffer1[3])){
$swap = $buffer1[3];
$data .= "<td class='text-center' style='position: relative; right: 55px;'><b>$swap</b></td>";
}
else{$swap = "Error";}

//Mail Queu
if (!empty($buffer1[5])){
$mailQueue = $buffer1[5];
$data .= "<td style='position: relative; right: 20px;'><b>". $mailQueue . "</b></td>";
}
else{$mailQueue = "Error";}
//Delivered mail
if (!empty($buffer1[9])){
$deliMail = $buffer1[9];
$data .= "<td><b>$deliMail</b></td>";
}
else{$deliMail = "Error";}
//free memory
$data .= "<td><b>". $buffer1[11] . "</b></td>";
//CPU usage
$data .= "<td><b>".  $buffer1[13] . "</b></td></td>";
//close table
//$data .= "</table></small>";
//$data .= '
//  </div>
//</div>
//';
$data .= "<tr class='child-2812' style='display: none;'>" ."<td><form  action='search.php' method='GET' name='D5' onsubmit='return sanitize()' > <label for='username'><b>Backup Search :</b></label>
<input type='text' name='query' id='userinput' onfocus='removeSpace()' class='border-primary rounded' placeholder='type username...' /><input type='submit' value='Search' />
<h6>ns17.doveserver.com  IP: 67.220.185.18 <br> ns18.doveserver.com IP: 67.220.185.19 </h6>
<input type='hidden' name='server' value='D5'></form></td></tr>";

echo $data;

/////////////////////////// STANDARD 6 //////////////////////////////////////
 //Reset and initialiaze variables
$timeout =1;$file1 = "";$buffer1 = "";$data="";$fp="";$errno="";$errstr="";$timeout="";$load="";$loadColor="";$availableBackupDates="";$disk_space="";$disk_free="";$disk_used_precent="";$disk_used_precent_color="";$backupValue="";$backupValueColor="";$bootValue="";$bootValueColor="";$swap ="";$mailQueue="";$deliMail=""; $homeValueColor="";$homeValue="";$httpStatus="";$mysqlStatus="";$varValue="";$varValueColor="";
 
//Get the shell output file from "monitorData.txt"
 $file1 = file_get_contents('/home/ogbonnan/public_html/monitor/data/D6monitorData.txt', true);
$buffer1 = preg_split("/:/", $file1, -1, PREG_SPLIT_NO_EMPTY);
$data .= "<small><table  class='table'>";
//Server name
//Data Toggle
$data .="<tr  class='parent' id='2813'><td ><span class='btn btn-default'><b>D6</b></span></td>";
//HTTP status
$httpStatus = stream_socket_client("67.220.183.18:80", $errno, $errstr, 30);
	if (!$httpStatus) {
		$data .= "<td><b>Offline</b></td>";
	  fclose($httpStatus);
	} else {
		$data .= "<td><b>Online</b></td>";
		fclose($httpStatus);
	}
//MySQL status
// Find the correct MySql port number.
$mysqlStatus = stream_socket_client("67.220.183.18:3306", $errno, $errstr, 30);

	if (!$mysqlStatus) {
		$data .= "<td style='color:red;'><b>Offline</b></td>";
	  fclose($mysqlStatus);
	} else {
		$data .= "<td><b>Online</b></td>";
		fclose($mysqlStatus);
	}
//SERVER LOAD
$load = substr($buffer1[15], 0, 4);
if($load >= 20){
$loadColor = 'red';
}
$data .= "<td style='color: $loadColor;'><b>$load</b></td>";
//Available backups Dates
$availableBackupDates =availableBackupDates();
$data .= "<td><b>$availableBackupDates</b></td>";
//Home Diskspace used
$homeValue = preg_replace('/[^0-9]/', '', $buffer1[19]); // remove the percentage from the value;
if ($homeValue >= 75 ){
    $homeValueColor = 'red';
}
$data .= "<td style='position: relative; right: 80px; style='color: $homeValueColor;'><b>".$homeValue."%</b></td>";


//Backup Disk Space used
$backupValue = preg_replace('/[^0-9]/', '', $buffer1[1]); // remove the percentage from the value;
if ($backupValue >= 90 ){
    $backupValueColor = 'red';
}
$data .= "<td style='color: $backupValueColor; position: relative; right: 85px;'><b>".$backupValue ."%</b></td>";
$backupValue = "";
//Boot partition used
$bootValue = preg_replace('/[^0-9]/', '', $buffer1[17]); // remove the percentage from the value;
if ($bootValue >= 90 ){
    $bootValueColor = 'red';
}
$data .= "<td class='text-center' style='color: $bootValueColor; position: relative; right: 110px;'><b>".$bootValue . "%</b></td>";

//Var Partition used
$varValue = preg_replace('/[^0-9]/', '', $buffer1[21]); // remove the percentage from the value;
if ($varValue >= 90 ){
    $varValueColor = 'red';
}
$data .= "<td class='text-center' style='color: $varValueColor; position: relative; right: 85px;'><b>".$varValue . "%</b></td>";

//Swap Diskspace used
if (!empty($buffer1[3])){
$swap = $buffer1[3];
$data .= "<td class='text-center' style='position: relative; right: 55px;'><b>$swap</b></td>";
}
else{$swap = "Error";}

//Mail Queu
if (!empty($buffer1[5])){
$mailQueue = $buffer1[5];
$data .= "<td style='position: relative; right: 20px;'><b>". $mailQueue . "</b></td>";
}
else{$mailQueue = "Error";}
//Delivered mail
if (!empty($buffer1[9])){
$deliMail = $buffer1[9];
$data .= "<td><b>$deliMail</b></td>";
}
else{$deliMail = "Error";}
//free memory
$data .= "<td><b>". $buffer1[11] . "</b></td>";
//CPU usage
$data .= "<td><b>".  $buffer1[13] . "</b></td></tr>";
//close table
//$data .= "</table></small>";
//$data .= '
//  </div>
//</div>
//';
$data .= "<tr class='child-2813' style='display: none;'>" ."<td><form  action='search.php' method='GET' name='D6' onsubmit='return sanitize()' > <label for='username'><b>Backup Search :</b></label>
<input type='text' name='query' id='userinput' onfocus='removeSpace()' class='border-primary rounded' placeholder='type username...' /><input type='submit' value='Search' />
<h6>ns21.doveserver.com  IP: 67.220.183.18 <br> ns22.doveserver.com IP: 67.220.183.19 </h6>
<input type='hidden' name='server' value='D6'></form></td></tr>";
echo $data;
///////////////////// STANDARD 7 ///////////////////////////////
 //Reset and initialiaze variables
$timeout =1;$file1 = "";$buffer1 = "";$data="";$fp="";$errno="";$errstr="";$timeout="";$load="";$loadColor="";$availableBackupDates="";$disk_space="";$disk_free="";$disk_used_precent="";$disk_used_precent_color="";$backupValue="";$backupValueColor="";$bootValue="";$bootValueColor="";$swap ="";$mailQueue="";$deliMail=""; $homeValueColor="";$homeValue="";$httpStatus="";$mysqlStatus="";$varValue="";$varValueColor="";
 
//Get the shell output file from "monitorData.txt"
 $file1 = file_get_contents('/home/ogbonnan/public_html/monitor/data/D7monitorData.txt', true);
$buffer1 = preg_split("/:/", $file1, -1, PREG_SPLIT_NO_EMPTY);
$data .= "<small><table  class='table'>";
//Server name
//Data toggle
$data .="<tr  class='parent' id='2814'><td ><span class='btn btn-default'><b>D7</b></span></td>";
//HTTP status
$httpStatus = stream_socket_client("67.220.187.210:80", $errno, $errstr, 30);
	if (!$httpStatus) {
		$data .= "<td><b>Offline</b></td>";
	  fclose($httpStatus);
	} else {
		$data .= "<td><b>Online</b></td>";
		fclose($httpStatus);
	}
//MySQL status
// Find the correct MySql port number.
$mysqlStatus = stream_socket_client("67.220.187.210:3306", $errno, $errstr, 30);

	if (!$mysqlStatus) {
		$data .= "<td style='color:red;'><b>Offline</b></td>";
	  fclose($mysqlStatus);
	} else {
		$data .= "<td><b>Online</b></td>";
		fclose($mysqlStatus);
	}
//SERVER LOAD
$load = substr($buffer1[15], 0, 4);
if($load >= 20){
$loadColor = 'red';
}
$data .= "<td style='color: $loadColor;'><b>$load</b></td>";
//Available backups Dates
$availableBackupDates =availableBackupDates();
$data .= "<td><b>$availableBackupDates</b></td>";
//Home Diskspace used
$homeValue = preg_replace('/[^0-9]/', '', $buffer1[19]); // remove the percentage from the value;
if ($homeValue >= 75 ){
    $homeValueColor = 'red';
}
$data .= "<td style='position: relative; right: 80px; style='color: $homeValueColor;'><b>".$homeValue."%</b></td>";


//Backup Disk Space used
$backupValue = preg_replace('/[^0-9]/', '', $buffer1[1]); // remove the percentage from the value;
if ($backupValue >= 90 ){
    $backupValueColor = 'red';
}
$data .= "<td style='color: $backupValueColor; position: relative; right: 85px;'><b>".$backupValue ."%</b></td>";
$backupValue = "";
//Boot partition used
$bootValue = preg_replace('/[^0-9]/', '', $buffer1[17]); // remove the percentage from the value;
if ($bootValue >= 90 ){
    $bootValueColor = 'red';
}
$data .= "<td class='text-center' style='color: $bootValueColor; position: relative; right: 110px;'><b>".$bootValue . "%</b></td>";

//Var Partition used
$varValue = preg_replace('/[^0-9]/', '', $buffer1[21]); // remove the percentage from the value;
if ($varValue >= 90 ){
    $varValueColor = 'red';
}
$data .= "<td class='text-center' style='color: $varValueColor; position: relative; right: 85px;'><b>".$varValue . "%</b></td>";

//Swap Diskspace used
if (!empty($buffer1[3])){
$swap = $buffer1[3];
$data .= "<td class='text-center' style='position: relative; right: 55px;'><b>$swap</b></td>";
}
else{$swap = "Error";}

//Mail Queu
if (!empty($buffer1[5])){
$mailQueue = $buffer1[5];
$data .= "<td style='position: relative; right: 20px;'><b>". $mailQueue . "</b></td>";
}
else{$mailQueue = "Error";}
//Delivered mail
if (!empty($buffer1[9])){
$deliMail = $buffer1[9];
$data .= "<td><b>$deliMail</b></td>";
}
else{$deliMail = "Error";}
//free memory
$data .= "<td><b>". $buffer1[11] . "</b></td>";
//CPU usage
$data .= "<td><b>".  $buffer1[13] . "</b></td></tr>";
//close table
//$data .= "</table></small>";
//$data .= '
//  </div>
//</div>
//';
$data .= "<tr class='child-2814' style='display: none;'>" ."<td><form  action='search.php' method='GET' name='D7' onsubmit='return sanitize()' > <label for='username'><b>Backup Search :</b></label>
<input type='text' name='query' id='userinput' onfocus='removeSpace()' class='border-primary rounded' placeholder='type username...' /><input type='submit' value='Search' />
<h6>ns23.doveserver.com  IP: 67.220.187.210 <br> ns24.doveserver.com IP: 67.220.187.211 </h6>
<input type='hidden' name='server' value='D7'></form></td></tr>";
echo $data;
/////////////////// STANDARD8 ///////////////////////////////////////////
 //Reset and initialiaze variables
$timeout =1;$file1 = "";$buffer1 = "";$data="";$fp="";$errno="";$errstr="";$timeout="";$load="";$loadColor="";$availableBackupDates="";$disk_space="";$disk_free="";$disk_used_precent="";$disk_used_precent_color="";$backupValue="";$backupValueColor="";$bootValue="";$bootValueColor="";$swap ="";$mailQueue="";$deliMail=""; $homeValueColor="";$homeValue="";$httpStatus="";$mysqlStatus="";$varValue="";$varValueColor="";
 
//Get the shell output file from "monitorData.txt"
 $file1 = file_get_contents('/home/ogbonnan/public_html/monitor/data/D8monitorData.txt', true);
$buffer1 = preg_split("/:/", $file1, -1, PREG_SPLIT_NO_EMPTY);
$data .= "<small><table  class='table'>";
//Server name
//Data toggle
$data .="<tr  class='parent' id='2815'><td ><span class='btn btn-default'><b>D8</b></span></td>";
//HTTP status
$httpStatus = stream_socket_client("209.205.209.130:80", $errno, $errstr, 30);
	if (!$httpStatus) {
		$data .= "<td><b>Offline</b></td>";
	  fclose($httpStatus);
	} else {
		$data .= "<td><b>Online</b></td>";
		fclose($httpStatus);
	}
//MySQL status
// Find the correct MySql port number.
$mysqlStatus = stream_socket_client("209.205.209.130:3306", $errno, $errstr, 30);

	if (!$mysqlStatus) {
		$data .= "<td style='color:red;'><b>Offline</b></td>";
	  fclose($mysqlStatus);
	} else {
		$data .= "<td><b>Online</b></td>";
		fclose($mysqlStatus);
	}
//SERVER LOAD
$load = substr($buffer1[15], 0, 4);
if($load >= 20){
$loadColor = 'red';
}
$data .= "<td style='color: $loadColor;'><b>$load</b></td>";
//Available backups Dates
$availableBackupDates =availableBackupDates();
$data .= "<td><b>$availableBackupDates</b></td>";
//Home Diskspace used
$homeValue = preg_replace('/[^0-9]/', '', $buffer1[19]); // remove the percentage from the value;
if ($homeValue >= 75 ){
    $homeValueColor = 'red';
}
$data .= "<td style='position: relative; right: 80px; style='color: $homeValueColor;'><b>".$homeValue."%</b></td>";


//Backup Disk Space used
$backupValue = preg_replace('/[^0-9]/', '', $buffer1[1]); // remove the percentage from the value;
if ($backupValue >= 90 ){
    $backupValueColor = 'red';
}
$data .= "<td style='color: $backupValueColor; position: relative; right: 85px;'><b>".$backupValue ."%</b></td>";
$backupValue = "";
//Boot partition used
$bootValue = preg_replace('/[^0-9]/', '', $buffer1[17]); // remove the percentage from the value;
if ($bootValue >= 90 ){
    $bootValueColor = 'red';
}
$data .= "<td class='text-center' style='color: $bootValueColor; position: relative; right: 110px;'><b>".$bootValue . "%</b></td>";

//Var Partition used
$varValue = preg_replace('/[^0-9]/', '', $buffer1[21]); // remove the percentage from the value;
if ($varValue >= 90 ){
    $varValueColor = 'red';
}
$data .= "<td class='text-center' style='color: $varValueColor; position: relative; right: 85px;'><b>".$varValue . "%</b></td>";

//Swap Diskspace used
if (!empty($buffer1[3])){
$swap = $buffer1[3];
$data .= "<td class='text-center' style='position: relative; right: 55px;'><b>$swap</b></td>";
}
else{$swap = "Error";}

//Mail Queu
if (!empty($buffer1[5])){
$mailQueue = $buffer1[5];
$data .= "<td style='position: relative; right: 20px;'><b>". $mailQueue . "</b></td>";
}
else{$mailQueue = "Error";}
//Delivered mail
if (!empty($buffer1[9])){
$deliMail = $buffer1[9];
$data .= "<td><b>$deliMail</b></td>";
}
else{$deliMail = "Error";}
//free memory
$data .= "<td><b>". $buffer1[11] . "</b></td>";
//CPU usage
$data .= "<td><b>".  $buffer1[13] . "</b></td></tr>";

//close table
//$data .= "</table></small>";
//$data .= '
//  </div>
//</div>
//';
$data .= "<tr class='child-2815' style='display: none;'>" ."<td><form  action='search.php' method='GET' name='D8' onsubmit='return sanitize()' > <label for='username'><b>Backup Search :</b></label>
<input type='text' name='query' id='userinput' onfocus='removeSpace()' class='border-primary rounded' placeholder='type username...' /><input type='submit' value='Search' />
<h6>ns25.doveserver.com  IP: 209.205.209.130 <br> ns26.doveserver.com IP: 209.205.209.131 </h6>
<input type='hidden' name='server' value='D8'></form></td></tr>";
echo $data;

////////////////////// STANDARD 9 /////////////////////////////////////////
 //Reset and initialiaze variables
$timeout =1;$file1 = "";$buffer1 = "";$data="";$fp="";$errno="";$errstr="";$timeout="";$load="";$loadColor="";$availableBackupDates="";$disk_space="";$disk_free="";$disk_used_precent="";$disk_used_precent_color="";$backupValue="";$backupValueColor="";$bootValue="";$bootValueColor="";$swap ="";$mailQueue="";$deliMail=""; $homeValueColor="";$homeValue="";$httpStatus="";$mysqlStatus="";$varValue="";$varValueColor="";
 
//Get the shell output file from "monitorData.txt"
 $file1 = file_get_contents('/home/ogbonnan/public_html/monitor/data/D9monitorData.txt', true);
$buffer1 = preg_split("/:/", $file1, -1, PREG_SPLIT_NO_EMPTY);
$data .= "<small><table  class='table'>";
//Server name
//Data toggle
$data .="<tr  class='parent' id='2816'><td ><span class='btn btn-default'><b>D9</b></span></td>";
//HTTP status
$httpStatus = stream_socket_client("209.205.209.34:80", $errno, $errstr, 30);
	if (!$httpStatus) {
		$data .= "<td><b>Offline</b></td>";
	  fclose($httpStatus);
	} else {
		$data .= "<td><b>Online</b></td>";
		fclose($httpStatus);
	}
//MySQL status
// Find the correct MySql port number.
$mysqlStatus = stream_socket_client("209.205.209.34:3306", $errno, $errstr, 30);

	if (!$mysqlStatus) {
		$data .= "<td style='color:red;'><b>Offline</b></td>";
	  fclose($mysqlStatus);
	} else {
		$data .= "<td><b>Online</b></td>";
		fclose($mysqlStatus);
	}
//SERVER LOAD
$load = substr($buffer1[15], 0, 4);
if($load >= 20){
$loadColor = 'red';
}
$data .= "<td style='color: $loadColor;'><b>$load</b></td>";
//Available backups Dates
$availableBackupDates =availableBackupDates();
$data .= "<td><b>$availableBackupDates</b></td>";
//Home Diskspace used
$homeValue = preg_replace('/[^0-9]/', '', $buffer1[19]); // remove the percentage from the value;
if ($homeValue >= 75 ){
    $homeValueColor = 'red';
}
$data .= "<td style='position: relative; right: 80px; style='color: $homeValueColor;'><b>".$homeValue."%</b></td>";


//Backup Disk Space used
$backupValue = preg_replace('/[^0-9]/', '', $buffer1[1]); // remove the percentage from the value;
if ($backupValue >= 90 ){
    $backupValueColor = 'red';
}
$data .= "<td style='color: $backupValueColor; position: relative; right: 85px;'><b>".$backupValue ."%</b></td>";
$backupValue = "";
//Boot partition used
$bootValue = preg_replace('/[^0-9]/', '', $buffer1[17]); // remove the percentage from the value;
if ($bootValue >= 90 ){
    $bootValueColor = 'red';
}
$data .= "<td class='text-center' style='color: $bootValueColor; position: relative; right: 110px;'><b>".$bootValue . "%</b></td>";

//Var Partition used
$varValue = preg_replace('/[^0-9]/', '', $buffer1[21]); // remove the percentage from the value;
if ($varValue >= 90 ){
    $varValueColor = 'red';
}
$data .= "<td class='text-center' style='color: $varValueColor; position: relative; right: 85px;'><b>".$varValue . "%</b></td>";

//Swap Diskspace used
if (!empty($buffer1[3])){
$swap = $buffer1[3];
$data .= "<td class='text-center' style='position: relative; right: 55px;'><b>$swap</b></td>";
}
else{$swap = "Error";}

//Mail Queu
if (!empty($buffer1[5])){
$mailQueue = $buffer1[5];
$data .= "<td style='position: relative; right: 20px;'><b>". $mailQueue . "</b></td>";
}
else{$mailQueue = "Error";}
//Delivered mail
if (!empty($buffer1[9])){
$deliMail = $buffer1[9];
$data .= "<td><b>$deliMail</b></td>";
}
else{$deliMail = "Error";}
//free memory
$data .= "<td><b>". $buffer1[11] . "</b></td>";
//CPU usage
$data .= "<td><b>".  $buffer1[13] . "</b></td></tr>";
//close table
//$data .= "</table></small>";
//$data .= '
//  </div>
//</div>
//';
$data .= "<tr class='child-2816' style='display: none;'>" ."<td><form  action='search.php' method='GET' name='D9' onsubmit='return sanitize()' > <label for='username'><b>Backup Search :</b></label>
<input type='text' name='query' id='userinput' onfocus='removeSpace()' class='border-primary rounded' placeholder='type username...' /><input type='submit' value='Search' />
<h6>ns29.doveserver.com IP: 209.205.209.34 <br> ns30.doveserver.com IP: 209.205.209.35 </h6>
<input type='hidden' name='server' value='D9'></form></td></tr>";
echo $data;

///////////////////////  STANDARD10 //////////////////////////////////////
 //Reset and initialiaze variables
$timeout =1;$file1 = "";$buffer1 = "";$data="";$fp="";$errno="";$errstr="";$timeout="";$load="";$loadColor="";$availableBackupDates="";$disk_space="";$disk_free="";$disk_used_precent="";$disk_used_precent_color="";$backupValue="";$backupValueColor="";$bootValue="";$bootValueColor="";$swap ="";$mailQueue="";$deliMail=""; $homeValueColor="";$homeValue="";$httpStatus="";$mysqlStatus="";$varValue="";$varValueColor="";
 
//Get the shell output file from "monitorData.txt"
 $file1 = file_get_contents('/home/ogbonnan/public_html/monitor/data/D10monitorData.txt', true);
$buffer1 = preg_split("/:/", $file1, -1, PREG_SPLIT_NO_EMPTY);
$data .= "<small><table  class='table'>";
//Server name
//Data toggle
$data .="<tr  class='parent' id='2817'><td ><span class='btn btn-default'><b>D10</b></span></td>";
//HTTP status
$httpStatus = stream_socket_client("209.205.206.58:80", $errno, $errstr, 30);
	if (!$httpStatus) {
		$data .= "<td><b>Offline</b></td>";
	  fclose($httpStatus);
	} else {
		$data .= "<td><b>Online</b></td>";
		fclose($httpStatus);
	}
//MySQL status
// Find the correct MySql port number.
$mysqlStatus = stream_socket_client("209.205.206.58:3306", $errno, $errstr, 30);

	if (!$mysqlStatus) {
		$data .= "<td style='color:red;'><b>Offline</b></td>";
	  fclose($mysqlStatus);
	} else {
		$data .= "<td><b>Online</b></td>";
		fclose($mysqlStatus);
	}
//SERVER LOAD
$load = substr($buffer1[15], 0, 4);
if($load >= 20){
$loadColor = 'red';
}
$data .= "<td style='color: $loadColor;'><b>$load</b></td>";
//Available backups Dates
$availableBackupDates =availableBackupDates();
$data .= "<td><b>$availableBackupDates</b></td>";
//Home Diskspace used
$homeValue = preg_replace('/[^0-9]/', '', $buffer1[19]); // remove the percentage from the value;
if ($homeValue >= 75 ){
    $homeValueColor = 'red';
}
$data .= "<td style='position: relative; right: 80px; style='color: $homeValueColor;'><b>".$homeValue."%</b></td>";


//Backup Disk Space used
$backupValue = preg_replace('/[^0-9]/', '', $buffer1[1]); // remove the percentage from the value;
if ($backupValue >= 90 ){
    $backupValueColor = 'red';
}
$data .= "<td style='color: $backupValueColor; position: relative; right: 85px;'><b>".$backupValue ."%</b></td>";
$backupValue = "";
//Boot partition used
$bootValue = preg_replace('/[^0-9]/', '', $buffer1[17]); // remove the percentage from the value;
if ($bootValue >= 90 ){
    $bootValueColor = 'red';
}
$data .= "<td class='text-center' style='color: $bootValueColor; position: relative; right: 110px;'><b>".$bootValue . "%</b></td>";

//Var Partition used
$varValue = preg_replace('/[^0-9]/', '', $buffer1[21]); // remove the percentage from the value;
if ($varValue >= 90 ){
    $varValueColor = 'red';
}
$data .= "<td class='text-center' style='color: $varValueColor; position: relative; right: 85px;'><b>".$varValue . "%</b></td>";

//Swap Diskspace used
if (!empty($buffer1[3])){
$swap = $buffer1[3];
$data .= "<td class='text-center' style='position: relative; right: 55px;'><b>$swap</b></td>";
}
else{$swap = "Error";}

//Mail Queu
if (!empty($buffer1[5])){
$mailQueue = $buffer1[5];
$data .= "<td style='position: relative; right: 20px;'><b>". $mailQueue . "</b></td>";
}
else{$mailQueue = "Error";}
//Delivered mail
if (!empty($buffer1[9])){
$deliMail = $buffer1[9];
$data .= "<td><b>$deliMail</b></td>";
}
else{$deliMail = "Error";}
//free memory
$data .= "<td><b>". $buffer1[11] . "</b></td>";
//CPU usage
$data .= "<td><b>".  $buffer1[13] . "</b></td><tr>";
//close table
//$data .= "</table></small>";
//$data .= '
//  </div>
//</div>
//';
$data .= "<tr class='child-2817' style='display: none;'>" ."<td><form  action='search.php' method='GET' name='D10' onsubmit='return sanitize()' > <label for='username'><b>Backup Search :</b></label>
<input type='text' name='query' id='userinput' onfocus='removeSpace()' class='border-primary rounded' placeholder='type username...' /><input type='submit' value='Search' />
<h6>ns17.smartwebng.com  IP: 209.205.206.58 <br> ns18.smartwebng.com IP: 209.205.206.59 </h6>
<input type='hidden' name='server' value='D10'></form></td></tr>";
echo $data;
/////////////////// STANDARD 11 //////////////////////////////////////
 //Reset and initialiaze variables
$timeout =1;$file1 = "";$buffer1 = "";$data="";$fp="";$errno="";$errstr="";$timeout="";$load="";$loadColor="";$availableBackupDates="";$disk_space="";$disk_free="";$disk_used_precent="";$disk_used_precent_color="";$backupValue="";$backupValueColor="";$bootValue="";$bootValueColor="";$swap ="";$mailQueue="";$deliMail=""; $homeValueColor="";$homeValue="";$httpStatus="";$mysqlStatus="";$varValue="";$varValueColor="";
 
//Get the shell output file from "monitorData.txt"
 $file1 = file_get_contents('/home/ogbonnan/public_html/monitor/data/D11monitorData.txt', true);
$buffer1 = preg_split("/:/", $file1, -1, PREG_SPLIT_NO_EMPTY);
$data .= "<small><table  class='table'>";
//Server name
//Data toggle
$data .="<tr  class='parent' id='2818'><td ><span class='btn btn-default'><b>D11</b></span></td>";
//HTTP status
$httpStatus = stream_socket_client("209.205.200.218:80", $errno, $errstr, 30);
	if (!$httpStatus) {
		$data .= "<td><b>Offline</b></td>";
	  fclose($httpStatus);
	} else {
		$data .= "<td><b>Online</b></td>";
		fclose($httpStatus);
	}
//MySQL status
// Find the correct MySql port number.
$mysqlStatus = stream_socket_client("209.205.200.218:3306", $errno, $errstr, 30);

	if (!$mysqlStatus) {
		$data .= "<td style='color:red;'><b>Offline</b></td>";
	  fclose($mysqlStatus);
	} else {
		$data .= "<td><b>Online</b></td>";
		fclose($mysqlStatus);
	}
//SERVER LOAD
$load = substr($buffer1[15], 0, 4);
if($load >= 20){
$loadColor = 'red';
}
$data .= "<td style='color: $loadColor;'><b>$load</b></td>";
//Available backups Dates
$availableBackupDates =availableBackupDates();
$data .= "<td><b>$availableBackupDates</b></td>";
//Home Diskspace used
$homeValue = preg_replace('/[^0-9]/', '', $buffer1[19]); // remove the percentage from the value;
if ($homeValue >= 75 ){
    $homeValueColor = 'red';
}
$data .= "<td style='position: relative; right: 80px; style='color: $homeValueColor;'><b>".$homeValue."%</b></td>";


//Backup Disk Space used
$backupValue = preg_replace('/[^0-9]/', '', $buffer1[1]); // remove the percentage from the value;
if ($backupValue >= 90 ){
    $backupValueColor = 'red';
}
$data .= "<td style='color: $backupValueColor; position: relative; right: 85px;'><b>".$backupValue ."%</b></td>";
$backupValue = "";
//Boot partition used
$bootValue = preg_replace('/[^0-9]/', '', $buffer1[17]); // remove the percentage from the value;
if ($bootValue >= 90 ){
    $bootValueColor = 'red';
}
$data .= "<td class='text-center' style='color: $bootValueColor; position: relative; right: 110px;'><b>".$bootValue . "%</b></td>";

//Var Partition used
$varValue = preg_replace('/[^0-9]/', '', $buffer1[21]); // remove the percentage from the value;
if ($varValue >= 90 ){
    $varValueColor = 'red';
}
$data .= "<td class='text-center' style='color: $varValueColor; position: relative; right: 85px;'><b>".$varValue . "%</b></td>";

//Swap Diskspace used
if (!empty($buffer1[3])){
$swap = $buffer1[3];
$data .= "<td class='text-center' style='position: relative; right: 55px;'><b>$swap</b></td>";
}
else{$swap = "Error";}

//Mail Queu
if (!empty($buffer1[5])){
$mailQueue = $buffer1[5];
$data .= "<td style='position: relative; right: 20px;'><b>". $mailQueue . "</b></td>";
}
else{$mailQueue = "Error";}
//Delivered mail
if (!empty($buffer1[9])){
$deliMail = $buffer1[9];
$data .= "<td><b>$deliMail</b></td>";
}
else{$deliMail = "Error";}
//free memory
$data .= "<td><b>". $buffer1[11] . "</b></td>";
//CPU usage
$data .= "<td><b>".  $buffer1[13] . "</b></td></tr>";
//close table
//$data .= "</table></small>";
//$data .= '
//  </div>
//</div>
//';
$data .= "<tr class='child-2818' style='display: none;'>" ."<td><form  action='search.php' method='GET' name='D11' onsubmit='return sanitize()' > <label for='username'><b>Backup Search :</b></label>
<input type='text' name='query' id='userinput' onfocus='removeSpace()' class='border-primary rounded' placeholder='type username...' /><input type='submit' value='Search' />
<h6>ns21.smartwebng.com IP: 209.205.200.218 <br> ns22.smartwebng.com IP: 209.205.200.219 </h6>
<input type='hidden' name='server' value='D11'></form></td></tr>";
echo $data;

////////////////// STANDARD12 ////////////////////////////////////////////
 //Reset and initialiaze variables
$timeout =1;$file1 = "";$buffer1 = "";$data="";$fp="";$errno="";$errstr="";$timeout="";$load="";$loadColor="";$availableBackupDates="";$disk_space="";$disk_free="";$disk_used_precent="";$disk_used_precent_color="";$backupValue="";$backupValueColor="";$bootValue="";$bootValueColor="";$swap ="";$mailQueue="";$deliMail=""; $homeValueColor="";$homeValue="";$httpStatus="";$mysqlStatus="";$varValue="";$varValueColor="";
 
//Get the shell output file from "monitorData.txt"
 $file1 = file_get_contents('/home/ogbonnan/public_html/monitor/data/D12monitorData.txt', true);
$buffer1 = preg_split("/:/", $file1, -1, PREG_SPLIT_NO_EMPTY);
$data .= "<small><table  class='table'>";
//Server name
//Data toggle
$data .="<tr  class='parent' id='2819'><td ><span class='btn btn-default'><b>D12</b></span></td>";
//HTTP status
$httpStatus = stream_socket_client("192.119.9.178:80", $errno, $errstr, 30);
	if (!$httpStatus) {
		$data .= "<td><b>Offline</b></td>";
	  fclose($httpStatus);
	} else {
		$data .= "<td><b>Online</b></td>";
		fclose($httpStatus);
	}
//MySQL status
// Find the correct MySql port number.
$mysqlStatus = stream_socket_client("192.119.9.178:3306", $errno, $errstr, 30);

	if (!$mysqlStatus) {
		$data .= "<td style='color:red;'><b>Offline</b></td>";
	  fclose($mysqlStatus);
	} else {
		$data .= "<td><b>Online</b></td>";
		fclose($mysqlStatus);
	}
//SERVER LOAD
$load = substr($buffer1[15], 0, 4);
if($load >= 20){
$loadColor = 'red';
}
$data .= "<td style='color: $loadColor;'><b>$load</b></td>";
//Available backups Dates
$availableBackupDates =availableBackupDates();
$data .= "<td><b>$availableBackupDates</b></td>";
//Home Diskspace used
$homeValue = preg_replace('/[^0-9]/', '', $buffer1[19]); // remove the percentage from the value;
if ($homeValue >= 75 ){
    $homeValueColor = 'red';
}
$data .= "<td style='position: relative; right: 80px; style='color: $homeValueColor;'><b>".$homeValue."%</b></td>";


//Backup Disk Space used
$backupValue = preg_replace('/[^0-9]/', '', $buffer1[1]); // remove the percentage from the value;
if ($backupValue >= 90 ){
    $backupValueColor = 'red';
}
$data .= "<td style='color: $backupValueColor; position: relative; right: 85px;'><b>".$backupValue ."%</b></td>";
$backupValue = "";
//Boot partition used
$bootValue = preg_replace('/[^0-9]/', '', $buffer1[17]); // remove the percentage from the value;
if ($bootValue >= 90 ){
    $bootValueColor = 'red';
}
$data .= "<td class='text-center' style='color: $bootValueColor; position: relative; right: 110px;'><b>".$bootValue . "%</b></td>";

//Var Partition used
$varValue = preg_replace('/[^0-9]/', '', $buffer1[21]); // remove the percentage from the value;
if ($varValue >= 90 ){
    $varValueColor = 'red';
}
$data .= "<td class='text-center' style='color: $varValueColor; position: relative; right: 85px;'><b>".$varValue . "%</b></td>";

//Swap Diskspace used
if (!empty($buffer1[3])){
$swap = $buffer1[3];
$data .= "<td class='text-center' style='position: relative; right: 55px;'><b>$swap</b></td>";
}
else{$swap = "Error";}

//Mail Queu
if (!empty($buffer1[5])){
$mailQueue = $buffer1[5];
$data .= "<td style='position: relative; right: 20px;'><b>". $mailQueue . "</b></td>";
}
else{$mailQueue = "Error";}
//Delivered mail
if (!empty($buffer1[9])){
$deliMail = $buffer1[9];
$data .= "<td><b>$deliMail</b></td>";
}
else{$deliMail = "Error";}
//free memory
$data .= "<td><b>". $buffer1[11] . "</b></td>";
//CPU usage
$data .= "<td><b>".  $buffer1[13] . "</b></td></tr>";
//close table
//$data .= "</table></small>";
//$data .= '
//  </div>
//</div>
//';
$data .= "<tr class='child-2819' style='display: none;'>" ."<td><form  action='search.php' method='GET' name='D12' onsubmit='return sanitize()' > <label for='username'><b>Backup Search :</b></label>
<input type='text' name='query' id='userinput' onfocus='removeSpace()' class='border-primary rounded' placeholder='type username...' /><input type='submit' value='Search' />
<h6>ns39.doveserver.com  IP: 192.119.9.178 <br> ns40.doveserver.com IP: 192.119.9.179 </h6>
<input type='hidden' name='server' value='D12'></form></td></tr>";
echo $data;

////////////////////////// STANDARD 13 //////////////////////////////////////
 //Reset and initialiaze variables
$timeout =1;$file1 = "";$buffer1 = "";$data="";$fp="";$errno="";$errstr="";$timeout="";$load="";$loadColor="";$availableBackupDates="";$disk_space="";$disk_free="";$disk_used_precent="";$disk_used_precent_color="";$backupValue="";$backupValueColor="";$bootValue="";$bootValueColor="";$swap ="";$mailQueue="";$deliMail=""; $homeValueColor="";$homeValue="";$httpStatus="";$mysqlStatus="";$varValue="";$varValueColor="";
 
//Get the shell output file from "monitorData.txt"
 $file1 = file_get_contents('/home/ogbonnan/public_html/monitor/data/D13monitorData.txt', true);
$buffer1 = preg_split("/:/", $file1, -1, PREG_SPLIT_NO_EMPTY);
$data .= "<small><table  class='table'>";
//Server name
//Data toggle
$data .="<tr  class='parent' id='2820'><td ><span class='btn btn-default'><b>D13</b></span></td>";
//HTTP status
$httpStatus = stream_socket_client("67.220.184.98:80", $errno, $errstr, 30);
	if (!$httpStatus) {
		$data .= "<td><b>Offline</b></td>";
	  fclose($httpStatus);
	} else {
		$data .= "<td><b>Online</b></td>";
		fclose($httpStatus);
	}
//MySQL status
// Find the correct MySql port number.
$mysqlStatus = stream_socket_client("67.220.184.98:3306", $errno, $errstr, 30);

	if (!$mysqlStatus) {
		$data .= "<td style='color:red;'><b>Offline</b></td>";
	  fclose($mysqlStatus);
	} else {
		$data .= "<td><b>Online</b></td>";
		fclose($mysqlStatus);
	}
//SERVER LOAD
$load = substr($buffer1[15], 0, 4);
if($load >= 20){
$loadColor = 'red';
}
$data .= "<td style='color: $loadColor;'><b>$load</b></td>";
//Available backups Dates
$availableBackupDates =availableBackupDates();
$data .= "<td><b>$availableBackupDates</b></td>";
//Home Diskspace used
$homeValue = preg_replace('/[^0-9]/', '', $buffer1[19]); // remove the percentage from the value;
if ($homeValue >= 75 ){
    $homeValueColor = 'red';
}
$data .= "<td style='position: relative; right: 80px; style='color: $homeValueColor;'><b>".$homeValue."%</b></td>";


//Backup Disk Space used
$backupValue = preg_replace('/[^0-9]/', '', $buffer1[1]); // remove the percentage from the value;
if ($backupValue >= 90 ){
    $backupValueColor = 'red';
}
$data .= "<td style='color: $backupValueColor; position: relative; right: 85px;'><b>".$backupValue ."%</b></td>";
$backupValue = "";
//Boot partition used
$bootValue = preg_replace('/[^0-9]/', '', $buffer1[17]); // remove the percentage from the value;
if ($bootValue >= 90 ){
    $bootValueColor = 'red';
}
$data .= "<td class='text-center' style='color: $bootValueColor; position: relative; right: 110px;'><b>".$bootValue . "%</b></td>";

//Var Partition used
$varValue = preg_replace('/[^0-9]/', '', $buffer1[21]); // remove the percentage from the value;
if ($varValue >= 90 ){
    $varValueColor = 'red';
}
$data .= "<td class='text-center' style='color: $varValueColor; position: relative; right: 85px;'><b>".$varValue . "%</b></td>";

//Swap Diskspace used
if (!empty($buffer1[3])){
$swap = $buffer1[3];
$data .= "<td class='text-center' style='position: relative; right: 55px;'><b>$swap</b></td>";
}
else{$swap = "Error";}

//Mail Queu
if (!empty($buffer1[5])){
$mailQueue = $buffer1[5];
$data .= "<td style='position: relative; right: 20px;'><b>". $mailQueue . "</b></td>";
}
else{$mailQueue = "Error";}
//Delivered mail
if (!empty($buffer1[9])){
$deliMail = $buffer1[9];
$data .= "<td><b>$deliMail</b></td>";
}
else{$deliMail = "Error";}
//free memory
$data .= "<td><b>". $buffer1[11] . "</b></td>";
//CPU usage
$data .= "<td><b>".  $buffer1[13] . "</b></td></tr>";
//close table
//$data .= "</table></small>";
//$data .= '
//  </div>
//</div>
//';
$data .= "<tr class='child-2820' style='display: none;'>" ."<td><form  action='search.php' method='GET' name='D13' onsubmit='return sanitize()' > <label for='username'><b>Backup Search :</b></label>
<input type='text' name='query' id='userinput' onfocus='removeSpace()' class='border-primary rounded' placeholder='type username...' /><input type='submit' value='Search' />
<h6>ns41.doveserver.com  IP: 67.220.184.98 <br> ns42.doveserver.com IP: 67.220.184.99 </h6>
<input type='hidden' name='server' value='D13'></form></td></tr>";
echo $data;

////////////////////////////// STANDARD 14 //////////////////////////////////
 //Reset and initialiaze variables
$timeout =1;$file1 = "";$buffer1 = "";$data="";$fp="";$errno="";$errstr="";$timeout="";$load="";$loadColor="";$availableBackupDates="";$disk_space="";$disk_free="";$disk_used_precent="";$disk_used_precent_color="";$backupValue="";$backupValueColor="";$bootValue="";$bootValueColor="";$swap ="";$mailQueue="";$deliMail=""; $homeValueColor="";$homeValue="";$httpStatus="";$mysqlStatus="";$varValue="";$varValueColor="";
 
//Get the shell output file from "monitorData.txt"
 $file1 = file_get_contents('/home/ogbonnan/public_html/monitor/data/D14monitorData.txt', true);
$buffer1 = preg_split("/:/", $file1, -1, PREG_SPLIT_NO_EMPTY);
$data .= "<small><table  class='table'>";
//Server name
//Data Toggle
$data .="<tr  class='parent' id='2821'><td ><span class='btn btn-default'><b>D14</b></span></td>";
//HTTP status
$httpStatus = stream_socket_client("67.220.187.50:80", $errno, $errstr, 30);
	if (!$httpStatus) {
		$data .= "<td><b>Offline</b></td>";
	  fclose($httpStatus);
	} else {
		$data .= "<td><b>Online</b></td>";
		fclose($httpStatus);
	}
//MySQL status
// Find the correct MySql port number.
$mysqlStatus = stream_socket_client("67.220.187.50:3306", $errno, $errstr, 30);

	if (!$mysqlStatus) {
		$data .= "<td style='color:red;'><b>Offline</b></td>";
	  fclose($mysqlStatus);
	} else {
		$data .= "<td><b>Online</b></td>";
		fclose($mysqlStatus);
	}
//SERVER LOAD
$load = substr($buffer1[15], 0, 4);
if($load >= 20){
$loadColor = 'red';
}
$data .= "<td style='color: $loadColor;'><b>$load</b></td>";
//Available backups Dates
$availableBackupDates =availableBackupDates();
$data .= "<td><b>$availableBackupDates</b></td>";
//Home Diskspace used
$homeValue = preg_replace('/[^0-9]/', '', $buffer1[19]); // remove the percentage from the value;
if ($homeValue >= 75 ){
    $homeValueColor = 'red';
}
$data .= "<td style='position: relative; right: 80px; style='color: $homeValueColor;'><b>".$homeValue."%</b></td>";


//Backup Disk Space used
$backupValue = preg_replace('/[^0-9]/', '', $buffer1[1]); // remove the percentage from the value;
if ($backupValue >= 90 ){
    $backupValueColor = 'red';
}
$data .= "<td style='color: $backupValueColor; position: relative; right: 85px;'><b>".$backupValue ."%</b></td>";
$backupValue = "";
//Boot partition used
$bootValue = preg_replace('/[^0-9]/', '', $buffer1[17]); // remove the percentage from the value;
if ($bootValue >= 90 ){
    $bootValueColor = 'red';
}
$data .= "<td class='text-center' style='color: $bootValueColor; position: relative; right: 110px;'><b>".$bootValue . "%</b></td>";

//Var Partition used
$varValue = preg_replace('/[^0-9]/', '', $buffer1[21]); // remove the percentage from the value;
if ($varValue >= 90 ){
    $varValueColor = 'red';
}
$data .= "<td class='text-center' style='color: $varValueColor; position: relative; right: 85px;'><b>".$varValue . "%</b></td>";

//Swap Diskspace used
if (!empty($buffer1[3])){
$swap = $buffer1[3];
$data .= "<td class='text-center' style='position: relative; right: 55px;'><b>$swap</b></td>";
}
else{$swap = "Error";}

//Mail Queu
if (!empty($buffer1[5])){
$mailQueue = $buffer1[5];
$data .= "<td style='position: relative; right: 20px;'><b>". $mailQueue . "</b></td>";
}
else{$mailQueue = "Error";}
//Delivered mail
if (!empty($buffer1[9])){
$deliMail = $buffer1[9];
$data .= "<td><b>$deliMail</b></td>";
}
else{$deliMail = "Error";}
//free memory
$data .= "<td><b>". $buffer1[11] . "</b></td>";
//CPU usage
$data .= "<td><b>".  $buffer1[13] . "</b></td></tr>";
//close table
//$data .= "</table></small>";
//$data .= '
//  </div>
//</div>
//';
$data .= "<tr class='child-2821' style='display: none;'>" ."<td><form  action='search.php' method='GET' name='D14' onsubmit='return sanitize()' > <label for='username'><b>Backup Search :</b></label>
<input type='text' name='query' id='userinput' onfocus='removeSpace()' class='border-primary rounded' placeholder='type username...' /><input type='submit' value='Search' />
<h6>ns43.doveserver.com  IP: 67.220.187.50 <br> ns44.doveserver.com IP: 67.220.187.51  </h6>
<input type='hidden' name='server' value='D14'></form></td></tr>";
echo $data;

////////////////////////////// STANDARD 15 //////////////////////////////////
 //Reset and initialiaze variables
$timeout =1;$file1 = "";$buffer1 = "";$data="";$fp="";$errno="";$errstr="";$timeout="";$load="";$loadColor="";$availableBackupDates="";$disk_space="";$disk_free="";$disk_used_precent="";$disk_used_precent_color="";$backupValue="";$backupValueColor="";$bootValue="";$bootValueColor="";$swap ="";$mailQueue="";$deliMail=""; $homeValueColor="";$homeValue="";$httpStatus="";$mysqlStatus="";$varValue="";$varValueColor="";
 
//Get the shell output file from "monitorData.txt"
 $file1 = file_get_contents('/home/ogbonnan/public_html/monitor/data/D15monitorData.txt', true);
$buffer1 = preg_split("/:/", $file1, -1, PREG_SPLIT_NO_EMPTY);
$data .= "<small><table  class='table'>";
//Server name
//Data Toggle
$data .="<tr  class='parent' id='2827'><td ><span class='btn btn-default'><b>D15</b></span></td>";
//HTTP status
$httpStatus = stream_socket_client("209.205.221.250:80", $errno, $errstr, 30);
	if (!$httpStatus) {
		$data .= "<td title='Apache server'><b>Offline</b></td>";
	  fclose($httpStatus);
	} else {
		$data .= "<td title='Apache server'><b>Online</b></td>";
		fclose($httpStatus);
	}
//MySQL status
// Find the correct MySql port number.
$mysqlStatus = stream_socket_client("209.205.221.250:3306", $errno, $errstr, 30);

	if (!$mysqlStatus) {
		$data .= "<td style='color:red;' title='Database server'><b>Offline</b></td>";
	  fclose($mysqlStatus);
	} else {
		$data .= "<td title='Database server'><b>Online</b></td>
";
		fclose($mysqlStatus);
	}
//SERVER LOAD
$load = substr($buffer1[15], 0, 4);
if($load >= 20){
$loadColor = 'red';
}
$data .= "<td style='color: $loadColor;' title='Server Load'><b>$load</b></td>";
//Available backups Dates
$availableBackupDates =availableBackupDates();
$data .= "<td title='Available Backups'><b>$availableBackupDates</b></td>";
//Home Diskspace used
$homeValue = preg_replace('/[^0-9]/', '', $buffer1[19]); // remove the percentage from the value;
if ($homeValue >= 75 ){
    $homeValueColor = 'red';
}
$data .= "<td style='position: relative; right: 80px; style='color: $homeValueColor;' title='Home Disk Space'><b>".$homeValue."%</b></td>";


//Backup Disk Space used
$backupValue = preg_replace('/[^0-9]/', '', $buffer1[1]); // remove the percentage from the value;
if ($backupValue >= 90 ){
    $backupValueColor = 'red';
}
$data .= "<td style='color: $backupValueColor; position: relative; right: 85px;' title='Backup Disk Space'><b>".$backupValue ."%</b></td>";
$backupValue = "";
//Boot partition used
$bootValue = preg_replace('/[^0-9]/', '', $buffer1[17]); // remove the percentage from the value;
if ($bootValue >= 90 ){
    $bootValueColor = 'red';
}
$data .= "<td class='text-center' style='color: $bootValueColor; position: relative; right: 110px;' title='Boot partition Diskspace'><b>".$bootValue . "%</b></td>";

//Var Partition used
$varValue = preg_replace('/[^0-9]/', '', $buffer1[21]); // remove the percentage from the value;
if ($varValue >= 90 ){
    $varValueColor = 'red';
}
$data .= "<td class='text-center' style='color: $varValueColor; position: relative; right: 85px;' title='Var partition Disk space'><b>".$varValue . "%</b></td>";

//Swap Diskspace used
if (!empty($buffer1[3])){
$swap = $buffer1[3];
$data .= "<td class='text-center' style='position: relative; right: 55px;' title='Swap patition Disk Space'><b>$swap</b></td>";
}
else{$swap = "Error";}

//Mail Queu
if (!empty($buffer1[5])){
$mailQueue = $buffer1[5];
$data .= "<td style='position: relative; right: 20px;' title='Mail queue'><b>". $mailQueue . "</b></td>";
}
else{$mailQueue = "Error";}
//Delivered mail
if (!empty($buffer1[9])){
$deliMail = $buffer1[9];
$data .= "<td title='Mail Delivered in 24h'><b>$deliMail</b></td>";
}
else{$deliMail = "Error";}
//free memory
$data .= "<td title='Physical Memory'><b>". $buffer1[11] . "</b></td>";
//CPU usage
$data .= "<td title='CPU'><b>".  $buffer1[13] . "</b></td></tr>";
//close table
//$data .= "</table></small>";
//$data .= '
//  </div>
//</div>
//';
$data .= "<tr class='child-2827' style='display: none;'>" ."<td><form  action='search.php' method='GET' name='D15' onsubmit='return sanitize()' > <label for='username'><b>Backup Search :</b></label>
<input type='text' name='query' id='userinput' onfocus='removeSpace()' class='border-primary rounded' placeholder='type username...' /><input type='submit' value='Search' />
<h6>ns47.doveserver.com IP: 209.205.221.250 <br> ns48.doveserver.com IP: 209.205.221.251 </h6>
<input type='hidden' name='server' value='D15'></form></td></tr>";
echo $data;


/////////////////////////////////////// HOST1 //////////////////////////////
 //Reset and initialiaze variables
$timeout =1;$file1 = "";$buffer1 = "";$data="";$fp="";$errno="";$errstr="";$timeout="";$load="";$loadColor="";$availableBackupDates="";$disk_space="";$disk_free="";$disk_used_precent="";$disk_used_precent_color="";$backupValue="";$backupValueColor="";$bootValue="";$bootValueColor="";$swap ="";$mailQueue="";$deliMail=""; $homeValueColor="";$homeValue="";$httpStatus="";$mysqlStatus="";$varValue="";$varValueColor="";
 
//Get the shell output file from "monitorData.txt"
 $file1 = file_get_contents('/home/ogbonnan/public_html/monitor/data/H1monitorData.txt', true);
$buffer1 = preg_split("/:/", $file1, -1, PREG_SPLIT_NO_EMPTY);
$data .= "<small><table  class='table'>";
//Server name
//Data toggle
$data .="<tr  class='parent' id='2822'><td ><span class='btn btn-default'><b>H1</b></span></td>";
//HTTP status
$httpStatus = stream_socket_client("67.220.188.162:80", $errno, $errstr, 30);
	if (!$httpStatus) {
		$data .= "<td><b>Offline</b></td>";
	  fclose($httpStatus);
	} else {
		$data .= "<td><b>Online</b></td>";
		fclose($httpStatus);
	}
//MySQL status
// Find the correct MySql port number.
$mysqlStatus = stream_socket_client("67.220.188.162:3306", $errno, $errstr, 30);

	if (!$mysqlStatus) {
		$data .= "<td style='color:red;'><b>Offline</b></td>";
	  fclose($mysqlStatus);
	} else {
		$data .= "<td><b>Online</b></td>";
		fclose($mysqlStatus);
	}
//SERVER LOAD
$load = substr($buffer1[15], 0, 4);
if($load >= 20){
$loadColor = 'red';
}
$data .= "<td style='color: $loadColor;'><b>$load</b></td>";
//Available backups Dates
$availableBackupDates =availableBackupDates();
$data .= "<td><b>$availableBackupDates</b></td>";
//Home Diskspace used
$homeValue = preg_replace('/[^0-9]/', '', $buffer1[19]); // remove the percentage from the value;
if ($homeValue >= 75 ){
    $homeValueColor = 'red';
}
$data .= "<td style='position: relative; right: 80px; style='color: $homeValueColor;'><b>".$homeValue."%</b></td>";


//Backup Disk Space used
$backupValue = preg_replace('/[^0-9]/', '', $buffer1[1]); // remove the percentage from the value;
if ($backupValue >= 90 ){
    $backupValueColor = 'red';
}
$data .= "<td style='color: $backupValueColor; position: relative; right: 85px;'><b>".$backupValue ."%</b></td>";
$backupValue = "";
//Boot partition used
$bootValue = preg_replace('/[^0-9]/', '', $buffer1[17]); // remove the percentage from the value;
if ($bootValue >= 90 ){
    $bootValueColor = 'red';
}
$data .= "<td class='text-center' style='color: $bootValueColor; position: relative; right: 110px;'><b>".$bootValue . "%</b></td>";

//Var Partition used
$varValue = preg_replace('/[^0-9]/', '', $buffer1[21]); // remove the percentage from the value;
if ($varValue >= 90 ){
    $varValueColor = 'red';
}
$data .= "<td class='text-center' style='color: $varValueColor; position: relative; right: 85px;'><b>".$varValue . "%</b></td>";

//Swap Diskspace used
if (!empty($buffer1[3])){
$swap = $buffer1[3];
$data .= "<td class='text-center' style='position: relative; right: 55px;'><b>$swap</b></td>";
}
else{$swap = "Error";}

//Mail Queu
if (!empty($buffer1[5])){
$mailQueue = $buffer1[5];
$data .= "<td style='position: relative; right: 20px;'><b>". $mailQueue . "</b></td>";
}
else{$mailQueue = "Error";}
//Delivered mail
if (!empty($buffer1[9])){
$deliMail = $buffer1[9];
$data .= "<td><b>$deliMail</b></td>";
}
else{$deliMail = "Error";}
//free memory
$data .= "<td><b>". $buffer1[11] . "</b></td>";
//CPU usage
$data .= "<td><b>".  $buffer1[13] . "</b></td></tr>";
//close table
//$data .= "</table></small>";
//$data .= '
//  </div>
//</div>
//';
$data .= "<tr class='child-2822' style='display: none;'>" ."<td><form  action='search.php' method='GET' name='H1' onsubmit='return sanitize()' > <label for='username'><b>Backup Search :</b></label>
<input type='text' name='query' id='userinput' onfocus='removeSpace()' class='border-primary rounded' placeholder='type username...' /><input type='submit' value='Search' />
<h6>ns9.smartwebng.com  IP: 67.220.188.162 <br>ns10.smartwebng.com IP:  67.220.188.163 </h6>
<input type='hidden' name='server' value='H1'></form></td></tr>";
echo $data;

//////////////////////////////  HOST2 //////////////////////////////////////
 //Reset and initialiaze variables
$timeout =1;$file1 = "";$buffer1 = "";$data="";$fp="";$errno="";$errstr="";$timeout="";$load="";$loadColor="";$availableBackupDates="";$disk_space="";$disk_free="";$disk_used_precent="";$disk_used_precent_color="";$backupValue="";$backupValueColor="";$bootValue="";$bootValueColor="";$swap ="";$mailQueue="";$deliMail=""; $homeValueColor="";$homeValue="";$httpStatus="";$mysqlStatus="";$varValue="";$varValueColor="";
 
//Get the shell output file from "monitorData.txt"
 $file1 = file_get_contents('/home/ogbonnan/public_html/monitor/data/H2monitorData.txt', true);
$buffer1 = preg_split("/:/", $file1, -1, PREG_SPLIT_NO_EMPTY);
$data .= "<small><table  class='table'>";
//Server name
//Data toggle
$data .="<tr  class='parent' id='2823'><td ><span class='btn btn-default'><b>H2</b></span></td>";
//HTTP status
$httpStatus = stream_socket_client("23.227.135.34:80", $errno, $errstr, 30);
	if (!$httpStatus) {
		$data .= "<td><b>Offline</b></td>";
	  fclose($httpStatus);
	} else {
		$data .= "<td><b>Online</b></td>";
		fclose($httpStatus);
	}
//MySQL status
// Find the correct MySql port number.
$mysqlStatus = stream_socket_client("23.227.135.34:3306", $errno, $errstr, 30);

	if (!$mysqlStatus) {
		$data .= "<td style='color:red;'><b>Offline</b></td>";
	  fclose($mysqlStatus);
	} else {
		$data .= "<td><b>Online</b></td>";
		fclose($mysqlStatus);
	}
//SERVER LOAD
$load = substr($buffer1[15], 0, 4);
if($load >= 20){
$loadColor = 'red';
}
$data .= "<td style='color: $loadColor;'><b>$load</b></td>";
//Available backups Dates
$availableBackupDates =availableBackupDates();
$data .= "<td><b>$availableBackupDates</b></td>";
//Home Diskspace used
$homeValue = preg_replace('/[^0-9]/', '', $buffer1[19]); // remove the percentage from the value;
if ($homeValue >= 75 ){
    $homeValueColor = 'red';
}
$data .= "<td style='position: relative; right: 80px; style='color: $homeValueColor;'><b>".$homeValue."%</b></td>";


//Backup Disk Space used
$backupValue = preg_replace('/[^0-9]/', '', $buffer1[1]); // remove the percentage from the value;
if ($backupValue >= 90 ){
    $backupValueColor = 'red';
}
$data .= "<td style='color: $backupValueColor; position: relative; right: 85px;'><b>".$backupValue ."%</b></td>";
$backupValue = "";
//Boot partition used
$bootValue = preg_replace('/[^0-9]/', '', $buffer1[17]); // remove the percentage from the value;
if ($bootValue >= 90 ){
    $bootValueColor = 'red';
}
$data .= "<td class='text-center' style='color: $bootValueColor; position: relative; right: 110px;'><b>".$bootValue . "%</b></td>";

//Var Partition used
$varValue = preg_replace('/[^0-9]/', '', $buffer1[21]); // remove the percentage from the value;
if ($varValue >= 90 ){
    $varValueColor = 'red';
}
$data .= "<td class='text-center' style='color: $varValueColor; position: relative; right: 85px;'><b>".$varValue . "%</b></td>";

//Swap Diskspace used
if (!empty($buffer1[3])){
$swap = $buffer1[3];
$data .= "<td class='text-center' style='position: relative; right: 55px;'><b>$swap</b></td>";
}
else{$swap = "Error";}

//Mail Queu
if (!empty($buffer1[5])){
$mailQueue = $buffer1[5];
$data .= "<td style='position: relative; right: 20px;'><b>". $mailQueue . "</b></td>";
}
else{$mailQueue = "Error";}
//Delivered mail
if (!empty($buffer1[9])){
$deliMail = $buffer1[9];
$data .= "<td><b>$deliMail</b></td>";
}
else{$deliMail = "Error";}
//free memory
$data .= "<td><b>". $buffer1[11] . "</b></td>";
//CPU usage
$data .= "<td><b>".  $buffer1[13] . "</b></td></tr>";
//close table
//$data .= "</table></small>";
//$data .= '
//  </div>
//</div>
//';
$data .= "<tr class='child-2823' style='display: none;'>" ."<td><form  action='search.php' method='GET' name='H2' onsubmit='return sanitize()' > <label for='username'><b>Backup Search :</b></label>
<input type='text' name='query' id='userinput' onfocus='removeSpace()' class='border-primary rounded' placeholder='type username...' /><input type='submit' value='Search' />
<h6>ns11.smartwebng.com  IP: 23.227.135.34 <br> ns12.smartwebng.com IP: 23.227.135.35 </h6>
<input type='hidden' name='server' value='H2'></form></td></tr>";
echo $data;

////////////////////// HOST3 ////////////////////////////////////////////
 //Reset and initialiaze variables
$timeout =1;$file1 = "";$buffer1 = "";$data="";$fp="";$errno="";$errstr="";$timeout="";$load="";$loadColor="";$availableBackupDates="";$disk_space="";$disk_free="";$disk_used_precent="";$disk_used_precent_color="";$backupValue="";$backupValueColor="";$bootValue="";$bootValueColor="";$swap ="";$mailQueue="";$deliMail=""; $homeValueColor="";$homeValue="";$httpStatus="";$mysqlStatus="";$varValue="";$varValueColor="";
 
//Get the shell output file from "monitorData.txt"
 $file1 = file_get_contents('/home/ogbonnan/public_html/monitor/data/H3monitorData.txt', true);
$buffer1 = preg_split("/:/", $file1, -1, PREG_SPLIT_NO_EMPTY);
$data .= "<small><table  class='table'>";
//Server name
//Data toggle
$data .="<tr  class='parent' id='2824'><td ><span class='btn btn-default'><b>H3</b></span></td>";
//HTTP status
$httpStatus = stream_socket_client("107.151.3.66:80", $errno, $errstr, 30);
	if (!$httpStatus) {
		$data .= "<td><b>Offline</b></td>";
	  fclose($httpStatus);
	} else {
		$data .= "<td><b>Online</b></td>";
		fclose($httpStatus);
	}
//MySQL status
// Find the correct MySql port number.
$mysqlStatus = stream_socket_client("107.151.3.66:3306", $errno, $errstr, 30);

	if (!$mysqlStatus) {
		$data .= "<td style='color:red;'><b>Offline</b></td>";
	  fclose($mysqlStatus);
	} else {
		$data .= "<td><b>Online</b></td>";
		fclose($mysqlStatus);
	}
//SERVER LOAD
$load = substr($buffer1[15], 0, 4);
if($load >= 20){
$loadColor = 'red';
}
$data .= "<td style='color: $loadColor;'><b>$load</b></td>";
//Available backups Dates
$availableBackupDates =availableBackupDates();
$data .= "<td><b>$availableBackupDates</b></td>";
//Home Diskspace used
$homeValue = preg_replace('/[^0-9]/', '', $buffer1[19]); // remove the percentage from the value;
if ($homeValue >= 75 ){
    $homeValueColor = 'red';
}
$data .= "<td style='position: relative; right: 80px; style='color: $homeValueColor;'><b>".$homeValue."%</b></td>";


//Backup Disk Space used
$backupValue = preg_replace('/[^0-9]/', '', $buffer1[1]); // remove the percentage from the value;
if ($backupValue >= 90 ){
    $backupValueColor = 'red';
}
$data .= "<td style='color: $backupValueColor; position: relative; right: 85px;'><b>".$backupValue ."%</b></td>";
$backupValue = "";
//Boot partition used
$bootValue = preg_replace('/[^0-9]/', '', $buffer1[17]); // remove the percentage from the value;
if ($bootValue >= 90 ){
    $bootValueColor = 'red';
}
$data .= "<td class='text-center' style='color: $bootValueColor; position: relative; right: 110px;'><b>".$bootValue . "%</b></td>";

//Var Partition used
$varValue = preg_replace('/[^0-9]/', '', $buffer1[21]); // remove the percentage from the value;
if ($varValue >= 90 ){
    $varValueColor = 'red';
}
$data .= "<td class='text-center' style='color: $varValueColor; position: relative; right: 85px;'><b>".$varValue . "%</b></td>";

//Swap Diskspace used
if (!empty($buffer1[3])){
$swap = $buffer1[3];
$data .= "<td class='text-center' style='position: relative; right: 55px;'><b>$swap</b></td>";
}
else{$swap = "Error";}

//Mail Queu
if (!empty($buffer1[5])){
$mailQueue = $buffer1[5];
$data .= "<td style='position: relative; right: 20px;'><b>". $mailQueue . "</b></td>";
}
else{$mailQueue = "Error";}
//Delivered mail
if (!empty($buffer1[9])){
$deliMail = $buffer1[9];
$data .= "<td><b>$deliMail</b></td>";
}
else{$deliMail = "Error";}
//free memory
$data .= "<td><b>". $buffer1[11] . "</b></td>";
//CPU usage
$data .= "<td><b>".  $buffer1[13] . "</b></td></tr>";
//close table
//$data .= "</table></small>";
//$data .= '
//  </div>
//</div>
//';
$data .= "<tr class='child-2824' style='display: none;'>" ."<td><form  action='search.php' method='GET' name='H3' onsubmit='return sanitize()' > <label for='username'><b>Backup Search :</b></label>
<input type='text' name='query' id='userinput' onfocus='removeSpace()' class='border-primary rounded' placeholder='type username...' /><input type='submit' value='Search' />
<h6>ns19.smartwebng.com  IP: 107.151.3.66 <br> ns20.smartwebng.com IP: 107.151.3.67 </h6>
<input type='hidden' name='server' value='H3'></form></td></tr>";
echo $data;

/////////////////////////// HOST4 ///////////////////////////////////////////
 //Reset and initialiaze variables
$timeout =1;$file1 = "";$buffer1 = "";$data="";$fp="";$errno="";$errstr="";$timeout="";$load="";$loadColor="";$availableBackupDates="";$disk_space="";$disk_free="";$disk_used_precent="";$disk_used_precent_color="";$backupValue="";$backupValueColor="";$bootValue="";$bootValueColor="";$swap ="";$mailQueue="";$deliMail=""; $homeValueColor="";$homeValue="";$httpStatus="";$mysqlStatus="";$varValue="";$varValueColor="";
 
//Get the shell output file from "monitorData.txt"
 $file1 = file_get_contents('/home/ogbonnan/public_html/monitor/data/H4monitorData.txt', true);
$buffer1 = preg_split("/:/", $file1, -1, PREG_SPLIT_NO_EMPTY);
$data .= "<small><table  class='table'>";
//Server name
//Data toggle
$data .="<tr  class='parent' id='2825'><td ><span class='btn btn-default'><b>H4</b></span></td>";
//HTTP status
$httpStatus = stream_socket_client("209.205.200.90:80", $errno, $errstr, 30);
	if (!$httpStatus) {
		$data .= "<td><b>Offline</b></td>";
	  fclose($httpStatus);
	} else {
		$data .= "<td><b>Online</b></td>";
		fclose($httpStatus);
	}
//MySQL status
// Find the correct MySql port number.
$mysqlStatus = stream_socket_client("209.205.200.90:3306", $errno, $errstr, 30);

	if (!$mysqlStatus) {
		$data .= "<td style='color:red;'><b>Offline</b></td>";
	  fclose($mysqlStatus);
	} else {
		$data .= "<td><b>Online</b></td>";
		fclose($mysqlStatus);
	}
//SERVER LOAD
$load = substr($buffer1[15], 0, 4);
if($load >= 20){
$loadColor = 'red';
}
$data .= "<td style='color: $loadColor;'><b>$load</b></td>";
//Available backups Dates
$availableBackupDates =availableBackupDates();
$data .= "<td><b>$availableBackupDates</b></td>";
//Home Diskspace used
$homeValue = preg_replace('/[^0-9]/', '', $buffer1[19]); // remove the percentage from the value;
if ($homeValue >= 75 ){
    $homeValueColor = 'red';
}
$data .= "<td style='position: relative; right: 80px; style='color: $homeValueColor;'><b>".$homeValue."%</b></td>";


//Backup Disk Space used
$backupValue = preg_replace('/[^0-9]/', '', $buffer1[1]); // remove the percentage from the value;
if ($backupValue >= 90 ){
    $backupValueColor = 'red';
}
$data .= "<td style='color: $backupValueColor; position: relative; right: 85px;'><b>".$backupValue ."%</b></td>";
$backupValue = "";
//Boot partition used
$bootValue = preg_replace('/[^0-9]/', '', $buffer1[17]); // remove the percentage from the value;
if ($bootValue >= 90 ){
    $bootValueColor = 'red';
}
$data .= "<td class='text-center' style='color: $bootValueColor; position: relative; right: 110px;'><b>".$bootValue . "%</b></td>";

//Var Partition used
$varValue = preg_replace('/[^0-9]/', '', $buffer1[21]); // remove the percentage from the value;
if ($varValue >= 90 ){
    $varValueColor = 'red';
}
$data .= "<td class='text-center' style='color: $varValueColor; position: relative; right: 85px;'><b>".$varValue . "%</b></td>";

//Swap Diskspace used
if (!empty($buffer1[3])){
$swap = $buffer1[3];
$data .= "<td class='text-center' style='position: relative; right: 55px;'><b>$swap</b></td>";
}
else{$swap = "Error";}

//Mail Queu
if (!empty($buffer1[5])){
$mailQueue = $buffer1[5];
$data .= "<td style='position: relative; right: 20px;'><b>". $mailQueue . "</b></td>";
}
else{$mailQueue = "Error";}
//Delivered mail
if (!empty($buffer1[9])){
$deliMail = $buffer1[9];
$data .= "<td><b>$deliMail</b></td>";
}
else{$deliMail = "Error";}
//free memory
$data .= "<td><b>". $buffer1[11] . "</b></td>";
//CPU usage
$data .= "<td><b>".  $buffer1[13] . "</b></td></tr>";
//close table
//$data .= "</table></small>";
//$data .= '
//  </div>
//</div>
//';
$data .= "<tr class='child-2825' style='display: none;'>" ."<td><form  action='search.php' method='GET' name='H4' onsubmit='return sanitize()' > <label for='username'><b>Backup Search :</b></label>
<input type='text' name='query' id='userinput' onfocus='removeSpace()' class='border-primary rounded' placeholder='type username...' /><input type='submit' value='Search' />
<h6>ns29.smartwebng.com  IP: 209.205.200.90 <br> ns30.smartwebng.com IP: 209.205.200.91 </h6>
<input type='hidden' name='server' value='H4'></form></td></tr>";
echo $data;

//////////////////////////////////////// HOST5 ///////////////////////////////
 //Reset and initialiaze variables
$timeout =1;$file1 = "";$buffer1 = "";$data="";$fp="";$errno="";$errstr="";$timeout="";$load="";$loadColor="";$availableBackupDates="";$disk_space="";$disk_free="";$disk_used_precent="";$disk_used_precent_color="";$backupValue="";$backupValueColor="";$bootValue="";$bootValueColor="";$swap ="";$mailQueue="";$deliMail=""; $homeValueColor="";$homeValue="";$httpStatus="";$mysqlStatus="";$varValue="";$varValueColor="";
 
//Get the shell output file from "monitorData.txt"
 $file1 = file_get_contents('/home/ogbonnan/public_html/monitor/data/H5monitorData.txt', true);
$buffer1 = preg_split("/:/", $file1, -1, PREG_SPLIT_NO_EMPTY);
$data .= "<small><table  class='table'>";
//Server name
//Data toggle
$data .="<tr  class='parent' id='2826'><td ><span class='btn btn-default'><b>H5</b></span></td>";
//HTTP status
$httpStatus = stream_socket_client("209.205.214.18:80", $errno, $errstr, 30);
	if (!$httpStatus) {
		$data .= "<td><b>Offline</b></td>";
	  fclose($httpStatus);
	} else {
		$data .= "<td><b>Online</b></td>";
		fclose($httpStatus);
	}
//MySQL status
// Find the correct MySql port number.
$mysqlStatus = stream_socket_client("209.205.214.18:3306", $errno, $errstr, 30);

	if (!$mysqlStatus) {
		$data .= "<td style='color:red;'><b>Offline</b></td>";
	  fclose($mysqlStatus);
	} else {
		$data .= "<td><b>Online</b></td>";
		fclose($mysqlStatus);
	}
//SERVER LOAD
$load = substr($buffer1[15], 0, 4);
if($load >= 20){
$loadColor = 'red';
}
$data .= "<td style='color: $loadColor;'><b>$load</b></td>";
//Available backups Dates
$availableBackupDates =availableBackupDates();
$data .= "<td><b>$availableBackupDates</b></td>";
//Home Diskspace used
$homeValue = preg_replace('/[^0-9]/', '', $buffer1[19]); // remove the percentage from the value;
if ($homeValue >= 75 ){
    $homeValueColor = 'red';
}
$data .= "<td style='position: relative; right: 80px; style='color: $homeValueColor;'><b>".$homeValue."%</b></td>";


//Backup Disk Space used
$backupValue = preg_replace('/[^0-9]/', '', $buffer1[1]); // remove the percentage from the value;
if ($backupValue >= 90 ){
    $backupValueColor = 'red';
}
$data .= "<td style='color: $backupValueColor; position: relative; right: 85px;'><b>".$backupValue ."%</b></td>";
$backupValue = "";
//Boot partition used
$bootValue = preg_replace('/[^0-9]/', '', $buffer1[17]); // remove the percentage from the value;
if ($bootValue >= 90 ){
    $bootValueColor = 'red';
}
$data .= "<td class='text-center' style='color: $bootValueColor; position: relative; right: 110px;'><b>".$bootValue . "%</b></td>";

//Var Partition used
$varValue = preg_replace('/[^0-9]/', '', $buffer1[21]); // remove the percentage from the value;
if ($varValue >= 90 ){
    $varValueColor = 'red';
}
$data .= "<td class='text-center' style='color: $varValueColor; position: relative; right: 85px;'><b>".$varValue . "%</b></td>";

//Swap Diskspace used
if (!empty($buffer1[3])){
$swap = $buffer1[3];
$data .= "<td class='text-center' style='position: relative; right: 55px;'><b>$swap</b></td>";
}
else{$swap = "Error";}

//Mail Queu
if (!empty($buffer1[5])){
$mailQueue = $buffer1[5];
$data .= "<td style='position: relative; right: 20px;'><b>". $mailQueue . "</b></td>";
}
else{$mailQueue = "Error";}
//Delivered mail
if (!empty($buffer1[9])){
$deliMail = $buffer1[9];
$data .= "<td><b>$deliMail</b></td>";
}
else{$deliMail = "Error";}
//free memory
$data .= "<td><b>". $buffer1[11] . "</b></td>";
//CPU usage
$data .= "<td><b>".  $buffer1[13] . "</b></td></tr>";
//close table
//$data .= "</table></small>";
//$data .= '
//  </div>
//</div>
//';
$data .= "<tr class='child-2826' style='display: none;'>" ."<td><form  action='search.php' method='GET' name='H5' onsubmit='return sanitize()' > <label for='username'><b>Backup Search :</b></label>
<input type='text' name='query' id='userinput' onfocus='removeSpace()' class='border-primary rounded' placeholder='type username...' /><input type='submit' value='Search' />
<h6>ns5.smartwebng.com   IP: 209.205.214.18 <br>ns6.smartwebng.com IP: 209.205.214.19 </h6>
<input type='hidden' name='server' value='H5'></form></td></tr>";
echo $data;


////////////////////////////// H6 //////////////////////////////////
 //Reset and initialiaze variables
$timeout =1;$file1 = "";$buffer1 = "";$data="";$fp="";$errno="";$errstr="";$timeout="";$load="";$loadColor="";$availableBackupDates="";$disk_space="";$disk_free="";$disk_used_precent="";$disk_used_precent_color="";$backupValue="";$backupValueColor="";$bootValue="";$bootValueColor="";$swap ="";$mailQueue="";$deliMail=""; $homeValueColor="";$homeValue="";$httpStatus="";$mysqlStatus="";$varValue="";$varValueColor="";
 
//Get the shell output file from "monitorData.txt"
 $file1 = file_get_contents('/home/ogbonnan/public_html/monitor/data/H6monitorData.txt', true);
$buffer1 = preg_split("/:/", $file1, -1, PREG_SPLIT_NO_EMPTY);
$data .= "<small><table  class='table'>";
//Server name
//Data Toggle
$data .="<tr  class='parent' id='2830'><td ><span class='btn btn-default'><b>H6</b></span></td>";
//HTTP status
$httpStatus = stream_socket_client("66.23.236.66:80", $errno, $errstr, 30);
	if (!$httpStatus) {
		$data .= "<td title='Apache server'><b>Offline</b></td>";
	  fclose($httpStatus);
	} else {
		$data .= "<td title='Apache server'><b>Online</b></td>";
		fclose($httpStatus);
	}
//MySQL status
// Find the correct MySql port number.
$mysqlStatus = stream_socket_client("66.23.236.66:3306", $errno, $errstr, 30);

	if (!$mysqlStatus) {
		$data .= "<td style='color:red;' title='Database server'><b>Offline</b></td>";
	  fclose($mysqlStatus);
	} else {
		$data .= "<td title='Database server'><b>Online</b></td>
";
		fclose($mysqlStatus);
	}
//SERVER LOAD
$load = substr($buffer1[15], 0, 4);
if($load >= 20){
$loadColor = 'red';
}
$data .= "<td style='color: $loadColor;' title='Server Load'><b>$load</b></td>";
//Available backups Dates
$availableBackupDates =availableBackupDates();
$data .= "<td title='Available Backups'><b>$availableBackupDates</b></td>";
//Home Diskspace used
$homeValue = preg_replace('/[^0-9]/', '', $buffer1[19]); // remove the percentage from the value;
if ($homeValue >= 75 ){
    $homeValueColor = 'red';
}
$data .= "<td style='position: relative; right: 80px; style='color: $homeValueColor;' title='Home Disk Space'><b>".$homeValue."%</b></td>";


//Backup Disk Space used
$backupValue = preg_replace('/[^0-9]/', '', $buffer1[1]); // remove the percentage from the value;
if ($backupValue >= 90 ){
    $backupValueColor = 'red';
}
$data .= "<td style='color: $backupValueColor; position: relative; right: 85px;' title='Backup Disk Space'><b>".$backupValue ."%</b></td>";
$backupValue = "";
//Boot partition used
$bootValue = preg_replace('/[^0-9]/', '', $buffer1[17]); // remove the percentage from the value;
if ($bootValue >= 90 ){
    $bootValueColor = 'red';
}
$data .= "<td class='text-center' style='color: $bootValueColor; position: relative; right: 110px;' title='Boot partition Diskspace'><b>".$bootValue . "%</b></td>";

//Var Partition used
$varValue = preg_replace('/[^0-9]/', '', $buffer1[21]); // remove the percentage from the value;
if ($varValue >= 90 ){
    $varValueColor = 'red';
}
$data .= "<td class='text-center' style='color: $varValueColor; position: relative; right: 85px;' title='Var partition Disk space'><b>".$varValue . "%</b></td>";

//Swap Diskspace used
if (!empty($buffer1[3])){
$swap = $buffer1[3];
$data .= "<td class='text-center' style='position: relative; right: 55px;' title='Swap patition Disk Space'><b>$swap</b></td>";
}
else{$swap = "Error";}

//Mail Queu
if (!empty($buffer1[5])){
$mailQueue = $buffer1[5];
$data .= "<td style='position: relative; right: 20px;' title='Mail queue'><b>". $mailQueue . "</b></td>";
}
else{$mailQueue = "Error";}
//Delivered mail
if (!empty($buffer1[9])){
$deliMail = $buffer1[9];
$data .= "<td title='Mail Delivered in 24h'><b>$deliMail</b></td>";
}
else{$deliMail = "Error";}
//free memory
$data .= "<td title='Physical Memory'><b>". $buffer1[11] . "</b></td>";
//CPU usage
$data .= "<td title='CPU'><b>".  $buffer1[13] . "</b></td></tr>";
//close table
//$data .= "</table></small>";
//$data .= '
//  </div>
//</div>
//';
$data .= "<tr class='child-2830' style='display: none;'>" ."<td><form  action='search.php' method='GET' name='H6' onsubmit='return sanitize()' > <label for='username'><b>Backup Search :</b></label>
<input type='text' name='query' id='userinput' onfocus='removeSpace()' class='border-primary rounded' placeholder='type username...' /><input type='submit' value='Search' />
<h6>ns31.smartwebng.com IP: 66.23.236.66 <br> ns32.smartwebng.com IP: 66.23.236.67 </h6>
<input type='hidden' name='server' value='H6'></form></td></tr>";
echo $data;

//close table
$data .= "</table></small>";
$data .= '
  </div>
</div>
';
            //FUNCTIONS
function badge($str, $type){
	return "<span class='badge badge-" . $type . " ' >$str</span>";
}

//Get available backup dates
function availableBackupDates(){
$allBackupDates = "";
global $buffer1;
$backups = preg_split("/[\s,]+/", $buffer1[7]);
if (!empty($backups[1]))
{
 //For first available backupdate
  $monthNo =  preg_split("/-/", $backups[1]);
  $dayNo = $monthNo[2];
 //convert month number to name
$month = date("F", mktime(0, 0, 0, $monthNo[1], 10));
$backupDate = substr($month, 0, 3)." ".$dayNo;
$allBackupDates = $backupDate.",";
//$data .= "<td><b>$backupDate</b></td>";
}
else{$allBackupDates = "";}

if (!empty($backups[2]))
{
     //For second available backupdate
  $monthNo =  preg_split("/-/", $backups[2]);
  $dayNo = $monthNo[2];
 //convert month number to name
$month = date("F", mktime(0, 0, 0, $monthNo[1], 10));
$backupDate = substr($month, 0, 3)." ".$dayNo;
$allBackupDates .= $backupDate.",";
//$data .= "<td><b>$backupDate</b></td>";
}
else{$allBackupDates .= "";}


if (!empty($backups[3]))
{
     //For third available backupdate
  $monthNo =  preg_split("/-/", $backups[3]);
  $dayNo = $monthNo[2];
 //convert month number to name
$month = date("F", mktime(0, 0, 0, $monthNo[1], 10));
$backupDate = substr($month, 0, 3)." ".$dayNo;
$allBackupDates .= $backupDate;

//$data .= "<td><b>$backupDate</b></td>";
}
else{$allBackupDates .= "";}

return $allBackupDates;
}


function arrayLoop($theArray)
{
    $arrlength = count($theArray);

for($x = 0; $x < $arrlength; $x++) {
    return $theArray[$x];
}
}

function url_get_contents ( $url ) {
    if ( ! function_exists( 'curl_init' ) ) {
        die( 'The cURL library is not installed.' );
    }
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_URL, $url );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    $output = curl_exec( $ch );
    curl_close( $ch );
    return $output;
}

function find_value() {
// $input is the word being supplied by the user
$handle = @fopen("/home/ogbonnan/public_html/monitor/monitorData.txt", "r");
if ($handle) {
  while (!feof($handle)) {
    
$result=preg_split(':',fgets($handle));
   
return $result;
    }
  fclose($handle);
  }
}

function  initialize()
{
$timeout =1;
$file1 = "";
$buffer1 = ""; 
$data = "";
$fp = "";
$errno = "";
$errstr = "";
$timeout = "";
$load = "";
$loadColor = "";
$availableBackupDates = "";
$disk_space = "";
$disk_free = "";
$disk_used_precent = "";
$disk_used_precent_color = "";
$backupValue = "";
$backupValueColor = "";
$bootValue = "";
$bootValueColor = "";
$swap = "";
$mailQueue = "";
$deliMail = "";

}


?>

	<script>
function removeSpace() {
 //var element = DocumentOrShadowRoot.activeElement;
 //alert(element);
  //document.getElementById("userinput").style.backgroundColor = "red";
 // https://developer.mozilla.org/en-US/docs/Web/API/DocumentOrShadowRoot/activeElement
}
</script>


</body>
</div></html>


