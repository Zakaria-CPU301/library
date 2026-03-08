document.addEventListener('livewire:navigated', () => {
    observer();
})

const observer = new IntersectionObserver((entriesObject) => {
    entriesObject.forEach((entry) => {
        if (!entry.isIntersecting) {
            console.log(this);
            document
                .getElementById("nav-collection")
                .classList.add("contain-nav");
        } else {
            document
                .getElementById("nav-collection")
                .classList.remove("contain-nav");
        }
    });
});
observer.observe(document.getElementById("header"));