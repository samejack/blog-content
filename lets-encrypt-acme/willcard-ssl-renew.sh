#!/bin/bash

export CF_Key="YOUR_CLOUD_FLARE_API_KEY"
export CF_Email="YOUR_CLOUD_FLARE_LOGIN_EMAIL"

DOMAIN=your-domain.com

mkdir -p /etc/letsencrypt/keys
mkdir -p /etc/letsencrypt/live/${DOMAIN}

/root/.acme.sh/acme.sh --issue -d "${DOMAIN}" -d "*.${DOMAIN}" --dns dns_cf \
  --key-file /etc/letsencrypt/keys/${DOMAIN}.key \
  --cert-file /etc/letsencrypt/live/${DOMAIN}/cert.pem \
  --fullchain-file /etc/letsencrypt/live/${DOMAIN}/fullchain.pem \
  --ca-file /etc/letsencrypt/live/${DOMAIN}/chain.pem \
  --reloadcmd "service haproxy restart"
