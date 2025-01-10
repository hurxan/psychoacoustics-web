
var carAmp = carAmpDb;
var modAmp = modAmpDb;

// minimum initial variation

delta = modAmp;

carDur /= 1000;             // cambio unità di misura in secondi


//funzione per randomizzare l'output
function createRandomizedOutput() {
    var rand = Math.floor(Math.random() * nAFC);// the variable sound will be the rand-th sound played
    for (var j = 0; j < nAFC; j++) {
        if (j == rand)
            playModulatedNoise((ITI / 1000) + (j * carDur) + j * (ISI / 1000), carAmp, carDur, modAmp, modFreq, modPhase, onRamp / 1000, offRamp / 1000, false);
        else
            playNoise((ITI / 1000) + (j * carDur) + j * (ISI / 1000), carAmp, carDur, onRamp / 1000, offRamp / 1000);
    }
    swap = rand + 1;
    enableResponseButtons()
}


//funzione per implementare l'algoritmo SimpleUpDown
function computeResponse(button) {
    pressedButton = button;

    results[0][i] = currentBlock;				// block
    results[1][i] = i + 1;						// trial
    results[2][i] = parseFloat(parseInt(delta * 1000) / 1000); 	// approximated delta
    results[3][i] = parseFloat(parseInt(delta * 1000) / 1000);				// approximated variable value
    results[4][i] = swap;	            // variable position

    //apply the algorithm to check for reversals, modify the delta parameter if needed
    checkReversal = nDOWNoneUPTest(upDownParam);
    modAmp = delta;

    if (checkReversal == 1)
        delta /= currentFactor;
    else if (checkReversal == -1)
        delta *= currentFactor;

    results[5][i] = pressedButton; 				// pressed button
    results[6][i] = pressedButton == swap ? 1 : 0;	// is the answer correct? 1->yes, 0->no
    results[7][i] = countRev; // reversals counter is updated in nDOWNoneUP() function and saved after it

    //increment counter
    i++;

    continueTest();
}
