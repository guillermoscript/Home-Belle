jQuery(window).ready(function() {

    addIconToCategoryWidget(changeIconsOfCategoryWidget)
    moveFilterToSideBarOfMovile()

});
jQuery('.widget_price_filter #min_price').attr('placeholder', 'Monto Min.')
jQuery('.widget_price_filter #max_price').attr('placeholder', 'Monto Max.')
jQuery('.widget_price_filter .button').text('Ir')
jQuery('.widget_price_filter .price_slider_amount').addClass('button-send')


function addIconToCategoryWidget(callBack) {   
    let parentDivs = document.getElementsByClassName('cat-parent')
    for (let divIndex = 0; divIndex < parentDivs.length; divIndex++) {
        const div = parentDivs[divIndex]
        div.classList.contains('cat-parent') 
        ? div.children[0].insertAdjacentHTML('afterend','<i class="fas fa-minus pointer"></i>') 
        : div.children[0].insertAdjacentHTML('afterend','<i class="fas fa-plus pointer"></i>')
    }
    callBack()
}

function moveFilterToSideBarOfMovile() {
    let content = document.getElementById('woocommerce_price_filter-3')
    let filter = document.getElementById('woocommerce_product_categories-3')

    document.querySelector('.oceanwp-off-canvas-sidebar').appendChild(filter)
    document.querySelector('.oceanwp-off-canvas-sidebar').appendChild(content)
}

function changeIconsOfCategoryWidget() {    
    let icons = document.querySelectorAll('.cat-parent .fas')
    function help(parent,flag) {
        for (let i = 0; i < parent.length; i++) {
            const child = parent[i]
            if  (child.classList.contains('children')) {
                flag ? fadeIn(child,'block') : fadeOut(child)
            }
        }
    }
    icons.forEach(icon => {
        icon.addEventListener('click', function () {
            if (this.classList.contains('fa-plus')) {
                help(this.parentElement.children, true)
                this.classList.add('fa-minus')
                this.classList.remove('fa-plus')
            } else {
                this.classList.remove('fa-minus')
                this.classList.add('fa-plus')
                help(this.parentElement.children, false)
            }
        })
    })
}
// ** FADE OUT FUNCTION **
function fadeOut(el) {
    el.style.opacity = 1;
    (function fade() {
        if ((el.style.opacity -= .1) < 0) {
            el.style.display = "none";
        } else {
            requestAnimationFrame(fade);
        }
    })();
};

// ** FADE IN FUNCTION **
function fadeIn(el, display) {
    el.style.opacity = 0;
    el.style.display = display || "block";
    (function fade() {
        var val = parseFloat(el.style.opacity);
        if (!((val += .1) > 1)) {
            el.style.opacity = val;
            requestAnimationFrame(fade);
        }
    })();
};

jQuery('.widget_price_filter #min_price, .widget_price_filter #max_price').change(function() {
    jQuery(this).siblings('.button').click()
})