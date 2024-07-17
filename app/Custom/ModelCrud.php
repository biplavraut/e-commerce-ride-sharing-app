<?php

namespace App\Custom;

use App\Custom\RequestHandler\RequestHandlerFactory;
use App\Custom\RequestHandler\RequestHandlerInterface;
use Illuminate\Database\Eloquent\Model;

class ModelCrud
{
	/**
	 * @var Model
	 */
	private $model;

	public function __construct(Model $model = null)
	{
		$this->model = $model;
	}

	/**
	 * @param Model $model
	 *
	 * @return ModelCrud
	 */
	public function setModel(Model $model): ModelCrud
	{
		$this->model = $model;

		return $this;
	}

	/**
	 * If options are present, loop through option else loop through the
	 * $columnsWithTypes property defined in the model
	 * Create Data type handler class from the given data type of the input data or options
	 * Assign it to model and save
	 *
	 * @param array $options
	 *
	 * @return Model
	 * @throws \Exception
	 */
	public function save($options = []): Model
	{
		if (count($options)) {
			foreach ($options as $column => $value) {
				$column = snake_case($column);
				if (!isset($this->model->columnsWithTypes[ $column ])) {
					throw new \Exception("'{$column}' column is not defined in " . get_class($this->model) . ' class.');
				}

				$requestHandler = RequestHandlerFactory::make($this->model->columnsWithTypes[ $column ]);

				$this->assignFieldsToModel($column, $value, $requestHandler);
			}
		} else {
			foreach ($this->model->columnsWithTypes as $column => $type) {
				$column         = snake_case($column);
				$requestHandler = RequestHandlerFactory::make($type);

				$this->assignFieldsToModel($column, request()->$column, $requestHandler);
			}
		}

		$this->model->save();

		return $this->model;
	}

	/**
	 * Assign request data to builder if request has data
	 *
	 * @param string $column
	 * @param mixed $value
	 * @param RequestHandlerInterface $requestHandler
	 *
	 * @return void
	 */
	private function assignFieldsToModel($column, $value, RequestHandlerInterface $requestHandler): void
	{
		//if ($value !== null) {
		$this->model->$column = $requestHandler->parse($column, $value, $this->model);
		//}
	}
}
