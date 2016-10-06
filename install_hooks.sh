#!/usr/bin/env bash

DIRECTORY="./.git/hooks/"

if [ ! -d "$DIRECTORY" ]; then
  mkdir $DIRECTORY
fi

file_directory="./hooks/"
files=("post-merge")

for i in "${files[@]}" 
do
  :
  cp $file_directory$i $DIRECTORY$i
done


