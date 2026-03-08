document.getElementById("collection").addEventListener("submit", function (e) {
    e.preventDefault();
    document.querySelectorAll("#collection button").forEach((e) => {
        e.classList.remove("bg-gray-900", "text-white");
    });
    e.submitter.classList.add("bg-gray-900", "text-white");

    let currentId = e.submitter.value;

    let url = "users/data";
    if (currentId) url += `?c-id=${currentId}`;

    const tableData = document.getElementById("view-data");
    let dataRowCollection = "";

    fetch(url, {
        method: "GET",
        headers: {
            Accept: "application/json",
        },
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.users.length == 0)
                dataRowCollection = `<tr><td colspan="9" class="border px-4 py-3 text-center capitalize italic">data in collection <b>${e.submitter.textContent}</b> is not found</td></tr>`;
            data.users.forEach((e, i) => {
                const routeEdit = `users/edit/${e.id}?action=single`;
                const routeDelete = `users/delete/${e.id}`;
                dataRowCollection += `
                            <tr>
                                <td class="border px-4 py-3 text-center">${i + 1}</td>
                                <td class="border px-4 py-3 capitalize">${e.fullname}</td>
                                <td class="border px-4 py-3">${e.username}</td>
                                <td class="border px-4 py-3">${e.email}</td>
                                <td class="border px-4 py-3 capitalize">${e.role}</td>
                                <td class="border px-4 py-3 capitalize">${e.collection?.collection_name}</td>
                                <td class="border px-1 py-1 text-center"><a href="" class="inline-flex bg-yellow-500 px-4 py-2 text-white rounded-md">Lihat</a></td>
                                <td class="border px-1 py-1 text-center"><a href="${routeEdit}" class="inline-flex bg-blue-500 px-4 py-2 text-white rounded-md">Edit</a></td>
                                <td class="border px-1 py-1 text-center"><a href="${routeDelete}" class="inline-flex bg-red-500 px-4 py-2 text-white rounded-md">Hapus</a></td>
                            </tr>
                        `;
            });
            tableData.innerHTML = dataRowCollection;
        })
        .catch((err) => console.log(err));
});