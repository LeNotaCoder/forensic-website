/**
* Template Name: Clinic
* Template URL: https://bootstrapmade.com/clinic-bootstrap-template/
* Updated: Jul 23 2025 with Bootstrap v5.3.7
* Author: BootstrapMade.com
* License: https://bootstrapmade.com/license/
*/

(function() {
  "use strict";

  /**
   * Apply .scrolled class to the body as the page is scrolled down
   */
  function toggleScrolled() {
    const selectBody = document.querySelector('body');
    const selectHeader = document.querySelector('#header');
    if (!selectHeader.classList.contains('scroll-up-sticky') && !selectHeader.classList.contains('sticky-top') && !selectHeader.classList.contains('fixed-top')) return;
    window.scrollY > 100 ? selectBody.classList.add('scrolled') : selectBody.classList.remove('scrolled');
  }

  document.addEventListener('scroll', toggleScrolled);
  window.addEventListener('load', toggleScrolled);

  /**
   * Mobile nav toggle
   */
  const mobileNavToggleBtn = document.querySelector('.mobile-nav-toggle');

  function mobileNavToogle() {
    document.querySelector('body').classList.toggle('mobile-nav-active');
    mobileNavToggleBtn.classList.toggle('bi-list');
    mobileNavToggleBtn.classList.toggle('bi-x');
  }
  if (mobileNavToggleBtn) {
    mobileNavToggleBtn.addEventListener('click', mobileNavToogle);
  }

  /**
   * Hide mobile nav on same-page/hash links
   */
  document.querySelectorAll('#navmenu a').forEach(navmenu => {
    navmenu.addEventListener('click', () => {
      if (document.querySelector('.mobile-nav-active')) {
        mobileNavToogle();
      }
    });

  });

  /**
   * Toggle mobile nav dropdowns
   */
  document.querySelectorAll('.navmenu .toggle-dropdown').forEach(navmenu => {
    navmenu.addEventListener('click', function(e) {
      e.preventDefault();
      this.parentNode.classList.toggle('active');
      this.parentNode.nextElementSibling.classList.toggle('dropdown-active');
      e.stopImmediatePropagation();
    });
  });

  /**
   * Preloader
   */
  const preloader = document.querySelector('#preloader');
  if (preloader) {
    window.addEventListener('load', () => {
      preloader.remove();
    });
  }

  /**
   * Scroll top button
   */
  let scrollTop = document.querySelector('.scroll-top');

  function toggleScrollTop() {
    if (scrollTop) {
      window.scrollY > 100 ? scrollTop.classList.add('active') : scrollTop.classList.remove('active');
    }
  }
  scrollTop.addEventListener('click', (e) => {
    e.preventDefault();
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  });

  window.addEventListener('load', toggleScrollTop);
  document.addEventListener('scroll', toggleScrollTop);

  /**
   * Animation on scroll function and init
   */
  function aosInit() {
    AOS.init({
      duration: 600,
      easing: 'ease-in-out',
      once: true,
      mirror: false
    });
  }
  window.addEventListener('load', aosInit);

  /**
   * Initiate glightbox
   */
  const glightbox = GLightbox({
    selector: '.glightbox'
  });

  /**
   * Initiate Pure Counter
   */
  new PureCounter();

  /**
   * Init swiper sliders
   */
  function initSwiper() {
    document.querySelectorAll(".init-swiper").forEach(function(swiperElement) {
      let config = JSON.parse(
        swiperElement.querySelector(".swiper-config").innerHTML.trim()
      );

      if (swiperElement.classList.contains("swiper-tab")) {
        initSwiperWithCustomPagination(swiperElement, config);
      } else {
        new Swiper(swiperElement, config);
      }
    });
  }

  window.addEventListener("load", initSwiper);

  /**
   * Frequently Asked Questions Toggle
   */
  document.querySelectorAll('.faq-item h3, .faq-item .faq-toggle, .faq-item .faq-header').forEach((faqItem) => {
    faqItem.addEventListener('click', () => {
      faqItem.parentNode.classList.toggle('faq-active');
    });
  });

})();




window.addEventListener("load", function () {
  const counter = document.getElementById("visitorCount");
  if (!counter) return;

  fetch("/visitor_counter.php", { cache: "no-store" })
    .then(res => res.json())
    .then(data => {
      counter.textContent = Number(data.count).toLocaleString();
    })
    .catch(err => console.error("Counter error:", err));
});





function toggleText() {
  const moreText = document.getElementById("moreText");
  const btn = document.getElementById("readMoreBtn");

  if (moreText.style.display === "none") {
    moreText.style.display = "block";
    btn.innerText = "Read Less";
    btn.classList.remove("btn-primary");
    btn.classList.add("btn-secondary");
  } else {
    moreText.style.display = "none";
    btn.innerText = "Read More";
    btn.classList.remove("btn-secondary");
    btn.classList.add("btn-primary");
  }
}


document.addEventListener("DOMContentLoaded", () => {
  const contactForm = document.getElementById("contactForm");
  if (!contactForm) return; // prevents error on other pages

  contactForm.addEventListener("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch("contact_submit.php", {
      method: "POST",
      body: formData
    })
    .then(res => res.text())
    .then(() => {
      console.log("Message sent successfully!");
      contactForm.reset();

      // GA4 tracking (optional but recommended)
      if (typeof gtag === "function") {
        gtag("event", "contact_submit", {
          event_category: "engagement",
          event_label: "Contact Form"
        });
      }
    })
    .catch(err => console.error("Error sending message:", err));
  });
});


document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("contactForm");
  if (!form) return;

  form.addEventListener("submit", function () {
    if (typeof gtag === "function") {
      gtag("event", "contact_submit", {
        event_category: "engagement",
        event_label: "Contact Form"
      });
      console.log("GA event fired: contact_submit");
    }
  });
});




document.addEventListener('click', function (e) {
  const link = e.target.closest('a');
  if (!link) return;

  if (link.href && link.href.match(/\.pdf$/i)) {
    gtag('event', 'pdf_download', {
      event_category: 'downloads',
      event_label: link.href
    });
  }
});



let scrollMarks = {25:false,50:false,75:false,100:false};

window.addEventListener('scroll', () => {
  const scrollTop = window.scrollY;
  const docHeight = document.documentElement.scrollHeight - window.innerHeight;
  const scrollPercent = Math.round((scrollTop / docHeight) * 100);

  [25,50,75,100].forEach(mark => {
    if (scrollPercent >= mark && !scrollMarks[mark]) {
      scrollMarks[mark] = true;
      gtag('event', 'scroll_depth', {
        event_category: 'engagement',
        event_label: mark + '%'
      });
    }
  });
});


document.addEventListener("DOMContentLoaded", function () {
  const banner = document.getElementById("cookieBanner");
  const btn = document.getElementById("cookieAccept");

  if (!banner || !btn) return;

  // 🔴 REMOVE banner immediately if already accepted
  if (localStorage.getItem("cookiesAccepted") === "true") {
    banner.remove();
    return;
  }

  btn.addEventListener("click", function () {
    localStorage.setItem("cookiesAccepted", "true");
    banner.remove();
  });
});





document.addEventListener("DOMContentLoaded", function() {
  const innovationVideos = [
    "https://www.youtube-nocookie.com/embed/rRQIu4fx1H8",
  ];

  let innovationIndex = 0;

  const innovationVideoFrame = document.getElementById("innovationVideo");

  function showInnovationVideo(index) {
    innovationVideoFrame.src = innovationVideos[index];
  }

  // Initialize first video
  showInnovationVideo(innovationIndex);

  document.getElementById("nextInnovationBtn").addEventListener("click", () => {
    innovationIndex = (innovationIndex + 1) % innovationVideos.length;
    showInnovationVideo(innovationIndex);
  });

  document.getElementById("prevInnovationBtn").addEventListener("click", () => {
    innovationIndex = (innovationIndex - 1 + innovationVideos.length) % innovationVideos.length;
    showInnovationVideo(innovationIndex);
  });
});


// List of video URLs
const casefiles_videoList = [
"https://www.youtube-nocookie.com/embed/E293sM4e5t0",
"https://www.youtube-nocookie.com/embed/Y6_uWN1zDEg",
"https://www.youtube-nocookie.com/embed/WHl6cvntnME"
];

let index = 0; // current video index

const iframe = document.getElementById("caseVideo");
const nextBtn = document.getElementById("nextBtn");
const prevBtn = document.getElementById("prevBtn");

// Load the first video initially
iframe.src = casefiles_videoList[index];

// Go to next video (loops to first)
nextBtn.addEventListener("click", () => {
index = (index + 1) % casefiles_videoList.length;
iframe.src = casefiles_videoList[index];
});

// Go to previous video (loops to last)
prevBtn.addEventListener("click", () => {
index = (index - 1 + casefiles_videoList.length) % casefiles_videoList.length;
iframe.src = casefiles_videoList[index];
});

