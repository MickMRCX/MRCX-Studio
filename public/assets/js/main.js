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

    const carouselTrack =
    document.getElementById("carouselTrack");

const carouselPrevious =
    document.getElementById("carouselPrevious");

const carouselNext =
    document.getElementById("carouselNext");

const carouselDots =
    document.getElementById("carouselDots");


if (
    carouselTrack &&
    carouselPrevious &&
    carouselNext &&
    carouselDots
)
{
    const slides =
        Array.from(
            carouselTrack.querySelectorAll(
                ".carousel-slide"
            )
        );

    let currentSlide = 0;


    function updateCarousel()
    {
        carouselTrack.style.transform =
            `translateX(-${currentSlide * 100}%)`;

        const dots =
            carouselDots.querySelectorAll(
                ".carousel-dot"
            );

        dots.forEach(
            (dot, index) =>
            {
                dot.classList.toggle(
                    "active",
                    index === currentSlide
                );
            }
        );
    }


    slides.forEach(
        (_, index) =>
        {
            const dot =
                document.createElement(
                    "button"
                );

            dot.className =
                "carousel-dot";

            dot.setAttribute(
                "aria-label",
                `Show screenshot ${index + 1}`
            );

            dot.addEventListener(
                "click",
                () =>
                {
                    currentSlide = index;

                    updateCarousel();
                }
            );

            carouselDots.appendChild(
                dot
            );
        }
    );


    carouselPrevious.addEventListener(
        "click",
        () =>
        {
            currentSlide--;

            if (currentSlide < 0)
            {
                currentSlide =
                    slides.length - 1;
            }

            updateCarousel();
        }
    );


    carouselNext.addEventListener(
        "click",
        () =>
        {
            currentSlide++;

            if (
                currentSlide >=
                slides.length
            )
            {
                currentSlide = 0;
            }

            updateCarousel();
        }
    );


    updateCarousel();
}