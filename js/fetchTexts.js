fetch("files/texts.json").then(response => response.json()).then(data => {
    for (var attr in data) {
        element = document.getElementById(attr);
        if (element != null)
            element.innerHTML = data[attr];
    }
})