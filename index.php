
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

////////////////////////////////////////////Server 1 //////////////////////////
//Reset and initialiaze variables
$timeout =1;$file1 = "";$buffer1 = "";$data="";$fp="";$errno="";$errstr="";$timeout="";$load="";$loadColor="";$availableBackupDates="";$disk_space="";$disk_free="";$disk_used_precent="";$disk_used_precent_color="";$backupValue="";$backupValueColor="";$bootValue="";$bootValueColor="";$swap ="";$mailQueue="";$deliMail=""; $homeValueColor="";$homeValue="";$httpStatus="";$mysqlStatus="";$varValue="";$varValueColor="";
 
//Get the shell output file from "monitorData.txt"
 $file1 = file_get_contents('/home/username/public_html/monitor/data/E1monitorData.txt', true);
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

////////////////////////////////////////////server 2...(repeate for different servers)//////////////////////////
 
 //Reset and initialiaze variables
$timeout =1;$file1 = "";$buffer1 = "";$data="";$fp="";$errno="";$errstr="";$timeout="";$load="";$loadColor="";$availableBackupDates="";$disk_space="";$disk_free="";$disk_used_precent="";$disk_used_precent_color="";$backupValue="";$backupValueColor="";$bootValue="";$bootValueColor="";$swap ="";$mailQueue="";$deliMail=""; $homeValueColor="";$homeValue="";$httpStatus="";$mysqlStatus="";$varValue="";$varValueColor="";
 
//Get the shell output file from "monitorData.txt"
 $file1 = file_get_contents('/home/username/public_html/monitor/data/H6monitorData.txt', true);
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
$handle = @fopen("/home/username/public_html/monitor/monitorData.txt", "r");
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


