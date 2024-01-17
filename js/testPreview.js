var context = new AudioContext();

// minimum initial variation
var varFreq = freq;					// frequency of the variable
var stdFreq = freq;					// frequency of the standard

var startingDelta = delta;

dur /= 1000;                        // cambio unità di misura in secondi
var stdDur = dur;					// duration of the standard
var varDur = dur;					// duration of the variable

var stdAmp = amp;					// intensity of the standard
var varAmp = amp;				    // intensity of the variable

var carAmp = amp;
var carDur = dur;

onRamp /= 1000;                        // cambio unità di misura in secondi
offRamp /= 1000;                        // cambio unità di misura in secondi

switch (type) {
    case "amplitude":
        varAmp = amp + startingDelta;
        break;
    case "frequency":
        varFreq = freq + startingDelta;
        break;
    case "duration":
        varDur = dur + (startingDelta / 1000);
        break;
    case "nduration":
        varDur = dur + (startingDelta / 1000);
        break;
    case "gap":
        delta = (startingDelta / 1000);
        break;
    case "nmodulation":
        delta = modAmp;
        break;
}

var swap = -1;						// position of variable sound

var betweenRampDur = 0.01           // durata rampa nel gap

//funzione per randomizzare l'output
function random() {
    for (var j = 1; j <= nAFC; j++)
        document.getElementById("button" + j).disabled = true;
    document.getElementById("playTest").disabled = true;
    document.getElementById("alert").style.visibility = "hidden";

    var rand = Math.floor(Math.random() * nAFC); // the variable sound will be the rand-th sound played

    for (var j = 0; j < nAFC; j++) {
        if (type == "amplitude" || type == "frequency") {
            if (j == rand)
                playSound((j * varDur) + j * (ISI / 1000), varFreq, varAmp, varDur, onRamp, offRamp);
            else
                playSound((j * stdDur) + j * (ISI / 1000), stdFreq, stdAmp, stdDur, onRamp, offRamp);
        } else if (type == "duration") {
            if (j == rand)
                playSound((j * stdDur) + j * (ISI / 1000), varFreq, varAmp, varDur, onRamp, offRamp);
            else if (j < rand)
                playSound((j * stdDur) + j * (ISI / 1000), stdFreq, stdAmp, stdDur, onRamp, offRamp);
            else if (j > rand)
                playSound(((j - 1) * stdDur) + varDur + j * (ISI / 1000), stdFreq, stdAmp, stdDur, onRamp, offRamp);
        } else if (type == "gap") {
            if (j == rand)
                playGapNoise((j * varDur) + j * (ISI / 1000), varAmp, varDur, onRamp, offRamp, delta);
            else
                playNoise((j * stdDur) + j * (ISI / 1000), stdAmp, stdDur, onRamp, offRamp);
        } else if (type == "nduration") {
            if (j == rand)
                playNoise((j * stdDur) + j * (ISI / 1000), varAmp, varDur, onRamp, offRamp);
            else if (j < rand)
                playNoise((j * stdDur) + j * (ISI / 1000), stdAmp, stdDur, onRamp, offRamp);
            else if (j > rand)
                playNoise(((j - 1) * stdDur) + varDur + j * (ISI / 1000), stdAmp, stdDur, onRamp, offRamp);
        } else if (type == "nmodulation") {
            if (j == rand)
                playModulatedNoise((j * carDur) + j * (ISI / 1000), 10 ** (carAmp / 20), carDur, 10 ** (modAmp / 20), modFreq, modPhase, onRamp, offRamp);
            else
                playNoise((j * carDur) + j * (ISI / 1000), carAmp, carDur, onRamp, offRamp);
        }
    }

    swap = rand + 1;

    //after playing the sound, the response buttons are reactivated
    source.onended = () => { //quando l'oscillatore sta suonando il programma non si ferma, quindi serve questo per riattivare i pulsanti solo quando finisce
        for (var j = 1; j <= nAFC; j++)
            document.getElementById("button" + j).disabled = false;
        document.getElementById("playTest").disabled = false;
    }

}

function select(button) {
    for (var j = 1; j <= nAFC; j++)
        document.getElementById("button" + j).disabled = true;
    let element = document.getElementById("alert")
    if (button == swap) {
        //if (feedback) {
            element.style.visibility = "visible";
            element.innerText = "Correct!"
            element.classList.remove("alert-danger");
            element.classList.add("alert-success");
        //}
    } else {
        //if (feedback) {
            element.style.visibility = "visible";
            element.innerText = "Wrong!"
            element.classList.remove("alert-success");
            element.classList.add("alert-danger");
        //}
    }
}