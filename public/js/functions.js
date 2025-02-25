function reloadPageOnClose(eventType, ignoreModalToReload) {
    document.addEventListener(eventType, function(event) {
        if (ignoreModalToReload.includes(event.target)) {
            return;
        }
        location.reload();
    });
}

var ignoreModalToReload = [
    document.querySelector('#occurrences'),
    document.querySelector('#bulk-fill'),
    document.querySelector('#report-create'),
    document.querySelector('#activities'),
];

function add(event) {
    var botao = event.target;
    botao.removeEventListener('click', add);
    var widget = botao.closest('.list-group-item');
    var tabela = widget.querySelector('.table tbody');
    var novaLinha = document.createElement('tr');
    var rand = Math.round(Math.random() * 99999);
    const report = botao.getAttribute('data-bs-report')
    novaLinha.innerHTML = `
            <td><div class="d-none"><input type="hidden" name="payments[${rand}][report_id]" value="${report}" required=""><textarea name="payments[${rand}][occurrences][occurrence]"></textarea><textarea name="payments[${rand}][occurrences][failures]"></textarea><textarea name="payments[${rand}][occurrences][suggestions]"></textarea></div><input type="text" class="form-control" name="payments[${rand}][invoice]" required></td>
            <td><input type="month" class="reference form-control" name="payments[${rand}][reference]" required></td>
            <td><input type="text" class="money form-control" name="payments[${rand}][price]" required></td>
            <td><input type="date" class="due_date form-control" name="payments[${rand}][due_date]" required></td>
            <td><input type="date" class="signature_date form-control" name="payments[${rand}][signature_date]" required></td>
            <td><div class="row"><div class="col-6" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Eventuais ocorrências" data-bs-original-title="Eventuais ocorrências"><button type="button" class="btn btn-default btn-icon text-primary" data-bs-toggle="modal" data-bs-target="#occurrences" data-bs-id="${rand}"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file-alert"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M14 3v4a1 1 0 0 0 1 1h4"></path><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"></path><path d="M12 17l.01 0"></path><path d="M12 11l0 3"></path></svg></button></div><div class="col-6" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Remover pagamento" data-bs-original-title="Remover pagamento"><button type="button" class="btn btn-default btn-icon text-danger" onclick="remove(this)"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-trash"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M4 7l16 0"></path><path d="M10 11l0 6"></path><path d="M14 11l0 6"></path><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path></svg></button></div></div></td>
        `;
    tabela.appendChild(novaLinha);
    $('.money').mask('000.000.000.000.000,00', {
        reverse: true
    });
    var novosBotoes = novaLinha.querySelectorAll('[data-bs-toggle="tooltip"]');
    novosBotoes.forEach(function(botao) {
        new bootstrap.Tooltip(botao);
    });
}

function remove(botao) {
    var linha = botao.closest('tr');
    linha.parentNode.removeChild(linha);
}

function put() {
    function putValue(nomeCampo) {
        var campoOrigem = document.querySelector('[name="' + nomeCampo + '"]');
        if (!campoOrigem) return; // Evita erro se o campo não existir

        var valorCampo = campoOrigem.value; // Funciona tanto para input quanto select

        document.querySelectorAll('.' + nomeCampo).forEach(function(campo) {
            if (campo.tagName === 'SELECT') {
                if ([...campo.options].some(option => option.value === valorCampo)) {
                    campo.value = valorCampo;
                }
            } else {
                campo.value = valorCampo;
            }
        });
    }

    putValue('reference');
    putValue('due_date');
    putValue('signature_date');
    putValue('manager');
}

function lastInvoice(id) {
    // Fazer a requisição HTTP para obter o pagamento com o ID especificado usando o método POST
    fetch('last', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // Adicione isso se estiver usando Laravel com proteção CSRF
            },
            body: JSON.stringify({
                id: id
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erro ao buscar pagamento');
            }
            return response.json();
        })
        .then(data => {
            const paymentInvoiceInput = document.querySelector(`#myTable_${id} tr[data-id="${id}"] .invoice`);
            paymentInvoiceInput.value = data.last_invoice; // Supondo que o retorno da requisição seja um objeto com a propriedade "last_invoice"
        })
        .catch(error => {
            Swal.fire({
                title: 'Ops!',
                text: 'O relatório possui contas com diferentes números. Deverá preencher manualmente.',
                icon: 'error',
                confirmButtonText: 'Entendido'
            })
        });
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

function handleCheckboxGroup(groupClass, colspanValue) {
    document.querySelectorAll(groupClass).forEach(function(checkbox) {
        checkbox.addEventListener('click', function() {
            var isChecked = this.checked;
            var currentTr = this.parentNode.parentNode;
            var nextTr = currentTr.nextElementSibling;
            while (nextTr && nextTr.querySelector('td[colspan="' + colspanValue + '"]') === null) {
                nextTr.querySelector('input[type="checkbox"]').checked = isChecked;
                nextTr = nextTr.nextElementSibling;
            }
        });
    });
}

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
            editForm.action = route.replace('/edit', '');
            for (const key in data) {
                if (Array.isArray(data[key])) {
                    console.log(key);
                    data[key].forEach(item => {
                        const checkbox = edit.querySelector(`input[name="${key}[]"][value="${item.id}"]`);
                        if (checkbox) {
                            checkbox.checked = true;
                        } else {
                            const select = edit.querySelector(`select[name="${key}[]"]`);
                            if (select && select.multiple) {
                                for (let option of select.options) {
                                    if (option.value == item.id) {
                                        option.selected = true;
                                    }
                                }
                            }
                        }
                    });                    
                } else if (isObject(data[key])) {
                    var array = data[key];
                    for (const item in array) {
                        edit.querySelector(`textarea[name="${key}[${item}]"]`).value = array[item];
                    }
                } else {
                    const inputField = edit.querySelector(`[name="${key}"]`);
                    if (inputField) {
                        const inputType = inputField.tagName.toLowerCase();
                        switch (inputType) {
                            case 'input':
                            case 'textarea':
                                //console.log(inputField.name + ':' +data[key])
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
            var moneyFormat = ['amount', 'monthly_payment', 'price'];
            moneyFormat.forEach(function(item) {
                var input = data[item];
                var valNumber = parseFloat(input);
                if (!isNaN(valNumber)) {
                    var valorFormatado = valNumber.toLocaleString('pt-BR', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                    $(`input[name="${item}"]`).val(valorFormatado);
                }
            });
        } catch (error) {
            console.error('Erro:', error);
        }
    });
}

const modalOccurrences = document.getElementById('occurrences');

if (modalOccurrences) {
    modalOccurrences.addEventListener('show.bs.modal', async event => {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-bs-id');
        const occurrenceIdField = modalOccurrences.querySelector('[name="occurrence_id"]');
        const occurrenceTextarea = modalOccurrences.querySelector('[name="occurrence"]');
        const failuresTextarea = modalOccurrences.querySelector('[name="failures"]');
        const suggestionsTextarea = modalOccurrences.querySelector('[name="suggestions"]');
        if (occurrenceIdField && occurrenceTextarea && failuresTextarea && suggestionsTextarea) {
            occurrenceIdField.value = id;
            occurrenceTextarea.value = getValue(`textarea[name="payments[${id}][occurrences][occurrence]"]`);
            failuresTextarea.value = getValue(`textarea[name="payments[${id}][occurrences][failures]"]`);
            suggestionsTextarea.value = getValue(`textarea[name="payments[${id}][occurrences][suggestions]"]`);
        }
    });

    modalOccurrences.querySelector('form').addEventListener('submit', function(event) {
        event.preventDefault();
        const occurrenceIdInput = modalOccurrences.querySelector('[name="occurrence_id"]');
        const occurrenceInput = modalOccurrences.querySelector('[name="occurrence"]');
        const failuresInput = modalOccurrences.querySelector('[name="failures"]');
        const suggestionsInput = modalOccurrences.querySelector('[name="suggestions"]');
        if (occurrenceIdInput && occurrenceInput && failuresInput && suggestionsInput) {
            const occurrenceId = occurrenceIdInput.value;
            const occurrence = occurrenceInput.value;
            const failures = failuresInput.value;
            const suggestions = suggestionsInput.value;
            setValue(`textarea[name="payments[${occurrenceId}][occurrences][occurrence]"]`, occurrence);
            setValue(`textarea[name="payments[${occurrenceId}][occurrences][failures]"]`, failures);
            setValue(`textarea[name="payments[${occurrenceId}][occurrences][suggestions]"]`, suggestions);
            const bootstrapModal = bootstrap.Modal.getInstance(modalOccurrences);
            if (bootstrapModal) {
                bootstrapModal.hide();
            }
        }
    });
}

function getValue(selector) {
    const element = document.querySelector(selector);
    return element ? element.value : '';
}

function setValue(selector, value) {
    const element = document.querySelector(selector);
    if (element) {
        element.value = value;
    }
}

function isObject(value) {
    return value !== null && typeof value === 'object' && !Array.isArray(value);
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

const addAttachment = document.getElementById('add-attachment')
if (addAttachment) {
    addAttachment.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget
        const id = button.getAttribute('data-bs-id')
        const uuid = button.getAttribute('data-bs-uuid')
        const process = button.getAttribute('data-bs-process')
        addAttachment.querySelector('input[name="id"]').value = id;
        addAttachment.querySelector('input[name="uuid"]').value = uuid;
        const title = addAttachment.querySelector('.modal-title')
        title.textContent = `Incluir anexo no protocolo ${process}`
    })
}

function highlightLines() {
    var linhas = document.querySelectorAll("#tabela tr");
    linhas.forEach(function(linha, index) {
        for (var i = index + 1; i < linhas.length; i++) {
            if (
                linha.getAttribute("data-company") === linhas[i].getAttribute("data-company") &&
                linha.getAttribute("data-reference") === linhas[i].getAttribute("data-reference") &&
                linha.getAttribute("data-signature") === linhas[i].getAttribute("data-signature") &&
                linha.getAttribute("data-location") === linhas[i].getAttribute("data-location")
            ) {
                // Adicionar classe de destaque
                linha.classList.add("highlight");
                linhas[i].classList.add("highlight");
            }
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const countableCheckboxes = document.querySelectorAll('input[type="checkbox"]:not(.group-checkbox-reports):not(.group-checkbox-reports-downloads)');
    const allCheckboxes = document.querySelectorAll('input[type="checkbox"]');
    const dynamicButtons = document.querySelectorAll('.dynamic-button');

    function updateButtons() {
        let selectedCount = 0;

        countableCheckboxes.forEach(checkbox => {
            if (checkbox.checked) {
                selectedCount++;
            }
        });

        dynamicButtons.forEach(button => {
            const baseText = button.getAttribute('data-base-text');
            if (selectedCount > 0) {
                button.disabled = false;
                button.innerHTML = baseText.replace('itens', `${selectedCount} itens`);
            } else {
                button.disabled = true;
                button.innerHTML = baseText;
            }
        });
    }

    allCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateButtons);
    });

    updateButtons();
});

$(document).ready(function() {
    $('.money').mask('000.000.000.000.000,00', {
        reverse: true
    });
    $('.cnpj').mask('00.000.000/0000-00', {
        reverse: true
    });
    $('.process').mask('0000/0000', {
        reverse: true
    });
    $('.process-erp').mask('0000/000000', {
        reverse: true
    });
});

document.addEventListener('DOMContentLoaded', function() {
    function updateSelectedText(selectElement) {
        var selectedOption = selectElement.options[selectElement.selectedIndex];
        var selectedText = selectedOption.text;
        var spanElement = document.getElementById(selectElement.getAttribute('data-span-id'));
        if (spanElement) {
            spanElement.textContent = selectedText;
        }
    }

    // Inicializa todos os elementos select com a classe 'dynamic-select'
    var selects = document.querySelectorAll('select.dynamic-select');
    selects.forEach(function(selectElement) {
        updateSelectedText(selectElement);
        selectElement.addEventListener('change', function() {
            updateSelectedText(selectElement);
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const button = document.getElementById('pendings-button');
    const url = '/dashboard/payments/pending/total'; // Substitua com o caminho da sua rota

    // Função para carregar o conteúdo da rota e atualizar o botão
    function updateButtonContent() {
        fetch(url)
            .then(response => response.text())
            .then(html => {
                button.innerHTML = html;
            })
            .catch(error => {
                console.error('Erro ao carregar o conteúdo:', error);
            });
    }

    // Atualize o botão quando a página for carregada
    updateButtonContent();
});

function checkStatus() {
    var btn = document.getElementById('status-btn');
    btn.innerHTML = "Verificando situação...";
    btn.classList.add('disabled');
}

function updateProgressBars() {
    const progressBars = document.querySelectorAll('.progress-bar');
    progressBars.forEach(function(bar) {
        let width = parseFloat(bar.style.width); 
        if (width > 75) {
            bar.classList.remove('bg-info');
            bar.classList.add('bg-danger');
        }
    });
}
document.addEventListener('DOMContentLoaded', updateProgressBars);

document.addEventListener("DOMContentLoaded", function() {
    // Obtém os parâmetros da URL atual
    const urlParams = new URLSearchParams(window.location.search);

    // Verifica se existe algum parâmetro 'modal' na URL
    const modalId = urlParams.get('modal');

    if (modalId) {
        // Seleciona o modal dinamicamente pelo ID extraído do parâmetro
        const modalElement = document.getElementById(modalId);

        // Verifica se o modal com o ID existe
        if (modalElement) {
            // Usa o Bootstrap para abrir o modal
            const bootstrapModal = new bootstrap.Modal(modalElement);
            bootstrapModal.show();
        } else {
            console.error(`Modal com ID '${modalId}' não encontrado.`);
        }
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const buttons = document.querySelectorAll('.view-activity');
    buttons.forEach(button => {
        button.addEventListener('click', function () {
            const activityId = this.getAttribute('data-id');
            const modalContent = document.getElementById('modalContent');

            // Limpa o conteúdo do modal
            modalContent.innerHTML = '<p>Carregando...</p>';

            // Faz a requisição
            const url = `/dashboard/activities/${activityId}`;
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        modalContent.innerHTML = `<p>${data.error}</p>`;
                        return;
                    }

                    // Monta a descrição básica
                    let content = `<p><strong>Descrição:</strong> ${data.description}</p>`;
                    content += `<p><strong>Tipo:</strong> ${data.log_name}</p>`;
                    content += `<p><strong>Evento:</strong> ${data.event}</p>`;
                    content += `<p><strong>Criado em:</strong> ${new Date(data.created_at).toLocaleString()}</p>`;

                    // Monta a tabela
                    content += '<table class="table">';
                    content += '<thead><tr><th>Campo</th>';

                    // Adiciona cabeçalhos condicionais
                    if (data.event === 'deleted') {
                        content += '<th>Antes</th>';
                    } else if (data.properties.old) {
                        content += '<th>Antes</th><th>Depois</th>';
                    } else {
                        content += '<th>Valor</th>';
                    }

                    content += '</tr></thead><tbody>';

                    if (data.event === 'deleted') {
                        // Caso "deleted", exibe apenas os valores de "old"
                        const oldValues = data.properties.old;
                        for (const key in oldValues) {
                            if (typeof oldValues[key] === 'object') {
                                // Renderiza objetos internos (como occurrences) de forma detalhada
                                for (const subKey in oldValues[key]) {
                                    content += `<tr>
                                        <td>${key}.${subKey}</td>
                                        <td>${oldValues[key][subKey] || '-'}</td>
                                    </tr>`;
                                }
                            } else {
                                content += `<tr>
                                    <td>${key}</td>
                                    <td>${oldValues[key]}</td>
                                </tr>`;
                            }
                        }
                    } else if (data.properties.old) {
                        // Caso "updated", exibe "old" e "attributes"
                        const oldValues = data.properties.old;
                        const newValues = data.properties.attributes;
                        const keys = new Set([...Object.keys(oldValues), ...Object.keys(newValues)]);

                        keys.forEach(key => {
                            if (typeof oldValues[key] === 'object' || typeof newValues[key] === 'object') {
                                // Renderiza objetos internos
                                const subKeys = new Set([
                                    ...(oldValues[key] ? Object.keys(oldValues[key]) : []),
                                    ...(newValues[key] ? Object.keys(newValues[key]) : [])
                                ]);

                                subKeys.forEach(subKey => {
                                    content += `<tr>
                                        <td>${key}.${subKey}</td>
                                        <td>${oldValues[key]?.[subKey] || '-'}</td>
                                        <td class="${(oldValues[key]?.[subKey] !== newValues[key]?.[subKey]) ? 'text-success' : ''}">
                                            ${newValues[key]?.[subKey] || '-'}
                                        </td>
                                    </tr>`;
                                });
                            } else {
                                const oldValue = oldValues[key] || '-';
                                const newValue = newValues[key] || '-';
                                const changedClass = oldValue !== newValue ? 'text-success' : ''; // Classe para destacar alterações
                                content += `<tr>
                                    <td>${key}</td>
                                    <td>${oldValue}</td>
                                    <td class="${changedClass}">${newValue}</td>
                                </tr>`;
                            }
                        });
                    } else {
                        // Caso sem "old", exibe apenas os atributos
                        const attributes = data.properties.attributes;
                        for (const key in attributes) {
                            if (typeof attributes[key] === 'object') {
                                // Renderiza objetos internos
                                for (const subKey in attributes[key]) {
                                    content += `<tr>
                                        <td>${key}.${subKey}</td>
                                        <td>${attributes[key][subKey]}</td>
                                    </tr>`;
                                }
                            } else {
                                content += `<tr>
                                    <td>${key}</td>
                                    <td>${attributes[key]}</td>
                                </tr>`;
                            }
                        }
                    }

                    content += '</tbody></table>';
                    modalContent.innerHTML = content;
                })
                .catch(error => {
                    modalContent.innerHTML = '<p>Erro ao carregar os dados.</p>';
                    console.error(error);
                });
        });
    });
});



reloadPageOnClose('hidden.bs.modal', ignoreModalToReload);
handleCheckboxGroup('.group-checkbox-reports', '5');
handleCheckboxGroup('.group-checkbox-reports-downloads', '3');
highlightLines();