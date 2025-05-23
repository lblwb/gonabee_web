const initSearchModal = async () => {
    const modal = document.querySelector('#searchGlbModal');
    const openBtn = document.querySelector('#searchGlb');
    const closeBtn = document.querySelector('#closeModalBtn');

    if (!modal || !openBtn || !closeBtn) {
        console.error('Один из элементов модалки не найден.');
        return;
    }

    // Инициализируем объект состояния
    window.searchGlbModal = {
        open: false,

        openModal() {
            modal.classList.remove('hidden');
            this.open = true;
            console.log('search')
        },

        closeModal() {
            modal.classList.add('hidden');
            this.open = false;
        },

        toggleModal() {
            this.open ? this.closeModal() : this.openModal();
        },

        observer() {
            console.log('Модалка открыта?', this.open);
            // Можно расширить: например, вызывать коллбеки при открытии/закрытии
        }
    };

    // Обработчики событий
    openBtn.addEventListener('click', () => {
        window.searchGlbModal.openModal();
        window.searchGlbModal.observer();
    });

    closeBtn.addEventListener('click', () => {
        window.searchGlbModal.closeModal();
        window.searchGlbModal.observer();
    });

    // Закрытие по клику вне содержимого
    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            window.searchGlbModal.closeModal();
            window.searchGlbModal.observer();
        }
    });
};

const subGalleryProductCard = async () => {
    console.debug("subGallery init...");

    document.querySelectorAll('.previewSliderItemImageSubGallery').forEach((galleryEl, index) => {
        // console.log(galleryEl);
        const paginationContainer = galleryEl.querySelector('.bottomPaginate');

        new Swiper(galleryEl, {
            slidesPerView: 1,
            grabCursor: true,
            spaceBetween: 0,
            loop: true,

            pagination: {
                el: paginationContainer,
                clickable: true,
                renderBullet: function (index, className) {
                    return `<span class="${className} bullet"></span>`;
                }
            },
        });
    });
}
//
const bannerMainHomeSlider = async () => {
    const bannerMainHomeSlider = document.querySelector('.bannerMainHomeSlider');
    var bannerMainHomeSliderEl = new Swiper(bannerMainHomeSlider, {
        // effect: 'cards',
        // Optional parameters
        direction: 'horizontal',
        loop: true,
        drag: false,
        //
        freeMode: false,
        simulateTouch: false,
        //
        resize: true,
        slidesPerView: 1,
        // spaceBetween: 12,
        breakpoints: {
            // when window width is <= 320px
            320: {
                slidesPerView: 1,
                // spaceBetween: 10,
            },
            // when window width is <= 480px
            480: {
                slidesPerView: 1,
                // spaceBetween: 10
            },

            // when window width is <= 640px
            640: {
                slidesPerView: 1,
                // spaceBetween: 30
            },

            1024: {
                slidesPerView: 1,
                // spaceBetween: 12,
            },
        },
    });
}

const newProductsSlider = async () => {
    const newProductsPreviewSliders = document.querySelectorAll('.newProductsPreviewSlider');
    // const featuresSliderActionBtnLft = document.querySelector(".featuresSliderActionBtnLft");
    // const featuresSliderActionBtnRgt = document.querySelector(".featuresSliderActionBtnRgh");
    // const featuresSliderActionCount = document.querySelector(".featuresSliderActionCount");

    newProductsPreviewSliders.forEach((SliderEl, index) => {
        var featuresProductSlider = new Swiper(SliderEl, {
            // effect: 'cards',
            // Optional parameters
            direction: 'horizontal',
            loop: true,
            drag: false,

            freeMode: false,
            simulateTouch: false,

            resize: true,

            spaceBetween: 12,

            navigation: {
                nextEl: ".sliderNavBtnNext",
                prevEl: ".sliderNavBtnPrev",
            },

            breakpoints: {


                // when window width is <= 320px
                320: {
                    slidesPerView: 2.2,
                    spaceBetween: 10,
                },
                // when window width is <= 480px
                480: {
                    slidesPerView: 2.4,
                    spaceBetween: 10
                },

                // when window width is <= 640px
                640: {
                    slidesPerView: 3.5,
                    spaceBetween: 30
                },

                1024: {
                    slidesPerView: 4,
                    spaceBetween: 12,
                },
            },
        });
    });
}

const mobMainProductsSlider = async () => {
    const mobMainProductSliders = document.querySelector('.shopProductDetailCardImagesMainMob .mainMobSlider');
    var mobMainProductSlider = new Swiper(mobMainProductSliders, {
        // effect: 'cards',
        // Optional parameters

        loop: true,
        drag: false,

        freeMode: false,
        simulateTouch: true,

        // resize: true,

        spaceBetween: 12,

        // slidesPerView: 2,

        breakpoints: {
            // when window width is <= 320px
            320: {
                slidesPerView: 1,
                spaceBetween: 10,
                direction: 'vertical',
            },
            // when window width is <= 480px
            480: {
                slidesPerView: 1,
                spaceBetween: 10,
                direction: 'vertical',
            },
        },
        pagination: {
            // el: featuresSliderActionCount,
            type: 'custom',
            renderCustom: function (swiper, current, total) {
                //
                current = current.length > 10 ? current : '0' + current
                total = total.length > 10 ? total : '0' + total
                return '<span class="current-position">' + current + '</span>' + '<span class="split-positions">/</span>' + '<span class="total-positions">' + total + '</span>';
            }
        }
    });
}


const accesrPreviewSlider = async () => {
    const accesrPreviewSlider = document.querySelector('.accesrPreviewSlider');
    // const featuresSliderActionBtnLft = document.querySelector(".featuresSliderActionBtnLft");
    // const featuresSliderActionBtnRgt = document.querySelector(".featuresSliderActionBtnRgh");
    // const featuresSliderActionCount = document.querySelector(".featuresSliderActionCount");
    var featuresProductSlider = new Swiper(accesrPreviewSlider, {
        // effect: 'cards',
        // Optional parameters
        direction: 'horizontal',
        loop: true,
        drag: false,

        freeMode: false,
        simulateTouch: false,

        resize: true,

        spaceBetween: 12,

        navigation: {
            nextEl: ".sliderNavBtnNext",
            prevEl: ".sliderNavBtnPrev",
        },

        breakpoints: {


            // when window width is <= 320px
            320: {
                slidesPerView: 2.2,
                spaceBetween: 10,
            },
            // when window width is <= 480px
            480: {
                slidesPerView: 2.4,
                spaceBetween: 10
            },

            // when window width is <= 640px
            640: {
                slidesPerView: 3.5,
                spaceBetween: 30
            },

            1024: {
                slidesPerView: 4,
                spaceBetween: 12,
            },
        },
    });

}

const ideaProductsSlider = async () => {
    const ideaProductSliderBlockList = document.querySelectorAll('.ideasProductWrapper .ideaProductSliderBlockList');
    ideaProductSliderBlockList.forEach((SliderEl, index) => {
        var ideaProductSlider = new Swiper(SliderEl, {
            // effect: 'cards',
            // Optional parameters

            loop: true,
            drag: false,

            freeMode: false,
            simulateTouch: true,

            // resize: true,

            spaceBetween: 12,

            // slidesPerView: 2,

            navigation: {
                nextEl: ".sliderNavBtnNext",
                prevEl: ".sliderNavBtnPrev",
            },

            breakpoints: {


                // when window width is <= 320px
                320: {
                    slidesPerView: 2,
                    spaceBetween: 10,
                    direction: 'vertical',
                },
                // when window width is <= 480px
                480: {
                    slidesPerView: 2,
                    spaceBetween: 10,
                    direction: 'vertical',
                },

                // when window width is <= 640px
                640: {
                    slidesPerView: 3,
                    spaceBetween: 30,
                    direction: 'vertical',
                },

                1024: {
                    slidesPerView: 2,
                    spaceBetween: 12,
                    direction: 'horizontal',
                },
            },
        });
    })
}


function createCustomPagination(swiper) {
    //
    const swiper_el = swiper.el;
    console.log("createCustomPagination", swiper.el);
    try {
        const paginationContainer = document.querySelector('.previewSliderItemBlock .bottomPaginate');
        paginationContainer.innerHTML = ''; // Очистим перед генерацией
        //
        swiper.slides.forEach((_, index) => {
            const bullet = document.createElement('div');
            bullet.classList.add('bullet');
            bullet.addEventListener('click', () => swiper.slideTo(index));
            paginationContainer.appendChild(bullet);
        });
    } catch (e) {
        console.error("createCustomPagination", e);
    }

}

function setActiveBullet(swiper) {
    console.log("setActiveBullet", swiper.el);
    const swiper_el = swiper.el;
    const bullets = document.querySelectorAll('.previewSliderItemBlock .custom-pagination .bullet');
    bullets.forEach((b, i) => {
        b.classList.toggle('__Active', i === swiper.activeIndex);
    });
}


const colorCircle = async () => {
    console.debug("color Cirlce init...");
    document.querySelectorAll('.color-circle').forEach(circle => {
        circle.addEventListener('click', () => {
            const colorId = circle.dataset.colorId;
            const target = document.querySelector(`.itemImageSubGalleryItem[data-color-id="${colorId}"]`);
            if (target) {
                target.scrollIntoView({behavior: 'smooth', inline: 'center'});
                // можно добавить выделение:
                target.classList.add('highlight');
                setTimeout(() => target.classList.remove('highlight'), 1000);
            }
        });
    });
}

const loadMoreBtn = async () => {
    const loadMoreBtn = document.getElementById('load_more_products');

    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function () {
            const button = this;
            const page = parseInt(button.getAttribute('data-page')) + 1;
            const ajaxurl = button.getAttribute('data-url');

            button.innerText = 'Загрузка...';

            const data = new FormData();
            data.append('action', 'load_more_products');
            data.append('page', page);

            fetch(ajaxurl, {
                method: 'POST',
                body: data
            })
                .then(response => response.text())
                .then(html => {
                    if (html.trim() !== "0") {
                        document.getElementById('more_products').insertAdjacentHTML('beforeend', html);
                        button.setAttribute('data-page', page);
                        button.innerText = 'Загрузить ещё';
                    } else {
                        button.remove(); // больше нечего загружать
                    }
                })
                .catch(error => {
                    console.error('Ошибка при подгрузке:', error);
                    button.innerText = 'Ошибка';
                });
        });
    }
}

//
const swipperInit = async () => {
    console.debug("swipper init...");

    //
    await bannerMainHomeSlider();

    await newProductsSlider();

    await ideaProductsSlider();

    await accesrPreviewSlider();

    await mobMainProductsSlider();
    //
    await subGalleryProductCard();
    //
    await colorCircle();
}


// const searchGlb = async () => {
//     const searchGlbElm = document.querySelector('#searchGlb');
//     searchGlbElm.addEventListener('click', () => {
//         alert("search");
//         window.searchGlbModal = true;
//     });
// }


const tooglesDropFooter = () => {
    const toggles = document.querySelectorAll('.footerMainMob__toggle');
    const contents = document.querySelectorAll('.footerMainMob__content');
    let openIndex = null;

    toggles.forEach((toggle, index) => {
        toggle.addEventListener('click', () => {
            const content = contents[index];
            const icon = toggle.querySelector('.footer__icon');

            if (openIndex === index) {
                // Закрытие текущей
                content.style.maxHeight = null;
                icon.classList.remove('rotated');
                openIndex = null;
            } else {
                // Закрытие всех
                contents.forEach((c, i) => {
                    c.style.maxHeight = null;
                    toggles[i].querySelector('.footer__icon').classList.remove('rotated');
                });

                // Открытие новой
                content.style.maxHeight = content.scrollHeight + "px";
                icon.classList.add('rotated');
                openIndex = index;
            }
        });

        // Сброс высоты при ресайзе окна
        window.addEventListener('resize', () => {
            if (openIndex !== null) {
                contents[openIndex].style.maxHeight = contents[openIndex].scrollHeight + "px";
            }
        });
    });
}
const uiMenuVibe = () => {
    SparkVibe.registerStore("appMenu", {
        mobBurger: {
            show: false,
            showMenu: function () {
                this.show = true;
                console.debug("showMenu called, show =", this.show);
            },
            hideMenu: function () {
                this.show = false;
                console.debug("hideMenu called, show =", this.show);
            },
            toggleMenu: function () {
                this.show = !this.show;
                console.debug("toggleMenu called, show =", this.show);
            }
        },
        isMobile: window.innerWidth <= 768
    });

    window.addEventListener("resize", () => {
        const isMobile = window.innerWidth <= 768;
        SparkVibe.setValueByPath("appMenu.isMobile", isMobile);
    });

};

const addCartBtn = () => {
    $.notify("Модель добавлена в корзину!", "success");
}

const filterBtnProducts = () => {
    $.notify("!", "success");
}

const footerVibe = () => {
    SparkVibe.registerStore('footer', {
        openIndex: null,
        footerItems: [
            {title: 'Section 1', content: 'Content 1', scrollHeight: 0},
            {title: 'Section 2', content: 'Content 2', scrollHeight: 0},
            // ... другие секции
        ],
        toggleFooter(index) {
            if (this.openIndex === index) {
                this.openIndex = null;
            } else {
                this.openIndex = index;
            }
        }
    });

    // Регистрация компонента
    SparkVibe.registerFileComponent('footer-main-mob', './FooterMainMob.html');

    // Обработчик ресайза для обновления scrollHeight
    function updateScrollHeights() {
        const contents = document.querySelectorAll('.footerMainMob__content');
        contents.forEach((content, index) => {
            SparkVibe.registerStore('footer').footerItems[index].scrollHeight = content.scrollHeight;
        });
    }

    // Обновление высоты при загрузке и ресайзе
    window.addEventListener('load', updateScrollHeights);
    window.addEventListener('resize', updateScrollHeights);
}

//
const appVibes = () => {
    SparkVibe.configure({
        LOG_LEVEL: "debug"
    });
    uiMenuVibe();
    SparkVibe.init();
};

const changeColor = () => {

}
const selectSizePrd = () => {

}


document.addEventListener('DOMContentLoaded', function () {
    appVibes();
    //
    console.debug("loaded!");
    //
    swipperInit().then();
    //
    // searchGlb().then();

    // initSearchModal().then();
    //

    tooglesDropFooter();

});