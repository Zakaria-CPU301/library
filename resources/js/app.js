import "./bootstrap";
import "./element/observer";
import "./element/tom-select";
document.addEventListener("livewire:navigated", () => {
    let inputDate = document.querySelectorAll("input[type='date']");
    inputDate.forEach((e) => {
        e.addEventListener("click", () => e.showPicker());
    });
    console.log('ok');
});


