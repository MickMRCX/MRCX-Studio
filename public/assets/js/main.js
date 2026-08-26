const menuToggle =
    document.getElementById("menuToggle");

const navLinks =
    document.getElementById("navLinks");


if (menuToggle && navLinks)
{
    menuToggle.addEventListener(
        "click",
        () =>
        {
            navLinks.classList.toggle("open");
        }
    );
}


const observer =
    new IntersectionObserver(
        entries =>
        {
            entries.forEach(
                entry =>
                {
                    if (!entry.isIntersecting)
                        return;

                    entry.target.classList.add("visible");

                    observer.unobserve(
                        entry.target
                    );
                }
            );
        },
        {
            threshold: 0.15
        }
    );


document
    .querySelectorAll(".reveal")
    .forEach(
        element =>
        {
            observer.observe(
                element
            );
        }
    );