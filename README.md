### Simple Nagios Dashboard

<a href="http://i.imgur.com/vlOAPfB.png"><img src="http://i.imgur.com/vlOAPfB.png" width="480" height="250" /></a>

<a href="http://i.imgur.com/Cs6bGrL.png"><img src="http://i.imgur.com/Cs6bGrL.png" width="480" height="250" /></a>

Simple Nagios Dashboard is a simple php based Nagios Dashboard. It is intended for a wall mounted monitor. It displays all host and service warnings. 

It requires that the Nagios status.dat file is available as JSON, it includes a module for that.

Setup is very simple, only PHP 5 is required. No database or whatsoever.

### Installation

- Extract code on Nagios Server
- Edit `$statusFile` in `json.php` to point to the location of your nagios status.dat file
- Install cronjob to generate JSON file:  


	    # /etc/cron.d/nagios-json
	    * * * * * root /usr/bin/php5 /var/www/json.php > /var/www/nagios.json.tmp; cp /var/www/nagios.json.tmp /var/www/nagios.json


- Extract code on webserver
- Edit  `config.php` and fill in your specific names and locations
- ???
- Visit page in web browser and PROFIT!!!


### Credits

- [PHP Nagios JSON by Christian Lizell](https://github.com/lizell/php-nagios-json) - GPLv3
- [Job van der Voort](https://github.com/JobV), Design
- Twitter Bootstrap 3
