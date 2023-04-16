#!/bin/bash
CF_TOKEN={YOUR_API_TOKEN}
CF_ZONE_ID={YOUR_ZONE_ID}
CF_RECORD_ID={YOUR_RECORD_ID}
DNS={YOUR_DOMAIN}

INTERNET_IP=`curl -s http://ipv4.icanhazip.com`
INTERFACE_IP=`ip address show ppp0 | grep ppp0 | grep global | awk '{print$2}'`
DNS_RECORD_IP=`dig ${DNS} | grep "${DNS}" | grep -v ';' | awk '{print$5}'`

if [ "$INTERNET_IP" != "$DNS_RECORD_IP" ]
then
  echo "Renew IP: ${DNS_RECORD_IP} to ${INTERNET_IP}"
  curl -X PUT "https://api.cloudflare.com/client/v4/zones/${CF_ZONE_ID}/dns_records/${CF_RECORD_ID}" \
    -H "Authorization: Bearer ${CF_TOKEN}" \
    -H "Content-Type: application/json" \
    --data '{"type":"A","name":"'${DNS}'","content":"'${INTERNET_IP}'","ttl":120,"proxied":false}'
else
  echo "No change: ${INTERNET_IP}"
fi
