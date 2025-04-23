document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".edit").forEach(button => {
        button.addEventListener("click", function () {
            const parentDiv = this.closest(".border");
            toggleEditMode(parentDiv, button);
        });
    });

    initializeSections();
});

function initializeSections() {
    const shippingDiv = document.getElementById("shipping");
    const paymentDiv = document.getElementById("payment");

    shippingDiv.dataset.fullData = JSON.stringify(getDefaultData(shippingDiv));
    paymentDiv.dataset.fullData = JSON.stringify(getDefaultData(paymentDiv));

    updateDisplayedData(shippingDiv, getDefaultData(shippingDiv));
    updateDisplayedData(paymentDiv, getDefaultData(paymentDiv));
}

function toggleEditMode(parentDiv, button) {
    const isEditing = parentDiv.classList.contains("editing");
    parentDiv.classList.toggle("editing");

    const editForm = parentDiv.querySelector(".edit-form");
    if (isEditing) {
        button.textContent = "Edit";
        saveChanges(parentDiv);
        editForm.style.maxHeight = "0";
        setTimeout(() => editForm.remove(), 300);
    } else {
        button.textContent = "Save";
        enableEditing(parentDiv);
    }
}

function enableEditing(parentDiv) {
    let storedData = JSON.parse(parentDiv.dataset.fullData || "[]");

    let inputsHtml = parentDiv.id === "shipping" ? `
    <div class='flex flex-col w-full gap-2 pt-2'>
    <div>
        <label class='block text-md inconsolata-bold'>Address</label>
        <input type='text' class='edit-input w-full rounded-lg bg-gray-200 border border-gray-300 p-3 h-12 focus:outline-none focus:ring-2 focus:ring-gray-300 border-gray-300' value='${storedData[0] || ""}' placeholder="Enter your address">
    </div>
    <div class='flex flex-col md:flex-row gap-2 md:gap-4'>
        <div class='w-full md:w-1/2'>
            <label class='block text-md inconsolata-bold'>Postal Code</label>
            <input type='text' class='edit-input w-full rounded-lg bg-gray-200 border border-gray-300 p-3 h-12 focus:outline-none focus:ring-2 focus:ring-gray-300 border-gray-300' value='${storedData[1] || ""}' placeholder="Enter postal code" maxlength="10">
        </div>
        <div class='w-full md:w-1/2'>
            <label class='block text-md inconsolata-bold'>City</label>
            <input type='text' class='edit-input w-full rounded-lg bg-gray-200 border border-gray-300 p-3 h-12 focus:outline-none focus:ring-2 focus:ring-gray-300 border-gray-300' value='${storedData[2] || ""}' placeholder="Enter city">
        </div>
    </div>
    <div>
        <label class='block text-md inconsolata-bold'>Country</label>
        <input type='text' class='edit-input w-full rounded-lg bg-gray-200 border border-gray-300 p-3 h-12 focus:outline-none focus:ring-2 focus:ring-gray-300 border-gray-300' value='${storedData[3] || ""}' placeholder="Enter country">
    </div>
</div>` : `
<div class='flex flex-col w-full gap-2 pt-2'>
    <div>
        <label class='block text-md inconsolata-bold'>Card Number</label>
        <input type='text' class='edit-input w-full rounded-lg bg-gray-200 border border-gray-300 p-3 h-12 focus:outline-none focus:ring-2 focus:ring-gray-300 border-gray-300' value='${storedData[0] || ""}' placeholder="Enter card number" maxlength="16" oninput="validateCardNumber(this)">
    </div>
    <div class='flex flex-col md:flex-row gap-2 md:gap-4'>
        <div class='w-full md:w-1/2'>
            <label class='block text-md inconsolata-bold'>Expiration Date</label>
            <input type='text' class='edit-input w-full rounded-lg bg-gray-200 border border-gray-300 p-3 h-12 focus:outline-none focus:ring-2 focus:ring-gray-300 border-gray-300' value='${storedData[1] || ""}' placeholder="MM/YY" maxlength="5" oninput="formatExpirationDate(this)">
        </div>
        <div class='w-full md:w-1/2'>
            <label class='block text-md inconsolata-bold'>Security Code</label>
            <input type='text' class='edit-input w-full rounded-lg bg-gray-200 border border-gray-300 p-3 h-12 focus:outline-none focus:ring-2 focus:ring-gray-300 border-gray-300' value='${storedData[2] || ""}' placeholder="Enter CVV" maxlength="3" oninput="validateCVV(this)">
        </div>
    </div>
</div>`;

    // Hide paragraphs smoothly
    parentDiv.querySelectorAll(".content p").forEach(p => p.style.display = "none");

    if (!parentDiv.querySelector(".edit-form")) {
        parentDiv.insertAdjacentHTML("beforeend", `<div class='edit-form w-full overflow- transition-all duration-300'>${inputsHtml}</div>`);
        setTimeout(() => {
            const form = parentDiv.querySelector(".edit-form");
            parentDiv.querySelector(".edit-form").style.maxHeight = `${form.scrollHeight}px`;
        }, 10);
    }
}

function saveChanges(parentDiv) {
    const inputs = parentDiv.querySelectorAll(".edit-input");
    let isValid = true;

    inputs.forEach(input => {
        if (input.value.trim() === "") {
            isValid = false;
            input.classList.add("border-red-500"); // Highlight empty fields
        } else {
            input.classList.remove("border-red-500");
        }
    });

    if (!isValid) {
        alert("Please fill out all fields before saving.");
        return;
    }

    const newData = Array.from(inputs).map(input => input.value);
    parentDiv.dataset.fullData = JSON.stringify(newData);

    updateDisplayedData(parentDiv, newData);
}

function updateDisplayedData(parentDiv, displayData) {
    const contentDiv = parentDiv.querySelector(".content");
    contentDiv.innerHTML = "";

    const labels = parentDiv.id === "shipping"
        ? [
            displayData[0] || "Address not provided",
            `${displayData[1] || ""} ${displayData[2] || ""}`.trim() || "Postal code and city not provided",
            displayData[3] || "Country not provided"
        ]
        : [
            displayData[0] ? "XXXX-XXXX-XXXX-" + displayData[0].slice(-4) : "Card number not provided",
            displayData[1] || "Expiration date not provided",
            displayData[2] ? "***" : "CVV not provided"
        ];

    labels.forEach((text, index) => {
        const p = document.createElement("p");
        p.className = `text-start text-md md:text-lg`;
        p.textContent = text;
        contentDiv.appendChild(p);
    });
}

function getDefaultData(parentDiv) {
    if (parentDiv.id === "shipping") {
        return ["", "", "", ""]; // Address, Postal Code, City, Country
    } else {
        return ["", "", ""]; // Card Number, Expiration Date, CVV
    }
}

// Validate card number (16 digits)
function validateCardNumber(input) {
    input.value = input.value.replace(/\D/g, ''); // Remove non-digits
    if (input.value.length > 16) {
        input.value = input.value.slice(0, 16); // Limit to 16 digits
    }
}

// Format expiration date as MM/YY
function formatExpirationDate(input) {
    let value = input.value.replace(/\D/g, ''); // Remove non-digits
    if (value.length > 2) {
        value = value.slice(0, 2) + '/' + value.slice(2, 4); // Add slash after MM
    }
    input.value = value;
}

// Validate CVV (3 digits)
function validateCVV(input) {
    input.value = input.value.replace(/\D/g, ''); // Remove non-digits
    if (input.value.length > 3) {
        input.value = input.value.slice(0, 3); // Limit to 3 digits
    }
}