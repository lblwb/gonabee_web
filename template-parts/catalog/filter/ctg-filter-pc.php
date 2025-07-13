<div id="appMainFilter" v-cloak>
    <div class="appFilterWrapper">
        <!-- Cat -->
        <div class="filter-section"
             v-if="filterData && Array.isArray(filterData.subcategories) && filterData.subcategories !== []">
            <div class="filter-title" @click="toggleSection('subcategory')">
                Категории
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"
                     :class="{ 'rotated': expandedSections && expandedSections.includes('subcategory') }">
                    <path
                            d="M10.0014 9.02379L5.8766 13.1486L4.6981 11.9701L10.0014 6.66671L15.3047 11.9701L14.1262 13.1486L10.0014 9.02379Z"
                            fill="#1F1F1F"/>
                </svg>
            </div>

            <div v-if="displayedCat && expandedSections.includes('subcategory')" class="filter-content"
                 style="margin-bottom: 20px">
                <div v-for="subCatItem in displayedCat" :key="subCatItem.slug" class="filter-group"
                     style="margin-bottom: 8px">
                    <div class="filter-item"
                         style="display: flex; align-items: center; cursor: pointer;">
                        <span class="subCatItem" @click="toggleRadio('selectedSubcategory', subCatItem.slug)" :class="{__Active: subCatItem.slug == selectedSubcategory}">{{ subCatItem.name }} ({{ subCatItem.count }})</span>
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"
                            @click="toggleAccordion(subCatItem.slug)"
                             :class="{ 'rotated': !openedSubcats.includes(subCatItem.slug) }" v-if="subCatItem.children?.length" >
                            <path
                                    d="M10.0014 9.02379L5.8766 13.1486L4.6981 11.9701L10.0014 6.66671L15.3047 11.9701L14.1262 13.1486L10.0014 9.02379Z"
                                    fill="#1F1F1F"/>
                        </svg>
                    </div>
                    <div v-if="openedSubcats.includes(subCatItem.slug) && subCatItem.children?.length"
                         class="sub-subcategory" style="margin-left: 16px; margin-top: 4px; margin-top: 10px">
                        <label v-for="child in subCatItem.children" :key="child.slug" class="filter-item"
                               style="display: flex; align-items: center; cursor: pointer;">
                            <input type="radio" v-model="selectedSubcategory" :value="child.slug"
                                   @click.stop="toggleRadio('selectedSubcategory', child.slug)" style="display: none"/>
                            <span class="subCatItem" :class="{__Active: child.slug == selectedSubcategory}">{{ child.name }} ({{ child.count }})</span>
                        </label>
                    </div>
                </div>
                <a class="filter-item" style="color: #E2B53C;" @click.prevent="toggleShowAllSubcat"><span
                            class="subCatItem"><span v-if="!showAllSubcat">Показать больше</span><span
                                v-else>Скрыть</span></span></a>
            </div>

        </div>

        <!-- Акции -->
        <div class="filter-section">
            <div class="filter-title" @click="toggleSection('promotions')">
                Акции
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"
                     :class="{ 'rotated': expandedSections && expandedSections.includes('promotions') }">
                    <path
                            d="M10.0014 9.02379L5.8766 13.1486L4.6981 11.9701L10.0014 6.66671L15.3047 11.9701L14.1262 13.1486L10.0014 9.02379Z"
                            fill="#1F1F1F" />
                </svg>
            </div>
            <div v-if="expandedSections && expandedSections.includes('promotions')" class="filter-content">
                <label class="filter-item">
                    <input type="checkbox" v-model="promotionActive" @change="applyFilters" />
                    <span> -5% при оплате Долями</span>
                </label>
            </div>
        </div>

        <!-- Прайс -->
        <div class="filter-section">
            <div class="filter-title" @click="toggleSection('price')">
                Цена
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"
                     :class="{ 'rotated': expandedSections && expandedSections.includes('price') }">
                    <path
                            d="M10.0014 9.02379L5.8766 13.1486L4.6981 11.9701L10.0014 6.66671L15.3047 11.9701L14.1262 13.1486L10.0014 9.02379Z"
                            fill="#1F1F1F" />
                </svg>
            </div>
            <div v-if="expandedSections && expandedSections.includes('price')" class="filter-content">
                <div class="sliderRange">
                    <div id="price-slider"></div>
                </div>
                <div class="sliderWrapper"
                     style="display: flex; justify-content: space-between; align-items: center;gap: 18px;">
                    <div class="sliderRangeInput" style="display: flex; gap: 10px;align-items: center;">
                        <label for="">От</label>
                        <input type="number" @keydown.enter="applyFilters" v-model.number="minPrice" :min="filterData.minPrice" :max="filterData.maxPrice"
                               style="padding: 10px 18px; width: 100%; border-radius: 5px; border: solid 1px #1F1F1F10;" />
                    </div>
                    <div class="sliderRangeInput" style="display: flex; gap: 10px;align-items: center;">
                        <label for="">До</label>
                        <input type="number" @keydown.enter="applyFilters"  v-model.number="maxPrice" :min="filterData.minPrice" :max="filterData.maxPrice"
                               style="padding: 10px 18px; width: 100%; border-radius: 5px; border: solid 1px #1F1F1F10;" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Размер -->
        <div class="filter-section" v-if="filterData && filterData.sizes">
            <div class="filter-title" @click="toggleSection('sizes')">
                Размер
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"
                     :class="{ 'rotated': expandedSections.includes('sizes') }">
                    <path
                            d="M10.0014 9.02379L5.8766 13.1486L4.6981 11.9701L10.0014 6.66671L15.3047 11.9701L14.1262 13.1486L10.0014 9.02379Z"
                            fill="#1F1F1F" />
                </svg>
            </div>
            <!--            {{filterData}}-->
            <div v-if="filterData && filterData.sizes && expandedSections.includes('sizes')" class="filter-content">
                <label v-for="size in displayedSizes" :key="size" class="filter-item">
                    <input type="radio" v-model="selectedSize" :value="size.slug" @click="toggleRadio('selectedSize', size.slug)" />
                    <span>{{ size.name }}</span>
                </label>
            </div>
            <a :href="" class="filter-item" style="color: #E2B53C;">
                <a class="filter-item" style="color: #E2B53C;" @click.prevent="toggleShowAllSizes"><span v-if="!showAllSizes">Показать больше</span><span v-else>Скрыть</span></a>
            </a>
        </div>

        <!-- Цвет -->
        <div class="filter-section" v-if="filterData && filterData.colors">
            <div class="filter-title" @click="toggleSection('colors')">
                Цвет
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"
                     :class="{ 'rotated': expandedSections.includes('colors') }">
                    <path
                            d="M10.0014 9.02379L5.8766 13.1486L4.6981 11.9701L10.0014 6.66671L15.3047 11.9701L14.1262 13.1486L10.0014 9.02379Z"
                            fill="#1F1F1F" />
                </svg>
            </div>
            <div v-if="displayedColors !== null && expandedSections.includes('colors')" class="filter-content" style="margin-bottom: 20px">
                <label v-for="color in displayedColors" :key="color.slug" class="filter-item color-item">
                    <input type="checkbox" v-model="selectedColors" :value="color.slug" @change="applyFilters" />
                    <span class="color-swatch" :style="{ backgroundColor: color.hex }"></span>
                    <span>{{ color.name }}</span>
                </label>
                <a class="filter-item" style="color: #E2B53C;" @click.prevent="toggleShowAllColors"><span v-if="!showAllColors">Показать больше</span><span v-else>Скрыть</span></a>
            </div>
        </div>

        <!-- Коллекция -->
        <div class="filter-section" v-if="filterData && filterData.collections">
            <div class="filter-title" @click="toggleSection('collections')">
                Коллекция
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"
                     :class="{ 'rotated': expandedSections.includes('collections') }">
                    <path
                            d="M10.0014 9.02379L5.8766 13.1486L4.6981 11.9701L10.0014 6.66671L15.3047 11.9701L14.1262 13.1486L10.0014 9.02379Z"
                            fill="#1F1F1F" />
                </svg>
            </div>
            <div v-if="expandedSections.includes('collections')" class="filter-content" style="margin-bottom: 20px;">
                <label v-for="col in filterData.collections" :key="col.slug" class="filter-item">
                    <input type="checkbox" v-model="selectedCollections" :value="col.slug" @change="applyFilters" />
                    <span>{{ col.name }}</span>
                </label>
            </div>
            <a :href="" class="filter-item" style="color: #E2B53C;">
                <span class="subCatItem"><span>Показать больше</span></span>
            </a>
        </div>

        <!-- Род занятий -->
        <div class="filter-section" v-if="filterData && filterData.occupations">
            <div class="filter-title" @click="toggleSection('occupation')">
                Род занятий
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"
                     :class="{ 'rotated': expandedSections.includes('occupation') }">
                    <path
                            d="M10.0014 9.02379L5.8766 13.1486L4.6981 11.9701L10.0014 6.66671L15.3047 11.9701L14.1262 13.1486L10.0014 9.02379Z"
                            fill="#1F1F1F" />
                </svg>
            </div>
            <div v-if="expandedSections.includes('occupation')" class="filter-content">
                <label v-for="occ in filterData.occupations" :key="occ" class="filter-item">
                    <input type="checkbox" v-model="selectedOccupations" :value="occ" @change="applyFilters" />
                    <span>{{ occ }}</span>
                </label>
            </div>
        </div>

        <!-- Коллекция -->
        <div class="filter-section">
            <div class="filter-content">
                <label class="filter-item" @click="toggleSection('onSale')"
                       style="display: flex; flex-flow: row-reverse; align-items: center; justify-content: space-between;">
                    <span class="icon" v-if="!onSale">
                        <svg width="40" height="22" viewBox="0 0 40 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                    d="M29 0H11C4.92487 0 0 4.92487 0 11C0 17.0751 4.92487 22 11 22H29C35.0751 22 40 17.0751 40 11C40 4.92487 35.0751 0 29 0Z"
                                    fill="#E5E5E5" />
                            <path
                                    d="M20 11C20 6.02944 15.9706 2 11 2C6.02944 2 2 6.02944 2 11C2 15.9706 6.02944 20 11 20C15.9706 20 20 15.9706 20 11Z"
                                    fill="white" />
                        </svg>
                    </span>
                    <span class="icon" v-if="onSale">
                          <svg width="40" height="22" viewBox="0 0 40 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                    d="M29 0H11C4.92487 0 0 4.92487 0 11C0 17.0751 4.92487 22 11 22H29C35.0751 22 40 17.0751 40 11C40 4.92487 35.0751 0 29 0Z"
                                    fill="#F0C224" />
                            <path
                                    d="M38 11C38 6.02944 33.9706 2 29 2C24.0294 2 20 6.02944 20 11C20 15.9706 24.0294 20 29 20C33.9706 20 38 15.9706 38 11Z"
                                    fill="white" />
                        </svg>
                    </span>
                    <span class="title">
                        Товары со скидкой
                    </span>
                    <input type="checkbox" v-model="onSale" @change="applyFilters" style="display: none" />
                </label>
            </div>
        </div>
        <div class="filter-section">
            <div class="filter-content">
                <label class="filter-item" @click="toggleSection('newCollection')"
                       style="display: flex; flex-flow: row-reverse; align-items: center; justify-content: space-between;">
                    <span class="icon" v-if="!newCollection">
                        <svg width="40" height="22" viewBox="0 0 40 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                    d="M29 0H11C4.92487 0 0 4.92487 0 11C0 17.0751 4.92487 22 11 22H29C35.0751 22 40 17.0751 40 11C40 4.92487 35.0751 0 29 0Z"
                                    fill="#E5E5E5" />
                            <path
                                    d="M20 11C20 6.02944 15.9706 2 11 2C6.02944 2 2 6.02944 2 11C2 15.9706 6.02944 20 11 20C15.9706 20 20 15.9706 20 11Z"
                                    fill="white" />
                        </svg>
                    </span>
                    <span class="icon" v-if="newCollection">
                        <svg width="40" height="22" viewBox="0 0 40 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                    d="M29 0H11C4.92487 0 0 4.92487 0 11C0 17.0751 4.92487 22 11 22H29C35.0751 22 40 17.0751 40 11C40 4.92487 35.0751 0 29 0Z"
                                    fill="#F0C224" />
                            <path
                                    d="M38 11C38 6.02944 33.9706 2 29 2C24.0294 2 20 6.02944 20 11C20 15.9706 24.0294 20 29 20C33.9706 20 38 15.9706 38 11Z"
                                    fill="white" />
                        </svg>
                    </span>
                    <span class="title">
                        Новая коллекция
                    </span>
                    <input type="checkbox" v-model="newCollection" @change="applyFilters" style="display: none" />
                </label>
            </div>
        </div>
        <div class="filter-section">
            <div class="filter-content">
                <label class="filter-item" @click="toggleSection('trending')"
                       style="display: flex; flex-flow: row-reverse; align-items: center; justify-content: space-between;">
                    <span class="icon" v-if="!trending">
                        <svg width="40" height="22" viewBox="0 0 40 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                    d="M29 0H11C4.92487 0 0 4.92487 0 11C0 17.0751 4.92487 22 11 22H29C35.0751 22 40 17.0751 40 11C40 4.92487 35.0751 0 29 0Z"
                                    fill="#E5E5E5" />
                            <path
                                    d="M20 11C20 6.02944 15.9706 2 11 2C6.02944 2 2 6.02944 2 11C2 15.9706 6.02944 20 11 20C15.9706 20 20 15.9706 20 11Z"
                                    fill="white" />
                        </svg>
                    </span>
                    <span class="icon" v-if="trending">
                        <svg width="40" height="22" viewBox="0 0 40 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                    d="M29 0H11C4.92487 0 0 4.92487 0 11C0 17.0751 4.92487 22 11 22H29C35.0751 22 40 17.0751 40 11C40 4.92487 35.0751 0 29 0Z"
                                    fill="#F0C224" />
                            <path
                                    d="M38 11C38 6.02944 33.9706 2 29 2C24.0294 2 20 6.02944 20 11C20 15.9706 24.0294 20 29 20C33.9706 20 38 15.9706 38 11Z"
                                    fill="white" />
                        </svg>
                    </span>
                    <span class="title">
                        Трендовые товары
                    </span>
                    <input type="checkbox" v-model="trending" @change="applyFilters" style="display: none" />
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
        minPrice: <?php echo $args['min_price']; ?>,
        maxPrice: <?php echo $args['max_price']; ?>,
        sizes: <?php echo json_encode($args['sizes']); ?>,
        sortBy: <?php echo json_encode($args['sortBy']) ?>,
        vwMatch: <?php echo json_encode($args['vwMatch']) ?>,
    };
</script>