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
			'<div style="display: flex; align-items: center;">' +
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

	document
		.querySelectorAll('.previewSliderItemImageSubGallery')
		.forEach((galleryEl, index) => {
			// console.log(galleryEl);
			const paginationContainer = galleryEl.querySelector('.bottomPaginate');

			new Swiper(galleryEl, {
				slidesPerView: 1,
				grabCursor: true,
				spaceBetween: 0,
				loop: true,
				simulateTouch: true,
				resize: true,
				drag: true,
				//
				pagination: {
					el: paginationContainer,
					clickable: true,
					renderBullet: function (index, className) {
						if (index >= 3) return ''; // после третьего булета ничего не рендерим
						return `<span class="${className} bullet"></span>`;
					},
				},
			});
		});
};
//
const bannerMainHomeSlider = async () => {
	const bannerMainHomeSlider = document.querySelector('.bannerMainHomeSlider');
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
	const bannerMainHomeSlider = document.querySelector('.mainMobSlider');
	const paginationContainer = document.querySelector(
		'.mainMobSlider .bottomPaginate'
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
			// renderBullet: function (index, className) {
			//     return `<span class="${className} bullet"></span>`;
			// },
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
	// const featuresSliderActionBtnLft = document.querySelector(".featuresSliderActionBtnLft");
	// const featuresSliderActionBtnRgt = document.querySelector(".featuresSliderActionBtnRgh");
	// const featuresSliderActionCount = document.querySelector(".featuresSliderActionCount");

	newProductsPreviewSliders.forEach((SliderEl, index) => {
		const parent = SliderEl.parentElement;
		const prevButton = parent.querySelector('.sliderNavBtnPrev');
		const nextButton = parent.querySelector('.sliderNavBtnNext');

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
	});
};

const mobMainProductsSlider = async () => {
	try {
		const mobMainProductSliders = document.querySelector(
			'.bannerMainHome .bannerMainHomeSlider'
		);

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

			spaceBetween: 12,

			// slidesPerView: 2,

			pagination: {
				el: document.querySelector(
					'.ideaProduct .ideaProductBody .ideaBottomPaginate'
				),
				clickable: true,
				renderBullet: function (index, className) {
					if (index >= 3) return ''; // после третьего булета ничего не рендерим
					return `<span class="${className} bullet"></span>`;
				},
			},

			navigation: {
				nextEl: '.ideaProductSliderNext',
				prevEl: '.ideaProductSliderPrev',
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
	});
};

// Инициализация и синхронизация слайдеров продукта
const thumbnailProductSlider = async (
	rootSelector = '#shopProductDetailCardImages'
) => {
	console.log('THUMBNAIL !!!');

	// Флаги состояния
	let isReloading = false;
	let isSyncing = false;

	// Инстансы слайдеров
	let thumbnailSlider = null;
	let mainSlider = null;
	let mobSlider = null;
	let thumbObserver = null;

	// Утилита debounce
	//const debounce = (fn, delay) => {
	//let timer;
	//return (...args) => {
	//clearTimeout(timer);
	//timer = setTimeout(() => fn(...args), delay);
	//};
	//};

	// Ждём появления элементов в DOM внутри root
	const waitFor = (selector, timeout = 5000) =>
		new Promise((resolve, reject) => {
			const start = Date.now();
			(function check() {
				const elems = document.querySelectorAll(`${rootSelector} ${selector}`);
				if (elems.length) {
					resolve(elems);
				} else if (Date.now() - start > timeout) {
					reject(
						new Error(`Elements ${selector} not found within ${timeout}ms`)
					);
				} else {
					setTimeout(check, 50);
				}
			})();
		});

	// «Тихая» синхронизация без коллбеков
	//const syncAll = debounce((idx) => {
	//if (isReloading || isSyncing) return;
	//isSyncing = true;
	//console.log('🔄 syncAll →', idx);
	//if (thumbnailSlider) thumbnailSlider.slideTo(idx, 0, false);
	//if (mainSlider) mainSlider.slideTo(idx, 0, false);
	//if (mobSlider) mobSlider.slideTo(idx, 0, false);
	//isSyncing = false;
	//}, 50);

	// Инициализация thumbnail-слайдера
	const initThumbnail = async () => {
		const rootEl = document.querySelector(rootSelector);
		const container = document.querySelector(
			`${rootSelector} .cardImagesThumbSlider`
		);
		if (!container) return null;

		try {
			await waitFor('.cardImagesThumbSlider .swiper-slide');
		} catch (e) {
			console.warn('Thumbnail slides not found:', e);
			return null;
		}

		// Отключаем старый observer
		if (thumbObserver) {
			thumbObserver.disconnect();
			thumbObserver = null;
		}

		const slides = container.querySelectorAll('.swiper-slide');
		const perView = Math.min(slides.length, 3);
		const loop = slides.length > perView;

		const config = {
			slidesPerView: perView,
			direction: 'vertical',
			spaceBetween: 20,
			loop: false,
			slideToClickedSlide: true,
			watchSlidesProgress: true,
			observer: true,
			observeParents: true,
			watchOverflow: false, // чтобы навигация не деактивировалась
			navigation: {
				nextEl: rootEl.querySelector('.cardImagesThumbSliderNavNext'),
				prevEl: rootEl.querySelector('.cardImagesThumbSliderNavPrev'),
			},

			//on: {
			//init() {
			//console.log('✅ Thumb init');
			//},
			//slideChange() {
			//if (!isReloading) syncAll(this.realIndex);
			//},
			//},
		};

		thumbnailSlider = new Swiper(container, config);

		thumbnailSlider.on('slideChange', () => {
			const idx = thumbnailSlider.realIndex; // реальный индекс без учёта клонов
			// если главный слайдер уже инициализирован – листаем вместе с мини-
			if (mainSlider) mainSlider.slideToLoop(idx, 0, false);
			// если мобильный слайдер уже инициализирован – тоже листаем
			if (mobSlider) mobSlider.slideToLoop(idx, 0, false);
		});

		thumbnailSlider.update();

		// Наблюдаем за data-select на превью
		//thumbObserver = new MutationObserver((muts) => {
		//muts.forEach((m) => {
		//if (m.type === 'attributes' && m.attributeName === 'data-select') {
		//const idx = parseInt(
		//m.target.getAttribute('data-swiper-slide-index'),
		//10
		//);
		//if (!isNaN(idx)) syncAll(idx);
		//}
		//});
		//});
		//container.querySelectorAll('.swiper-slide').forEach((slide) => {
		//thumbObserver.observe(slide, {
		//attributes: true,
		//attributeFilter: ['data-select'],
		//});
		//});

		return thumbnailSlider;
	};

	// Инициализация основного слайдера
	const initMain = async () => {
		const rootEl = document.querySelector(rootSelector);

		const container = document.querySelector(
			`${rootSelector} .shopProductDetailCardImagesMain`
		);
		if (!container) return null;

		try {
			await waitFor('.shopProductDetailCardImagesMain .swiper-slide');
		} catch (e) {
			console.warn('Main slides not found:', e);
			return null;
		}

		const slides = container.querySelectorAll('.swiper-slide');
		const loop = slides.length > 1;

		const config = {
			slidesPerView: 'auto',
			spaceBetween: 20,
			loop,
			observer: true,
			observeParents: true,
			watchOverflow: false,
			allowTouchMove: true,
			navigation: {
				nextEl: rootEl.querySelector('.cardImagesThumbSliderNavNext'),
				prevEl: rootEl.querySelector('.cardImagesThumbSliderNavPrev'),
			},

			thumbs: thumbnailSlider
				? {
						swiper: thumbnailSlider,
						slideThumbActiveClass: '__Active',
				  }
				: undefined,
			//on: {
			//init() {
			//console.log('✅ Main init');
			//},
			//slideChange() {
			//if (!isReloading) syncAll(this.realIndex);
			//},
			//},
		};

		mainSlider = new Swiper(container, config);
		mainSlider.update();
		return mainSlider;
	};

	// Инициализация мобильного слайдера
	const initMob = async () => {
		const rootEl = document.querySelector(rootSelector);

		const container = document.querySelector(`${rootSelector} .mainMobSlider`);
		if (!container) return null;

		try {
			await waitFor('.mainMobSlider .swiper-slide');
		} catch (e) {
			console.warn('Mobile slides not found:', e);
			return null;
		}

		const slides = container.querySelectorAll('.swiper-slide');
		const loop = slides.length > 1;
		const pag = container.querySelector('.bottomPaginate');

		const config = {
			slidesPerView: 1,
			direction: 'horizontal',
			loop,
			simulateTouch: true,
			observer: true,
			observeParents: true,
			watchOverflow: false,
			allowTouchMove: true,
			pagination: pag
				? {
						el: pag,
						clickable: true,
						renderBullet: (i, cls) =>
							i < 3 ? `<span class="${cls} bullet"></span>` : '',
				  }
				: undefined,
			navigation: {
				nextEl: container.querySelector('.mainMobSliderNavNext'),
				prevEl: container.querySelector('.mainMobSliderNavPrev'),
			},
			//on: {
			//init() {
			//console.log('✅ Mob init');
			//},
			//slideChange() {
			//if (!isReloading) syncAll(this.realIndex);
			//},
			//},
		};

		mobSlider = new Swiper(container, config);
		mobSlider.update();
		return mobSlider;
	};

	// Полная перезагрузка всех трёх
	const reloadAll = async () => {
		console.log('🔄 reloadAll start');
		isReloading = true;

		if (thumbnailSlider) thumbnailSlider.destroy(true, true);
		if (mainSlider) mainSlider.destroy(true, true);
		if (mobSlider) mobSlider.destroy(true, true);
		if (thumbObserver) {
			thumbObserver.disconnect();
			thumbObserver = null;
		}

		await Vue.nextTick();
		await initThumbnail();
		await initMain();
		await initMob();

		isReloading = false;
		console.log('🔄 reloadAll done');
	};

	// Первичная инициализация
	console.log('🚀 initial init');
	isReloading = true;
	await initThumbnail();
	await initMain();
	await initMob();
	isReloading = false;
	console.log('✅ initial init done');

	// Экспорт для Vue-компонента
	window.reloadProductSliders = reloadAll;
};

// Vue-компонент — инициализация только внутри #shopProductDetailCardImages
const shopProductDetailCardImages = () => {
	const root = '#shopProductDetailCardImages';
	if (!document.querySelector(root)) return console.warn(`${root} не найден`);

	thumbnailProductSlider(root); // Инициализация слайдеров

	Vue.createApp({
		setup() {
			const el = document.querySelector(root);
			const productId = el.getAttribute('data-product-id');
			const slidesData = window.prdImagesSlides || { all: [], by_color: {} };
			const firstColor = Object.keys(slidesData.by_color)[0] || null;
			const selectedColor = Vue.ref(firstColor);

			// Вызов перезагрузки после монтирования компонента
			Vue.onMounted(async () => {
				await Vue.nextTick(); // Ждём обновления DOM
				if (window.reloadProductSliders) {
					window.reloadProductSliders(); // Перезагружаем слайдеры
					console.log('Sliders reloaded after component mount');
				}
			});

			const selectSlideImgByColor = async (color) => {
				selectedColor.value = color;
				await Vue.nextTick();
				if (window.reloadProductSliders) {
					window.reloadProductSliders();
				}
			};

			return {
				selectedColor,
				selectSlideImgByColor,
			};
		},
	}).mount(root);
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

	const waitFor = (selector, timeout = 5000) =>
		new Promise((resolve, reject) => {
			const start = Date.now();
			(function check() {
				const elems = document.querySelectorAll(`${rootSelector} ${selector}`);
				if (elems.length) {
					resolve(elems);
				} else if (Date.now() - start > timeout) {
					reject(
						new Error(`Elements ${selector} not found within ${timeout}ms`)
					);
				} else {
					setTimeout(check, 50);
				}
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

	await ideaProductsSlider();

	await accesrPreviewSlider();

	await mobMainProductsSlider();
	//
	await subGalleryProductCard();
	//
	await colorCircle();

	await thumbnailProductSlider();
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
		const el = document.getElementById('singlePrdCard');
		if (!el) return console.warn('#singlePrdCard не найден!');

		window.states.singlePrdCard = Vue.createApp({
			setup() {
				// const addedProducts = Vue.ref([]); // Храним ID добавленных товаров
				const appShop = Vue.ref({
					cart: {
						select: {
							color: '',
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
						// selected: null
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

				const selectCartColor = (color) => {
					console.log('Выбран цвет:', color);
					appShop.value.cart.select.color = color;
					//
					if (
						window.states.shopPrdDetailCardImages &&
						window.states.shopPrdDetailCardImages.selectSlideImgByColor
					) {
						window.states.shopPrdDetailCardImages.selectSlideImgByColor(color);
					}
				};

				// Получаем параметры из data-атрибутов
				const productId = el.getAttribute('data-product-id');
				const nonce = el.getAttribute('data-nonce');
				const ajaxUrl = el.getAttribute('data-ajax-url');

				// const getSelectedWhtLst = (productId) => isFavorite(productId);
				const getSelectedWhtLst = (productId) =>
					Vue.computed(
						() => !window.states.headMainNav.getFavorites().includes(productId)
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
								},
							}
						)
						.then(function (response) {
							if (response.data.success) {
								const action = response.data.data.action;
								if (action === 'added') {
									// $(document) — глобальный контейнер уведомлений
									jQuery.notify(
										jQuery('.notifyAnchor'),
										// 2.1) содержимое: просто передаём текст
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
									// $(document) — глобальный контейнер уведомлений
									jQuery.notify(
										jQuery('.notifyAnchor'),
										// 2.1) содержимое: просто передаём текст
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
								// state.message = response.data.data.message || 'Произошла ошибка';
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
									// $(document) — глобальный контейнер уведомлений
									jQuery.notify(
										// 2.1) содержимое: просто передаём текст
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
							}

							window.states.headMainNav.fetchFavorites();
						})
						.catch(function (error) {
							console.error(error);
							console.log('Ошибка запроса');
						});
				}

				const addToCartBtn = (event, productItem) => {
					console.log(productId);
					if (!appShop.value.cart.btnAddActive) {
						// appShop.cart.value.push(productId)
						appShop.value.cart.btnAddActive = true;
						// Показ уведомления
						showAddToCartNotification(productItem);
						// console.log(event.target);
						if (event.target.querySelector('.addToCardBtnHeadingTitle')) {
							event.target.querySelector(
								'.addToCardBtnHeadingTitle'
							).innerHTML = 'Перейти в корзину';
						}
					} else {
						// Переход в корзину
						window.location.href = '/cart';
					}
				};

				const addToWhtListMob = (itemCard) => {
					console.log('test mob');
					showAddFavoriteNotification(itemCard);
				};

				//

				//
				const toggleIdeaModal = () => {
					console.log('PC - идеи!');
					appShop.value.idea.modal.show = !appShop.value.idea.modal.show;
				};

				const toggleIdeaMbModal = () => {
					console.log('Мобильные - идеи!');
					appShop.value.idea.modal_mb.show = !appShop.value.idea.modal_mb.show;
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
					appShop.value.cart.mob.size.show = !appShop.value.cart.mob.size.show;
				};

				// Vue.onEvent() //

				// Следим за изменением состояния меню и блокируем body
				Vue.watch(
					() =>
						appShop.value.idea.modal.show || appShop.value.idea.modal_mb.show,
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

				Vue.onMounted(() => {
					const defColor = el.dataset.defaultColor;
					const defSize = el.dataset.defaultSize;

					if (defColor) {
						appShop.value.cart.select.color = defColor;
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
					getSelectedWhtLst,
					addToWhtListMob,
					//
					toggleIdeaModal,
					toggleIdeaMbModal,
					showIdeaByProduct,
					//
					toggleMobSizePanel,
					//
					appShop,
					appFavoriteBtn,
				};
			},
		}).mount(el);
	};

	// Компонент для карточки товара (мобильная версия)
	const singlePrdMob = () => {
		const singlePrdMobEl = document.getElementById('singlePrdMob');

		window.states.singlePrdMob = Vue.createApp({
			setup() {
				const appMobShop = Vue.ref({
					cart: {
						select: {
							color: '',
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
						// products: [],
					},
				});

				//

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

				//

				// Получаем параметры из data-атрибутов
				const productId = singlePrdMobEl.getAttribute('data-product-id');
				const nonce = singlePrdMobEl.getAttribute('data-nonce');
				const ajaxUrl = singlePrdMobEl.getAttribute('data-ajax-url');

				// const getSelectedWhtLst = (productId) => isFavorite(productId);
				const getSelectedWhtLst = (productId) =>
					Vue.computed(() => !getFavorites().includes(productId)).value;

				const addToWhtListMob = (itemCard) => {
					console.log('test');

					showAddFavoriteNotification(itemCard);
				};

				function showAddToCartNotification(cardItem) {
					let imageUrl = cardItem.imageUrl ? cardItem.imageUrl : '';
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
								},
							}
						)
						.then(function (response) {
							if (response.data.success) {
								const action = response.data.data.action;
								if (action === 'added') {
									// $(document) — глобальный контейнер уведомлений
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
									// $(document) — глобальный контейнер уведомлений
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
								// fetchCart

								window.states.headMainNav.fetchCart();
							} else {
								console.log('Произошла ошибка');
								// state.message = response.data.data.message || 'Произошла ошибка';
							}
						})
						.catch(function (error) {
							console.error(error);
							console.log('Ошибка запроса');
						});
				}

				function showAddFavoriteNotification(cardItem) {
					let imageUrl = cardItem.imageUrl ? cardItem.imageUrl : '';
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
									// state.buttonText = 'Добавить в избранное';
									// state.message = 'Модель удалена из избранного';
									// state.isFavorited = false;
									// $(document) — глобальный контейнер уведомлений
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
							}
						})
						.catch(function (error) {
							console.error(error);
							console.log('Ошибка запроса');
						});
				}

				const addToCartMob = (event, productItem) => {
					console.log(productId);
					if (!appMobShop.value.cart.btnAddActive) {
						// appShop.cart.value.push(productId)
						appMobShop.value.cart.btnAddActive = true;
						// Показ уведомления
						showAddToCartNotification(productItem);
						console.log(event.target);
						if (
							document.querySelector(
								'#singlePrdMob .addToCartBtn .addToCartBtnTitle'
							)
						) {
							document.querySelector(
								'#singlePrdMob .addToCartBtn .addToCartBtnTitle'
							).innerHTML = 'Перейти в корзину';
						}
					} else {
						// Переход в корзину
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

				const selectCartColor = (color) => {
					console.log('Выбран цвет:', color);
					appMobShop.value.cart.select.color = color;
					if (
						window.states.shopPrdDetailCardImages &&
						window.states.shopPrdDetailCardImages.selectSlideImgByColor
					) {
						window.states.shopPrdDetailCardImages.selectSlideImgByColor(color);
					}
				};

				return {
					getProductsCartExist,
					toggleMobSizePanel,
					selectCartSize,
					selectCartColor,
					addToCartMob,
					addToWhtListMob,
					getSelectedWhtLst,
					// toggleFavorite,
					appMobShop,
				};
			},
		}).mount(document.getElementById('singlePrdMob'));
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
							console.log(productId, getFavorites().includes(productId));

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

		window.states.headMainNav = Vue.createApp({
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

				const getSelectedWhtLst = (productId) =>
					Vue.computed(() => !getFavorites().includes(productId)).value;

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

				const toggleMobNav = () => {
					appMainNav.value.mob.nav_menu.show =
						!appMainNav.value.mob.nav_menu.show;
				};

				//TODO: upd.

				const openSubMenu = (dropdownContent, event, button) => {
					console.log('Opening submenu:', dropdownContent);
					appMainNav.value.mob.sub_menu.content = dropdownContent;
					appMainNav.value.mob.sub_menu.title = button
						? button.textContent.trim()
						: '';
					appMainNav.value.mob.sub_menu.show = true;
					if (button && button.setAttribute) {
						document
							.querySelectorAll('.quadmenu-dropdown-toggle')
							.forEach((btn) => btn.setAttribute('aria-expanded', 'false'));
						button.setAttribute('aria-expanded', 'true');
					} else {
						console.warn('Button not available for aria-expanded update');
					}
				};

				const closeSubMenu = () => {
					appMainNav.value.mob.sub_menu.show = false;
					appMainNav.value.mob.sub_menu.content = '';
					appMainNav.value.mob.sub_menu.title = ''; // Clear title
					document
						.querySelectorAll('.quadmenu-dropdown-toggle')
						.forEach((btn) => btn.setAttribute('aria-expanded', 'false'));
				};

				const toggleMobSearch = () => {
					appMainNav.value.mob.nav_search.show =
						!appMainNav.value.mob.nav_search.show;
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

						['click', 'touchstart'].forEach((eventType) => {
							document.addEventListener(
								eventType,
								function (e) {
									const itemBtn = e.target.closest(
										'.quadmenu-item-has-children .quadmenu-dropdown-toggle'
									);
									if (itemBtn) {
										document.removeEventListener('click', itemBtn);
										document.removeEventListener('touchstart', itemBtn);

										console.log(`${eventType} on button:`, itemBtn);
										e.preventDefault();
										e.stopPropagation();
										const dropdown = itemBtn.nextElementSibling;
										if (dropdown) {
											console.log('Submenu:', dropdown);
											if (window.states.headMainNav) {
												window.states.headMainNav.openSubMenu(
													dropdown.outerHTML,
													e,
													itemBtn
												);
											} else {
												console.error('window.states.headMainNav is undefined');
											}

											console.log(itemBtn.nextElementSibling);

											//
											itemBtn.nextElementSibling.remove();
										} else {
											console.warn('Submenu not found for:', itemBtn);
										}
									}
								},
								{ passive: false }
							);
						});

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
				};
			},
		}).mount(el);
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
				// const selectedCategories = Vue.ref([]);
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

	const shopProductDetailCardImages = () => {
		const el = document.getElementById('shopProductDetailCardImages');
		if (!el) return console.warn('#shopProductDetailCardImages не найден!');

		window.states.shopPrdDetailCardImages = Vue.createApp({
			setup() {
				const productId = el.getAttribute('data-product-id');

				// Debounce функция для предотвращения множественных обновлений
				const debounce = (func, wait) => {
					let timeout;
					return function executedFunction(...args) {
						const later = () => {
							clearTimeout(timeout);
							func(...args);
						};
						clearTimeout(timeout);
						timeout = setTimeout(later, wait);
					};
				};

				// Функция фильтрации изображений по цвету
				const filterImagesByColor = (slidesData, selectedColor) => {
					if (!selectedColor) {
						// Если цвет не выбран, показываем основные изображения галереи
						return slidesData.all.filter((slide) => slide.type === 'gallery');
					} else {
						// Показываем изображения выбранного цвета
						return slidesData.by_color[selectedColor] || [];
					}
				};

				const appShopDetailCardSlider = Vue.ref({
					slidesData: window.prdImagesSlides || { all: [], by_color: {} },
					selectedColor: null,
					currentImageIndex: 0,
				});

				// Вычисляем текущие слайды на основе выбранного цвета
				const currentSlides = Vue.computed(() => {
					return filterImagesByColor(
						appShopDetailCardSlider.value.slidesData,
						appShopDetailCardSlider.value.selectedColor
					);
				});

				// Текущее изображение
				const currentImage = Vue.computed(() => {
					const slides = currentSlides.value;
					if (slides.length === 0) return null;
					return slides[
						Math.min(
							appShopDetailCardSlider.value.currentImageIndex,
							slides.length - 1
						)
					];
				});

				const appFavoriteBtn = Vue.ref({
					status: {
						active: false,
					},
				});

				const FAVORITES_KEY = 'favorite_products';

				function getFavorites() {
					try {
						return JSON.parse(localStorage.getItem(FAVORITES_KEY)) || [];
					} catch (e) {
						return [];
					}
				}

				const selectSlideImg = (slideItem) => {
					console.log('Selected image:', slideItem);
					const slides = currentSlides.value;
					const index = slides.findIndex(
						(slide) => slide.url === slideItem.url
					);
					if (index !== -1) {
						appShopDetailCardSlider.value.currentImageIndex = index;
					}
				};

				// Улучшенная функция смены цвета с перезагрузкой слайдеров
				const selectSlideImgByColor = debounce((queryColor) => {
					console.log('🎨 Смена цвета на:', queryColor);

					// Обновляем выбранный цвет
					appShopDetailCardSlider.value.selectedColor = queryColor;
					appShopDetailCardSlider.value.currentImageIndex = 0; // Сброс индекса при смене цвета

					// Ждем обновления DOM и перезагружаем слайдеры
					Vue.nextTick(() => {
						console.log(
							'📊 Количество слайдов изменилось:',
							currentSlides.value.length
						);
						console.log('🔄 Перезагрузка слайдеров после смены цвета');

						if (window.reloadProductSliders) {
							window.reloadProductSliders();
						}
					});
				}, 300);

				const getSelectedWhtLst = (productId) =>
					Vue.computed(() => !getFavorites().includes(productId)).value;

				const addToWhtListMob = (itemCard) => {
					console.log('test prd', !getFavorites().includes(productId));
					appFavoriteBtn.value.status.active =
						!getFavorites().includes(productId);
					console.log(appFavoriteBtn.value.status.active);
					window.states.headMainNav.addToWhtListMob({
						...itemCard,
						...{ productId: productId },
					});
				};

				// Следим за изменением текущих слайдов и обновляем слайдеры
				Vue.watch(
					currentSlides,
					(newSlides, oldSlides) => {
						if (newSlides.length !== oldSlides.length) {
							console.log(
								'📊 Количество слайдов изменилось:',
								newSlides.length
							);
							Vue.nextTick(() => {
								if (window.reloadProductSliders) {
									window.reloadProductSliders();
								}
							});
						}
					},
					{ deep: true }
				);

				Vue.onMounted(() => {
					appFavoriteBtn.value.status.active =
						getFavorites().includes(productId);

					// Инициализация с первым доступным цветом
					const slidesData = appShopDetailCardSlider.value.slidesData;
					if (
						slidesData.by_color &&
						Object.keys(slidesData.by_color).length > 0
					) {
						const firstColor = Object.keys(slidesData.by_color)[0];
						console.log('🎯 Инициализация с первым цветом:', firstColor);
						appShopDetailCardSlider.value.selectedColor = firstColor;
					}
				});

				return {
					getSelectedWhtLst,
					selectSlideImg,
					selectSlideImgByColor,
					addToWhtListMob,
					appShopDetailCardSlider: Vue.computed(() => ({
						...appShopDetailCardSlider.value,
						currentSlides: currentSlides.value,
						currentImage: currentImage.value,
					})),
					appFavoriteBtn,
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
					isOpen: false,
					promocode: '',
					message: '',
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
						const nonce = elPromocode.getAttribute('data-nonce');
						const ajaxUrl = elPromocode.getAttribute('data-ajax-url');

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

							jQuery.notify(
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
						// console.log(appCartStates.value.cart.qty_item);
						return appCartStates.value.cart.qty_item[cartItemKey] || 1;
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
							document.querySelectorAll('#cartTotalCount');
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
								appCartStates.value.cart.qty_item[cartItemKey] = newQty;

								// Также можно обновить DOM напрямую
								const qtyElement = document.querySelector(
									`[data-cart-key-id="${cartItemKey}"]`
								);
								if (qtyElement) {
									qtyElement.setAttribute('data-cart-qty', newQty);
									qtyElement.textContent = newQty;
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
						window.states.headMainNav.getSelectedWhtLst(productId);
					} catch (e) {
						return 0;
					}
				};

				//

				//

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
				};
			},
		}).mount(cartView);
		// }, 500)
		// cartPromocode();
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
					step: {
						selected: 'userinfo',
					},
					totalData: {
						customerInfo: {
							name: '----',
							phone: '----',
							email: '---',
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
						isOpen: false,
						isLoading: false,
						promocode: null,
						isError: false,
						message: null,
					},
				});

				// Реактивные данные формы
				const formData = Vue.reactive({
					firstName: '',
					lastName: '',
					middleName: '',
					phone: '',
					email: '',
				});

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
						appStateCheckout.value.totalData.customerInfo.name = fullName.value;
					} else if (field === 'phone') {
						appStateCheckout.value.totalData.customerInfo.phone = value;
					} else if (field === 'email') {
						appStateCheckout.value.totalData.customerInfo.email = value;
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

				const selectStepCheckout = (selectStep) => {
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
					isOpen: false,
					promocode: '',
					message: '',
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
						const nonce = elPromocode.getAttribute('data-nonce');
						const ajaxUrl = elPromocode.getAttribute('data-ajax-url');

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

				// Инициализ ация обработчиков формы
				const setupFormHandlers = () => {
					const formNode = checkoutForm.value;
					if (!formNode) return console.warn('Form node not found');

					const fields = {
						firstName: '#billing_first_name',
						lastName: '#billing_last_name',
						middleName: '#billing_middle_name',
						phone: '#billing_phone',
						email: '#billing_email',
					};

					const eventHandlers = {};

					for (const [field, selector] of Object.entries(fields)) {
						const element = formNode.querySelector(selector);
						if (element) {
							const debouncedHandler = debounce((event) => {
								handleFieldChange(field, event.target.value);
							}, 300);

							eventHandlers[field] = debouncedHandler;
							element.addEventListener('input', eventHandlers[field]);
						}
					}

					return eventHandlers;
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

				Vue.onMounted(() => {
					setupFormHandlers();

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

					const fields = {
						firstName: '#billing_first_name',
						lastName: '#billing_last_name',
						middleName: '#billing_middle_name',
						phone: '#billing_phone',
						email: '#billing_email',
					};

					for (const [field, selector] of Object.entries(fields)) {
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
						selectedCategories: [],
						promotionActive: false,
						minPrice: window.filterData.minPrice,
						maxPrice: window.filterData.maxPrice,
						selectedSize: '',
						selectedColors: [],
						selectedVwMatch: [],
						selectedCollections: [],
						selectedOccupations: [],
						selectedSubcategory: null,
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
				mounted() {
					// Initialize from URL parameters
					const params = new URLSearchParams(window.location.search);
					this.selectedCategories = params.get('subcategory')
						? params.get('subcategory').split(',')
						: [];
					this.promotionActive = params.get('promotion') === '1';
					this.minPrice =
						parseFloat(params.get('min_price')) || window.filterData.minPrice;
					this.maxPrice =
						parseFloat(params.get('max_price')) || window.filterData.maxPrice;
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
						// Initialize price slider
						const slider = document.getElementById('price-slider');
						noUiSlider.create(slider, {
							start: [this.minPrice, this.maxPrice],
							connect: true,
							range: {
								min: window.filterData.minPrice,
								max: window.filterData.maxPrice,
							},
						});
						slider.noUiSlider.on('update', (values) => {
							this.minPrice = parseFloat(values[0]);
							this.maxPrice = parseFloat(values[1]);
						});
					}, 200);

					this.filterData = window.filterData;
					// console.log(this.filterData);
				},
				methods: {
					toggleSection(section) {
						this.expandedSections = this.expandedSections.includes(section)
							? this.expandedSections.filter((s) => s !== section)
							: [...this.expandedSections, section];
					},
					applyFilters() {
						const params = new URLSearchParams();
						if (this.selectedCategories.length)
							params.set('subcategory', this.selectedCategories.join(','));
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

	// const addFavorite

	// Запуск всех компонентов
	singlePrdCard();
	singlePrdMob();
	headMainNav();
	popularCatCards();
	filterMbFilter();
	shopProductDetailCardImages();
	// cartPromocode();
	cartView();
	cartMbViewEvent();
	checkoutMbView();
	favBtnPrd();
	ctgFilterPc();
	//
	footerSubscribeForm();
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

	const menu = document.querySelector('.headerMainNavWrapper');

	changeNotifyPosition();

	window.addEventListener('scroll', changeNotifyPosition);
	window.addEventListener('resize', changeNotifyPosition);

	// Функция для изменения позиции врапера
	function changeNotifyPosition() {
		if (!menu) return;

		const offset = 18;

		let newPosition = menu.getBoundingClientRect().bottom;
		const scrollTop = window.scrollY || document.documentElement.scrollTop;
		const headerLogo = document.querySelector('.headerMbBarNavLogo');

		if (
			headerLogo &&
			window.innerWidth <= 768 &&
			headerLogo.classList.contains('__Hide') &&
			scrollTop < 70
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

document.addEventListener('DOMContentLoaded', function () {
	//
	console.debug('loaded!');
	//
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
