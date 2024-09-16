document.addEventListener('DOMContentLoaded', () => {
    const modalTrigger = document.querySelector('[data-modal-target]');
    
    if (modalTrigger) { 
        const modal = document.querySelector(modalTrigger.getAttribute('data-modal-target'));
        const agreeBtn = document.getElementById('agreeBtn');
        const closeModal = document.getElementById('closeModal');

        if (modal) { 
            modalTrigger.addEventListener('click', (event) => {
                event.preventDefault();
                modal.classList.remove('hidden');
            });

            if (agreeBtn) {
                agreeBtn.addEventListener('click', () => {
                    modal.classList.add('hidden');
                    document.getElementById('terms').checked = true;
                });
            }

            if (closeModal) {
                closeModal.addEventListener('click', () => {
                    modal.classList.add('hidden');
                });
            }
        }
    }
});
