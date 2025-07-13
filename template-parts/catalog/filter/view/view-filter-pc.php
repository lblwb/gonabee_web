<div id="viewSortFilter" class="shopCatalogViewFilter" style="margin-bottom: 30px;" v-cloak>
    <div class="viewFilterWrapper" style="display: flex; width: 100%; justify-content: flex-end; align-items: center">
        <div class="viewFilterTypes">
            <div class="viewFilterTypesWrapper" style="display: flex; gap: 10px">
                <div class="viewFilterTypesItem" @click="selectViewType('compact')" :class="{__Active: selectedViewType === 'compact'}"  aria-label="Переключить на сеточный вид (3 блока)">
                    <svg width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="0.5" y="0.5" width="49" height="49" rx="24.5" stroke="currentColor"/>
                        <rect x="19" y="18" width="4" height="5" fill="currentColor"/>
                        <rect x="26" y="18" width="4" height="5" fill="currentColor"/>
                        <rect x="12" y="18" width="4" height="5" fill="currentColor"/>
                        <rect x="19" y="26" width="4" height="5" fill="currentColor"/>
                        <rect x="26" y="26" width="4" height="5" fill="currentColor"/>
                        <rect x="33" y="18" width="4" height="5" fill="currentColor"/>
                        <rect x="33" y="26" width="4" height="5" fill="currentColor"/>
                        <rect x="12" y="26" width="4" height="5" fill="currentColor"/>
                    </svg>
                </div>
                <div class="viewFilterTypesItem" @click="selectViewType('grid')" aria-label="Переключить на компактный вид (4 блока)" :class="{__Active: selectedViewType === 'grid'}">
                    <svg width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="0.5" y="0.5" width="49" height="49" rx="24.5" stroke="currentColor"/>
                        <rect x="23" y="18" width="4" height="5" fill="currentColor"/>
                        <rect x="30" y="18" width="4" height="5" fill="currentColor"/>
                        <rect x="16" y="18" width="4" height="5" fill="currentColor"/>
                        <rect x="23" y="26" width="4" height="5" fill="currentColor"/>
                        <rect x="30" y="26" width="4" height="5" fill="currentColor"/>
                        <rect x="16" y="26" width="4" height="5" fill="currentColor"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="viewFilterSort" v-if="sortItems">
            <div class="viewFilterSortInput">
                <div class="viewFilterSort" v-if="sortItems">
                    <div class="viewFilterSortInput">
                        <div class="customSelectWrapper" role="combobox" :aria-expanded="isOpen" @click="toggleSelect" :class="{ open: isOpen }" @focusout="handleBlur" @keydown="handleKeydown" tabindex="0">
                            <div class="customSelectDisplay">
                                <span>{{ selectedSortLabel }}</span>
                                <span class="customSelectArrow">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10.0014 9.02379L5.8766 13.1486L4.6981 11.9701L10.0014 6.66671L15.3047 11.9701L14.1262 13.1486L10.0014 9.02379Z" fill="#1F1F1F"/>
                            </svg>
                        </span>
                            </div>
                            <ul class="customSelectOptions" v-show="isOpen">
                                <li v-for="sort in sortItems" :key="sort.slug" @click="selectSort(sort.slug)" :class="{ selected: selectedSort === sort.slug }" role="option" :aria-selected="selectedSort === sort.slug">
                                    {{ sort.name }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div v-else class="viewFilterSort">
                    <p>Сортировка недоступна</p>
                </div>
            </div>
        </div>
        <div v-else class="viewFilterSort">
            <p>Сортировка недоступна</p>
        </div>
    </div>
</div>