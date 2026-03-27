document.addEventListener("livewire:navigated", () => {
    observer.observe(document.getElementById("header") ?? document.getElementById('main-navigation'));
    observer.observe(document.getElementById("spinner-load-data"));
});

const observer = new IntersectionObserver((entriesObject) => {
    entriesObject.forEach((entry) => {
        if (entry.target.id === "header" || entry.target.id === 'main-navigation') {
            if (!entry.isIntersecting) {
                console.log('main nav ok');
                
                document
                    .getElementById("nav-collection")
                    .classList.add("contain-nav");
            } else {
                document
                    .getElementById("nav-collection")
                    .classList.remove("contain-nav");
            }
        } else if (entry.target.id === 'spinner-load-data' && entry.isIntersecting) {
            console.log('obs ok');
            
            document.getElementById('loadClick').click()
        }
    });
});

