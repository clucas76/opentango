# opentango
OpenTango Ethernet / IP / TCP-UDP network testing tool
======================================================
1. Why ?
--------
This tool is based on Debian Live. It is a Network testing tool USB key.
We are praticing some French field technician who are not able to use BERT / RFC2544 tests and we realize that a cheap tool can be used for some kind of tests. 
So that we are able to make a kind of clean metrology test, we have choosen to boot a Linux on a fresh field technician PC to avoid some crappy OS ;)

You are able to make : 
 * Ethernet testing tool (based an etherate ()) ;
 * IP testing (based on bwping) ;
 * TCP / UDP (based on iperf / iperf3) ;
 * QOS ;
It is able to visualize the test and save it automatically on a FAT32 partition on the key.

How ?
-----
You will find a 'live-build' repository. 
How to build it : apt-get install live-build ...

i  live-boot                            4.0.2-1                                    all          Live System Boot Components
ii  live-boot-doc                        4.0.2-1                                    all          Live System Boot Components (documentation)
ii  live-boot-initramfs-tools            4.0.2-1                                    all          Live System Boot Components (initramfs-tools backend)
ii  live-build                           4.0.3-1                                    all          Live System Build Components
ii  live-config                          4.0.4-1                                    all          Live System Configuration Components
ii  live-config-doc                      4.0.4-1                                    all          Live System Configuration Components (documentation)
ii  live-config-sysvinit                 4.0.4-1                                    all          Live System Configuration Components (sysvinit backend)
ii  live-manual-html                     1:4.0.1-1                                  all          Live Systems Documentation (html)
ii  live-tools                           4.0.2-1.1                                  all          Live System Extra Components


'lb build' will produce an ISO you will be able to push on your usb key. I don't have script it for now. 

dd if=your.iso of=/dev/sdX

After this you must add an FAT32 partition with some tool such as cfdisk (mkfs.vfat -n OPENTANGO /dev/sdXY). 
You must hide your FAT32 partition and make the Linux partition bootable.

Now, you must be able to boot your USB key (you must verify USB is bootable on your BIOS settings).

Future :
--------
We will write it together and under GPLv3 terms ;)

Thanks :
--------
Thanks to my friend Ludo for all these hours on our lunch time to work on this.
You can visit : http://www.opentango.net to see what it can produce.

Have fun :)

Christophe 
