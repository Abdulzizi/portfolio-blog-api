<?php

namespace App\Models;

use App\Http\Traits\Uuid;
use App\Repository\CrudInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class UserModel extends Authenticatable implements CrudInterface, JWTSubject
{
    use HasFactory;
    use Uuid; // Use SoftDeletes library

    public $timestamps = true;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
    ];

    protected $table = 'users';

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            'user' => [
                'id' => $this->id,
                'email' => $this->email,
                'updated_security' => $this->updated_security,
            ],
        ];
    }


    public function drop(string $id)
    {
        return $this->find($id)->delete();
    }

    public function edit(array $payload, string $id)
    {
        return $this->find($id)->update($payload);
    }

    public function getAll(array $filter, int $page = 1, int $itemPerPage = 0, string $sort = '')
    {

        $skip = ($page * $itemPerPage) - $itemPerPage;
        $user = $this->query();

        if (! empty($filter['username'])) {
            $user->where('username', 'LIKE', '%' . $filter['username'] . '%');
        }

        if (! empty($filter['email'])) {
            $user->where('email', 'LIKE', '%' . $filter['email'] . '%');
        }

        $allowedSorts = [
            'username_asc' => 'username ASC',
            'username_desc' => 'username DESC',
            'email_asc' => 'email ASC',
            'email_desc' => 'email DESC',
        ];

        $sortKey = str_replace(' ', '_', strtolower($sort));

        if (isset($allowedSorts[$sortKey])) {
            $user->orderByRaw($allowedSorts[$sortKey]);
        } else {
            $user->orderBy('id', 'asc');
        }

        // dd($sortKey);

        $total = $user->count();
        $list = $user->skip($skip)->take($itemPerPage)->get();

        return [
            'total' => $total,
            'data' => $list,
        ];
    }

    public function getById(string $id)
    {
        return $this->find($id);
    }

    public function store(array $payload)
    {
        return $this->create($payload);
    }
}
