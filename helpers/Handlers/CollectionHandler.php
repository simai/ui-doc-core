<?php

    namespace App\Helpers\Handlers;

    use Closure;
    use Illuminate\Support\Arr;
    use TightenCo\Jigsaw\Collection\Collection;
    use TightenCo\Jigsaw\IterableObject;
    use Illuminate\Support\Collection as BaseCollection;
    class CollectionHandler extends Collection
    {

        /**
         * @var \Illuminate\Support\HigherOrderCollectionProxy|mixed
         */
        public $settings;
        public $name;

        public static function withSettings(IterableObject $settings, $name): CollectionHandler|static
        {
            $collection = new static;
            $collection->settings = $settings;
            $collection->name = $name;

            return $collection;
        }

        public function loadItems(BaseCollection $items): CollectionHandler
        {
            $sortedItems = $this
                ->defaultSort($items)
                ->map($this->getMap())
                ->filter($this->getFilter())
                ->keyBy(function ($item) {
                    return trim(str_replace('\\','/',$item->_meta->relativePath), '/') . '/' . $item->getFilename();
                });

            return $this->updateItems($this->addAdjacentItems($sortedItems));
        }



        private function defaultSort($items)
        {
            $sortSettings = collect(Arr::get($this->settings, 'sort'))->map(function ($setting) {
                return [
                    'key' => ltrim($setting, '-+'),
                    'direction' => $setting[0] === '-' ? -1 : 1,
                ];
            });

            if (! $sortSettings->count()) {
                $sortSettings = [['key' => 'filename', 'direction' => 1]];
            }

            return $items->sort(function ($item_1, $item_2) use ($sortSettings) {
                return $this->compareItems($item_1, $item_2, $sortSettings);
            });
        }

        private function compareItems($item_1, $item_2, $sortSettings)
        {

            foreach ($sortSettings as $setting) {
                $value_1 = $this->getValueForSorting($item_1, Arr::get($setting, 'key'));
                $value_2 = $this->getValueForSorting($item_2, Arr::get($setting, 'key'));

                if ($value_1 > $value_2) {
                    return $setting['direction'];
                } elseif ($value_1 < $value_2) {
                    return -$setting['direction'];
                }
            }
        }

        private function getValueForSorting($item, $key): string
        {
            return strtolower($item->$key instanceof Closure ? $item->$key($item) : $item->get($key) ?? $item->_meta->get($key) ?? '');
        }

        private function getFilter()
        {
            $filter = Arr::get($this->settings, 'filter');

            if ($filter) {
                return $filter;
            }

            return function ($item) {
                return true;
            };
        }

        private function getMap()
        {
            $map = Arr::get($this->settings, 'map');

            if ($map) {
                return $map;
            }

            return function ($item) {
                return $item;
            };
        }

        private function addAdjacentItems(BaseCollection $items): BaseCollection
        {
            $count = $items->count();
            $adjacentItems = $items->map(function ($item) {
                return trim(str_replace('\\','/',$item->_meta->relativePath), '/') . '/' . $item->getFilename();
            });
            $previousItems = $adjacentItems->prepend(null)->take($count);
            $nextItems = $adjacentItems->push(null)->take(-$count);

            return $items->each(function ($item) use ($previousItems, $nextItems) {
                $item->_meta->put('previousItem', $previousItems->shift())->put('nextItem', $nextItems->shift());
            });
        }
    }
