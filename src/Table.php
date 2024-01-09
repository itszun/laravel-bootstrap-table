<?php

namespace Itszun\LaravelBootstrapTable;

use Itszun\LaravelBootstrapTable\Action;
use Itszun\LaravelBootstrapTable\Column;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;

class Table {

    protected $name = "";

    protected $title = "";

    protected $idField = "";

    protected $columns = [];
    protected $actions = [];

    protected $striped = true;
    protected $sidePagination = 'server';
    protected $smartDisplay = false;
    protected $cookie = true;
    protected $cookieExpire = '1h';
    protected $showExport = false;
    protected $exportTypes = ['json', 'xml', 'csv', 'txt', 'excel'];
    protected $showFilter = true;
    protected $flat = true;
    protected $keyEvents = false;
    protected $showMultiSort = false;
    protected $reorderableColumns = false;
    protected $resizable = false;
    protected $pagination = true;
    protected $cardView = false;
    protected $detailView = false;
    protected $search = true;
    protected $showRefresh = true;
    protected $showToggle = true;
    protected $clickToSelect = true;
    protected $showColumns = true;

    protected $request_get_config = true;
    protected $query = null;

    protected static $like_keyword = "like";

    public function __construct()
    {
        $this->request_get_config = request()->get('get_config');
        if(config('database.default') === "pgsql") {
            static::$like_keyword = "ilike";
        }
    }

    public static function make($name, $title = "") {
        $obj = new Table();
        $obj->name = $name;
        $obj->title = $title;
        return $obj;   
    }

    public function idField($field) {
        $this->idField = $field;
        return $this;
    }

    public function columns($columns) {
        $this->columns = $columns;
        return $this;
    }

    public function actions($actions) {
        $this->actions = $actions;
        return $this;
    }

    public function query(Builder|EloquentBuilder $query) 
    {
        $this->query = $query;
        return $this;
    }

    public function get_columns_array()
    {
        $columns = [];
        foreach($this->columns as $col) {
            $columns[] = $col->get_array();
        }
        return $columns;
    }

    public function get_searchable_column_names()
    {
        $names = [];
        foreach($this->columns as $col) {
            if($col->isSearchable()) {
                $names[] = $col->getName();
            }
        }
        return $names;
    }
    

    public function get_array()
    {
        $columns = $this->get_columns_array();
        if(count($this->actions) > 0) {
            $columns[] = Column::make('action', __('Aksi'))->get_array();
        }
        return [
            'columns' => $columns,
            'idField' => $this->idField,
            "striped" => $this->striped,
            "sidePagination" => $this->sidePagination,
            "smartDisplay" => $this->smartDisplay,
            "cookie" => $this->cookie,
            "cookieExpire" => $this->cookieExpire,
            "showExport" => $this->showExport,
            "exportTypes" => $this->exportTypes,
            "showFilter" => $this->showFilter,
            "flat" => $this->flat,
            "keyEvents" => $this->keyEvents,
            "showMultiSort" => $this->showMultiSort,
            "reorderableColumns" => $this->reorderableColumns,
            "resizable" => $this->resizable,
            "pagination" => $this->pagination,
            "cardView" => $this->cardView,
            "detailView" => $this->detailView,
            "search" => $this->search,
            "showRefresh" => $this->showRefresh,
            "showToggle" => $this->showToggle,
            "clickToSelect" => $this->clickToSelect,
            "showColumns" => $this->showColumns,
        ];
    }

    public function get_data() {
        $request = request();
        $order = $request->order;
        $search = $request->get('search');
        $query = $this->query;
        $actions = $this->actions;
        $has_action = !empty($this->actions);

        $query->orderBy(request()->get('sort', 'id'),$request->get('order', 'asc'));
        
        if($this->search && $search) {
            foreach($this->get_searchable_column_names() as $colname) {
                $query->orWhere($colname, Table::$like_keyword, "%".$search."%");
            }
        }

        $rows = $query
            ->get();

        $rows = $rows->map(function($model) use ($has_action, $actions) {
            $assoc = $model->toArray();
            if($has_action) {
                $assoc['action'] = static::renderAction($model, $actions);
            }
            return $assoc;
        });

        $total = $this->query->count();
        return compact('rows', 'total');
    }

    public static function renderAction($model, $actions) {
        $buttons = collect($actions)->reduce(function ($acc, Action $action) use ($model) {
            $acc .= $action->render($model);
            return $acc;
        }, "");
        return view('components.table-action-group', [
            'buttons' => $buttons
        ])->render();
    }

    public function render()
    {
        if($this->request_get_config) {
            return $this->get_array();
        }
        return $this->get_data();
    }
}