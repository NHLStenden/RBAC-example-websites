window.onload = () => {
    setupFilters();
}

function setupFilters() {
    const table = document.querySelector("section.report table");

    const headers = table.querySelectorAll("th");

    headers.forEach((th, index) => {
        const par = document.createElement("p");
        const selectionBox = document.createElement("select");
        par.appendChild(selectionBox);
        th.appendChild(par);

        setupOneFilter(table, selectionBox, index);

        selectionBox.addEventListener("change", (event) => {
            const selectedValue = event.target.value;
            const values = table.querySelectorAll(`tr td:nth-child(${index+1})`);
            values.forEach(td => td.classList.remove('filtered'));

            // now set a new filter only if a real value was added
            if (selectedValue !== "-1") {
                const filteredItems = Array.from(values).filter(td => td.textContent !== selectedValue);
                filteredItems.forEach(td => td.classList.add('filtered'));
                selectionBox.classList.add("has-selection");
            }
            else {
                selectionBox.classList.remove("has-selection");
            }
        });
    });
}

function setupOneFilter(table, selectionBox, index) {
    selectionBox.innerHTML = ""; // clear old stuff
    const emptyOption = document.createElement("option");
    emptyOption.value = -1;
    emptyOption.textContent = "<empty>";
    selectionBox.appendChild(emptyOption);

    const uniqueValues = new Set();
    const values = table.querySelectorAll(`tr td:nth-child(${index+1}):not(.filtered)`);
    values.forEach(td => uniqueValues.add(td.textContent));

    const sortedUniqueValues =  Array.from(uniqueValues).sort((a,b) => a.localeCompare(b));

    sortedUniqueValues.forEach(val => {
        const option = document.createElement("option");
        option.value = val;
        option.textContent = val;
        selectionBox.appendChild(option);
    });

}