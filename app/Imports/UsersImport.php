<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    protected $collectionId;
    
    public function __construct($cId)
    {
        $this->collectionId = $cId;
    }
    
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return User::updateOrCreate([
            'username' => $row['username'],
            'email' => $row['email'],
        ], [
            'username' => $row['username'],
            'email' => $row['email'],
            'fullname' => $row['fullname'],
            'password' => Hash::make($row['password']),
            'collection_id' => $this->collectionId
        ]);
    }
}
