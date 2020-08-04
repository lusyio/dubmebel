<div class="search-div position-relative">
    <form id="searchForm" role="search" method="get" class="search-form"
          action="<?php echo esc_url(home_url('/')); ?>">
        <div class="input-group">
            <input type="search" class="search-field form-control"
                   placeholder="Поиск..."
                   value="<?php echo esc_attr(get_search_query()); ?>" name="s"
                   title="<?php _ex('Search for:', 'label', 'wp-bootstrap-starter'); ?>">
            <div class="input-group-append">
                <button id="searchBtn" class="btn btn-outline-secondary" type="submit">
                    <img src="/wp-content/themes/storefront-child/svg/main/search.svg" alt="">
                </button>
            </div>
        </div>
    </form>
</div>