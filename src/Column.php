<?php

namespace Itszun\LaravelBootstrapTable;

use Illuminate\Support\Str;
use Itszun\LaravelBootstrapTable\Traits\Makeable;

class Column {
    use Makeable;

    protected $sortable = false;
    protected $order = false;
    protected $searchable = false;
    protected $align = 'center';
    protected $valign = null;
    protected $width = null;

    public function sortable($status = true) {
        $this->sortable = $status;
        return $this;
    }

    public function orderable($status = true) {
        $this->order = $status;
        return $this;
    }

    public function searchable($status = true) {
        $this->searchable = $status;
        return $this;
    }

    public function isSearchable() {
        return $this->searchable;
    }

    public function align($align = "center") {
        $this->align = $align;
        return $this;
    }

    public function valign($valign = "center") {
        $this->valign = $valign;
        return $this;
    }

    public function width($width = "center") {
        $this->width = $width;
        return $this;
    }

    public function getId()
    {
        return $this->field;
    }

    public function getName()
    {
        return $this->field;
    }

    public function get_array()
    {
        return collect([
            "field" => $this->field,
            "title" => $this->title,
            "sortable" => $this->sortable,
            "order" => $this->order,
            "searchable" => $this->searchable,
            "align" => $this->align,
            "valign" => $this->valign,
            "width" => $this->width,
        ])->filter()->toArray();
    }
}