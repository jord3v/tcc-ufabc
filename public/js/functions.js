function reloadPageOnClose(eventType) {
    document.addEventListener(eventType, function(event) {
        location.reload();
    });
}
reloadPageOnClose('hidden.bs.modal');
reloadPageOnClose('hidden.bs.offcanvas');

function add(event) {
    var botao = event.target;
    botao.removeEventListener('click', add);
    var widget = botao.closest('.list-group-item');
    var tabela = widget.querySelector('.table tbody');
    var novaLinha = document.createElement('tr');
    var rand = Math.round(Math.random() * 99999);
    const report = botao.getAttribute('data-bs-report')

    novaLinha.innerHTML = `
            <td><input type="hidden" class="form-control" name="payments[${rand}][report_id]" value="${report}" required><input type="text" class="form-control" name="payments[${rand}][invoice]" required></td>
            <td><input type="month" class="form-control" name="payments[${rand}][reference]" required></td>
            <td><input type="text" class="form-control" name="payments[${rand}][price]" required></td>
            <td><input type="date" class="form-control" name="payments[${rand}][due_date]" required></td>
            <td><input type="date" class="form-control" name="payments[${rand}][signature_date]" required></td>
            <td><button type="button" class="btn-link" onclick="remove(this)">Remover</button></td>
        `;
    tabela.appendChild(novaLinha);
}

function remove(botao) {
    var linha = botao.closest('tr');
    linha.parentNode.removeChild(linha);
}

var botoes = document.querySelectorAll('.btn-outline-primary');
botoes.forEach(function(botao) {
    botao.addEventListener('click', add);
});

(() => {
    'use strict';

    const verificarCamposInvalidos = (tabPane) => {
        const foo = document.getElementById(tabPane.id);
        const camposInvalidos = foo ? foo.querySelectorAll(':invalid').length : 0;
        return camposInvalidos;
    };

    const manipularElementoDOM = (elemento, camposInvalidos) => {
        elemento.classList.toggle('text-danger', camposInvalidos > 0);
        elemento.classList.toggle('text-success', camposInvalidos === 0);
        if (camposInvalidos > 0) {
            let textoQuantidade = elemento.querySelector('.quantidade-campos-invalidos');
            if (!textoQuantidade) {
                textoQuantidade = document.createElement('small');
                textoQuantidade.classList.add('quantidade-campos-invalidos');
                elemento.appendChild(textoQuantidade);
            }
            textoQuantidade.textContent = `(verifique os ${camposInvalidos} campos)`;
        } else {
            const textoQuantidade = elemento.querySelector('.quantidade-campos-invalidos');
            if (textoQuantidade) {
                textoQuantidade.remove();
            }
        }
    };

    const forms = document.querySelectorAll('.needs-validation');
    const tabPanes = document.querySelectorAll('.tab-pane');

    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
                tabPanes.forEach(tabPane => {
                    const seletor = `[href="#${tabPane.id}"]`;
                    const elemento = document.querySelector(seletor);
                    if (elemento) {
                        const camposInvalidos = verificarCamposInvalidos(tabPane);
                        manipularElementoDOM(elemento, camposInvalidos);
                    }
                });
            }
            form.classList.add('was-validated');
        }, false);
    });
})();


document.querySelectorAll('.group-checkbox').forEach(function(checkbox) {
    checkbox.addEventListener('click', function() {
        var isChecked = this.checked;
        var currentTr = this.parentNode.parentNode;
        var nextTr = currentTr.nextElementSibling;
        while (nextTr && nextTr.querySelector('td[colspan="4"]') === null) {
            nextTr.querySelector('input[type="checkbox"]').checked = isChecked;
            nextTr = nextTr.nextElementSibling;
        }
    });
});


const edit = document.getElementById('edit');
if (edit) {
    edit.addEventListener('show.bs.modal', async event => {
        try {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-bs-id');
            const route = `${window.location.origin + window.location.pathname}/${id}/edit`;
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
                                console.log(data[key] + 'recebe: '+inputField.name)
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
if (destroy) {
    const destroyForm = destroy.querySelector('form');
    destroy.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget
        const id = button.getAttribute('data-bs-id')
        const route = `${window.location.origin + window.location.pathname}/${id}`;
        destroyForm.action = route;
    });
}

const selectElement = document.getElementById('year-note');
selectElement.addEventListener('change', function() {
    const selectedYear = this.value;
    let currentUrl = window.location.href;
    currentUrl = currentUrl.split('?')[0];
    const newUrl = selectedYear ? `${currentUrl}?year=${selectedYear}` : currentUrl;
    window.location.href = newUrl;
});