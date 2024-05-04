function reloadPageOnClose(eventType) {
    document.addEventListener(eventType, function(event) {
        location.reload();
    });
}
reloadPageOnClose('hidden.bs.modal');
reloadPageOnClose('hidden.bs.offcanvas');

(() => {
    'use strict'
    const forms = document.querySelectorAll('.needs-validation')
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            form.classList.add('was-validated')
        }, false)
    })
})()


const edit = document.getElementById('edit');
if (edit) {
    edit.addEventListener('show.bs.modal', async event => {
        try {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-bs-id');
            const route = `${window.location.href}/${id}`;
            const response = await fetch(route);
            if (!response.ok) {
                throw new Error('Erro ao carregar dados do servidor');
            }
            const data = await response.json();
            const editForm = edit.querySelector('form');
            editForm.action = route;
            for (const key in data) {
                if (Array.isArray(data[key])) {
                    data[key].forEach(item => {
                        const checkbox = edit.querySelector(`input[value="${item.name}"]`);
                        if (checkbox) {
                            checkbox.checked = true;
                        }
                    });
                } else {
                    const inputField = edit.querySelector(`[name="${key}"]`);
                    if (inputField) {
                        const inputType = inputField.tagName.toLowerCase();
                        switch (inputType) {
                            case 'input':
                            case 'textarea':
                                inputField.value = data[key];
                                if (inputField.type === 'radio' || inputField.type === 'checkbox') {
                                    inputField.checked = data[key];
                                }
                                break;
                            case 'select':
                                const options = inputField.querySelectorAll('option');
                                let optionFound = false;
                                options.forEach(option => {
                                    if (option.value === data[key]) {
                                        option.selected = true;
                                        optionFound = true;
                                    }
                                });
                                if (!optionFound) {
                                    console.log(`Nenhuma opção correspondente encontrada para '${key}'`);
                                }
                                break;
                            default:
                                console.log(`Tipo de input '${inputType}' não suportado.`);
                                break;
                        }
                    }
                }
            }
        } catch (error) {
            console.error('Erro:', error);
        }
    });
}

const destroy = document.getElementById('delete')
   const destroyForm = destroy.querySelector('form');
   if (destroy) {
      destroy.addEventListener('show.bs.modal', event => {
         const button = event.relatedTarget
         const id = button.getAttribute('data-bs-id')
         const route = `${window.location.href}/${id}`;
         destroyForm.action = route;
    });
}