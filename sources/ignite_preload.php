<?php
/**
 * Created by PhpStorm.
 * User: Denis Magda
 * Date: 12/16/2016
 * Time: 11:46 AM
 */

// Total number of persons.
$totalNumberOfPersons = 100;


print "Connecting to Ignite ...\n";

$dbh = connect();

print "The connection has been established...\n";


print "Filling in persons cache ...\n";

fillInPersonsCache($dbh, $totalNumberOfPersons);


print "Filling in vehicles cache ...\n";

fillInVehiclesCache($dbh, $totalNumberOfPersons);


// Connecting to the Ignite Cluster from the script.
function connect()
{
    try {
        // Connecting to Ignite using pre-configured DSN.
        $dbh = new PDO('odbc:LocalApacheIgniteDSN');

        // Changing PDO error mode.
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $dbh;
    } catch (PDOException $e) {
        print "Error: " . $e->getMessage() . "\n";
        die();
    }
}

/**
 * @param $dbh
 * @param $personsNumber
 */
function fillInPersonsCache($dbh, $personsNumber)
{
    for ($i = 0; $i < $personsNumber; $i++) {
        try {
            // Preparing query.
            // 'key' is a special keyword that refers to cache entry's key value in Ignite.
            // https://apacheignite.readme.io/docs/indexes#section-registering-indexed-types
            $dbs = $dbh->prepare(
                'INSERT INTO Person (_key, firstName, lastName, age, address) 
                 VALUES (?, ?, ?, ?, ?)');

            // Filling out parameters with dummy values.
            $key = $i;
            $firstName = "Name " . $i;
            $lastName = "Last name " . $i;
            $age = rand(20, 80);
            $address = "Some Street" . rand($i, $i * 100);

            // Binding parameters.
            $dbs->bindParam(1, $key);
            $dbs->bindParam(2, $firstName);
            $dbs->bindParam(3, $lastName);
            $dbs->bindParam(4, $age);
            $dbs->bindParam(5, $address);

            // Executing the query.
            $dbs->execute();
        } catch (PDOException $e) {
            print "Error: " . $e->getMessage() . "\n";
            die();
        }
    }
}

/**
 * @param $dbh
 * @param $totalPersonsNumber
 */
function fillInVehiclesCache($dbh, $totalPersonsNumber)
{
    for ($i = 0; $i < 2000; $i++) {
        try {
            // Preparing query. Have to set the schema name (cache name) explicitly for Vehicle because
            // the default one that is configured in DSN is 'PersonCache'.
            $dbs = $dbh->prepare(
                'INSERT INTO "VehicleCache".Vehicle (personId, vehicleId, type, model, year) 
                 VALUES (?, ?, ?, ?, ?)');

            // Filling out parameters with dummy values.
            $personId = rand(0, $totalPersonsNumber);
            $vehicleId = $i;
            $type = rand(1, 5);
            $model = "Car Model" . rand($i, $i * 100);
            $year = rand(1986, 2017);

            // Binding parameters.
            $dbs->bindParam(1, $personId);
            $dbs->bindParam(2, $vehicleId);
            $dbs->bindParam(3, $type);
            $dbs->bindParam(4, $model);
            $dbs->bindParam(5, $year);

            // Executing the query.
            $dbs->execute();
        } catch (PDOException $e) {
            print "Error: " . $e->getMessage() . "\n";
            die();
        }
    }
}