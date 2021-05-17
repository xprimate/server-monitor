# Auhor: Jude Ogbonna
# Description: Executes monitor.sh , save the output to a file and tranfer the file to an ftp host

/usr/local/sbin/monitor.sh > /usr/local/sbin/E3monitorData.txt;
#Append the content of backedupUsers to E3monitorData.txt
cat /usr/local/sbin/backedupUsers >> /usr/local/sbin/E3monitorData.txt;
curl -u monitor@ftphost.com:ftpPassword -T /usr/local/sbin/E3monitorData.txt  ftp://ftphost.com/

