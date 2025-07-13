<div id="appMainFilter">
    <div class="appFilterWrapper">
        <!--  -->
        <div class="filter-section" v-if="filterData.sortBy" style="padding-bottom: 16px!important">
            <div class="filter-title" style="color: #F0C224; font-size: 14px;">
                Сортировать по
            </div>
            <!--            {{filterData}}-->
            <div class="filter-content">
                <label v-for="sortItem in filterData.sortBy" :key="sortItem" class="filter-item">
                    <input type="radio" v-model="selectedSortBy" :value="sortItem.slug" @change="applyFilters"/>
                    <span>{{ sortItem.name }}</span>
                </label>
            </div>
        </div>

        <!--  -->
        <div class="filter-section">
            <div class="filter-title" style="color: #F0C224; font-size: 14px;">
                Цена
            </div>

            <div class="filter-content">
                <div class="sliderRange">
                    <div id="price-slider"></div>
                </div>
                <div class="sliderWrapper"
                     style="display: flex; justify-content: space-between; align-items: center;gap: 18px; margin-bottom: 24px;">
                    <div class="sliderRangeInput" style="display: flex; gap: 10px;align-items: center;">
                        <input type="number" v-model="filterData.minPrice"
                               style="padding: 10px 18px; width: 100%; border-radius: 5px; border: solid 1px #1F1F1F10;"/>
                    </div>
                    <div class="sliderRangeInput" style="display: flex; gap: 10px;align-items: center;">
                        <input type="number" v-model="filterData.maxPrice"
                               style="padding: 10px 18px; width: 100%; border-radius: 5px; border: solid 1px #1F1F1F10;"/>
                    </div>
                </div>
            </div>
        </div>
        <!--  -->
        <div class="filter-section">
            <div class="filter-title" @click="toggleSection('mob.vwMatch.show')">
                Вид одежды
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                     xmlns="http://www.w3.org/2000/svg"
                     class="rotatedMb">
                    <path d="M10.0014 9.02379L5.8766 13.1486L4.6981 11.9701L10.0014 6.66671L15.3047 11.9701L14.1262 13.1486L10.0014 9.02379Z"
                          fill="#1F1F1F"/>
                </svg>
            </div>
            <div class="modalSubFilter" v-if="expandedSections && expandedSections.includes('mob.vwMatch.show')">
                <div class="mobileFilterContentHeader" style="padding: 18px 10px;">
                    <div class="mobileSearchContentHeaderWrapper">
                        <div class="mobileSearchContentHeaderSearch">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path
                                        d="M15.0232 13.8477L18.5921 17.4166L17.4136 18.5951L13.8447 15.0262C12.5615 16.0528 10.9341 16.667 9.16406 16.667C5.02406 16.667 1.66406 13.307 1.66406 9.16699C1.66406 5.02699 5.02406 1.66699 9.16406 1.66699C13.3041 1.66699 16.6641 5.02699 16.6641 9.16699C16.6641 10.937 16.0499 12.5644 15.0232 13.8477ZM13.3513 13.2293C14.3703 12.1792 14.9974 10.7467 14.9974 9.16699C14.9974 5.94408 12.387 3.33366 9.16406 3.33366C5.94115 3.33366 3.33073 5.94408 3.33073 9.16699C3.33073 12.3899 5.94115 15.0003 9.16406 15.0003C10.7437 15.0003 12.1762 14.3732 13.2264 13.3542L13.3513 13.2293Z"
                                        fill="white"/>
                            </svg>
                        </div>
                        <div class="mobileMenuContentHeading" style="">
                            <div class="mobileMenuContentHeadingTitle"
                                 style="font-family: 'Sofia Sans',sans-serif;font-weight: 600;font-size: 20px;line-height: 125%;letter-spacing: 0%;text-align: center;">
                                Фильтры
                            </div>
                        </div>
                        <div class="mobileSearchContentHeaderExit" @click="toggleSection('mob.vwMatch.show')">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path
                                        d="M8.82223 10L2.32812 3.5059L3.50663 2.32739L10.0007 8.82143L16.4948 2.32739L17.6733 3.5059L11.1792 10L17.6733 16.494L16.4948 17.6726L10.0007 11.1785L3.50663 17.6726L2.32812 16.494L8.82223 10Z"
                                        fill="#F9F9F9"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="mobileFilterContentBody" style="padding: 30px 10px;">
                    <div class="filter-section">
                        <div class="filter-title">
                            Вид одежды
                        </div>
                        <div class="modalSubFilter" v-if="expandedSections && expandedSections.includes('mob.vwMatch.show')">
                            <div class="mobileFilterContentHeader" style="padding: 18px 10px;">
                                <div class="mobileSearchContentHeaderWrapper">
                                    <div class="mobileSearchContentHeaderSearch">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                    d="M15.0232 13.8477L18.5921 17.4166L17.4136 18.5951L13.8447 15.0262C12.5615 16.0528 10.9341 16.667 9.16406 16.667C5.02406 16.667 1.66406 13.307 1.66406 9.16699C1.66406 5.02699 5.02406 1.66699 9.16406 1.66699C13.3041 1.66699 16.6641 5.02699 16.6641 9.16699C16.6641 10.937 16.0499 12.5644 15.0232 13.8477ZM13.3513 13.2293C14.3703 12.1792 14.9974 10.7467 14.9974 9.16699C14.9974 5.94408 12.387 3.33366 9.16406 3.33366C5.94115 3.33366 3.33073 5.94408 3.33073 9.16699C3.33073 12.3899 5.94115 15.0003 9.16406 15.0003C10.7437 15.0003 12.1762 14.3732 13.2264 13.3542L13.3513 13.2293Z"
                                                    fill="white"/>
                                        </svg>
                                    </div>
                                    <div class="mobileMenuContentHeading" style="">
                                        <div class="mobileMenuContentHeadingTitle"
                                             style="font-family: 'Sofia Sans',sans-serif;font-weight: 600;font-size: 20px;line-height: 125%;letter-spacing: 0%;text-align: center;">
                                            Фильтры
                                        </div>
                                    </div>
                                    <div class="mobileSearchContentHeaderExit" @click="toggleSection('mob.vwMatch.show')">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                    d="M8.82223 10L2.32812 3.5059L3.50663 2.32739L10.0007 8.82143L16.4948 2.32739L17.6733 3.5059L11.1792 10L17.6733 16.494L16.4948 17.6726L10.0007 11.1785L3.50663 17.6726L2.32812 16.494L8.82223 10Z"
                                                    fill="#F9F9F9"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div class="mobileFilterContentBody" style="padding: 30px 10px;">
                                <div class="filter-section">
                                    <div class="filter-title">
                                        Вид одежды
                                    </div>
                                    <div class="filter-content">
                                        <div v-if="filterData && filterData.vwMatch" class="filter-content"
                                             style="margin-bottom: 20px">
                                            <label v-for="viewMatch in filterData.vwMatch" :key="viewMatch.slug" class="filter-item color-item">
                                                <input type="checkbox" v-model="selectedVwMatch" :value="viewMatch.slug" @change="applyFilters"/>
                                                <span>{{ viewMatch.name }}</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--        -->
        <div class="filter-section">
            <div class="filter-title" @click="toggleSection('mob.color.show')">
                Цвет
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                     xmlns="http://www.w3.org/2000/svg"
                     class="rotatedMb">
                    <path d="M10.0014 9.02379L5.8766 13.1486L4.6981 11.9701L10.0014 6.66671L15.3047 11.9701L14.1262 13.1486L10.0014 9.02379Z"
                          fill="#1F1F1F"/>
                </svg>
            </div>
            <div class="modalSubFilter" v-if="expandedSections && expandedSections.includes('mob.color.show')">
                <div class="mobileFilterContentHeader" style="padding: 18px 10px;">
                    <div class="mobileSearchContentHeaderWrapper">
                        <div class="mobileSearchContentHeaderSearch">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path
                                        d="M15.0232 13.8477L18.5921 17.4166L17.4136 18.5951L13.8447 15.0262C12.5615 16.0528 10.9341 16.667 9.16406 16.667C5.02406 16.667 1.66406 13.307 1.66406 9.16699C1.66406 5.02699 5.02406 1.66699 9.16406 1.66699C13.3041 1.66699 16.6641 5.02699 16.6641 9.16699C16.6641 10.937 16.0499 12.5644 15.0232 13.8477ZM13.3513 13.2293C14.3703 12.1792 14.9974 10.7467 14.9974 9.16699C14.9974 5.94408 12.387 3.33366 9.16406 3.33366C5.94115 3.33366 3.33073 5.94408 3.33073 9.16699C3.33073 12.3899 5.94115 15.0003 9.16406 15.0003C10.7437 15.0003 12.1762 14.3732 13.2264 13.3542L13.3513 13.2293Z"
                                        fill="white"/>
                            </svg>
                        </div>
                        <div class="mobileMenuContentHeading" style="">
                            <div class="mobileMenuContentHeadingTitle"
                                 style="font-family: 'Sofia Sans',sans-serif;font-weight: 600;font-size: 20px;line-height: 125%;letter-spacing: 0%;text-align: center;">
                                Фильтры
                            </div>
                        </div>
                        <div class="mobileSearchContentHeaderExit" @click="toggleSection('mob.color.show')">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path
                                        d="M8.82223 10L2.32812 3.5059L3.50663 2.32739L10.0007 8.82143L16.4948 2.32739L17.6733 3.5059L11.1792 10L17.6733 16.494L16.4948 17.6726L10.0007 11.1785L3.50663 17.6726L2.32812 16.494L8.82223 10Z"
                                        fill="#F9F9F9"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="mobileFilterContentBody" style="padding: 30px 10px;">
                    <div class="filter-section">
                        <div class="filter-title">
                            Цвет
                        </div>
                        <div class="filter-content">
                            <div v-if="expandedSections.includes('colors')" class="filter-content"
                                 style="margin-bottom: 80px">
                                <label v-for="color in filterData.colors" :key="color.slug"
                                       class="filter-item color-item">
                                    <input type="checkbox" v-model="selectedColors" :value="color.slug"
                                           @change="applyFilters"/>
                                    <span class="color-swatch" :style="{ backgroundColor: color.hex }"></span>
                                    <span>{{ color.name }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!---->
        <div class="filter-section">
            <div class="filter-title" @click="toggleSection('mob.sizes.show')">
                Размер
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                     xmlns="http://www.w3.org/2000/svg"
                     class="rotatedMb">
                    <path d="M10.0014 9.02379L5.8766 13.1486L4.6981 11.9701L10.0014 6.66671L15.3047 11.9701L14.1262 13.1486L10.0014 9.02379Z"
                          fill="#1F1F1F"/>
                </svg>
            </div>
            <div class="modalSubFilter" v-if="expandedSections && expandedSections.includes('mob.sizes.show')">
                <div class="mobileFilterContentHeader" style="padding: 18px 10px;">
                    <div class="mobileSearchContentHeaderWrapper">
                        <div class="mobileSearchContentHeaderSearch">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path
                                        d="M15.0232 13.8477L18.5921 17.4166L17.4136 18.5951L13.8447 15.0262C12.5615 16.0528 10.9341 16.667 9.16406 16.667C5.02406 16.667 1.66406 13.307 1.66406 9.16699C1.66406 5.02699 5.02406 1.66699 9.16406 1.66699C13.3041 1.66699 16.6641 5.02699 16.6641 9.16699C16.6641 10.937 16.0499 12.5644 15.0232 13.8477ZM13.3513 13.2293C14.3703 12.1792 14.9974 10.7467 14.9974 9.16699C14.9974 5.94408 12.387 3.33366 9.16406 3.33366C5.94115 3.33366 3.33073 5.94408 3.33073 9.16699C3.33073 12.3899 5.94115 15.0003 9.16406 15.0003C10.7437 15.0003 12.1762 14.3732 13.2264 13.3542L13.3513 13.2293Z"
                                        fill="white"/>
                            </svg>
                        </div>
                        <div class="mobileMenuContentHeading" style="">
                            <div class="mobileMenuContentHeadingTitle"
                                 style="font-family: 'Sofia Sans',sans-serif;font-weight: 600;font-size: 20px;line-height: 125%;letter-spacing: 0%;text-align: center;">
                                Фильтры
                            </div>
                        </div>
                        <div class="mobileSearchContentHeaderExit" @click="toggleSection('mob.sizes.show')">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path
                                        d="M8.82223 10L2.32812 3.5059L3.50663 2.32739L10.0007 8.82143L16.4948 2.32739L17.6733 3.5059L11.1792 10L17.6733 16.494L16.4948 17.6726L10.0007 11.1785L3.50663 17.6726L2.32812 16.494L8.82223 10Z"
                                        fill="#F9F9F9"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="mobileFilterContentBody" style="padding: 30px 10px;">
                    <div class="filter-section">
                        <div class="filter-title">
                            Размер
                        </div>
                        <div class="filter-content">
                            <div v-if="filterData && filterData.sizes && expandedSections.includes('sizes')"
                                 class="filter-content">
                                <label v-for="size in filterData.sizes" :key="size" class="filter-item">
                                    <input type="radio" v-model="selectedSize" :value="size.slug"
                                           @change="applyFilters"/>
                                    <span>{{ size.name }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--  -->


        <div class="filter-section">
            <div class="filter-title" @click="toggleSection('mob.occupation.show')">
                Род занятий
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                     xmlns="http://www.w3.org/2000/svg"
                     class="rotatedMb">
                    <path d="M10.0014 9.02379L5.8766 13.1486L4.6981 11.9701L10.0014 6.66671L15.3047 11.9701L14.1262 13.1486L10.0014 9.02379Z"
                          fill="#1F1F1F"/>
                </svg>
            </div>
            <div class="modalSubFilter" v-if="expandedSections && expandedSections.includes('mob.occupation.show')">
                <div class="mobileFilterContentHeader" style="padding: 18px 10px;">
                    <div class="mobileSearchContentHeaderWrapper">
                        <div class="mobileSearchContentHeaderSearch">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path
                                        d="M15.0232 13.8477L18.5921 17.4166L17.4136 18.5951L13.8447 15.0262C12.5615 16.0528 10.9341 16.667 9.16406 16.667C5.02406 16.667 1.66406 13.307 1.66406 9.16699C1.66406 5.02699 5.02406 1.66699 9.16406 1.66699C13.3041 1.66699 16.6641 5.02699 16.6641 9.16699C16.6641 10.937 16.0499 12.5644 15.0232 13.8477ZM13.3513 13.2293C14.3703 12.1792 14.9974 10.7467 14.9974 9.16699C14.9974 5.94408 12.387 3.33366 9.16406 3.33366C5.94115 3.33366 3.33073 5.94408 3.33073 9.16699C3.33073 12.3899 5.94115 15.0003 9.16406 15.0003C10.7437 15.0003 12.1762 14.3732 13.2264 13.3542L13.3513 13.2293Z"
                                        fill="white"/>
                            </svg>
                        </div>
                        <div class="mobileMenuContentHeading" style="">
                            <div class="mobileMenuContentHeadingTitle"
                                 style="font-family: 'Sofia Sans',sans-serif;font-weight: 600;font-size: 20px;line-height: 125%;letter-spacing: 0%;text-align: center;">
                                Фильтр
                            </div>
                        </div>
                        <div class="mobileSearchContentHeaderExit" @click="toggleSection('mob.occupation.show')">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path
                                        d="M8.82223 10L2.32812 3.5059L3.50663 2.32739L10.0007 8.82143L16.4948 2.32739L17.6733 3.5059L11.1792 10L17.6733 16.494L16.4948 17.6726L10.0007 11.1785L3.50663 17.6726L2.32812 16.494L8.82223 10Z"
                                        fill="#F9F9F9"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="mobileFilterContentBody" style="padding: 30px 10px;">
                    <div class="filter-section">
                        <div class="filter-title">
                            Род занятий
                        </div>
                        <div class="filter-content">
                            <div v-if="expandedSections.includes('occupation')" class="filter-content">
                                <label v-for="occ in filterData.occupations" :key="occ" class="filter-item">
                                    <input type="checkbox" v-model="selectedOccupations" :value="occ"
                                           @change="applyFilters"/>
                                    <span>{{ occ }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Toogls -->
        <div class="filter-section">
            <div class="filter-content">
                <label class="filter-item" @click="toggleSection('onSale')"
                       style="display: flex; flex-flow: row-reverse; align-items: center; justify-content: space-between;">
                    <span class="icon" v-if="!onSale">
                        <svg width="42" height="26" viewBox="0 0 42 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="2" width="40" height="22" rx="11" fill="#E5E5E5"/>
                        <g filter="url(#filter0_d_0_2547)">
                            <rect x="4" y="2" width="18" height="18" rx="9" fill="white"/>
                        </g>
                        <defs>
                            <filter id="filter0_d_0_2547" x="0" y="0" width="26" height="26"
                                    filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                                <feColorMatrix in="SourceAlpha" type="matrix"
                                               values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                                <feOffset dy="2"/>
                                <feGaussianBlur stdDeviation="2"/>
                                <feComposite in2="hardAlpha" operator="out"/>
                                <feColorMatrix type="matrix"
                                               values="0 0 0 0 0.152941 0 0 0 0 0.152941 0 0 0 0 0.152941 0 0 0 0.1 0"/>
                                <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_0_2547"/>
                                <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_0_2547"
                                         result="shape"/>
                            </filter>
                        </defs>
                    </svg>
                    </span>
                    <span class="icon" v-if="onSale">
                        <svg width="44" height="26" viewBox="0 0 44 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect width="40" height="22" rx="11" fill="#F0C224"/>
                            <g filter="url(#filter0_d_0_2547)">
                                <rect x="22" y="2" width="18" height="18" rx="9" fill="white"/>
                            </g>
                            <defs>
                                <filter id="filter0_d_0_2547" x="18" y="0" width="26" height="26"
                                        filterUnits="userSpaceOnUse"
                                        color-interpolation-filters="sRGB">
                                    <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                                    <feColorMatrix in="SourceAlpha" type="matrix"
                                                   values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"
                                                   result="hardAlpha"/>
                                    <feOffset dy="2"/>
                                    <feGaussianBlur stdDeviation="2"/>
                                    <feComposite in2="hardAlpha" operator="out"/>
                                    <feColorMatrix type="matrix"
                                                   values="0 0 0 0 0.152941 0 0 0 0 0.152941 0 0 0 0 0.152941 0 0 0 0.1 0"/>
                                    <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_0_2547"/>
                                    <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_0_2547"
                                             result="shape"/>
                                </filter>
                            </defs>
                        </svg>
                    </span>
                    <span class="title">
                        Товары со скидкой
                    </span>
                    <input type="checkbox" v-model="onSale" @change="applyFilters" style="display: none"/>
                </label>
            </div>
        </div>
        <div class="filter-section">
            <div class="filter-content">
                <label class="filter-item" @click="toggleSection('newCollection')"
                       style="display: flex; flex-flow: row-reverse; align-items: center; justify-content: space-between;">
                    <span class="icon" v-if="!newCollection">
                        <svg width="42" height="26" viewBox="0 0 42 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="2" width="40" height="22" rx="11" fill="#E5E5E5"/>
                        <g filter="url(#filter0_d_0_2547)">
                            <rect x="4" y="2" width="18" height="18" rx="9" fill="white"/>
                        </g>
                        <defs>
                            <filter id="filter0_d_0_2547" x="0" y="0" width="26" height="26"
                                    filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                                <feColorMatrix in="SourceAlpha" type="matrix"
                                               values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                                <feOffset dy="2"/>
                                <feGaussianBlur stdDeviation="2"/>
                                <feComposite in2="hardAlpha" operator="out"/>
                                <feColorMatrix type="matrix"
                                               values="0 0 0 0 0.152941 0 0 0 0 0.152941 0 0 0 0 0.152941 0 0 0 0.1 0"/>
                                <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_0_2547"/>
                                <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_0_2547"
                                         result="shape"/>
                            </filter>
                        </defs>
                    </svg>
                    </span>
                    <span class="icon" v-if="newCollection">
                        <svg width="44" height="26" viewBox="0 0 44 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect width="40" height="22" rx="11" fill="#F0C224"/>
                            <g filter="url(#filter0_d_0_2547)">
                                <rect x="22" y="2" width="18" height="18" rx="9" fill="white"/>
                            </g>
                            <defs>
                                <filter id="filter0_d_0_2547" x="18" y="0" width="26" height="26"
                                        filterUnits="userSpaceOnUse"
                                        color-interpolation-filters="sRGB">
                                    <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                                    <feColorMatrix in="SourceAlpha" type="matrix"
                                                   values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"
                                                   result="hardAlpha"/>
                                    <feOffset dy="2"/>
                                    <feGaussianBlur stdDeviation="2"/>
                                    <feComposite in2="hardAlpha" operator="out"/>
                                    <feColorMatrix type="matrix"
                                                   values="0 0 0 0 0.152941 0 0 0 0 0.152941 0 0 0 0 0.152941 0 0 0 0.1 0"/>
                                    <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_0_2547"/>
                                    <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_0_2547"
                                             result="shape"/>
                                </filter>
                            </defs>
                        </svg>
                    </span>
                    <span class="title">
                       Новая коллекция
                    </span>
                    <input type="checkbox" v-model="newCollection" @change="applyFilters" style="display: none"/>
                </label>
            </div>
        </div>
        <div class="filter-section">
            <div class="filter-content">
                <label class="filter-item" @click="toggleSection('trending')"
                       style="display: flex; flex-flow: row-reverse; align-items: center; justify-content: space-between;">
                    <span class="icon" v-if="!trending">
                        <svg width="42" height="26" viewBox="0 0 42 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="2" width="40" height="22" rx="11" fill="#E5E5E5"/>
                        <g filter="url(#filter0_d_0_2547)">
                            <rect x="4" y="2" width="18" height="18" rx="9" fill="white"/>
                        </g>
                        <defs>
                            <filter id="filter0_d_0_2547" x="0" y="0" width="26" height="26"
                                    filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                                <feColorMatrix in="SourceAlpha" type="matrix"
                                               values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                                <feOffset dy="2"/>
                                <feGaussianBlur stdDeviation="2"/>
                                <feComposite in2="hardAlpha" operator="out"/>
                                <feColorMatrix type="matrix"
                                               values="0 0 0 0 0.152941 0 0 0 0 0.152941 0 0 0 0 0.152941 0 0 0 0.1 0"/>
                                <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_0_2547"/>
                                <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_0_2547"
                                         result="shape"/>
                            </filter>
                        </defs>
                    </svg>
                    </span>
                    <span class="icon" v-if="trending">
                        <svg width="44" height="26" viewBox="0 0 44 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect width="40" height="22" rx="11" fill="#F0C224"/>
                            <g filter="url(#filter0_d_0_2547)">
                                <rect x="22" y="2" width="18" height="18" rx="9" fill="white"/>
                            </g>
                            <defs>
                                <filter id="filter0_d_0_2547" x="18" y="0" width="26" height="26"
                                        filterUnits="userSpaceOnUse"
                                        color-interpolation-filters="sRGB">
                                    <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                                    <feColorMatrix in="SourceAlpha" type="matrix"
                                                   values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"
                                                   result="hardAlpha"/>
                                    <feOffset dy="2"/>
                                    <feGaussianBlur stdDeviation="2"/>
                                    <feComposite in2="hardAlpha" operator="out"/>
                                    <feColorMatrix type="matrix"
                                                   values="0 0 0 0 0.152941 0 0 0 0 0.152941 0 0 0 0 0.152941 0 0 0 0.1 0"/>
                                    <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_0_2547"/>
                                    <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_0_2547"
                                             result="shape"/>
                                </filter>
                            </defs>
                        </svg>
                    </span>
                    <span class="title">
                        Трендовые товары
                    </span>
                    <input type="checkbox" v-model="trending" @change="applyFilters" style="display: none"/>
                </label>
            </div>
        </div>
    </div>
</div>
<script>
    window.filterData = {
        subcategories: <?php echo json_encode($args['subcategories']); ?>,
        colors: <?php echo json_encode($args['colors']); ?>,
        collections: <?php echo json_encode($args['collections']); ?>,
        occupations: <?php echo json_encode($args['occupations']); ?>,
        minPrice: <?php echo $args['min_price'] ?? 0; ?>,
        maxPrice: <?php echo $args['max_price'] ?? 0; ?>,
        sizes: <?php echo json_encode($args['sizes']); ?>,
        sortBy: <?php echo json_encode($args['sortBy']) ?>,
        vwMatch: <?php echo json_encode($args['vwMatch']) ?>,
    };
</script>