<?php

namespace Picobaz\GridView;

class SerialColumns
{
    protected static $headerLabel = '#';
    protected static $counter = 0;

    public static function run($gridView)
    {
        $counter = $gridView->pagination
            ? (($gridView->dataProvider->currentPage() - 1) * $gridView->dataProvider->perPage()) + 1
            : 1;
        $counter += self::$counter;
        self::$counter++;
        return $counter;
    }

    public static function headerLabel()
    {
        return self::$headerLabel;
    }
}