#/bin/bash

KEEP_DAYS=30
ELASTICSEARCH_HOST='http://elasticsearch:9200'
DATE_THRESHOLD=`date -d "${KEEP_DAYS} days ago" +%Y/%m/%d`
TIME_THRESHOLD=`date -d "${DATE_THRESHOLD}" +%s`

INDEX_LIST=`curl -s -k ${ELASTICSEARCH_HOST}/_cat/indices?s=index | grep 'logstash' | awk '{print$3}'`

echo "DATE_THRESHOLD=${DATE_THRESHOLD}"

for INDEX_NAME in ${INDEX_LIST}
do
    INDEX_DATE=`echo "${INDEX_NAME}" | sed -e 's/\./\//g' | sed -e 's/logstash-//g'`
    INDEX_TIME=`date -d "${INDEX_DATE}" +%s`
    if [ ${TIME_THRESHOLD} -gt ${INDEX_TIME} ]; then
        echo "Remove Index: ${INDEX_NAME}"
        curl -XDELETE -k ${ELASTICSEARCH_HOST}/${INDEX_NAME}
    fi
done
