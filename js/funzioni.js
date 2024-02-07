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

function updateLink(ref){
	var test = document.getElementById('testType').value;
	document.getElementById('link').innerHTML = "psychoacoustics.dpg.psy.unipd.it/sito/demographicData.php?ref="+ref+"&test="+test;
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

let audio = new Audio("audio/sound.mp3");
let volume = document.getElementById("intesity");
// update the volume when the slider is moved
volume.addEventListener("input", (e) => {
  console.log("playing");
  audio.volume = e.currentTarget.value / 100;
  audio.play();
  window.setTimeout(function(){audio.pause();}, 2000);
});

window.updatePage = updatePage;