#!/bin/bash 

#Note: create a folder near this script, put any *.gz files into it and run ./md5_gen.sh FOLDER_NAME

if [ $# -eq 0 ]; then
    echo "usage: $0 [path/]";
    else
        #Read folder content
	ls -1 "$1/" | grep .gz | grep -v md5 > "$1/list.txt"
	
        #Remove old *.md5 files
	find ./$1/* -name "*.md5" |  sed 's/^/"/' | sed 's/$/"/' | xargs rm -rf
	
        #Process each file
	while read line
        do
	md5sum "$1/$line"
	md5sum "$1/$line" | egrep -o "^[^ ]+" > "$1/$line.md5"
        done < "$1/list.txt"

	#Remove temporary files
        rm "$1/list.txt"
fi