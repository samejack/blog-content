#!/bin/bash

_term() {
 echo "Caught SIGTERM signal and remove etcd key..."
 curl -L http://${ETCD_HOST}:2379/v2/keys/push/servers/server-${HOSTNAME} -XDELETE
 kill -TERM "$child" 2>/dev/null
}

trap _term SIGTERM

# Start your service at here...

child=$!
wait "$child"
