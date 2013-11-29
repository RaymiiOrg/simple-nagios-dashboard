<?php
# Copyright (C) 2013 Remy van Elst
#
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
# You should have received a copy of the GNU General Public License
# along with this program. If not, see <http://www.gnu.org/licenses/>.
require_once("./functions.php");
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php print($title . " - " . $organization); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Remy van Elst">
    <meta http-equiv="refresh" content="30">
    <link href="//netdna.bootstrapcdn.com/bootswatch/3.0.2/flatly/bootstrap.min.css" rel="stylesheet">
    <style type="text/css">
        .alert { 
            padding:5px 13px !important;
            border-radius: 0px !important;
            margin-bottom: 2px !important;
        }
    </style>
</head>
<body>

    <div class="row">
        <div class="col-md-12">
            <h1><?php print($title . " - " . $organization); ?></h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?php
            print($hosts_total . " Hosts");
            print("<br />" . $service_total . " Services<br />");
            if($criticals_count > 0 || $host_issue_count > 0) {
                print('<div class="alert alert-danger"><h2>');
                    #print('<script type="text/javascript">document.body.style.backgroundColor = "#ff0039";</script>');
                if($criticals_count > 0) {
                    print(($criticals_count));
                    print(" Critical Issues. ");
                }
                if ($host_issue_count > 0) {
                 print($host_issue_count . " Hosts Down!");
             } 
             print("<h2></div>");
         } elseif(($warnings_count) > 0) {
            print(($warnings_count));
            print(" Non Critical Issues</h2></div>");
        } else {
            print('<div class="alert alert-success"><h2>');
            print(" Everything Running Fine!</h2></div>");
        }
        ?>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <?php
        host_alert_cards("Hosts Down", "danger", $host_issue_count, $host_issues);
        service_alert_cards("Critical Issues", "danger", $criticals_count, $criticals);
        host_alert_cards("Hosts Acknowledged Down", "info", $host_ack_issues_count, $host_ack_issues);
        ?>
    </div>
    <div class="col-md-6">
        <?php
        service_alert_cards("Minor Issues ", "warning", $warnings_count, $warnings);
        service_alert_cards("Acknowledged Minor Issues", "info", $warnings_ack_count, $warnings_ack_issues);
        service_alert_cards("Acknowledged Criticals", "info", $criticals_ack_count, $criticals_ack);
        ?>
    </div>
    <div class="col-md-6">
        <?php
        ?>
    </div>
</div>

<div class="row">
    <div class="container text-center">
    <span>Simple Nagios Dashboard by <a href="https://raymii.org">Remy van Elst (code)</a> and <a href="https://github.com/JobV">Job van der Voort (design)</a>.</span>
    </div>
</div>


</body>
</html>
