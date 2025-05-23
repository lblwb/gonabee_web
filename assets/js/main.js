const registerNotifyCustom = () => {
	jQuery.notify.addStyle('cartStyle', {
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
			renderBullet: function (index, className) {
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
				nextEl: '.sliderNavBtnNext',
				prevEl: '.sliderNavBtnPrev',
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
				renderBullet: function (index, className) {
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
			nextEl: '.sliderNavBtnNext',
			prevEl: '.sliderNavBtnPrev',
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
					return `<span class="${className} bullet"></span>`;
				},
			},

			navigation: {
				nextEl: '.sliderNavBtnNext',
				prevEl: '.sliderNavBtnPrev',
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

//
const swipperInit = async () => {
	console.debug('swipper init...');

	//
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
							size: 'L',
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
										// 2.1) содержимое: просто передаём текст
										'Модель добавлена в корзину!',
										{
											// 2.2) указываем наш стиль и класс base
											style: 'cartStyle',
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
											icon: imageUrl,
										}
									);
								} else {
									// $(document) — глобальный контейнер уведомлений
									jQuery.notify(
										// 2.1) содержимое: просто передаём текст
										'Модель удалена из корзины!',
										{
											// 2.2) указываем наш стиль и класс base
											style: 'cartStyle',
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
											icon: imageUrl,
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
										'Модель добавлена в избранное!',
										{
											// 2.2) указываем наш стиль и класс base
											style: 'cartStyle',
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
											icon: imageUrl,
										}
									);
								} else {
									jQuery.notify(
										// 2.1) содержимое: просто передаём текст
										'Модель удалена из избранного!',
										{
											// 2.2) указываем наш стиль и класс base
											style: 'cartStyle',
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
											icon: imageUrl,
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
						showAddToCartNotification(productId);
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
							size: 'L',
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
									addToFavorites(productId);

									jQuery.notify('Модель добавлена в избранное!', {
										style: 'cartStyle',
										className: 'base',
										showAnimation: 'slideDown',
										showDuration: 300,
										hideAnimation: 'slideUp',
										hideDuration: 200,
										autoHide: true,
										autoHideDelay: 2000,
										globalPosition: 'top center',
										icon: imageUrl,
									});

									console.log('Товар добавлен в избранное');
								} else {
									removeFromFavorites(productId);

									jQuery.notify('Модель удалена из избранное!', {
										style: 'cartStyle',
										className: 'base',
										showAnimation: 'slideDown',
										showDuration: 300,
										hideAnimation: 'slideUp',
										hideDuration: 200,
										autoHide: true,
										autoHideDelay: 2000,
										globalPosition: 'top center',
										icon: imageUrl,
									});

									console.log('Товар удален из избранного');
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
										// 2.1) содержимое: просто передаём текст
										'Модель добавлена в корзину!',
										{
											// 2.2) указываем наш стиль и класс base
											style: 'cartStyle',
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
											icon: imageUrl,
										}
									);
								} else {
									// $(document) — глобальный контейнер уведомлений
									jQuery.notify(
										// 2.1) содержимое: просто передаём текст
										'Модель удалена из корзины!',
										{
											// 2.2) указываем наш стиль и класс base
											style: 'cartStyle',
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
											icon: imageUrl,
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
										// 2.1) содержимое: просто передаём текст
										'Модель добавлена в избранное!',
										{
											// 2.2) указываем наш стиль и класс base
											style: 'cartStyle',
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
											icon: imageUrl,
										}
									);
								} else {
									// state.buttonText = 'Добавить в избранное';
									// state.message = 'Товар удален из избранного';
									// state.isFavorited = false;
									console.log('Товар удален из избранного');
									// $(document) — глобальный контейнер уведомлений
									jQuery.notify(
										// 2.1) содержимое: просто передаём текст
										'Модель удалена из избранное!',
										{
											// 2.2) указываем наш стиль и класс base
											style: 'cartStyle',
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
											icon: imageUrl,
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
						showAddToCartNotification(productId);
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

											jQuery.notify('Модель добавлена в избранное!', {
												style: 'cartStyle',
												className: 'base',
												showAnimation: 'slideDown',
												showDuration: 300,
												hideAnimation: 'slideUp',
												hideDuration: 200,
												autoHide: true,
												autoHideDelay: 2000,
												globalPosition: 'top center',
												icon: imageUrl,
											});

											console.log('Товар добавлен в избранное');
										} else {
											removeFromFavorites(productId);

											appFavoriteBtn.value.status.active = false;

											jQuery.notify('Модель удалена из избранное!', {
												style: 'cartStyle',
												className: 'base',
												showAnimation: 'slideDown',
												showDuration: 300,
												hideAnimation: 'slideUp',
												hideDuration: 200,
												autoHide: true,
												autoHideDelay: 2000,
												globalPosition: 'top center',
												icon: imageUrl,
											});

											console.log('Товар удален из избранного');
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
							// navigation.reload();
						};

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
						nav_search: {
							context: {
								queryText: '',
							},
							show: false,
							cat: {
								count: 1,
								result: {
									list: [],
								},
							},
							products: {
								count: 10,
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

									jQuery.notify('Модель добавлена в избранное!', {
										style: 'cartStyle',
										className: 'base',
										showAnimation: 'slideDown',
										showDuration: 300,
										hideAnimation: 'slideUp',
										hideDuration: 200,
										autoHide: true,
										autoHideDelay: 2000,
										globalPosition: 'top center',
										icon: imageUrl,
									});

									console.log('Товар добавлен в избранное');
								} else {
									removeFromFavorites(productId);

									jQuery.notify('Модель удалена из избранное!', {
										style: 'cartStyle',
										className: 'base',
										showAnimation: 'slideDown',
										showDuration: 300,
										hideAnimation: 'slideUp',
										hideDuration: 200,
										autoHide: true,
										autoHideDelay: 2000,
										globalPosition: 'top center',
										icon: imageUrl,
									});

									console.log('Товар удален из избранного');
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

				// Следим за изменением состояния меню и блокируем body
				Vue.watch(
					() =>
						appMainNav.value.mob.nav_menu.show ||
						appMainNav.value.mob.nav_search.show,
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
						appMainNav.value.mob.nav_search.show = true;
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
				const debug = false;

				const appMainFilter = Vue.ref({
					//appMainNav.mob.nav_filter.show
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

				// Следим за изменением состояния меню и блокируем body
				Vue.watch(
					() => appMainFilter.value.mob.nav_filter.show,
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

				Vue.onMounted(() => {});

				return {
					toggleMobFilter,
					appMainFilter,
				};
			},
		}).mount(el);
	};

	const shopProductDetailCardImages = () => {
		const el = document.getElementById('shopProductDetailCardImages');
		if (!el) return console.warn('#shopProductDetailCardImages не найден!');

		// const dataImages =  ? window.prdImagesSlides : [];

		window.states.shopPrdDetailCardImages = Vue.createApp({
			setup() {
				const debug = false;

				const productId = el.getAttribute('data-product-id');

				const appShopDetailCardSlider = Vue.ref({
					//appMainNav.mob.nav_filter.show
					slides: [...window.prdImagesSlides],
					select: {
						slide: {
							src:
								window.prdImagesSlides && window.prdImagesSlides.length > 0
									? window.prdImagesSlides[0]
									: null,
						},
					},
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
					appShopDetailCardSlider.value.select.slide.src = slideItem;
				};

				// const getSelectedWhtLst = (productId) => Vue.computed(() => !(getFavorites().includes(productId))).value;
				// const getSelectedWhtLst = (productId) => Vue.computed(() => window.states.headMainNav.get).value;
				// const getSelectedWhtLst = (productId) => Vue.computed(() => window.states.headMainNav.getSelectedWhtLst(productId).value).value;
				const getSelectedWhtLst = (productId) =>
					Vue.computed(() => !getFavorites().includes(productId)).value;

				const addToWhtListMob = (itemCard) => {
					appFavoriteBtn.value.status.active =
						!getFavorites().includes(productId);
					window.states.headMainNav.addToWhtListMob({
						...itemCard,
						...{ productId: productId },
					});
				};

				Vue.onMounted(() => {
					appFavoriteBtn.value.status.active =
						!getFavorites().includes(productId);
				});

				return {
					getSelectedWhtLst,
					selectSlideImg,
					addToWhtListMob,
					appShopDetailCardSlider,
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
									style: 'cartStyle',
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
									style: 'cartStyle',
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
								style: 'cartStyle',
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
								style: 'cartStyle',
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

				// Инициализация обработчиков формы
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

				Vue.onMounted(() => {
					setupFormHandlers();
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
						],
						selectedCategories: [],
						promotionActive: false,
						minPrice: window.filterData.minPrice,
						maxPrice: window.filterData.maxPrice,
						selectedSize: '',
						selectedColors: [],
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
					this.selectedColors = params.get('color')
						? params.get('color').split(',')
						: [];
					this.selectedCollections = params.get('collection')
						? params.get('collection').split(',')
						: [];
					this.selectedOccupations = params.get('occupation')
						? params.get('occupation').split(',')
						: [];
					this.onSale = params.get('on_sale') === '1';
					this.newCollection = params.get('new') === '1';
					this.trending = params.get('trending') === '1';

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
							params.set('color', this.selectedColors.join(','));
						if (this.selectedCollections.length)
							params.set('collection', this.selectedCollections.join(','));
						if (this.selectedOccupations.length)
							params.set('occupation', this.selectedOccupations.join(','));
						if (this.onSale) params.set('on_sale', '1');
						if (this.newCollection) params.set('new', '1');
						if (this.trending) params.set('trending', '1');
						window.location.href = '?' + params.toString();
					},
				},
			}).mount(elAppMainFilter);
		});
	};

	// const addFavorite

	// Запуск всех компонентов
	singlePrdCard();
	singlePrdMob();
	headMainNav();
	filterMbFilter();
	shopProductDetailCardImages();
	// cartPromocode();
	cartView();
	cartMbViewEvent();
	checkoutMbView();
	favBtnPrd();
	ctgFilterPc();
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

const fixedScrollNavMob = () => {
	const header = document.querySelector('#headerMainNav');
	const headerMain = document.querySelector('.headerMain');
	const headerLogo = document.querySelector('.headerMbBarNavLogo');

	// let isFixed = false;
	let headerHeight = header.offsetHeight;

	const updateHeaderHeight = () => {
		headerHeight = header.offsetHeight;
		// wrapper.style.height = `${headerHeight}px`;
	};

	const setHeaderOffset = (isClear = false) => {
		console.log(isClear);
		if (isClear) {
			document.body.style.setProperty('margin-top', '', 'important');
			return;
		}

		let value = headerHeight;

		if (
			window.states &&
			window.states.headMainNav &&
			window.states.headMainNav.appMainNav
		) {
			if (window.innerWidth <= 768) {
				value += headerLogo.offsetHeight;
			}
		}

		document.body.style.setProperty('margin-top', `${value}px`, 'important');
	};

	const updateNavPosition = () => {
		const scrollTop = window.scrollY || document.documentElement.scrollTop;

		if (scrollTop >= 70) {
			updateHeaderHeight();
			header.style.position = 'fixed';
			header.style.top = '0';
			setHeaderOffset();
			isFixed = true;
		} else if (scrollTop < 70) {
			header.style.position = 'unset';
			setHeaderOffset(true);
			isFixed = false;
		}
	};

	window.addEventListener('scroll', updateNavPosition);
	window.addEventListener('resize', () => {
		updateHeaderHeight();
		updateNavPosition();
	});
	// window.addEventListener("DOMContentLoaded", () => {
	//     // updateHeaderHeight();
	//     updateNavPosition();
	// });

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
});

uiShopAppVibe();
