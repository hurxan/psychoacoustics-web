
dur /= 1000;                        // cambio unità di misura in secondi
var stdDur = dur;					// duration of the standard
var varDur = dur;					// duration of the variable

var stdAmp = amp;					// intensity of the variable
var varAmp = amp;					// intensity of the standard 

var betweenRampDur = 0.001;       // durata rampa nel gap

//funzione per randomizzare l'output
function createRandomizedOutput() {
    var rand = Math.floor(Math.random() * nAFC);    // the variable sound will be the rand-th sound played
    for (var j = 0; j < nAFC; j++) {
        if (j == rand)
            playGapNoise((ITI / 1000) + (j * varDur) + j * (ISI / 1000), varAmp, varDur, onRamp / 1000, offRamp / 1000, delta / 1000, false);
        else
            playNoise((ITI / 1000) + (j * stdDur) + j * (ISI / 1000), stdAmp, stdDur, onRamp / 1000, offRamp / 1000);
    }
    swap = rand + 1;
    enableResponseButtons()
}


//funzione per implementare l'algoritmo SimpleUpDown
function computeResponse(button) {
    pressedButton = button;

    results[0][i] = currentBlock;				// block
    results[1][i] = i + 1;						// trial
    results[4][i] = swap;
    results[5][i] = pressedButton; 				// pressed button
    results[6][i] = pressedButton == swap ? 1 : 0;	// is the answer correct? 1->yes, 0->no


    results[2][i] = parseFloat(parseInt(delta * 1000) / 1000); 	// approximated delta
    results[3][i] = parseFloat(parseInt(delta * 1000) / 1000);				// approximated variable value

    checkReversal = nDOWNoneUPTest(upDownParam);
    
    if (checkReversal == 1)
        delta /= currentFactor;
    else if (checkReversal == -1)
        delta *= currentFactor;

    results[7][i] = countRev; // reversals counter is updated in nDOWNoneUP() function and saved after it

    //increment counter
    i++;

    continueTest();
}

