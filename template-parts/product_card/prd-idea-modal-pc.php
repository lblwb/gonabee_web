<div class="detailIdeaModal" v-if="appShop.idea.modal.show">
    <div class="detailIdeaOverlay" @click.self="toggleIdeaModal">
        <div class="detailIdeaModalWrapper">
            <div class="detailIdeaModalBlock" style="position: relative;">
                <!-- КНОПКА ЗАКРЫТИЯ -->
                <button
                    @click="toggleIdeaModal"
                    style="position: absolute; top: 15px; right: 15px; background: none; border: none; font-size: 24px; cursor: pointer; opacity: 0.6"
                >
                    &times;
                </button>
                <div class="detailModalBlockWrapper">
                    <div class="detailModalBlockProductImgSlider" style="flex: 1; max-width: 40vw;">
                        <div class="productImgSliderWrapper" style="display: flex; gap: 20px;">
                            <div class="productImgSliderThumb">
                                <div class="productImgSlideThumbList">
                                    <div class="productImgSlideThumbListItem" style="border: solid 1px transparent; border-radius: 10px; opacity: 0.5; max-width: 120px; overflow: hidden;">
                                        <img :src="appShop.idea.modal.data.image"
                                             v-if="appShop.idea.modal.data.image"
                                             style="width: 100%; height: 100%; object-fit: cover; object-position: center">
                                    </div>
                                </div>
                            </div>
                            <div class="productImgSliderMain" style="border-radius: 5px; overflow: hidden; position: relative">
                                <img :src="appShop.idea.modal.data.image"
                                     v-if="appShop.idea.modal.data.image">

                                <div class="cardImagesMainHeadingFav">
                                    <div class="previewSliderItemWhiteList" id="favBtnPrd">
                                        <div class="whiteListBtn" style="background: #FFFFFF;"
                                             @click="addToWhtListMob({productId: window.states.appShop.idea.modal.data.id})">
                                            <div class="whiteListBtnIcon" v-if="true">
                                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M7.99839 3.01902C9.56439 1.61333 11.9844 1.66 13.4928 3.17157C15.0013 4.68315 15.0531 7.09133 13.6501 8.662L7.99765 14.3233L2.34531 8.662C0.94242 7.09133 0.99486 4.67934 2.50263 3.17157C4.0121 1.6621 6.42785 1.61125 7.99839 3.01902ZM12.5491 4.1134C11.5497 3.11196 9.93612 3.07134 8.88905 4.01125L7.99899 4.81016L7.10845 4.01187C6.05837 3.07065 4.44776 3.11205 3.44543 4.11438C2.45227 5.10754 2.40241 6.6982 3.31767 7.7488L7.99765 12.4362L12.6778 7.7488C13.5934 6.6978 13.5437 5.11017 12.5491 4.1134Z"
                                                          fill="#1F1F1F"/>
                                                </svg>
                                            </div>
                                            <div class="whiteListBtnIcon" v-else>
                                                <svg width="30" height="30" viewBox="0 0 30 30" fill="none"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <rect width="30" height="30" rx="15" fill="white"/>
                                                    <path d="M14.9994 10.019C16.5654 8.61333 18.9854 8.66 20.4938 10.1716C22.0022 11.6831 22.054 14.0913 20.6511 15.662L14.9986 21.3233L9.34628 15.662C7.9434 14.0913 7.99584 11.6793 9.5036 10.1716C11.0131 8.6621 13.4288 8.61125 14.9994 10.019Z"
                                                          fill="#CE1B19"/>
                                                </svg>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="productInfoCard" style="flex: 1; max-width: 768px;">
                        <div class="productInfoCardHeading">
                            <div class="productInfoCardHeadingTitle" v-html="appShop.idea.modal.data.title">
                            </div>
                            <div class="productInfoCardHeadingPrice"
                                 v-html="appShop.idea.modal.data.price">
                            </div>
                            <div class="productInfoCardHeadingSpr" style="opacity: 0.1; padding: 20px 0;">
                                <hr>
                            </div>
                            <!--                                                        <div class="productInfoCardHeadingColor">-->
                            <!--                                                            {{appShop.idea.modal.data.colors}}-->
                            <!--                                                        </div>-->
                            <!--                                                        <div class="productInfoCardHeadingSize">-->
                            <!--                                                            {{appShop.idea.modal.data.colors}}-->
                            <!--                                                        </div>-->
                            <div class="productInfoCardHeadingAddCart">
                                <a href="#" class="addCartBtn" @click="addToCartBtn(appShop.idea.modal.data.id)">
                                    Добавить в корзину
                                </a>
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
</div>