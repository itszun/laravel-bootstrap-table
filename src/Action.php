<?php

namespace Itszun\LaravelBootstrapTable;

use Closure;
use Itszun\LaravelBootstrapTable\Traits\Makeable;

class Action {
    use Makeable;

    protected $url = "";
    protected $color = "primary";
    protected $size = "";
    
    public function url(string|Closure $url) {
        $this->url = $url;
        return $this;
    }

    public function color($color = "primary") {
        $this->color = $color;
        return $this;
    }

    public function size($size = "sm")
    {
        $this->size = $size;
        return $this;
    }

    public function render($model) {
        $data = $this->get_array();
        $data['url'] = is_callable($this->url) ? ($this->url)($model) : $this->url;

        return view('components.table-action-button', $data)->render();
    }

    public function get_array()
    {
        return collect([
            "field" => $this->field,
            "url" => $this->url,
            "color" => $this->color,
            "size" => $this->size,
        ])->filter()->toArray();
    }
}