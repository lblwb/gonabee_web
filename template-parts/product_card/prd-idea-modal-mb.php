<div class="detailIdeaModalMb" v-if="appShop.idea.modal_mb.show">
    <div class="detailIdeaMbOverlay" @click.self="toggleIdeaModal" v-if="appShop.idea.modal.data"
         style="position: fixed; top: 0; right: 0; left: 0; bottom: 0; z-index: 300; background: #fff;">
        <div class="detailIdeaMbHeading" style="padding: 16px 10px; background: #222222;">
            <div class="detailIdeaMbHeadingWrapper"
                 style="display: flex; justify-content: space-between; align-items: center;">

                <div class="detailIdeaMbHeadingBack"
                     onclick="try{window.states.singlePrdCard.toggleIdeaMbModal()}catch (e) {}">
                    <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9.47759 10.4995L13.8086 6.16838L12.5712 4.93095L7.00265 10.4995L12.5712 16.0679L13.8086 14.8304L9.47759 10.4995Z"
                              fill="#F9F9F9"/>
                    </svg>
                </div>
                <div class="detailIdeaMbHeadingTitle"
                     style="font-family: 'Sofia Sans',sans-serif;font-style: normal;font-weight: 600;font-size: 20px;line-height: 125%;text-align: center;font-feature-settings: 'ss01' on;color: #F9F9F9;">
                    Идея для образа
                </div>
                <div class="detailIdeaMbHeadingRow"></div>
            </div>
        </div>
        <div class="detailIdeaMbBody" style="max-height: 90vh;overflow-y: auto;padding-bottom: 140px;">
            <div class="shopProductDetailCardImagesMainMob">
                <div class="detailCardImagesMainMobWrapper">
                    <div class="mainMobSlider swiper">
                        <div class="mainMobSliderWrapper swiper-wrapper">
                            <!--                            {{appShop.idea.modal.data.image}}-->
                            <div class="mainMobSliderItemSlide swiper-slide">
                                <img :src="appShop.idea.modal.data.image"
                                     alt="product gallery image"/>
                            </div>
                        </div>
                        <div class="bottomPaginate"></div>
                    </div>

                    <div class="mainMobTop"
                         style="position: absolute; top: 20px; right: 10px; left: 10px; z-index: 10">
                        <div class="mainMobTopWrapper"
                             style="display: flex; align-items: center; justify-content: space-between;">
                            <!--                                        -->
                            <div class="mainMobTopWhtAddWrp"></div>
                            <div class="mainMobTopWhtAddWrp">
                                <div class="mainMobTopWhtAdd">
                                    <a @click="addToWhtListMob({imageUrl:''})">
                                        <div class="mainMobTopWhtAddBtn"
                                             v-if="appFavoriteBtn && !appFavoriteBtn.status.active"
                                             @click="addToWhtListMob({imageUrl:''})">
                                            <svg width="40" height="40" viewBox="0 0 40 40" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <rect width="40" height="40" rx="20" fill="white"/>
                                                <path
                                                        d="M19.9982 13.7738C21.9557 12.0167 24.9807 12.075 26.8662 13.9645C28.7518 15.8539 28.8166 18.8642 27.0629 20.8275L19.9973 27.9042L12.9319 20.8275C11.1783 18.8642 11.2438 15.8492 13.1285 13.9645C15.0154 12.0776 18.0351 12.0141 19.9982 13.7738ZM25.6866 15.1418C24.4373 13.89 22.4204 13.8392 21.1116 15.0141L19.999 16.0127L18.8858 15.0148C17.5732 13.8383 15.5599 13.8901 14.307 15.143C13.0656 16.3844 13.0033 18.3728 14.1473 19.686L19.9973 25.5453L25.8475 19.686C26.992 18.3723 26.9299 16.3877 25.6866 15.1418Z"
                                                        fill="#1F1F1F"/>
                                            </svg>
                                        </div>
                                        <div class="mainMobTopWhtAddBtn" v-else>
                                            <svg width="40" height="40" viewBox="0 0 40 40" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <rect width="40" height="40" rx="20" fill="white"/>
                                                <path d="M19.9982 13.7738C21.9557 12.0167 24.9807 12.075 26.8662 13.9645C28.7518 15.8539 28.8166 18.8642 27.0629 20.8275L19.9973 27.9042L12.9319 20.8275C11.1783 18.8642 11.2438 15.8492 13.1285 13.9645C15.0154 12.0776 18.0351 12.0141 19.9982 13.7738ZM25.6866 15.1418C24.4373 13.89 22.4204 13.8392 21.1116 15.0141L19.999 16.0127L18.8858 15.0148C17.5732 13.8383 15.5599 13.8901 14.307 15.143C13.0656 16.3844 13.0033 18.3728 14.1473 19.686L19.9973 25.5453L25.8475 19.686C26.992 18.3723 26.9299 16.3877 25.6866 15.1418Z"
                                                      fill="#CE1B19"/>
                                                <path d="M25.6866 15.1418C24.4373 13.89 22.4204 13.8392 21.1116 15.0141L19.999 16.0127L18.8858 15.0148C17.5732 13.8383 15.5599 13.8901 14.307 15.143C13.0656 16.3844 13.0033 18.3728 14.1473 19.686L19.9973 25.5453L25.8475 19.686C26.992 18.3723 26.9299 16.3877 25.6866 15.1418Z"
                                                      fill="#CE1B19"/>
                                            </svg>
                                        </div>
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Info -->
                    <div class="shopProductDetailCardInfo" style="flex: 1; padding: 10px;">
                        <div class="detailCardInfoHeading">
                            <div class="detailCardInfoHeadingWrapper">
                                <div class="detailCardInfoHeadingTitle" v-html="appShop.idea.modal.data.title">
                                </div>
                            </div>
                            <div class="cardInfoHeadingPrice" style="">
                                <div class="cardInfoHeadingPriceWrapper">
                                    <div class="cardInfoHeadingPriceFull" v-html="appShop.idea.modal.data.price">
                                    </div>
                                </div>
                            </div>
                            <div class="detailCardHeadingSeparate">
                                <div class="detailCardInfoHeadingColor" v-if="appShop.idea.modal.data.colors">
                                    <div class="infoHeadingColorHead">
                                        <div class="infoHeadingColorHeadTitle" style="margin-bottom: 16px">
                                            Цвет:
                                        </div>
                                    </div>

                                    <div class="itemBlockHeadingSelColor" style="display: flex;gap: 10px;">
                                        <div
                                                v-for="color in appShop.idea.modal.data.colors"
                                                :key="color.slug"
                                                class="colorBox"
                                                :class="{ __Active: appShop.cart.select.color === color.slug }"
                                                @click="selectCartColor(color.slug)"
                                        >
                                            <div
                                                    class="color-circle"
                                                    :title="color.name"
                                                    :data-color-id="color.slug"
                                                    :style="{ backgroundColor: color.code }"
                                            ></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="detailCardInfoHeadingAtr" style="">
                            <div class="detailCardInfoHeadingSizes" v-if="appShop.idea.modal.data.sizes">
                                <div class="size-selector">
                                    <div class="size-selector__header">
                                        <span class="size-selector__title">Размер</span>
                                        <a href="#size-chart" class="size-selector__link">Размерная сетка</a>
                                    </div>
                                    <div class="size-selector__options">
                                        <label class="size-selector__option"
                                               v-for="sizeItem in appShop.idea.modal.data.sizes"
                                               :key="size"
                                               @click="selectCartSize(sizeItem)"
                                               :class="{__Active: appShop.cart.select.size === sizeItem}">
                                            <input type="radio" name="attribute_pa_size"
                                                   :value="sizeItem" required>
                                            <span>{{sizeItem}}</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="detailCardInfoHeadingAddToWrapper">
                            <div class="detailCardInfoHeadingAddToCart">
                                <div class="addToCardBtn" href="#"
                                     @click="addToCartBtn($event, {productId:appShop.idea.modal.data.id, imageUrl:''})">
                                    <div class="addToCardBtnHeading">
                                        <div class="addToCardBtnHeadingTitle">
                                            Добавить в корзину
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="detailCardHeadingSeparateBottom"></div>
                        </div>
                        <div class="detailCardInfoHeadingDscWrapper" v-if="appShop.idea.modal.data.description">
                            <div class="detailCardInfoHeadingDesc">
                                {{appShop.idea.modal.data.description}}
                            </div>
                            <div class="detailCardHeadingSeparateBottom"></div>
                        </div>

                    </div>
                    <div class="detailCardInfoBody">
                        <div class="detailCardInfoBodyWrapper">
                            <div class="detailCardInfoBodyAttr">
                                <div class="detailCardInfoBodyAttrWrapper">
                                    <!--                                        -->
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

            <!-- mob -->
            <div class="shopProductDetailMobFixBar" id="singlePrdMob" :data-product-id="appShop.idea.modal.data.id"
                 v-cloak>
                <div class="detailMobFixBarWrapper">

                    <div class="selSizeProductBlock" style="padding: 10px;border-bottom: solid 1px #eee;"
                         v-show="appShop.cart.mob.size.show">
                        <div class="selSizeProductBlockHeading">
                            <div class="selSizeProductBlockHeadingTitle"
                                 style="font-family: Sofia Sans;font-weight: 600;font-size: 20px;line-height: 125%;letter-spacing: 0%;">
                                Размеры
                            </div>
                        </div>
                        <div class="selSizeProductBlockBody">
                            <div class="detailCardInfoHeadingSizes">

                                <div class="size-selector" v-if="appShop.idea.modal.data.sizes">
                                    <!--                                <div class="size-selector__header">-->
                                    <!--                                    <a href="#size-chart" class="size-selector__link">Размерная сетка</a>-->
                                    <!--                                </div>-->
                                    <div class="size-selector__options" >
                                        <label class="size-selector__option"
                                               v-for="sizeItem in appShop.idea.modal.data.sizes"
                                               :key="size"
                                               @click="selectCartSize(sizeItem)"
                                               :class="{__Active: appShop.cart.select.size === sizeItem}">
                                            <input type="radio" name="attribute_pa_size"
                                                   :value="sizeItem" required>
                                            <span>{{sizeItem}}</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="size-selector" v-else style="padding: 10px 0">Размеры товара отсутствуют!</div>
                            </div>
                        </div>
                    </div>

                    <div class="detailMobFixBarSelectSize">
                        <a @click="toggleMobSizePanel">Выберите размер</a>
                    </div>
                    <div class="detailMobFixBarSelectActions">
                        <div class="detailMobFixBarSelectActionsWrap" style="">
                            <div class="whiteListBtn"
                                 v-if="getSelectedWhtLst(appShop.idea.modal.data.id)"
                                 @click="addToWhtListMob({imageUrl:'', productId: appShop.idea.modal.data.id})">
                                <svg width="40" height="40" viewBox="0 0 40 40" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <rect width="40" height="40" rx="20" fill="#F0C224"/>
                                    <path
                                            d="M19.9982 13.7738C21.9557 12.0167 24.9807 12.075 26.8662 13.9645C28.7518 15.8539 28.8166 18.8642 27.0629 20.8275L19.9973 27.9042L12.9319 20.8275C11.1783 18.8642 11.2438 15.8492 13.1285 13.9645C15.0154 12.0776 18.0351 12.0141 19.9982 13.7738ZM25.6866 15.1418C24.4373 13.89 22.4204 13.8392 21.1116 15.0141L19.999 16.0127L18.8858 15.0148C17.5732 13.8383 15.5599 13.8901 14.307 15.143C13.0656 16.3844 13.0033 18.3728 14.1473 19.686L19.9973 25.5453L25.8475 19.686C26.992 18.3723 26.9299 16.3877 25.6866 15.1418Z"
                                            fill="#1F1F1F"/>
                                </svg>
                            </div>

                            <div class="whiteListBtn"
                                 v-else
                                 @click="addToWhtListMob({imageUrl:'', productId: appShop.idea.modal.data.id})">
                                <svg width="40" height="40" viewBox="0 0 40 40" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_181_11533)">
                                        <path d="M40 20C40 8.9543 31.0457 0 20 0C8.9543 0 0 8.9543 0 20C0 31.0457 8.9543 40 20 40C31.0457 40 40 31.0457 40 20Z"
                                              fill="#F0C224"/>
                                        <path d="M19.9982 13.7738C21.9557 12.0167 24.9807 12.075 26.8662 13.9645C28.7518 15.8539 28.8166 18.8642 27.0629 20.8275L19.9973 27.9042L12.9319 20.8275C11.1783 18.8642 11.2438 15.8492 13.1285 13.9645C15.0154 12.0776 18.0351 12.0141 19.9982 13.7738ZM25.6866 15.1418C24.4373 13.89 22.4204 13.8392 21.1116 15.0141L19.999 16.0127L18.8858 15.0148C17.5732 13.8383 15.5599 13.8901 14.307 15.143C13.0656 16.3844 13.0033 18.3728 14.1473 19.686L19.9973 25.5453L25.8475 19.686C26.992 18.3723 26.9299 16.3877 25.6866 15.1418Z"
                                              fill="#CE1B19"/>
                                        <path d="M25.6866 15.1418C24.4373 13.89 22.4204 13.8392 21.1116 15.0141L19.999 16.0127L18.8858 15.0148C17.5732 13.8383 15.5599 13.8901 14.307 15.143C13.0656 16.3844 13.0033 18.3728 14.1473 19.686L19.9973 25.5453L25.8475 19.686C26.992 18.3723 26.9299 16.3877 25.6866 15.1418Z"
                                              fill="#CE1B19"/>
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_181_11533">
                                            <rect width="40" height="40" fill="white"/>
                                        </clipPath>
                                    </defs>
                                </svg>
                            </div>


                            <div class="addToCartBtn" style=""
                                 @click="addToCartMob($event, {imageUrl:'', productId: appShop.idea.modal.data.id })">
                                <div class="addToCartBtnWrapper" style="">
                                    <div class="addToCartBtnTitle" style="">Добавить в
                                        корзину <span v-html="appShop.idea.modal.data.price"></span></div>
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