
// Save scroll position before form submission
document.addEventListener("submit", function (event) {
	// Check if the event target is a form and save the scroll position
	if (event.target.tagName === "FORM") {
		sessionStorage.setItem("scrollPosition", window.scrollY);
	}
});

// Restore scroll position on page load
window.addEventListener("load", function () {
	const savedScrollPosition = sessionStorage.getItem("scrollPosition");
	if (savedScrollPosition !== null) {
		window.scrollTo({
			top: parseInt(savedScrollPosition, 10),
			behavior: "auto" // Instant scroll, no animation
		});
		sessionStorage.removeItem("scrollPosition"); // Clean up after restoring
	}
});

