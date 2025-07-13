<div class="shopCatalogMore">
    <button id="load_more_products"
            class="load-more-products"
            data-paged="1"
            data-term="<?php echo get_queried_object_id(); ?>"
            data-url="<?php echo admin_url('admin-ajax.php'); ?>">
        Загрузить больше
        <span class="loading-spinner" style="display:none;">

        </span>
    </button>
</div>