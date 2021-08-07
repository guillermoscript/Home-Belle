jQuery(document).ready(function() {
    jQuery('.woocommerce-login_register').click(function(e) {
        e.preventDefault()
        jQuery(this).parents('.u-columns').children('div.active').removeClass('active').siblings().addClass('active')
        // jQuery('#billing_country').select2()
        validate_register()
    })
    jQuery('#billing_country').val('VE')
    jQuery('#billing_country').select2()
    if (jQuery('#account_first_name')[0]) {
        jQuery('#account_first_name, #account_last_name').keypress(function(e) {
            if (jQuery(this).val().length >= 68) return false
        })
        jQuery('#account_first_name, #account_last_name').change(function(e) {
            if (jQuery(this).val().length >= 68) jQuery(this).val(this.value.substring(0, 68))
        })
        jQuery('#account_display_name').keypress(function(e) {
            if (jQuery(this).val().length >= 32) return false
        })
        jQuery('#account_display_name').change(function(e) {
            if (jQuery(this).val().length >= 32) jQuery(this).val(this.value.substring(0, 32))
        })
    }
})
jQuery(window).load(() => {
    document.querySelector('form button[type=submit]').addEventListener('click',stopIt)
    
    if (document.querySelector('form.login button[type=submit]')) {
        document.querySelector('form.login button[type=submit]').addEventListener('click',() => {
            validate_login()
            document.querySelector('form.login button[type=submit]').removeEventListener('click',stopIt)
            document.querySelector('form.login button[type=submit]').click()
        })
    }

    jQuery('form.login input').keyup(function(e) {
        e.preventDefault()
        jQuery(this).siblings('label.error').html('')
    })
    jQuery('#formid').on('keyup keypress', function(e) {
        let keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            return validate_login();
        }
    })
    if (jQuery('form.edit-address')[0]) {
        validate_billing()
        jQuery('form .button').click(function() {
            if (jQuery('form').valid()) {
                // jQuery('#place_order').removeAttr("disabled")
                jQuery('#place_order').click()
            }
        })
    }

    jQuery('#woocommerce_privacy_policy').change(function() {
        (jQuery(this).is(':checked')) ? jQuery('form.register button').removeAttr('disabled'): jQuery('form.register button').attr('disabled', true)
    })
    jQuery.validator.addMethod("is_exist", function(value, element) {
        return this.optional(element) || (jQuery(element).attr('data-exist') === 'yes') ? false : true
    }, "Ya existe.");
    validate_register()
    jQuery('#reg_email, #reg_username').keyup(function() { validate_is_exists(this) }).change(function() { validate_is_exists(this) })
})

function stopIt(e) {
    e.preventDefault();
    e.stopPropagation();
}

function validate_is_exists(element) {
    let value = jQuery(element).val(),
        name = jQuery(element).attr('name')
    if (jQuery(element).val().length > 5) {
        validate_register_ajax(value, name)
    }
}

function validate_register_ajax(value, name) {
    jQuery.post(register_ajax.url, {
            // nonce: nonce.val(),
            action: register_ajax.action,
            value: value,
            name: name,
        }).success(function(response) {
                jQuery(`input[name=${name}]`).attr('data-exist', response)
                if (response === 'yes') {
                    jQuery(`form.register input[name=${name}]`).siblings('label.error').html(`El ${jQuery(`form.register input[name=${name}]`).attr('data-title')} ya existe.`)
        } else if (response === 'no') {
            jQuery(`input[name=${name}]`).siblings('label.error').html('')
        }
    })
}

function validate_register() {
    jQuery('form.register').validate({
        rules: {
            billing_first_name: {
                required: true
            },
            billing_last_name: {
                required: true
            },
            email: {
                required: true,
                email: true,
                is_exist: true
            },
            username: {
                required: true,
                minlength: 6,
                is_exist: true
            },
            password: {
                required: true,
                minlength: 8
            },
            // password2: {
            //     equalTo: '#reg_password'
            // },
            woocommerce_privacy_policy: {
                required: true
            }
        },
        messages: {
            billing_first_name: 'Nombre requerido',
            billing_last_name: 'Apellido requerido',
            email: {
                required: 'Correo electrónico requerido.',
                email: 'Correo electrónico inválido.',
                is_exist: 'El correo electrónico ya existe.'
            },
            username: {
                required: 'Usuario requerido.',
                minlength: '6 caracteres minimo.',
                is_exist: 'El nombre de usuario ya existe.'
            },
            password: {
                required: 'Contraseña requerida.',
                minlength: '8 caracteres minimo.'
            },
            // password2: {
            //     equalTo: 'Su contraseña no coincide.',
            // },
            woocommerce_privacy_policy: 'Para crearse una cuenta necesita aceptar nuestros términos y condiciones.',
        }
    })
}

function validate_login() {
    jQuery('form.login').validate({
        rules: {
            username: {
                required: true,
                email: true
            },
            password: {
                required: true,
                // minlength: 8
            }
        },
        messages: {
            username: {
                required: 'Correo electrónico requerido.',
                email: 'Correo electrónico inválido.'
            },
            password: {
                required: 'Contraseña requerida.',
                // minlength: '8 caracteres minimo.'
            }
        }
    })
}

function validate_billing() {
    jQuery('form').validate({
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
            }
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
    })
}