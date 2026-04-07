$(function () {
    var form = $('#venta-form');
    if (!form.length) {
        return;
    }

    var itemsDataRaw = form.attr('data-items');
    var existingItemsRaw = form.attr('data-sale-items');
    var promotionsDataRaw = form.attr('data-promotions');
    var selectedPromotionId = form.attr('data-selected-promotion') || '';
    var itemsData = itemsDataRaw ? JSON.parse(itemsDataRaw) : [];
    var existingItems = existingItemsRaw ? JSON.parse(existingItemsRaw) : [];
    var promotionsData = promotionsDataRaw ? JSON.parse(promotionsDataRaw) : [];
    var tbody = $('#venta-items-body');
    var addButton = $('#add-item-btn');
    var subtotalDisplay = $('#subtotal-display');
    var discountDisplay = $('#discount-display');
    var totalDisplay = $('#total-display');
    var promotionSelect = $('#promotion_id');
    var promotionDescription = $('#promotion-description');
    var ciField = $('#ci-field');
    var ciInput = $('#customer-ci');
    var rowIndex = 0;
    var formErrors = $('#form-errors');
    var errorList = formErrors.find('ul');
    var submitButton = form.find('button[type="submit"]');
    var originalButtonHtml = submitButton.html();

    ciField.addClass('hidden');

    function numberWithDots(value) {
        return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function formatCurrency(value) {
        var amount = parseFloat(value) || 0;
        return 'Bs ' + numberWithDots(amount.toFixed(2));
    }

    function buildOptions() {
        var options = '<option value="">Selecciona un ítem</option>';
        itemsData.forEach(function (item) {
            options += '<option value="' + item.id + '">' + item.name + '</option>';
        });
        return options;
    }

    function createRow(data) {
        var currentIndex = rowIndex++;
        var quantityValue = data && data.quantity ? data.quantity : 1;
        var selectValue = data && data.item_id ? data.item_id : '';

        var rowHtml = ''
            + '<tr data-row="' + currentIndex + '">'
            + '<td class="px-3 py-2">'
            + '<select name="items[' + currentIndex + '][item_id]" class="item-select w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">'
            + buildOptions()
            + '</select>'
            + '</td>'
            + '<td class="px-3 py-2">'
            + '<input type="text" name="items[' + currentIndex + '][unit_price]" class="item-unit-price w-full border border-slate-300 rounded-lg px-3 py-2 bg-slate-50" readonly value="0.00">'
            + '</td>'
            + '<td class="px-3 py-2">'
            + '<input type="number" min="1" name="items[' + currentIndex + '][quantity]" class="item-quantity w-20 border border-slate-300 rounded-lg px-3 py-2" value="' + quantityValue + '">'
            + '</td>'
            + '<td class="px-3 py-2">'
            + '<span class="line-total text-slate-800 font-semibold">Bs 0.00</span>'
            + '</td>'
            + '<td class="px-3 py-2">'
            + '<button type="button" class="remove-item text-red-600 font-semibold hover:underline">Eliminar</button>'
            + '</td>'
            + '</tr>';

        var row = $(rowHtml);
        tbody.append(row);
        attachRowEvents(row);
        if (selectValue) {
            row.find('.item-select').val(selectValue).trigger('change');
        } else {
            row.find('.item-select').trigger('change');
        }
        row.find('.item-quantity').val(quantityValue);
        updateLineTotal(row);
    }

    function attachRowEvents(row) {
        row.find('.item-select').on('change', function () {
            var selectedId = $(this).val();
            var item = itemsData.find(function (entry) {
                return entry.id === Number(selectedId);
            });

            var unitInput = row.find('.item-unit-price');
            if (item) {
                unitInput.val(item.price.toFixed(2));
            } else {
                unitInput.val('0.00');
            }
            updateLineTotal(row);
        });

        row.find('.item-quantity').on('input change', function () {
            updateLineTotal(row);
        });

        row.find('.remove-item').on('click', function () {
            row.remove();
            updateSummary();
        });
    }

    function updateLineTotal(row) {
        var quantity = parseInt(row.find('.item-quantity').val()) || 0;
        var unitPrice = parseFloat(row.find('.item-unit-price').val()) || 0;
        var lineTotal = quantity * unitPrice;
        row.find('.line-total').text(formatCurrency(lineTotal));
        updateSummary();
    }

    function getPromotionById(promotionId) {
        if (!promotionId) {
            return null;
        }
        return promotionsData.find(function (entry) {
            return entry.id === Number(promotionId);
        });
    }

    function applyPromotion(promotionId) {
        var promotion = getPromotionById(promotionId);
        if (promotion) {
            promotionDescription.text(promotion.description ?? '');

            if (promotion.single_use) {
                ciField.removeClass('hidden');
                ciInput.prop('required', true);
            } else {
                ciField.addClass('hidden');
                ciInput.prop('required', false).val('');
            }
        } else {
            promotionDescription.text('');
            ciField.addClass('hidden');
            ciInput.prop('required', false).val('');
        }

        updateSummary();
    }

    function updateSummary() {
        var subtotal = 0;
        tbody.find('tr').each(function () {
            var quantity = parseInt($(this).find('.item-quantity').val()) || 0;
            var unitPrice = parseFloat($(this).find('.item-unit-price').val()) || 0;
            subtotal += quantity * unitPrice;
        });

        var promotion = getPromotionById(promotionSelect.val());
        var discountAmount = 0;
        if (promotion) {
            var discountValueNum = parseFloat(promotion.discount_value) || 0;
            if (promotion.discount_type === 'percentage') {
                var percentage = Math.min(discountValueNum, 100);
                discountAmount = (percentage / 100) * subtotal;
            } else {
                discountAmount = Math.min(discountValueNum, subtotal);
            }
        }

        var total = Math.max(subtotal - discountAmount, 0);

        subtotalDisplay.text(formatCurrency(subtotal));
        discountDisplay.text(formatCurrency(discountAmount));
        totalDisplay.text(formatCurrency(total));
    }

    addButton.on('click', function () {
        createRow();
    });

    promotionSelect.on('change', function () {
        applyPromotion($(this).val());
    });

    if (existingItems.length) {
        existingItems.forEach(function (item) {
            createRow({
                item_id: item.item_id,
                quantity: item.quantity,
            });
        });
    } else {
        createRow();
    }

    formErrors.find('.field-error').text('');

    if (selectedPromotionId) {
        promotionSelect.val(selectedPromotionId);
    }
    applyPromotion(promotionSelect.val());

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
                console.error('Venta submit error', xhr);
                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    showErrors(xhr.responseJSON.errors);
                } else {
                    Swal.fire('Error', 'Ocurrió un error inesperado.', 'error');
                }
            },
            complete: function () {
                submitButton.prop('disabled', false).html(originalButtonHtml);
            }
        });
    });
});
