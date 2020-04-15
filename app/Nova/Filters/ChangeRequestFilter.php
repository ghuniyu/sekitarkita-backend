<?php

namespace App\Nova\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class ChangeRequestFilter extends Filter
{

    private $columnName;
    private $options;

    public function __construct($columnName, array $options, $customName = 'Filter')
    {
        $this->columnName = $columnName;
        $this->options = $options;
        $this->name = $customName;
    }

    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';

    public function key()
    {
        return 'filter-' . $this->columnName;
    }

    /**
     * Apply the filter to the given query.
     *
     * @param Request $request
     * @param Builder $query
     * @param mixed $value
     * @return Builder
     */
    public function apply(Request $request, $query, $value)
    {
        return $query->where($this->columnName, $value);
    }

    /**
     * Get the filter's available options.
     *
     * @param Request $request
     * @return array
     */
    public function options(Request $request)
    {
        return $this->options;
    }
}
