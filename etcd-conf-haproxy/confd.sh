#!/bin/bash

/usr/sbin/confd -interval 10 -node '${ETCD_HOST}:2379' -confdir /etc/confd
