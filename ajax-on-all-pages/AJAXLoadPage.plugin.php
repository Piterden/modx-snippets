<?php
if ($modx->event->name == 'OnLoadWebDocument') {
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        if ($modx->resource->get('id') == '356' || $modx->resource->get('id') == '357') {
            $modx->resource->set('template', 0);
        } else {
            $modx->resource->set('template', 3);
        }
        $modx->resource->set('cacheable', 0);
    }
}
