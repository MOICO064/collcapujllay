<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class Sidebar extends Component
{
    public array $links;

    public function __construct(array $navItems = [])
    {
        $configItems = config('sidebar.nav_items', []);
        $icons = config('sidebar.icons', []);
        $currentUrl = url()->current();

        $items = $navItems ?: $configItems;

        $this->links = array_map(function (array $item) use ($icons, $currentUrl) {
            $href = $this->resolveHref($item);

            return [
                'label' => $item['label'] ?? '',
                'href' => $href,
                'icon' => $icons[$item['icon'] ?? ''] ?? null,
                'isActive' => $this->resolveActive($item, $href, $currentUrl),
            ];
        }, $items);
    }

    protected function resolveHref(array $item): string
    {
        if (!empty($item['route']) && Route::has($item['route'])) {
            return route($item['route']);
        }

        return $item['href'] ?? '#';
    }

    protected function resolveActive(array $item, string $href, string $currentUrl): bool
    {
        if (!empty($item['match'])) {
            foreach (Arr::wrap($item['match']) as $pattern) {
                if (Route::is($pattern)) {
                    return true;
                }
            }
        }

        if (!empty($item['route']) && Route::has($item['route']) && Route::is($item['route'])) {
            return true;
        }

        $cleanCurrent = strtok($currentUrl, '?');

        if ($href !== '#' && filled($href) && Str::startsWith($cleanCurrent, $href)) {
            return true;
        }

        return false;
    }

    public function render()
    {
        return view('components.sidebar');
    }
}
