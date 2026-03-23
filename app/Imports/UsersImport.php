<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    protected $collectionId;
    public $role;

    public function __construct($cId, $role)
    {
        $this->collectionId = $cId;
        $this->role = $role;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $rows)
    {
        $heading = $rows->first()->keys()->toArray();
        $matching = ['username', 'fullname', 'email', 'password'];
        $missing = array_diff($matching, $heading);
        if(!empty($missing)) {
            throw new \Exception('header tidak lengkap, membutuhkan beberapa header lagi: ' . implode(', ', $missing));
        }

        foreach ($rows as $row) {
            User::updateOrCreate([
                'username' => $row['username'],
                'email' => $row['email'],
            ], [
                'username' => $row['username'],
                'email' => $row['email'],
                'fullname' => $row['fullname'],
                'password' => Hash::make($row['password']),
                'role' => $this->role,
                'collection_id' => $this->collectionId
            ]);
        }
    }
}
