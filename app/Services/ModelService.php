<?php

namespace App\Services;

use App\Custom\ModelCrud;
use Yajra\DataTables\Facades\DataTables;

abstract class ModelService
{
	/**
	 * @var ModelCrud
	 */
	protected $modelCrud;

	public function __construct()
	{
		$this->modelCrud = new ModelCrud();
	}

	/**
	 * Get fresh instance of model
	 *
	 * @return \Illuminate\Database\Eloquent\Model
	 */
	public function getNewModel()
	{
		return $this->query()->newModelInstance();
	}

	/**
	 * Add relation to query builder
	 *
	 * @param $relations
	 *
	 * @return $this
	 */
	public function with($relations)
	{
		$this->query()->with(is_array($relations) ? $relations : func_get_args());

		return $this;
	}

	/**
	 * Add relation_count in the response
	 *
	 * @param string $relations
	 *
	 * @return $this
	 */
	public function withCount(string $relations)
	{
		$this->query()->withCount(is_array($relations) ? $relations : func_get_args());

		return $this;
	}

	/**
	 * Get collection with pagination
	 *
	 * @param       $limit
	 * @param array $columns
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	public function getPaginatedList($limit, $columns = ['*'])
	{
		return $this->query()->paginate($limit, $columns);
	}

	/**
	 * Get latest collection with pagination
	 *
	 * @param       $limit
	 * @param array $columns
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	public function getLatestPaginatedList($limit, $columns = ['*'])
	{
		return $this->query()->latest()->paginate($limit, $columns);
	}

	/**
	 * Get collection without pagination
	 *
	 * @param array $columns
	 *
	 * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
	 */
	public function getAll($columns = ['*'])
	{
		return $this->query()->get($columns);
	}

	/**
	 * Get json response for DataTable
	 *
	 * @param array $columns
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getForDataTable($columns = ['*'])
	{
		return DataTables::eloquent($this->query()->select($columns))
		                 ->addColumn('action', function ($model) {
			                 return (string) view('admin.extras.actions', ['model' => $model]);
		                 })
		                 ->toJson();
	}

	/**
	 * Get a row searching by id
	 *
	 * @param       $id
	 * @param array $columns
	 *
	 * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
	 */
	public function find($id, $columns = ['*'])
	{
		return $this->query()->find($id, $columns);
	}

	/**
	 * Get a row searching by column name
	 *
	 * @param $column
	 * @param $value
	 * @param array $columns
	 *
	 * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
	 */
	public function findBy($column, $value, $columns = ['*'])
	{
		return $this->query()->where($column, $value)->first($columns);
	}

	/**
	 * Get a row searching by id otherwise throw ModelNotFoundException exception
	 *
	 * @param       $id
	 * @param array $columns
	 *
	 * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
	 */
	public function findOrFail($id, $columns = ['*'])
	{
		return $this->query()->findOrFail($id, $columns);
	}

	/**
	 * Get a row searching by column name otherwise throw ModelNotFoundException exception
	 *
	 * @param $column
	 * @param $value
	 * @param array $columns
	 *
	 * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
	 */
	public function findOrFailBy($column, $value, $columns = ['*'])
	{
		return $this->query()->where($column, $value)->firstOrFail($columns);
	}

	/**
	 * Get row count
	 *
	 * @return int
	 */
	public function getCount()
	{
		return $this->query()->count();
	}
	// Added to count by Month
	public function getMonthCount()
	{
		$usersThisMonth = $this->query()
		                       ->selectRaw('count(id) as count, substring(created_at,9,2) as day')
		                       ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()->addDay()])
		                       ->groupBy('day')
		                       ->pluck('count', 'day')
		                       ->count();

		return $usersThisMonth;
	}

	public function getTotalMonthCount()
	{
		$usersThisMonth = $this->query()
		                       ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()->addDay()])
		                       ->count();

		return $usersThisMonth;
	}
	// 
	public function getWeekCount()
	{
		return $this->query()->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()->addDay()])->count();
	}

	public function getYesterdayCount(){
		return $this->query()->whereDate('created_at', date('Y-m-d',strtotime("-1 days")))->count();
	}

	public function getTodayCount(){
		return $this->query()->whereDate('created_at', date('Y-m-d'))->count();
	}

	/**
	 * Store data to database
	 *
	 * @param array $options
	 *
	 * @return \Illuminate\Database\Eloquent\Model
	 */
	public function store($options = [])
	{
		return $this->modelCrud->setModel($this->getNewModel())->save($options);
	}

	/**
	 * Update data by model
	 *
	 * @param       $model
	 * @param array $options
	 *
	 * @return \Illuminate\Database\Eloquent\Model
	 */
	public function updateByModel($model, $options = [])
	{
		return $this->modelCrud->setModel($model)->save($options);
	}

	/**
	 * Update data by model
	 *
	 * @param int $id
	 * @param array $options
	 *
	 * @return \Illuminate\Database\Eloquent\Model
	 */
	public function update($id, $options = [])
	{
		$model = $this->findOrFail($id);

		return $this->updateByModel($model, $options);
	}

	/**
	 * Delete a row from the database
	 *
	 * @param $model
	 *
	 * @return \Illuminate\Database\Eloquent\Model
	 */
	public function deleteByModel($model)
	{
		$model->delete();

		return $model;
	}

	/**
	 * Delete a row from the database
	 *
	 * @param int $id
	 *
	 * @return \Illuminate\Database\Eloquent\Model
	 */
	public function delete($id)
	{
		$model = $this->findOrFail($id);

		return $this->deleteByModel($model);
	}

	public function deleteMultiple($ids)
	{
		$model = static::MODEL;

		return $model::destroy($ids);
	}

	/**
	 * Returns new static builder instance
	 *
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function query()
	{
		return call_user_func(static::MODEL . '::query');
	}

	/**
	 * Returns this month data in this format [1=>5, 2=>4, ...., 28=>3]
	 *
	 * @return array
	 */
	public function thisMonthData()
	{
		$usersThisMonth = $this->query()
		                       ->selectRaw('count(id) as count, substring(created_at,9,2) as day')
		                       ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()->addDay()])
		                       ->groupBy('day')
		                       ->pluck('count', 'day')
		                       ->toArray();

		$returnData  = [];
		$daysInMonth = range(1, now()->daysInMonth);
		foreach ($daysInMonth as $value) {
			$key = sprintf('%02d', $value);

			$returnData[ $value ] = $usersThisMonth[ $key ] ?? 0;
		}

		return $returnData;
	}

	public function thisWeek(){
		return ['from' => now()->startOfWeek()->format('Y-m-d H-i-s'), 'to' => now()->endOfWeek()->format('Y-m-d H-i-s')];
	}

	public function thisMonth(){
		return ['from' => now()->startOfMonth()->format('Y-m-d H-i-s'), 'to' => now()->endOfMonth()->format('Y-m-d H-i-s')];
	}

	public function customDateData($from, $to)
	{
		$totalDataCount = $this->query()
		                       ->selectRaw('count(id) as count, substring(created_at,9,2) as day')
		                       ->whereBetween('created_at', [$from, $to])
		                       ->groupBy('day')
		                       ->pluck('count', 'day')
		                       ->toArray();

		$returnData  = [];
		$daysInMonth = range(1, now()->daysInMonth);
		foreach ($daysInMonth as $value) {
			$key = sprintf('%02d', $value);

			$returnData[ $value ] = $totalDataCount[ $key ] ?? 0;
		}

		return $returnData;
	}
}