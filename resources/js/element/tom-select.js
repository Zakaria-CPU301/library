document.addEventListener("livewire:navigated", () => {
    tomSelect();
});

Livewire.on("currently-page", (param) => {
    let current = param.current
    let sel = {};
    if (current == "users") {
        sel = {
            role: false,
            collection: true,
        };
    } else {
        sel = {
            lang: false,
            category: true,
        };
    }
    tomSelect(sel)
});


function tomSelect(sel) {
    for (const key in sel) {
        new TomSelect(document.getElementById(key), {
            create: sel[key],
            createFilter: function (input) {
                input = input.trim().toLowerCase();
                for (let key in this.options) {
                    let existingText = this.options[key].text
                        .trim()
                        .toLowerCase();

                    if (existingText === input || !isNaN(input)) {
                        return false;
                    }
                }
                return true;
            },
            sortField: { //ascending text content saja
                field: "text",
                direction: "asc",
            },
            onChange(value) {
                Livewire.dispatch(key, value)
            },
        });
    }
}
