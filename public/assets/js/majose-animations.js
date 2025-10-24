// MajoseSport - Animaciones dinámicas y efectos interactivos
document.addEventListener('DOMContentLoaded', function() {
    
    // Animación de entrada escalonada para las tarjetas
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

    // Aplicar animación a todas las tarjetas
    document.querySelectorAll('.majose-card').forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = 'all 0.6s ease-out';
        card.style.transitionDelay = `${index * 0.1}s`;
        observer.observe(card);
    });

    // Efecto de hover dinámico para las tarjetas
    document.querySelectorAll('.majose-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px) scale(1.02)';
            this.style.boxShadow = '0 16px 48px rgba(229, 57, 53, 0.2)';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
            this.style.boxShadow = '0 8px 24px rgba(0, 0, 0, 0.15)';
        });
    });

    // Animación del panel de bienvenida
    const welcomePanel = document.querySelector('.majose-welcome-panel');
    if (welcomePanel) {
        welcomePanel.style.opacity = '0';
        welcomePanel.style.transform = 'scale(0.9)';
        
        setTimeout(() => {
            welcomePanel.style.transition = 'all 0.8s ease-out';
            welcomePanel.style.opacity = '1';
            welcomePanel.style.transform = 'scale(1)';
        }, 200);
    }

    // Efecto de typing para el título principal
    const titleElement = document.querySelector('.majose-welcome-panel h1');
    if (titleElement) {
        const originalText = titleElement.textContent;
        titleElement.textContent = '';
        
        let i = 0;
        const typeWriter = () => {
            if (i < originalText.length) {
                titleElement.textContent += originalText.charAt(i);
                i++;
                setTimeout(typeWriter, 100);
            }
        };
        
        setTimeout(typeWriter, 1000);
    }

    // Animación de números en las estadísticas
    function animateNumbers() {
        document.querySelectorAll('.majose-stat-number').forEach(element => {
            const target = parseInt(element.textContent.replace(/[^0-9]/g, ''));
            const duration = 2000;
            const increment = target / (duration / 16);
            let current = 0;
            
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                
                // Mantener formato original si tiene símbolos
                const originalText = element.textContent;
                if (originalText.includes('$')) {
                    element.textContent = '$' + Math.floor(current).toLocaleString();
                } else {
                    element.textContent = Math.floor(current).toLocaleString();
                }
            }, 16);
        });
    }

    // Iniciar animación de números cuando las tarjetas sean visibles
    const statsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                setTimeout(animateNumbers, 500);
                statsObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    const statsContainer = document.querySelector('.majose-stats-grid');
    if (statsContainer) {
        statsObserver.observe(statsContainer);
    }

    // Efecto de parallax suave en el panel de bienvenida
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        const parallax = document.querySelector('.majose-welcome-panel');
        if (parallax) {
            const speed = scrolled * 0.5;
            parallax.style.transform = `translateY(${speed}px)`;
        }
    });

    // Animación de carga para los gráficos
    const chartObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const canvas = entry.target;
                canvas.style.opacity = '0';
                canvas.style.transform = 'scale(0.8)';
                
                setTimeout(() => {
                    canvas.style.transition = 'all 0.8s ease-out';
                    canvas.style.opacity = '1';
                    canvas.style.transform = 'scale(1)';
                }, 300);
            }
        });
    }, { threshold: 0.3 });

    document.querySelectorAll('canvas').forEach(canvas => {
        chartObserver.observe(canvas);
    });

    // Efecto de ripple en botones
    document.querySelectorAll('.majose-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.classList.add('ripple');
            
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });

    // Añadir estilos para el efecto ripple
    const style = document.createElement('style');
    style.textContent = `
        .majose-btn {
            position: relative;
            overflow: hidden;
        }
        
        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: scale(0);
            animation: ripple-animation 0.6s linear;
            pointer-events: none;
        }
        
        @keyframes ripple-animation {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
        
        .majose-card {
            cursor: pointer;
        }
        
        .majose-card:hover .majose-icon {
            transform: scale(1.1) rotate(5deg);
        }
        
        .majose-icon {
            transition: all 0.3s ease-out;
        }
    `;
    document.head.appendChild(style);

    // Efecto de partículas flotantes en el fondo
    function createFloatingParticles() {
        const particleContainer = document.createElement('div');
        particleContainer.style.position = 'fixed';
        particleContainer.style.top = '0';
        particleContainer.style.left = '0';
        particleContainer.style.width = '100%';
        particleContainer.style.height = '100%';
        particleContainer.style.pointerEvents = 'none';
        particleContainer.style.zIndex = '1';
        document.body.appendChild(particleContainer);

        for (let i = 0; i < 20; i++) {
            const particle = document.createElement('div');
            particle.style.position = 'absolute';
            particle.style.width = '4px';
            particle.style.height = '4px';
            particle.style.background = 'rgba(229, 57, 53, 0.1)';
            particle.style.borderRadius = '50%';
            particle.style.left = Math.random() * 100 + '%';
            particle.style.top = Math.random() * 100 + '%';
            particle.style.animation = `float ${3 + Math.random() * 4}s ease-in-out infinite`;
            particle.style.animationDelay = Math.random() * 2 + 's';
            
            particleContainer.appendChild(particle);
        }
    }

    // Crear partículas flotantes
    createFloatingParticles();

    // Añadir animación CSS para las partículas
    const particleStyle = document.createElement('style');
    particleStyle.textContent = `
        @keyframes float {
            0%, 100% {
                transform: translateY(0px) translateX(0px);
                opacity: 0.1;
            }
            50% {
                transform: translateY(-20px) translateX(10px);
                opacity: 0.3;
            }
        }
    `;
    document.head.appendChild(particleStyle);

    console.log('🎨 MajoseSport: Animaciones dinámicas cargadas correctamente');
});
