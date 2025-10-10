
// Mobile menu toggle (fix)
const mobileMenuBtn = document.getElementById('mobile-menu-btn');
const mobileMenu = document.getElementById('mobile-menu');
const mobileMenuIcon = mobileMenuBtn.querySelector('i');
function closeMenu() {
    mobileMenu.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
    mobileMenuBtn.setAttribute('aria-expanded', 'false');
    if (mobileMenuIcon) {
        mobileMenuIcon.classList.remove('fa-xmark');
        mobileMenuIcon.classList.add('fa-bars');
    }
}
function openMenu() {
    mobileMenu.classList.remove('hidden');
    document.body.classList.add('overflow-hidden'); // optional: kunci scroll saat menu terbuka
    mobileMenuBtn.setAttribute('aria-expanded', 'true');
    if (mobileMenuIcon) {
        mobileMenuIcon.classList.remove('fa-bars');
        mobileMenuIcon.classList.add('fa-xmark');
    }
}
mobileMenuBtn.addEventListener('click', () => {
    const isHidden = mobileMenu.classList.contains('hidden');
    if (isHidden) openMenu();
    else closeMenu();
});
// Tutup saat klik link di dalam menu
mobileMenu.addEventListener('click', (e) => {
    if (e.target.closest('a')) closeMenu();
});
// Tutup saat klik di luar menu
document.addEventListener('click', (e) => {
    if (!mobileMenu.classList.contains('hidden')) {
        const clickInside = e.target.closest('#mobile-menu') || e.target.closest('#mobile-menu-btn');
        if (!clickInside) closeMenu();
    }
});
// Tutup otomatis saat resize ke md ke atas
window.addEventListener('resize', () => {
    if (window.innerWidth >= 768) closeMenu();
});
// Navbar scroll effect
const navbar = document.getElementById('navbar');
window.addEventListener('scroll', () => {
    if (window.scrollY > 20) {
        navbar.classList.add('navbar-scrolled');
    } else {
        navbar.classList.remove('navbar-scrolled');
    }
});
// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});
// Animation on scroll
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);
// Observe all animated elements
document.querySelectorAll('.animate-fadeIn, .animate-slideIn').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(20px)';
    el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
    observer.observe(el);
});