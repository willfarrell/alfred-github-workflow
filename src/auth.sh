#!/bin/bash
METHOD="GET";
JSON='{"scope":["repo"]}'

while true ; do
    case "$1" in
        -u )
            USERNAME=$2
            shift 2
        ;;
        --user )
            USERNAME=$2
            shift 2
        ;;
        -p )
            PASSWORD=$2
            shift 2
        ;;
        --pass )
            PASSWORD=$2
            shift 2
        ;;
        --url )
            URL=$2
            shift 2
        ;;
        -X )
            METHOD=$2
            shift 2
        ;;
        --method )
            METHOD=$2
            shift 2
        ;;
        --proxy )
            PROXY_PARAMETER="-x $2"
            shift 2
        ;;
        *)
            break
        ;;
    esac 
done;

expect -c "
spawn curl $PROXY_PARAMETER -i -X $METHOD -d $JSON -u $USERNAME $URL
expect \"Enter host password for user '$USERNAME'\"
send \"$PASSWORD\r\" 
interact "