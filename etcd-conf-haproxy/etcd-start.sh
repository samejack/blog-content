#!/bin/bash
THIS_IP=`ifconfig eth0 | grep "inet addr" | cut -d ':' -f 2 | cut -d ' ' -f 1`
/usr/sbin/etcd --data-dir=data.etcd \
              --name ${HOSTNAME} \
              --advertise-client-urls "http://${THIS_IP}:2379" \
              --listen-client-urls 'http://0.0.0.0:2379'
