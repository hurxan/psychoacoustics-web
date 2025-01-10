<?php

/**
 * this file contains all the common functions where no DB interaction in required
 */

/**
 * this function check the POST data names passed in the $formElements for malicious characters to prevent SQL injecon 
 * 
 * @param array $formElements Array containin the name of the post variables to sanitize
 * @return bool return false if the variables are ok to insert in sql
 */
function checkSpecialCharacter($formElements): bool
{
    $elements = $formElements;
    $characters = [
        '"',
        "'",
        "\\",
        chr(0),
        ";",
        "--",
        "#",
        "/*",
        "*/",
        "%",
        "_",
        "(",
        ")",
        "$",
        "&",
        "|",
        "<",
        ">",
        "!",
    ];
    $specialCharacters = false;
    foreach ($elements as $elem) {
        $_POST[$elem] = str_replace("'", "''", $_POST[$elem]);
        foreach ($characters as $char)
            $specialCharacters |= is_numeric(strpos($_POST[$elem], $char));
    }
    return $specialCharacters;
}


/**
 * convert compact test type to extended test type
 * @param string $testTypeCmp contains compact string test type
 * @return string contains string containin extended test type
 */
function getExtfromCmpType($testTypeCmp): string
{

    switch ($testTypeCmp) {
        case "freq":
            return "PURE_TONE_FREQUENCY";
            break;
        case "amp":
            return "PURE_TONE_INTENSITY";
            break;
        case "dur":
            return "PURE_TONE_DURATION";
            break;
        case "gap":
            return "WHITE_NOISE_GAP";
            break;
        case "ndur":
            return "WHITE_NOISE_DURATION";
            break;
        case "nmod":
            return "WHITE_NOISE_MODULATION";
            break;
        default:
            return null;
            break;
    }
}

/**
 * initialize an array with all the test parameters required
 * this function may seems uselsess, but it extract all the variable nedded from overcrowded arrays like
 * $_POST or $_SESSION to create an ordinated and restricted array of parameter.
 * 
 * Notice how this function retrieve all the parameters, even those that might not be initialized 
 * based on the test selected, setting them to null
 * 
 * @param array $rawParameters contains an array with miscellaneous data extracted from a test
 * @return array $newParam contains all and only the parametes needed to perform and save the test
 */
function initializeTestParameter($rawParameters): array
{

    $newParam = [];

    $newParam["amplitude"] = $rawParameters["amplitude"];
    $newParam["frequency"] = $rawParameters["frequency"] ?? null;
    $newParam["duration"] = $rawParameters["duration"];
    $newParam["onRamp"] = $rawParameters["onRamp"];
    $newParam["offRamp"] = $rawParameters["offRamp"];
    $newParam["modAmplitude"] = $rawParameters["modAmplitude"] ?? null;
    $newParam["modFrequency"] = $rawParameters["modFrequency"] ?? null;
    $newParam["modPhase"] = $rawParameters["modPhase"] ?? null;
    $newParam["blocks"] = $rawParameters["blocks"];
    $newParam["nAFC"] = $rawParameters["nAFC"];
    $newParam["ITI"] = $rawParameters["ITI"];
    $newParam["ISI"] = $rawParameters["ISI"];
    $newParam["delta"] = $rawParameters["delta"];
    $newParam["checkFb"] = $rawParameters["checkFb"] ?? 0;
    $newParam["factor"] = $rawParameters["factor"];
    $newParam["secFactor"] = $rawParameters["secFactor"];
    $newParam["reversals"] = $rawParameters["reversals"];
    $newParam["secReversals"] = $rawParameters["secReversals"];
    $newParam["threshold"] = $rawParameters["threshold"];
    $newParam["algorithm"] = $rawParameters["algorithm"];

    return $newParam;
}

/*
 * self explaining
 */
function isUserLogged(): bool
{
    if (isset($_SESSION['loggedUser']))
        return true;
    return false;
}


/**
 * Logs a message to a single log file.
 *
 * @param string $message The message to log.
 */
function logEvent($message): void
{
    $logDir = __DIR__ . '/../../logs/events/'; // Centralized log file path
    $currentMonth = date('m-Y'); // Format: YYYY-MM
    $logFile = $logDir . "events_log_$currentMonth.txt";


    $timestamp = date('Y-m-d H:i:s');
    $formattedMessage = "[$timestamp] $message" . PHP_EOL;

    file_put_contents($logFile, $formattedMessage, FILE_APPEND);
}


function trackCountryTraffic(): void
{
    $logDir = __DIR__ . '/../../logs/traffic/'; // Centralized log file path
    $currentMonth = date('m-Y'); // Format: YYYY-MM
    $logFile = $logDir . "traffic_log_$currentMonth.txt";

    $timestamp = date('Y-m-d H:i:s');

    // Get the visitor's IP
    $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';

    // Fetch country from ip-api.com
    $apiUrl = "http://ip-api.com/json/$ipAddress";
    $response = @file_get_contents($apiUrl);
    $country = 'Unknown Country';

    if ($response) {
        $data = json_decode($response, true);
        $country = $data['country'] ?? $country;
    }

    // Format the log entry
    $logEntry = "[$timestamp] $country" . PHP_EOL;

    // Write to the log file
    file_put_contents($logFile, $logEntry, FILE_APPEND);
}
