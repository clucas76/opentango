#!/bin/bash

tx=`cat /sys/class/net/eth0/statistics/tx_bytes`
rx=`cat /sys/class/net/eth0/statistics/rx_bytes`

echo -n $rx" "$tx

