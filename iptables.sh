#! /bin/bash
iptables -A INPUT -p tcp --match multiport --dports 30000:32767 -j ACCEPT
iptables -A INPUT -p tcp --match multiport --dports 80,443 -j ACCEPT
iptables -A INPUT -p tcp --match multiport --dports 10250:10252 -j ACCEPT
iptables -A INPUT -p tcp --match multiport --dports 6443 -j ACCEPT
iptables -A INPUT -p tcp --match multiport --dports 2379:2380 -j ACCEPT