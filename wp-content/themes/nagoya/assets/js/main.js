(function ($) {
    "use strict";

    $(document).ready(function () {

        // Play video in HP
        $('.js-btn-play').each(function () {
            $('.js-btn-play').on('click', function (e) {
                var _this = $(this),
                    wrp = _this.closest('.kl-parent-video');

                wrp.children('.kl-cover-img').hide();
                _this.hide();
                wrp.find('iframe')[0].src += "&autoplay=1";
                e.preventDefault();
            });
        });

        // Slick gallery product
        let slickGallery = $('.js-slick-gallery');
        if (slickGallery) {
            $('.js-slick-gallery').slick({
                dots: true,
                slidesToShow: 1,
                slidesToScroll: 1,
                arrows: false,
                infinite: true,
                speed: 500,
                autoplaySpeed: 1500,
                fade: false,
            });
        }

    });

})(jQuery);

// Get the variation taille input element
const variationInput = document.getElementById('taille');

if(variationInput){
    variationInput.addEventListener('input', function () {
        const variationValue = variationInput.value.trim();

        const variations = document.querySelectorAll('.variations_form .variations select');
        variations.forEach(function (variation) {
            if (variation.getAttribute('name') === 'attribute_pa_taille') {
                const option = variation.querySelector('option[value="' + variationValue + '"]');
                if (option) {
                    variation.value = variationValue;

                    if (typeof jQuery !== 'undefined' && typeof jQuery.fn.trigger !== 'undefined') {
                        jQuery(variation).trigger('change');
                    }
                }
            }
        });
    });
}

// Modal in single product
const variationBtn = document.querySelectorAll('.js-btn-modal-variation')
const variationColorContent = document.querySelector('#pa_color.kl-attributes-wrapper')
const titleProduct = document.querySelector('.kl-detail-product .product_title')
const priceProduct = document.querySelector('.kl-detail-product .price')
const woocommerceTabs = document.querySelector('.kl-woocommerce-tabs')
const bolckBtnProduct = document.querySelector('.kl-detail-product .kl-single_add_to_cart_btn')
variationBtn.forEach(itemElement => {
    itemElement.addEventListener('click', () => {
        variationColorContent.classList.add('d-none')
        titleProduct.classList.add('d-none')
        priceProduct.classList.add('d-none')
        woocommerceTabs.classList.add('d-none')
        bolckBtnProduct.classList.add('d-none')
    })
})

const closeBtn = document.querySelectorAll('.handleClose')
closeBtn.forEach(itemElement => {
    itemElement.addEventListener('click', () => {
        variationColorContent.classList.remove('d-none')
        titleProduct.classList.remove('d-none')
        priceProduct.classList.remove('d-none')
        woocommerceTabs.classList.remove('d-none')
        bolckBtnProduct.classList.remove('d-none')
    })
})

// Hide variation if empty
const dataEmptyVariation = document.querySelectorAll('.thwvsf-rad-li input')
dataEmptyVariation.forEach(inputElement => {
    if (inputElement.getAttribute('data-value') === '') {
        inputElement.closest('label').classList.add('d-none')
    }
})

const btnLoadMore = document.querySelector('.js-load-more-post')
const itemPost = document.querySelectorAll('.kl-animate-block')

btnLoadMore.addEventListener('click', () => {
    btnLoadMore.style.display = 'none'

    itemPost.forEach((item) => {
        item.className = item.className !== 'col-md-6 col-lg-4 kl-product-cat-col show' ? 'col-md-6 col-lg-4 kl-product-cat-col show' : 'hide';
        if (item.className === 'col-md-6 col-lg-4 kl-product-cat-col show') {
            setTimeout(function () {
                item.style.display = 'block';
            }, 0);
        }
        if (item.className === 'hide') {
            setTimeout(function () {
                item.style.display = 'none';
            }, 700);
        }
    })
})