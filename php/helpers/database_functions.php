<?php

/**
 *this file contains all the common functions where a database interaction is required
 */

include_once __DIR__ . '/../db_connect.php';


/**
 * retrieve the key of the referral test based on the referral string passed
 * each account is identified by a referral string, each account has an single active referral.
 * @param string $referral contains the referral string
 * @param \mysqli $conn connection to the database
 * @return array $refrow contains the test key  (fk_GuestTest, fk_TestCount)
 */
function getReferralKeyFromInviteCode($referral, $conn): array
{

    $refSQL = "SELECT 
                    Username, 
	          Guest_ID,
                    fk_GuestTest, 
                    fk_TestCount 
               FROM 
                    account 
               WHERE
                    Referral='$referral'";

    $result = $conn->query($refSQL);
    $refrow = $result->fetch_assoc();

    if (!isset($refrow['Username'])) { //in case the referral is incorrect and no related username could be found
        throw new Exception("referral not valid");
    }
    return $refrow;
}

//ignore
function selectFromTable($columns, $table, $conditions, $conn)
{

    // Prepare the columns part
    $columnsList = implode(", ", $columns);

    // Prepare the conditions part (conditions are passed as strings directly)
    $conditionString = implode(" AND ", $conditions);

    // Build the SQL query
    $sql = "SELECT $columnsList FROM $table WHERE $conditionString";

    // Execute the query
    return $conn->query($sql);
}


/**
 * insert a new test based on given data
 * this insertion works with all the test tipology, since all possible null value are checked
 * maybe is not a super cool function but it does the job
 * 
 * @param int $id user id
 * @param int $count number of the test take by the $id, dont ask why the key is composed like this
 * @param string $testTypeCmp contains the tipology of the test in compact form
 * @param array $param contains all the parameter of the test 
 * @param string $result contains the string with the result 
 * @param string $score contains the threshold score
 * @param string $geometricScore contains the threshold geoemetric score
 * @param \mysqli $conn contains connnection with the database
 */
function insertTest($id, $count, $referralName, $testTypeCmp, $param, $results, $score, $geometricScore, $conn): void
{
    $testType = getExtfromCmpType($testTypeCmp);
    $deviceInfo = str_replace(";", " ", $_SERVER['HTTP_USER_AGENT']);	//take user device info

    //depending on the type of test is going to be saved, these parameters
    //might not be setted, in that case, i set a corrisponding variable to null for db insertion
    $frequency = isset($param['frequency']) ? $param['frequency'] : "NULL";
    $modAmplitude = isset($param['modAmplitude']) ? $param['modAmplitude'] : "NULL";
    $modFrequency = isset($param['modFrequency']) ? $param['modFrequency'] : "NULL";
    $modPhase = isset($param['modPhase']) ? $param['modPhase'] : "NULL";
    $sampleRate = isset($param['sampleRate']) ? $param['sampleRate'] : "0";


    //these are strings, inserting null is like inserting an empty string
    $results =	$results = isset($results) ? $results : null;
    $algorithm = (string) $param['algorithm'];
    $referralName = isset($referralName) ? $referralName : null;
    $score = $score = isset($score) ? $score : null;
    $geometricScore = isset($geometricScore) ? $geometricScore : null;



    $values = [
        $id,                                  // Guest_ID
        $count,                               // Test_count
        "'$referralName'",				      // Ref_name
        "current_timestamp()",                // Timestamp
        "'$testType'",                            // Type
        $param['amplitude'],                  // Amplitude
        $frequency,                           // Frequency
        $param['duration'],                   // Duration
        $param['onRamp'],                     // OnRamp
        $param['offRamp'],                    // OffRamp
        $param['blocks'],                     // Blocks
        $param['delta'],                      // Delta
        $param['nAFC'],                       // nAFC
        $param['ITI'],                        // ITI
        $param['ISI'],                        // ISI
        $param['factor'],                     // Factor
        $param['reversals'],                  // Reversal
        $param['secFactor'],                  // SecFactor
        $param['secReversals'],               // SecReversal
        $param['threshold'],                  // Threshold
        "'$algorithm'",                  	  // Algorithm
        "'$results'",  						  // Result
        "'$score'",								  //score
        "'$geometricScore'",					  //GeometricScore	
        $sampleRate,                          // SampleRate
        $param['checkFb'],                    // Feedback
        $modAmplitude,                        // ModAmplitude
        $modFrequency,                        // ModFrequency
        $modPhase,                            // ModPhase
        "'$deviceInfo'"                       // DeviceInfo
    ];

    // Join all values with commas and wrap in parentheses
    $sql = "INSERT INTO test VALUES (" . implode(", ", $values) . ");";

    $conn->query($sql);
}



/**
 * this function fetch all the test parameters assigning them the correct naming
 * (naming in the DB and PHP program are different)
 */
function getTestParameters($id, $count, $conn): array
{

    $sql = "SELECT 
                Timestamp AS creationDate,  #extra, might be handy
                Type, 
                Amplitude AS amplitude, 
                Frequency AS frequency, 
                Duration AS duration, 
                OnRamp AS onRamp,
                OffRamp AS offRamp,
                blocks, 
                Delta AS delta, 
                nAFC AS nAFC, 
                ISI AS ISI, 
                ITI AS ITI, 
                Factor AS factor, 
                Reversal AS reversals, 
                SecFactor AS secFactor, 
                SecReversal AS secReversals, 
                Feedback AS checkFb, 
                Threshold AS threshold, 
                Algorithm AS algorithm, 
                ModAmplitude AS modAmplitude, 
                ModFrequency AS modFrequency, 
                ModPhase AS modPhase

        
            FROM test
        
            WHERE Guest_ID='{$id}' AND Test_count='{$count}'";

    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    return $row;
}


/**
 * find the numer of tests taken by a user, used to assign a new count number for the test primary key
 * @param int $id id of the guest we want to analyze
 * @param \msqli $conn connection to db
 * @return int $count number of tests taken by that id
 */
function getLastTestCount($id, $conn): int
{

    $sql = "SELECT Max(Test_count) as count 
            FROM test 
            WHERE Guest_ID='$id'";

    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    $count = $row['count'];

    if (isset($count))
        return $count;
    return 0;
}

