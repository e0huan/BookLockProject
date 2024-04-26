#!/bin/sh
cd ./htdocs
LD_LIBRARY_PATH=../lib ../lib/ld-linux-x86-64.so.2 ../php $@
