document.addEventListener("livewire:navigated", () => {
let sel = {
    role: false,
    collection: true,
    lang: false,
    categoryForm: true,
    categoryIndex: false,
};
for (const key in sel) {
    const elSelection = document.getElementById(key);
    if (!elSelection) continue;

    new tomSelect(elSelection, {
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
        sortField: {
            //ascending text content saja
            field: "text",
            direction: "asc",
        },
        onChange(value) {
            Livewire.dispatch(key, value);
        },
    });
}
});
