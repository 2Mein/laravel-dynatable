<?php namespace Twomein\LaravelDynatable;

use Illuminate\Support\Collection;

/**
 * Class Dynatable
 */
class Dynatable
{

    /**
     * @var $query Collection
     */
    protected $query;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var array
     */
    protected $columns;

    /**
     * @var Callback
     */
    protected $sorts;

    /**
     * @var Callback
     */
    protected $search;

    /**
     * @param $query
     */
    protected function configure($query, array $columns = [], $inputs)
    {
        $this->query = $query;

        $this->options = [
            'page-length' => (int)$inputs['perPage'],
            'page-number' => (int)$inputs['page'],
            'offset' => (int)$inputs['offset'],
            'sorts' => isset($inputs['sorts']) ? $inputs['sorts'] : null,
            'search' => isset($inputs['queries']['search']) ? $inputs['queries']['search'] : null,
        ];

        $this->setDefaultHandlers($columns);
    }

    /**
     * @param $columns
     */
    protected function setDefaultHandlers($columns)
    {
        // Define default handler for rendering content of column
        foreach ($columns as $name) {
            $this->columns[$name] = function ($row) use ($name) {
                return $row->$name;
            };
        }

        // Define default handler for ordering column
        foreach ($columns as $name) {
            $this->sorts[$name] = function ($query, $mode) use ($name) {
                $query->sortBy(function ($model) use ($name) {
                    return $model->{$name};
                });

                if ($mode == 'desc') {
                    $query->reverse();
                }

                return $query;
            };
        }

        // Define default handler for global searching
        $this->search = function ($query, $term) use ($columns) {
            return $query->filter(function ($object) use ($term, $columns) {
                foreach ($columns as $column) {
                    if (strpos($object->{$column}, $term) !== FALSE)
                        return true;
                }
            });
        };
    }

    /**
     * Define the display handler for the defined column
     *
     * @param $name
     * @param $handler
     *
     * @return $this
     */
    public function column($name, $handler)
    {
        $this->columns[$name] = $handler;

        return $this;
    }

    /**
     * Define custom sort handler for the defined column
     *
     * @param $name
     * @param $handler
     *
     * @return $this
     */
    public function sort($name, $handler)
    {
        $this->sorts[$name] = $handler;

        return $this;
    }

    /**
     * Define the search handler for the table
     *
     * @param $handler
     */
    public function search($handler)
    {
        $this->search = $handler;

        return $this;
    }

    /**
     * @return bool
     */
    protected function handleSearch()
    {
        if (!isset($this->options['search']))
            return false;

        $handler = $this->search;

        $this->query = $handler($this->query, $this->options['search']);
    }

    /**
     *
     */
    protected function handleSorting()
    {
        if (!isset($this->options['sorts']))
            return false;

        foreach ($this->options['sorts'] as $name => $mode) {
            $this->query = $this->sorts[$name]($this->query, $mode == 1 ? 'asc' : 'desc');
        }
    }

    /**
     *
     */
    protected function handlePagination()
    {
        $this->query->forPage($this->options['offset'], $this->options['page-length']);
    }

    /**
     * Creating records for the dynatables
     *
     * @return array
     */
    protected function getRecords()
    {
        $records = [];

        foreach ($this->query->all() as $row) {
            $record = [];
            foreach ($this->columns as $name => $handler) {
                $record[$name] = $handler($row);
            }
            $records[] = $record;
        }

        return $records;
    }

    /**
     * @param $query Eloquent collection to be send.
     * @param array $columns Array of columns you wish to display.
     * @param $inputs The inputs from datatables.
     * @return array
     */
    public function make($query, array $columns = [], $inputs)
    {
        $this->configure($query, $columns, $inputs);

        $datas = [];

        // Apply the search filter
        $this->handleSearch();

        $datas['totalRecordCount'] = $this->query->count();
        $datas['queryRecordCount'] = $this->query->count();

        // Filter items by pagination
        $this->handleSorting();
        $this->handlePagination();

        $datas['records'] = $this->getRecords();

        return $datas;
    }
}