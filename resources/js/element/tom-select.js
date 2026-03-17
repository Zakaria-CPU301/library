document.addEventListener("livewire:navigated", () => {
    tomSelect();
})

function tomSelect() {
    const sel = {
        role: false,
        collection: true,
    };
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
            onChange(value) {
                Livewire.dispatch(key, {[key]: value})
            },
            sortField: {
                field: "text",
                direction: "asc",
            },
        });
    }
}
