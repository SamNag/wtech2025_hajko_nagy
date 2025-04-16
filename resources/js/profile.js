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
    const profileDiv = document.getElementById("profile-info");

    profileDiv.dataset.fullData = JSON.stringify(getDefaultData(profileDiv));

    updateDisplayedData(profileDiv, getDefaultData(profileDiv));
}

function enableEditing(parentDiv) {
    let storedData = JSON.parse(parentDiv.dataset.fullData || "[]");

    let inputsHtml =
    `<div class='flex flex-col w-full gap-2 pt-2'>
        <div class='flex flex-col md:flex-row gap-2 md:gap-4'>
            <div class='w-full md:w-1/2'>
                <label class='block text-md inconsolata-bold'>Name</label>
                <input type='text' class='edit-input w-full rounded-lg bg-gray-200 border border-gray-300 p-3 h-12 focus:outline-none focus:ring-2 focus:ring-gray-300 border-gray-300' value='${storedData[0] || ""}' placeholder="Enter name">
            </div>
            <div class='w-full md:w-1/2'>
                <label class='block text-md inconsolata-bold'>Surname</label>
                <input type='text' class='edit-input w-full rounded-lg bg-gray-200 border border-gray-300 p-3 h-12 focus:outline-none focus:ring-2 focus:ring-gray-300 border-gray-300' value='${storedData[1] || ""}' placeholder="Enter surname">
            </div>
        </div>
        <div>
            <label class='block text-md inconsolata-bold'>Email</label>
            <input type='email' class='edit-input w-full rounded-lg bg-gray-200 border border-gray-300 p-3 h-12 focus:outline-none focus:ring-2 focus:ring-gray-300 border-gray-300' value='${storedData[2] || ""}' placeholder="Enter your email address">
        </div>
        <div>
            <label class='block text-md inconsolata-bold'>Phone</label>
            <input type='text' class='edit-input w-full rounded-lg bg-gray-200 border border-gray-300 p-3 h-12 focus:outline-none focus:ring-2 focus:ring-gray-300 border-gray-300' value='${storedData[3] || ""}' placeholder="Enter phone number">
        </div>
    </div>`;

    // Hide paragraphs smoothly
    parentDiv.querySelectorAll(".content p").forEach(p => p.style.display = "none");

    if (!parentDiv.querySelector(".edit-form")) {
        parentDiv.insertAdjacentHTML("beforeend", `<div class='edit-form w-full overflow- transition-all duration-100'>${inputsHtml}</div>`);
        setTimeout(() => {
            const form = parentDiv.querySelector(".edit-form");
            parentDiv.querySelector(".edit-form").style.maxHeight = `${form.scrollHeight}px`;
        }, 10);
    }
}

function toggleEditMode(parentDiv, button) {
    const isEditing = parentDiv.classList.contains("editing");
    parentDiv.classList.toggle("editing");

    const editForm = parentDiv.querySelector(".edit-form");
    if (isEditing) {
        button.textContent = "Edit";
        saveChanges(parentDiv);
        editForm.style.maxHeight = "0";
        setTimeout(() => editForm.remove());
    } else {
        button.textContent = "Save";
        enableEditing(parentDiv);
    }
}

function saveChanges(parentDiv) {
    const inputs = parentDiv.querySelectorAll(".edit-input");
    inputs.forEach(input => {
        if (input.value.trim() === "") {
            input.classList.add("border-red-500");
            alert("Please fill out all fields before saving.");
        } else {
            input.classList.remove("border-red-500");
        }
    });

    const newData = Array.from(inputs).map(input => input.value);
    parentDiv.dataset.fullData = JSON.stringify(newData);

    updateDisplayedData(parentDiv, newData);
}

function updateDisplayedData(parentDiv, displayData) {
    const contentDiv = parentDiv.querySelector(".content");
    contentDiv.innerHTML = "";

    const labels =
        [
            `${displayData[0] || ""} ${displayData[1] || ""}` || "Full name not provided",
            displayData[2] || "Email address not provided",
            displayData[3] || "Phone number not provided"
        ]

    labels.forEach((text, index) => {
        const p = document.createElement("p");
        p.className = `text-start text-md md:text-lg`;
        p.textContent = text;
        contentDiv.appendChild(p);
    });
}

function getDefaultData(parentDiv) {
    return ["Elisabeth", "Statham", "elisa.stat@gmail.com", "+421980452636"];  // Name, Surname, Email, Phone
}