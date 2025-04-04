REM this will generate and copy all themes in the project,
REM run it in CMD< requires node.js, npm and lessec js for node.js

REM run build.bat having while having your terminal current directory in theme-builder

call xcopy /s/e/y "includes" "../../themes/blue/*" 
call xcopy /s/e/y "includes" "../../themes/brown/*" 
call xcopy /s/e/y "includes" "../../themes/gold/*" 
call xcopy /s/e/y "includes" "../../themes/green/*" 
call xcopy /s/e/y "includes" "../../themes/maroon/*" 
call xcopy /s/e/y "includes" "../../themes/orange/*" 
call xcopy /s/e/y "includes" "../../themes/pink/*" 
call xcopy /s/e/y "includes" "../../themes/purple/*" 
call xcopy /s/e/y "includes" "../../themes/red/*" 
call xcopy /s/e/y "includes" "../../themes/royal-blue/*" 
call xcopy /s/e/y "includes" "../../themes/teal/*" 

call lessc build-blue.less ../../themes/blue/styles/main/framework.css 
call lessc build-brown.less ../../themes/brown/styles/main/framework.css 
call lessc build-gold.less ../../themes/gold/styles/main/framework.css 
call lessc build-green.less ../../themes/green/styles/main/framework.css 
call lessc build-maroon.less ../../themes/maroon/styles/main/framework.css 
call lessc build-orange.less ../../themes/orange/styles/main/framework.css 
call lessc build-pink.less ../../themes/pink/styles/main/framework.css 
call lessc build-purple.less ../../themes/purple/styles/main/framework.css 
call lessc build-red.less ../../themes/red/styles/main/framework.css 
call lessc build-royal-blue.less ../../themes/royal-blue/styles/main/framework.css 
call lessc build-teal.less ../../themes/teal/styles/main/framework.css 