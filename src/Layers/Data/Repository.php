<?php


namespace Centras\Layers\Data;

use Illuminate\Database\Eloquent\Model;

/**
 * Базовый класс репозитории для Laravel
 *
 * Class Repository
 * @package Centras\Layers\Data
 */
abstract class Repository
{
    /**
     * Current Model
     *
     * @var Model|null
     */
    protected Model|null $model = null;

    /**
     * get ALL
     */
    public function all()
    {
        return $this->model::all();
    }

    /**
     * find by ID
     */
    public function byId(int $id): Model
    {
        return $this->model->find($id);
    }

    /**
     * @param array $payload
     * @return mixed
     */
    public function create(array $payload)
    {
        return $this->model->create($payload);
    }
}
