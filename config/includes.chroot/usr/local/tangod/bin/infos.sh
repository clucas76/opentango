#!/bin/sh

while [ 1 -eq 1 ];
do
	> /tmp/infos.txt
	echo "INFORMATIONS :" >> /tmp/infos.txt
	echo "==============" >> /tmp/infos.txt

	echo "" >> /tmp/infos.txt
	echo "NEGOCIATION : " >> /tmp/infos.txt
	nego=`mii-tool eth0`
	echo $nego >> /tmp/infos.txt

	echo "" >> /tmp/infos.txt
	echo "VLAN :" >> /tmp/infos.txt
	vlan=`ifconfig | grep 'eth0\.'`
	if [ "X$vlan" = "X" ]; then
		echo "vlan : natif" >> /tmp/infos.txt
	else 
		ifconfig | grep 'eth0\.' >> /tmp/infos.txt
	fi

	echo "" >> /tmp/infos.txt
	echo "IP :" >> /tmp/infos.txt
	ip addr | grep eth0 >> /tmp/infos.txt

	echo "" >> /tmp/infos.txt
	echo "ROUTES :" >> /tmp/infos.txt
	ip route >> /tmp/infos.txt 

	chown www-data.www-data /tmp/infos.txt

	sleep 10
done
exit 0
