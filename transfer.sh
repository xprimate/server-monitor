# Auhor: Jude Ogbonna
# Description: Executes monitor.sh , save the output to a file and tranfer the file to an ftp host

monitor.sh > /usr/local/sbin/D4monitorData.txt;
curl -u monitor@ogbonna.name.ng:Bfn5o$%X0Z3$ -T /usr/local/sbin/D4monitorData.txt  ftp://209.205.207.130
