<?php

namespace Rocket\Interfaces;

interface iCrud
{
    /*CRUD*/
    public function create($attributes);

    public function read($order_by = null);

    public function update($attributes,$id);

    public function delete($column, $value);

    /*Helpers*/
    public function find($column, $value);

    public function where($column, $condition, $value);

    public function like($column, $value);

    public function count();

    public function query($param);

    public function read_page($init ,$results, $order_by = null);
}