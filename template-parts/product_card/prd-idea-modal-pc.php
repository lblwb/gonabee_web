<?php
$modal_type = isset($args['modal-type']) ? $args['modal-type'] : 'idea';
?>
<div class="detailIdeaModal"
    id="prdModal"
    data-action-fetch-prd="prd_info"
    data-action-add-to-cart="add_to_cart_mb"
    data-action-update-prd="update_prd_cart"
    data-nonce-fetch-prd="<?= wp_create_nonce('prd_info_nonce') ?>"
    data-nonce-add-to-cart="<?= wp_create_nonce('add_to_cart_mb') ?>"
    data-nonce-update-prd="<?= wp_create_nonce('update_prd_card_nonce') ?>"
    data-ajax_url="<?php echo esc_url(admin_url('admin-ajax.php')); ?>"
    data-modal-type="<?= $modal_type ?>"
    v-cloak>

    <!-- Десктоп -->
    <div class="detailIdeaOverlay" v-show="isShow && isDesktop" @click.self="closeModal">
        <div class="detailIdeaModalWrapper" @click.self="closeModal">
            <div class="detailIdeaModalBlock" style="position: relative;">
                <!-- КНОПКА ЗАКРЫТИЯ -->
                <button
                    @click="closeModal"
                    style="position: absolute; top: 15px; right: 15px; background: none; border: none; font-size: 24px; cursor: pointer; opacity: 0.6">
                    &times;
                </button>
                <div class="detailIdeaModalLoader" v-if="isLoad">
                    <svg fill="#f0c224" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <rect x="1" y="1" width="7.33" height="7.33">
                            <animate id="spinner_oJFS" begin="0;spinner_5T1J.end+0.2s" attributeName="x" dur="0.6s" values="1;4;1" />
                            <animate begin="0;spinner_5T1J.end+0.2s" attributeName="y" dur="0.6s" values="1;4;1" />
                            <animate begin="0;spinner_5T1J.end+0.2s" attributeName="width" dur="0.6s" values="7.33;1.33;7.33" />
                            <animate begin="0;spinner_5T1J.end+0.2s" attributeName="height" dur="0.6s" values="7.33;1.33;7.33" />
                        </rect>
                        <rect x="8.33" y="1" width="7.33" height="7.33">
                            <animate begin="spinner_oJFS.begin+0.1s" attributeName="x" dur="0.6s" values="8.33;11.33;8.33" />
                            <animate begin="spinner_oJFS.begin+0.1s" attributeName="y" dur="0.6s" values="1;4;1" />
                            <animate begin="spinner_oJFS.begin+0.1s" attributeName="width" dur="0.6s" values="7.33;1.33;7.33" />
                            <animate begin="spinner_oJFS.begin+0.1s" attributeName="height" dur="0.6s" values="7.33;1.33;7.33" />
                        </rect>
                        <rect x="1" y="8.33" width="7.33" height="7.33">
                            <animate begin="spinner_oJFS.begin+0.1s" attributeName="x" dur="0.6s" values="1;4;1" />
                            <animate begin="spinner_oJFS.begin+0.1s" attributeName="y" dur="0.6s" values="8.33;11.33;8.33" />
                            <animate begin="spinner_oJFS.begin+0.1s" attributeName="width" dur="0.6s" values="7.33;1.33;7.33" />
                            <animate begin="spinner_oJFS.begin+0.1s" attributeName="height" dur="0.6s" values="7.33;1.33;7.33" />
                        </rect>
                        <rect x="15.66" y="1" width="7.33" height="7.33">
                            <animate begin="spinner_oJFS.begin+0.2s" attributeName="x" dur="0.6s" values="15.66;18.66;15.66" />
                            <animate begin="spinner_oJFS.begin+0.2s" attributeName="y" dur="0.6s" values="1;4;1" />
                            <animate begin="spinner_oJFS.begin+0.2s" attributeName="width" dur="0.6s" values="7.33;1.33;7.33" />
                            <animate begin="spinner_oJFS.begin+0.2s" attributeName="height" dur="0.6s" values="7.33;1.33;7.33" />
                        </rect>
                        <rect x="8.33" y="8.33" width="7.33" height="7.33">
                            <animate begin="spinner_oJFS.begin+0.2s" attributeName="x" dur="0.6s" values="8.33;11.33;8.33" />
                            <animate begin="spinner_oJFS.begin+0.2s" attributeName="y" dur="0.6s" values="8.33;11.33;8.33" />
                            <animate begin="spinner_oJFS.begin+0.2s" attributeName="width" dur="0.6s" values="7.33;1.33;7.33" />
                            <animate begin="spinner_oJFS.begin+0.2s" attributeName="height" dur="0.6s" values="7.33;1.33;7.33" />
                        </rect>
                        <rect x="1" y="15.66" width="7.33" height="7.33">
                            <animate begin="spinner_oJFS.begin+0.2s" attributeName="x" dur="0.6s" values="1;4;1" />
                            <animate begin="spinner_oJFS.begin+0.2s" attributeName="y" dur="0.6s" values="15.66;18.66;15.66" />
                            <animate begin="spinner_oJFS.begin+0.2s" attributeName="width" dur="0.6s" values="7.33;1.33;7.33" />
                            <animate begin="spinner_oJFS.begin+0.2s" attributeName="height" dur="0.6s" values="7.33;1.33;7.33" />
                        </rect>
                        <rect x="15.66" y="8.33" width="7.33" height="7.33">
                            <animate begin="spinner_oJFS.begin+0.3s" attributeName="x" dur="0.6s" values="15.66;18.66;15.66" />
                            <animate begin="spinner_oJFS.begin+0.3s" attributeName="y" dur="0.6s" values="8.33;11.33;8.33" />
                            <animate begin="spinner_oJFS.begin+0.3s" attributeName="width" dur="0.6s" values="7.33;1.33;7.33" />
                            <animate begin="spinner_oJFS.begin+0.3s" attributeName="height" dur="0.6s" values="7.33;1.33;7.33" />
                        </rect>
                        <rect x="8.33" y="15.66" width="7.33" height="7.33">
                            <animate begin="spinner_oJFS.begin+0.3s" attributeName="x" dur="0.6s" values="8.33;11.33;8.33" />
                            <animate begin="spinner_oJFS.begin+0.3s" attributeName="y" dur="0.6s" values="15.66;18.66;15.66" />
                            <animate begin="spinner_oJFS.begin+0.3s" attributeName="width" dur="0.6s" values="7.33;1.33;7.33" />
                            <animate begin="spinner_oJFS.begin+0.3s" attributeName="height" dur="0.6s" values="7.33;1.33;7.33" />
                        </rect>
                        <rect x="15.66" y="15.66" width="7.33" height="7.33">
                            <animate id="spinner_5T1J" begin="spinner_oJFS.begin+0.4s" attributeName="x" dur="0.6s" values="15.66;18.66;15.66" />
                            <animate begin="spinner_oJFS.begin+0.4s" attributeName="y" dur="0.6s" values="15.66;18.66;15.66" />
                            <animate begin="spinner_oJFS.begin+0.4s" attributeName="width" dur="0.6s" values="7.33;1.33;7.33" />
                            <animate begin="spinner_oJFS.begin+0.4s" attributeName="height" dur="0.6s" values="7.33;1.33;7.33" />
                        </rect>
                    </svg>
                </div>
                <div class="detailModalBlockWrapper">
                    <!-- <pre style="background:#222;color:#fff;padding:10px;font-size:12px;">
                    {{ addToCartButtonText }}
                    </pre>
                    <pre style="background:#222;color:#fff;padding:10px;font-size:12px;">
                    {{ addToCartButtonText.value }}
                    </pre>
                    <pre style="background:#222;color:#fff;padding:10px;font-size:12px;">
                    {{ isAddToCartDisabled }}
                    </pre>
                    <pre style="background:#222;color:#fff;padding:10px;font-size:12px;">
                    {{ isAddToCartDisabled.value }}
                    </pre> -->
                    <!-- Слайдер -->
                    <div class="shopProductDetailCardImages">
                        <div class="shopProductDetailCardImagesWrapper" style="display: flex;gap: 57px;">
                            <div class="shopProductDetailCardImagesThumb">

                                <div class="cardImagesThumbSliderNavBlock">
                                    <button class="cardImagesThumbSliderNavPrev cardImagesThumbSliderNavigation"
                                        @click="prevSlide">
                                        <svg width="12" height="8" viewBox="0 0 12 8" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M6.00143 3.02379L1.8766 7.14856L0.698096 5.97005L6.00143 0.666709L11.3047 5.97005L10.1262 7.14856L6.00143 3.02379Z"
                                                fill="#252525" />
                                        </svg>
                                    </button>
                                </div>

                                <div class="cardImagesThumbSlider">

                                    <div class="cardImagesThumbWrapper">
                                        <!-- Слайды миниатюр -->
                                        <div class="cardImagesThumbItem"
                                            v-for="(slideItem, index) in appShopDetailCardSlider.currentSlides"
                                            :key="slideItem"
                                            :class="{ '__Active': index === appShopDetailCardSlider.currentIndex }"
                                            @click="selectSlideByIndex(index)">
                                            <img :src="slideItem" alt="product thumb" />
                                        </div>
                                    </div>
                                </div>

                                <div class="cardImagesThumbSliderNavBlock">
                                    <button class="cardImagesThumbSliderNavNext cardImagesThumbSliderNavigation"
                                        @click="nextSlide">
                                        <svg width="12" height="8" viewBox="0 0 12 8" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M5.99857 4.97621L10.1234 0.851442L11.3019 2.02995L5.99857 7.33329L0.695312 2.02995L1.87382 0.851441L5.99857 4.97621Z"
                                                fill="#252525" />
                                        </svg>
                                    </button>
                                </div>

                            </div>

                            <div class="shopProductDetailCardImagesMain" style="position: relative">
                                <div class="cardImagesMainTop">
                                    <div class="cardImagesMainHeading">
                                        <div class="cardImagesMainHeadingFav">
                                            <div class="previewSliderItemWhiteList"
                                                @click="addToWhtListMob()">
                                                <div class="whiteListBtn" style="background: #FFFFFF;">
                                                    <div class="whiteListBtnIcon"
                                                        v-if="appFavoriteBtn !== null && !appFavoriteBtn.status.active">
                                                        <svg width="30" height="30" viewBox="0 0 30 30" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <g clip-path="url(#clip0_1365_42)">
                                                                <mask id="mask0_1365_42" style="mask-type:luminance"
                                                                    maskUnits="userSpaceOnUse" x="0" y="0" width="30"
                                                                    height="30">
                                                                    <path d="M30 0H0V30H30V0Z" fill="white" />
                                                                </mask>
                                                                <g mask="url(#mask0_1365_42)">
                                                                    <path
                                                                        d="M30 15C30 6.71573 23.2843 0 15 0C6.71573 0 0 6.71573 0 15C0 23.2843 6.71573 30 15 30C23.2843 30 30 23.2843 30 15Z"
                                                                        fill="white" />
                                                                    <path
                                                                        d="M14.9994 10.019C16.5654 8.61333 18.9854 8.66 20.4938 10.1716C22.0023 11.6831 22.0541 14.0913 20.6511 15.662L14.9986 21.3233L9.34629 15.662C7.9434 14.0913 7.99584 11.6793 9.50361 10.1716C11.0131 8.6621 13.4288 8.61125 14.9994 10.019ZM19.5501 11.1134C18.5507 10.112 16.9371 10.0713 15.89 11.0112L15 11.8102L14.1094 11.0119C13.0593 10.0706 11.4487 10.112 10.4464 11.1144C9.45325 12.1075 9.40339 13.6982 10.3186 14.7488L14.9986 19.4362L19.6788 14.7488C20.5944 13.6978 20.5447 12.1102 19.5501 11.1134Z"
                                                                        fill="#1F1F1F" />
                                                                </g>
                                                            </g>
                                                            <defs>
                                                                <clipPath id="clip0_1365_42">
                                                                    <rect width="30" height="30" fill="white" />
                                                                </clipPath>
                                                            </defs>
                                                        </svg>
                                                    </div>
                                                    <div class="whiteListBtnIcon" v-else>
                                                        <svg width="30" height="30" viewBox="0 0 30 30" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <rect width="30" height="30" rx="15" fill="white" />
                                                            <path
                                                                d="M14.9994 10.019C16.5654 8.61333 18.9854 8.66 20.4938 10.1716C22.0022 11.6831 22.054 14.0913 20.6511 15.662L14.9986 21.3233L9.34628 15.662C7.9434 14.0913 7.99584 11.6793 9.5036 10.1716C11.0131 8.6621 13.4288 8.61125 14.9994 10.019Z"
                                                                fill="#CE1B19" />
                                                        </svg>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Основное изображение -->
                                <div class="cardImagesMainImg">
                                    <img :src="appShopDetailCardSlider.currentImage"
                                        :key="appShopDetailCardSlider.currentImage" alt="product main image" />
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- Слайдер -->

                    <div class="shopProductDetailCardInfoWrap">
                        <!-- Info -->
                        <div class="shopProductDetailCardInfo" style="flex: 1;">

                            <div class="detailCardInfoHeading">
                                <div class="detailCardInfoHeadingWrapper">
                                    <div class="detailCardInfoHeadingTitle">
                                        {{ data.prdName }}
                                    </div>
                                </div>
                                <div class="cardInfoHeadingPrice" style="">
                                    <div class="cardInfoHeadingPriceWrapper">
                                        <div class="cardInfoHeadingPriceFull" v-html="data.prdPriceFull"></div>
                                        <div class="cardInfoHeadingPriceSalePerc" v-if="data.prdPriceSale" v-html="data.prdPriceSale"></div>
                                    </div>
                                </div>
                                <div class="detailCardHeadingSeparate" v-if="data.prdColors && data.prdColors.length !== 0 ">
                                    <div class="detailCardInfoHeadingColor">

                                        <div class="infoHeadingColorHead">
                                            <div class="infoHeadingColorHeadTitle" style="margin-bottom: 16px">
                                                Цвет:
                                            </div>
                                        </div>

                                        <div class="itemBlockHeadingSelColor">
                                            <div class="colorBox"
                                                v-for="(color, index) in data.prdColors"
                                                :class="{ '__Active': appShop.select.color === color.color_slug }"
                                                @click="selectCartColor(color.color_slug)">
                                                <!-- Иконка для цвета "none" -->
                                                <div class="color-none-icon"
                                                    v-if="color.color_slug === 'none'"
                                                    :title="color.color_name">
                                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <circle cx="10" cy="10" r="8" stroke="#ccc" stroke-width="1.5"
                                                            fill="white" />
                                                        <line x1="4" y1="4" x2="16" y2="16" stroke="#e74c3c"
                                                            stroke-width="2" />
                                                    </svg>
                                                </div>
                                                <div class="color-circle"
                                                    v-else
                                                    :title="color.color_name"
                                                    :data-color-id="color.color_slug"
                                                    :style="{ backgroundColor: color.color_code }"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="detailCardInfoHeadingAtr" style="">
                                    <div class="detailCardInfoHeadingSizes" v-if="data.prdSizes">
                                        <div class="size-selector">
                                            <div class="size-selector__header">
                                                <span class="size-selector__title">Размер</span>
                                                <a href="#size-chart" class="size-selector__link">Размерная сетка</a>
                                            </div>
                                            <div class="size-selector__options">
                                                <label class="size-selector__option"
                                                    @click="selectCartSize(size)"
                                                    v-for="size in data.prdSizes"
                                                    :class="{__Active: appShop.select.size === size}">

                                                    <input type="radio" name="attribute_pa_size"
                                                        :value="size" required>
                                                    <span>{{ size }}</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="detailCardInfoHeadingAddToWrapper">
                                    <div class="detailCardInfoHeadingAddToCart">
                                        <div class="addToCardBtn" :class="{ 
                                                '__Disabled': isAddToCartDisabled,
                                                'disabled': !data.prdIsStock
                                            }"
                                            @click="btnHandler(0, { imageUrl: data.prdThumbnail })">
                                            <div class="addToCardBtnHeading">
                                                <div class="addToCardBtnHeadingTitle">
                                                    {{ content.btn }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="detailCardHeadingSeparateBottom"></div>
                                </div>
                                <div class="detailCardInfoHeadingDesc" v-if="data.prdDescription">
                                    {{ data.prdDescription }}
                                </div>
                                <div class="detailCardHeadingSeparateBottom" v-if="data.prdDescription"></div>
                                <div class="detailCardHeadingSeparateBottom" v-if="data.prdComposition && data.prdDelivery">
                                    <div class="footerMainMob__accordion" v-if="data.prdComposition && data.prdDelivery">
                                        <div class="footerMainMob__section" style="border-bottom: none; margin-bottom: 0;" v-if="data.prdComposition">
                                            <button class="footerMainMob__toggle detailCardHeadingLink" data-index="0"
                                                style="padding: 0;">
                                                Состав и уход
                                                <span class="footer__icon">
                                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M9.99948 10.9736L5.87464 6.84888L4.69614 8.02739L9.99948 13.3307L15.3027 8.02739L14.1242 6.84888L9.99948 10.9736Z"
                                                            fill="#000" />
                                                    </svg>
                                                </span>
                                            </button>
                                            <div class="footerMainMob__content"
                                                style="max-height: 0px;margin-bottom: 20px;padding: 0;color: #444;">
                                                {{ data.prdComposition }}
                                            </div>
                                        </div>

                                        <div class="footerMainMob__section" style="border-bottom: none; margin-bottom: 0;" v-if="data.prdDelivery">
                                            <button
                                                class="footerMainMob__toggle detailCardHeadingLink detailCardHeadingLinkPrd"
                                                data-index="0" style="padding: 0;">
                                                Доставка и оплата
                                                <span class="footer__icon">
                                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M9.99948 10.9736L5.87464 6.84888L4.69614 8.02739L9.99948 13.3307L15.3027 8.02739L14.1242 6.84888L9.99948 10.9736Z"
                                                            fill="#000" />
                                                    </svg>
                                                </span>
                                            </button>
                                            <div class="footerMainMob__content"
                                                style="max-height: 0px;margin-bottom: 20px;padding: 0;color: #444;">
                                                {{ data.prdDelivery }}
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--                                            <div class="cardImagesMainImg">-->
                <!--                                                <transition name="fade">-->
                <!--                                                    <img :src="appShopDetailCardSlider.select.slide.src"-->
                <!--                                                         v-show="appShopDetailCardSlider.select.slide.src"-->
                <!--                                                         :key="appShopDetailCardSlider.select.slide.src"/>-->
                <!--                                                </transition>-->
                <!--                                            </div>-->

            </div>
        </div>
    </div>

    <!-- Мобилка -->
    <div class="detailIdeaModalMb" v-show="isShow && isMobile">
        <div class="detailIdeaMbOverlay" @click.self="closeModal"
            style="position: fixed; top: 0; right: 0; left: 0; bottom: 0; z-index: 300; background: #fff;">
            <div class="detailIdeaMbHeading" style="padding: 16px 10px; background: #222222; position: relative; z-index: 300;">
                <div class="detailIdeaMbHeadingWrapper"
                    style="display: flex; justify-content: space-between; align-items: center;">

                    <div class="detailIdeaMbHeadingBack" @click="closeModal">
                        <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M9.47759 10.4995L13.8086 6.16838L12.5712 4.93095L7.00265 10.4995L12.5712 16.0679L13.8086 14.8304L9.47759 10.4995Z"
                                fill="#F9F9F9" />
                        </svg>
                    </div>
                    <div class="detailIdeaMbHeadingTitle"
                        style="font-family: 'Sofia Sans',sans-serif;font-style: normal;font-weight: 600;font-size: 20px;line-height: 125%;text-align: center;font-feature-settings: 'ss01' on;color: #F9F9F9;">
                        {{ content.title }}
                    </div>
                    <div class="detailIdeaMbHeadingRow"></div>
                </div>
            </div>

            <div class="detailIdeaModalLoader" v-if="isLoad">
                <svg fill="#f0c224" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <rect x="1" y="1" width="7.33" height="7.33">
                        <animate id="spinner_oJFS" begin="0;spinner_5T1J.end+0.2s" attributeName="x" dur="0.6s" values="1;4;1" />
                        <animate begin="0;spinner_5T1J.end+0.2s" attributeName="y" dur="0.6s" values="1;4;1" />
                        <animate begin="0;spinner_5T1J.end+0.2s" attributeName="width" dur="0.6s" values="7.33;1.33;7.33" />
                        <animate begin="0;spinner_5T1J.end+0.2s" attributeName="height" dur="0.6s" values="7.33;1.33;7.33" />
                    </rect>
                    <rect x="8.33" y="1" width="7.33" height="7.33">
                        <animate begin="spinner_oJFS.begin+0.1s" attributeName="x" dur="0.6s" values="8.33;11.33;8.33" />
                        <animate begin="spinner_oJFS.begin+0.1s" attributeName="y" dur="0.6s" values="1;4;1" />
                        <animate begin="spinner_oJFS.begin+0.1s" attributeName="width" dur="0.6s" values="7.33;1.33;7.33" />
                        <animate begin="spinner_oJFS.begin+0.1s" attributeName="height" dur="0.6s" values="7.33;1.33;7.33" />
                    </rect>
                    <rect x="1" y="8.33" width="7.33" height="7.33">
                        <animate begin="spinner_oJFS.begin+0.1s" attributeName="x" dur="0.6s" values="1;4;1" />
                        <animate begin="spinner_oJFS.begin+0.1s" attributeName="y" dur="0.6s" values="8.33;11.33;8.33" />
                        <animate begin="spinner_oJFS.begin+0.1s" attributeName="width" dur="0.6s" values="7.33;1.33;7.33" />
                        <animate begin="spinner_oJFS.begin+0.1s" attributeName="height" dur="0.6s" values="7.33;1.33;7.33" />
                    </rect>
                    <rect x="15.66" y="1" width="7.33" height="7.33">
                        <animate begin="spinner_oJFS.begin+0.2s" attributeName="x" dur="0.6s" values="15.66;18.66;15.66" />
                        <animate begin="spinner_oJFS.begin+0.2s" attributeName="y" dur="0.6s" values="1;4;1" />
                        <animate begin="spinner_oJFS.begin+0.2s" attributeName="width" dur="0.6s" values="7.33;1.33;7.33" />
                        <animate begin="spinner_oJFS.begin+0.2s" attributeName="height" dur="0.6s" values="7.33;1.33;7.33" />
                    </rect>
                    <rect x="8.33" y="8.33" width="7.33" height="7.33">
                        <animate begin="spinner_oJFS.begin+0.2s" attributeName="x" dur="0.6s" values="8.33;11.33;8.33" />
                        <animate begin="spinner_oJFS.begin+0.2s" attributeName="y" dur="0.6s" values="8.33;11.33;8.33" />
                        <animate begin="spinner_oJFS.begin+0.2s" attributeName="width" dur="0.6s" values="7.33;1.33;7.33" />
                        <animate begin="spinner_oJFS.begin+0.2s" attributeName="height" dur="0.6s" values="7.33;1.33;7.33" />
                    </rect>
                    <rect x="1" y="15.66" width="7.33" height="7.33">
                        <animate begin="spinner_oJFS.begin+0.2s" attributeName="x" dur="0.6s" values="1;4;1" />
                        <animate begin="spinner_oJFS.begin+0.2s" attributeName="y" dur="0.6s" values="15.66;18.66;15.66" />
                        <animate begin="spinner_oJFS.begin+0.2s" attributeName="width" dur="0.6s" values="7.33;1.33;7.33" />
                        <animate begin="spinner_oJFS.begin+0.2s" attributeName="height" dur="0.6s" values="7.33;1.33;7.33" />
                    </rect>
                    <rect x="15.66" y="8.33" width="7.33" height="7.33">
                        <animate begin="spinner_oJFS.begin+0.3s" attributeName="x" dur="0.6s" values="15.66;18.66;15.66" />
                        <animate begin="spinner_oJFS.begin+0.3s" attributeName="y" dur="0.6s" values="8.33;11.33;8.33" />
                        <animate begin="spinner_oJFS.begin+0.3s" attributeName="width" dur="0.6s" values="7.33;1.33;7.33" />
                        <animate begin="spinner_oJFS.begin+0.3s" attributeName="height" dur="0.6s" values="7.33;1.33;7.33" />
                    </rect>
                    <rect x="8.33" y="15.66" width="7.33" height="7.33">
                        <animate begin="spinner_oJFS.begin+0.3s" attributeName="x" dur="0.6s" values="8.33;11.33;8.33" />
                        <animate begin="spinner_oJFS.begin+0.3s" attributeName="y" dur="0.6s" values="15.66;18.66;15.66" />
                        <animate begin="spinner_oJFS.begin+0.3s" attributeName="width" dur="0.6s" values="7.33;1.33;7.33" />
                        <animate begin="spinner_oJFS.begin+0.3s" attributeName="height" dur="0.6s" values="7.33;1.33;7.33" />
                    </rect>
                    <rect x="15.66" y="15.66" width="7.33" height="7.33">
                        <animate id="spinner_5T1J" begin="spinner_oJFS.begin+0.4s" attributeName="x" dur="0.6s" values="15.66;18.66;15.66" />
                        <animate begin="spinner_oJFS.begin+0.4s" attributeName="y" dur="0.6s" values="15.66;18.66;15.66" />
                        <animate begin="spinner_oJFS.begin+0.4s" attributeName="width" dur="0.6s" values="7.33;1.33;7.33" />
                        <animate begin="spinner_oJFS.begin+0.4s" attributeName="height" dur="0.6s" values="7.33;1.33;7.33" />
                    </rect>
                </svg>
            </div>

            <div class="detailIdeaMbBody" style="max-height: 90vh;overflow-y: auto;padding-bottom: 140px;">
                <!-- Карточка -->
                <div class="shopProductDetailCardImagesMainMob">
                    <div class="detailCardImagesMainMobWrapper">
                        <div class="mainMobSlider swiper" id="productMobileSlider">
                            <div class="mainMobSliderWrapper swiper-wrapper">
                                <div class="mainMobSliderItemSlide swiper-slide"
                                    v-for="(slideItem, index) in appShopDetailCardSlider.currentSlides"
                                    :key="slideItem">
                                    <img :src="slideItem" alt="product gallery image" />
                                </div>
                            </div>
                            <div class="bottomPaginate"></div>
                        </div>

                        <div class="mainMobTop"
                            style="position: absolute; top: 20px; right: 10px; left: 10px; z-index: 10">
                            <div class="mainMobTopWrapper"
                                style="display: flex; align-items: center; justify-content: space-between;">
                                <div class="mainMobTopBack"
                                    @click="closeModal">
                                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <rect width="40" height="40" rx="20" fill="white" />
                                        <path
                                            d="M19.0266 19.9995L23.1514 15.8746L21.9729 14.6961L16.6695 19.9995L21.9729 25.3027L23.1514 24.1242L19.0266 19.9995Z"
                                            fill="#252525" />
                                    </svg>
                                </div>

                                <div class="mainMobTopWhtAddWrp">
                                    <div class="mainMobTopWhtAdd">
                                        <a @click="addToWhtListMob({imageUrl: data.prdThumbnail})">
                                            <div class="mainMobTopWhtAddBtn"
                                                v-if="appFavoriteBtn !== null && !appFavoriteBtn.status.active">
                                                <svg width="40" height="40" viewBox="0 0 40 40" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <rect width="40" height="40" rx="20" fill="white" />
                                                    <path
                                                        d="M19.9982 13.7738C21.9557 12.0167 24.9807 12.075 26.8662 13.9645C28.7518 15.8539 28.8166 18.8642 27.0629 20.8275L19.9973 27.9042L12.9319 20.8275C11.1783 18.8642 11.2438 15.8492 13.1285 13.9645C15.0154 12.0776 18.0351 12.0141 19.9982 13.7738ZM25.6866 15.1418C24.4373 13.89 22.4204 13.8392 21.1116 15.0141L19.999 16.0127L18.8858 15.0148C17.5732 13.8383 15.5599 13.8901 14.307 15.143C13.0656 16.3844 13.0033 18.3728 14.1473 19.686L19.9973 25.5453L25.8475 19.686C26.992 18.3723 26.9299 16.3877 25.6866 15.1418Z"
                                                        fill="#1F1F1F" />
                                                </svg>
                                            </div>
                                            <div class="mainMobTopWhtAddBtn" v-else>
                                                <svg width="40" height="40" viewBox="0 0 40 40" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <rect width="40" height="40" rx="20" fill="white" />
                                                    <path
                                                        d="M19.9982 13.7738C21.9557 12.0167 24.9807 12.075 26.8662 13.9645C28.7518 15.8539 28.8166 18.8642 27.0629 20.8275L19.9973 27.9042L12.9319 20.8275C11.1783 18.8642 11.2438 15.8492 13.1285 13.9645C15.0154 12.0776 18.0351 12.0141 19.9982 13.7738ZM25.6866 15.1418C24.4373 13.89 22.4204 13.8392 21.1116 15.0141L19.999 16.0127L18.8858 15.0148C17.5732 13.8383 15.5599 13.8901 14.307 15.143C13.0656 16.3844 13.0033 18.3728 14.1473 19.686L19.9973 25.5453L25.8475 19.686C26.992 18.3723 26.9299 16.3877 25.6866 15.1418Z"
                                                        fill="#CE1B19" />
                                                    <path
                                                        d="M25.6866 15.1418C24.4373 13.89 22.4204 13.8392 21.1116 15.0141L19.999 16.0127L18.8858 15.0148C17.5732 13.8383 15.5599 13.8901 14.307 15.143C13.0656 16.3844 13.0033 18.3728 14.1473 19.686L19.9973 25.5453L25.8475 19.686C26.992 18.3723 26.9299 16.3877 25.6866 15.1418Z"
                                                        fill="#CE1B19" />
                                                </svg>
                                            </div>
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="shopProductDetailCardInfo" style="flex: 1; padding: 10px;">
                            <div class="detailCardInfoHeading">
                                <div class="detailCardInfoHeadingWrapper">
                                    <div class="detailCardInfoHeadingTitle" v-html="data.prdTitle">
                                    </div>
                                </div>
                                <div class="cardInfoHeadingPrice" style="">
                                    <div class="cardInfoHeadingPriceWrapper">
                                        <div class="cardInfoHeadingPriceFull" v-html="data.prdPriceFull"></div>
                                    </div>
                                </div>
                                <div class="detailCardHeadingSeparate">
                                    <div class="detailCardInfoHeadingColor" v-if="data.prdColors">
                                        <div class="infoHeadingColorHead">
                                            <div class="infoHeadingColorHeadTitle" style="margin-bottom: 16px">
                                                Цвет:
                                            </div>
                                        </div>

                                        <div class="itemBlockHeadingSelColor">
                                            <div class="colorBox"
                                                v-for="(color, index) in data.prdColors"
                                                :class="{ '__Active': appShop.select.color === color.color_slug }"
                                                @click="selectCartColor(color.color_slug, color.color_id)">
                                                <!-- Иконка для цвета "none" -->
                                                <div class="color-none-icon"
                                                    v-if="color.color_slug === 'none'"
                                                    :title="color.color_name">
                                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <circle cx="10" cy="10" r="8" stroke="#ccc" stroke-width="1.5"
                                                            fill="white" />
                                                        <line x1="4" y1="4" x2="16" y2="16" stroke="#e74c3c"
                                                            stroke-width="2" />
                                                    </svg>
                                                </div>
                                                <div class="color-circle"
                                                    v-else
                                                    :title="color.color_name"
                                                    :data-color-id="color.color_id"
                                                    :data-color-slug="color.color_slug"
                                                    :style="{ backgroundColor: color.color_code }"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="detailCardInfoHeadingAtr" style="">
                                <div class="detailCardInfoHeadingSizes" v-if="data.prdSizes">
                                    <div class="size-selector">
                                        <div class="size-selector__header">
                                            <span class="size-selector__title">Размер</span>
                                            <a href="#size-chart" class="size-selector__link">Размерная сетка</a>
                                        </div>
                                        <div class="size-selector__options">
                                            <label class="size-selector__option"
                                                @click="selectCartSize(size)"
                                                v-for="size in data.prdSizes"
                                                :class="{__Active: appShop.select.size === size}">

                                                <input type="radio" name="attribute_pa_size"
                                                    :value="size" required>
                                                <span>{{ size }}</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="detailCardInfoHeadingAddToWrapper">
                                <div class="detailCardInfoHeadingAddToCart">
                                    <div class="addToCardBtn" href="#"
                                        :class="{ 
                                            '__Disabled': isAddToCartDisabled,
                                            'disabled': !data.prdIsStock
                                        }"
                                        @click="btnHandler($event, {productId: data.prdId, imageUrl:data.prdThumbnail })">
                                        <div class="addToCardBtnHeading">
                                            <div class="addToCardBtnHeadingTitle">
                                                {{ content.btn }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="detailCardHeadingSeparateBottom"></div>
                            </div>

                            <div class="detailCardInfoHeadingDscWrapper" v-if="data.prdDescription">
                                <div class="detailCardInfoHeadingDesc">
                                    {{ data.prdDescription }}
                                </div>
                                <div class="detailCardHeadingSeparateBottom"></div>
                            </div>
                        </div>

                        <div class="detailCardInfoBody">
                            <div class="detailCardInfoBodyWrapper">
                                <div class="detailCardInfoBodyAttr">
                                    <div class="detailCardInfoBodyAttrWrapper">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="detailCardInfoFooter">
                            <div class="detailCardInfoFooterWrapper">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- BAR -->
                <div class="shopProductDetailMobFixBar" :data-product-id="data.prdId">
                    <div class="detailMobFixBarWrapper">

                        <div class="selSizeProductBlock" style="padding: 10px;border-bottom: solid 1px #eee;"
                            v-show="appShop.mob.size.show">
                            <div class="selSizeProductBlockHeading">
                                <div class="selSizeProductBlockHeadingTitle"
                                    style="font-family: Sofia Sans;font-weight: 600;font-size: 20px;line-height: 125%;letter-spacing: 0%;">
                                    Размеры
                                </div>
                            </div>
                            <div class="selSizeProductBlockBody">
                                <div class="detailCardInfoHeadingSizes">
                                    <div class="size-selector" v-if="data.prdSizes">
                                        <!--                                <div class="size-selector__header">-->
                                        <!--                                    <a href="#size-chart" class="size-selector__link">Размерная сетка</a>-->
                                        <!--                                </div>-->
                                        <div class="size-selector__options">
                                            <label class="size-selector__option"
                                                @click="selectCartSize(size)"
                                                v-for="size in data.prdSizes"
                                                :class="{__Active: appShop.select.size === size}">

                                                <input type="radio" name="attribute_pa_size"
                                                    :value="size" required>
                                                <span>{{ size }}</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="size-selector" v-else style="padding: 10px 0">Размеры товара отсутствуют!
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="detailMobFixBarSelectSize" v-if="data.prdSizes">
                            <a @click="toggleMobSizePanel">Выберите размер</a>
                        </div>
                        <div class="detailMobFixBarSelectActions">
                            <div class="detailMobFixBarSelectActionsWrap" style="">
                                <div class="whiteListBtn" @click="addToWhtListMob()" style="background: #FFFFFF;">
                                    <div class="whiteListBtnIcon"
                                        v-if="appFavoriteBtn !== null && !appFavoriteBtn.status.active">
                                        <svg width="30" height="30" viewBox="0 0 30 30" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <g clip-path="url(#clip0_1365_42)">
                                                <mask id="mask0_1365_42" style="mask-type:luminance"
                                                    maskUnits="userSpaceOnUse" x="0" y="0" width="30"
                                                    height="30">
                                                    <path d="M30 0H0V30H30V0Z" fill="white" />
                                                </mask>
                                                <g mask="url(#mask0_1365_42)">
                                                    <path
                                                        d="M30 15C30 6.71573 23.2843 0 15 0C6.71573 0 0 6.71573 0 15C0 23.2843 6.71573 30 15 30C23.2843 30 30 23.2843 30 15Z"
                                                        fill="white" />
                                                    <path
                                                        d="M14.9994 10.019C16.5654 8.61333 18.9854 8.66 20.4938 10.1716C22.0023 11.6831 22.0541 14.0913 20.6511 15.662L14.9986 21.3233L9.34629 15.662C7.9434 14.0913 7.99584 11.6793 9.50361 10.1716C11.0131 8.6621 13.4288 8.61125 14.9994 10.019ZM19.5501 11.1134C18.5507 10.112 16.9371 10.0713 15.89 11.0112L15 11.8102L14.1094 11.0119C13.0593 10.0706 11.4487 10.112 10.4464 11.1144C9.45325 12.1075 9.40339 13.6982 10.3186 14.7488L14.9986 19.4362L19.6788 14.7488C20.5944 13.6978 20.5447 12.1102 19.5501 11.1134Z"
                                                        fill="#1F1F1F" />
                                                </g>
                                            </g>
                                            <defs>
                                                <clipPath id="clip0_1365_42">
                                                    <rect width="30" height="30" fill="white" />
                                                </clipPath>
                                            </defs>
                                        </svg>
                                    </div>
                                    <div class="whiteListBtnIcon" v-else>
                                        <svg width="30" height="30" viewBox="0 0 30 30" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <rect width="30" height="30" rx="15" fill="white" />
                                            <path
                                                d="M14.9994 10.019C16.5654 8.61333 18.9854 8.66 20.4938 10.1716C22.0022 11.6831 22.054 14.0913 20.6511 15.662L14.9986 21.3233L9.34628 15.662C7.9434 14.0913 7.99584 11.6793 9.5036 10.1716C11.0131 8.6621 13.4288 8.61125 14.9994 10.019Z"
                                                fill="#CE1B19" />
                                        </svg>
                                    </div>
                                </div>


                                <div class="addToCartBtn" style=""
                                    @click="addToCartBtn($event, {imageUrl: data.prdThumbnail, productId: data.prdId })"
                                    :class="{ 
                                        '__Disabled': isAddToCartDisabled,
                                        'disabled': !data.prdIsStock
                                    }">
                                    <div class="addToCartBtnWrapper" style="">
                                        <div class="addToCartBtnTitle" style=""> {{ content.btn }} <span v-if="currentModalType == 'idea' && data.prdIsStock" v-html="data.prdPriceFull"></span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>