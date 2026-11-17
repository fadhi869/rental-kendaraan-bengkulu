/**
 * High-End Motion UI & Physics Engine
 * Rental Armada Bengkulu
 */
(function () {
  'use strict';

  // 1. 3D Tilt & Cursor Spotlight
  function initTiltAndSpotlight() {
    const cards = document.querySelectorAll('.tilt-card');
    cards.forEach((card) => {
      let isHovered = false;

      card.addEventListener('mouseenter', () => {
        isHovered = true;
      });

      card.addEventListener('mousemove', (e) => {
        if (!isHovered) return;
        const rect = card.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        // Spotlight CSS Variables
        card.style.setProperty('--mouse-x', `${x}px`);
        card.style.setProperty('--mouse-y', `${y}px`);

        // 3D Perspective Tilt Math
        const centerX = rect.width / 2;
        const centerY = rect.height / 2;
        const deltaX = (x - centerX) / centerX;
        const deltaY = (y - centerY) / centerY;

        const maxTilt = 6.5; // degrees
        const rotateX = -deltaY * maxTilt;
        const rotateY = deltaX * maxTilt;

        card.style.transform = `perspective(900px) rotateX(${rotateX.toFixed(2)}deg) rotateY(${rotateY.toFixed(2)}deg) scale3d(1.018, 1.018, 1.018)`;
      });

      card.addEventListener('mouseleave', () => {
        isHovered = false;
        card.style.transform = 'perspective(900px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)';
        card.style.removeProperty('--mouse-x');
        card.style.removeProperty('--mouse-y');
      });
    });
  }

  // 2. Odometer / Counter Roll Effect with Easing
  function initOdometerCounters() {
    const counters = document.querySelectorAll('[data-counter-target]');
    if (!counters.length) return;

    function easeOutExpo(x) {
      return x === 1 ? 1 : 1 - Math.pow(2, -10 * x);
    }

    counters.forEach((el) => {
      const target = parseFloat(el.getAttribute('data-counter-target') || '0');
      const isCurrency = el.getAttribute('data-counter-currency') === 'true';
      const duration = 1400; // ms
      const startTime = performance.now();

      function update(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        const easeVal = easeOutExpo(progress);
        const currentVal = Math.round(easeVal * target);

        if (isCurrency) {
          el.textContent = 'Rp ' + currentVal.toLocaleString('id-ID');
        } else {
          el.textContent = currentVal.toLocaleString('id-ID');
        }

        if (progress < 1) {
          requestAnimationFrame(update);
        } else {
          if (isCurrency) {
            el.textContent = 'Rp ' + target.toLocaleString('id-ID');
          } else {
            el.textContent = target.toLocaleString('id-ID');
          }
        }
      }

      requestAnimationFrame(update);
    });
  }

  // 3. Animated Progress Fill on Fleet Status
  function initProgressFills() {
    const fills = document.querySelectorAll('.progress-liquid-fill');
    if (!fills.length) return;

    setTimeout(() => {
      fills.forEach((fill) => {
        const pct = fill.getAttribute('data-fill-percent') || '0';
        fill.style.width = `${pct}%`;
      });
    }, 180);
  }

  // 4. Magnetic Button Physics
  function initMagneticButtons() {
    const btns = document.querySelectorAll('.btn-magnetic');
    btns.forEach((btn) => {
      btn.addEventListener('mousemove', (e) => {
        const rect = btn.getBoundingClientRect();
        const centerX = rect.left + rect.width / 2;
        const centerY = rect.top + rect.height / 2;

        const dx = (e.clientX - centerX) * 0.35;
        const dy = (e.clientY - centerY) * 0.35;

        btn.style.transform = `translate3d(${dx.toFixed(1)}px, ${dy.toFixed(1)}px, 0) scale(1.04)`;
      });

      btn.addEventListener('mouseleave', () => {
        btn.style.transform = 'translate3d(0, 0, 0) scale(1)';
      });
    });
  }

  // 5. Tactile Ripple Effect
  function initRipple() {
    document.addEventListener('click', (e) => {
      const btn = e.target.closest('.btn-magnetic, .btn-premium-cta, .btn');
      if (!btn) return;

      const rect = btn.getBoundingClientRect();
      const ripple = document.createElement('span');
      ripple.classList.add('ripple-span');

      const size = Math.max(rect.width, rect.height) * 2;
      ripple.style.width = `${size}px`;
      ripple.style.height = `${size}px`;
      ripple.style.left = `${e.clientX - rect.left - size / 2}px`;
      ripple.style.top = `${e.clientY - rect.top - size / 2}px`;

      btn.appendChild(ripple);

      setTimeout(() => {
        ripple.remove();
      }, 600);
    });
  }

  // 6. Sidebar Liquid Glider Transition
  function initSidebarGlider() {
    const sidebar = document.querySelector('.app-sidebar');
    if (!sidebar) return;

    const navGroups = sidebar.querySelectorAll('.nav-group');
    navGroups.forEach((group) => {
      let glider = group.querySelector('.sidebar-glider');
      if (!glider) {
        glider = document.createElement('div');
        glider.className = 'sidebar-glider';
        group.style.position = 'relative';
        group.appendChild(glider);
      }

      const items = group.querySelectorAll('.nav-item');
      const activeItem = group.querySelector('.nav-item.active');

      items.forEach((item) => {
        item.addEventListener('mouseenter', () => {
          if (item === activeItem) {
            glider.style.opacity = '0';
            return;
          }
          const top = item.offsetTop;
          const height = item.offsetHeight;
          glider.style.top = `${top}px`;
          glider.style.height = `${height}px`;
          glider.style.opacity = '1';
        });
      });

      group.addEventListener('mouseleave', () => {
        glider.style.opacity = '0';
      });
    });
  }

  // 7. Interactive Crosshair and Floating Tooltip for Dashboard Chart
  window.initDashboardChartMotion = function (chartInstance, containerEl) {
    if (!chartInstance || !containerEl) return;

    // Create crosshair line
    let crosshair = containerEl.querySelector('.chart-crosshair-line');
    if (!crosshair) {
      crosshair = document.createElement('div');
      crosshair.className = 'chart-crosshair-line';
      containerEl.appendChild(crosshair);
    }

    // Create glassmorphism tooltip
    let tooltipEl = containerEl.querySelector('.chart-glass-tooltip');
    if (!tooltipEl) {
      tooltipEl = document.createElement('div');
      tooltipEl.className = 'chart-glass-tooltip';
      tooltipEl.innerHTML = '<div class="tooltip-date"></div><div class="tooltip-value"></div>';
      containerEl.appendChild(tooltipEl);
    }

    const dateEl = tooltipEl.querySelector('.tooltip-date');
    const valEl = tooltipEl.querySelector('.tooltip-value');

    // Tooltip Physics Inertia State
    let currentX = 0;
    let currentY = 0;
    let targetX = 0;
    let targetY = 0;
    let isVisible = false;

    function renderPhysics() {
      if (isVisible) {
        currentX += (targetX - currentX) * 0.22;
        currentY += (targetY - currentY) * 0.22;
        tooltipEl.style.transform = `translate3d(${currentX.toFixed(1)}px, ${currentY.toFixed(1)}px, 0)`;
      }
      requestAnimationFrame(renderPhysics);
    }
    requestAnimationFrame(renderPhysics);

    const canvas = chartInstance.canvas;

    canvas.addEventListener('mousemove', (e) => {
      const points = chartInstance.getElementsAtEventForMode(e, 'index', { intersect: false }, true);
      if (points.length) {
        const point = points[0];
        const datasetIndex = point.datasetIndex;
        const index = point.index;
        const meta = chartInstance.getDatasetMeta(datasetIndex);
        const bar = meta.data[index];

        if (bar) {
          const yPos = bar.y;
          crosshair.style.top = `${yPos}px`;
          crosshair.style.opacity = '0.85';

          // Data values
          const rawVal = chartInstance.data.datasets[datasetIndex].data[index];
          const label = chartInstance.data.labels[index];

          dateEl.textContent = label;
          valEl.textContent = 'Rp ' + Number(rawVal).toLocaleString('id-ID');

          // Position tooltip with offset
          const containerRect = containerEl.getBoundingClientRect();
          targetX = e.clientX - containerRect.left + 16;
          targetY = Math.max(10, e.clientY - containerRect.top - 46);

          if (!isVisible) {
            currentX = targetX;
            currentY = targetY;
            isVisible = true;
          }
          tooltipEl.style.opacity = '1';
        }
      } else {
        crosshair.style.opacity = '0';
        tooltipEl.style.opacity = '0';
        isVisible = false;
      }
    });

    canvas.addEventListener('mouseleave', () => {
      crosshair.style.opacity = '0';
      tooltipEl.style.opacity = '0';
      isVisible = false;
    });
  };

  // 8. Ambient Interactive Background Spotlight (Disabled on touch devices)
  function initAmbientLoginSpotlight() {
    const wrapper = document.getElementById('authWrapper');
    const spotlight = document.getElementById('authAmbientSpotlight');
    if (!wrapper || !spotlight) return;

    // Only enable mouse spotlight on devices that support hover
    if (window.matchMedia && !window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
      return;
    }

    let isVisible = false;

    function handleMouseMove(e) {
      const rect = wrapper.getBoundingClientRect();
      const x = e.clientX - rect.left;
      const y = e.clientY - rect.top;

      wrapper.style.setProperty('--mouse-bg-x', `${x}px`);
      wrapper.style.setProperty('--mouse-bg-y', `${y}px`);

      if (!isVisible) {
        spotlight.style.opacity = '1';
        isVisible = true;
      }
    }

    function handleMouseLeave() {
      spotlight.style.opacity = '0';
      isVisible = false;
    }

    wrapper.addEventListener('mousemove', handleMouseMove, { passive: true });
    wrapper.addEventListener('mouseleave', handleMouseLeave);
  }

  // 9. Login Form Micro-Interactions (Password Toggle, Submit Spinner, Error Shake)
  function initLoginFormInteractions() {
    const form = document.getElementById('loginForm');
    const card = document.getElementById('loginCard');
    const pwdInput = document.getElementById('password');
    const toggleBtn = document.getElementById('passwordToggleBtn');
    const toggleIcon = document.getElementById('passwordToggleIcon');
    const submitBtn = document.getElementById('btnLogin');

    // Password Toggle with Micro-Motion (Rotation / Scale)
    if (toggleBtn && pwdInput) {
      toggleBtn.addEventListener('click', function (e) {
        e.preventDefault();
        const isPassword = pwdInput.getAttribute('type') === 'password';

        // Add micro-motion transition class
        toggleBtn.classList.add('toggling');

        setTimeout(() => {
          pwdInput.setAttribute('type', isPassword ? 'text' : 'password');
          toggleBtn.classList.toggle('toggled', isPassword);

          if (toggleIcon) {
            toggleIcon.setAttribute('data-lucide', isPassword ? 'eye-off' : 'eye');
            if (window.lucide && typeof window.lucide.createIcons === 'function') {
              window.lucide.createIcons();
            }
          }
          toggleBtn.classList.remove('toggling');
        }, 80);

        pwdInput.focus();
      });
    }

    // Form Submit Tactile Feedback & Loading Spinner
    if (form && submitBtn) {
      form.addEventListener('submit', function (e) {
        const usernameInput = document.getElementById('username');
        const usernameVal = usernameInput ? usernameInput.value.trim() : '';
        const passwordVal = pwdInput ? pwdInput.value : '';

        // Validation check before submitting
        if (!usernameVal || !passwordVal) {
          e.preventDefault();
          if (card) {
            card.classList.remove('error-shake');
            void card.offsetWidth; // Reflow to restart animation
            card.classList.add('error-shake');
          }
          if (!usernameVal && usernameInput) {
            usernameInput.focus();
          } else if (!passwordVal && pwdInput) {
            pwdInput.focus();
          }
          return;
        }

        // Activate tactile loading spinner
        submitBtn.classList.add('is-loading');
      });
    }

    // Clean up error-shake class after animation ends so it can re-trigger cleanly
    if (card && card.classList.contains('error-shake')) {
      card.addEventListener('animationend', function (e) {
        if (e.animationName === 'errorShake') {
          card.classList.remove('error-shake');
        }
      }, { once: true });
    }
  }

  // Run all motion initializers on DOM load
  document.addEventListener('DOMContentLoaded', () => {
    initTiltAndSpotlight();
    initOdometerCounters();
    initProgressFills();
    initMagneticButtons();
    initRipple();
    initSidebarGlider();
    initAmbientLoginSpotlight();
    initLoginFormInteractions();
  });
})();
