document.addEventListener("livewire:navigated", () => {
    observer();
});

const observer = new IntersectionObserver((entriesObject) => {
    entriesObject.forEach((entry) => {
        if (entry.target.id === "header") {
            if (!entry.isIntersecting) {
                console.log('not intersect');
                
                document
                    .getElementById("nav-collection")
                    .classList.add("contain-nav");
            } else {
                document
                    .getElementById("nav-collection")
                    .classList.remove("contain-nav");
            }
        } else if (entry.target.id === 'spinner-load-data' && entry.isIntersecting) {
            console.log(entry.isIntersecting);
            
            Livewire.dispatch('load-more');
        }
    });
});
observer.observe(document.getElementById("header"));
observer.observe(document.getElementById("spinner-load-data"));
