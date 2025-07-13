const registerNotifyCustom = () => {
	jQuery.notify.addStyle('cartStyle', {
		html:
			"<div class='notifyWrapper'>" +
			"<div class='notifyImageWrapper' data-notify-html='image'></div>" +
			"<span class='notifyText' data-notify-text='title'></span>" +
			'</div>',
		classes: {
			base: {
				// все ваши CSS‑правила для .notifyjs-custom-base мы назовём классом base
				'min-height': '60px',
				padding: '10px 15px',
				display: 'flex',
				'align-items': 'center',
				'justify-content': 'center',
				gap: '8px',
				'background-color': '#1e1e1e',
				color: '#ffffff',
				'font-family': "'Montserrat', sans-serif",
				'font-weight': 500,
				'font-size': '14px',
				'box-shadow': '0 2px 8px rgba(0,0,0,0.3)',
			},
		},
	});
	jQuery.notify.addStyle('cartStyleTwo', {
		html:
			'<div style="display: flex; align-items: center; border-radius: 100px;">' +
			'<div class="NotifyWrapper" style="display: flex; align-items: center; gap: 8px;">' +
			'<div class="NotifyIcon" style="display: flex; align-items: center;">' +
			'<img data-notify-html="icon" />' +
			'</div>' +
			'<div class="NotifyTitle" style="display: flex; align-items: center;">' +
			'<span data-notify-text class="notifyjs-message"></span>' +
			'</div>' +
			'</div>' +
			'</div>',
		classes: {
			base: {
				// все ваши CSS‑правила для .notifyjs-custom-base мы назовём классом base
				'background-color': '#1e1e1e',
				color: '#ffffff',
				padding: '10px 15px',
				'border-radius': '4px',
				display: 'flex',
				'align-items': 'center',
				'box-shadow': '0 2px 8px rgba(0,0,0,0.3)',
				'font-family': "'Montserrat', sans-serif",
				'font-weight': 500,
				'font-size': '14px',
				'line-height': '125%',
				'letter-spacing': '0%',
			},
		},
	});
};

const prdColorsScrollBarView = (container = document) => {
	// Находим все группы-контейнеры с прокруткой
	const wrappers = container.querySelectorAll('.colorSelectorWrapper');

	wrappers.forEach((wrapper) => {
		console.log('init color slider');
		const scrollContainer = wrapper.querySelector('.itemBlockHeadingSelColor');
		const leftBtn = wrapper.querySelector('.colorScrollBtn.__left');
		const rightBtn = wrapper.querySelector('.colorScrollBtn.__right');

		if (scrollContainer.scrollWidth === scrollContainer.clientWidth) {
			leftBtn && leftBtn.remove();
			rightBtn && rightBtn.remove();
			return;
		}

		if (!leftBtn || !rightBtn || !scrollContainer) return;

		const scrollAmount = 100; // пикселей за шаг

		leftBtn.addEventListener('click', () => {
			scrollContainer.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
		});

		rightBtn.addEventListener('click', () => {
			scrollContainer.scrollBy({ left: scrollAmount, behavior: 'smooth' });
		});
	});
};

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
			console.log('search');
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
		},
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
	console.debug('subGallery init...');

	const galleries = document.querySelectorAll(
		'.previewSliderItemImageSubGallery'
	);

	if (!galleries.length) {
		console.warn('No galleries found');
		return;
	}

	galleries.forEach((galleryEl, index) => {
		const paginationContainer = galleryEl.querySelector('.bottomPaginate');

		if (!paginationContainer) {
			console.warn(`Pagination container not found for gallery ${index}`);
			return;
		}

		// Ограничиваем число слайдов до 3
		const wrapper = galleryEl.querySelector('.swiper-wrapper');
		if (wrapper) {
			Array.from(wrapper.children)
				.slice(3)
				.forEach((slide) => wrapper.removeChild(slide));
		}

		const slides = galleryEl.querySelectorAll('.swiper-slide');
		const slidesCount = slides.length;

		if (slidesCount === 0) {
			console.warn(`No slides found for gallery ${index}`);
			return;
		}

		// Определяем настройки в зависимости от размера экрана
		const isMobile = window.innerWidth <= 768;

		const swiper = new Swiper(galleryEl, {
			slidesPerView: 1,
			grabCursor: isMobile, // Touch только на мобильных
			spaceBetween: 0,
			loop: slidesCount > 1,
			simulateTouch: isMobile, // Touch-события только на мобильных
			allowTouchMove: isMobile, // Разрешить перетаскивание только на мобильных
			resize: true,
			nested: true,
			pagination: {
				el: paginationContainer,
				clickable: true,
				renderBullet: function (index, className) {
					return `<span class="${className} bullet" data-slide-index="${index}"></span>`;
				},
			},
		});

		// Функция для ручного обновления активного буллета
		function updateBullets(idx) {
			const bullets = paginationContainer.querySelectorAll(
				'.swiper-pagination-bullet.bullet'
			);
			bullets.forEach((b) =>
				b.classList.remove('swiper-pagination-bullet-active')
			);
			const active = paginationContainer.querySelector(
				`.swiper-pagination-bullet.bullet[data-slide-index="${idx}"]`
			);
			if (active) active.classList.add('swiper-pagination-bullet-active');
		}

		// Сразу для стартового положения
		updateBullets(swiper.realIndex);

		// При любой смене слайда
		swiper.on('slideChange', () => {
			updateBullets(swiper.realIndex);
		});

		// Hover эффект только для десктопа
		if (!isMobile && paginationContainer) {
			paginationContainer.addEventListener('mouseover', (e) => {
				if (e.target && e.target.classList.contains('bullet')) {
					const slideIndex = parseInt(
						e.target.getAttribute('data-slide-index')
					);
					if (!isNaN(slideIndex)) {
						swiper.slideTo(slideIndex);
					}
				}
			});
		}
	});
};

const previewSliderItemImage = async () => {
	(function () {
		const initPreviewSliders = () => {
			document
				.querySelectorAll('.previewSliderItem .previewSliderItemImageWrapper')
				.forEach((wrapperEl) => {
					if (wrapperEl.dataset.vApp == '') {
						return;
					}
					const productId = wrapperEl.dataset.productId;
					const defColorAttr = wrapperEl.dataset.defColor || '';
					const firstSlide = wrapperEl.querySelector(
						'.itemImageSubGalleryItem'
					);
					const defaultColor =
						defColorAttr !== ''
							? defColorAttr
							: firstSlide?.dataset.colorId || 'none';

					// создаём Vue-приложение
					const app = Vue.createApp({
						setup() {
							const { reactive, onMounted, watch, nextTick } = Vue;
							const state = reactive({ selectedColor: {} });

							// метод, доступный из window.states
							const selectColor = (colorSlug) => {
								// console.debug('selected-color-prd:', productId, colorSlug);
								state.selectedColor[productId] = colorSlug;
							};

							const getColor = (prdId) => state.selectedColor[prdId] ?? null;
							const isActiveColorSlider = (prdId, slug) =>
								getColor(prdId) === slug;

							onMounted(() => {
								if (getColor(productId) === null && defaultColor !== '') {
									setTimeout(() => selectColor(defaultColor), 0);
								}
							});

							watch(
								() => state.selectedColor[productId],
								(newSlug) => {
									nextTick(() => {
										if (!newSlug) return;
										const slide = wrapperEl.querySelector(
											`[data-color-id="${newSlug}"]`
										);
										// if (!slide) return;
										const paginationContainer =
											slide.querySelector('.bottomPaginate');
										if (!paginationContainer) {
											// console.warn('⚠️ .bottomPaginate не найден, Swiper не будет инициализирован');
											// return;
										}
										// console.log(paginationContainer);
										if (slide) {
											new Swiper(slide.closest('.swiper'), {
												loop: false,
												slidesPerView: 1,
												// pagination: {
												//     el: slide.querySelector('.bottomPaginate .bullet')
												// },
												pagination: {
													el: paginationContainer,
													clickable: true,
													// renderBullet: function (index, className) {
													//     return `<span class="${className} bullet"></span>`;
													// },
													renderBullet: function (index, className) {
														if (index >= 3) return ''; // после третьего булета ничего не рендерим
														return `<span class="${className} bullet"></span>`;
													},
												},
											});

											subGalleryProductCard().then();

											// === переключаем класс __Active на .colorBox ===
											const item = wrapperEl.closest('.previewSliderItem');
											if (!item) return;

											// снимаем у всех
											item.querySelectorAll('.colorBox').forEach((box) => {
												box.classList.remove('__Active');
											});

											// находим именно тот .colorBox, внутри которого нужный .color-circle
											const activeBox = item.querySelector(
												`.colorBox .color-circle[data-color-id="${newSlug}"]`
											);
											if (activeBox) {
												activeBox.parentElement.classList.add('__Active');
											}
										}
									});
								}
							);

							return { selectColor, isActiveColorSlider };
						},
					});

					// тут mount возвращает прокси-компонент, у которого есть selectColor
					const vm = app.mount(wrapperEl);

					// сохраняем именно vm, а не app!
					window.states = window.states || {};
					window.states[`slider_${productId}`] = vm;
				});
		};

		if (
			document.readyState === 'complete' ||
			document.readyState === 'interactive'
		) {
			setTimeout(initPreviewSliders, 0);
		} else {
			document.addEventListener('DOMContentLoaded', initPreviewSliders);
		}
	})();
};

//
const bannerMainHomeSlider = async () => {
	const bannerMainHomeSlider = document.querySelector('.bannerMainHomeSlider');
	if (!bannerMainHomeSlider) return;

	const paginationContainer = document.querySelector(
		'.bannerMainHomeSlider .bottomPaginateCorn'
	);

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

		pagination: {
			el: paginationContainer,
			clickable: true,
			renderBullet: function (index, className) {
				return `<span class="${className} bullet"></span>`;
			},
		},
	});
};

const bannerProductSlider = async () => {
	// Исключаем слайдеры, которые управляются Vue
	const bannerMainHomeSlider = document.querySelector(
		'.mainMobSlider:not(#productMobileSlider)'
	);
	if (!bannerMainHomeSlider) return;

	const paginationContainer =
		bannerMainHomeSlider.querySelector('.bottomPaginate');

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

		pagination: {
			el: paginationContainer,
			clickable: true,
			renderBullet: function (index, className) {
				if (index >= 3) return ''; // после третьего булета ничего не рендерим
				return `<span class="${className} bullet"></span>`;
			},
		},
	});
};

const newProductsSlider = async () => {
	const newProductsPreviewSliders = document.querySelectorAll(
		'.newProductsPreviewSlider'
	);

	newProductsPreviewSliders.forEach((SliderEl) => {
		if (!SliderEl) return;

		// 1) Для каждой вложенной галереи внутри этого слайда товаров
		SliderEl.querySelectorAll('.previewSliderItemImageSubGallery').forEach(
			(subGalleryEl) => {
				const wrap = subGalleryEl.querySelector('.itemImageSubGalleryWrap');
				if (!wrap) return;

				// Удаляем все слайды, начиная с четвёртого
				Array.from(wrap.children)
					.slice(3)
					.forEach((slide) => wrap.removeChild(slide));

				// Если галерея уже инициализирована Swiper’ом — уничтожаем старую инстанцию
				if (subGalleryEl.swiper) {
					subGalleryEl.swiper.destroy(true, true);
				}

				// Переинициализируем Swiper для галереи картинок (не более 3 видимых, перелистывание по одному)
				new Swiper(subGalleryEl, {
					slidesPerView: 1,
					spaceBetween: 8,
					simulateTouch: false,
					pagination: {
						el: subGalleryEl.querySelector('.bottomPaginate'),
						clickable: true,
					},
					breakpoints: {
						320: { slidesPerView: 1, spaceBetween: 4 },
						480: { slidesPerView: 2, spaceBetween: 6 },
						640: { slidesPerView: 1, spaceBetween: 8 },
					},
				});
			}
		);

		// 2) Основной слайдер товаров
		const parent = SliderEl.parentElement;
		if (!parent) return;

		const prevButton = parent.querySelector('.sliderNavBtnPrev');
		const nextButton = parent.querySelector('.sliderNavBtnNext');

		new Swiper(SliderEl, {
			direction: 'horizontal',
			loop: true,
			drag: false,
			freeMode: false,
			simulateTouch: false,
			resize: true,
			spaceBetween: 12,
			navigation: {
				nextEl: nextButton,
				prevEl: prevButton,
			},
			breakpoints: {
				320: {
					slidesPerView: 2.2,
					spaceBetween: 10,
				},
				480: {
					slidesPerView: 2.4,
					spaceBetween: 10,
				},
				640: {
					slidesPerView: 3.5,
					spaceBetween: 30,
				},
				1024: {
					slidesPerView: 4,
					spaceBetween: 12,
				},
			},
		});
	});
};

const mobMainProductsSlider = async () => {
	try {
		const mobMainProductSliders = document.querySelector(
			'.bannerMainHome .bannerMainHomeSlider'
		);

		if (!mobMainProductSliders) return;

		const paginationContainer =
			mobMainProductSliders.querySelector('.bottomPaginate');

		var mobMainProductSlider = new Swiper(mobMainProductSliders, {
			// effect: 'cards',
			// Optional parameters

			loop: true,
			drag: false,

			freeMode: false,
			simulateTouch: true,

			// resize: true,

			spaceBetween: 0,

			pagination: {
				el: paginationContainer,
				clickable: true,
				// renderBullet: function (index, className) {
				//     return `<span class="${className} bullet"></span>`;
				// },
				renderBullet: function (index, className) {
					if (index >= 3) return ''; // после третьего булета ничего не рендерим
					return `<span class="${className} bullet"></span>`;
				},
			},

			// slidesPerView: 2,

			breakpoints: {
				// when window width is <= 320px
				320: {
					slidesPerView: 1,
					spaceBetween: 0,
					direction: 'horizontal',
				},
				// when window width is <= 480px
				480: {
					slidesPerView: 1,
					spaceBetween: 0,
					direction: 'horizontal',
				},
			},
			pagination: {
				// el: featuresSliderActionCount,
				type: 'custom',
				renderCustom: function (swiper, current, total) {
					//
					current = current.length > 10 ? current : '0' + current;
					total = total.length > 10 ? total : '0' + total;
					return (
						'<span class="current-position">' +
						current +
						'</span>' +
						'<span class="split-positions">/</span>' +
						'<span class="total-positions">' +
						total +
						'</span>'
					);
				},
			},
		});
	} catch (e) {
		console.debug('Home slider not found!');
		console.error(e);
	}
};

const accesrPreviewSlider = async () => {
	const accesrPreviewSlider = document.querySelector('.accesrPreviewSlider');
	if (!accesrPreviewSlider)
		return console.warn('accesrPreviewSlider in not found');

	const parent = accesrPreviewSlider.parentElement;
	const prevButton = parent.querySelector('.sliderNavBtnPrev');
	const nextButton = parent.querySelector('.sliderNavBtnNext');
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
			nextEl: nextButton,
			prevEl: prevButton,
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
				spaceBetween: 10,
			},

			// when window width is <= 640px
			640: {
				slidesPerView: 3.5,
				spaceBetween: 30,
			},

			1024: {
				slidesPerView: 4,
				spaceBetween: 12,
			},
		},
	});
};

const ideaProductsSlider = async () => {
	const ideaProductSliderBlockList = document.querySelectorAll(
		'.ideasProductWrapper .ideaProductSliderBlockList'
	);

	ideaProductSliderBlockList.forEach((SliderEl, index) => {
		// console.log(paginationContainer);

		var ideaProductSlider = new Swiper(SliderEl, {
			// effect: 'cards',
			// Optional parameters

			loop: true,
			drag: false,

			freeMode: false,
			simulateTouch: true,

			// resize: true,
			slidesPerView: 2,
			spaceBetween: 10,
			direction: 'vertical',

			spaceBetween: 12,

			// slidesPerView: 2,

			// pagination: {
			// 	el: document.querySelector(
			// 		'.ideaProduct .ideaProductBody .ideaBottomPaginate'
			// 	),
			// 	clickable: true,
			// 	renderBullet: function (index, className) {
			// 		if (index >= 3) return ''; // после третьего булета ничего не рендерим
			// 		return `<span class="${className} bullet"></span>`;
			// 	},
			// },

			navigation: {
				nextEl: '.ideaProductSliderNext',
				prevEl: '.ideaProductSliderPrev',
			},

			breakpoints: {
				1024: {
					slidesPerView: 2,
					spaceBetween: 12,
					direction: 'horizontal',
				},
			},
		});
	});
};

// Кастомный слайдер без Swiper для карточки товара
const shopProductDetailCardImages = (node = document) => {
	const el = node.querySelector('#shopProductDetailCardImages');
	if (!el) return console.warn('#shopProductDetailCardImages не найден!');

	const stateId = el.dataset.prdGroupId;
	if (!stateId) throw new Error(`У узла нет атрибута айди товара`, el);

	window.states.shopPrdDetailCardImages =
		window.states.shopPrdDetailCardImages || {};

	window.states.shopPrdDetailCardImages[stateId] = Vue.createApp({
		setup() {
			const debug = false;
			const productId = el.getAttribute('data-product-id');
			// внутри setup():
			const { ref, computed, onMounted, nextTick, watch } = Vue;

			// Получаем данные изображений по цветам
			let imagesByColor = window.prdImagesByColor || {};
			let defaultColor = window.defaultColorSlug || 'default';

			const appShopDetailCardSlider = Vue.reactive({
				allImages: imagesByColor,
				currentColor: defaultColor,
				currentSlides: imagesByColor[defaultColor] || [],
				currentIndex: 0,
				currentImage: Vue.computed(() => {
					const slides = appShopDetailCardSlider.currentSlides;
					const index = appShopDetailCardSlider.currentIndex;
					return slides[index] || '';
				}),
			});

			const test = Vue.ref('default');

			if (stateId === 'modal-prd') {
				Vue.watch(
					() => window.states.prdModal?.data,
					(newData) => {
						console.log(
							'--------------- prd modal ----------------------------------'
						);
						console.log(newData);

						appShopDetailCardSlider.allImages =
							window.states.prdModal.data.prdSlides;

						appShopDetailCardSlider.currentColor =
							window.states.prdModal.data.prdDefaultColor;
						test.value = window.states.prdModal.data.prdDefaultColor;

						appShopDetailCardSlider.currentSlides =
							window.states.prdModal.data.prdSlides?.[
								window.states.prdModal.data.prdDefaultColor
							] || [];

						Vue.nextTick(() => {
							// Триггерим реактивность
							const temp = { ...appShopDetailCardSlider };
							Object.keys(temp).forEach((key) => {
								appShopDetailCardSlider[key] = temp[key];
							});
						});

						console.log(appShopDetailCardSlider);
					},
					{ deep: true }
				);
			}

			const appFavoriteBtn = Vue.ref({
				status: {
					active: false,
				},
			});

			// Мобильный слайдер
			const mobileSlider = Vue.ref({
				currentIndex: 0,
				swiper: null,
			});

			const FAVORITES_KEY = 'favorite_products';

			function getFavorites() {
				try {
					return JSON.parse(localStorage.getItem(FAVORITES_KEY)) || [];
				} catch (e) {
					return [];
				}
			}

			// Функция для прокрутки миниатюр
			const scrollThumbs = (direction) => {
				const wrapper = document.querySelector('.cardImagesThumbWrapper');
				if (!wrapper) return;

				const itemHeight = 80; // Высота одной миниатюры
				const scrollAmount = direction === 'up' ? -itemHeight : itemHeight;
				wrapper.scrollBy({ top: scrollAmount, behavior: 'smooth' });
			};

			// Функция для автоматической прокрутки к активной миниатюре
			const scrollToActiveThumbnail = (index) => {
				const wrapper = document.querySelector('.cardImagesThumbWrapper');
				if (!wrapper) return;

				const itemHeight = 140; // Высота одной миниатюры

				// Всегда прокручиваем так, чтобы активный элемент был первым
				const scrollPosition = index * itemHeight;

				wrapper.scrollTo({
					top: scrollPosition,
					behavior: 'smooth',
				});
			};

			watch(
				() => appShopDetailCardSlider.currentIndex,
				(newIndex) => {
					// Ждём, пока DOM обновит класс __Active
					nextTick(() => {
						scrollToActiveThumbnail(newIndex);
					});
				}
			);

			// Инициализация мобильного слайдера
			// В функции shopProductDetailCardImages, исправить initMobileSlider
			// ЗАМЕНИТЬ ЭТУ ФУНКЦИЮ:
			const initMobileSlider = () => {
				Vue.nextTick(() => {
					// Уничтожаем старый слайдер если есть
					if (mobileSlider.value.swiper) {
						mobileSlider.value.swiper.destroy(true, true);
					}

					const mobileSliderEl = document.querySelector('#productMobileSlider');
					if (!mobileSliderEl) return;

					const slides = mobileSliderEl.querySelectorAll('.swiper-slide');
					if (slides.length === 0) return;

					// Создаем новый Swiper для мобильного слайдера
					mobileSlider.value.swiper = new Swiper(mobileSliderEl, {
						direction: 'horizontal',
						loop: slides.length > 1,
						slidesPerView: 'auto',
						spaceBetween: 0,
						pagination: {
							el: '.bottomPaginate',
							clickable: true,
							renderBullet: function (index, className) {
								return `<span class="${className}"></span>`;
							},
						},
						on: {
							slideChange: function () {
								// ИСПРАВЛЕНИЕ: Используем realIndex для корректной работы с loop
								const realIndex = this.realIndex;
								mobileSlider.value.currentIndex = realIndex;
								appShopDetailCardSlider.currentIndex = realIndex;

								// Прокручиваем миниатюры к активному элементу
								scrollToActiveThumbnail(realIndex);
							},
						},
					});
				});
			};

			// Обновление мобильного слайдера
			const updateMobileSlider = () => {
				if (mobileSlider.value.swiper) {
					mobileSlider.value.swiper.update();
					mobileSlider.value.swiper.slideTo(0, 0); // Переходим к первому слайду
				}
			};

			// Функция для обновления слайдов по цвету
			// ЗАМЕНИТЬ ЭТУ ФУНКЦИЮ:
			const updateSlidesByColor = (colorSlug) => {
				console.log('Обновление слайдов для цвета:', colorSlug);

				const colorImages = appShopDetailCardSlider.allImages[colorSlug];
				if (colorImages && colorImages.length > 0) {
					appShopDetailCardSlider.currentColor = colorSlug;
					appShopDetailCardSlider.currentSlides = colorImages;
					appShopDetailCardSlider.currentIndex = 0; // Сбрасываем на первое изображение

					// ИСПРАВЛЕНИЕ: Пересоздаем мобильный слайдер с новыми изображениями
					Vue.nextTick(() => {
						initMobileSlider();
					});

					// Прокручиваем миниатюры к началу
					const wrapper = document.querySelector('.cardImagesThumbWrapper');
					if (wrapper) {
						wrapper.scrollTo({ top: 0, behavior: 'smooth' });
					}
				} else {
					console.warn(`Изображения для цвета ${colorSlug} не найдены`);
				}
			};

			// Навигация по слайдам
			const nextSlide = () => {
				const maxIndex = appShopDetailCardSlider.currentSlides.length - 1;
				if (appShopDetailCardSlider.currentIndex < maxIndex) {
					appShopDetailCardSlider.currentIndex++;
				} else {
					appShopDetailCardSlider.currentIndex = 0; // Зацикливание
				}

				// Автопрокрутка миниатюр
				scrollToActiveThumbnail(appShopDetailCardSlider.currentIndex);

				// Синхронизируем мобильный слайдер
				if (mobileSlider.value.swiper) {
					mobileSlider.value.swiper.slideTo(
						appShopDetailCardSlider.currentIndex
					);
				}
			};

			const prevSlide = () => {
				const maxIndex = appShopDetailCardSlider.currentSlides.length - 1;
				if (appShopDetailCardSlider.currentIndex > 0) {
					appShopDetailCardSlider.currentIndex--;
				} else {
					appShopDetailCardSlider.currentIndex = maxIndex; // Зацикливание
				}

				// Автопрокрутка миниатюр
				scrollToActiveThumbnail(appShopDetailCardSlider.currentIndex);

				// Синхронизируем мобильный слайдер
				if (mobileSlider.value.swiper) {
					mobileSlider.value.swiper.slideTo(
						appShopDetailCardSlider.currentIndex
					);
				}
			};

			const selectSlideByIndex = (index) => {
				if (
					index >= 0 &&
					index < appShopDetailCardSlider.currentSlides.length
				) {
					appShopDetailCardSlider.currentIndex = index;

					// Автопрокрутка миниатюр
					scrollToActiveThumbnail(index);

					// Синхронизируем мобильный слайдер
					if (mobileSlider.value.swiper) {
						mobileSlider.value.swiper.slideTo(index);
					}
				}
			};

			// Устаревшие функции для совместимости
			const selectSlideImg = (slideItem) => {
				const index = appShopDetailCardSlider.currentSlides.indexOf(slideItem);
				if (index !== -1) {
					selectSlideByIndex(index);
				}
			};

			const selectSlideImgByColorQuery = (queryColor) => {
				updateSlidesByColor(queryColor);
			};

			const getSelectedWhtLst = (productId) =>
				Vue.computed(() => !getFavorites().includes(productId)).value;

			const addToWhtListMob = (itemCard) => {
				console.log('test prd', !getFavorites().includes(productId));
				appFavoriteBtn.value.status.active =
					!getFavorites().includes(productId);
				console.log(appFavoriteBtn.value.status.active);
				if (window.states && window.states.headMainNav) {
					window.states.headMainNav.addToWhtListMob({
						...itemCard,
						...{ productId: productId },
					});
				}
			};

			Vue.onMounted(() => {
				appFavoriteBtn.value.status.active = getFavorites().includes(productId);

				// Инициализируем с дефолтным цветом
				if (defaultColor && imagesByColor[defaultColor]) {
					updateSlidesByColor(defaultColor);
				}

				// Инициализируем мобильный слайдер
				setTimeout(() => {
					initMobileSlider();
					scrollToActiveThumbnail(appShopDetailCardSlider.currentIndex);
				}, 100);
			});

			// Экспортируем функцию для внешнего использования
			const instance = {
				updateSlidesByColor,
				selectSlideImg,
				selectSlideImgByColorQuery,
				nextSlide,
				prevSlide,
				selectSlideByIndex,
				addToWhtListMob,
				getSelectedWhtLst,
				appShopDetailCardSlider,
				appFavoriteBtn,
				mobileSlider,
				initMobileSlider,
				updateMobileSlider,
				scrollThumbs,
				scrollToActiveThumbnail,
				test,
			};

			// Сохраняем ссылку на экземпляр для внешнего доступа

			return instance;
		},
	}).mount(el);
};
// Удаляем старую функцию thumbnailProductSlider, так как теперь используем кастомный слайдер

const photoReviewsSlider = async () => {
	console.debug('photoReviews slider init...');

	// Ищем слайдер фотоотзывов по классу
	const photoReviewsSlider = document.querySelector(
		'.newProductsPreview .newProductsPreviewSlider'
	);
	if (!photoReviewsSlider) {
		console.warn('Photo reviews slider not found');
		return;
	}

	// Проверяем, есть ли слайды
	const slides = photoReviewsSlider.querySelectorAll('.swiper-slide');
	if (slides.length === 0) {
		console.warn('No slides found in photo reviews slider');
		return;
	}

	// Ищем кнопки навигации в родительском контейнере
	const parent = photoReviewsSlider.closest('.newProductsPreview');
	const prevButton = parent ? parent.querySelector('.sliderNavBtnPrev') : null;
	const nextButton = parent ? parent.querySelector('.sliderNavBtnNext') : null;

	if (!prevButton || !nextButton) {
		console.warn('Navigation buttons not found for photo reviews slider');
		return;
	}

	// Инициализируем Swiper
	const swiper = new Swiper(photoReviewsSlider, {
		direction: 'horizontal',
		loop: slides.length > 1,
		drag: false,
		freeMode: false,
		simulateTouch: false,
		slidesPerView: 'auto',
		spaceBetween: 8,
		resize: true,
		spaceBetween: 12,
		navigation: {
			nextEl: nextButton,
			prevEl: prevButton,
		},
		breakpoints: {
			320: {
				slidesPerView: 2.2,
				spaceBetween: 10,
			},
			480: {
				slidesPerView: 2.4,
				spaceBetween: 10,
			},
			640: {
				slidesPerView: 3.5,
				spaceBetween: 30,
			},
			1024: {
				slidesPerView: 4,
				spaceBetween: 12,
			},
		},
	});

	console.debug('Photo reviews slider initialized successfully');
};

function createCustomPagination(swiper) {
	//
	const swiper_el = swiper.el;
	console.log('createCustomPagination', swiper.el);
	try {
		const paginationContainer = document.querySelector(
			'.previewSliderItemBlock .bottomPaginate'
		);
		paginationContainer.innerHTML = ''; // Очистим перед генерацией
		//
		swiper.slides.forEach((_, index) => {
			const bullet = document.createElement('div');
			bullet.classList.add('bullet');
			bullet.addEventListener('click', () => swiper.slideTo(index));
			paginationContainer.appendChild(bullet);
		});
	} catch (e) {
		console.error('createCustomPagination', e);
	}
}

function setActiveBullet(swiper) {
	console.log('setActiveBullet', swiper.el);
	const swiper_el = swiper.el;
	const bullets = document.querySelectorAll(
		'.previewSliderItemBlock .custom-pagination .bullet'
	);
	bullets.forEach((b, i) => {
		b.classList.toggle('__Active', i === swiper.activeIndex);
	});
}

const colorCircle = async () => {
	console.debug('color Cirlce init...');
	document.querySelectorAll('.color-circle').forEach((circle) => {
		circle.addEventListener('click', () => {
			const colorId = circle.dataset.colorId;
			const target = document.querySelector(
				`.itemImageSubGalleryItem[data-color-id="${colorId}"]`
			);
			if (target) {
				target.scrollIntoView({ behavior: 'smooth', inline: 'center' });
				// можно добавить выделение:
				target.classList.add('highlight');
				setTimeout(() => target.classList.remove('highlight'), 1000);
			}
		});
	});
};

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
				body: data,
			})
				.then((response) => response.text())
				.then((html) => {
					if (html.trim() !== '0') {
						document
							.getElementById('more_products')
							.insertAdjacentHTML('beforeend', html);
						button.setAttribute('data-page', page);
						button.innerText = 'Загрузить ещё';
					} else {
						button.remove(); // больше нечего загружать
					}
				})
				.catch((error) => {
					console.error('Ошибка при подгрузке:', error);
					button.innerText = 'Ошибка';
				});
		});
	}
};
const storeReviewSlider = async () => {
	const container = document.querySelector('.storeReviewsBodySlider');
	if (!container) return;

	const waitFor = (selector, timeout = 5000, context = document) =>
		new Promise((resolve, reject) => {
			const start = Date.now();
			(function check() {
				const elems = context.querySelectorAll(selector);
				if (elems.length) {
					return resolve(elems);
				}
				if (Date.now() - start > timeout) {
					return reject(
						new Error(
							`Элементы ${selector} в контексте ${context.tagName} не найдены за ${timeout}мс`
						)
					);
				}
				setTimeout(check, 100);
			})();
		});

	try {
		await waitFor('.storeReviewsBodySlider .swiper-slide');
	} catch (e) {
		console.warn('Thumbnail slides not found:', e);
		return null;
	}

	const slider = new Swiper(container, {
		spaceBetween: 10, // gap между слайдами
		mousewheel: {
			forceToAxis: true,
			sensitivity: 1,
		},
		// Отключаем навигацию и буллеты
		pagination: false,
		navigation: false,
		scrollbar: false,
		// адаптивность (если нужно)
		breakpoints: {
			0: {
				slidesPerView: 1,
			},
			768: {
				slidesPerView: 3,
			},
		},
	});
};
//
const swipperInit = async () => {
	console.debug('swipper init...');

	//
	await storeReviewSlider();

	await bannerMainHomeSlider();

	await bannerProductSlider();

	await newProductsSlider();

	await photoReviewsSlider();

	await ideaProductsSlider();

	await accesrPreviewSlider();

	await mobMainProductsSlider();
	//
	await subGalleryProductCard();

	await previewSliderItemImage();
	//
	await colorCircle();

	await prdColorsScrollBarView();

	// Убираем thumbnailProductSlider, так как теперь используем кастомный слайдер
};

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
				content.style.maxHeight = content.scrollHeight + 'px';
				icon.classList.add('rotated');
				openIndex = index;
			}
		});

		// Сброс высоты при ресайзе окна
		window.addEventListener('resize', () => {
			if (openIndex !== null) {
				contents[openIndex].style.maxHeight =
					contents[openIndex].scrollHeight + 'px';
			}
		});
	});
};

const uiShopAppVibe = () => {
	// Инициализируем глобальное хранилище состояний
	window.states = window.states || {};
	window.states.productSize = Vue.reactive({
		size: 'L',
	});
	// Компонент для карточки товара (десктоп)
	const singlePrdCard = () => {
		const nodeList = document.querySelectorAll('#singlePrdCard');
		if (!nodeList.length) return console.warn('#singlePrdCard не найден!');

		nodeList.forEach((el) => {
			const stateId = el.dataset.prdGroupId;
			if (!stateId) throw new Error(`У узла нет атрибута айди товара`, el);

			window.states.singlePrdCard = window.states.singlePrdCard || {};
			window.states.singlePrdCard[stateId] = Vue.createApp({
				setup() {
					const appShop = Vue.ref({
						cart: {
							select: {
								color: '',
								colorId: '',
								size: Vue.computed({
									get: () => window.states.productSize.size,
									set: (value) => (window.states.productSize.size = value),
								}),
							},
							mob: {
								size: {
									show: false,
								},
							},
							btnAddActive: false,
						},
						idea: {
							modal_mb: {
								data: null,
								show: false,
							},
							modal: {
								data: null,
								show: false,
							},
						},
					});

					const appFavoriteBtn = Vue.ref({
						status: {
							active: false,
						},
					});

					const getProductsCartExist = (ProductId) =>
						Vue.computed(() => appShop.value.products.includes(ProductId));

					const selectCartSize = (size) => {
						console.log('Выбран размер:', size);
						appShop.value.cart.select.size = size;
					};

					const selectCartColor = (color, colorId) => {
						console.log('Выбран цвет:', color);
						appShop.value.cart.select.color = color;
						appShop.value.cart.select.colorId = colorId;
						window.states.singlePrdMob[stateId].appMobShop.cart.select.color =
							color;
						window.states.singlePrdMob[stateId].appMobShop.cart.select.colorId =
							colorId;

						// Проверяем, является ли цвет "none"
						const isNoneColor = color && color.toLowerCase() === 'none';

						// Уведомляем компонент слайдера о смене цвета
						if (
							window.states &&
							window.states.shopPrdDetailCardImages &&
							window.states.shopPrdDetailCardImages[stateId]
						) {
							window.states.shopPrdDetailCardImages[
								stateId
							].updateSlidesByColor(color);
						}
					};

					// Computed свойства для кнопки
					const isAddToCartDisabled = Vue.computed(() => {
						const selectedColor = appShop.value?.cart?.select?.color;
						return selectedColor && selectedColor.toLowerCase() === 'none';
					});

					const addToCartButtonText = Vue.computed(() => {
						return isAddToCartDisabled.value
							? 'Выберите цвет'
							: 'Добавить в корзину';
					});

					// Получаем параметры из data-атрибутов
					const productId = el.getAttribute('data-product-id');
					const nonce = el.getAttribute('data-nonce');
					const ajaxUrl = el.getAttribute('data-ajax-url');

					const getSelectedWhtLst = (productId) =>
						Vue.computed(
							() =>
								!window.states.headMainNav.getFavorites().includes(productId)
						).value;

					function showAddToCartNotification(cardItem) {
						let imageUrl = cardItem.imageUrl ? cardItem.imageUrl : '';
						console.log(`Товар ${productId} добавлен в корзину!`);

						// Отправляем AJAX-запрос через axios
						axios
							.post(
								ajaxUrl,
								{
									nonce: nonce,
								},
								{
									params: {
										action: 'add_to_cart_mb',
										product_id: productId,
										productQuanity: 1,
										productSize: appShop.value.cart.select.size || false,
										productColor: appShop.value.cart.select.colorId || false,
									},
								}
							)
							.then(function (response) {
								if (response.data.success) {
									const action = response.data.data.action;
									if (action === 'added') {
										jQuery.notify(
											jQuery('.notifyAnchor'),
											{
												title: 'Модель добавлена в корзину!',
												image: `<img src="${imageUrl}" alt="Карточка товара" />`,
											},
											{
												style: 'cartStyle',
												className: 'base',
												showAnimation: 'slideDown',
												showDuration: 300,
												hideAnimation: 'slideUp',
												hideDuration: 200,
												autoHide: true,
												autoHideDelay: 2000,
												gap: 0,
												position: 'bottom left',
												arrowShow: false,
											}
										);
									} else {
										jQuery.notify(
											jQuery('.notifyAnchor'),
											{
												title: 'Модель удалена из корзины!',
												image: `<img src="${imageUrl}" alt="Карточка товара" />`,
											},
											{
												style: 'cartStyle',
												className: 'base',
												showAnimation: 'slideDown',
												showDuration: 300,
												hideAnimation: 'slideUp',
												hideDuration: 200,
												autoHide: true,
												autoHideDelay: 2000,
												gap: 0,
												position: 'bottom left',
												arrowShow: false,
											}
										);
									}
								} else {
									console.log('Произошла ошибка');
									jQuery.notify(
										jQuery('.notifyAnchor'),
										{
											title: response.data.data.message,
											image: `<img src="${imageUrl}" alt="Карточка товара" />`,
										},
										{
											style: 'cartStyle',
											className: 'base',
											showAnimation: 'slideDown',
											showDuration: 300,
											hideAnimation: 'slideUp',
											hideDuration: 200,
											autoHide: true,
											autoHideDelay: 2000,
											gap: 0,
											position: 'bottom left',
											arrowShow: false,
										}
									);
								}

								window.states.headMainNav.fetchCart();
							})
							.catch(function (error) {
								console.error(error);
								console.log('Ошибка запроса');
							});
					}

					function showAddFavoriteNotification(cardItem) {
						let imageUrl = cardItem.imageUrl ? cardItem.imageUrl : '';

						console.log(cardItem);

						// Отправляем AJAX-запрос через axios
						axios
							.post(
								ajaxUrl,
								{
									nonce: nonce,
								},
								{
									params: {
										action: 'toggle_favorite',
										product_id: productId,
									},
								}
							)
							.then(function (response) {
								if (response.data.success) {
									const action = response.data.data.action;
									if (action === 'added') {
										jQuery.notify(
											jQuery('.notifyAnchor'),
											{
												title: 'Модель добавлена в избранное!',
												image: `<img src="${imageUrl}" alt="Карточка товара" />`,
											},
											{
												style: 'cartStyle',
												className: 'base',
												showAnimation: 'slideDown',
												showDuration: 300,
												hideAnimation: 'slideUp',
												hideDuration: 200,
												autoHide: true,
												autoHideDelay: 2000,
												gap: 0,
												position: 'bottom left',
												arrowShow: false,
											}
										);
									} else {
										jQuery.notify(
											jQuery('.notifyAnchor'),
											{
												title: 'Модель удалена из избранного!',
												image: `<img src="${imageUrl}" alt="Карточка товара" />`,
											},
											{
												style: 'cartStyle',
												className: 'base',
												showAnimation: 'slideDown',
												showDuration: 300,
												hideAnimation: 'slideUp',
												hideDuration: 200,
												autoHide: true,
												autoHideDelay: 2000,
												gap: 0,
												position: 'bottom left',
												arrowShow: false,
											}
										);
									}
								} else {
									console.log('Произошла ошибка');
									jQuery.notify(
										jQuery('.notifyAnchor'),
										{
											title: response.data.data.message,
											image: `<img src="${imageUrl}" alt="Карточка товара" />`,
										},
										{
											style: 'cartStyle',
											className: 'base',
											showAnimation: 'slideDown',
											showDuration: 300,
											hideAnimation: 'slideUp',
											hideDuration: 200,
											autoHide: true,
											autoHideDelay: 2000,
											gap: 0,
											position: 'bottom left',
											arrowShow: false,
										}
									);
								}

								window.states.headMainNav.fetchFavorites();
							})
							.catch(function (error) {
								console.error(error);
								console.log('Ошибка запроса');
							});
					}

					const addToCartBtn = (event, productItem) => {
						// Проверяем, не заблокирована ли кнопка
						if (isAddToCartDisabled.value) {
							console.log('Кнопка заблокирована - выбран цвет "none"');
							return;
						}

						console.log(productId);
						if (!appShop.value.cart.btnAddActive) {
							appShop.value.cart.btnAddActive = true;
							showAddToCartNotification(productItem);
							if (event.target.querySelector('.addToCardBtnHeadingTitle')) {
								event.target.querySelector(
									'.addToCardBtnHeadingTitle'
								).innerHTML = 'Перейти в корзину';
							}
						} else {
							window.location.href = '/cart';
						}
					};

					const addToWhtListMob = (itemCard) => {
						console.log('test mob');
						showAddFavoriteNotification(itemCard);
					};

					const toggleIdeaModal = () => {
						console.log('PC - идеи!');
						appShop.value.idea.modal.show = !appShop.value.idea.modal.show;
					};

					const toggleIdeaMbModal = () => {
						console.log('Мобильные - идеи!');
						appShop.value.idea.modal_mb.show =
							!appShop.value.idea.modal_mb.show;
					};

					const fetchIdeaProduct = (productId) => {
						axios
							.post(
								ajaxUrl,
								{
									nonce: nonce,
								},
								{
									params: {
										action: 'get_product_modal',
										product_id: productId,
									},
								}
							)
							.then(function (response) {
								if (response.data.success) {
									const data_idea = response.data.data;
									appShop.value.idea.modal.data = data_idea;
									return true;
								} else {
									return false;
								}
							});
					};

					const showIdeaByProduct = (productId) => {
						if (fetchIdeaProduct(productId)) {
						}
						if (window.innerWidth <= 768) {
							toggleIdeaMbModal();
						} else {
							toggleIdeaModal();
						}
					};

					const toggleMobSizePanel = () => {
						appShop.value.cart.mob.size.show =
							!appShop.value.cart.mob.size.show;
					};

					// Следим за изменением состояния меню и блокируем body
					Vue.watch(
						() =>
							appShop.value.idea.modal.show || appShop.value.idea.modal_mb.show,
						(show) => {
							if (show) {
								document.body.style.overflow = 'hidden';
								document.body.style.touchAction = 'none';
							} else {
								document.body.style.overflow = '';
								document.body.style.touchAction = '';
							}
						}
					);

					Vue.onMounted(() => {
						const defColor = el.dataset.defaultColor;
						const defSize = el.dataset.defaultSize;
						const defColorId = el.dataset.defaultColorId;

						if (defColor) {
							appShop.value.cart.select.color = defColor;
							appShop.value.cart.select.colorId = defColorId;
							// Инициализируем слайдер с дефолтным цветом
							setTimeout(() => {
								if (
									window.states.shopPrdDetailCardImages &&
									window.states.shopPrdDetailCardImages[stateId]
								) {
									window.states.shopPrdDetailCardImages[
										stateId
									].updateSlidesByColor(defColor);
								}
							}, 200);
						}
						if (defSize) {
							appShop.value.cart.select.size = defSize;
						}
					});

					return {
						getProductsCartExist,
						selectCartSize,
						selectCartColor,
						addToCartBtn,
						addToCartButtonText,
						isAddToCartDisabled,
						getSelectedWhtLst,
						addToWhtListMob,
						toggleIdeaModal,
						toggleIdeaMbModal,
						showIdeaByProduct,
						toggleMobSizePanel,
						appShop,
						appFavoriteBtn,
						isAddToCartDisabled,
					};
				},
			}).mount(el);
		});
	};

	// Компонент для карточки товара (мобильная версия)
	const singlePrdMob = () => {
		const nodeList = document.querySelectorAll('#singlePrdMob');
		if (!nodeList.length) return console.warn('#singlePrdCard не найден!');

		nodeList.forEach((el) => {
			const stateId = el.dataset.prdGroupId;
			if (!stateId) throw new Error(`У узла нет атрибута айди товара`, el);

			window.states.singlePrdMob = window.states.singlePrdMob || {};
			window.states.singlePrdMob[stateId] = Vue.createApp({
				setup() {
					const { ref, computed, watch, onMounted } = Vue;
					const appMobShop = Vue.ref({
						cart: {
							select: {
								color: '',
								defaultColor: '',
								size: Vue.computed({
									get: () => window.states.productSize.size,
									set: (value) => (window.states.productSize.size = value),
								}),
							},
							mob: {
								size: {
									show: false,
								},
							},
							btnAddActive: false,
						},
					});

					const isStock = el.dataset.isStock === 'true';

					// COMPUTED СВОЙСТВА ДЛЯ БЛОКИРОВКИ КНОПКИ (объявляем только один раз)
					const isAddToCartDisabled = Vue.computed(() => {
						const selectedColor = appMobShop.value?.cart?.select?.color;
						if (!isStock) return true;
						return selectedColor && selectedColor.toLowerCase() === 'none';
					});

					const addToCartButtonText = Vue.computed(() => {
						if (!isStock) {
							return 'Товара нет в наличии';
						}
						if (isAddToCartDisabled.value) {
							return 'Выберите цвет';
						}
						return appMobShop.value.cart.btnAddActive
							? 'Перейти в корзину'
							: 'Добавить в корзину';
					});

					// Функция для обновления кнопки
					// ЗАМЕНИТЬ ЭТУ ФУНКЦИЮ:
					const updateCartButton = () => {
						// ИСПРАВЛЕННЫЙ СЕЛЕКТОР - ищем именно мобильную кнопку в правильном контейнере
						const button = document.querySelector(
							'#singlePrdMob .addToCartBtn'
						);
						const buttonTitle = document.querySelector(
							'#singlePrdMob .addToCartBtn .addToCartBtnTitle'
						);

						if (button && buttonTitle) {
							if (isAddToCartDisabled.value) {
								// Блокируем кнопку
								button.style.opacity = '0.5';
								button.style.pointerEvents = 'none';
								button.style.backgroundColor = '#f3f4f6';
								button.style.color = '#9ca3af';

								// Меняем только текст, сохраняем цену
								const priceSpan = buttonTitle.querySelector(
									'.woocommerce-Price-amount'
								);
								const priceHtml = priceSpan ? priceSpan.outerHTML : '';
								buttonTitle.innerHTML = `<span>${addToCartButtonText.value}</span> ${priceHtml}`;
							} else {
								// Разблокируем кнопку
								button.style.opacity = '1';
								button.style.pointerEvents = 'auto';
								button.style.backgroundColor = '';
								button.style.color = '';

								// Восстанавливаем обычный текст
								const priceSpan = buttonTitle.querySelector(
									'.woocommerce-Price-amount'
								);
								const priceHtml = priceSpan ? priceSpan.outerHTML : '';
								const buttonText = appMobShop.value.cart.btnAddActive
									? 'Перейти в корзину'
									: 'Добавить в корзину';
								buttonTitle.innerHTML = `<span>${buttonText}</span> ${priceHtml}`;
							}
						}
					};

					// Следим за изменением цвета и состояния кнопки
					Vue.watch(
						() => [
							appMobShop.value.cart.select.color,
							appMobShop.value.cart.btnAddActive,
						],
						() => {
							updateCartButton();
						}
					);

					const getProductsCartExist = (ProductId) =>
						Vue.computed(() => appShop.value.products.includes(ProductId));

					const FAVORITES_KEY = 'favorite_products';

					function getFavorites() {
						try {
							return JSON.parse(localStorage.getItem(FAVORITES_KEY)) || [];
						} catch (e) {
							return [];
						}
					}

					function saveFavorites(ids) {
						localStorage.setItem(FAVORITES_KEY, JSON.stringify(ids));
					}

					function addToFavorites(productId) {
						let favorites = getFavorites();
						if (!favorites.includes(productId)) {
							favorites.push(productId);
							saveFavorites(favorites);
						}
					}

					function removeFromFavorites(productId) {
						let favorites = getFavorites().filter((id) => id !== productId);
						saveFavorites(favorites);
					}

					function isFavorite(productId) {
						return getFavorites().includes(productId);
					}

					// Получаем параметры из data-атрибутов
					const productId = el.getAttribute('data-product-id');
					const nonce = el.getAttribute('data-nonce');
					const ajaxUrl = el.getAttribute('data-ajax-url');

					const getSelectedWhtLst = (productId) =>
						Vue.computed(() => !getFavorites().includes(productId)).value;

					const addToWhtListMob = (itemCard) => {
						console.log('test');
						showAddFavoriteNotification(itemCard);
					};

					function showAddToCartNotification(cardItem) {
						let imageUrl = cardItem.imageUrl ? cardItem.imageUrl : '';
						// Отправляем AJAX-запрос через axios
						return axios
							.post(
								ajaxUrl,
								{
									nonce: nonce,
								},
								{
									params: {
										action: 'add_to_cart_mb',
										product_id: productId,
										productQuanity: 1,
										productSize: appMobShop.value.cart.select.size || false,
										productColor: appMobShop.value.cart.select.colorId || false,
									},
								}
							)
							.then(function (response) {
								if (response.data.success) {
									const action = response.data.data.action;
									if (action === 'added') {
										jQuery.notify(
											jQuery('.notifyAnchor'),
											{
												title: 'Модель добавлена в корзину!',
												image: `<img src="${imageUrl}" alt="Карточка товара" />`,
											},
											{
												style: 'cartStyle',
												className: 'base',
												showAnimation: 'slideDown',
												showDuration: 300,
												hideAnimation: 'slideUp',
												hideDuration: 200,
												autoHide: true,
												autoHideDelay: 2000,
												gap: 0,
												position: 'bottom left',
												arrowShow: false,
											}
										);
										return true;
									} else {
										jQuery.notify(
											jQuery('.notifyAnchor'),
											{
												title: 'Модель удалена из корзины!',
												image: `<img src="${imageUrl}" alt="Карточка товара" />`,
											},
											{
												style: 'cartStyle',
												className: 'base',
												showAnimation: 'slideDown',
												showDuration: 300,
												hideAnimation: 'slideUp',
												hideDuration: 200,
												autoHide: true,
												autoHideDelay: 2000,
												gap: 0,
												position: 'bottom left',
												arrowShow: false,
											}
										);
									}
									window.states.headMainNav.fetchCart();
									return false;
								} else {
									console.log('Произошла ошибка');
									jQuery.notify(
										jQuery('.notifyAnchor'),
										{
											title: response.data.data.message,
											image: `<img src="${imageUrl}" alt="Карточка товара" />`,
										},
										{
											style: 'cartStyle',
											className: 'base',
											showAnimation: 'slideDown',
											showDuration: 300,
											hideAnimation: 'slideUp',
											hideDuration: 200,
											autoHide: true,
											autoHideDelay: 2000,
											gap: 0,
											position: 'bottom left',
											arrowShow: false,
										}
									);
								}
								return false;
							})
							.catch(function (error) {
								console.error(error);
								console.log('Ошибка запроса');
							});
					}

					function showAddFavoriteNotification(cardItem) {
						let imageUrl = cardItem.imageUrl ? cardItem.imageUrl : '';
						axios
							.post(
								ajaxUrl,
								{
									nonce: nonce,
								},
								{
									params: {
										action: 'toggle_favorite',
										product_id: productId,
									},
								}
							)
							.then(function (response) {
								if (response.data.success) {
									const action = response.data.data.action;
									if (action === 'added') {
										jQuery.notify(
											jQuery('.notifyAnchor'),
											{
												title: 'Модель добавлена в избранное!',
												image: `<img src="${imageUrl}" alt="Карточка товара" />`,
											},
											{
												style: 'cartStyle',
												className: 'base',
												arrowShow: false,
												showAnimation: 'slideDown',
												showDuration: 300,
												hideAnimation: 'slideUp',
												hideDuration: 200,
												autoHide: true,
												autoHideDelay: 2000,
												gap: 0,
												position: 'bottom left',
												arrowShow: false,
											}
										);
									} else {
										jQuery.notify(
											jQuery('.notifyAnchor'),
											{
												title: 'Модель удалена из избранного!',
												image: `<img src="${imageUrl}" alt="Карточка товара" />`,
											},
											{
												style: 'cartStyle',
												className: 'base',
												showAnimation: 'slideDown',
												showDuration: 300,
												hideAnimation: 'slideUp',
												hideDuration: 200,
												autoHide: true,
												autoHideDelay: 2000,
												gap: 0,
												position: 'bottom left',
												arrowShow: false,
											}
										);
									}
									window.states.headMainNav.fetchFavorites();
								} else {
									console.log('Произошла ошибка');
									jQuery.notify(
										jQuery('.notifyAnchor'),
										{
											title: response.data.data.message,
											image: `<img src="${imageUrl}" alt="Карточка товара" />`,
										},
										{
											style: 'cartStyle',
											className: 'base',
											showAnimation: 'slideDown',
											showDuration: 300,
											hideAnimation: 'slideUp',
											hideDuration: 200,
											autoHide: true,
											autoHideDelay: 2000,
											gap: 0,
											position: 'bottom left',
											arrowShow: false,
										}
									);
								}
							})
							.catch(function (error) {
								console.error(error);
								console.log('Ошибка запроса');
							});
					}

					// ИСПРАВЛЕННАЯ ФУНКЦИЯ addToCartMob
					const addToCartMob = async (event, productItem) => {
						const isAdded = await showAddToCartNotification(productItem);

						if (!isAdded) return;

						// Проверяем, не заблокирована ли кнопка
						if (isAddToCartDisabled.value) {
							console.log('Кнопка заблокирована - выбран цвет "none"');
							return;
						}

						console.log(productId);
						if (!appMobShop.value.cart.btnAddActive) {
							appMobShop.value.cart.btnAddActive = true;
							// Обновляем кнопку после изменения состояния
							updateCartButton();
						} else {
							window.location.href = '/cart';
						}
					};

					const toggleMobSizePanel = () => {
						appMobShop.value.cart.mob.size.show =
							!appMobShop.value.cart.mob.size.show;
					};

					const selectCartSize = (size) => {
						console.log('Выбран размер:', size);
						appMobShop.value.cart.select.size = size;
					};

					// Функция выбора цвета (убрано дублирование isAddToCartDisabled)
					const selectCartColor = (color) => {
						console.log('Выбран цвет:', color);
						appMobShop.value.cart.select.color = color;

						// Синхронизируем с десктопным компонентом
						if (
							window.states.singlePrdCard &&
							window.states.singlePrdCard[stateId]
						) {
							window.states.singlePrdCard[stateId].selectCartColor(color);
						}

						// Обновляем слайдер изображений
						if (
							window.states.shopPrdDetailCardImages &&
							window.states.shopPrdDetailCardImages[stateId]
						) {
							window.states.shopPrdDetailCardImages[
								stateId
							].updateSlidesByColor(color);
						}
					};

					Vue.onMounted(() => {
						const defColor =
							el.dataset.defaultColor ||
							window.states.singlePrdCard[stateId].appShop.cart.select.color;

						const defSize =
							el.dataset.defaultSize ||
							window.states.singlePrdCard[stateId].appShop.cart.select.size;

						const defColorId =
							el.dataset.defaultSize ||
							window.states.singlePrdCard[stateId].appShop.cart.select.colorId;

						if (defColor) {
							appMobShop.value.cart.select.color = defColor;
						}
						if (defColorId) {
							appMobShop.value.cart.select.colorId = defColorId;
						}
						if (defSize) {
							appMobShop.value.cart.select.size = defSize;
						}

						// Инициализация состояния кнопки
						Vue.nextTick(() => {
							updateCartButton();
						});
					});

					return {
						getProductsCartExist,
						toggleMobSizePanel,
						selectCartSize,
						selectCartColor,
						addToCartMob,
						addToWhtListMob,
						getSelectedWhtLst,
						appMobShop,
						isAddToCartDisabled,
						addToCartButtonText,
					};
				},
			}).mount(el);
		});
	};

	// Компонент для карточки товара (мобильная версия)
	const favBtnPrd = () => {
		const favBtnPrds = document.querySelectorAll('#favBtnPrd');
		console.log(favBtnPrds);

		const FAVORITES_KEY = 'favorite_products';

		function getFavorites() {
			try {
				return JSON.parse(localStorage.getItem(FAVORITES_KEY)) || [];
			} catch (e) {
				return [];
			}
		}

		function saveFavorites(ids) {
			localStorage.setItem(FAVORITES_KEY, JSON.stringify(ids));
		}

		function addToFavorites(productId) {
			let favorites = getFavorites();
			if (!favorites.includes(productId)) {
				favorites.push(productId);
				saveFavorites(favorites);
			}
		}

		function removeFromFavorites(productId) {
			let favorites = getFavorites().filter((id) => id !== productId);
			saveFavorites(favorites);
		}

		function isFavorite(productId) {
			return getFavorites().includes(productId);
		}

		// setTimeout(() => {
		try {
			// console.log(favBtnPrds);
			for (const favBtn of favBtnPrds) {
				if (favBtn.dataset.vApp == '') {
					return;
				}
				Vue.createApp({
					setup() {
						const favorites = Vue.ref(getFavorites());
						const appMobShop = Vue.ref({
							cart: {
								select: {
									color: '',
									size: 'L',
								},
								mob: {
									size: {
										show: false,
									},
								},
							},
							favorite: {
								active: false,
							},
						});

						const appFavoriteBtn = Vue.ref({
							status: {
								active: false,
							},
						});

						const productId = parseInt(favBtn.getAttribute('data-product-id'));
						const nonce = favBtn.getAttribute('data-nonce');
						const ajaxUrl = favBtn.getAttribute('data-ajax-url');

						// const getSelectedWhtLst = (productId) => Vue.computed(() => !(getFavorites().includes(productId))).value;

						// function getSelectedWhtLst(productId){
						//     return Vue.computed(() => getFavorites().includes(productId));
						// }

						function showAddFavoriteNotification(cardItem) {
							let imageUrl = cardItem.imageUrl ? cardItem.imageUrl : '';
							// console.log(productId, 'favBtn', getSelectedWhtLst(productId));

							axios
								.post(
									ajaxUrl,
									{
										nonce: nonce,
									},
									{
										params: {
											action: 'toggle_favorite',
											product_id: productId,
										},
									}
								)
								.then(function (response) {
									if (response.data.success) {
										const action = response.data.data.action;
										if (action === 'added') {
											addToFavorites(productId);

											appFavoriteBtn.value.status.active = true;

											jQuery.notify(
												jQuery('.notifyAnchor'),
												{
													title: 'Модель добавлена в избранное!',
													image: `<img src="${imageUrl}" alt="Карточка товара" />`,
												},

												{
													style: 'cartStyle',
													className: 'base',
													showAnimation: 'slideDown',
													showDuration: 300,
													hideAnimation: 'slideUp',
													hideDuration: 200,
													autoHide: true,
													autoHideDelay: 2000,
													gap: 0,
													position: 'bottom left',
													arrowShow: false,
												}
											);

											console.log('Товар добавлен в избранное');
										} else {
											removeFromFavorites(productId);

											appFavoriteBtn.value.status.active = false;

											jQuery.notify(
												jQuery('.notifyAnchor'),
												{
													title: 'Модель удалена из избранного!',
													image: `<img src="${imageUrl}" alt="Карточка товара" />`,
												},
												{
													style: 'cartStyle',
													className: 'base',
													showAnimation: 'slideDown',
													showDuration: 300,
													hideAnimation: 'slideUp',
													hideDuration: 200,
													autoHide: true,
													autoHideDelay: 2000,
													gap: 0,
													position: 'bottom left',
													arrowShow: false,
												}
											);

											console.log('Модель удалена из избранного');
										}
									} else {
										console.log('Произошла ошибка');
										jQuery.notify(
											jQuery('.notifyAnchor'),
											{
												title: response.data.data.message,
												image: `<img src="${imageUrl}" alt="Карточка товара" />`,
											},
											{
												style: 'cartStyle',
												className: 'base',
												showAnimation: 'slideDown',
												showDuration: 300,
												hideAnimation: 'slideUp',
												hideDuration: 200,
												autoHide: true,
												autoHideDelay: 2000,
												gap: 0,
												position: 'bottom left',
												arrowShow: false,
											}
										);
									}

									window.states.headMainNav.fetchFavorites();
								})
								.catch(function (error) {
									console.error(error);
									console.log('Ошибка запроса');
								});
						}

						const addToWhtListMob = (itemCard) => {
							console.log('addToWhtListMob', itemCard);
							showAddFavoriteNotification(itemCard);
							appFavoriteBtn.value.status.active =
								!appFavoriteBtn.value.status.active;
							// navigation.reload();
						};

						// Vue.watch(getFavorites(), () => {
						//     if (getFavorites().includes(productId)) {
						//         appFavoriteBtn.value.status.active = true;
						//     } else {
						//         appFavoriteBtn.value.status.active = false;
						//     }
						// })

						Vue.onMounted(() => {
							// console.log("Fav btn", favBtn);
							// console.log(productId, getFavorites().includes(productId));

							if (getFavorites().includes(productId)) {
								appFavoriteBtn.value.status.active = true;
							} else {
								appFavoriteBtn.value.status.active = false;
							}
						});

						return {
							// getFavorites,
							appFavoriteBtn,
							addToWhtListMob,
							// getSelectedWhtLst,
							appMobShop,
						};
					},
				}).mount(favBtn);
			}
		} catch (e) {
			console.error(e);
		}
		// }, 400);
	};

	// window.favBtnPrdSync = favBtnPrd;

	// Компонент для навигационного меню (шапка)
	// const cartPromocode = () => {
	//     const elPromocode = document.getElementById('promoCart');
	//     if (!elPromocode) return console.warn('#promoCart не найден!');
	//
	//     window.states.cartPromocode = Vue.createApp({
	//         setup() {
	//             const state = Vue.reactive({
	//                 isOpen: false,
	//                 promocode: '',
	//                 message: '',
	//                 isError: false,
	//                 isLoading: false
	//             });
	//
	//             const togglePromocode = () => {
	//                 state.isOpen = !state.isOpen;
	//                 if (state.isOpen) {
	//                     Vue.nextTick(() => {
	//                         document.querySelector('.promocodeBlock__input')?.focus();
	//                     });
	//                 }
	//             };
	//
	//             const applyPromocode = async () => {
	//                 if (!state.promocode) return;
	//
	//                 state.isLoading = true;
	//                 state.message = '';
	//                 state.isError = false;
	//
	//                 try {
	//                     const nonce = elPromocode.getAttribute('data-nonce');
	//                     const ajaxUrl = elPromocode.getAttribute('data-ajax-url');
	//
	//                     const response = await axios.post(ajaxUrl, {
	//                         nonce: nonce,
	//                         coupon_code: state.promocode,
	//                     }, {
	//                         params: {
	//                             action: 'apply_cart_coupon',
	//                             coupon_code: state.promocode,
	//                         }
	//                     });
	//
	//                     if (response.data.success) {
	//                         state.message = response.data.message || 'Промокод успешно применен!';
	//                         state.promocode = '';
	//
	//
	//                         jQuery.notify(
	//                             // 2.1) содержимое: просто передаём текст
	//                             state.message,
	//                             {
	//                                 // 2.2) указываем наш стиль и класс base
	//                                 style: 'cartStyle',
	//                                 className: 'base',
	//                                 // 2.3) появление/скрытие
	//                                 showAnimation: 'slideDown',
	//                                 showDuration: 300,
	//                                 hideAnimation: 'slideUp',
	//                                 hideDuration: 200,
	//                                 // 2.4) автоскрытие через 2 секунды
	//                                 autoHide: true,
	//                                 autoHideDelay: 2000,
	//                                 // 2.5) позиционирование (можно изменить на top left / bottom right и т.д.)
	//                                 globalPosition: 'top center',
	//                                 // 2.6) передаём URL картинки, чтобы плагин подставил её в <img>
	//                                 icon: null
	//                             }
	//                         );
	//                     } else {
	//                         state.message = response.data.message || 'Ошибка применения промокода';
	//                         state.isError = true;
	//
	//                         jQuery.notify(
	//                             // 2.1) содержимое: просто передаём текст
	//                             state.message,
	//                             {
	//                                 // 2.2) указываем наш стиль и класс base
	//                                 style: 'cartStyle',
	//                                 className: 'base',
	//                                 // 2.3) появление/скрытие
	//                                 showAnimation: 'slideDown',
	//                                 showDuration: 300,
	//                                 hideAnimation: 'slideUp',
	//                                 hideDuration: 200,
	//                                 // 2.4) автоскрытие через 2 секунды
	//                                 autoHide: true,
	//                                 autoHideDelay: 2000,
	//                                 // 2.5) позиционирование (можно изменить на top left / bottom right и т.д.)
	//                                 globalPosition: 'top center',
	//                                 // 2.6) передаём URL картинки, чтобы плагин подставил её в <img>
	//                                 icon: null
	//                             }
	//                         );
	//                     }
	//
	//                 } catch (error) {
	//                     console.error('Promocode application failed:', error);
	//                     state.message = 'Произошла ошибка при обработке запроса';
	//                     state.isError = true;
	//                 } finally {
	//                     state.isLoading = false;
	//                 }
	//             };
	//
	//             // Lifecycle hook example (optional initialization)
	//             Vue.onMounted(() => {
	//                 console.log('Component mounted');
	//             });
	//
	//             return {
	//                 state,
	//                 togglePromocode,
	//                 applyPromocode
	//             };
	//         }
	//     }).mount(elPromocode);
	// };

	// Компонент для навигационного меню (шапка)
	const headMainNav = () => {
		const el = document.getElementById('headerMainNav');
		if (!el) return console.warn('#headerMainNav не найден!');

		//

		const FAVORITES_KEY = 'favorite_products';

		function getFavorites() {
			try {
				return JSON.parse(localStorage.getItem(FAVORITES_KEY)) || [];
			} catch (e) {
				return [];
			}
		}

		function saveFavorites(ids) {
			localStorage.setItem(FAVORITES_KEY, JSON.stringify(ids));
		}

		function addToFavorites(productId) {
			let favorites = getFavorites();
			if (!favorites.includes(productId)) {
				favorites.push(productId);
				saveFavorites(favorites);
			}
		}

		function removeFromFavorites(productId) {
			let favorites = getFavorites().filter((id) => id !== productId);
			saveFavorites(favorites);
		}

		function isFavorite(productId) {
			return getFavorites().includes(productId);
		}

		// Получаем параметры из data-атрибутов
		// const productId = el.getAttribute('data-product-id');
		const nonce = el.getAttribute('data-nonce');
		const ajaxUrl = el.getAttribute('data-ajax-url');
		const dataCount = el.getAttribute('data-count');
		const userAuthStatus = el.getAttribute('data-user-auth');

		const headerComponent = Vue.createApp({
			setup() {
				const debug = false;
				const appMainNav = Vue.ref({
					navbar: {
						cart: {
							count: null,
						},
						favorite: {
							count: null,
						},
						user: {
							isAuth: false,
						},
						modals: {
							account: {
								step: '',
								field: {
									email: '',
									password: '',
									email_checkbox: true,
								},
							},
						},
						nav_search: {
							show: false,
							context: {
								queryText: '',
							},
							cat: {
								count: 1,
								result: {
									list: [],
								},
							},
							products: {
								count: 0,
								result: {
									list: [],
								},
							},
						},
					},
					mob: {
						nav_menu: {
							logo: {
								show: false,
							},
							fixed: {
								show: false,
							},
							show: false,
						},
						//TODO: upd
						sub_menu: {
							history: [],
							show: false, // Состояние модального окна
							content: '', // Содержимое подменю
							title: '',
						},
						nav_search: {
							context: {
								queryText: '',
							},
							show: false,
							cat: {
								count: 0,
								result: {
									list: [],
								},
							},
							products: {
								count: 0,
								result: {
									list: [],
								},
							},
						},
					},
				});

				const getSelectedWhtLst = (productId) => {
					console.log('getSelectedWhtLst', productId);
					console.log(getFavorites().includes(productId));
					return Vue.computed(() => getFavorites().includes(productId)).value;
				};

				function showAddFavoriteNotification(cardItem) {
					let imageUrl = cardItem.imageUrl ? cardItem.imageUrl : '';
					const productId = cardItem.productId;

					axios
						.post(
							ajaxUrl,
							{
								nonce: nonce,
							},
							{
								params: {
									action: 'toggle_favorite',
									product_id: productId,
								},
							}
						)
						.then(function (response) {
							if (response.data.success) {
								const action = response.data.data.action;
								if (action === 'added') {
									addToFavorites(productId);

									jQuery.notify(
										jQuery('.notifyAnchor'),
										{
											title: 'Модель добавлена в избранное!',
											image: `<img src="${imageUrl}" alt="Карточка товара" />`,
										},

										{
											style: 'cartStyle',
											className: 'base',
											showAnimation: 'slideDown',
											showDuration: 300,
											hideAnimation: 'slideUp',
											hideDuration: 200,
											autoHide: true,
											autoHideDelay: 2000,
											gap: 0,
											position: 'bottom left',
											arrowShow: false,
										}
									);

									console.log('Товар добавлен в избранное');
								} else {
									removeFromFavorites(productId);

									jQuery.notify(
										jQuery('.notifyAnchor'),
										{
											title: 'Модель удалена из избранного!',
											image: `<img src="${imageUrl}" alt="Карточка товара" />`,
										},
										{
											style: 'cartStyle',
											className: 'base',
											showAnimation: 'slideDown',
											showDuration: 300,
											hideAnimation: 'slideUp',
											hideDuration: 200,
											autoHide: true,
											autoHideDelay: 2000,
											gap: 0,
											position: 'bottom left',
											arrowShow: false,
										}
									);

									console.log('Модель удалена из избранного');
								}
							} else {
								console.log('Произошла ошибка');
								jQuery.notify(
									jQuery('.notifyAnchor'),
									{
										title: response.data.data.message,
										image: `<img src="${imageUrl}" alt="Карточка товара" />`,
									},
									{
										style: 'cartStyle',
										className: 'base',
										showAnimation: 'slideDown',
										showDuration: 300,
										hideAnimation: 'slideUp',
										hideDuration: 200,
										autoHide: true,
										autoHideDelay: 2000,
										gap: 0,
										position: 'bottom left',
										arrowShow: false,
									}
								);
							}

							window.states.headMainNav.fetchFavorites();
						})
						.catch(function (error) {
							console.error(error);
							console.log('Ошибка запроса');
						});
				}

				const addToWhtListMob = (itemCard) => {
					console.log('addToWhtListMob', itemCard);
					showAddFavoriteNotification(itemCard);
				};

				const hideMobNav = () => {
					appMainNav.value.mob.nav_menu.show = false;
				};

				const toggleMobNav = () => {
					appMainNav.value.mob.nav_menu.show =
						!appMainNav.value.mob.nav_menu.show;
				};

				const toggleMobSearch = () => {
					hideMobNav();
					appMainNav.value.mob.nav_search.show =
						!appMainNav.value.mob.nav_search.show;
				};

				//TODO: upd.

				// const openSubMenu = (dropdownContent, event, button) => {
				// 	console.log('Opening submenu:', dropdownContent);
				// 	appMainNav.value.mob.sub_menu.content = dropdownContent;
				// 	appMainNav.value.mob.sub_menu.title = button
				// 		? button.textContent.trim()
				// 		: '';
				// 	appMainNav.value.mob.sub_menu.show = true;
				// 	if (button && button.setAttribute) {
				// 		document
				// 			.querySelectorAll('.quadmenu-dropdown-toggle')
				// 			.forEach((btn) => btn.setAttribute('aria-expanded', 'false'));
				// 		button.setAttribute('aria-expanded', 'true');
				// 	} else {
				// 		console.warn('Button not available for aria-expanded update');
				// 	}
				// };

				const openSubMenu = (dropdownContent, event, button, title) => {
					// Если менюшка уже открыта
					if (appMainNav.value.mob.sub_menu.show) {
						const preventMenu = {
							title: appMainNav.value.mob.sub_menu.title,
							content: appMainNav.value.mob.sub_menu.content,
						};

						appMainNav.value.mob.sub_menu.history.push(preventMenu);
					}
					appMainNav.value.mob.sub_menu.content = dropdownContent;
					appMainNav.value.mob.sub_menu.title = title;
					appMainNav.value.mob.sub_menu.show = true;
					// if (button && button.setAttribute) {
					// 	document
					// 		.querySelectorAll('.quadmenu-dropdown-toggle')
					// 		.forEach((btn) => btn.setAttribute('aria-expanded', 'false'));
					// 	button.setAttribute('aria-expanded', 'true');
					// } else {
					// 	console.warn('Button not available for aria-expanded update');
					// }
				};

				const closeSubMenu = () => {
					if (appMainNav.value.mob.sub_menu.history.length === 0) {
						appMainNav.value.mob.sub_menu.show = false;
						appMainNav.value.mob.sub_menu.content = '';
						appMainNav.value.mob.sub_menu.title = ''; // Clear title
						document
							.querySelectorAll('.quadmenu-dropdown-toggle')
							.forEach((btn) => btn.setAttribute('aria-expanded', 'false'));
					} else {
						const preventMenu = appMainNav.value.mob.sub_menu.history.pop();
						appMainNav.value.mob.sub_menu.content = preventMenu.content;
						appMainNav.value.mob.sub_menu.title = preventMenu.title; // Clear title
					}
				};

				const onInputSearchHandler = (e) => {
					// console.log(appMainNav.value.mob.nav_search.context.queryText);
					// Очистка предыдущего таймера
					clearTimeout(this.debounceTimer);

					// Новый debounce: 400 мс
					this.debounceTimer = setTimeout(() => {
						searchText();
					}, 400);
				};

				const searchText = async () => {
					const query =
						appMainNav.value.mob.nav_search.context.queryText.trim();

					if (query.length < 2) {
						appMainNav.value.mob.nav_search.products.result.list = [];
						appMainNav.value.mob.nav_search.cat.result.list = [];
						return;
					}

					try {
						const response = await fetch(
							`/wp-admin/admin-ajax.php?action=search_prd_or_cat&query=${encodeURIComponent(
								query
							)}`
						);
						const data = await response.json();
						appMainNav.value.mob.nav_search.products.result.list =
							data.products;
						appMainNav.value.mob.nav_search.products.count = data.products
							? data.products.length
							: 0;
						//
						appMainNav.value.mob.nav_search.cat.result.list = data.categories;
						appMainNav.value.mob.nav_search.cat.count = data.categories
							? data.categories.length
							: 0;
					} catch (error) {
						console.error('Ошибка запроса:', error);
						this.results = [];
					}
				};

				const fetchCart = async () => {
					const response = await axios
						.get(ajaxUrl, {
							params: {
								nonce: nonce,
								action: 'cart_info',
							},
						})
						.then(function (response) {
							if (response.data.success) {
								const cart_count = response.data.data.cart_count;
								appMainNav.value.navbar.cart.count = cart_count;
								// console.log(cart_count);
							}
						});
				};
				const fetchFavorites = async () => {
					const response = await axios
						.get(ajaxUrl, {
							params: {
								nonce: nonce,
								action: 'favorites_info',
							},
						})
						.then(function (response) {
							if (response.data.success) {
								const favorites = response.data.data.favorites;
								const favorites_count = response.data.data.favorites_count;
								appMainNav.value.navbar.favorite.count = favorites_count;
								// console.log(cart_count);
							}
						});
				};

				// account

				const closeModalAccount = () => {
					appMainNav.value.navbar.modals.account.step = '';
					appMainNav.value.navbar.modals.account.field.email = '';
					appMainNav.value.navbar.modals.account.field.password = '';
				};

				const viewModalAccount = (step) => {
					appMainNav.value.navbar.modals.account.step = step;
					console.log(appMainNav.value.modals);
				};

				const checkEmailExists = () => {
					// appMainNav.navbar.modals.account.field.passwor
					const email = appMainNav.value.navbar.modals.account.field.email;
					fetch('/wp-admin/admin-ajax.php?action=check_email_exists', {
						method: 'POST',
						headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
						body: new URLSearchParams({ email }),
					})
						.then((res) => res.json())
						.then((data) => {
							if (data.exists) {
								appMainNav.value.navbar.modals.account.step = 'authLogin';
							} else {
								appMainNav.value.navbar.modals.account.step = 'authCreateNew';
							}
						});
				};

				const doLogin = () => {
					const { email, password } =
						appMainNav.value.navbar.modals.account.field;

					if (password && password.length <= 3) {
						jQuery.notify(
							jQuery('.notifyAnchor'),
							{
								title: 'Ошибка! Пароль очень короткий!',
							},
							{
								style: 'cartStyle',
								className: 'base',
								showAnimation: 'slideDown',
								showDuration: 300,
								hideAnimation: 'slideUp',
								hideDuration: 200,
								autoHide: true,
								autoHideDelay: 2000,
								gap: 0,
								position: 'bottom left',
								arrowShow: false,
							}
						);
						return;
					}

					fetch('/wp-admin/admin-ajax.php', {
						method: 'POST',
						headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
						body: new URLSearchParams({
							action: 'ajax_login',
							email,
							password,
						}),
					})
						.then((res) => res.json())
						.then((data) => {
							if (data.success) {
								appMainNav.value.navbar.user.isAuth = true;
								appMainNav.value.navbar.user.data = data.user;
								closeModalAccount();

								jQuery.notify(
									jQuery('.notifyAnchor'),
									{
										title: 'Успешная авторизация!',
									},
									{
										style: 'cartStyle',
										className: 'base',
										showAnimation: 'slideDown',
										showDuration: 300,
										hideAnimation: 'slideUp',
										hideDuration: 200,
										autoHide: true,
										autoHideDelay: 2000,
										gap: 0,
										position: 'bottom left',
										arrowShow: false,
									}
								);

								navigation.reload();
							} else {
								// alert(data.message);
								jQuery.notify(
									jQuery('.notifyAnchor'),
									{
										title: 'Ошибка! Проверьте данные и попробуйте снова!',
									},
									{
										style: 'cartStyle',
										className: 'base',
										showAnimation: 'slideDown',
										showDuration: 300,
										hideAnimation: 'slideUp',
										hideDuration: 200,
										autoHide: true,
										autoHideDelay: 2000,
										gap: 0,
										position: 'bottom left',
										arrowShow: false,
									}
								);
							}
						});
				};

				const doRegister = () => {
					const { email, password } =
						appMainNav.value.navbar.modals.account.field;

					if (password && password.length <= 3) {
						jQuery.notify(
							jQuery('.notifyAnchor'),
							{
								title: 'Ошибка! Пароль очень короткий!',
							},
							{
								style: 'cartStyle',
								className: 'base',
								showAnimation: 'slideDown',
								showDuration: 300,
								hideAnimation: 'slideUp',
								hideDuration: 200,
								autoHide: true,
								autoHideDelay: 2000,
								gap: 0,
								position: 'bottom left',
								arrowShow: false,
							}
						);
						return;
					}

					fetch('/wp-admin/admin-ajax.php', {
						method: 'POST',
						headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
						body: new URLSearchParams({
							action: 'ajax_register',
							email,
							password,
						}),
					})
						.then((res) => res.json())
						.then((data) => {
							if (data.success) {
								appMainNav.value.navbar.user.isAuth = true;
								appMainNav.value.navbar.user.data = data.user;
								closeModalAccount();
								navigation.reload();

								jQuery.notify(
									jQuery('.notifyAnchor'),
									{
										title: 'Успешная регистрация!',
									},
									{
										style: 'cartStyle',
										className: 'base',
										showAnimation: 'slideDown',
										showDuration: 300,
										hideAnimation: 'slideUp',
										hideDuration: 200,
										autoHide: true,
										autoHideDelay: 2000,
										gap: 0,
										position: 'bottom left',
										arrowShow: false,
									}
								);
							} else {
								jQuery.notify(
									jQuery('.notifyAnchor'),
									{
										title: 'Ошибка! Проверьте данные и попробуйте снова!',
									},
									{
										style: 'cartStyle',
										className: 'base',
										showAnimation: 'slideDown',
										showDuration: 300,
										hideAnimation: 'slideUp',
										hideDuration: 200,
										autoHide: true,
										autoHideDelay: 2000,
										gap: 0,
										position: 'bottom left',
										arrowShow: false,
									}
								);
							}
						});
				};

				//Full Search

				const selectCartColorProduct = (productLink, colorSlug) => {
					console.log('click color');
					const href = `${productLink}?color_slug=${colorSlug}`;
					window.location.href = href;
				};

				const toggleSearchFull = () => {
					appMainNav.value.navbar.nav_search.show =
						!appMainNav.value.navbar.nav_search.show;
				};

				//Watch
				Vue.watch(
					() =>
						appMainNav.value.mob.nav_menu.show ||
						appMainNav.value.mob.nav_search.show ||
						appMainNav.value.navbar.modals.account.step !== '',
					(show) => {
						if (show) {
							document.body.style.overflow = 'hidden'; // блокируем прокрутку
							document.body.style.touchAction = 'none'; // предотвращаем скролл на мобильных
						} else {
							document.body.style.overflow = '';
							document.body.style.touchAction = '';
						}
					}
				);

				//
				// Vue.watch(() => (window.scrollY || document.documentElement.scrollTop || window.innerWidth <= 768), (show) => {
				//     console.log(show);
				//     appMainNav.value.mob.nav_menu.logo.show = true;
				// })

				const checkAndUpdateLogo = () => {
					if (
						window.scrollY ||
						document.documentElement.scrollTop ||
						window.innerWidth <= 768
					) {
						const scrollTop =
							window.scrollY || document.documentElement.scrollTop;
						const isMobile = window.innerWidth <= 768;

						if (isMobile) {
							if (scrollTop < 70 && !appMainNav.value.mob.nav_menu.logo.show) {
								appMainNav.value.mob.nav_menu.logo.show = true;
							} else if (
								scrollTop >= 70 &&
								appMainNav.value.mob.nav_menu.logo.show
							) {
								appMainNav.value.mob.nav_menu.logo.show = false;
							}
						} else if (appMainNav.value.mob.nav_menu.logo.show) {
							appMainNav.value.mob.nav_menu.logo.show = false;
						}
					}
				};

				Vue.onMounted(() => {
					setTimeout(() => {
						const menuContainer = document.querySelector('#quadmenu');
						if (!menuContainer) {
							console.warn('#quadmenu not found');
							return;
						}

						const toggle_btns = document.querySelectorAll(
							'.quadmenu-item-has-children .quadmenu-dropdown-toggle'
						);

						console.log(
							'Found toggle buttons:',
							toggle_btns.length,
							toggle_btns
						);

						const initMobileSubMenu = () => {
							let preventNode = null;
							let preventTime = 0;
							let preventTimeout = null;
							const DELAY = 250;

							['click', 'touchstart'].forEach((eventType) => {
								document.addEventListener(
									eventType,
									(event) => {
										console.log('click');
										const menuItem = event.target.closest(
											'.quadmenu-item-has-children'
										);
										if (!menuItem) return;
										event.preventDefault();

										const menuLink = menuItem.querySelector('a');

										const now = Date.now();

										// Если на том же элементе и прошло менее DELAY — не вызываем
										if (
											preventNode === menuItem &&
											now - preventTime <= DELAY
										) {
											clearTimeout(preventTimeout);
											window.location.href = menuLink.href;
											return;
										}

										// Обновляем «последний вызов»
										preventNode = menuItem;
										console.log('Установлен новый preventNode', preventNode);
										preventTime = now;

										// По желанию — ещё и задержка перед реальным открытием
										preventTimeout = setTimeout(() => {
											handleDropdownMenu(event);
										}, DELAY);
									},
									{ passive: false }
								);
							});

							function handleDropdownMenu(event) {
								const DROPDOWN_SELECTORS = [
									'.quadmenu-dropdown-menu',
									'.custom-product-categories',
									'.custom-collections',
								];

								try {
									if (window.innerWidth > 560) return;

									const itemBtn = event.target.closest(
										'.quadmenu-item-has-children'
									);
									if (!itemBtn) return;

									const itemLink = itemBtn.querySelector(
										'a.quadmenu-dropdown-toggle'
									)
										? itemBtn.querySelector('a.quadmenu-dropdown-toggle')
										: itemBtn.querySelector('.widgetMenuHeading');
									if (!itemLink) return;

									const title = itemLink.textContent.trim();

									// document.removeEventListener('click', itemBtn);
									// document.removeEventListener('touchstart', itemBtn);

									event.preventDefault();
									event.stopPropagation();

									const dropdown = DROPDOWN_SELECTORS.map((selector) =>
										itemBtn.querySelector(selector)
									).find((el) => el !== null);

									if (!dropdown) {
										window.location.href = itemLink.href;
										throw new Error('Submenu not found');
									}
									if (!window.states.headMainNav)
										throw new Error('window.states.headMainNav is undefined');

									openSubMenu(dropdown.outerHTML, event, itemLink, title);
									// window.states.headMainNav.openSubMenuTest(
									// 	dropdown.outerHTML,
									// 	event,
									// 	itemLink,
									// 	title
									// );

									// console.log(itemBtn.nextElementSibling);

									//
									// itemBtn.nextElementSibling.remove();
								} catch (err) {
									console.error('В обработчике подменю произошла ошибка');
									console.error(err);
								}
							}
						};

						if (window.innerWidth <= 560) {
							initMobileSubMenu();
							// Наблюдение за изменениями классов у элементов с классом 'quadmenu-item-has-children'
							const observer = new MutationObserver((mutationsList) => {
								mutationsList.forEach((mutation) => {
									if (
										mutation.type === 'attributes' &&
										mutation.attributeName === 'class'
									) {
										const target = mutation.target;

										// Проверка, есть ли нужные классы
										if (
											target.classList.contains('quadmenu-item-has-children') &&
											(target.classList.contains('open') ||
												target.classList.contains('last'))
										) {
											console.log('Добавились классы, удаляем их');
											target.classList.remove('open', 'last');
											console.log(
												'Удалены классы open и last у элемента:',
												target
											);
										}
									}
								});
							});

							// Получаем все элементы для наблюдения
							const targets = document.querySelectorAll(
								'.quadmenu-item-has-children'
							);
							targets.forEach((el) => {
								observer.observe(el, {
									attributes: true,
									attributeFilter: ['class'],
								});
							});
						}

						// ['click', 'touchstart'].forEach((eventType) => {
						// 	document.addEventListener(
						// 		eventType,
						// 		function (e) {
						// 			const itemBtn = e.target.closest(
						// 				'.quadmenu-item-has-children .quadmenu-dropdown-toggle'
						// 			);
						// 			if (window.innerWidth < 560) {
						// 				if (itemBtn) {
						// 					document.removeEventListener('click', itemBtn);
						// 					document.removeEventListener('touchstart', itemBtn);

						// 					console.log(`${eventType} on button:`, itemBtn);
						// 					e.preventDefault();
						// 					e.stopPropagation();
						// 					const dropdown = itemBtn.nextElementSibling;
						// 					if (dropdown) {
						// 						console.log('Submenu:', dropdown);
						// 						if (window.states.headMainNav) {
						// 							window.states.headMainNav.openSubMenu(
						// 								dropdown.outerHTML,
						// 								e,
						// 								itemBtn
						// 							);
						// 						} else {
						// 							console.error(
						// 								'window.states.headMainNav is undefined'
						// 							);
						// 						}

						// 						// console.log(itemBtn.nextElementSibling);

						// 						//
						// 						// itemBtn.nextElementSibling.remove();
						// 					} else {
						// 						console.warn('Submenu not found for:', itemBtn);
						// 					}
						// 				}
						// 			}
						// 		},
						// 		{ passive: false }
						// 	);
						// });

						jQuery('.quadmenu-dropdown-toggle').off(
							'click touchstart mouseenter hover'
						);

						// Lazy loading for banner
						const modalSubMenu = document.querySelector('.modalSubMenu');
						if (modalSubMenu) {
							const observer = new IntersectionObserver(
								(entries) => {
									entries.forEach((entry) => {
										if (entry.isIntersecting) {
											const banner =
												modalSubMenu.querySelector('.bannerMenuWidget');
											if (banner && banner.classList.contains('lazy')) {
												const bgUrl = banner.getAttribute('data-bg');
												if (bgUrl) {
													banner.style.backgroundImage = bgUrl;
													banner.classList.remove('lazy');
												}
											}
										}
									});
								},
								{ threshold: 0.1 }
							);
							observer.observe(modalSubMenu);
						}
					}, 1000);

					appMainNav.value.navbar.user.isAuth = userAuthStatus;

					// console.log(dataCount);
					if (dataCount > 0) {
						appMainNav.value.navbar.cart.count = dataCount;
					}
					//
					fetchCart().then();
					fetchFavorites().then();

					setInterval(() => {
						fetchCart().then();
						fetchFavorites().then();
					}, 24000);

					//
					if (debug) {
						mob.nav_search.show = true;
						appMainNav.value.mob.nav_search.context.queryText = 'му';
						onInputSearchHandler();
						setTimeout(() => {
							appMainNav.value.mob.nav_search.context.queryText = 'фут';
							onInputSearchHandler();
						}, 1200);
						setTimeout(() => {
							appMainNav.value.mob.nav_search.context.queryText = 'ша';
							onInputSearchHandler();
						}, 1800);
						setTimeout(() => {
							appMainNav.value.mob.nav_search.context.queryText = 'лонг';
							onInputSearchHandler();
						}, 2000);
						setTimeout(() => {
							appMainNav.value.mob.nav_search.context.queryText = 'од';
							onInputSearchHandler();
						}, 2500);
					}

					//

					window.addEventListener('scroll', checkAndUpdateLogo);
					window.addEventListener('resize', checkAndUpdateLogo);
					window.addEventListener('DOMContentLoaded', checkAndUpdateLogo);
				});

				return {
					getFavorites,
					toggleMobNav,
					toggleMobSearch,
					onInputSearchHandler,
					//
					viewModalAccount,
					closeModalAccount,
					checkEmailExists,
					doLogin,
					doRegister,
					//
					toggleSearchFull,
					//
					openSubMenu,
					closeSubMenu,
					//
					//
					getSelectedWhtLst,
					addToWhtListMob,
					//
					fetchCart,
					fetchFavorites,
					appMainNav,
					selectCartColorProduct,
				};
			},
		});

		headerComponent.directive('attach-events', {
			mounted(el) {
				console.log('✅✅✅ Монтируем результат поиска');
				prdColorsScrollBarView(el);
			},
			updated(el) {
				console.log('✅✅✅ Результат поиска обновился');
				prdColorsScrollBarView(el);
			},
		});

		window.states.headMainNav = headerComponent.mount(el);
	};

	const viewFilterPc = () => {
		const viewSortApp = Vue.createApp({
			data() {
				return {
					selectedViewType: 'grid',
					viewTypeItems: [
						{ slug: 'compact', name: 'Компактный вид (4 блока)' },
						{ slug: 'grid', name: 'Сеточный вид (3 блока)' },
					],
					isViewTypeOpen: false,
					sortItems: [
						{ name: 'По популярности', slug: 'popular' },
						{ name: 'По возрастанию цены', slug: 'price_asc' },
						{ name: 'По убыванию цены', slug: 'price_desc' },
						{ name: 'По новизне', slug: 'date_desc' },
					],
					selectedSort: '',
					selectedSortLabel: null,
					isOpen: false,
				};
			},
			computed: {
				selectedViewTypeLabel() {
					const selected = this.viewTypeItems.find(
						(item) => item.slug === this.selectedViewType
					);
					return selected ? selected.name : 'Выберите вид';
				},
			},
			methods: {
				toggleViewTypeSelect() {
					this.isViewTypeOpen = !this.isViewTypeOpen;
				},
				selectViewType(type_slug) {
					if (this.selectedViewType === type_slug) return;
					this.selectedViewType = type_slug;
					this.isViewTypeOpen = false;
					this.updateQueryParams();

					// switch (type_slug) {
					// 	case 'grid':
					// 		document.documentElement.style.setProperty(
					// 			'--catalog-raw-count',
					// 			'3'
					// 		);
					// 		break;
					// 	case 'compact':
					// 		document.documentElement.style.setProperty(
					// 			'--catalog-raw-count',
					// 			'4'
					// 		);
					// 		break;
					// 	default:
					// 		document.documentElement.style.setProperty(
					// 			'--catalog-raw-count',
					// 			'4'
					// 		);
					// }
				},
				handleViewTypeBlur() {
					this.isViewTypeOpen = false;
				},
				toggleSelect() {
					this.isOpen = !this.isOpen;
				},
				selectSort(slug, update_flow = true) {
					const sort = this.sortItems.find((item) => item.slug === slug);
					this.selectedSort = slug;
					this.selectedSortLabel = sort ? sort.name : 'Выберите сортировку';
					this.isOpen = false;
					if (update_flow) {
						this.updateQueryParams();
					}
				},
				updateQueryParams() {
					const params = new URLSearchParams(window.location.search);
					if (this.selectedSort) params.set('sortBy', this.selectedSort);
					if (this.selectedViewType) params.set('view', this.selectedViewType);
					window.location.href = '?' + params.toString();
				},
				handleBlur() {
					this.isOpen = false;
				},
				handleKeydown(event) {},
			},
			mounted() {
				const params = new URLSearchParams(window.location.search);
				const sortParam = params.get('sortBy');
				const viewParam = params.get('view');

				// Проверка сортировки
				if (sortParam) {
					this.selectedSort = sortParam;
				} else {
					this.selectedSort = 'date_desc';
				}

				// Проверка типа отображения (grid/list и т.д.)
				if (viewParam) {
					this.selectedViewType = viewParam;
				}

				// Вызов selectSort для применения сортировки
				this.selectSort(this.selectedSort, false);
			},
		}).mount('#viewSortFilter');
	};

	//
	const filterMbFilter = () => {
		const el = document.getElementById('filterMbFilter');
		if (!el) return console.warn('#filterMbFilter не найден!');

		window.states.headMainMbFilter = Vue.createApp({
			setup() {
				const expandedSections = Vue.ref([
					'sortBy',
					'subcategory',
					'categories',
					'promotions',
					'price',
					'sizes',
					'colors',
					'collections',
					'occupation',
					'toggles',
				]);
				const selectedSubcategory = Vue.ref([]);
				const selectedCategories = Vue.ref([]);
				const promotionActive = Vue.ref(false);
				const minPrice = Vue.ref(window.filterData.minPrice);
				const maxPrice = Vue.ref(window.filterData.maxPrice);
				const selectedSize = Vue.ref('');
				const selectedColors = Vue.ref([]);
				const selectedCollections = Vue.ref([]);
				const selectedOccupations = Vue.ref([]);
				const selectedSortBy = Vue.ref([]);
				const onSale = Vue.ref(false);
				const newCollection = Vue.ref(false);
				const trending = Vue.ref(false);
				const sizes = Vue.ref([]);
				const filterData = Vue.ref(window.filterData);

				console.log(filterData);

				const appMainFilter = Vue.ref({
					mob: {
						nav_filter: {
							show: false,
							count: 0,
							filters: null,
						},
					},
				});

				const toggleMobFilter = () => {
					appMainFilter.value.mob.nav_filter.show =
						!appMainFilter.value.mob.nav_filter.show;
					console.log(
						'mob-filter-show',
						appMainFilter.value.mob.nav_filter.show
					);
				};

				const toggleSection = (section) => {
					if (expandedSections.value.includes(section)) {
						expandedSections.value = expandedSections.value.filter(
							(s) => s !== section
						);
					} else {
						expandedSections.value.push(section);
					}
				};

				const fetchProductsWithFilters = (params) => {
					// TODO: реализация
				};

				const updateUrlHistory = (params) => {
					const newUrl = '?' + params.toString();
					window.history.pushState({}, '', newUrl);
					window.location.href = newUrl;
					// fetchProductsWithFilters(params);
				};

				const toggleRadio = (modelName, value) => {
					if (this[modelName] === null) {
						this[modelName] = null;
					}
					//this.applyFilters();
				};

				const applyFilters = () => {
					const params = new URLSearchParams();
					if (selectedSubcategory.value.length)
						params.set('subcategory', selectedSubcategory.value.join(','));
					if (promotionActive.value) params.set('promotion', '1');
					if (minPrice.value > window.filterData.minPrice)
						params.set('min_price', minPrice.value);
					if (maxPrice.value < window.filterData.maxPrice)
						params.set('max_price', maxPrice.value);
					if (selectedSize.value) params.set('size', selectedSize.value);
					if (selectedColors.value.length)
						params.set('color_ex', selectedColors.value.join(','));
					if (selectedCollections.value.length)
						params.set('collection', selectedCollections.value.join(','));
					if (selectedOccupations.value.length)
						params.set('occupation', selectedOccupations.value.join(','));
					if (selectedSortBy.value && selectedSortBy.value.length)
						params.set('sortBy', selectedSortBy.value);
					if (onSale.value) params.set('on_sale', '1');
					if (newCollection.value) params.set('is_new', '1');
					if (trending.value) params.set('is_trending', '1');

					updateUrlHistory(params);
				};

				Vue.watch(
					() => appMainFilter.value.mob.nav_filter.show,
					(show) => {
						if (show) {
							document.body.style.overflow = 'hidden';
							document.body.style.touchAction = 'none';
						} else {
							document.body.style.overflow = '';
							document.body.style.touchAction = '';
						}
					}
				);

				Vue.onMounted(() => {
					const params = new URLSearchParams(window.location.search);
					selectedSubcategory.value = params.get('subcategory')
						? params.get('subcategory').split(',')
						: [];
					promotionActive.value = params.get('promotion') === '1';
					minPrice.value =
						parseFloat(params.get('min_price')) || window.filterData.minPrice;
					maxPrice.value =
						parseFloat(params.get('max_price')) || window.filterData.maxPrice;
					selectedSize.value = params.get('size') || '';
					selectedColors.value = params.get('color_ex')
						? params.get('color_ex').split(',')
						: [];
					selectedCollections.value = params.get('collection')
						? params.get('collection').split(',')
						: [];
					selectedOccupations.value = params.get('occupation')
						? params.get('occupation').split(',')
						: [];
					selectedSortBy.value = params.get('sortBy');
					onSale.value = params.get('on_sale') === '1';
					newCollection.value = params.get('is_new') === '1';
					trending.value = params.get('is_trending') === '1';
				});

				return {
					expandedSections,
					selectedSubcategory,
					promotionActive,
					minPrice,
					maxPrice,
					selectedSize,
					selectedColors,
					selectedCollections,
					selectedOccupations,
					selectedSortBy,
					onSale,
					newCollection,
					trending,
					sizes,
					filterData,
					toggleMobFilter,
					appMainFilter,
					toggleSection,
					applyFilters,
				};
			},
		}).mount(el);
	};

	const cartView = () => {
		const cartView = document.getElementById('cartView');
		if (!cartView) return console.warn('#cartView не найден!');

		// setTimeout(() => {
		window.states.cartView = Vue.createApp({
			setup() {
				// const cart = Vue.ref(null);

				// Получаем параметры из data-атрибутов
				const cartList = cartView.getAttribute('data-cart-list');
				const nonce = cartView.getAttribute('data-nonce');
				const ajaxUrl = cartView.getAttribute('data-ajax-url');

				const appCartStates = Vue.ref({
					cart: {
						qty_item: {}, // Инициализируем как объект
						list: [],
					},
				});

				const appPromocodeStates = Vue.reactive({
					isDisable: false,
					isOpen: false,
					promocode: '',
					message: '',
					discountRaw: false,
					discountFormatted: false,
					isError: false,
					isLoading: false,
				});

				const togglePromocode = () => {
					appPromocodeStates.isOpen = !appPromocodeStates.isOpen;
					if (appPromocodeStates.isOpen && !appPromocodeStates.isDisable) {
						Vue.nextTick(() => {
							document.querySelector('.promocodeBlock__input')?.focus();
						});
					}
				};

				const applyPromocode = async () => {
					if (!appPromocodeStates.promocode) return;

					appPromocodeStates.isLoading = true;
					appPromocodeStates.message = '';
					appPromocodeStates.isError = false;

					try {
						// const nonce = elPromocode.getAttribute("data-nonce");
						// const ajaxUrl = elPromocode.getAttribute("data-ajax-url");

						const response = await axios.post(
							ajaxUrl,
							{
								nonce: nonce,
								coupon_code: appPromocodeStates.promocode,
							},
							{
								params: {
									action: 'apply_cart_coupon',
									coupon_code: appPromocodeStates.promocode,
								},
							}
						);

						if (response.data.success) {
							appPromocodeStates.message =
								response.data.message || 'Промокод успешно применен!';
							appPromocodeStates.promocode = '';

							appPromocodeStates.discountRaw = response.data['discount_raw'];
							appPromocodeStates.discountFormatted =
								response.data['discount_formatted'];

							jQuery.notify(
								jQuery('.notifyAnchor'),
								// 2.1) содержимое: просто передаём текст
								appPromocodeStates.message,
								{
									// 2.2) указываем наш стиль и класс base
									style: 'cartStyleTwo',
									className: 'base',
									// 2.3) появление/скрытие
									showAnimation: 'slideDown',
									showDuration: 300,
									hideAnimation: 'slideUp',
									hideDuration: 200,
									// 2.4) автоскрытие через 2 секунды
									autoHide: true,
									autoHideDelay: 2000,
									// 2.5) позиционирование (можно изменить на top left / bottom right и т.д.)
									globalPosition: 'top center',
									// 2.6) передаём URL картинки, чтобы плагин подставил её в <img>
									icon: null,
								}
							);
						} else {
							appPromocodeStates.message =
								response.data.message || 'Ошибка применения промокода';
							appPromocodeStates.isError = true;

							jQuery.notify(
								jQuery('.notifyAnchor'),
								// 2.1) содержимое: просто передаём текст
								appPromocodeStates.message,
								{
									// 2.2) указываем наш стиль и класс base
									style: 'cartStyleTwo',
									className: 'base',
									// 2.3) появление/скрытие
									showAnimation: 'slideDown',
									showDuration: 300,
									hideAnimation: 'slideUp',
									hideDuration: 200,
									// 2.4) автоскрытие через 2 секунды
									autoHide: true,
									autoHideDelay: 2000,
									// 2.5) позиционирование (можно изменить на top left / bottom right и т.д.)
									globalPosition: 'top center',
									// 2.6) передаём URL картинки, чтобы плагин подставил её в <img>
									icon: null,
								}
							);
						}
					} catch (error) {
						console.error('Promocode application failed:', error);
						appPromocodeStates.message =
							'Произошла ошибка при обработке запроса';
						appPromocodeStates.isError = true;
					} finally {
						appPromocodeStates.isLoading = false;
					}
				};
				//

				const count_qty = (cartItemKey) => {
					appCartStates.value.cart.list = window.cartListData;
					try {
						return appCartStates.value.cart.list[cartItemKey].quantity || 1;
					} catch (e) {
						return 1;
					}
				};

				const decrease_cart = (cartItemKey) => {
					updateQty(cartItemKey, -1);
				};

				const increase_cart = (cartItemKey) => {
					updateQty(cartItemKey, 1);
				};

				const del_prd_cart = (cartItemKey) => {
					updateQty(cartItemKey, 1);
				};

				const updateTotalCount = (count = 0) => {
					try {
						const cartTotalAmount =
							document.querySelectorAll('[data-total-count]');
						cartTotalAmount.forEach((el) => {
							el.innerHTML = count;
						});
					} catch (e) {
						console.error('not total update');
					}
				};

				const updateTotalEditAmount = (amount = 0) => {
					try {
						const cartTotalAmount =
							document.querySelectorAll('#cartTotalAmount');
						cartTotalAmount.forEach((el) => {
							el.innerHTML = amount;
						});
					} catch (e) {
						console.error('not total update');
					}
				};

				const updateQty = (cartItemKey, change) => {
					const current_qty = count_qty(cartItemKey);
					const newQty = current_qty + change;
					if (newQty < 1) {
						//
					}

					axios
						.post(
							ajaxUrl,
							{
								// action: 'update_cart_item_qty',
							},
							{
								params: {
									cart_item_key: cartItemKey,
									qty: newQty,
									action: 'update_cart_item_qty',
								},
							}
						)
						.then((res) => {
							if (res.data.success) {
								appCartStates.value.cart.list[cartItemKey].quantity = newQty;

								// Также можно обновить DOM напрямую
								const qtyElement = document.querySelectorAll(
									`[data-cart-key-id="${cartItemKey}"]`
								);
								if (qtyElement.length > 0) {
									qtyElement.forEach((element) => {
										element.setAttribute('data-cart-qty', newQty);
										element.textContent = newQty;
									});
								}

								updateTotalEditAmount(res.data.data.cart_total_edit);
								updateTotalCount(res.data.data.cart_count_total);
							} else {
								alert('Ошибка при обновлении корзины');
							}
						})
						.catch((err) => {
							console.error('Ошибка запроса:', err);
							alert('Ошибка при обновлении корзины');
						});
				};
				const remove_cart_item = (cartItemKey, change) => {
					axios
						.post(
							ajaxUrl,
							{},
							{
								params: {
									cart_item_key: cartItemKey,
									action: 'cart_item_remove',
								},
							}
						)
						.then((res) => {
							if (res.data.success) {
								// appCartStates.value.cart.qty_item[cartItemKey] = newQty;

								if (res.data.data.action === 'removed') {
									// Также можно обновить DOM напрямую
									const prdElement = document.querySelector(
										`[data-prd-key-id="${cartItemKey}"]`
									);
									console.log(prdElement);
									prdElement.remove();
									delete appCartStates.value.cart.qty_item[cartItemKey];
									window.states.headMainNav.fetchCart();
									// if (appCartStates.value.cart.qty_item === null || appCartStates.value.cart.qty_item === undefined) {
									window.navigation.reload();
									// }
								}
							} else {
								alert('Ошибка при обновлении корзины');
							}
						})
						.catch((err) => {
							console.error('Ошибка запроса:', err);
							alert('Ошибка при обновлении корзины');
						});
				};

				const getSelectedWhtLst = (productId) => {
					try {
						return window.states.headMainNav.getSelectedWhtLst(productId);
					} catch (e) {
						return 0;
					}
				};

				const addToWhtListMob = async (itemCard) => {
					try {
						console.log('click btn');
						const el = event.currentTarget;

						const icon = el.querySelector('.infoCardTopWhlBtnIcon');
						icon.classList.toggle('disable');

						window.states.headMainNav.addToWhtListMob(itemCard);
					} catch (err) {
						console.error(err);
					}
				};

				//

				//
				const getCartInfo = async () => {
					try {
						const cartData = await axios.get('/wp-json/wc/store/cart');
						return cartData.data;
					} catch (err) {
						console.log('При получении данных о корзине произошла ошибка');
						throw err;
					}
				};

				function formatDiscount(coupon) {
					// Сырые данные и настройки валюты из cartData
					const raw = parseFloat(coupon.totals.total_discount); // например "90000" → 90000
					const minorUnit = coupon.totals.currency_minor_unit; // 2
					const decSep = coupon.totals.currency_decimal_separator; // ","
					const thouSep = coupon.totals.currency_thousand_separator; // " "
					const prefix = coupon.totals.currency_prefix; // ""
					const suffix = coupon.totals.currency_suffix; // " ₽"

					// Переводим raw в основную единицу (делим на 10^minorUnit)
					const value = raw / Math.pow(10, minorUnit); // 90000 / 100 = 900

					// Форматируем целую и дробную часть вручную
					const parts = value.toFixed(minorUnit).split('.'); // ["900", "00"]
					// Вставляем тысячи
					parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, thouSep); // "900" → "900" (если было "9000" → "9 000")
					// Собираем с учётом нужного десятичного разделителя
					const numberString = parts.join(decSep); // "900,00"

					return `${prefix}${numberString}${suffix}`; // "900,00 ₽"
				}

				const setDiscount = async () => {
					try {
						appPromocodeStates.isLoading = true;

						const cartData = await getCartInfo();
						const coupon = cartData['coupons'][0];
						if (!coupon) return console.log('Купон еще не введен');

						const code = coupon['code'];
						const discountRaw =
							cartData['coupons']['0']['totals']['total_discount'];
						const discountFormatted = formatDiscount(coupon);

						appPromocodeStates.isDisable = true;
						appPromocodeStates.promocode = code;
						appPromocodeStates.discountRaw = discountRaw;
						appPromocodeStates.discountFormatted = discountFormatted;
					} catch (err) {
						console.error(err);
					} finally {
						appPromocodeStates.isLoading = false;
					}
				};

				Vue.onMounted(() => {
					appCartStates.value.cart.list = window.cartListData;

					console.log(window.cartListData);

					if (
						appCartStates.value.cart.list &&
						appCartStates.value.cart.list.length
					) {
						// Инициализируем qty_item по списку
						const qtyMap = null;

						appCartStates.value.cart.list.forEach((item) => {
							qtyMap[item.cart_item_key] = item.quantity;
						});

						appCartStates.value.cart.qty_item = qtyMap;
					}

					setDiscount();
				});

				return {
					appCartStates,
					getSelectedWhtLst,
					decrease_cart,
					increase_cart,
					remove_cart_item,
					count_qty,
					updateQty,
					//
					appPromocodeStates,
					togglePromocode,
					applyPromocode,
					addToWhtListMob,
				};
			},
		}).mount(cartView);
		// }, 500)
		// cartPromocode();
	};

	const checkoutPcView = () => {
		const el = document.querySelector('#checkoutPc');
		if (!el) return console.warn('#checkoutPc не найден!');

		window.states.checkoutPcView = Vue.createApp({
			setup() {
				const MIN_VISIBLE_WIDTH = '768';

				const appStateCheckout = Vue.reactive({
					isShow: window.innerWidth > MIN_VISIBLE_WIDTH,
				});

				const checkWidth = () => {
					appStateCheckout.isShow = window.innerWidth > MIN_VISIBLE_WIDTH;
				};

				Vue.onMounted(() => {
					window.addEventListener('resize', checkWidth);
				});
				Vue.onUnmounted(() => {
					window.removeEventListener('resize', checkWidth);
				});
				return {
					appStateCheckout,
				};
			},
		}).mount(el);
	};

	const checkoutMbView = () => {
		const el = document.getElementById('appMbCheckout');
		if (!el) return console.warn('#appMbCheckout не найден!');

		window.states.checkoutMbView = Vue.createApp({
			setup() {
				const currentHref = Vue.ref(window.location.href);
				const debug = false;
				const checkoutForm = Vue.ref(null);

				const appStateCheckout = Vue.ref({
					appTitle: 'Получатель заказа',
					isEntity: false,
					isMobile: window.innerWidth <= 768,
					step: {
						selected: 'userinfo',
					},
					totalData: {
						commonInfo: {
							phone: {
								label: 'Номер телефона',
								value: '----',
							},
							email: {
								label: 'Почта',
								value: '----',
							},
						},
						customerInfo: {
							name: {
								label: 'ФИО',
								value: '----',
							},
						},
						entityInfo: {
							companyName: {
								label: 'Название компании',
								value: Vue.computed(() => formData.companyName),
							},
							billingLegalAddress: {
								label: 'Юридический адрес',
								value: Vue.computed(() => formData.billingLegalAddress),
							},
							billingInn: {
								label: 'ИНН',
								value: Vue.computed(() => formData.billingInn),
							},
							billingKpp: {
								label: 'КПП',
								value: Vue.computed(() => formData.billingKpp),
							},
							billingContactPerson: {
								label: 'Контактное лицо',
								value: Vue.computed(() => formData.billingContactPerson),
							},
							billingFax: {
								label: 'Факс',
								value: Vue.computed(() => formData.billingFax),
							},
						},
						deliveryInfo: {
							method: 'Доставка СДЭКом в постамат',
							phone: '+74950090495',
							schedule: 'Пн-Вс с 08:00-22:00',
							term: 'В течение 1 дня',
							cost: 250,
						},
					},
					promocode: {
						isDisable: false,
						isOpen: false,
						promocode: '',
						message: '',
						discountRaw: false,
						discountFormatted: false,
						isError: false,
						isLoading: false,
					},
				});

				const userInfo = Vue.computed(() => {
					const specificInfo = appStateCheckout.value.isEntity
						? appStateCheckout.value.totalData.entityInfo
						: appStateCheckout.value.totalData.customerInfo;

					const commonInfo = appStateCheckout.value.totalData.commonInfo;

					// Фильтруем
					return Object.entries({ ...specificInfo, ...commonInfo })
						.map(([key, { label, value }]) => ({ key, label, value }))
						.filter((item) => item.value != null && item.value !== '');
				});

				// Реактивные данные формы
				const formData = Vue.reactive({
					firstName: '',
					lastName: '',
					middleName: '',
					phone: '',
					email: '',
					companyName: '',
					billingLegalAddress: '',
					billingInn: '',
					billingKpp: '',
					billingContactPerson: '',
					billingFax: '',
					shipMethodId: false,
					paymentMethodId: false,
				});

				const shipMethods = {
					official_cdek: {
						title: 'Доставка СДЭК',
					},
					'yandex-go-delivery': {
						title: 'Яндекс Доставка',
					},
					pickup_location: {
						title: 'Самовывоз из шоу-рума в СПБ',
					},
				};

				const FIELDS = {
					firstName: '#billing_first_name',
					lastName: '#billing_last_name',
					middleName: '#billing_middle_name',
					phone: '#billing_phone',
					email: '#billing_email',
					companyName: '#billing_company_name',
					billingLegalAddress: '#billing_legal_address',
					billingInn: '#billing_inn',
					billingKpp: '#billing_kpp',
					billingContactPerson: '#billing_contact_person',
					billingFax: '#billing_fax',
				};

				const REQUIRED_CLASS = 'validate-required';
				const INVALID_CLASS = 'woocommerce-invalid';

				// Вычисляемое свойство для полного имени
				const fullName = Vue.computed(() => {
					return `${formData.lastName} ${formData.firstName} ${formData.middleName}`.trim();
				});

				// Дебаунс функция
				const debounce = (fn, delay) => {
					let timeoutId;
					return (...args) => {
						clearTimeout(timeoutId);
						timeoutId = setTimeout(() => fn.apply(this, args), delay);
					};
				};

				// Обработчик изменений полей
				const handleFieldChange = (field, value) => {
					formData[field] = value;

					// Обновление данных в appStateCheckout
					if (
						field === 'firstName' ||
						field === 'lastName' ||
						field === 'middleName'
					) {
						appStateCheckout.value.totalData.customerInfo.name.value =
							fullName.value;
					} else if (field === 'phone') {
						appStateCheckout.value.totalData.commonInfo.phone.value = value;
					} else if (field === 'email') {
						appStateCheckout.value.totalData.commonInfo.email.value = value;
					}
				};

				const backBtn = () => {
					const selected_step = appStateCheckout.value.step.selected;
					switch (selected_step) {
						case 'userinfo':
							window.navigation.navigate('/cart');
							break;
						case '':
							appStateCheckout.value.step.selected = 'userinfo';
							break;
						case 'delivery':
							appStateCheckout.value.step.selected = 'userinfo';
							break;
						case 'payment':
							appStateCheckout.value.step.selected = 'delivery';
							break;
					}
				};

				const toggleMobFilter = () => {
					appMainFilter.value.mob.nav_filter.show =
						!appMainFilter.value.mob.nav_filter.show;
					console.log(
						'mob-filter-show',
						appMainFilter.value.mob.nav_filter.show
					);
				};

				const validateRequiredFields = (
					message = 'Введите обязателные поля!'
				) => {
					const currentPage = document.querySelector(
						'.cartPageBodyList.__Selected'
					);
					if (!currentPage) return console.error('Текущая страница не найдена');

					const requiredFields = currentPage.querySelectorAll(
						`.${REQUIRED_CLASS}`
					);

					let isInvalid = false;
					requiredFields.forEach((nodeWrapper) => {
						const input = nodeWrapper.querySelector('input');

						if (input.value == false) {
							nodeWrapper.classList.add(INVALID_CLASS);
							isInvalid = true;
						}
					});

					if (isInvalid) {
						jQuery.notify(jQuery('.notifyAnchor'), message, {
							style: 'cartStyleTwo',
							className: 'info',
							showAnimation: 'slideDown',
							showDuration: 300,
							hideAnimation: 'slideUp',
							hideDuration: 200,
							autoHide: true,
							autoHideDelay: 2000,
							gap: 0,
							position: 'bottom left',
							arrowShow: false,
						});
					}

					return isInvalid;
				};

				const handleInputs = (event) => {
					const wrapper = event.target.closest(`.${REQUIRED_CLASS}`);
					if (!wrapper) return;

					if (event.target.value.length < 1) return;

					wrapper.classList.remove(INVALID_CLASS);
				};

				const initInputValidate = () => {
					const inputs = [
						...document.querySelectorAll(`.${REQUIRED_CLASS}`),
					].map((wrapper) => {
						return wrapper.querySelector('input');
					});

					inputs.forEach((input) => {
						addEventListener('change', handleInputs);
					});
				};

				const selectStepCheckout = (selectStep) => {
					if (validateRequiredFields('Заполните обязательные поля')) return;
					appStateCheckout.value.step.selected = selectStep;
					switch (selectStep) {
						case 'userinfo':
							appStateCheckout.value.appTitle = 'Получатель заказа';
							break;
						case '':
							appStateCheckout.value.appTitle = 'Получатель заказа';
							break;
						case 'delivery':
							appStateCheckout.value.appTitle = 'Способ получения';
							break;
						case 'payment':
							appStateCheckout.value.appTitle = 'Способ оплаты';
							break;
					}
				};

				const getSelectedWhtLst = (productId) =>
					Vue.computed(
						() => !window.states.headMainNav.getFavorites().includes(productId)
					).value;

				const appPromocodeStates = Vue.reactive({
					isDisable: false,
					isOpen: false,
					promocode: '',
					message: '',
					discountRaw: false,
					discountFormatted: false,
					isError: false,
					isLoading: false,
				});

				const togglePromocode = () => {
					appPromocodeStates.isOpen = !appPromocodeStates.isOpen;
					if (appPromocodeStates.isOpen) {
						Vue.nextTick(() => {
							document.querySelector('.promocodeBlock__input')?.focus();
						});
					}
				};

				const applyPromocode = async () => {
					if (!appPromocodeStates.promocode) return;

					appPromocodeStates.isLoading = true;
					appPromocodeStates.message = '';
					appPromocodeStates.isError = false;

					try {
						const ajaxUrl = el.getAttribute('data-ajax_url');
						const nonce = el.getAttribute('data-nonce');

						const response = await axios.post(
							ajaxUrl,
							{
								nonce: nonce,
								coupon_code: appPromocodeStates.promocode,
							},
							{
								params: {
									action: 'apply_cart_coupon',
									coupon_code: appPromocodeStates.promocode,
								},
							}
						);

						if (response.data.success) {
							appPromocodeStates.message =
								response.data.message || 'Промокод успешно применен!';
							appPromocodeStates.promocode = '';

							appPromocodeStates.discountRaw = response.data['discount_raw'];
							appPromocodeStates.discountFormatted =
								response.data['discount_formatted'];

							jQuery.notify(appPromocodeStates.message, {
								style: 'cartStyleTwo',
								className: 'base',
								showAnimation: 'slideDown',
								showDuration: 300,
								hideAnimation: 'slideUp',
								hideDuration: 200,
								autoHide: true,
								autoHideDelay: 2000,
								globalPosition: 'top center',
								icon: null,
							});
						} else {
							appPromocodeStates.message =
								response.data.message || 'Ошибка применения промокода';
							appPromocodeStates.isError = true;

							jQuery.notify(appPromocodeStates.message, {
								style: 'cartStyleTwo',
								className: 'base',
								showAnimation: 'slideDown',
								showDuration: 300,
								hideAnimation: 'slideUp',
								hideDuration: 200,
								autoHide: true,
								autoHideDelay: 2000,
								globalPosition: 'top center',
								icon: null,
							});
						}
					} catch (error) {
						console.error('Promocode application failed:', error);
						appPromocodeStates.message =
							'Произошла ошибка при обработке запроса';
						appPromocodeStates.isError = true;
					} finally {
						appPromocodeStates.isLoading = false;
					}
				};

				const getShippingPrice = (data, rateId) => {
					try {
						const shippingRates = data['shipping_rates'][0]['shipping_rates'];

						const currentShippingRate = shippingRates.find(
							(rateObj) => rateObj['rate_id'] === rateId
						);
						if (!currentShippingRate)
							throw new Error(`Тариф ${rateid} не найден`);

						return (
							currentShippingRate['price'] +
							currentShippingRate['currency_suffix']
						);
					} catch (err) {
						console.log('Произошла ошибка при поиске цены доставки');
						console.error(err);
					}
				};

				const getCartInfo = async () => {
					try {
						const cartData = await axios.get('/wp-json/wc/store/cart');
						return cartData.data;
					} catch (err) {
						console.log('При получении данных о корзине произошла ошибка');
						throw err;
					}
				};

				const setShippingPrice = async (rateId) => {
					try {
						const cartData = await getCartInfo();
						appStateCheckout.value.totalData.deliveryInfo.cost =
							getShippingPrice(cartData, rateId);
					} catch (err) {
						console.log(
							'Произошла ошибка при запросе на получение информации корзины'
						);
						console.error(err);
					}
				};

				const setShipMethod = async (rateId, title) => {
					if (formData.shipMethodId === rateId) return;
					formData.shipMethodId = rateId;

					if (title) {
						appStateCheckout.value.totalData.deliveryInfo.method = title;
					} else if (shipMethods[rateId]?.title) {
						appStateCheckout.value.totalData.deliveryInfo.method =
							shipMethods[rateId].title;
					}

					setShippingPrice(rateId);
				};

				const setPaymentMethod = (paymentId) => {
					if (formData.paymentMethodId === paymentId) return;

					formData.paymentMethodId = paymentId;
				};

				// Инициализ ация обработчиков формы
				const setupFormHandlers = () => {
					const formNode = checkoutForm.value;
					if (!formNode) return console.warn('Form node not found');

					const eventHandlers = {};

					for (const [field, selector] of Object.entries(FIELDS)) {
						const element = formNode.querySelector(selector);
						if (element) {
							const debouncedHandler = debounce((event) => {
								handleFieldChange(field, event.target.value);
							}, 300);
							handleFieldChange(field, element.value);
							eventHandlers[field] = debouncedHandler;
							element.addEventListener('input', eventHandlers[field]);
						}
					}

					const checkboxEntity = document.querySelector(
						'.cartMbWrapper .legal-entity-toggle input'
					);
					if (!checkboxEntity)
						return console.warn('checkboxEntity node not found');

					appStateCheckout.value.isEntity = checkboxEntity.checked;

					checkboxEntity.addEventListener('change', () => {
						appStateCheckout.value.isEntity = event.target.checked;
					});

					return eventHandlers;
				};

				function formatDiscount(coupon) {
					// Сырые данные и настройки валюты из cartData
					const raw = parseFloat(coupon.totals.total_discount); // например "90000" → 90000
					const minorUnit = coupon.totals.currency_minor_unit; // 2
					const decSep = coupon.totals.currency_decimal_separator; // ","
					const thouSep = coupon.totals.currency_thousand_separator; // " "
					const prefix = coupon.totals.currency_prefix; // ""
					const suffix = coupon.totals.currency_suffix; // " ₽"

					// Переводим raw в основную единицу (делим на 10^minorUnit)
					const value = raw / Math.pow(10, minorUnit); // 90000 / 100 = 900

					// Форматируем целую и дробную часть вручную
					const parts = value.toFixed(minorUnit).split('.'); // ["900", "00"]
					// Вставляем тысячи
					parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, thouSep); // "900" → "900" (если было "9000" → "9 000")
					// Собираем с учётом нужного десятичного разделителя
					const numberString = parts.join(decSep); // "900,00"

					return `${prefix}${numberString}${suffix}`; // "900,00 ₽"
				}

				const setDiscount = async () => {
					try {
						appPromocodeStates.isLoading = true;

						const cartData = await getCartInfo();
						const coupon = cartData['coupons'][0];
						if (!coupon) return console.log('Купон еще не введен');

						const code = coupon['code'];
						const discountRaw =
							cartData['coupons']['0']['totals']['total_discount'];
						const discountFormatted = formatDiscount(coupon);

						appPromocodeStates.isDisable = true;
						appPromocodeStates.promocode = code;
						appPromocodeStates.discountRaw = discountRaw;
						appPromocodeStates.discountFormatted = discountFormatted;
					} catch (err) {
						console.error(err);
					} finally {
						appPromocodeStates.isLoading = false;
					}
				};

				const addToWhtListMob = async (itemCard) => {
					try {
						console.log('click btn');
						const el = event.currentTarget;

						const icon = el.querySelector('.infoCardTopWhlBtnIcon');
						icon.classList.toggle('disable');

						window.states.headMainNav.addToWhtListMob(itemCard);
					} catch (err) {
						console.error(err);
					}
				};

				// Следим за изменением шага
				Vue.watch(
					() => appStateCheckout.value.step.selected,
					(newStep) => {
						if (newStep === 'userinfo') {
							// Даем Vue время на перерисовку DOM
							Vue.nextTick(() => {
								setupFormHandlers();
							});
						}
					}
				);

				Vue.watch(
					currentHref,
					(newHref) => {
						if (window.innerWidth < 780) {
							const header = document.querySelector('.headerMain');
							if (!header) return;

							if (newHref.includes('/checkout/')) {
								header.style.visibility = 'hidden';
							} else {
								header.style.visibility = 'visible';
							}
						}
					},
					{ immediate: true }
				);

				const checkMobile = () => {
					appStateCheckout.value.isMobile = window.innerWidth <= 768;
				};

				const billingFieldsWrapper = Vue.ref(null);

				Vue.onMounted(() => {
					initInputValidate();
					setupFormHandlers();
					observer = new MutationObserver((mutations) => {
						console.log('✅✅✅ Врапер для полей изменен');
						console.log('✅✅✅ Новый обработчик');
						setupFormHandlers();
					});
					if (billingFieldsWrapper.value) {
						observer.observe(billingFieldsWrapper.value, {
							childList: true, // добавление/удаление дочерних узлов
							subtree: true, // наблюдение за потомками
						});
					}

					window.addEventListener('resize', checkMobile);

					// Ищем активные методы доставки при загрузке \
					try {
						const hiddenInput = document.querySelector(
							'.shipping-methods-block input[name="shipping_method"]'
						);
						if (!hiddenInput) throw new Error('hidden input not fount');

						const currentShippingMethodId = hiddenInput.value;

						const currentShippingMethodNode = document.querySelector(
							`[data-shipping-method="${currentShippingMethodId}"]`
						);
						if (!currentShippingMethodNode)
							throw new Error(
								'Узел текущего установленного метода доставки не найден'
							);

						const shippingTitle = currentShippingMethodNode.getAttribute(
							'data-shipping-title'
						);
						setShipMethod(currentShippingMethodId, shippingTitle);
					} catch (err) {
						console.log('Ошибка при установке метода доставки при загрузке');
						console.error(err);
					}

					setShippingPrice(formData.shipMethodId);
					setDiscount();

					// Подставляем цену доставки

					// Устанавливаем значения полей подставленные бразуером
					// for (const [key, selector] of Object.entries(FIELDS)) {
					// 	const node = document.querySelector(selector);
					// 	if (!node) continue;

					// 	handleFieldChange(key, node.value);
					// }

					interval = setInterval(() => {
						if (window.location.href !== currentHref.value) {
							currentHref.value = window.location.href;
						}
					}, 500);
				});

				Vue.onBeforeUnmount(() => {
					clearInterval(interval);
				});

				Vue.onUnmounted(() => {
					const formNode = checkoutForm.value;
					if (!formNode) return;

					for (const [field, selector] of Object.entries(FIELDS)) {
						const element = formNode.querySelector(selector);
						if (element && eventHandlers[field]) {
							element.removeEventListener('input', eventHandlers[field]);
						}
					}
				});

				return {
					formData,
					fullName,
					handleFieldChange,
					checkoutForm,
					selectStepCheckout,
					getSelectedWhtLst,
					backBtn,
					appStateCheckout,
					appPromocodeStates,
					togglePromocode,
					applyPromocode,
					setShipMethod,
					setPaymentMethod,
					userInfo,
					billingFieldsWrapper,
					addToWhtListMob,
				};
			},
		}).mount(el);
	};

	const cartMbViewEvent = () => {
		// Проверка пути и ширины экрана
		const isCartPage = window.location.pathname === '/cart/';
		const isMobile = window.innerWidth < 768;

		if (isCartPage && isMobile) {
			const cartMobBottom = document.querySelector('.cartMobBottom');

			if (!cartMobBottom) return;

			// Отслеживание скролла
			window.addEventListener('scroll', function () {
				const scrollTop = window.scrollY || document.documentElement.scrollTop;

				if (scrollTop > 100) {
					// Скрываем блок
					cartMobBottom.style.display = 'none';
				} else {
					// Показываем блок обратно
					cartMobBottom.style.display = '';
				}
			});
		}
	};

	const ctgFilterPc = () => {
		const elAppMainFilters = document.querySelectorAll('#appMainFilter');
		if (!elAppMainFilters) return console.warn('#appMainFilter не найден!');
		elAppMainFilters.forEach((elAppMainFilter) => {
			const appFilter = Vue.createApp({
				data() {
					return {
						expandedSections: [
							'subcategory',
							'categories',
							'promotions',
							'price',
							'sizes',
							'colors',
							'collections',
							'occupation',
							'toggles',
							'vwMatch',
						],
						// selectedCategories: [],
						promotionActive: false,
						minPrice: 1,
						maxPrice: 1,
						selectedSize: '',
						showAllSizes: false,
						//
						selectedColors: [],
						showAllColors: false,
						//
						selectedVwMatch: [],
						selectedCollections: [],
						selectedOccupations: [],
						//
						selectedSubcategory: null,
						openedSubcats: [], // массив slug-ов раскрытых категорий
						showAllSubcat: false,
						//
						selectedNewCollections: null,
						selectedTrendPrd: null,
						onSale: false,
						newCollection: false,
						trending: false,
						sizes: ['XS', 'S', 'M', 'L', 'XL'], // Predefined sizes
						filterData: {
							// colors: []
						},
					};
				},
				computed: {
					displayedCat() {
						if (!this.filterData || !this.filterData.subcategories) return [];
						return this.showAllSubcat
							? this.filterData.subcategories
							: this.filterData.subcategories.slice(0, 6);
					},
					displayedColors() {
						if (!this.filterData || !this.filterData.colors) return [];
						return this.showAllColors
							? this.filterData.colors
							: this.filterData.colors.slice(0, 6);
					},
					displayedSizes() {
						if (!this.filterData || !this.filterData.sizes) return [];
						return this.showAllSizes
							? this.filterData.sizes
							: this.filterData.sizes.slice(0, 6);
					},
				},
				mounted() {
					this.filterData = window.filterData;
					// Initialize from URL parameters
					const params = new URLSearchParams(window.location.search);
					this.selectedSubcategory =
						params.get('subcategory') !== null ? params.get('subcategory') : [];
					// const subcategory = params.get('subcategory') ? params.get('subcategory').split(',') : "";
					// console.log(params.get('subcategory'));
					this.minPrice =
						params.get('min_price') !== null
							? params.get('min_price')
							: this.filterData.minPrice;
					this.maxPrice =
						params.get('max_price') !== null
							? params.get('max_price')
							: this.filterData.maxPrice;

					console.log(
						this.minPrice,
						this.maxPrice,
						params.get('min_price'),
						params.get('max_price'),
						this.filterData.minPrice,
						this.filterData.maxPrice
					);
					//
					this.promotionActive = params.get('promotion') === '1';
					this.selectedSize = params.get('size') || '';
					this.selectedColors = params.get('color_ex')
						? params.get('color_ex').split(',')
						: [];
					this.selectedCollections = params.get('collection')
						? params.get('collection').split(',')
						: [];
					this.selectedOccupations = params.get('occupation')
						? params.get('occupation').split(',')
						: [];
					this.selectedVwMatch = params.get('vwMatch')
						? params.get('vwMatch').split(',')
						: [];
					this.onSale = params.get('on_sale') === '1';
					this.newCollection = params.get('new') === '1';
					this.trending = params.get('trending') === '1';

					//

					setTimeout(() => {
						const sliderEl = document.getElementById('price-slider');
						if (!sliderEl || sliderEl.classList.contains('noUi-target')) return;

						noUiSlider.create(sliderEl, {
							start: [this.minPrice, this.maxPrice],
							connect: true,
							range: {
								min: this.filterData.minPrice,
								max: this.filterData.maxPrice,
							},
							step: 1,
							format: {
								to: (value) => parseInt(value),
								from: (value) => value,
							},
						});

						let debounceTimeout = null;
						let isProgrammaticUpdate = false; // Флаг для программных изменений

						sliderEl.noUiSlider.on('update', (values) => {
							const newMin = parseInt(values[0]);
							const newMax = parseInt(values[1]);

							if (isProgrammaticUpdate) {
								isProgrammaticUpdate = false; // Сбрасываем флаг
								return;
							}

							if (this.minPrice == newMin && this.maxPrice == newMax) {
								return;
							}

							this.minPrice = newMin;
							this.maxPrice = newMax;

							if (debounceTimeout) {
								clearTimeout(debounceTimeout);
							}

							debounceTimeout = setTimeout(() => {
								if (this.minPrice !== newMin || this.maxPrice !== newMax) {
									return;
								}
								console.log(
									'applyFilters executed with:',
									this.minPrice,
									this.maxPrice
								);
								this.applyFilters();
							}, 1000);
						});

						// Если applyFilters или другой код обновляет слайдер
						this.updateSlider = function (min, max) {
							isProgrammaticUpdate = true; // Устанавливаем флаг
							sliderEl.noUiSlider.set([min, max]);
						};
					}, 400);

					// console.log(this.filterData);
				},
				methods: {
					toggleAccordion(slug) {
						const idx = this.openedSubcats.indexOf(slug);

						// if(!this.openedSubcats.includes(slug) && !this.subCatItem.children?.length){
						// 	this.toggleRadio('selectedSubcategory', slug)
						// }

						if (idx === -1) {
							// открыть
							this.openedSubcats.push(slug);
						} else {
							// закрыть
							this.openedSubcats.splice(idx, 1);
						}
					},
					toggleShowAllSubcat() {
						this.showAllSubcat = !this.showAllSubcat;
					},
					toggleShowAllColors() {
						this.showAllColors = !this.showAllColors;
					},
					toggleShowAllSubCat() {
						this.showAllSubcat = !this.showAllSubcat;
					},
					toggleShowAllSizes() {
						this.showAllSizes = !this.showAllSizes;
					},
					toggleSection(section) {
						this.expandedSections = this.expandedSections.includes(section)
							? this.expandedSections.filter((s) => s !== section)
							: [...this.expandedSections, section];
					},
					toggleRadio(modelName, value) {
						const currentValue = this[modelName];

						console.log(
							'[toggleRadio] field:',
							modelName,
							'current:',
							currentValue,
							'clicked:',
							value
						);

						if (currentValue === value) {
							// Повторный клик — сброс
							this[modelName] = null;
							console.log(`[toggleRadio] ${modelName} reset to null`);
						} else {
							// Новое значение — записать
							this[modelName] = value;
							console.log(`[toggleRadio] ${modelName} changed to`, value);
						}

						// applyFilters вызывается только если произошли изменения
						if (currentValue !== this[modelName]) {
							this.applyFilters();
						}
					},
					applyFilters() {
						const params = new URLSearchParams();
						if (this.selectedSubcategory)
							params.set('subcategory', this.selectedSubcategory);
						console.log(this.selectedSubcategory);
						if (this.promotionActive) params.set('promotion', '1');
						if (this.minPrice > window.filterData.minPrice)
							params.set('min_price', this.minPrice);
						if (this.maxPrice < window.filterData.maxPrice)
							params.set('max_price', this.maxPrice);
						if (this.selectedSize) params.set('size', this.selectedSize);
						if (this.selectedColors.length)
							params.set('color_ex', this.selectedColors.join(','));
						if (this.selectedCollections.length)
							params.set('collection', this.selectedCollections.join(','));
						if (this.selectedOccupations.length)
							params.set('occupation', this.selectedOccupations.join(','));
						if (this.selectedVwMatch.length)
							params.set('vwMatch', this.selectedVwMatch.join(','));
						if (this.onSale) params.set('on_sale', '1');
						if (this.newCollection) params.set('new', '1');
						if (this.trending) params.set('trending', '1');
						window.location.href = '?' + params.toString();
					},
				},
			}).mount(elAppMainFilter);
		});
	};

	const popularCatCards = () => {
		const elAppPopularCat = document.querySelector('#popularCat');
		if (!elAppPopularCat) return;

		const appFilter = Vue.createApp({
			setup() {
				const popularCatSel = Vue.ref('woman');
				return {
					popularCatSel,
				};
			},
		}).mount(elAppPopularCat);
	};

	const footerSubscribeForm = () => {
		const footerSubscribeForm = document.querySelector('#footerSubscribeForm');
		if (!footerSubscribeForm) return;

		const appFooterSubscriveForm = Vue.createApp({
			setup() {
				const subscribeFormStates = Vue.ref({
					fields: {
						email: '',
					},
				});

				const isEmailValid = Vue.computed(() => {
					const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
					const email = subscribeFormStates.value?.fields?.email || '';
					return emailPattern.test(email);
				});

				const getValid = () => {
					try {
						return !isEmailValid.value;
					} catch (e) {
						return false;
					}
				};

				const subscribeSubmitForm = () => {
					//TODO: [handle wp method]
					const subscriptionKey = 'isSubscribed';
					const isSubscribed = localStorage.getItem(subscriptionKey);
					if (isSubscribed) {
						// Пользователь уже подписан
						jQuery.notify(
							jQuery('.notifyAnchor'),
							'ℹ️ Вы уже подписаны на рассылку!',
							{
								style: 'cartStyleTwo',
								className: 'info',
								showAnimation: 'slideDown',
								showDuration: 300,
								hideAnimation: 'slideUp',
								hideDuration: 200,
								autoHide: true,
								autoHideDelay: 2000,
								gap: 0,
								position: 'bottom left',
								arrowShow: false,
							}
						);
						return;
					}

					jQuery.notify(
						jQuery('.notifyAnchor'),
						'🥳 Поздравляем! Вы успешно подписались на рассылку!',
						{
							style: 'cartStyleTwo',
							className: 'base',
							showAnimation: 'slideDown',
							showDuration: 300,
							hideAnimation: 'slideUp',
							hideDuration: 200,
							autoHide: true,
							autoHideDelay: 2000,
							gap: 0,
							position: 'bottom left',
							arrowShow: false,
						}
					);
					localStorage.setItem(subscriptionKey, 'true');
				};

				return {
					subscribeSubmitForm,
					subscribeFormStates,
					getValid,
				};
			},
		}).mount(footerSubscribeForm);
	};

	const accountHelperApp = () => {
		const accountHelperSection = document.querySelector('.help-section');
		if (!accountHelperSection) return;

		window.states.accountHelperSection = Vue.createApp({
			setup() {
				const search = Vue.reactive({
					input: '',
					debouncedInput: '',
					category: 0,
				});

				const categories = Vue.ref([]);
				const faqItems = Vue.ref([]);
				const loading = Vue.ref(false);

				let debounceTimeout = null;

				// Получение категорий FAQ
				const fetchCategories = () => {
					fetch('/wp-json/wp/v2/faq_category?per_page=100')
						.then((res) => res.json())
						.then((data) => {
							categories.value = data.map((cat) => ({
								term_id: cat.id,
								name: cat.name,
							}));
						});
				};

				// Получение самих FAQ
				const fetchFaqItems = () => {
					loading.value = true;
					const body = new URLSearchParams({
						action: 'get_faq_items',
						search: search.debouncedInput,
						category: search.category,
					});

					fetch('/wp-admin/admin-ajax.php', {
						method: 'POST',
						headers: {
							'Content-Type': 'application/x-www-form-urlencoded',
						},
						body,
					})
						.then((res) => res.json())
						.then((data) => {
							faqItems.value = data.success ? data.data : [];
							loading.value = false;
						});
				};

				// Debounce по вводу в поле
				Vue.watch(
					() => search.input,
					(newVal) => {
						clearTimeout(debounceTimeout);
						debounceTimeout = setTimeout(() => {
							search.debouncedInput = newVal;
						}, 400); // задержка 400 мс
					}
				);

				// Watch на debounced input
				Vue.watch(
					() => search.debouncedInput,
					() => {
						fetchFaqItems();
					}
				);

				// Watch на смену категории
				Vue.watch(
					() => search.category,
					() => {
						fetchFaqItems();
					}
				);

				Vue.onMounted(() => {
					fetchCategories();
					fetchFaqItems();
				});

				return {
					search,
					categories,
					faqItems,
					loading,
				};
			},
		}).mount(accountHelperSection);
	};

	const prdModal = () => {
		const el = document.getElementById('prdModal');
		if (!el) return console.warn('#prdModal не найден!');

		window.states.prdModal = Vue.createApp({
			setup() {
				/**
				 * TTL (Time To Live) для записей в кэше, в миллисекундах.
				 * 5 * 60 * 1000 = 5 минут.
				 */
				const TTL = 10 * 60 * 1000;

				/**
				 * Префикс для ключей в localStorage, чтобы избежать конфликтов.
				 */
				const LOCAL_STORAGE_PREFIX = 'prd_';

				const FAVORITES_KEY = 'favorite_products';

				// === ВСПОМОГАТЕЛЬНЫЕ ФУНКЦИИ ===
				const getlsKey = (id) => {
					return `${LOCAL_STORAGE_PREFIX}${id}`;
				};

				const jsonParse = (raw, isEntries = false) => {
					let obj = JSON.parse(raw);
					if (typeof obj !== 'object' || obj === null)
						throw new Error('cache is not a object');

					// Возращаем пары значений
					if (isEntries) return Object.entries(obj);

					// Возращам объект
					return obj;
				};

				const getLocalStorageCache = (id, isEntries) => {
					const lsKey = getlsKey(id);
					// Поулчаем строку json
					const raw = localStorage.getItem(lsKey);
					if (!raw) return;

					const cache = jsonParse(raw);

					// Если кэш просрочен
					if (Date.now() - cache.ts > 5000) {
						console.warn(`Кэш продукта: ${id} просрочен`);
					}

					if (isEntries) return Object.entries(cache);
					return cache;
				};

				// === ПЕРЕМЕННЫЕ ===
				let isShow = Vue.ref(false);
				let isLoad = Vue.ref(false);
				let isError = Vue.ref(false);
				let isDesktop = Vue.ref(window.innerWidth > 560);
				let isMobile = Vue.computed(() => !isDesktop.value);

				let prdItemKey = Vue.ref(null);

				window.addEventListener('resize', () => {
					isDesktop.value = window.innerWidth > 560;
				});

				const api = {
					actions: {
						fetchPrd: el.dataset.actionFetchPrd,
						addToCart: el.dataset.actionAddToCart,
						updatePrd: el.dataset.actionUpdatePrd,
					},
					nonces: {
						fetchPrd: el.dataset.nonceFetchPrd,
						addToCart: el.dataset.nonceAddToCart,
						updatePrd: el.dataset.nonceUpdatePrd,
					},
					ajax_url: el.dataset.ajax_url,
				};

				const data = Vue.reactive({
					prdId: null,
					prdName: null,
					prdDescription: null,
					prdComposition: null,
					prdDelivery: null,
					prdColors: null,
					prdSlides: null,
					prdSizes: null,
					prdDefaultColor: null,
					prdThumbnail: null,
					prdPriceFull: null,
					prdPriceSale: null,
					prdIsStock: null,
				});

				const MODAL_CONTENT_MAP = {
					idea: {
						title: 'Идея для образа',
						btn: Vue.computed(() =>
							data.prdIsStock ? 'Добавить в корзину' : 'Товара нет в наличии'
						),
					},
					update: {
						title: 'Изменить',
						btn: 'Изменить товар',
					},
				};

				const currentModalType = el.dataset.modalType || 'idea';

				const content = Vue.computed(() => MODAL_CONTENT_MAP[currentModalType]);

				const appShop = Vue.ref({
					select: {
						color: Vue.computed({
							get: () => data.prdDefaultColor,
							set: (value) => (data.prdDefaultColor = value),
						}),

						colorId: Vue.computed(() => {
							const found = data.prdColors.find((color) => {
								return color.color_slug === data.prdDefaultColor;
							});

							if (found) return found.color_id;
							return undefined;
						}),

						size: Vue.computed({
							get: () => window.states.productSize.size,
							set: (value) => (window.states.productSize.size = value),
						}),
					},
					mob: {
						size: {
							show: false,
						},
					},
					cart: {
						btnAddActive: false,
					},
				});

				// TODO: Бля надо сделать так, чтоб загружалась сразу это хуйня. Для этого надо поменять логику сохранения в localStorage или нет ?
				const productCache = new Map();

				// === ОСНОВНЫЕ ФУНКЦИИ ===
				const showModal = async (id, itemKey = false) => {
					prdItemKey.value = itemKey;

					if (!id) return;
					isShow.value = true;

					// Если id текущего и предыдущего товара совпадают
					if (id == data.prdId) {
						return;
					}

					// Берем кэш с map
					if (productCache.has(getlsKey(id))) {
						Object.assign(data, productCache.get(getlsKey(id)).data);

						isShow.value = true;
						return;
					}

					const lsp = localStorage.getItem(getlsKey(id));
					if (lsp) {
						Object.assign(data, jsonParse(lsp).data);
						return;
					}

					const dataProduct = await fetchProduct(id);

					saveCache(id, dataProduct);
					Object.assign(data, dataProduct);
					// for (const key in dataProduct) {
					// 	if (
					// 		typeof data[key] === 'object' &&
					// 		data[key] !== null &&
					// 		dataProduct[key] &&
					// 		typeof dataProduct[key] === 'object'
					// 	) {
					// 		Object.assign(data[key], dataProduct[key]);
					// 	} else {
					// 		data[key] = dataProduct[key];
					// 	}
					// }
				};

				const fetchProduct = async (id) => {
					isLoad.value = true;

					try {
						const respones = await axios.post(
							api.ajax_url,
							{
								nonce: api.nonces.fetchPrd,
								action: api.actions.fetchPrd,
								id,
							},
							{
								headers: {
									'Content-Type': 'application/x-www-form-urlencoded',
								},
								transformRequest: [
									(data) => {
										// преобразуем объект в строку вида "a=1&b=2"
										return Object.entries(data)
											.map(
												([k, v]) =>
													`${encodeURIComponent(k)}=${encodeURIComponent(v)}`
											)
											.join('&');
									},
								],
							}
						);

						return respones.data.data;
					} catch (error) {
						isError.value = true;

						if (error.respones) {
							console.log('Сервер ответил ошибкой:');
							console.log(error.message);
							console.log(error.response.status);
							console.log(error.response.data);
						} else if (error.request) {
							console.log('Запрос отправлен, но нет ответа:', error.message);
							console.log(error);
						} else {
							console.log('Ошибка при настройке запроса:', error.message);
							console.log(error);
						}

						// TODO: Вывести сообщение ошибки в фронт
					} finally {
						isLoad.value = false;
					}
				};

				const saveCache = (id, dataPrd) => {
					// const lsKey = getlsKey(id);
					// const cacheObj = {
					// 	data: dataPrd,
					// 	ts: Date.now(),
					// };
					// productCache.set(lsKey, cacheObj);
					// const raw = JSON.stringify(cacheObj);
					// localStorage.setItem(lsKey, raw);
				};

				const closeModal = () => {
					console.log('test');
					isShow.value = false;
				};

				const updatePrd = async () => {
					isLoad.value = true;
					try {
						if (!prdItemKey.value) throw new Error('item key is not found');

						const respones = await axios.post(
							api.ajax_url,
							{
								nonce: api.nonces.updatePrd,
								action: api.actions.updatePrd,
								cartItemKey: prdItemKey.value,
								prdSize: appShop.value.select.size,
								prdColor: appShop.value.select.colorId,
							},
							{
								headers: {
									'Content-Type': 'application/x-www-form-urlencoded',
								},
								transformRequest: [
									(data) => {
										// преобразуем объект в строку вида "a=1&b=2"
										return Object.entries(data)
											.map(
												([k, v]) =>
													`${encodeURIComponent(k)}=${encodeURIComponent(v)}`
											)
											.join('&');
									},
								],
							}
						);

						console.log('Товар изменен');
						location.reload();
					} catch (error) {
						isLoad.value = 'false';
						isError.value = true;

						if (error.respones) {
							console.log('Сервер ответил ошибкой:');
							console.log(error.message);
							console.log(error.response.status);
							console.log(error.response.data);
						} else if (error.request) {
							console.log('Запрос отправлен, но нет ответа:', error.message);
							console.log(error);
						} else {
							console.log('Ошибка при настройке запроса:', error.message);
							console.log(error);
						}

						// TODO: Вывести сообщение ошибки в фронт
					}
				};

				// Slider
				const appShopDetailCardSlider = Vue.reactive({
					allImages: Vue.computed(() => data.prdSlides),
					currentColor: Vue.computed({
						get: () => data.prdDefaultColor,
						set: (val) => (data.prdDefaultColor = val),
					}),
					currentSlides: Vue.computed(
						() => data.prdSlides?.[appShopDetailCardSlider.currentColor] || []
					),
					currentIndex: 0,
					currentImage: Vue.computed(() => {
						const slides = appShopDetailCardSlider.currentSlides;
						const index = appShopDetailCardSlider.currentIndex;
						return slides[index] || '';
					}),
				});

				function getFavorites() {
					try {
						return JSON.parse(localStorage.getItem(FAVORITES_KEY)) || [];
					} catch (e) {
						return [];
					}
				}

				const appFavoriteBtn = Vue.ref({
					status: {
						active: false,
					},
				});

				Vue.watch(
					() => data.prdId,
					(newData) => {
						appFavoriteBtn.value.status.active =
							getFavorites().includes(newData);
					}
				);

				const mobileSlider = Vue.ref({
					currentIndex: 0,
					swiper: null,
				});

				const initMobileSlider = () => {
					Vue.nextTick(() => {
						// Уничтожаем старый слайдер если есть
						let inited = false;

						if (mobileSlider.value.swiper) {
							mobileSlider.value.swiper.off('slideChange');
							mobileSlider.value.swiper.destroy(true, true);
							mobileSlider.value.swiper = null;
						}

						const mobileSliderEl = el.querySelector('#productMobileSlider');
						if (!mobileSliderEl)
							throw new Error('Мобильный слайдер в модалке не найден');

						const slides = mobileSliderEl.querySelectorAll('.swiper-slide');
						if (slides.length === 0) return;

						// Создаем новый Swiper для мобильного слайдера
						mobileSlider.value.swiper = new Swiper(mobileSliderEl, {
							direction: 'horizontal',
							loop: slides.length > 1,
							slidesPerView: 'auto',
							spaceBetween: 0,
							pagination: {
								el: '.bottomPaginate',
								clickable: true,
								renderBullet: function (index, className) {
									return `<span class="${className}"></span>`;
								},
							},
							on: {
								init() {
									this.realIndex = 0;
									const realIndex = this.realIndex;
									appShopDetailCardSlider.currentIndex = realIndex;
								},
								slideChange() {
									// НЕ выводим ничего до init
									if (!inited) return;

									console.log('--- slideChange: ', this.realIndex);
									const realIndex = this.realIndex;
									mobileSlider.value.currentIndex = realIndex;
									appShopDetailCardSlider.currentIndex = realIndex;
									scrollToActiveThumbnail(realIndex);
								},
							},
						});
					});
				};

				// Обновление мобильного слайдера
				const updateMobileSlider = () => {
					if (mobileSlider.value.swiper) {
						mobileSlider.value.swiper.update();
						mobileSlider.value.swiper.slideTo(0, 0); // Переходим к первому слайду
					}
				};

				const updateSlidesByColor = (colorSlug) => {
					console.log('Обновление слайдов для цвета:', colorSlug);

					const colorImages = data.prdSlides[colorSlug];
					if (colorImages && colorImages.length > 0) {
						appShopDetailCardSlider.currentColor = colorSlug;
						appShopDetailCardSlider.currentIndex = 0; // Сбрасываем на первое изображение

						// ИСПРАВЛЕНИЕ: Пересоздаем мобильный слайдер с новыми изображениями
						Vue.nextTick(() => {
							initMobileSlider();
						});

						// Прокручиваем миниатюры к началу
						const wrapper = el.querySelector('.cardImagesThumbWrapper');
						if (wrapper) {
							wrapper.scrollTo({ top: 0, behavior: 'smooth' });
						}
					} else {
						console.warn(`Изображения для цвета ${colorSlug} не найдены`);
					}
				};

				// Функция для автоматической прокрутки к активной миниатюре
				const scrollToActiveThumbnail = (index) => {
					const wrapper = el.querySelector('.cardImagesThumbWrapper');
					if (!wrapper) return;

					const itemHeight = 140; // Высота одной миниатюры

					// Всегда прокручиваем так, чтобы активный элемент был первым
					const scrollPosition = index * itemHeight;

					wrapper.scrollTo({
						top: scrollPosition,
						behavior: 'smooth',
					});
				};

				const nextSlide = () => {
					const maxIndex = appShopDetailCardSlider.currentSlides.length - 1;
					if (appShopDetailCardSlider.currentIndex < maxIndex) {
						appShopDetailCardSlider.currentIndex++;
					} else {
						appShopDetailCardSlider.currentIndex = 0; // Зацикливание
					}

					// Автопрокрутка миниатюр
					scrollToActiveThumbnail(appShopDetailCardSlider.currentIndex);

					// Синхронизируем мобильный слайдер
					if (mobileSlider.value.swiper) {
						mobileSlider.value.swiper.slideTo(
							appShopDetailCardSlider.currentIndex
						);
					}
				};
				const prevSlide = () => {
					const maxIndex = appShopDetailCardSlider.currentSlides.length - 1;
					if (appShopDetailCardSlider.currentIndex > 0) {
						appShopDetailCardSlider.currentIndex--;
					} else {
						appShopDetailCardSlider.currentIndex = maxIndex; // Зацикливание
					}

					// Автопрокрутка миниатюр
					scrollToActiveThumbnail(appShopDetailCardSlider.currentIndex);

					// Синхронизируем мобильный слайдер
					if (mobileSlider.value.swiper) {
						mobileSlider.value.swiper.slideTo(
							appShopDetailCardSlider.currentIndex
						);
					}
				};

				const selectSlideByIndex = (index) => {
					if (
						index >= 0 &&
						index < appShopDetailCardSlider.currentSlides.length
					) {
						appShopDetailCardSlider.currentIndex = index;

						// Автопрокрутка миниатюр
						scrollToActiveThumbnail(index);

						// Синхронизируем мобильный слайдер
						if (mobileSlider.value.swiper) {
							mobileSlider.value.swiper.slideTo(index);
						}
					}
				};

				const getSelectedWhtLst = (productId) =>
					Vue.computed(() => !getFavorites().includes(productId)).value;

				const addToWhtListMob = (itemCard) => {
					console.log('add to favorite');
					appFavoriteBtn.value.status.active = !getFavorites().includes(
						Vue.computed(() => data.prdId).value
					);

					console.log(appFavoriteBtn.value.status.active);

					if (window.states && window.states.headMainNav) {
						window.states.headMainNav.addToWhtListMob({
							...{ imageUrl: data.prdThumbnail },
							...{ productId: Vue.computed(() => data.prdId).value },
						});
					}
				};

				Vue.watch(
					() => appShopDetailCardSlider.currentIndex,
					(newIndex) => {
						// Ждём, пока DOM обновит класс __Active
						Vue.nextTick(() => {
							scrollToActiveThumbnail(newIndex);
						});
					}
				);

				// INFO
				const selectCartColor = (color, colorId) => {
					console.log('Выбран цвет:', color);
					appShop.value.select.color = color;
					appShop.value.select.colorId = colorId;

					// Проверяем, является ли цвет "none"
					const isNoneColor = color && color.toLowerCase() === 'none';

					// Уведомляем компонент слайдера о смене цвета
					updateSlidesByColor(color);
				};

				const selectCartSize = (size) => {
					console.log('Выбран размер:', size);
					appShop.value.select.size = size;
				};

				const isAddToCartDisabled = Vue.computed(() => {
					const selectedColor = appShop.value?.select?.color;
					return selectedColor && selectedColor.toLowerCase() === 'none';
				});

				const addToCartButtonText = Vue.computed(() => {
					return isAddToCartDisabled.value
						? 'Выберите цвет'
						: 'Добавить в корзину';
				});

				const updateCartButton = () => {
					console.log('update button');
					// ИСПРАВЛЕННЫЙ СЕЛЕКТОР - ищем именно мобильную кнопку в правильном контейнере
					// const button = document.querySelector(
					// 	'#singlePrdMob .addToCartBtn'
					// );
					// const buttonTitle = document.querySelector(
					// 	'#singlePrdMob .addToCartBtn .addToCartBtnTitle'
					// );

					// if (button && buttonTitle) {
					// 	if (isAddToCartDisabled.value) {
					// 		// Блокируем кнопку
					// 		button.style.opacity = '0.5';
					// 		button.style.pointerEvents = 'none';
					// 		button.style.backgroundColor = '#f3f4f6';
					// 		button.style.color = '#9ca3af';

					// 		// Меняем только текст, сохраняем цену
					// 		const priceSpan = buttonTitle.querySelector(
					// 			'.woocommerce-Price-amount'
					// 		);
					// 		const priceHtml = priceSpan ? priceSpan.outerHTML : '';
					// 		buttonTitle.innerHTML = `<span>Выберите цвет</span> ${priceHtml}`;
					// 	} else {
					// 		// Разблокируем кнопку
					// 		button.style.opacity = '1';
					// 		button.style.pointerEvents = 'auto';
					// 		button.style.backgroundColor = '';
					// 		button.style.color = '';

					// 		// Восстанавливаем обычный текст
					// 		const priceSpan = buttonTitle.querySelector(
					// 			'.woocommerce-Price-amount'
					// 		);
					// 		const priceHtml = priceSpan ? priceSpan.outerHTML : '';
					// 		const buttonText = appMobShop.value.cart.btnAddActive
					// 			? 'Перейти в корзину'
					// 			: 'Добавить в корзину';
					// 		buttonTitle.innerHTML = `<span>${buttonText}</span> ${priceHtml}`;
					// 	}
					// }
				};

				const addToCartBtn = (event, productItem) => {
					// Проверяем, не заблокирована ли кнопка
					if (isAddToCartDisabled.value) {
						console.log('Кнопка заблокирована - выбран цвет "none"');
						return;
					}

					if (!appShop.value.cart.btnAddActive) {
						appShop.value.cart.btnAddActive = true;
						showAddToCartNotification(productItem);
						// Обновляем кнопку после изменения состояния
						updateCartButton();
					} else {
						window.location.href = '/cart';
					}
				};

				const btnHandler = () => {
					switch (currentModalType) {
						case 'idea':
							addToCartBtn(...args);
						case 'update':
							updatePrd();
					}
				};

				function showAddToCartNotification(cardItem) {
					let imageUrl = cardItem.imageUrl ? cardItem.imageUrl : '';
					console.log(`Товар ${data.prdId} добавлен в корзину!`);

					// Отправляем AJAX-запрос через axios
					axios
						.post(
							api.ajax_url,
							{
								nonce: api.nonces.addToCart,
							},
							{
								params: {
									action: api.actions.addToCart,
									product_id: data.prdId,
									productQuanity: 1,
									productSize: appShop.value.select.size || false,
									productColor: appShop.value.select.colorId || false,
								},
							}
						)
						.then(function (response) {
							if (response.data.success) {
								const action = response.data.data.action;
								if (action === 'added') {
									jQuery.notify(
										jQuery('.notifyAnchor'),
										{
											title: 'Модель добавлена в корзину!',
											image: `<img src="${imageUrl}" alt="Карточка товара" />`,
										},
										{
											style: 'cartStyle',
											className: 'base',
											showAnimation: 'slideDown',
											showDuration: 300,
											hideAnimation: 'slideUp',
											hideDuration: 200,
											autoHide: true,
											autoHideDelay: 2000,
											gap: 0,
											position: 'bottom left',
											arrowShow: false,
										}
									);
								} else {
									jQuery.notify(
										jQuery('.notifyAnchor'),
										{
											title: 'Модель удалена из корзины!',
											image: `<img src="${imageUrl}" alt="Карточка товара" />`,
										},
										{
											style: 'cartStyle',
											className: 'base',
											showAnimation: 'slideDown',
											showDuration: 300,
											hideAnimation: 'slideUp',
											hideDuration: 200,
											autoHide: true,
											autoHideDelay: 2000,
											gap: 0,
											position: 'bottom left',
											arrowShow: false,
										}
									);
								}
							} else {
								console.log('Произошла ошибка');
								jQuery.notify(
									jQuery('.notifyAnchor'),
									{
										title: response.data.data.message,
										image: `<img src="${imageUrl}" alt="Карточка товара" />`,
									},
									{
										style: 'cartStyle',
										className: 'base',
										showAnimation: 'slideDown',
										showDuration: 300,
										hideAnimation: 'slideUp',
										hideDuration: 200,
										autoHide: true,
										autoHideDelay: 2000,
										gap: 0,
										position: 'bottom left',
										arrowShow: false,
									}
								);
							}

							window.states.headMainNav.fetchCart();
						})
						.catch(function (error) {
							console.error(error);
							console.log('Ошибка запроса');
						});
				}

				const toggleMobSizePanel = () => {
					appShop.value.mob.size.show = !appShop.value.mob.size.show;
				};

				Vue.watch(data, () => {
					console.log('data change init mobile slider');

					initMobileSlider();
					scrollToActiveThumbnail(appShopDetailCardSlider.currentIndex);
				});

				Vue.onMounted(() => {
					// Инициализируем с дефолтным цветом
					if (data.prdDefaultColor && data.prdSlides[data.prdDefaultColor]) {
						updateSlidesByColor(data.prdDefaultColor);
					}

					// Инициализируем мобильный слайдер
					setTimeout(() => {
						initMobileSlider();
						scrollToActiveThumbnail(appShopDetailCardSlider.currentIndex);
					}, 100);
				});

				return {
					data,
					isShow,
					isError,
					isLoad,
					isDesktop,
					isMobile,
					showModal,
					closeModal,
					appShopDetailCardSlider,
					prevSlide,
					nextSlide,
					selectSlideByIndex,
					addToWhtListMob,
					appFavoriteBtn,
					getSelectedWhtLst,
					appShop,
					selectCartColor,
					selectCartSize,
					addToCartButtonText,
					isAddToCartDisabled,
					addToCartBtn,
					content,
					toggleMobSizePanel,
					currentModalType,
					btnHandler,
				};
			},
		}).mount(el);
	};

	// const addFavorite
	window.states = Vue.reactive({
		...window.states,
	});
	// Запуск всех компонентов
	checkoutPcView();
	singlePrdCard();
	singlePrdMob();
	headMainNav();
	popularCatCards();
	filterMbFilter();
	viewFilterPc();
	shopProductDetailCardImages();
	// cartPromocode();
	cartView();
	cartMbViewEvent();
	checkoutMbView();
	favBtnPrd();
	ctgFilterPc();
	//
	footerSubscribeForm();
	prdModal();
	accountHelperApp();
};

// const uiMenuVibe = () => {
//
//     SparkVibe.registerStore("appMenu", {
//         mobBurger: {
//             show: false,
//             showMenu: function () {
//                 this.show = true;
//                 console.debug("showMenu called, show =", this.show);
//             },
//             hideMenu: function () {
//                 this.show = false;
//                 console.debug("hideMenu called, show =", this.show);
//             },
//             toggleMenu: function () {
//                 this.show = !this.show;
//                 console.debug("toggleMenu called, show =", this.show);
//             },
//         },
//         mobSizeProd: {
//             show: false,
//             showBlock: function () {
//                 this.show = true;
//                 console.debug("showMenu called, show =", this.show);
//             },
//             hideBlock: function () {
//                 this.show = false;
//                 console.debug("hideMenu called, show =", this.show);
//             },
//             toggleBlock: function () {
//                 this.show = !this.show;
//                 console.debug("toggleMenu called, show =", this.show);
//             },
//         },
//         isMobile: window.innerWidth <= 768,
//     });
//
//     window.addEventListener("resize", () => {
//         const isMobile = window.innerWidth <= 768;
//         SparkVibe.setValueByPath("appMenu.isMobile", isMobile);
//     });
// };

const addCartBtn = () => {
	$.notify('Модель добавлена в корзину!', 'success');
};

const filterBtnProducts = () => {
	$.notify('filter', 'info');
};

function throttle(fn, limit = 100) {
	let lastCall = 0;
	return function (...args) {
		const now = Date.now();
		if (now - lastCall >= limit) {
			lastCall = now;
			fn.apply(this, args);
		}
	};
}

const footerVibe = () => {
	SparkVibe.registerStore('footer', {
		openIndex: null,
		footerItems: [
			{ title: 'Section 1', content: 'Content 1', scrollHeight: 0 },
			{ title: 'Section 2', content: 'Content 2', scrollHeight: 0 },
			// ... другие секции
		],
		toggleFooter(index) {
			if (this.openIndex === index) {
				this.openIndex = null;
			} else {
				this.openIndex = index;
			}
		},
	});

	// Регистрация компонента
	SparkVibe.registerFileComponent('footer-main-mob', './FooterMainMob.html');

	// Обработчик ресайза для обновления scrollHeight
	function updateScrollHeights() {
		const contents = document.querySelectorAll('.footerMainMob__content');
		contents.forEach((content, index) => {
			SparkVibe.registerStore('footer').footerItems[index].scrollHeight =
				content.scrollHeight;
		});
	}

	// Обновление высоты при загрузке и ресайзе
	window.addEventListener('load', updateScrollHeights);
	window.addEventListener('resize', updateScrollHeights);
};

const changeColor = () => {};
const selectSizePrd = () => {};

const hideLogoMob = () => {
	const headerMbBarNavLogo = document.querySelector('.headerMbBarNavLogo');
	if (!headerMbBarNavLogo) return;

	let logoVisible = false;

	const showLogo = () => {
		gsap.fromTo(
			headerMbBarNavLogo,
			{
				height: 0,
				paddingTop: 0,
				paddingBottom: 0,
				opacity: 0,
				y: -20,
				display: 'none',
			}
			// {
			//     height: "auto",
			//     paddingTop: 12,
			//     paddingBottom: 12,
			//     opacity: 1,
			//     y: 0,
			//     display: "flex",
			//     duration: 0.6,
			//     ease: "power3.out",
			//     onStart: () => {
			//         headerMbBarNavLogo.style.visibility = "visible";
			//     }
			// }
		);
		logoVisible = true;
	};

	const hideLogo = () => {
		gsap.to(headerMbBarNavLogo, {
			height: 0,
			paddingTop: 0,
			paddingBottom: 0,
			opacity: 0,
			y: -10,
			duration: 0.4,
			ease: 'power2.inOut',
			onComplete: () => {
				headerMbBarNavLogo.style.display = 'none';
				// headerMbBarNavLogo.style.visibility = "hidden";
			},
		});
		logoVisible = false;
	};

	const checkAndUpdateLogo = () => {
		const scrollTop = window.scrollY || document.documentElement.scrollTop;
		const isMobile = window.innerWidth <= 768;

		if (isMobile) {
			if (scrollTop < 70 && !logoVisible) {
				showLogo();
			} else if (scrollTop >= 70 && logoVisible) {
				hideLogo();
			}
		} else if (logoVisible) {
			hideLogo();
		}
	};

	window.addEventListener('scroll', checkAndUpdateLogo);
	window.addEventListener('resize', checkAndUpdateLogo);
	window.addEventListener('DOMContentLoaded', checkAndUpdateLogo);
	checkAndUpdateLogo();
};

// Липкая менюшка
const fixedScrollNavMob = () => {
	const header = document.querySelector('.headerMainNavWrapper');
	const headerNav = document.querySelector('.headerMainNav');
	const headerLogo = document.querySelector('.headerMbBarNavLogo');

	// let isFixed = false;
	let headerHeight = headerNav.offsetHeight;

	const updateHeaderHeight = () => {
		headerHeight = headerNav.offsetHeight;

		if (
			window.states &&
			window.states.headMainNav &&
			window.states.headMainNav.appMainNav
		) {
			if (window.innerWidth <= 768 && headerLogo.classList.contains('__Hide')) {
				headerHeight += headerLogo.offsetHeight;
			}
		}
	};

	// 	document.body.style.setProperty('margin-top', `${value}px`, 'important');
	// };

	const updateNavPosition = () => {
		const scrollTop = window.scrollY || document.documentElement.scrollTop;
		updateHeaderHeight();
		setCssVariables(headerNav.offsetHeight, 'height', header);

		if (scrollTop >= 70) {
			header.style.position = 'fixed';
			header.style.top = '0';
			isFixed = true;
		} else if (scrollTop < 70) {
			header.style.position = 'absolute';
			header.style.top = 'unset';
			isFixed = false;
		}
	};

	//

	setTimeout(() => {
		updateNavPosition();
		updateHeaderHeight();
		setCssVariables(headerHeight, 'height', header);
		setHeaderOffset(headerHeight);
	}, 10);

	window.addEventListener('scroll', updateNavPosition);
	window.addEventListener('resize', () => {
		updateNavPosition();
		setCssVariables(headerHeight, 'height', header);
		setHeaderOffset(headerHeight);
	});

	updateHeaderHeight();
	updateNavPosition();
};

const showProductNav = (event) => {
	try {
		const target = event.target;
		navigation.navigate(target.dataset.href);
	} catch (e) {
		console.error('not click product link', event);
	}
};

// const selectCartColorProduct = (event) => {
//     const target = event.target;
//     SparkVibe.setValueByPath("appShop.cart.select.color", target.dataset.colorId)
// }

const selAccountGender = () => {
	// jQuery для обработки кликов по SVG
	jQuery(document).ready(function ($) {
		// Обработчик кликов на мужской и женский пол
		$('.gender-option').on('click', function () {
			var gender = $(this).data('gender');

			// Убираем активный класс у всех иконок
			$('.gender-option svg').each(function () {
				$(this).removeClass('active');
			});

			// Добавляем активный класс к выбранной иконке
			var svg = $(this).find('svg');
			svg.addClass('active');

			// Обновляем значение в скрытом radio input
			$(this).find('input[type="radio"]').prop('checked', true);
		});
	});
};

function setCssVariables(
	value,
	variableName,
	targetNode = document.documentElement
) {
	targetNode.style.setProperty(`--${variableName}`, `${value}px`);
}

// Устанавливаем ширину скроллбара
const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
setCssVariables(scrollbarWidth, 'scrollbar-width');

// Анимация для выпадающего меню
function dropdownMenu() {
	const menuWrapper = document.querySelector('.headerMainNavWrapper');
	const menuWrapperBaseHeight = menuWrapper.offsetHeight;
	setCssVariables(menuWrapperBaseHeight, 'height', menuWrapper);

	const menuItems = document.querySelectorAll('.quadmenu-item');
	let lastOpen = null;

	const observe = new MutationObserver((mutationList) => {
		mutationList.forEach((mutation) => {
			const classList = mutation.target.classList;

			if (
				mutation.type === 'attributes' &&
				mutation.attributeName === 'class'
			) {
				if (isOpen(classList, mutation.oldValue)) return openMenu(mutation);
				if (isClose(classList, mutation)) return closeMenu();
			}
		});
	});

	function isOpen(classList, oldValue) {
		return classList.contains('open') && !oldValue.match(/\bopen\b/);
	}

	function isClose(classList, mutation) {
		return (
			!classList.contains('open') &&
			mutation.oldValue.match(/\bopen\b/) &&
			mutation.target === lastOpen
		);
	}

	function openMenu(mutation) {
		console.log('open');
		setLast(mutation.target);

		const currentDropMenu = mutation.target.querySelector(
			'.quadmenu-dropdown-menu'
		);
		const currentDropMenuHeight = currentDropMenu.offsetHeight;

		menuWrapper.style.height =
			menuWrapperBaseHeight + currentDropMenuHeight + 'px';
		// menuWrapper.style.maxHeight = 'none';
	}

	function closeMenu(mutation) {
		console.log('closes');
		menuWrapper.style.height = '';
		// menuWrapper.style.maxHeight = '';
	}

	function setLast(node) {
		if (lastOpen) lastOpen.classList.remove('last');

		lastOpen = node;
		lastOpen.classList.add('last');
	}

	menuItems.forEach((item) => {
		observe.observe(item, {
			attributes: true,
			attributeOldValue: true,
			attributeFilter: ['class'],
		});
	});
}

// Добавляет отступы
function setHeaderOffset(offset) {
	const headerWrapper = document.querySelector('.headerMain');

	const headerBottomHeight =
		offset || document.querySelector('.headerMainNav').offsetHeight;

	headerWrapper.style.paddingBottom = headerBottomHeight + 'px';
}

setHeaderOffset();

function initNotify() {
	// Якорь для уведомления
	const notifyNode = document.createElement('div');
	notifyNode.classList.add('notifyAnchor');

	// Обертка для уведомления
	const notifyWrapperNode = document.createElement('div');
	notifyWrapperNode.classList.add('notifyAnchorWrapper');
	notifyWrapperNode.appendChild(notifyNode);
	notifyWrapperNode.style.position = 'fixed';
	notifyWrapperNode.style.width = '100%';
	notifyWrapperNode.style.height = '0px';

	document.body.appendChild(notifyWrapperNode);

	const isCheckout = document.body.classList.contains('woocommerce-checkout');
	const checkotMenu = document.querySelector('.cartMbHeader');
	const menu = document.querySelector('.headerMainNavWrapper');

	changeNotifyPosition();

	window.addEventListener('scroll', changeNotifyPosition);
	window.addEventListener('resize', changeNotifyPosition);

	// Функция для изменения позиции врапера
	function changeNotifyPosition() {
		if (!menu) return;

		const offset = 18;

		let newPosition = isCheckout
			? checkotMenu.getBoundingClientRect().bottom
			: menu.getBoundingClientRect().bottom;
		const scrollTop = window.scrollY || document.documentElement.scrollTop;
		const headerLogo = document.querySelector('.headerMbBarNavLogo');

		if (
			headerLogo &&
			window.innerWidth <= 768 &&
			headerLogo.classList.contains('__Hide') &&
			scrollTop < 70 &&
			!isCheckout
		) {
			newPosition += headerLogo.offsetHeight;
		}

		// Для десктопа
		if (window.innerWidth > 768) {
			newPosition += offset;
		}

		notifyWrapperNode.style.top = newPosition + 'px';
	}
}

function setContainerScope() {
	const container = document.querySelector('.gridWrap');

	if (container) {
		const right = container.getBoundingClientRect().right;
		setCssVariables(right, 'coordinate-container-right');
	}
}

function animateHeightOnChange(selector) {
	const element = document.querySelector(selector);
	if (!element) {
		console.warn(`Элемент по селектору "${selector}" не найден.`);
		return;
	}

	let lastHeight = element.offsetHeight;
	let isAnimating = false;

	element.style.overflow = 'hidden';
	element.style.transition = 'height 0.3s ease';

	const isVisible = (el) => {
		return el.offsetParent !== null; // false, если display: none у родителя
	};

	const observer = new ResizeObserver((entries) => {
		for (const entry of entries) {
			if (!isVisible(element)) return;

			const newHeight = entry.target.offsetHeight;

			if (isAnimating || newHeight === lastHeight) return;

			const prevHeight = lastHeight;
			lastHeight = newHeight;
			isAnimating = true;

			element.style.transition = 'none';
			element.style.height = prevHeight + 'px';

			requestAnimationFrame(() => {
				element.style.transition = 'height 0.3s ease';
				element.style.height = newHeight + 'px';

				const onTransitionEnd = () => {
					if (newHeight > 0) {
						element.style.height = 'auto';
					}
					element.removeEventListener('transitionend', onTransitionEnd);
					isAnimating = false;
				};
				element.addEventListener('transitionend', onTransitionEnd);
			});
		}
	});

	observer.observe(element);
}

function getNaturalHeight(node) {
	const clone = node.cloneNode(true);

	clone.style.position = 'absolute';
	clone.style.visibility = 'hidden';
	clone.style.height = 'auto';
	clone.style.maxHeight = 'none';
	clone.style.flex = 'none'; // не участвует во флексе

	document.body.appendChild(clone);
	const height = clone.offsetHeight;
	document.body.removeChild(clone);

	return height;
}

function syncHeights(selectorFrom, selectorTo, isNaturalHeight = false) {
	const fromNode = document.querySelector(selectorFrom);
	const toNode = document.querySelector(selectorTo);

	console.log(fromNode, toNode);

	if (!(fromNode instanceof Element) || !(toNode instanceof Element)) {
		console.warn(
			'syncHeights: Один из элементов не найден или не является DOM-узлом'
		);
		return;
	}

	const fn = () => {
		const height = isNaturalHeight
			? getNaturalHeight(fromNode)
			: fromNode.offsetHeight;
		toNode.style.maxHeight = height + 'px';
	};

	fn();
	new ResizeObserver(fn).observe(fromNode);
}
function selectPaymentMethod(element) {
	console.log('Выбран новый метод оплаты');
	try {
		// Контейнер выбора метода оплаты
		const container = element.closest('.payment-methods-block');
		if (!container) throw new Error('Контейнер выбора метода оплаты не найден');

		// Скрытый инпут выбора оплаты
		const hiddenInput = container.querySelector('input[name="payment_method"]');
		if (!hiddenInput)
			throw new Error('Скрытый инпут выбора метода оплаты не найден');

		// ID метода оплаты
		const choosenPaymentMethod = element.dataset.paymentMethod;
		if (!choosenPaymentMethod)
			throw new Error('Атрибут paymentMethod не найден');

		// Добавляем в скрытый инпут новый метод оплаты
		hiddenInput.value = choosenPaymentMethod;
		setActiveClass(element, '__Active');

		// Вызываем ивенты Woocommerce
		document.body.dispatchEvent(
			new CustomEvent('payment_method_selected', {
				detail: {
					gateway: choosenPaymentMethod,
					element: element,
				},
			})
		);
		document.body.dispatchEvent(new Event('update_checkout'));
	} catch (error) {
		console.log('Ошибка при выборе метода оплаты');
		console.error(error);
	}

	function setActiveClass(targetNode, className) {
		const selectPaymentList = document.querySelectorAll('.paymentMethodItem');

		selectPaymentList.forEach((selectPayment) => {
			selectPayment.classList.remove(className);
		});

		targetNode.classList.add(className);
	}
}

function handleShippingMethod(element, toggle = false) {
	const SELECTORS = {
		mainContainer: '.shipping-methods-block',
		tariffContainer: '.shipMethodTariffContainer',
		methodItem: '.shipMethodItem',
		hiddenInput: 'input[name="shipping_method"]',
	};
	console.log('Выбран новый метод оплаты');
	try {
		if (toggle) {
			handleToggle();
		} else {
			handleSelect();
		}
	} catch (error) {
		console.log('Ошибка при выборе метода оплаты');
		console.error(error);
	}

	function handleSelect() {
		event.stopPropagation();
		// Контейнер выбора метода оплаты
		const container = element.closest(SELECTORS.mainContainer);
		if (!container)
			throw new Error('Контейнер выбора метода доставки не найден');

		// Скрытый инпут выбора оплаты
		const hiddenInput = container.querySelector(SELECTORS.hiddenInput);
		if (!hiddenInput)
			throw new Error('Скрытый инпут выбора метода доставки не найден');

		// ID метода оплаты
		const choosenPaymentMethod = element.dataset.shippingMethod;
		if (!choosenPaymentMethod)
			throw new Error('Атрибут paymentMethod не найден');

		// Добавляем в скрытый инпут новый метод оплаты
		hiddenInput.value = choosenPaymentMethod;

		// Еслли нажат чекбокс
		if (element.classList.contains('shipMethodTariffItem')) {
			const methodItem = element.closest(SELECTORS.methodItem);

			const currentCheckbox = element.querySelector('input[type=checkbox]');
			if (!currentCheckbox) throw new Error('Нажатый чекбокс не найден');

			const tariffWrapper = element.closest(SELECTORS.tariffContainer);
			if (!tariffWrapper) throw new Error('Обертка для тарифов не найдена');

			setActiveCheckbox(currentCheckbox, tariffWrapper);

			if (methodItem) {
				setActiveClass(methodItem, '__Active');
			} else {
				console.warn('При выборе чекбокса врапер метода не найдет');
			}
		} else {
			setActiveClass(element, '__Active');
			disableAllCheckbox(
				choosenPaymentMethod,
				element.getAttribute('data-method-title')
			);
		}

		document.body.dispatchEvent(new Event('update_checkout'));
		window.states.checkoutMbView.setShipMethod(
			choosenPaymentMethod,
			element.getAttribute('data-shipping-title')
		);
	}

	function handleToggle() {
		event.preventDefault();

		const tariffContainer = element.querySelector(SELECTORS.tariffContainer);
		if (!tariffContainer) throw new Error('Wrapper для тарифов не найден');

		tariffContainer.classList.toggle('disable');
	}

	function setActiveClass(targetNode, className) {
		const selectPaymentList = document.querySelectorAll(SELECTORS.methodItem);

		selectPaymentList.forEach((selectPayment) => {
			selectPayment.classList.remove(className);
		});

		targetNode.classList.add(className);
	}

	function disableAllCheckbox() {
		const mainContainer = document.querySelector(SELECTORS.mainContainer);
		const chechboxList = mainContainer.querySelectorAll('input[type=checkbox]');
		chechboxList.forEach((checkbox) => (checkbox.checked = false));
	}
	function setActiveCheckbox(currentCheckbox, checkboxWrapper) {
		disableAllCheckbox();
		currentCheckbox.checked = true;
	}
}

// Coupon Vanilla JS
function togglePromocode(btn) {
	const close = (btn, blockContent) => {
		btn.setAttribute('aria-expanded', false);
		blockContent.setAttribute('aria-hidden', true);
		blockContent.style.display = 'none';
	};

	const open = (btn, blockContent) => {
		btn.setAttribute('aria-expanded', true);
		blockContent.setAttribute('aria-hidden', false);
		blockContent.style.display = '';
	};

	try {
		event.preventDefault();

		const container = btn.closest('.promocodeWrapper');
		if (!container) throw new Error('Контейнер промокода не найден');

		const blockContent = container.querySelector('#promocodeContent');
		if (!blockContent)
			throw new Error('Контейнер для контента промокода не найден');

		const isBtnExpanded = btn.getAttribute('aria-expanded');
		const isBlockContentHidden = blockContent.getAttribute('aria-hidden');

		if (isBtnExpanded === null && isBlockContentHidden === null) {
			throw new Error('Атрибутты не найдены');
		}

		// is open
		if (isBtnExpanded === 'true' && isBlockContentHidden === 'false') {
			close(btn, blockContent);
		}

		// is close
		if (isBtnExpanded === 'false' && isBlockContentHidden === 'true') {
			open(btn, blockContent);
		}
	} catch (error) {
		console.error('При обработки нажатия на купон произошла ошибка');
		console.error(error);
	}
}

async function applyPromocode(btn) {
	try {
		event.preventDefault();
		console.log('Кнопка промокода нажата');

		const container = btn.closest('.promocodeWrapper');
		if (!container) throw new Error('Контейнер промокода не найден');

		const formAction = container.getAttribute('data-action');
		if (!formAction) throw new Error('Дата атрибут action не найден');

		const formNonce = container.getAttribute('data-nonce');
		if (!formNonce) throw new Error('Дата атрибут nonce не найден');

		const ajaxUrl = container.getAttribute('data-ajax-url');
		if (!ajaxUrl) throw new Error('Дата атрибут ajax-url не найден');

		const input = container.querySelector('input');
		if (!input) throw new Error('Input промокода не найден');

		const code = input.value.trim();
		if (!code) {
			notification('Введите промокод');
			return;
		}

		const form = new FormData();
		form.append('action', formAction);
		form.append('nonce', formNonce);
		form.append('coupon_code', code);

		const { data } = await axios.post(ajaxUrl, form);

		if (data.success) {
			notification(data.data.message);
			document.body.dispatchEvent(new Event('update_checkout'));
		} else {
			notification(data.data.message);
		}
	} catch (error) {
		console.error('При обработки формы промокода произошла ошибка');
		console.error(error);
	}

	function notification(message) {
		jQuery.notify(
			// 2.1) содержимое: просто передаём текст
			jQuery('.notifyAnchor'),
			message,
			{
				// 2.2) указываем наш стиль и класс base
				style: 'cartStyleTwo',
				className: 'base',
				// 2.3) появление/скрытие
				showAnimation: 'slideDown',
				showDuration: 300,
				hideAnimation: 'slideUp',
				hideDuration: 200,
				// 2.4) автоскрытие через 2 секунды
				autoHide: true,
				autoHideDelay: 2000,
				// 2.5) позиционирование (можно изменить на top left / bottom right и т.д.)
				globalPosition: 'top center',
				// 2.6) передаём URL картинки, чтобы плагин подставил её в <img>
				icon: null,
			}
		);
	}
}

function initAutoOverlayForCustomFragments() {
	const FRAGMENT_SELECTORS = ['.cartPageBodyList'];

	function createOverlay() {
		const overlay = document.createElement('div');
		overlay.classList.add('blockUI', 'blockOverlay');

		return overlay;
	}

	function toggleOverlay(isLoad) {
		FRAGMENT_SELECTORS.forEach((selector) => {
			const targetNode = document.querySelectorAll(selector);

			targetNode.forEach((node) => {
				if (!node) {
					console.warn('targetNode is not a found');
				}

				let overlay = node.querySelector(':scope > .blockUI.blockOverlay');

				if (isLoad) {
					if (!overlay) {
						overlay = createOverlay();
						node.appendChild(overlay);
					}

					node.style.position = 'relative';
				} else {
					overlay?.remove?.();
				}
			});
		});
	}

	// Патчим запросы
	(function () {
		const originOpen = XMLHttpRequest.prototype.open;
		const originSend = XMLHttpRequest.prototype.send;

		XMLHttpRequest.prototype.open = function (method, url) {
			this._url = url;
			return originOpen.apply(this, arguments);
		};

		XMLHttpRequest.prototype.send = function (body) {
			if (this._url && this._url.indexOf('checkout') !== -1) {
				const params = Object.fromEntries(new URLSearchParams(body));

				params['ship_to_different_address'] = 1;

				arguments[0] = new URLSearchParams(params).toString();
			}
			if (this._url && this._url.indexOf('update_order_review') !== -1) {
				console.log('🚀 Отправен запрос для обновления чекаута');

				// Получаем объект с парамметрами запроса
				const params = Object.fromEntries(new URLSearchParams(body));
				// Получаем объект postData
				const postData = Object.fromEntries(
					new URLSearchParams(params['post_data'])
				);

				// Переносим данные с post_data выше
				if (!params.paymentMethod) {
					console.warn('payment_method not fount');

					params['payment_method'] = postData['payment_method'];
				}

				if (postData['shipping_address_1'] && !params['shipping_address_1']) {
					console.log('shipping_address_1 not fount');

					params['shipping_address_1'] = postData['shipping_address_1'];
				}

				if (postData['shipping_city'] && !params['shipping_city']) {
					console.log('shipping_city not fount');

					params['shipping_city'] = postData['shipping_city'];
				}

				if (postData['shipping_postcode'] && !params['shipping_postcode']) {
					console.log('shipping_postcode not fount');

					params['shipping_postcode'] = postData['shipping_postcode'];
				}

				arguments[0] = new URLSearchParams(params).toString();
				toggleOverlay(true);
				this.addEventListener('loadend', () => toggleOverlay(false));
			}

			return originSend.apply(this, arguments);
		};
	})();
}

initAutoOverlayForCustomFragments();

function toggleCheckbox(wrapper, className) {
	try {
		const input = wrapper.querySelector('input[type=checkbox]');
		if (!input) throw new Error('Чекбокс не найден');

		wrapper.classList.toggle(className);
		input.checked = !input.checked;
		input.dispatchEvent(new Event('change'));
		document.body.dispatchEvent(new Event('update_checkout'));
	} catch (err) {
		console.error('Ошибка в функции toggleCheckbox');
		console.error(err);
	}
}

function initOrderItems(wrapper = document) {
	try {
		const BORDER_OFFSET = 1;

		const orderItems = wrapper.querySelectorAll('.orderBlock');
		if (orderItems.length < 1) return;

		orderItems.forEach((orderItem) => {
			const orderHeader = orderItem.querySelector('.orderHeading');
			if (!orderHeader) throw new Error('.orderItemHeading is not found');

			const orderToggleBtn = orderItem.querySelector('.orderBtnToggle');
			if (!orderToggleBtn) throw new Error('.orderBtnToggle is not found');

			const { orderCollapseHeight } = getElementHeight(orderHeader, orderItem);

			orderItem.style.height = `${orderCollapseHeight}px`;

			const handleToggle = () => {
				event.stopPropagation();
				const { orderMaxHeight, orderCollapseHeight } = getElementHeight(
					orderHeader,
					orderItem
				);

				orderItem.classList.toggle('__Active');

				if (orderItem.classList.contains('__Active')) {
					orderItem.style.height = `${orderMaxHeight}px`;
				} else {
					orderItem.style.height = `${orderCollapseHeight}px`;
				}
			};

			orderHeader.addEventListener('click', handleToggle);
			orderToggleBtn.addEventListener('click', handleToggle);
		});

		function getElementHeight(header, el) {
			const orderCollapseHeight = header.clientHeight - BORDER_OFFSET;
			const orderMaxHeight = el.scrollHeight;

			return {
				orderCollapseHeight,
				orderMaxHeight,
			};
		}
	} catch (err) {
		console.log('При иницилизации order items произошла ошибка');
		console.error(err);
	}
}
function initOrderHistoryTabs() {
	const ACTIVE_CLASS = '__Active';
	const TAB_SELECTOR = '.orderPageBlockTabItem';
	const TAB_CONTENT_SELECTOR = '.orderPageBlockContent';

	const tabs = document.querySelectorAll(TAB_SELECTOR);
	if (tabs.length < 1) return;

	const tabContents = document.querySelectorAll(TAB_CONTENT_SELECTOR);
	if (tabContents.length < 1) return;

	try {
		tabs.forEach((tab) => {
			tab.addEventListener('click', () => {
				clearAllNodes();
				setActive(tab);
			});
		});
	} catch (err) {
		console.error('При иницилизации initOrderHistoryTabs произошла ошибка');
		console.error(err);
	}

	function clearAllNodes() {
		try {
			tabs.forEach((tab) => tab.classList.remove(ACTIVE_CLASS));
			tabContents.forEach((content) => content.classList.remove(ACTIVE_CLASS));
		} catch (err) {
			console.error('В функции clearAllNodes произошла ошибка');
			throw err;
		}
	}
	function setActive(tab) {
		try {
			const targetTabContentId = tab.getAttribute('data-target');
			if (!targetTabContentId)
				throw new Error('targetTabContentId is not defined');

			const targetTabContent = document.querySelector(targetTabContentId);
			if (!targetTabContent) throw new Error('targetTabContent is not defined');

			tab.classList.add(ACTIVE_CLASS);
			targetTabContent.classList.add(ACTIVE_CLASS);

			// initOrderItems(targetTabContent);
		} catch (err) {
			console.error('В функции setActive произошла ошибка');
			throw err;
		}
	}
}

// window.callNotify = function callNotify() {
// 	jQuery.notify(
// 		jQuery('.notifyAnchor'),
// 		// 2.1) содержимое: просто передаём текст
// 		'This is message',
// 		{
// 			// 2.2) указываем наш стиль и класс base
// 			style: 'cartStyleTwo',
// 			className: 'base',
// 			// 2.3) появление/скрытие
// 			showAnimation: 'slideDown',
// 			showDuration: 300,
// 			hideAnimation: 'slideUp',
// 			hideDuration: 200,
// 			// 2.4) автоскрытие через 2 секунды
// 			autoHide: true,
// 			autoHideDelay: 2000,
// 			// 2.5) позиционирование (можно изменить на top left / bottom right и т.д.)
// 			globalPosition: 'top center',
// 			// 2.6) передаём URL картинки, чтобы плагин подставил её в <img>
// 			icon: null,
// 		}
// 	);
// };

/**
 * Устанавливает cookie
 * @param {string} name  — имя куки
 * @param {string} value — значение
 * @param {Object} [options] — дополнительные опции:
 *   - expires: число дней или дата (Date)
 *   - path: путь (по умолчанию '/')
 *   - domain: домен
 *   - secure: boolean
 *   - sameSite: 'Lax' | 'Strict' | 'None'
 */
function setCookie(name, value, options = {}) {
	let cookieStr = encodeURIComponent(name) + '=' + encodeURIComponent(value);

	if (options.expires) {
		let exp = options.expires;
		if (typeof exp === 'number') {
			const date = new Date();
			date.setTime(date.getTime() + exp * 24 * 60 * 60 * 1000);
			exp = date;
		}
		cookieStr += '; expires=' + exp.toUTCString();
	}

	cookieStr += '; path=' + (options.path || '/');

	if (options.domain) cookieStr += '; domain=' + options.domain;
	if (options.secure) cookieStr += '; secure';
	if (options.sameSite) cookieStr += '; samesite=' + options.sameSite;

	document.cookie = cookieStr;
}

/**
 * Читает значение куки по имени
 * @param {string} name — имя куки
 * @returns {string|null}
 */
function getCookie(name) {
	const nameEQ = encodeURIComponent(name) + '=';
	const parts = document.cookie.split(';');
	for (let part of parts) {
		part = part.trim();
		if (part.startsWith(nameEQ)) {
			return decodeURIComponent(part.substring(nameEQ.length));
		}
	}
	return null;
}

(function () {
	const proto = Storage.prototype;
	const origSet = proto.setItem;
	const origRem = proto.removeItem;
	const origClr = proto.clear;

	// общий генератор события
	function emit(key, oldValue, newValue) {
		const ev = new CustomEvent('localStorageChanged', {
			detail: { key, oldValue, newValue },
		});
		window.dispatchEvent(ev);
	}

	proto.setItem = function (key, value) {
		const old = this.getItem(key);
		origSet.apply(this, arguments);
		emit(key, old, String(value));
	};

	proto.removeItem = function (key) {
		const old = this.getItem(key);
		origRem.apply(this, arguments);
		emit(key, old, null);
	};

	proto.clear = function () {
		origClr.apply(this, arguments);
		// можно передавать special‑ключ или null
		emit(null, null, null);
	};
})();

window.addEventListener('localStorageChanged', (e) => {
	if (e.detail.key !== 'favorite_products') return;

	setCookie(e.detail.key, e.detail.newValue);
	console.log('Установлены новые куки');
	console.log('olg Value storage', e.detail.oldValue);
	console.log('new Value storage', e.detail.newValue);
	console.log('New cookie', getCookie(e.detail.key));
});

function stopScrollPropagation(selector) {
	const scrollNodeList = document.querySelectorAll(selector);
	if (scrollNodeList.length < 1) {
		console.warn('From stopScrollPropagation: элементы не найдены');
		return;
	}

	scrollNodeList.forEach((scrollNode) => {
		console.log('stop propagation');
		scrollNode.addEventListener(
			'touchmove',
			function (event) {
				const touch = event.touches[0];

				const canScrollVertically =
					(scrollNode.scrollTop > 0 && touch.clientY > 0) ||
					scrollNode.scrollTop + scrollNode.clientHeight <
						scrollNode.scrollHeight;

				const canScrollHorizontally =
					(scrollNode.scrollLeft > 0 && touch.clientX > 0) ||
					scrollNode.scrollLeft + scrollNode.clientWidth <
						scrollNode.scrollWidth;

				if (canScrollVertically || canScrollHorizontally) {
					event.stopPropagation(); // блокируем передачу свайпу
				}
			},
			{ passive: false }
		);
	});
}

// Обновляем при изменении размера (например, поворот экрана)
document.addEventListener('DOMContentLoaded', function () {
	stopScrollPropagation('.itemBlockHeadingSelColor');

	initOrderItems();

	initOrderHistoryTabs();

	animateHeightOnChange('.detailIdeaModalBlock');

	registerNotifyCustom();
	//
	swipperInit().then();
	//
	tooglesDropFooter();
	//
	// hideLogoMob();
	//
	fixedScrollNavMob();
	//
	selAccountGender();

	dropdownMenu();

	initNotify();

	setContainerScope();
});

uiShopAppVibe();
