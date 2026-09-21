<?php
// Стили подключаются и при выдаче готового HTML из кеша.
$this->registerCss(<<<CSS
.sx-collection-list-item-wrapper {
    margin-top: 5px;
    margin-bottom: 5px;
}
.sx-collection-list .sx-collection-list-item-wrapper {
    padding-right: 7px !important;
    padding-left: 7px !important;
}
.sx-collection-list {
    margin-right: -7px !important;
    margin-left: -7px !important;
}
.sx-collection-list-item {
    border-radius: var(--base-radius);
    overflow: hidden;
}
CSS
);
