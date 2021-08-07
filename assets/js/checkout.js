function moveUserStepByStep(idsToDisable, idToAble, callback) {
    const [disableIdOne, disableIdTwo] = idsToDisable
    const containerToBeDisableOne = document.getElementById(disableIdOne)
    const containerToBeDisableTwo = document.getElementById(disableIdTwo)
    const containerToBeAble = document.getElementById(idToAble)

    containerToBeDisableOne.children[1].classList.add('chet')
    containerToBeDisableTwo.children[1].classList.add('chet')
    // containerToBeDisable.children[1].classList.remove('tehc')
    containerToBeAble.children[1].classList.remove('chet')
    // containerToBeAble.children[1].classList.add('tehc')

    console.log('as');
    setTimeout(() => {
        containerToBeDisableOne.children[1].classList.add('hiddenn')
        containerToBeDisableTwo.children[1].classList.add('hiddenn')
    }, 700)
    containerToBeDisableOne.classList.add('drop-disable')
    containerToBeDisableTwo.classList.add('drop-disable')
    containerToBeAble.classList.remove('drop-disable')

    setTimeout(() => {
        containerToBeAble.children[1].classList.remove('hiddenn')
        // console.log(callback);
        callback()
    }, 700)
}

function movePencilIcon(idToDisable, id, itsGoingBack = false) {
    console.log(idToDisable, id, 'saaaaaaaaaa');
    // console.log(idToDisable, id, 'sadasd');
    const whereToMoveIcon = document.querySelector('#' + idToDisable + ' .drop-tilte')
    // const icon = document.getElementById('pencil-icon')

    whereToMoveIcon.insertAdjacentHTML('afterend', `
        <div id="pencil-icon${id}" class="pencil-cont pencil${id}">
            <p>Editar</p>
        </div>
    `)

    if (itsGoingBack) document.getElementById('pencil-icon' + id).addEventListener('click', () => events[itsGoingBack]())
}

function removePencilIcon(step) {
    console.log(document.querySelectorAll('#' + step + ' .pencil-cont'));

    document.querySelector('#' + step + ' .pencil-cont').remove()
    // for (let i = 0; i < document.querySelectorAll('#' + step + ' .pencil-cont').length; i++) {
    //     const element = document.querySelectorAll('#' + step + ' .pencil-cont')[i];
    //     element.remove()
    //     if (i === document.querySelectorAll('#' + step + ' .pencil-cont').length - 1) {
    //         break
    //     }
    // }
}

const events = {
    continueStepTwo: () => moveUserStepByStep(['step-1', 'step-3'], 'step-2', () => movePencilIcon('step-1', '1', 'backStepOne')),
    backStepOne: () => moveUserStepByStep(['step-2', 'step-3'], 'step-1', () => removePencilIcon('step-1')),
    moveStepTree: () => moveUserStepByStep(['step-2', 'step-1'], 'step-3', () => movePencilIcon('step-2', '2', 'backStepTwo')),
    backStepTwo: () => moveUserStepByStep(['step-3', 'step-1'], 'step-2', () => removePencilIcon('step-2'))
}

function checkWhatStepIs(ev) {
    return events[ev.target.id]()
}

let isInStepThree = false
function thingsToDoBeforMoveStep(e) {
    e.preventDefault()
    if (document.getElementById('payment').classList.contains('none')) {
        document.getElementById('payment').classList.remove('none')
    }
    
    isInStepThree = true
    jQuery('form.checkout').validate().destroy()
    let isNewUser = false
    let retiroIsDisable = false
    let idOfRetiro = ''
    let idOfPasswordAndEmail = ''
    let idOfBillings = `#billing_first_name,
    #billing_last_name,
    #billing_address_1,
    #billing_city,
    #billing_state,
    #billing_postcode,
    #billing_phone`;
    let idOfShippings = `#shipping_first_name,
    #shipping_last_name,
    #shipping_country,
    #shipping_address_1,
    #shipping_city,
    #shipping_state`

    document.getElementById('retiro').options[0].value = ''
    document.getElementById('envios').options[0].value = ''
    if (document.querySelector('#account_password')) {
        isNewUser = true
        idOfPasswordAndEmail = `#account_password,`
        // idOfPasswordAndEmail = `#billing_email, #account_password,`
    }
    if (document.getElementById('retiro_field').classList.contains('none')) {
        retiroIsDisable = true
        idOfRetiro += `#envios, `
    } else {
        idOfRetiro += `#retiro, `
    }

    jQuery.validator.addMethod("require", function (value, element, arg) {
        return arg !== value;
    }, "Value must not equal arg.");


    removeAllHtmlWithThisClass('woocommerce-NoticeGroup')

    validate_billing(isNewUser, retiroIsDisable)
    if (document.getElementById('ship-to-different-address-checkbox').checked) {
        jQuery('form.checkout').validate().destroy()
        validate_shipping(isNewUser)
        if (jQuery(idOfPasswordAndEmail + idOfBillings + ',' + idOfShippings).valid()) {
            if (validated()) {
                checkWhatStepIs(e)
            }
        }
    } else {
        if (jQuery(idOfPasswordAndEmail + idOfRetiro + idOfBillings).valid()) {
            if (validated()) {
                checkWhatStepIs(e)
            }
        }
    }
}

function showCompanyInput() {
    let selectIdn = document.getElementById('codigo_documento');

    selectIdn.addEventListener('change', function () {
        if (this.value === 'option_3') {
            document.getElementById('billing_company_field').classList.remove('none')
        } else {
            document.getElementById('billing_company_field').classList.add('none')
        }
    })
}


function removeAllHtmlWithThisClass(clase) {
    if (document.getElementsByClassName(clase)) {
        for (let i = 0; i < document.getElementsByClassName(clase).length; i++) {
            document.getElementsByClassName(clase)[i].remove();
        }
    }
}


function validated() {
    let arrayOfErrors = [];

    if (validationIdn('billing_cid') === 'cantidad no aceptada') {
        arrayOfErrors.push('Error la cantidad de digitos no es aceptada en la Cédula, por favor corrijalo')
    }
    if (validationIdn('billing_cid') === 'no hay nada') {
        arrayOfErrors.push('Error no hay nada en la Cédula, por favor corrijalo')
    }
    if (validationIdn('billing_cid') === 'hay una letra') {
        arrayOfErrors.push('Error hay letras en la Cédula, por favor corrijalo')
    }
    if (validacionCellphone('billing_phone') === 'no es un numero valido') {
        arrayOfErrors.push('Error No es un numero valido, por favor ingrese un numero de venezuela valido, Ejemplo: 0424 123 4567');
    }

    if (validacionCellphone('billing_phone') === 'no estan en los metodos de pago') {
        arrayOfErrors.push('Error No es un metodo disponible el que puso, por favor ingrese uno de los disponibles')
    }

    if (arrayOfErrors.length === 0) {
        return true
    } else {
        showError(arrayOfErrors)
        return false
    }
}

function showError(mensaje) {

    jQuery('.woocommerce-notices-wrapper').prepend(`
        <div class="woocommerce-NoticeGroup woocommerce-NoticeGroup-checkout">
            <ul class="woocommerce-error" role="alert">
            </ul>
        </div>
    `);

    mensaje.forEach(elem => {
        document.getElementsByClassName('woocommerce-error')[0].insertAdjacentHTML('afterbegin', '<li>' + elem + '</li>')
    })
    jQuery('html, body').animate({ scrollTop: 0 }, 'slow');

}

function controllerOfSteps() {
    const buttonContinueToStepTwo = document.getElementById('continueStepTwo')
    const buttonBackToStepOne = document.getElementById('backStepOne')
    const buttonContinueToStepTree = document.getElementById('moveStepTree')
    const buttonBackStepTwo = document.getElementById('backStepTwo')

    buttonContinueToStepTwo.addEventListener('click', checkWhatStepIs)
    buttonContinueToStepTree.addEventListener('click', thingsToDoBeforMoveStep)
    buttonBackToStepOne.addEventListener('click', checkWhatStepIs)
    buttonBackStepTwo.addEventListener('click', checkWhatStepIs)
}

function observerW() {
    // target element that we will observe
    const target = document.getElementsByClassName('checkout')[0];

    // config object
    const config = {
        characterData: true,
        childList: true,
        subtree: true
    };

    // subscriber function
    function subscriber(mutations) {
        mutations.forEach(mutation => {
            // console.log(mutation);
            if (mutation.target === document.querySelector('.drop-cont-table.flexx')) {
                if (mutation.addedNodes.length > 0) {
                    console.log('aaaaa');
                    document.querySelector('#total_orden').insertBefore(document.querySelector('#type_shipping_field'), document.querySelector('#total_orden .order-total'))

                    controllerOfSteps()
                }

            }
            // if (mutation.target === document.getElementById('place_order')) {
            //     document.querySelector('#total_orden').insertBefore(document.querySelector('#type_shipping_field'), document.querySelector('#total_orden .order-total'))
            //     // document.querySelector('#total_orden').appendChild(document.querySelector('#type_shipping_field'))
            // }
            if (mutation.target === document.getElementById('select2-billing_city-container')) {
                // jQuery('body').trigger('update_checkout');
                // if (!document.getElementById('ui-bloq')) {
                //     document.querySelector('#step-2 .drop-cont-table #customer_details').insertAdjacentHTML('beforeend', `
                //         <div id="ui-bloq" class="blockUI blockOverlay" style="z-index: 1000; border: medium none; margin: 0px; padding: 0px; width: 100%; height: 100%; top: 0px; left: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; opacity: 0.6; cursor: default; position: absolute;"></div>
                //     `)
                // }
                
                if (mutation.addedNodes[0]) {
                    if (mutation.addedNodes[0].data in citys) {

                        document.getElementById('envios_field').classList.add('none')
                        document.getElementById('retiro_field').classList.remove('none')
                        console.log('yes city');
                        if (document.getElementById('retiro').value === 'retiro_1') {
                            jQuery('#type_shipping_field [data-title]')[0].innerText = `costo de ${getCitySelected(document.getElementById('billing_city').value)}$ adicionales al total.`
                        } else if (document.getElementById('retiro').value === 'retiro_2') {
                            jQuery('#type_shipping_field [data-title]')[0].innerText = `Gratis`
                        }
                    } else {
                        document.getElementById('envios_field').classList.remove('none')
                        document.getElementById('retiro_field').classList.add('none')
                        jQuery('#type_shipping_field [data-title]')[0].innerText = `COBRO A DESTINO (Selecciona una empresa de envio)`
                    }
                }
            }
        })
    }

    // instantiating observer
    const observer = new MutationObserver(subscriber);

    // observing target
    observer.observe(target, config);
}

function grabPriceOfBf() {
    document.querySelectorAll('.currency.hidden p')[1].innerText = document.getElementById('tasa-hoy').innerText.replace('\n', '').replace('Bs.S', '')
    document.querySelectorAll('.total-bf p')[1].innerText = document.getElementById('precio-total').innerText.replace('\n', '').replace('Bs.S', '')
    TOTAL_BF = document.querySelector('#total_orden li.total-bf p:last-child').innerText
    // document.querySelectorAll('.currency.hidden p')[1].innerHTML.
    // document.querySelectorAll('.currency hidden p')[1].innerText = document.getElementById('precio-total').innerText
}

function eliminateCupon() {
    document.querySelector('.woocommerce-remove-coupon').addEventListener('click', () => {
        jQuery('.cart-discount').fadeOut()
    })
}

jQuery(document).ready(function () {

    // jQuery('#customer_details .back_overlay, #customer_details .close_modal, #customer_details a.moveStepTree').click(function(e) {
    //     e.preventDefault()
    //     jQuery('form.checkout').validate().destroy()
    //     validate_billing()
    //     if (document.getElementById('ship-to-different-address-checkbox').checked) {
    //         validate_shipping()
    //     } 
    //     if (jQuery('form.checkout').valid()) {

    //     }
    // })

    // if (jQuery( window ).height() <= 598) {
    //     document.querySelector('.woocommerce').style.height = "60vh"
    // }

    jQuery.validator.addMethod("date_asia", function (value, element) {
        return this.optional(element) || /^([0-2][0-9]|(3)[0-1])(\/)(((0)[0-9])|((1)[0-2]))(\/)\d{4}$/.test(value);
    }, "Por favor, colocar una fecha válida.");


    jQuery.validator.addMethod("sizeFile", function (value, element) {
        return this.optional(element) || element.files[0].size / 1000000 < 1;
    }, "Tamaño maximo 1mb.");

    document.querySelector('.col-1').append(document.querySelector('.caja-con-facturacion'))

    info_address()

    jQuery('.select2-selection__arrow').html('<svg xmlns="http://www.w3.org/2000/svg" width="12.298" height="4.852" viewBox="0 0 12.298 4.852"><path id="Path_91" data-name="Path 91" d="M2888.061,400l5.65,3.831,6.1-3.831" transform="translate(-2887.781 -399.577)" fill="none" stroke="#707070" stroke-width="1"/></svg>')

    observerW()
    
    // move postal code to the end 
    document.getElementById('billing_city_field').insertAdjacentElement('afterend', document.getElementById('billing_postcode_field'))

    document.querySelectorAll('.woocommerce-notices-wrapper')[0].remove()

    grabPriceOfBf()

    // document.getElementById('payment').classList.remove('none')
    document.querySelector('#step-3 .drop-cont-table.payment-cont').append(document.getElementById('payment'))

    removeAndAddCssClassesToInputsToLookGood()

    showCompanyInput()

    changeSelectControllerOfwhatTypeOfShippingIsForCitysThatAceptsGuick()
    jQuery(document).ready(function ($) {
        $('form.checkout').on('change', '#retiro', function (e) {
            $('body').trigger('update_checkout');
            // selectEventHandler(e)
        });
    });

    jQuery('body').on('updated_checkout', removeHiddenClassInStepThree)
   
    if (document.getElementById('billing_city').value in citys) {
        document.getElementById('retiro_field').classList.remove('none')
    } else {
        document.getElementById('envios_field').classList.remove('none')
    }
})

function removeHiddenClassInStepThree() {
    if (isInStepThree) {
        document.getElementById('payment').classList.remove('none')
    }
}

const TOTAL = document.querySelector('.order-total bdi').innerText
let TOTAL_BF = document.querySelector('#total_orden li.total-bf p:last-child').innerText
const citys = {
    Guanta: '5',
    Lechería: '2',
    Barcelona: '3',
    "Puerto La Cruz": '3'
}

const getCitySelected = citySelected => citys[citySelected]
function selectEventHandler(e) {
    console.log(e.target)
    if (e.target.value === 'retiro_2') {
        jQuery('#type_shipping_field [data-title]')[0].innerText = `Gratis`
        document.querySelector('.order-total bdi').innerText = TOTAL
        document.querySelector('#total_orden li.total-bf p:last-child').innerText = TOTAL_BF
    } else if (e.target.value === 'retiro_1') {

        jQuery('#type_shipping_field [data-title]')[0].innerText = `costo de ${getCitySelected(document.getElementById('billing_city').value)}$ adicionales al total.`

        let valueOfShippingRateOfGuick = addShippingRateOfGuick(getCitySelected(document.getElementById('billing_city').value))
        document.querySelector('.order-total bdi').innerText = '$' + valueOfShippingRateOfGuick.newTotalValueInDolars
        document.querySelector('#total_orden li.total-bf p:last-child').innerText = valueOfShippingRateOfGuick.newTotalValueInBf
    }
}

function changeSelectControllerOfwhatTypeOfShippingIsForCitysThatAceptsGuick() {

    let select = document.getElementById('retiro')
    select.addEventListener('change', selectEventHandler)
}

function addShippingRateOfGuick(valueOfCity) {

    let totalText = document.querySelector('.order-total bdi').innerText
    let total = totalText.replace('$', '')


    let rate = document.querySelector('#total_orden li.currency p:last-child').innerText
    let cleanedRateBs = rate.replaceAll('.', '').replace(' BsS', '')
    let cleanedTotalBF = TOTAL_BF.replaceAll('.', '').replace(' BsS', '').replaceAll(',', '.')
    let valueOfCityInBf = parseFloat(valueOfCity) * parseFloat(cleanedRateBs)

    let newTotalValueInDolars = parseFloat((parseFloat(valueOfCity) + parseFloat(total)).toFixed(2))
    let newTotalValueInBf = new Intl.NumberFormat("de-DE").format(parseFloat((parseFloat(cleanedTotalBF) + parseFloat(valueOfCityInBf)).toFixed(2)))
    return { newTotalValueInDolars, newTotalValueInBf }
}

function removeAndAddCssClassesToInputsToLookGood() {
    // const billEmail = document.getElementById('billing_email_field')
    const shippingState = document.getElementById('shipping_state_field')
    const shippingCity = document.getElementById('shipping_city_field')
    const billingState = document.getElementById('billing_state_field')
    const billingCity = document.getElementById('billing_city_field')
    // billEmail.classList.remove('form-row-wide')
    shippingState.classList.remove('form-row-wide')
    shippingCity.classList.remove('form-row-wide')
    billingState.classList.remove('form-row-wide')
    billingCity.classList.remove('form-row-wide')
    shippingState.classList.add('form-row-first')
    shippingCity.classList.add('form-row-last')
    billingCity.classList.add('form-row-last')
    billingState.classList.add('form-row-first')
}

function validate_billing(isNewUser = false, retiroIsDisable = false) {

    let validationsObj = {
        rules: {
            billing_first_name: {
                required: true
            },
            billing_last_name: {
                required: true
            },
            billing_address_1: {
                required: true
            },
            billing_city: {
                required: true
            },
            billing_state: {
                required: true
            },
            billing_postcode: {
                required: true
            },
            billing_phone: {
                required: true
            },

        },
        messages: {
            billing_first_name: 'Es requerido el nombre.',
            billing_last_name: 'Es requerido el apellido.',
            billing_address_1: 'Es requerido una dirección.',
            billing_city: 'Es requerido la ciudad.',
            billing_state: 'Es requerido el estado.',
            billing_postcode: 'Es requerido el código postal.',
            billing_phone: 'Es requerido un número telefónico.',
        }
    }
    if (isNewUser) {
        validationsObj.rules.account_password = {
            required: true,
            minlength: 8
        }
        // validationsObj.rules.billing_email = {
        //         required: true,
        //         email: true,
        // }
        validationsObj.messages.account_password = {
            required: 'su contraseña es requerida.',
            minlength: '8 caracteres minimo.'
            // required: 'Es requerido un retiro.',
        }

    }

    if (retiroIsDisable) {
        validationsObj.rules.envios = {
            required: true
        }
        // validationsObj.messages.envios = 'Seleccion una empresa de envio.'
    } else {
        validationsObj.rules.retiro = {
            required: true
        }
        // validationsObj.messages.retiro = 'Selecciona una forma de retiro.'
    }
    console.log(validationsObj);
    jQuery('form.checkout').validate(validationsObj)
}


function validate_shipping(isNewUser = false) {

    let validationObj = {
        rules: {
            billing_first_name: {
                required: true
            },
            billing_last_name: {
                required: true
            },
            billing_address_1: {
                required: true
            },
            billing_city: {
                required: true
            },
            billing_state: {
                required: true
            },
            billing_phone: {
                required: true
            },
            shipping_first_name: {
                required: true
            },
            shipping_last_name: {
                required: true
            },
            shipping_country: {
                required: true
            },
            shipping_address_1: {
                required: true
            },
            shipping_city: {
                required: true
            },
            shipping_state: {
                required: true
            }
        },
        messages: {
            billing_first_name: 'Es requerido el nombre.',
            billing_last_name: 'Es requerido el apellido.',
            billing_address_1: 'Es requerido una dirección.',
            billing_city: 'Es requerido la ciudad.',
            billing_state: 'Es requerido el estado.',
            billing_phone: 'Es requerido un número telefónico.',
            shipping_first_name: 'Es requerido el nombre.',
            shipping_last_name: 'Es requerido el apellido.',
            shipping_address_1: 'Es requerido una dirección.',
            shipping_city: 'Es requerido la ciudad.',
            shipping_state: 'Es requerido el estado.',
            shipping_country: 'Es requerido un pais.'
        }
    }

    if (isNewUser === true) {
        validationObj.rules.account_password = {
            required: true,
            minlength: 8
        }
        // validationObj.rules.billing_email = {
        //         required: true,
        //         email: true,
        // }
        validationObj.messages.account_password = {
            required: 'su contraseña es requerida.',
            minlength: '8 caracteres minimo.'
        }
        // validationObj.messages.billing_email = {
        //     billing_email: 'Es requerido el email.',
        // }
    }
    jQuery('form.checkout').validate(validationObj)
}

function info_address() {
    if (!jQuery('#customer_details')[0]) return
    let address = jQuery('#billing_country option:selected').text()
    if (jQuery('#billing_state').val() !== '') {
        address += ', ' + jQuery('#billing_state').val()
    }
    if (jQuery('#billing_city').val() !== '') {
        address += ', ' + jQuery('#billing_city').val()
    }
    if (jQuery('#billing_address_1').val() !== '') {
        address += ', ' + jQuery('#billing_address_1').val()
    }
    if (jQuery('#billing_address_2').val() !== '') {
        address += ', ' + jQuery('#billing_address_2').val()
    }
    if (jQuery('#billing_postcode').val() !== '') {
        address += ', ' + jQuery('#billing_postcode').val()
    }
    address += '.'
    jQuery('address').html(address)
}


function validacionCellphone(id) {
    let cellphone = document.getElementById(id);

    if (cellphone === null) return '';

    if (/(^(\+58\s?)?(\d{3}|\d{4})([\s\-]?\d{3})([\s\-]?\d{4})$)/g.test(cellphone.value)) {
        return cellphone.value.replace(/(\s)|([\(,\),-])|(\+)/g, '')
    } else {
        return 'no es un numero valido'
    }
}


function validationIdn(id) {
    let input = document.getElementById(id);

    if (input === null) return '';
    if (/[a-zA-Z]/gi.test(input.value)) {
        return 'hay una letra'
    }
    if (input.value.length >= 7) {
        // if (input.value.length === 10 || input.value.length === 8 ) {
        // if (/(\d{1,2})|(\.\d{3})/gi.test(input.value)) {
        if (/^\d+$/gi.test(input.value)) {
            return input.value
        }
    } else if (input.value.length === 0) {
        return 'no hay nada'
    } else {
        return 'cantidad no aceptada'
    }
}
