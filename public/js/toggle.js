document.addEventListener("DOMContentLoaded", function () {
    const profileSection = document.getElementById("profile");
    const ordersSection = document.getElementById("orders");

    const profileRadio = document.getElementById("option-profile");
    const ordersRadio = document.getElementById("option-orders");

    function toggleSections() {
        if (profileRadio.checked) {
            profileSection.classList.remove("hidden");
            ordersSection.classList.add("hidden");
        } else if (ordersRadio.checked) {
            ordersSection.classList.remove("hidden");
            profileSection.classList.add("hidden");
        }
    }

    // Initial setup
    toggleSections();

    // Event Listeners for switching
    profileRadio.addEventListener("change", toggleSections);
    ordersRadio.addEventListener("change", toggleSections);
});
