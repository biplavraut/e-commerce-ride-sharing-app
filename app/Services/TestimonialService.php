<?php

namespace App\Services;

use App\Testimonial;
use Yajra\DataTables\Facades\DataTables;

class TestimonialService extends ModelService {
	const MODEL = Testimonial::class;

	public function getForIndex($limit = 20, $columns = ['*']) {
		return $this->query()->latest()->paginate($limit, $columns);
	}

	public function getForDataTable($columns = ['*']) {
		$query = $this->query()->select($columns)->latest();

		return DataTables::eloquent($query)
		                 ->addColumn('image50', function(Testimonial $model) {
			                 return $model->image(50, 50);
		                 })
		                 ->addColumn('action', function(Testimonial $model) {
			                 return (string)view('admin.extras.actions', ['model' => $model]);
		                 })
		                 ->toJson();
	}

	public function deleteByModel($model) {
		$model->deleteImage();

		return parent::deleteByModel($model);
	}

}