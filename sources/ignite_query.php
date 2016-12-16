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
 * Connecting to the cluster.
 *
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
 * Getting a total number of persons.
 *
 * @param $dbh
 * @return mixed
 */
function numberOfPersons($dbh)
{
    $res = $dbh->query('SELECT count(*) FROM Person');

    print ">>>> Persons number: " . $res->fetchColumn() . "\n\n";
}

/**
 * Getting a total number of vehicles.
 *
 * @param $dbh
 * @return mixed
 */
function numberOfVehicles($dbh)
{
    // Have to set the schema name (cache name) explicitly for Vehicle because
    // the default one that is configured in DSN is 'PersonCache'.
    $res = $dbh->query('SELECT count(*) FROM "VehicleCache".Vehicle');

    print ">>>> Vehicles number: " . $res->fetchColumn() . "\n\n";
}

/**
 * Getting a total number of persons per vehicle.
 *
 * @param $dbh
 */
function personVehiclesNumber($dbh, $personId)
{
    // Have to set the schema name (cache name) explicitly for Vehicle because
    // the default one that is configured in DSN is 'PersonCache'.
    $res = $dbh->query('SELECT count(*) FROM "VehicleCache".Vehicle as v 
                        WHERE v.personId = ' . $personId);

    print ">>>> Person's vehicles number: " . $res->fetchColumn() . "\n\n";
}

/**
 * Getting the oldest vehicles grouped by people.
 *
 * The query returns a complete and consistent result set thanks to the setup of affinity collocation in
 * between persons' and vehicles' caches.
 *
 * @param $dbh
 */
function oldestVehiclePerPerson($dbh)
{
    print ">>>> Oldest car per person" . "\n\n";

    // Have to set the schema name (cache name) explicitly for Vehicle because
    // the default one that is configured in DSN is 'PersonCache'.
    $res = $dbh->query('SELECT p.lastName, min(v.year) as year_res FROM Person as p 
                        JOIN "VehicleCache".Vehicle as v ON p._key = v.personID
                        WHERE p.age > 30 and p.age < 50 GROUP BY p.lastName ORDER BY year_res');

    // Printing results.
    foreach ($res as $row) {
        print $row[0] . "  vehicle year: " . $row[1] . "\n";
    }
}
