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
require_once('./config.php');

function data_to_json($url) {
    $json = file_get_contents($url);
    $data = json_decode($json, true);
    return $data;
}

$data = data_to_json($json_url);

if(empty($data)) {
    die("<html><head><title>Dashboard Error</title><meta name='author' content='Remy van Elst'><meta http-equiv='refresh' content='5'></head><body>JSON File $json_url could not be loadedi</body></html>");
}

$hosts = $data["hosts"];
$services = $data["services"];
$host = array();
foreach ($services as $key => $value) {
    $host["$key"] = $value;
}

function host_total($hosts) {
    $hosts_total = count($hosts);
    return $hosts_total;
}

function service_total($services) {
    $service_total = 0;
    $host = array();
    foreach ($services as $key => $value) {
        $host["$key"] = $value;
        $service_total += count($value);
    }
    return $service_total;
}

function alert_services($host, $state, $ack, $not) {
    global $extinfo_url;
    $alert_json = array();
    $alert_count = 0;
    foreach($host as $host_name => $services) {
        foreach($services as $service_name => $service_info) {
            if($service_info["current_state"] == $state && $service_info["problem_has_been_acknowledged"] == $ack && $service_info["notifications_enabled"] == $not) {
                $alert_json["$alert_count"]["service_name"] = $service_name;
                $alert_json["$alert_count"]["host_name"] = $host_name;
                $alert_json["$alert_count"]["plugin_output"] = $service_info["plugin_output"];
                $alert_json["$alert_count"]["url"] = $extinfo_url . "?type=2&host=" . str_replace(" ", "+", $host_name) . "&service=" . str_replace(" ", "+", $service_name);
                $alert_count += 1;
            }
        }
    }
    return $alert_json;
}

function service_alert_cards($severety, $card_type, $counter, $card_data) {
    $c_count = 1;
    print("<h2>" . $severety . "</h2>");
    if($counter == 0) {
        print("<div class='alert alert-success'>");
        print(str_repeat("<span class='glyphicon glyphicon-thumbs-up'> </span> &nbsp; ", 2));
        print("No " . $severety . "!");
        print("</div>");
    } else {
        foreach($card_data as $key => $card) {
            print("<div class='alert alert-" . $card_type . "'>");
            print("<a href='" . $card["url"] . "'>");
            print("#" . $c_count . " ");
            print("<b>" . $card["service_name"] . "</b>");
            print(" on ");
            print("<i>" . $card["host_name"] . "</i> ");
            print("</a>");
            print("" . $card["plugin_output"] . ".");
            print("</div>");
            $c_count += 1;
        }
    }
}

function host_issue_count($hosts, $state, $ack, $not) {
    $alert_count = 0;
    foreach($hosts as $key => $value) {
            if($value["current_state"] == $state && $value["problem_has_been_acknowledged"] == $ack && $value["notifications_enabled"] == $not) {
                $alert_count += 1;
            }
    }
    return $alert_count;
}

function alert_hosts($host, $state, $ack, $notif) {
    global $extinfo_url;
    $alert_json = array();
    $alert_count = 0;
        foreach($host as $key => $value) {
            if($value["current_state"] == "$state" && $value["problem_has_been_acknowledged"] == "$ack" && $value["notifications_enabled"] == "$notif") {
		#print($key . " - " . $value["current_state"] . " - " . $value["problem_has_been_acknowledged"] . " - " . $value["notifications_enabled"] . "<br />");
                $alert_json["$alert_count"]["host_name"] = $value["host_name"];
		switch ($value["current_state"]) {
		    case "1":
                	$alert_json["$alert_count"]["current_state"] = "Down";
			break;
		    case "2":
                	$alert_json["$alert_count"]["current_state"] = "Unknown";
			break;
		    default:
                	$alert_json["$alert_count"]["current_state"] = "Unknown!";
			break;
		}		
                $alert_json["$alert_count"]["plugin_output"] = $value["plugin_output"];
                $alert_json["$alert_count"]["url"] = $extinfo_url . "?type=&host=" . str_replace(" ", "+", $value["host_name"]);
                $alert_count += 1;
            }
    }
    return $alert_json;
}

function host_alert_cards($severety, $card_type, $counter, $card_data) {
    $c_count = 1;
    print("<h2>" . $severety . "</h2>");
    if($counter == 0) {
        print("<div class='alert alert-success'>");
        print(str_repeat("<span class='glyphicon glyphicon-thumbs-up'> </span> &nbsp; ", 3));
        print("No " . $severety . "!");
        print("</div>");
    } else {
        foreach($card_data as $key => $card) {
            print("<div class='alert alert-" . $card_type . "'>");
            print("<a href='" . $card["url"] . "'>");
            print("#" . $c_count . " ");
            print("<b>" . $card["host_name"] . "</b></a>");
            print(" " . $card["plugin_output"] . ".");
            print("</div>");
            $c_count += 1;
        }
    }
}
$hosts_total = host_total($hosts);
$service_total = service_total($services);

$host_issue_count = host_issue_count($hosts, 1, 0, 1);
$host_issues = alert_hosts($hosts, 1, 0, 1);

$host_ackn_issues = alert_hosts($hosts, 1, 1, 1);
$host_not_issues = alert_hosts($hosts, 1, 0, 0);
$host_ack_issues = array_merge($host_ackn_issues, $host_not_issues);
$host_ack_issues_count = count($host_ack_issues);

$warnings = alert_services($host, 1, 0, 1);
$warnings_count = count($warnings);

$warnings_ackn = alert_services($host, 1, 1, 1);
$warnings_not = alert_services($host, 1, 0, 0);

$warnings_ack_issues = array_merge($warnings_ackn, $warnings_not);
$warnings_ack_count = count($warnings_ack_issues);


$criticals = alert_services($host, 2, 0, 1);
$criticals_count = count($criticals);

$criticals_ackn = alert_services($host, 2, 1, 1);
$criticals_not = alert_services($host, 2, 0, 0);

$criticals_ack = array_merge($criticals_ackn, $criticals_not);
$criticals_ack_count = count($criticals_ack);

?>
