$(function () {
    var form = $('#venta-form');
    if (!form.length) {
        return;
    }

    var itemsData = form.attr('data-items') ? JSON.parse(form.attr('data-items')) : [];
    var existingItems = form.attr('data-sale-items') ? JSON.parse(form.attr('data-sale-items')) : [];
    var categoriesContainer = $('#venta-items-body');
    var totalDisplay = $('#total-display');
    var balanceDueDisplay = $('#balance-due-display');
    var changeDisplay = $('#change-display');
    var paidAmountInput = $('#paid_amount');
    var formErrors = $('#form-errors');
    var errorList = formErrors.find('ul');
    var submitButton = form.find('button[type="submit"]');
    var originalButtonHtml = submitButton.html();
    var rowIndex = 0;

    function numberWithDots(value) {
        return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function formatCurrency(value) {
        var amount = parseFloat(value) || 0;
        return 'Bs ' + numberWithDots(amount.toFixed(2));
    }

    function createCategorySection(category) {
        var section = $('<div>').addClass('space-y-2 rounded-2xl border border-slate-200 bg-slate-50 p-3 shadow-sm');

        var header = $('<div>').addClass('flex items-center justify-between gap-2');
        $('<div>')
            .append(
                $('<p>').addClass('text-[0.65rem] font-semibold uppercase tracking-wide text-slate-500').text('Categoría'),
                $('<h4>').addClass('text-base font-semibold text-slate-900 truncate').text(category.name)
            )
            .appendTo(header);
        $('<span>').addClass('text-[0.65rem] font-semibold uppercase tracking-wide text-slate-500').text(category.items.length + ' ítems').appendTo(header);

        var grid = $('<div>').addClass('grid gap-2 sm:grid-cols-2 xl:grid-cols-3');
        section.append(header, grid);

        categoriesContainer.append(section);
        return grid;
    }

    function createItemCard(container, item, quantity, useOnceNumber) {
        var currentIndex = rowIndex++;
        var quantityValue = quantity != null ? quantity : 0;
        var basePrice = parseFloat(item.price) || 0;

        var card = $('<div>')
            .addClass('flex flex-col gap-2 rounded-lg border border-slate-100 bg-white p-3 text-sm shadow-sm item-card sm:flex-row sm:items-center sm:justify-between')
            .attr('data-base-price', basePrice);

        var description = $('<div>').addClass('flex-1 space-y-1 min-w-0');
        $('<input>').attr({
            type: 'hidden',
            name: 'items[' + currentIndex + '][item_id]',
            value: item.id
        }).appendTo(description);
        $('<input>').attr({
            type: 'hidden',
            name: 'items[' + currentIndex + '][unit_price]',
            value: basePrice.toFixed(2)
        }).appendTo(description);
        $('<p>').addClass('text-sm text-slate-500').text('Ítem').appendTo(description);
        $('<h4>').addClass('text-lg font-semibold text-slate-900').text(item.name).appendTo(description);
        $('<p>').addClass('text-sm text-slate-500').text(item.category_name || 'Sin categoría').appendTo(description);
        $('<p>').addClass('text-xs font-semibold text-emerald-600').text('Bs ' + basePrice.toFixed(2)).appendTo(description);
        $('<span>').addClass('inline-flex items-center gap-1 text-[0.65rem] uppercase tracking-wide text-slate-500').text(item.use_once ? 'Uso único' : 'Regular').appendTo(description);

        if (item.use_once) {
            var codeField = $('<div>').addClass('space-y-1 mt-3');
            $('<label>').addClass('text-xs font-semibold text-slate-600 uppercase tracking-wide').text('Código único').appendTo(codeField);
            $('<input>').attr({
                type: 'text',
                name: 'items[' + currentIndex + '][use_once_number]',
                placeholder: 'Código o número',
                value: useOnceNumber || ''
            }).addClass('w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500')
                .appendTo(codeField);
            description.append(codeField);
        }

        var quantityField = $('<div>').addClass('space-y-1 flex flex-col items-start sm:items-end max-w-[120px]');
        $('<label>').addClass('text-xs font-semibold text-slate-600 uppercase tracking-wide').text('Cantidad').appendTo(quantityField);
        $('<input>').attr({
            type: 'number',
            min: '0',
            step: '1',
            name: 'items[' + currentIndex + '][quantity]',
            value: quantityValue
        }).addClass('item-quantity w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500')
            .appendTo(quantityField);

        var totalField = $('<div>').addClass('text-right space-y-1');
        $('<p>').addClass('text-xs font-semibold text-slate-600 uppercase tracking-wide').text('Total').appendTo(totalField);
        $('<p>').addClass('line-total text-2xl font-bold text-slate-900').text('Bs 0.00').appendTo(totalField);

        card.append(description, quantityField, totalField);
        container.append(card);

        card.find('.item-quantity').on('input change', function () {
            updateLineTotal(card);
        });
        updateLineTotal(card);
    }

    function updateLineTotal(card) {
        var quantity = parseInt(card.find('.item-quantity').val()) || 0;
        var basePrice = parseFloat(card.data('base-price')) || 0;
        var lineTotal = quantity * basePrice;
        card.find('.line-total').text(formatCurrency(lineTotal));
        updateSummary();
    }

    function updateSummary() {
        var total = 0;
        $('.item-card').each(function () {
            var quantity = parseInt($(this).find('.item-quantity').val()) || 0;
            var basePrice = parseFloat($(this).data('base-price')) || 0;
            total += quantity * basePrice;
        });

        var paidAmount = parseFloat(paidAmountInput.val()) || 0;
        var change = Math.max(paidAmount - total, 0);
        var balanceDue = Math.max(total - paidAmount, 0);

        totalDisplay.text(formatCurrency(total));
        balanceDueDisplay.text(formatCurrency(balanceDue));
        changeDisplay.text(formatCurrency(change));
    }

    function clearErrors() {
        formErrors.addClass('hidden');
        errorList.empty();
        form.find('.field-error').text('');
        form.find('.border-red-500').removeClass('border-red-500');
    }

    function showErrors(errors) {
        formErrors.removeClass('hidden');
        $.each(errors, function (field, messages) {
            var message = messages && messages.length ? messages[0] : 'Error en el campo ' + field;
            errorList.append('<li data-field="' + field + '">' + message + '</li>');
            $('#' + field + '-error').text(message);
            $('#' + field).addClass('border-red-500');
        });
    }

    var existingMap = {};
    if (existingItems.length) {
        existingItems.forEach(function (item) {
            existingMap[item.item_id] = {
                quantity: item.quantity,
                use_once_number: item.use_once_number || ''
            };
        });
    }

    var categories = [];
    var categoryIndex = {};

    categoriesContainer.empty();
    itemsData.forEach(function (item) {
        var catId = item.category_id ?? 'sin-categoria';
        if (!categoryIndex[catId]) {
            categoryIndex[catId] = {
                id: catId,
                name: item.category_name || 'Sin categoría',
                items: []
            };
            categories.push(categoryIndex[catId]);
        }
        categoryIndex[catId].items.push(item);
    });

    categories.sort(function (a, b) {
        return a.name.localeCompare(b.name);
    });

    if (!categories.length) {
        categoriesContainer.html('<p class="text-sm text-slate-500">No hay ítems habilitados en catálogo.</p>');
    } else {
        categories.forEach(function (category) {
            category.items.sort(function (a, b) {
                return a.name.localeCompare(b.name);
            });
            var sectionContainer = createCategorySection(category);
            category.items.forEach(function (item) {
                var existing = existingMap[item.id] || {};
                createItemCard(sectionContainer, item, existing.quantity ?? 0, existing.use_once_number ?? '');
            });
        });
    }

    updateSummary();

    paidAmountInput.on('input change', function () {
        updateSummary();
    });

    form.on('submit', function (event) {
        event.preventDefault();
        clearErrors();

        var url = form.data('url');
        var method = form.data('method') || 'POST';
        var formData = new FormData(this);

        if (method !== 'POST') {
            formData.append('_method', method);
        }

        submitButton.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Guardando...');

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            },
            success: function (response) {
                if (response.pdf_url) {
                    window.open(response.pdf_url, '_blank');
                }
                Swal.fire({
                    title: 'Listo',
                    text: response.message,
                    icon: 'success',
                    timer: 2000,
                    timerProgressBar: true,
                    showConfirmButton: false
                }).then(() => {
                    if (response.redirect) {
                        window.location.href = response.redirect;
                    }
                });
            },
            error: function (xhr) {
                console.error('Error al guardar la venta:', xhr.status, xhr.responseJSON ?? xhr.responseText);
                if (xhr.responseJSON?.message) {
                    console.error('Detalle del servidor:', xhr.responseJSON.message);
                }
                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    showErrors(xhr.responseJSON.errors);
                } else {
                    Swal.fire('Error', xhr.responseJSON?.message || 'Ocurrió un error inesperado.', 'error');
                }
            },
            complete: function () {
                submitButton.prop('disabled', false).html(originalButtonHtml);
            }
        });
    });
});
