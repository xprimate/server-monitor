#monitor.sh v1.4
#!/bin/bash

#Author: Jude Ogbonna
#Description: Server Monitoring Script

#backupSpace
backupSpace=$(df /backup | awk 'END { print $5}')
echo ":Backup Space:$backupSpace"

#Percentage of Swap space
usedSwap=$(free -m | grep 'Swap' | awk '{print $3}')
if [ $usedSwap -eq 0  ]
then
swap=0
echo ":Swap:$swap%"
else
swap=$(free | grep 'Swap' | awk '{print ($3/$2)*100}' | awk '{print int($1+0.5)}')
echo ":Swap:$swap%"
fi
#Exim mail Queue
mailQueue=$(/usr/sbin/exim -bpc)
echo ":Mail Queue:$mailQueue"

#Available Backups
backupDate=$(/usr/local/cpanel/bin/whmapi1 backup_date_list | grep "-" | awk '{b1 = $2; b2 = $3; print b1 }')
echo ":Available Backup:$backupDate"

#Get delivered masseage count within 24 hours.
unixTime=$(date +"%s")
oneDay=86400
((aDay = unixTime - oneDay))
outbound=$(/usr/local/cpanel/bin/whmapi1 emailtrack_stats starttime=$aDay endtime=$unixTime)

echo "$outbound" | grep SUCCESSCOUNT | awk '{a = $2; print ":Delivered Mail:" a }'

#Used RAM
TOTAL_MEM=`free | grep Mem | awk '{print $2}'`
USED_MEM=`free | grep Mem | awk '{print $3}'`
PERCENT=$(awk "BEGIN {printf \"%.2f\",($USED_MEM/$TOTAL_MEM)*100}")
echo ":Used RAM:$PERCENT%"

#Used CPU
usedCPU=$(awk '/cpu /{print 100*($2+$4)/($2+$4+$5)}' /proc/stat)
echo ":CPU usage:$usedCPU%"

#Server load
load=$(uptime | grep -ohe 'load average[s:][: ].*' | awk '{ print $3 }')
echo ":Server load:$load"

#boot partition space
bootSpace=$(df | grep 'boot' | awk {'print $5'})
echo ":Boot Space:$bootSpace"

#Home  partition space
homeSpace=$(df | grep -w "/" | awk {'print $5'})
echo ":Home Space:$homeSpace"

#Var  partition space
varSpace=$(df | grep -w "/tmp" | awk {'print $5'})
echo ":Var Space:$varSpace"

#echo ":clamd:"$(systemctl is-active clamd);
#echo ":cpanel-dovecot-solr:"$(systemctl is-active cpanel-dovecot-solr);

#List available backedup users every 6  hourse
currentHour=$(date +%R | colrm 3)
if (( ( $currentHour==00 || $currentHour==06  || $currentHour==12 ) ))
 then
backedupUsers=$(/usr/local/cpanel/bin/whmapi1 backup_set_list | tac | awk 'NR>5' | head -n -4)
echo ":users:"$backedupUsers > /usr/local/sbin/backedupUsers
fi
