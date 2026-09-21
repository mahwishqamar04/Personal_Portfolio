/**
 * Mehwish Qamar Portfolio - Main JavaScript
 * Handles navigation, animations, project filtering, contact form, and scroll effects.
 */

(function($) {
    'use strict';

    // ---------- Navbar Scroll Effect ----------
    $(window).on('scroll', function() {
        var scrollTop = $(this).scrollTop();
        
        if (scrollTop > 50) {
            $('#mainNav').addClass('scrolled');
        } else {
            $('#mainNav').removeClass('scrolled');
        }

        if (scrollTop > 300) {
            $('#backToTop').addClass('visible');
        } else {
            $('#backToTop').removeClass('visible');
        }

        updateActiveNav();
        checkFadeIn();
    });

    // ---------- Active Navigation Link ----------
    function updateActiveNav() {
        var scrollPos = $(document).scrollTop() + 100;
        
        $('section[id]').each(function() {
            var sectionTop = $(this).offset().top;
            var sectionBottom = sectionTop + $(this).outerHeight();
            var id = $(this).attr('id');
            
            if (scrollPos >= sectionTop && scrollPos < sectionBottom) {
                $('.nav-link').removeClass('active');
                $('.nav-link[href="#' + id + '"]').addClass('active');
            }
        });
    }

    // ---------- Smooth Scroll ----------
    $('.nav-link, .back-to-top').on('click', function(e) {
        var href = $(this).attr('href');
        
        if (href && href.charAt(0) === '#') {
            e.preventDefault();
            var target = $(href);
            
            if (target.length) {
                $('html, body').animate({
                    scrollTop: target.offset().top - 70
                }, 600);
                
                $('.navbar-collapse').collapse('hide');
            }
        }
    });

    // ---------- Fade-In Animation ----------
    function checkFadeIn() {
        $('.fade-in').each(function() {
            var elementTop = $(this).offset().top;
            var viewportBottom = $(window).scrollTop() + $(window).height();
            
            if (elementTop < viewportBottom - 50) {
                $(this).addClass('visible');
            }
        });
    }

    // ---------- Project Filtering ----------
    $('.filter-btn').on('click', function() {
        var filter = $(this).data('filter');
        
        $('.filter-btn').removeClass('active');
        $(this).addClass('active');
        
        if (filter === 'all') {
            $('.project-item').fadeIn(400);
        } else {
            $('.project-item').each(function() {
                if ($(this).data('category') === filter) {
                    $(this).fadeIn(400);
                } else {
                    $(this).fadeOut(400);
                }
            });
        }
    });

    // ---------- Contact Form ----------
    $('#contactForm').on('submit', function(e) {
        e.preventDefault();
        
        var form = $(this);
        var formData = form.serialize();
        var submitBtn = form.find('.btn-submit');
        var originalText = submitBtn.text();
        
        var name = form.find('#contactName').val().trim();
        var email = form.find('#contactEmail').val().trim();
        var subject = form.find('#contactSubject').val().trim();
        var message = form.find('#contactMessage').val().trim();
        
        if (!name || !email || !subject || !message) {
            showFormMessage('Please fill in all fields.', 'error');
            return;
        }
        
        if (!isValidEmail(email)) {
            showFormMessage('Please enter a valid email address.', 'error');
            return;
        }
        
        submitBtn.prop('disabled', true).text('Sending...');
        
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        
        $.ajax({
            url: 'api/contact.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            success: function(response) {
                if (response.success) {
                    showFormMessage(response.message, 'success');
                    form[0].reset();
                } else {
                    showFormMessage(response.message || 'Something went wrong.', 'error');
                }
            },
            error: function() {
                showFormMessage('Network error. Please try again later.', 'error');
            },
            complete: function() {
                submitBtn.prop('disabled', false).text(originalText);
            }
        });
    });

    function showFormMessage(msg, type) {
        var $msg = $('.form-message');
        $msg.removeClass('success error').addClass(type).text(msg).fadeIn();
        setTimeout(function() {
            $msg.fadeOut();
        }, 5000);
    }

    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    // ---------- Init ----------
    $(document).ready(function() {
        $('.service-card, .project-card, .skill-category, .cert-card, .timeline-item, .contact-info-card').addClass('fade-in');
        setTimeout(checkFadeIn, 100);
    });

})(jQuery);

/* STEP 8 — UI/UX SCROLL REVEAL */

document.addEventListener("DOMContentLoaded", function () {

    const revealElements = document.querySelectorAll(
        ".project-card, .service-card, .skill-card, .education-card, .certification-card, .experience-card, .about-card"
    );

    if (!revealElements.length) return;

    if (
        window.matchMedia &&
        window.matchMedia("(prefers-reduced-motion: reduce)").matches
    ) {
        return;
    }

    revealElements.forEach(function (element) {
        element.classList.add("mq-reveal");
    });

    const observer = new IntersectionObserver(
        function (entries, observerInstance) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add("mq-reveal-visible");
                    observerInstance.unobserve(entry.target);
                }
            });
        },
        {
            threshold: 0.12
        }
    );

    revealElements.forEach(function (element) {
        observer.observe(element);
    });
});


/* STEP 8 — UI/UX SCROLL REVEAL */

document.addEventListener("DOMContentLoaded", function () {

    const revealElements = document.querySelectorAll(
        ".project-card, .service-card, .skill-card, .education-card, .certification-card, .experience-card, .about-card"
    );

    if (!revealElements.length) return;

    if (
        window.matchMedia &&
        window.matchMedia("(prefers-reduced-motion: reduce)").matches
    ) {
        return;
    }

    revealElements.forEach(function (element) {
        element.classList.add("mq-reveal");
    });

    const observer = new IntersectionObserver(
        function (entries, observerInstance) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add("mq-reveal-visible");
                    observerInstance.unobserve(entry.target);
                }
            });
        },
        {
            threshold: 0.12
        }
    );

    revealElements.forEach(function (element) {
        observer.observe(element);
    });
});

