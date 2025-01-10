function changeColor(){
    var bottone = document.getElementById("test-button")
    bottone.style.backgroundColor = '#80000F';
    bottone.style.color= 'fff';
    bottone.style.borderColor = '#000';
}

function leave(){
    var bottone = document.getElementById('test-button')
    bottone.style.backgroundColor = '#8B0000';
    bottone.style.color= 'fff';
    bottone.style.borderColor = '#000';
}

function copy(id){
	var value = document.getElementById(id).innerHTML;
	value = value.replaceAll('&amp;', '&');
	navigator.clipboard.writeText(value);
	alert('copied to clipboard');
}


function updatePage(display){
	var elems = document.getElementsByClassName("conditionalDisplay");
	for(j = 0; j < elems.length; j++) {
		if(display)
			elems[j].style.display = "";
		else
			elems[j].style.display = "none";
	}
}


function setupVolumeControl() {
    const audio = new Audio("audio/audio.mp3"); // Create the audio object
    const volume = document.getElementById("volume"); // Get the volume slider element

    // Ensure the slider exists before attaching events
    if (volume) {
        volume.addEventListener("input", (e) => {
            console.log("playing");
            audio.volume = e.currentTarget.value / 100; // Adjust audio volume
            audio.play(); // Start playing the audio

            // Pause the audio after 5 seconds
            window.setTimeout(() => {
                audio.pause();
            }, 2000);
        });
	
	window.updatePage = updatePage;
    } else {
        console.error("Volume slider not found.");
    }
}