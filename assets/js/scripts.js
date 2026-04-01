document.addEventListener('DOMContentLoaded', () => {
    
    // --- Mobile Navigation Toggle ---
    const mobileToggle = document.querySelector('.mobile-toggle');
    const navLinks = document.querySelector('.nav-links');
    const icon = mobileToggle.querySelector('i');

    mobileToggle.addEventListener('click', () => {
        navLinks.classList.toggle('active');
        if(navLinks.classList.contains('active')) {
            icon.classList.remove('fa-bars');
            icon.classList.add('fa-xmark');
        } else {
            icon.classList.remove('fa-xmark');
            icon.classList.add('fa-bars');
        }
    });

    // Close mobile menu when clicking a link
    const navItems = document.querySelectorAll('.nav-links a');
    navItems.forEach(item => {
        item.addEventListener('click', () => {
            if(window.innerWidth <= 768) {
                navLinks.classList.remove('active');
                icon.classList.remove('fa-xmark');
                icon.classList.add('fa-bars');
            }
        });
    });

    // --- Sticky Header Logic ---
    const header = document.querySelector('.header');
    window.addEventListener('scroll', () => {
        if(window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });

    // --- Active Link Highlighting on Scroll (Only for hash links on same page) ---
    const sections = document.querySelectorAll('section, footer');
    const navA = document.querySelectorAll('.nav-links li a[href*="#"]');

    window.addEventListener('scroll', () => {
        let current = '';
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;
            if(window.scrollY >= (sectionTop - 150)) {
                current = section.getAttribute('id');
            }
        });

        navA.forEach(a => {
            a.classList.remove('active');
            const href = a.getAttribute('href');
            if(current && href.includes(current)) {
                a.classList.add('active');
            }
        });
    });

    // --- AJAX Form Submission ---
    const contactForm = document.querySelector('.appointment-form');
    if (contactForm) {
        contactForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const submitBtn = contactForm.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            
            // UI Feedback
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Envoi en cours...';
            
            const formData = new FormData(contactForm);
            
            try {
                const response = await fetch('process_form.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.status === 'success') {
                    contactForm.innerHTML = `
                        <div class="success-message-container">
                            <i class="fa-solid fa-circle-check success-icon"></i>
                            <h3>Merci !</h3>
                            <p>${result.message}</p>
                            <button onclick="location.reload()" class="btn-primary mt-4">Envoyer un autre message</button>
                        </div>
                    `;
                } else {
                    throw new Error(result.message);
                }
            } catch (error) {
                alert("Erreur: " + error.message);
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            }
        });
    }

    // --- Reveal Animations (Fade-in on scroll) ---
    const fadeElements = document.querySelectorAll('.service-card, .promo-card, .col-item, .section-header, .box-shadow-card, .footer-widget');
    fadeElements.forEach(el => el.classList.add('fade-in'));

    const appearOptions = {
        threshold: 0.15,
        rootMargin: "0px 0px -50px 0px"
    };

    const appearOnScroll = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if(!entry.isIntersecting) {
                return;
            } else {
                entry.target.classList.add('appear');
                observer.unobserve(entry.target);
            }
        });
    }, appearOptions);

    fadeElements.forEach(el => {
        appearOnScroll.observe(el);
    });
    // --- Accordion Toggle ---
    document.querySelectorAll('.accordion-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const item = btn.closest('.accordion-item');
            const accordion = item.closest('.accordion');
            const isOpen = item.classList.contains('open');

            // Close all siblings
            accordion.querySelectorAll('.accordion-item').forEach(i => {
                i.classList.remove('open');
                i.querySelector('.acc-icon').textContent = '+';
            });

            // If it wasn't open, open it now
            if (!isOpen) {
                item.classList.add('open');
                btn.querySelector('.acc-icon').textContent = '−';
            }
        });
    });
});
