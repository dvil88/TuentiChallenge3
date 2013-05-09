Author:			Diego Villar Sancho
Language:		PHP
Dependencies:	pcntl
				gmp


Here you have the original compressed files and the original PHP files 
sent in the submit phase of every challenge, additionally there are 2 
more files:	

# test16.php
=============================
File used initially in the Turing Machine, it creates the machine 
automatically from a states array and executes it with the input.
In the final solution the machine was analyzed to know what it really
does and was simplified to use decimal numbers and increase its 
eficiency.

#videoProcesser.php
=============================
This file was used to process Challenge #17, it separates the frames
of the video in images and then get the color in the position of one
light, after getting the binary string it converts it to ascii chars
and use a regex to get host and cookie to make the HTTP request and 
get the statement.


Dependencies:
=============================
PCNTL: threads are used in challenge #4 to process the file in 4
separate files, so this extension is needed to run this script.

GMP: In challenge #17 we need to calculate the factorial of a given 
number, doing it manually its very slow and expensive, so GMP is used
to calculate this number because you can get it instantly, even with 
very big numbers.