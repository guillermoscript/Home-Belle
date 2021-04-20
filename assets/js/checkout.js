function moveUserStepByStep(idToDisable, idToAble) {
    const containerToBeDisable = document.getElementById(idToDisable)
    const containerToBeAble = document.getElementById(idToAble)

    containerToBeDisable.children[1].classList.add('chet')
    // containerToBeDisable.children[1].classList.remove('tehc')
    containerToBeAble.children[1].classList.remove('chet')
    // containerToBeAble.children[1].classList.add('tehc')

    setTimeout( () => containerToBeDisable.children[1].classList.add('hiddenn'),700)
    containerToBeDisable.classList.add('drop-disable')
    containerToBeAble.classList.remove('drop-disable')
    
    setTimeout( () => containerToBeAble.children[1].classList.remove('hiddenn'),700)
}

const events = {
    continueStepTwo: () =>  moveUserStepByStep('step-1','step-2'),
    backStepOne: () =>  moveUserStepByStep('step-2','step-1'),
    moveStepTree: () =>  moveUserStepByStep('step-2','step-3'),
    backStepTwo: () =>  moveUserStepByStep('step-3','step-2')
}

function checkWhatStepIs(ev) {
   return events[ev.target.id]()
}

function controllerOfSteps() {
    const buttonContinueToStepTwo = document.getElementById('continueStepTwo')
    const buttonBackToStepOne = document.getElementById('backStepOne')
    const buttonContinueToStepTree = document.getElementById('moveStepTree')
    const buttonBackStepTwo = document.getElementById('backStepTwo')
    
    buttonContinueToStepTwo.addEventListener('click', checkWhatStepIs)
    buttonContinueToStepTree.addEventListener('click', e => {
        e.preventDefault()
        jQuery('form.checkout').validate().destroy()
        let isNewUser = false
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
        if (document.querySelector('#account_password')) {
            isNewUser = true
            idOfPasswordAndEmail = `#account_password,`
            // idOfPasswordAndEmail = `#billing_email, #account_password,`
        }

        validate_billing(isNewUser)
        if (document.getElementById('ship-to-different-address-checkbox').checked) {
            jQuery('form.checkout').validate().destroy()
            validate_shipping(isNewUser)
            if (jQuery(idOfPasswordAndEmail + idOfBillings + ',' + idOfShippings).valid()) {
                checkWhatStepIs(e)
            }
        } else {
            if (jQuery(idOfPasswordAndEmail + idOfBillings ).valid()) {
                checkWhatStepIs(e)
            }
        }
    })
    buttonBackToStepOne.addEventListener('click',checkWhatStepIs)
    buttonBackStepTwo.addEventListener('click',checkWhatStepIs)
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
        mutations.forEach( mutation => {
            // console.log(mutation);
            if (mutation.target === document.querySelector('.drop-cont-table.flexx')) {
                if (mutation.addedNodes.length > 0) {
                    console.log('aaaaa');
                    controllerOfSteps()
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
    document.querySelectorAll('.currency.hidden p')[1].innerText = document.getElementById('tasa-hoy').innerText.replace('\n','')
    document.querySelectorAll('.total-bf p')[1].innerText = document.getElementById('precio-total').innerText.replace('\n','')
    // document.querySelectorAll('.currency.hidden p')[1].innerHTML.
    // document.querySelectorAll('.currency hidden p')[1].innerText = document.getElementById('precio-total').innerText
}

function eliminateCupon() {
    document.querySelector('.woocommerce-remove-coupon').addEventListener('click', () => {
        jQuery('.cart-discount').fadeOut()
    })
}

jQuery(document).ready(function() {
    
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

    jQuery.validator.addMethod("date_asia", function(value, element) {
        return this.optional(element) || /^([0-2][0-9]|(3)[0-1])(\/)(((0)[0-9])|((1)[0-2]))(\/)\d{4}$/.test(value);
    }, "Por favor, colocar una fecha válida.");

    jQuery.validator.addMethod("sizeFile", function(value, element) {
        return this.optional(element) || element.files[0].size / 1000000 < 1;
    }, "Tamaño maximo 1mb.");

    info_address()

    jQuery('.select2-selection__arrow').html('<svg xmlns="http://www.w3.org/2000/svg" width="12.298" height="4.852" viewBox="0 0 12.298 4.852"><path id="Path_91" data-name="Path 91" d="M2888.061,400l5.65,3.831,6.1-3.831" transform="translate(-2887.781 -399.577)" fill="none" stroke="#707070" stroke-width="1"/></svg>')

    observerW()

    grabPriceOfBf()

    document.querySelector('#step-3 .drop-cont-table.payment-cont').append(document.getElementById('payment'))

    removeAndAddCssClassesToInputsToLookGood()
})

function removeAndAddCssClassesToInputsToLookGood() {
    // const billEmail = document.getElementById('billing_email_field')
    const shippingState = document.getElementById('shipping_state_field')
    const shippingCity = document.getElementById('shipping_city_field')
    // const billingState = document.getElementById('billing_state_field')
    // const billingCity = document.getElementById('billing_city_field')
    // billEmail.classList.remove('form-row-wide')
    shippingState.classList.remove('form-row-wide')
    shippingCity.classList.remove('form-row-wide')
    // billingState.classList.remove('form-row-wide')
    // billingCity.classList.remove('form-row-wide')
    shippingState.classList.add('form-row-first')
    shippingCity.classList.add('form-row-last')
    // billingCity.classList.add('form-row-last')
    // billingState.classList.add('form-row-first')
}

function validate_billing(isNewUser = false) {

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
    if (isNewUser === true) {
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
        }
        // validationsObj.messages.billing_email = {
        //     billing_email: 'Es requerido el email.',
        // }
    }
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

