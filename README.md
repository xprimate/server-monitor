# server-monitor
For displaying server paramater(disk space, RAM, mail queu, CPU etc) of multiple servers on a website.
This is only compatible in cpanel server.

#GUIDE
The program is divided into two, the "server" folder and the "web" folder
The server folder will be created and configured on the server, you need a root access to do this.

On CentOS server, navigate to "usr/local/sbin"
Create the copy all the files in the "server" folder to the "usr/local/sbin/" directory
Ensure that "monitor.sh" and "transfer.sh" has the permission 0775 . you can do this by running "chmod 0775 monitor.sh" and "chmod 0775 transfer.sh"
You can rename "E3monitorData.txt" to any name but ensure that it the name is replaced everywhere in "transfer.sh" code.
Create a ftp host, this will recive the monitor data sent perioically.
When creating the ftp host, add "/data" to the ftp host directory. This will enable the data to be sent directly to the data directory in the "server" folder
Do these for different server you want to monitor

In the "transfer.sh" code replace "monitor@ftphost.com", "ftpPassword" and ftp://ftphost.com/ to the your ftp host username, passwor and ftp host name.
In the same ftp host, upload all the contents of the "web" folder to the public directory subfolder   on the account(usually public_html)
You can name the subfolder monitor
Do these for different server you want to monitor

create a cron job to execute every 5 minute, do the following from your command line:
EDITOR=nano crontab -e

When the cron tab opens, add the following code to execute "transfer.sh" every five minutes

*/5 * * * * /usr/local/sbin/transfer.sh
Do these for different server you want to monitor

type "Ctrl + o" to save and then type "Ctrl to x"  to exit.
Navigate to www.yourdomainname/monitor

You are done!





