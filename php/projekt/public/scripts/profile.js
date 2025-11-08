document.addEventListener('DOMContentLoaded', () => {
            console.log('Profile script loaded');
            
            const toggle = document.querySelector('[data-message-toggle]');
            const panel = document.querySelector('[data-message-panel]');
            const closeBtn = document.querySelector('[data-message-close]');

            if (!toggle || !panel) {
                return;
            }

            const closePanel = () => {
                panel.classList.remove('is-open');
                toggle.setAttribute('aria-expanded', 'false');
            };

            const openPanel = () => {
                panel.classList.add('is-open');
                toggle.setAttribute('aria-expanded', 'true');
            };

            toggle.addEventListener('click', () => {
                const isOpen = panel.classList.contains('is-open');
                (isOpen ? closePanel : openPanel)();
            });

            closeBtn?.addEventListener('click', closePanel);

            document.addEventListener('click', (event) => {
                if (!panel.contains(event.target) && !toggle.contains(event.target)) {
                    closePanel();
                }
            });
        });