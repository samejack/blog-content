#!/bin/bash

# wait for etcd start
while ! nc -z ${ETCD_HOST} 2379; do
 echo "Waiting for etcd start..."
 sleep 1
done

# registe srvice hostname into etcd
curl -L http://${ETCD_HOST}:2379/v2/keys/app/servers/server-${HOSTNAME} -XPUT -d value="${HOSTNAME}"
