window.onload = () => {
    setup();
}

function setup() {
    const elSelectRole = document.querySelector("select[name='role']");
    console.log(elSelectRole);
    elSelectRole.addEventListener("change", (event) => {
        console.log(event);

        const dn = encodeURIComponent(event.target.value);

        const xhr = new XMLHttpRequest();
        xhr.open("get", `/intranet/ajax/getLdapMembersForGroup.php?dn=${dn}`);

        xhr.onload = (res) => {
            if (xhr.status === 200) {
                const data = JSON.parse(xhr.responseText);
                processData(data);
            }
        }
        xhr.send();
    });
}

function processData(data) {
    const tableBody = document.querySelector("#current-user-list table tbody");
    tableBody.innerHTML = '';
    for (let i = 0; i < data.length; i++) {
        tableBody.appendChild(createOneUser(data[i]));
    }

    const userSelectList = document.querySelector("#user");

    for (let i = 0; i < userSelectList.options.length; i++) {

        const option = userSelectList.options[i];
        const itemAlreadyInList = data.find(u => u['dn'] === option.value);

        option.disabled = (itemAlreadyInList !== undefined);

    }
}

function createOneUser(user) {
    const item = document.createElement("tr");
    item.classList.add("user");

    const td1 = document.createElement("td");
    const td2 = document.createElement("td");
    const td3 = document.createElement("td");

    item.appendChild(td1);
    item.appendChild(td2);
    item.appendChild(td3);

    td1.classList.add('sn');
    td2.classList.add('givenName');

    td1.textContent = user['sn'];
    td2.textContent = user['givenName'];

    return item;
}