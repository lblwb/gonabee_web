<!-- mb -->
<div v-show="appMainNav.mob.nav_search.show" class="mobileSearchMain">
    <div class="mobileSearchOverlay">
        <div class="mobileSearchContent">
            <div class="mobileSearchContentHeader">
                <div class="mobileSearchContentHeaderWrapper">
                    <div class="mobileSearchContentHeaderSearch">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                             xmlns="http://www.w3.org/2000/svg">
                            <path
                                    d="M15.0232 13.8477L18.5921 17.4166L17.4136 18.5951L13.8447 15.0262C12.5615 16.0528 10.9341 16.667 9.16406 16.667C5.02406 16.667 1.66406 13.307 1.66406 9.16699C1.66406 5.02699 5.02406 1.66699 9.16406 1.66699C13.3041 1.66699 16.6641 5.02699 16.6641 9.16699C16.6641 10.937 16.0499 12.5644 15.0232 13.8477ZM13.3513 13.2293C14.3703 12.1792 14.9974 10.7467 14.9974 9.16699C14.9974 5.94408 12.387 3.33366 9.16406 3.33366C5.94115 3.33366 3.33073 5.94408 3.33073 9.16699C3.33073 12.3899 5.94115 15.0003 9.16406 15.0003C10.7437 15.0003 12.1762 14.3732 13.2264 13.3542L13.3513 13.2293Z"
                                    fill="white"/>
                        </svg>
                    </div>
                    <div class="mobileMenuContentInput" style="">
                        <input v-model="appMainNav.mob.nav_search.context.queryText" @input="onInputSearchHandler"
                               placeholder="Начните поиск" type="text">
                    </div>
                    <div class="mobileSearchContentHeaderExit" @click="toggleMobSearch">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                             xmlns="http://www.w3.org/2000/svg">
                            <path
                                    d="M8.82223 10L2.32812 3.5059L3.50663 2.32739L10.0007 8.82143L16.4948 2.32739L17.6733 3.5059L11.1792 10L17.6733 16.494L16.4948 17.6726L10.0007 11.1785L3.50663 17.6726L2.32812 16.494L8.82223 10Z"
                                    fill="#F9F9F9"/>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="mobileMenuContentWrapper" style="">
                <div class="mobileSearchContentSearchCat">
                    <div class="searchCatList" v-if="appMainNav.mob.nav_search.cat.result.list">
                        <div class="searchCatListItem" v-for="catItem in appMainNav.mob.nav_search.cat.result.list"
                             :key="catItem.name">
                            <a class="searchCatListItemCard" :href="catItem.link">
                                <div class="searchCatListItemCardWrapper" style="">
                                    <div class="searchCatListItemCardImg" v-if="catItem.image">
                                        <img :src="catItem.image" :alt="catItem.name">
                                    </div>
                                    <div class="searchCatListItemCardImg __Bord" v-else>
                                        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAALQAAAD6CAYAAAAIn20uAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAMXSURBVHgB7dfBSsNAAEXR0YpCoSC4ciH4/1/lThC7anEhCM5o0W/I5Rx4Icn6EiZX499h7nFuP7cbsA2nueNlf+GukJ/n7uauB2zHavb+cn9eQT/MPQ3YtnXC+Al6xXw3YPtu1/HiMKBh77xMyU7QpAiaFEGTImhSBE2KoEkRNCmCJkXQpAiaFEGTImhSBE2KoEkRNCmCJkXQpAiaFEGTImhSBE2KoEkRNCmCJkXQpAiaFEGTImhSBE2KoEkRNCmCJkXQpAiaFEGTImhSBE2KoEkRNCmCJkXQpAiaFEGTImhSBE2KoEkRNCmCJkXQpAiaFEGTImhSBE2KoEkRNCmCJkXQpAiaFEGTImhSBE2KoEkRNCmCJkXQpAiaFEGTImhSBE2KoEkRNCmCJkXQpAiaFEGTImhSBE2KoEkRNCmCJkXQpAiaFEGTImhSBE2KoEkRNCmCJkXQpAiaFEGTImhSBE2KoEkRNCmCJkXQpAiaFEGTImhSBE2KoEkRNCmCJkXQpAiaFEGTImhSBE2KoEkRNCmCJkXQpAiaFEGTImhSBE2KoEkRNCmCJkXQpAiaFEGTImhSBE2KoEkRNCmCJkXQpAiaFEGTImhSBE2KoEkRNCmCJkXQpAiaFEGTImhSBE2KoEkRNCmCJkXQpAiaFEGTImhSBE2KoEkRNCmCJkXQpAiaFEGTImhSBE2KoEkRNCmCJkXQpAiaFEGTImhSBE2KoEkRNCmCJkXQpAiaFEGTImhSBE2KoEkRNCmCJkXQpAiaFEGTImhSBE2KoEkRNCmCJkXQpAiaFEGTImhSBE2KoEkRNCmCJkXQpAiaFEGTImhSBE2KoEkRNCmCJkXQpAiaFEGTImhSBE2KoEkRNCmCJkXQpAiaFEGTImhSBE2KoEkRNCmCJkXQpAiaFEGTImhSBE2KoEkRNCmCJkXQpAiaFEGTImhSBE2KoEkRNCmCJkXQpAiaFEGTImhSBE2KoEkRNCkr6K8BESvojwEN5xX064CG9928fM5dzR0GbNf6ML/tLg+n8Rv2zdztgG1Y/3/ryPwyd1wvvgEmbxUF78bXTgAAAABJRU5ErkJggg=="
                                             :alt="catItem.name"
                                             style="object-fit: cover;object-position: bottom; min-height: 16vh;max-width: 21.5vw;">
                                    </div>
                                    <div class="searchCatListItemCardHeading">
                                        <div class="cardHeadingTitle"
                                             style="">
                                            {{catItem.name}}
                                        </div>
                                        <div class="cardHeadingSubTitle" v-if="catItem.parent_cat"
                                             style="">
                                            {{catItem.parent_cat.name}}
                                        </div>
                                        <div class="cardHeadingSubTitle" v-else>
                                            ——
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="searchCatList" v-else>—</div>
                </div>

                <div class="mobileMenuContentSearchSep" style="">
                    <hr>
                </div>
                <div class="mobileMenuContentSearchResult">
                    <div class="searchResultHeading" style="">
                        <div class="searchResultHeadingTitle" style="">
                            Результаты поиска ({{appMainNav.mob.nav_search.products.count}})
                        </div>
                    </div>
                    <div class="searchResultList" v-if="appMainNav.mob.nav_search.products.result.list">
                        <div class="searchResultListWrapper" style="">
                            <div class="searchResultListItem previewSliderItem"
                                 v-for="productItem in appMainNav.mob.nav_search.products.result.list"
                                 :key="productItem.name">
                                <div class="previewSliderItemBlock" style="position: relative;">
                                    <div class="itemBlockHeading">
                                        <div class="itemBlockHeadingWrapper"
                                             style="display: flex; justify-content: space-between;">
                                            <div class="previewSliderItemWhiteList"
                                                 :class="{__Active: getSelectedWhtLst(productItem.ID)}"
                                                 data-product-id="productItem.ID">
                                                <div class="whiteListBtn" style="background: #FFFFFF;"
                                                     @click="addToWhtListMob({imageUrl:'', productId: productItem.ID})">
                                                    <div class="whiteListBtnIcon"
                                                         v-if="getSelectedWhtLst(productItem.data.ID)">
                                                        <svg width="30" height="30" viewBox="0 0 30 30" fill="none"
                                                             xmlns="http://www.w3.org/2000/svg">
                                                            <rect width="30" height="30" rx="15" fill="white"/>
                                                            <path d="M14.9994 10.019C16.5654 8.61333 18.9854 8.66 20.4938 10.1716C22.0022 11.6831 22.054 14.0913 20.6511 15.662L14.9986 21.3233L9.34628 15.662C7.9434 14.0913 7.99584 11.6793 9.5036 10.1716C11.0131 8.6621 13.4288 8.61125 14.9994 10.019ZM19.55 11.1134C18.5506 10.112 16.9371 10.0713 15.89 11.0112L15 11.8102L14.1094 11.0119C13.0594 10.0706 11.4487 10.1121 10.4464 11.1144C9.45325 12.1075 9.40339 13.6982 10.3187 14.7488L14.9986 19.4362L19.6788 14.7488C20.5944 13.6978 20.5447 12.1102 19.55 11.1134Z"
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

                                    <div class="previewSliderItemImage">
                                        <div class="previewSliderItemImageWrapper">
                                            <div class="previewSliderItemImageSubGallery swiper">
                                                <div class="itemImageSubGalleryWrap swiper-wrapper">
                                                    <div class="itemImageSubGalleryItem swiper-slide"
                                                         v-for="galleryItem in productItem.gallery"
                                                         :data-color-id="productItem.colors.id"
                                                         :data-href="productItem.id"
                                                         @click="showProductNav"
                                                         :style="`background-image: url(${productItem.image}); text-decoration: none;`">
                                                    </div>
                                                </div>
                                                <!--pag-->
                                                <div class="bottomPaginate"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="previewSliderItemBlockHeading">
                                        <div class="itemBlockHeadingTitle">
                                            <a :href="productItem.link"
                                               style="">{{productItem.name}}</a>
                                        </div>
                                        <div class="itemBlockHeadingPrice" v-html="productItem.price"></div>
                                        <!--     Colors            -->
                                        <div class="itemBlockHeadingSelColor">
                                            <div class="colorBox" v-for="color in productItem.colors_list"
                                                 :key="color.slug">
                                                <div class="color-circle"
                                                     :title="color.name"
                                                     @click="selectCartColorProduct(color.slug)"
                                                     :style="{ backgroundColor: color.code }"
                                                     :data-color-id="color.slug">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--    Search Item   -->
                <!--    Hr   -->
                <!--    Search Result   -->
            </div>

        </div>
    </div>
</div>