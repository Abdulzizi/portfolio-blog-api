<?php

namespace App\Models;

use App\Http\Traits\Uuid;
use App\Repository\CrudInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class UserModel extends Authenticatable implements CrudInterface, JWTSubject
{
    use HasFactory;
    use SoftDeletes; // Use SoftDeletes library
    use Uuid; // Use SoftDeletes library

    /**
     * Akan mengisi kolom "created_at" dan "updated_at" secara otomatis,
     *
     * @var bool
     */
    public $timestamps = true;

    protected $attributes = [
        'user_roles_id' => 1, // memberi nilai default = 1 pada kolom user_roles_id
    ];

    /**
     * Menentukan kolom apa saja yang bisa dimanipulasi oleh UserModel
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'photo',
        'user_roles_id',
        'phone_number',
    ];

    /**
     * Menentukan nama tabel yang terhubung dengan Class ini
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * Relation to Role
     *
     * @return void
     */
    public function role()
    {
        return $this->hasOne(RoleModel::class, 'id', 'user_roles_id');
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Payload yang disimpan pada token JWT, jangan tampilkan informasi
     * yang bersifat rahasia pada payload ini untuk mengamankan akun pengguna
     *
     * @return array
     */
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

    /**
     * Method untuk mengecek apakah user memiliki permission
     *
     * @param  string  $permissionName  contoh: user.create / user.update
     * @return bool
     */
    public function isHasRole($permissionName)
    {
        $tokenPermission = explode('|', $permissionName);
        $userPrivilege = json_decode($this->role->access ?? '{}', true);

        foreach ($tokenPermission as $val) {
            $permission = explode('.', $val);
            $feature = $permission[0] ?? '-';
            $activity = $permission[1] ?? '-';
            if (isset($userPrivilege[$feature][$activity]) && $userPrivilege[$feature][$activity] === true) {
                return true;
            }
        }

        return false;
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

        if (! empty($filter['name'])) {
            $user->where('name', 'LIKE', '%' . $filter['name'] . '%');
        }

        if (! empty($filter['email'])) {
            $user->where('email', 'LIKE', '%' . $filter['email'] . '%');
        }

        $total = $user->count();
        $list = $user->skip($skip)->take($itemPerPage)->orderByRaw($sort)->get();

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
