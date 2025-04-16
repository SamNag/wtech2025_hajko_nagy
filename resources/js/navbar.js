
function loadNavbar() {
    fetch("header.html")
        .then(response => response.text())
        .then(data => {
            document.getElementById("header-container").innerHTML = data;

            // Attach the event listener after the navbar content has been loaded
            const toggleButton = document.querySelector("[data-collapse-toggle='navbar-default']");
            const menu = document.getElementById("navbar-default");

            if (toggleButton && menu) {
                toggleButton.addEventListener("click", function () {
                    console.log("Toggle button clicked"); // For debugging
                    menu.classList.toggle("hidden");
                });
            }
        })
        .catch(error => console.error("Error loading navbar:", error));
}

// Call the function to load the navbar
document.addEventListener("DOMContentLoaded", loadNavbar);
