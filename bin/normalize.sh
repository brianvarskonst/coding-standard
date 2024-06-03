#!/bin/bash
normalize() {
    xmllint --format "$1" |
    sed -e 's/>\s*</></g' \
        -e 's/\s\+/ /g' \
        -e 's/^ *//' \
        -e 's/ *$//'
}

normalize "$1"
