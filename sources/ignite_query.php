<?php
/**
 * Created by PhpStorm.
 * User: Denis Magda
 * Date: 12/16/2016
 * Time: 12:31 PM
 */

print "Connecting to Ignite ...\n";

$dbh = connect();

print "The connection has been established...\n";

try {
    numberOfPersons($dbh);

    numberOfVehicles($dbh);

    personVehiclesNumber($dbh, 50);

    oldestVehiclePerPerson($dbh);

} catch (PDOException $e) {
    print "Error: " . $e->getMessage() . "\n";
    die();
}

/**
 * @return PDO
 */
function connect() {
    try {
        // Connecting to Ignite using pre-configured DSN.
        $dbh = new PDO('odbc:LocalApacheIgniteDSN');

        // Changing PDO error mode.
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $dbh;
    } catch (PDOException $e) {
        print "Error: " . $e->getMessage() . "\n\n";
        die();
    }
}

/**
 * @param $dbh
 * @return mixed
 */
function numberOfPersons($dbh)
{
    $res = $dbh->query('SELECT count(*) FROM Person');

    print ">>>> Persons number: " . $res->fetchColumn() . "\n\n";
}

/**
 * @param $dbh
 * @return mixed
 */
function numberOfVehicles($dbh)
{
    $res = $dbh->query('SELECT count(*) FROM "VehicleCache".Vehicle');

    print ">>>> Vehicles number: " . $res->fetchColumn() . "\n\n";
}

/**
 * @param $dbh
 */
function personVehiclesNumber($dbh, $personId)
{
    $res = $dbh->query('SELECT count(*) FROM "VehicleCache".Vehicle as v 
                        WHERE v.personId = ' . $personId);

    print ">>>> Person's vehicles number: " . $res->fetchColumn() . "\n\n";
}

/**
 * @param $dbh
 */
function oldestVehiclePerPerson($dbh)
{
    print ">>>> Oldest car per person" . "\n\n";

    $res = $dbh->query('SELECT p.lastName, min(v.year) as year_res FROM Person as p 
                        JOIN "VehicleCache".Vehicle as v ON p._key = v.personID
                        WHERE p.age > 30 and p.age < 50 GROUP BY p.lastName ORDER BY year_res');

    // Printing results.
    foreach ($res as $row) {
        print $row[0] . "  vehicle year: " . $row[1] . "\n";
    }
}
