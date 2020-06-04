#!/bin/bash
INPUT=exposicion.md
OUTPUT_PDF=exposicion.pdf
OUTPUT_HTML=exposicion.html
OUTPUT_SLIDES=exposicion_p.html

pandoc $INPUT -o $OUTPUT_PDF\
    --template ./templates/eisvogel.tex\
    --listings\
    --number-sections\
    --tab-stop 2 -s\
    --toc\
    --toc-depth=5\
    -V titlepage=true\
    -V toc-own-page=true\
    -V classoption=oneside\
    -V mainfont="SourceSansPro-Regular"\
    -V mainfontoptions="Scale=1.0"\
    -V fontsize=10pt\
    -V colorlinks\
    -V thanks\
    -V subparagraph
    
pandoc $INPUT -o $OUTPUT_HTML\
    --template ./templates/github.html5\
    --self-contained\
    --listings\
    --number-sections\
    --tab-stop 2 -s\
    --toc --toc-depth=5

pandoc $INPUT -o $OUTPUT_SLIDES\
    -t slidy\
    --self-contained\
    --listings\
    --number-sections\
    --tab-stop 2 -s\
    --toc --toc-depth=5