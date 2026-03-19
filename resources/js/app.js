import "./bootstrap";

document.addEventListener("livewire:navigated", () => {
    let inputDate = document.querySelectorAll("input[type='date']");
    inputDate.forEach((e) => {
        e.addEventListener("click", () => e.showPicker());
    });
});
