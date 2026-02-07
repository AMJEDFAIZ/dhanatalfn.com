// Mobile Menu Toggle
const mobileMenuBtn = document.getElementById('mobile-menu-btn');
const closeMenuBtn = document.getElementById('close-menu-btn');
const mobileMenu = document.getElementById('mobile-menu');
const mobileLinks = document.querySelectorAll('.mobile-link');

function toggleMenu() {
    mobileMenu.classList.toggle('-translate-x-full');
    document.body.classList.toggle('overflow-hidden');
}

if (mobileMenuBtn) {
    mobileMenuBtn.addEventListener('click', toggleMenu);
}

if (closeMenuBtn) {
    closeMenuBtn.addEventListener('click', toggleMenu);
}

mobileLinks.forEach(link => {
    link.addEventListener('click', () => {
        if (!mobileMenu.classList.contains('-translate-x-full')) {
            toggleMenu();
        }
    });
});

// Header Scroll Effect
const header = document.getElementById('header');

window.addEventListener('scroll', () => {
    if (window.scrollY > 50) {
        header.classList.add('shadow-md', 'py-2');
        header.classList.remove('py-3');
    } else {
        header.classList.remove('shadow-md', 'py-2');
        header.classList.add('py-3');
    }
});

// Reveal Animations on Scroll
function reveal() {
    var reveals = document.querySelectorAll(".reveal");

    for (var i = 0; i < reveals.length; i++) {
        var windowHeight = window.innerHeight;
        var elementTop = reveals[i].getBoundingClientRect().top;
        var elementVisible = 150;

        if (elementTop < windowHeight - elementVisible) {
            reveals[i].classList.add("active");
        }
    }
}

window.addEventListener("scroll", reveal);

// Portfolio Filter
const filterButtons = document.querySelectorAll('.portfolio-filter');
const portfolioItems = document.querySelectorAll('.portfolio-item');

if (filterButtons.length > 0) {
    filterButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Remove active class from all buttons
            filterButtons.forEach(btn => btn.classList.remove('active', 'bg-accent', 'text-white', 'border-accent'));
            filterButtons.forEach(btn => btn.classList.add('text-gray-600', 'border-gray-300'));

            // Add active class to clicked button
            button.classList.add('active', 'bg-accent', 'text-white', 'border-accent');
            button.classList.remove('text-gray-600', 'border-gray-300');

            const filterValue = button.getAttribute('data-filter');

            portfolioItems.forEach(item => {
                if (filterValue === 'all' || item.getAttribute('data-category') === filterValue) {
                    item.classList.remove('hidden');
                    item.classList.add('block', 'animate-fade-in');
                } else {
                    item.classList.remove('block', 'animate-fade-in');
                    item.classList.add('hidden');
                }
            });
        });
    });
}

// Skills Progress Animation
const skillsSection = document.querySelector('#skills');
const progressBars = document.querySelectorAll('.progress-bar');

function animateSkills() {
    progressBars.forEach(bar => {
        const width = bar.getAttribute('data-width');
        bar.style.width = width + '%';
    });
}

// Counters Animation
const counters = document.querySelectorAll('.counter');
let countersStarted = false;

function startCount(el) {
    const goal = parseInt(el.dataset.goal);
    let count = 0;
    const duration = 2000; // 2 seconds
    const increment = goal / (duration / 16); // 60fps

    let timer = setInterval(() => {
        count += increment;
        if (count >= goal) {
            el.textContent = goal + (el.dataset.suffix || '');
            clearInterval(timer);
        } else {
            el.textContent = Math.floor(count) + (el.dataset.suffix || '');
        }
    }, 16);
}

// Intersection Observer for Skills and Counters
const observerOptions = {
    threshold: 0.5
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            if (entry.target.id === 'skills') {
                animateSkills();
            }
            if (entry.target.classList.contains('counter-container') && !countersStarted) {
                counters.forEach(counter => startCount(counter));
                countersStarted = true;
            }
        }
    });
}, observerOptions);

if (skillsSection) observer.observe(skillsSection);
const counterContainer = document.querySelector('.counter-container');
if (counterContainer) observer.observe(counterContainer);

// Lightbox
const lightbox = document.getElementById('lightbox');
const lightboxImg = document.getElementById('lightbox-img');
const lightboxCaption = document.getElementById('lightbox-caption');
const lightboxClose = document.getElementById('lightbox-close');
const lightboxNext = document.getElementById('lightbox-next');
const lightboxPrev = document.getElementById('lightbox-prev');
const lightboxZoom = document.getElementById('lightbox-zoom');

// Dynamic Image List
let currentImages = [];
let currentImageIndex = 0;
let isZoomed = false;

function updateCurrentImages() {
    // Select all potential lightbox images
    const allCandidates = Array.from(document.querySelectorAll('.gallery-item img, .portfolio-item img, .lightbox-trigger img'));

    // Filter out hidden images (those in hidden parents)
    currentImages = allCandidates.filter(img => {
        const parent = img.closest('.gallery-item, .portfolio-item');
        if (parent && parent.classList.contains('hidden')) {
            return false;
        }
        return true;
    });
}

function openLightbox(imgElement) {
    updateCurrentImages(); // Refresh list based on current filters

    const index = currentImages.indexOf(imgElement);
    if (index === -1) return;

    currentImageIndex = index;
    updateLightboxImage();

    lightbox.classList.remove('hidden');
    // Reset zoom state
    isZoomed = false;
    if (lightboxZoom) lightboxZoom.innerHTML = '<i class="fa-solid fa-magnifying-glass-plus"></i>';
    if (lightboxImg) {
        lightboxImg.style.transform = '';
        lightboxImg.classList.remove('scale-[2]');
        lightboxImg.classList.add('cursor-grab');
        lightboxImg.classList.remove('cursor-grabbing');
    }

    setTimeout(() => {
        lightbox.classList.remove('opacity-0');
        if (lightboxImg) {
            lightboxImg.classList.remove('scale-95');
            lightboxImg.classList.add('scale-100');
        }
        if (lightboxCaption) {
            lightboxCaption.classList.remove('opacity-0', 'translate-y-2');
        }
    }, 10);
    document.body.classList.add('overflow-hidden');
}

function updateLightboxImage() {
    if (!lightboxImg || currentImages.length === 0) return;
    const img = currentImages[currentImageIndex];
    lightboxImg.src = img.src;
    lightboxImg.alt = img.alt;

    // Update Caption
    if (lightboxCaption) {
        lightboxCaption.textContent = img.alt || 'صورة';
    }
}

function toggleZoom() {
    if (!lightboxImg) return;

    isZoomed = !isZoomed;

    if (isZoomed) {
        lightboxImg.classList.add('scale-[2]');
        lightboxImg.classList.remove('cursor-grab');
        lightboxImg.classList.add('cursor-grabbing'); // Or grab cursor
        if (lightboxZoom) lightboxZoom.innerHTML = '<i class="fa-solid fa-magnifying-glass-minus"></i>';
    } else {
        lightboxImg.classList.remove('scale-[2]');
        lightboxImg.classList.add('cursor-grab');
        lightboxImg.classList.remove('cursor-grabbing');
        if (lightboxZoom) lightboxZoom.innerHTML = '<i class="fa-solid fa-magnifying-glass-plus"></i>';

        // Reset position if dragged (if we add drag logic later)
        lightboxImg.style.transform = '';
    }
}

function closeLightbox() {
    if (!lightbox) return;
    lightbox.classList.add('opacity-0');
    if (lightboxImg) {
        lightboxImg.classList.remove('scale-100');
        lightboxImg.classList.add('scale-95');
    }
    if (lightboxCaption) {
        lightboxCaption.classList.add('opacity-0', 'translate-y-2');
    }

    setTimeout(() => {
        lightbox.classList.add('hidden');
        if (lightboxImg) lightboxImg.src = '';
    }, 300);
    document.body.classList.remove('overflow-hidden');
}

function nextImage() {
    if (currentImages.length === 0) return;
    currentImageIndex = (currentImageIndex + 1) % currentImages.length;

    lightboxImg.style.opacity = '0';
    if (lightboxCaption) lightboxCaption.style.opacity = '0';

    setTimeout(() => {
        updateLightboxImage();
        lightboxImg.style.opacity = '1';
        if (lightboxCaption) lightboxCaption.style.opacity = '1';

        // Reset zoom
        isZoomed = false;
        lightboxImg.classList.remove('scale-[2]');
        if (lightboxZoom) lightboxZoom.innerHTML = '<i class="fa-solid fa-magnifying-glass-plus"></i>';
    }, 200);
}

function prevImage() {
    if (currentImages.length === 0) return;
    currentImageIndex = (currentImageIndex - 1 + currentImages.length) % currentImages.length;

    lightboxImg.style.opacity = '0';
    if (lightboxCaption) lightboxCaption.style.opacity = '0';

    setTimeout(() => {
        updateLightboxImage();
        lightboxImg.style.opacity = '1';
        if (lightboxCaption) lightboxCaption.style.opacity = '1';

        // Reset zoom
        isZoomed = false;
        lightboxImg.classList.remove('scale-[2]');
        if (lightboxZoom) lightboxZoom.innerHTML = '<i class="fa-solid fa-magnifying-glass-plus"></i>';
    }, 200);
}

// Initialize Lightbox Triggers
function initLightboxTriggers() {
    const triggers = document.querySelectorAll('.gallery-item img, .portfolio-item img, .lightbox-trigger img');
    triggers.forEach(img => {
        img.style.cursor = 'zoom-in';
        img.onclick = (e) => {
            e.stopPropagation();
            openLightbox(img);
        };

        // Handle parent clicks if they are not links
        const parent = img.closest('.gallery-item, .portfolio-item');
        if (parent && !parent.querySelector('a')) { // Only if no link inside
             parent.onclick = (e) => {
                 if (e.target.tagName !== 'A') {
                     openLightbox(img);
                 }
             };
        }
    });
}

// Run initialization
document.addEventListener('DOMContentLoaded', () => {
    initLightboxTriggers();
});
// Also run immediately in case DOM is already loaded
initLightboxTriggers();

if (lightboxClose) lightboxClose.addEventListener('click', closeLightbox);
if (lightboxNext) lightboxNext.addEventListener('click', (e) => { e.stopPropagation(); nextImage(); });
if (lightboxPrev) lightboxPrev.addEventListener('click', (e) => { e.stopPropagation(); prevImage(); });
if (lightboxZoom) lightboxZoom.addEventListener('click', (e) => { e.stopPropagation(); toggleZoom(); });
if (lightboxImg) lightboxImg.addEventListener('click', (e) => { e.stopPropagation(); toggleZoom(); });

// Close on background click
if (lightbox) {
    lightbox.addEventListener('click', (e) => {
        if (e.target === lightbox) {
            closeLightbox();
        }
    });
}

// Keyboard navigation
document.addEventListener('keydown', (e) => {
    if (!lightbox || lightbox.classList.contains('hidden')) return;

    if (e.key === 'Escape') closeLightbox();
    if (e.key === 'ArrowRight') nextImage();
    if (e.key === 'ArrowLeft') prevImage();
});



