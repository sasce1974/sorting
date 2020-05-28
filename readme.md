### SORTING ASSIGNMENT
######Date: 05/28/2020
######Done by: Aleksandar Ardjanliev

#### RUNNING THE PROGRAM ON A WEB SERVER

To use the program on a local web server, please unzip the folder **sorting** into a public
web server directory and open with a browser _(eg. http://localhost/sorting/)_.

If using cURL to run, please open the page **raw.php** in the same path
_(eg. curl http://localhost/sorting/raw.php)_ for non HTML output.

By lunching the program _(index.php or raw.php)_, loading of the text files from
the **source/** directory, sorting, saving the outcome to **output.txt** file and printing to
browser are done automatically.


#### RUNNING THE PROGRAM WITH DOCKER

To use the program with Docker containers, please unzip the folder **sorting**. On windows machines
it SHOULD be placed to the Users directory _(eg. C:\Users\{UserName}\docker)_. 
Files **docker-compose.yml**, **Dockerfile** and **nginx/default.conf** are included for 
configuration of the containers.

**Prerequisites**: Docker, Internet connection.

To install the containers please enter the sorting directory with the CI and run the command:

    docker-compose build

After installation, run:

    docker-compose up -d

It will download and run **PHP** and **NGINX** images, then create containers. Nginx will use
the port 80 (will serve on localhost) so if there is already running web server on this port,
it SHOULD be turned off.

The program then CAN be used in a local environment on a same manner as described in the 
RUNNING THE PROGRAM ON A WEB SERVER.

To stop the docker containers, run:

    docker-compose down


