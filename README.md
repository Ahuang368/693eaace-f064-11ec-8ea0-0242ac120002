# Introduction
This CLI Application is used for generate 3 types of reports, which are Progress Report, Diagnostic Report and Feedback report. With 2 user input values, Student ID and options of the report selected, this Application will validate the input data. When those data fulfill the requirements, one of the three report will output on console, otherwise, an error message will be displayed. 

# Development
## Environment Setup
I am using Visual Studio Code as the IDE. I also use Docker, Composer and php. 

Here are the reference details:
1. VS Code, download link: https://code.visualstudio.com/
2. Docker Desktop, download link: https://www.docker.com/get-started/
   Documentation, https://docs.docker.com/get-started/
3. Composer, download link: https://getcomposer.org/
   Documentation, https://getcomposer.org/doc/
4. PHP, https://www.php.net/
5. PHPUnit, https://phpunit.de/   

Under the Application directory, run the command lines below for setup and run the Application:
    Install from command: 'docker-compose run build'
    Generate Report from command: 'docker-compose run app'
    Run automated test from command: 'docker-compose run test'

# ToDO
1. Use GitHub Actions to run tests
2. Improve the code coverage in Unit Tests