<?php

namespace S3base\Core\Classes;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Psr\SimpleCache\InvalidArgumentException;

class AdminMenu
{
    protected $admin_menu_items = [];

    private function defaultItems()
    {
        $this->admin_menu_items = [
            [
                'id' => 'mn_general_sections',
                'priority' => 0,
                'parent_id' => null,
                'name' => trans('s3base/core::core.mn_general_sections'),
                'icon' => null,
                'url' => 'javascript:;',
                'children' => [],
                'permissions' => [],
                'active' => false,
            ], [
                'id' => 'mn_develop_sections',
                'priority' => 1,
                'parent_id' => null,
                'name' => trans('s3base/core::core.mn_develop_sections'),
                'icon' => null,
                'url' => 'javascript:;',
                'children' => [],
                'permissions' => [],
                'active' => false,
            ]
        ];

        return $this;
    }

    private function loadFromModelTypes()
    {
        $model_types = collect(model_types() ?? [])->filter(function ($model_type) {
            return isset($model_type['show_in_admin_menu']) && $model_type['show_in_admin_menu'];
        });

        foreach ($model_types as $model_type) {
            $this->admin_menu_items[] = [
                'id' => $model_type['name'],
                'priority' => $model_type['menu_position'] ?? 99,
                'parent_id' => $model_type['parent_admin_menu'] ?? null,
                'name' => trans('s3base/core::core.mn_general_sections'),
                'icon' => $model_type['menu_icon'] ?? 'feather icon-box',
                'url' => 'javascript:;',
                'children' => [],
                'permissions' => [],
                'active' => false,
            ];
        }
    }

    public function registerItem(array $options): self
    {
        if (isset($options['children'])) {
            unset($options['children']);
        }

        $defaultOptions = [
            'id' => '',
            'priority' => 99,
            'parent_id' => null,
            'name' => '',
            'icon' => null,
            'url' => '',
            'children' => [],
            'permissions' => [],
            'active' => false,
        ];

        $options = array_merge($defaultOptions, $options);
        $id = $options['id'];

        if (!$id && !app()->runningInConsole() && app()->isLocal()) {
            $calledClass = isset(debug_backtrace()[1]) ?
                debug_backtrace()[1]['class'] . '@' . debug_backtrace()[1]['function']
                :
                null;
            throw new \RuntimeException('Menu id not specified: ' . $calledClass);
        }

        if (isset($this->admin_menu_items[$id]) && $this->admin_menu_items[$id]['name'] && !app()->runningInConsole() && app()->isLocal()) {
            $calledClass = isset(debug_backtrace()[1]) ?
                debug_backtrace()[1]['class'] . '@' . debug_backtrace()[1]['function']
                :
                null;
            throw new \RuntimeException('Menu id already exists: ' . $id . ' on class ' . $calledClass);
        }

        if (isset($this->admin_menu_items[$id])) {

            $options['children'] = array_merge($options['children'], $this->admin_menu_items[$id]['children']);
            $options['permissions'] = array_merge($options['permissions'], $this->admin_menu_items[$id]['permissions']);

            $this->admin_menu_items[$id] = array_replace($this->admin_menu_items[$id], $options);

            return $this;
        }

        if ($options['parent_id']) {
            if (!isset($this->admin_menu_items[$options['parent_id']])) {
                $this->admin_menu_items[$options['parent_id']] = ['id' => $options['parent_id']] + $defaultOptions;
            }

            $this->admin_menu_items[$options['parent_id']]['children'][] = $options;

            $permissions = array_merge($this->admin_menu_items[$options['parent_id']]['permissions'], $options['permissions']);
            $this->admin_menu_items[$options['parent_id']]['permissions'] = $permissions;
        } else {
            $this->admin_menu_items[$id] = $options;
        }

        return $this;
    }

    /**
     * Rearrange links
     * @return Collection
     * @throws Exception
     */
    public function getAll(): Collection
    {
        $this->defaultItems()->loadFromModelTypes();

        $currentUrl = URL::full();

        $prefix = request()->route()->getPrefix();
        if (!$prefix || $prefix === 'admin') {
            $uri = explode('/', request()->route()->uri());
            $prefix = end($uri);
        }

        $routePrefix = '/' . $prefix;

        if (request()->isSecure()) {
            $protocol = 'https://';
        } else {
            $protocol = 'http://';
        }
        $protocol .= 'admin';

        foreach ($this->admin_menu_items as $key => &$link) {
            if ($link['permissions'] && !Auth::user()->hasAnyPermission($link['permissions'])) {
                Arr::forget($this->admin_menu_items, $key);
                continue;
            }

            $link['active'] = $currentUrl == $link['url'] ||
                (Str::contains($link['url'], $routePrefix) &&
                    !in_array($routePrefix, ['//', '/admin']) &&
                    !Str::startsWith($link['url'], $protocol));
            if (!count($link['children'])) {
                continue;
            }

            $link['children'] = collect($link['children'])->sortBy('priority')->toArray();

            foreach ($link['children'] as $subKey => $subMenu) {
                if ($subMenu['permissions'] && !Auth::user()->hasAnyPermission($subMenu['permissions'])) {
                    Arr::forget($link['children'], $subKey);
                    continue;
                }

                if ($currentUrl == $subMenu['url'] || Str::contains($currentUrl, $subMenu['url'])) {
                    $link['children'][$subKey]['active'] = true;
                    $link['active'] = true;
                }
            }
        }

        return collect(apply_filter('get_admin_menu_items', $this->admin_menu_items))->sortBy('priority');
    }
}
