
var varFreq = freq + delta;			// frequency of the variable 
var stdFreq = freq;					// frequency of the standard

dur /= 1000;                        // convert to seconds
var stdDur = dur;					// duration of the standard
var varDur = dur;					// duration of the variable

var stdAmp = amp;					// intensity of the standard
var varAmp = amp;					// intensity of the variable


//randomize output
function createRandomizedOutput() {
    var rand = Math.floor(Math.random() * nAFC);// the variable sound will be the rand-th sound played
    //var rand = 0;
    for (var j = 0; j < nAFC; j++) {
        if (j == rand)
            //variable sound
            playSound((ITI / 1000) + (j * varDur) + j * (ISI / 1000), varFreq, varAmp, varDur, onRamp / 1000, offRamp / 1000, false);
        else
            playSound((ITI / 1000) + (j * stdDur) + j * (ISI / 1000), stdFreq, stdAmp, stdDur, onRamp / 1000, offRamp / 1000);
    }
    swap = rand + 1;
    enableResponseButtons()
}


function computeResponse(button) {
    pressedButton = button;

    results[0][i] = currentBlock;				// block
    results[1][i] = i + 1;						// trial
    results[2][i] = parseFloat(parseInt((varFreq - stdFreq) * 1000) / 1000); 	// approximated delta
    results[3][i] = parseFloat(parseInt(varFreq * 1000) / 1000);			// approximated variable value
    results[4][i] = swap;						// variable position


    //apply the algorithm to check for reversals, modify the delta parameter if needed
    delta = varFreq - stdFreq;
    checkReversal = nDOWNoneUPTest(upDownParam);

    if (checkReversal == 1)
        delta /= currentFactor;
    else if (checkReversal == -1)
        delta *= currentFactor;

    varFreq = stdFreq + delta;


    results[5][i] = pressedButton; 				// pressed button
    results[6][i] = pressedButton == swap ? 1 : 0;	// is the answer correct? 1->yes, 0->no
    results[7][i] = countRev; // reversals counter is updated in nDOWNoneUP() function and saved after it

    //prepare for new trial
    i++;

    continueTest();
}




