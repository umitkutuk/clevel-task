<?php

namespace App\Repositories\Interfaces;

use Illuminate\Http\Request;

interface NoteInterface {

    public function findById(int $id);

    public function getAll($page = false);

    public function create();

    public function store(array $request);

    public function update(array $request, int $id);

    public function edit(int $id);

    public function delete(int $id);

}
