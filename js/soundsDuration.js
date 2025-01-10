
// minimum initial variation
var varFreq = freq;					// frequency of the variable 
var stdFreq = freq;					// frequency of the standard

delta /= 1000;                      // cambio unità di misura in secondi

dur /= 1000;                        // cambio unità di misura in secondi
var stdDur = dur;					// duration of the standard
var varDur = dur + delta;			// duration of the variable

var stdAmp = amp;					// intensity of the variable
var varAmp = amp;					// intensity of the standard 


//funzione per randomizzare l'output
function createRandomizedOutput() {
    var rand = Math.floor(Math.random() * nAFC);// the variable sound will be the rand-th sound played
    for (var j = 0; j < nAFC; j++) {
        if (j == rand)
            playSound((ITI / 1000) + (j * stdDur) + j * (ISI / 1000), varFreq, varAmp, varDur, onRamp / 1000, offRamp / 1000, false);
        else if (j < rand)
            playSound((ITI / 1000) + (j * stdDur) + j * (ISI / 1000), stdFreq, stdAmp, stdDur, onRamp / 1000, offRamp / 1000);
        else if (j > rand)
            playSound(((ITI / 1000) + (j - 1) * stdDur) + varDur + j * (ISI / 1000), stdFreq, stdAmp, stdDur, onRamp / 1000, offRamp / 1000);
    }
    swap = rand + 1;
    enableResponseButtons()
}


//funzione per implementare l'algoritmo SimpleUpDown
function computeResponse(button) {
    pressedButton = button;

    results[0][i] = currentBlock;				// block
    results[1][i] = i + 1;						// trial
    results[2][i] = parseFloat(parseInt((varDur - stdDur) * 1000)); 	// approximated delta
    results[3][i] = parseFloat(parseInt(varDur * 1000));				// approximated variable value
    results[4][i] = swap;						// variable position

    //apply the algorithm to check for reversals, modify the delta parameter if needed
    delta = varDur - stdDur;
    checkReversal = nDOWNoneUPTest(upDownParam); 

    if (checkReversal == 1)
        delta /= currentFactor;
    else 
    if (checkReversal == -1)
        delta *= currentFactor;

    varDur = stdDur + delta;

    results[5][i] = pressedButton; 				// pressed button
    results[6][i] = pressedButton == swap ? 1 : 0;	// is the answer correct? 1->yes, 0->no
    results[7][i] = countRev; // reversals counter is updated in nDOWNoneUP() function and saved after it

    //increment counter
    i++;


    continueTest();
}

