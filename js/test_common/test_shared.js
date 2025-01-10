
const algorithmMapping = {
    SimpleUpDown: 1,
    TwoDownOneUp: 2,
    ThreeDownOneUp: 3,
};


const upDownParam = algorithmMapping[algorithm] || 2; //decide which parameter to pass on nDOWNoneUP based on alg passed
var context = new AudioContext({
    sampleRate: 48000,
});

// array and variables for data storage
var history = [];				// will have the answers ('1' if right, '0' if wrong)
var reversalsPositions = [];	// will have the position of the i-th reversal in the history array 
var i = 0;						// next index of the array
var countRev = 0;				// count of reversals 
var results = [[], [], [], [], [], [], [], []];		// block, trial, delta, variable value, variable position, pressed button, correct answer?, reversals
var score = 0					// final score
var geometric_score = 1
var positiveStrike = -1;		// -1 = unsetted, 0 = negative strike, 1 = positive strike
var formattedResult = "";				// final results that will be saved on the db
var timestamp = 0;				// timestamp of the starting of the test
var pressedButton;
var swap = -1;						// position of variable sound
var correctAnsw = 0;				// number of correct answers
var currentFactor = factor;			// first or second factor, depending on the number of reversals
var checkReversal = 0;
var description;


//starting function for all the tests
function start() {
    document.getElementById("StartingWindow").style.display = "none"; //starting window becomes invisible
    document.getElementById("PlayForm").style.display = "inherit"; //test interface becomes visible

    // take the timestamp when the test starts
    var currentdate = new Date();
    timestamp = currentdate.getFullYear() + "-" + (currentdate.getMonth() + 1) + "-" + currentdate.getDate() + " " + currentdate.getHours() + ":" + currentdate.getMinutes() + ":" + currentdate.getSeconds();

    reversalsPositions[0] = 0;
    createRandomizedOutput();
}


function timer() {
    document.getElementById("wrong").style.display = "none";
    document.getElementById("correct").style.display = "none";
}


function enableResponseButtons() {
    //after playing the sound, the response buttons are reactivated
    source.onended = () => { //waiting for the sound to ends
        for (var j = 1; j <= nAFC; j++)
            document.getElementById("button" + j).disabled = false;
    }
}


function disableResponseButtons() {
    for (var j = 1; j <= nAFC; j++)
        document.getElementById("button" + j).disabled = true;
}


//not used, generalization of the code written in the other test files
function computeResponseGeneral(varParam, stdParam, button) {

    pressedButton = button;
    results[0][i] = currentBlock;				// block
    results[1][i] = i + 1;						// trial
    results[4][i] = swap;						// variable position
    results[5][i] = pressedButton; 				// pressed button
    results[6][i] = pressedButton == swap ? 1 : 0;	// is the answer correct? 1->yes, 0->no

    delta = varParam - stdParam;

    results[2][i] = parseFloat(parseInt((delta) * 1000) / 1000); 	// approximated delta
    results[3][i] = parseFloat(parseInt(varParam * 1000) / 1000);			// approximated variable value

    //apply the algorithm to check for reversals, modify the delta parameter if needed
    checkReversal = nDOWNoneUPTest(upDownParam);

    if (checkReversal == 1)
        delta /= currentFactor;
    else if (checkReversal == -1)
        delta *= currentFactor;

    varParam = stdParam + delta;

    results[7][i] = countRev; // reversals counter is updated in nDOWNoneUP() function and saved after it 
    return varParam;
}



function nDOWNoneUPTest(n) {
    revDirection = 0 //'0->no reversal' '1->reversal up' '-1->reversal down'
    isReversal = false;

    if (pressedButton == swap) { //correct answer
        history[i] = 1;
        correctAnsw += 1;

        if (correctAnsw == n) { //if there are n consegutive correct answers
            correctAnsw = 0;

            if (positiveStrike == 0) {//reversal up
                isReversal = true;
            }
            revDirection = 1;
            positiveStrike = 1;
        }

        if (feedback) {
            document.getElementById("correct").style.display = "inherit";
            document.getElementById("wrong").style.display = "none";
        }
    } else { //wrong answer
        history[i] = 0;
        correctAnsw = 0;

        if (positiveStrike == 1) { //reversal down
            isReversal = true;
        }
        revDirection = -1;
        positiveStrike = 0;

        if (feedback) {
            document.getElementById("correct").style.display = "none";
            document.getElementById("wrong").style.display = "inherit";
        }
    }

    if (isReversal) {
        countRev++;
        //reversalsPositions[countRev] = i - (n - 1);//save the position of that 
        reversalsPositions[countRev] = i;

        if (countRev > reversals)
            currentFactor = secondFactor;


    }

    window.setTimeout("timer()", 500);
    return revDirection;
}


//this function could be written as monolithic with only createRandomizedOutput as a function
//but i find it's more clear this way, other than more reusable
function continueTest() {
    if (countRev < reversals + secondReversals) {
        // disable the response buttons until the new sounds are heared
        disableResponseButtons();
        //randomize and play the next sounds
        createRandomizedOutput();

    } else {
        //test ended
        createResults();
        //pass the data to the php file
        endTest()
    }
}


function createResults() {

    //format datas as a csv file
    //format: block;trials;delta;variableValue;variablePosition;button;correct;reversals;";
    for (var j = 0; j < i; j++) {
        formattedResult += results[0][j] + ";" + results[1][j] + ";" + results[2][j] + ";" + results[3][j] + ";"
        formattedResult += results[4][j] + ";" + results[5][j] + ";" + results[6][j] + ";" + results[7][j] + ",";
    }

    //calculate score
    score = 0;
    startingReversal = countRev - reversalThreshold;
    deltaHistory = results[2];

    for (var j = countRev; j > countRev - reversalThreshold; j--) { //start from the last rev, going backwards      
        revPosition = reversalsPositions[j];

        currentDelta = deltaHistory[revPosition]; //delta before the reversal

        deltaSeeker = revPosition - 1;
        while (deltaHistory[revPosition] == deltaHistory[deltaSeeker])
            deltaSeeker--;
        previousDelta = deltaHistory[deltaSeeker];

        partialScore = (currentDelta + previousDelta) / 2;
        console.log(partialScore);
        score += partialScore; //average delta of the reversal
        console.log(j + 'score' + score);
        geometric_score *= partialScore;
    }

    geometric_score = Math.pow(geometric_score, 1 / reversalThreshold);
    geometric_score = parseFloat(parseInt(geometric_score * 1000) / 1000);

    console.log('sum = ' + score);
    console.log('revthreshld ' + reversalThreshold);

    score /= reversalThreshold; //average deltas of every reversal
    score = parseFloat(parseInt(score * 1000) / 1000); //approximate to 2 decimal digits
    console.log('final score ' + score);
}



function endTest() {
    description = "&blocks=" + blocks + "&sampleRate=" + context.sampleRate;
    location.href =
        "php/save_test.php?result=" + formattedResult + description +
        "&timestamp=" + timestamp +
        "&currentBlock=" + currentBlock +
        "&score=" + score +
        "&geometric_score=" + geometric_score;
}

document.addEventListener('keypress', function keypress(event) {
    if (!document.getElementById("button1").disabled) {
        if ((event.code >= 'Digit1' && event.code <= 'Digit' + nAFC) ||
            (event.code >= 'Numpad1' && event.code <= 'Numpad' + nAFC)) {
            computeResponse(event.key);
            console.log('You pressed ' + event.key + ' button');
        }
    }
});



