document.addEventListener("livewire:navigated", () => {
    observer.observe(document.getElementById("header"));
    observer.observe(document.getElementById("spinner-load-data"));
});

const observer = new IntersectionObserver((entriesObject) => {
    entriesObject.forEach((entry) => {
        if (entry.target.id === "header") {
            if (!entry.isIntersecting) {
                document
                    .getElementById("nav-collection")
                    .classList.add("contain-nav");
            } else {
                document
                    .getElementById("nav-collection")
                    .classList.remove("contain-nav");
            }
        } else if (entry.target.id === 'spinner-load-data' && entry.isIntersecting) {
            Livewire.dispatch('load-more');
        }
    });
});

