<?php

namespace App;

use App\Models\Invoice;
use App\Models\Post;
use App\Models\RequestService;
use App\Models\Service;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;


class User extends Eloquent implements
    AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use Authenticatable, Authorizable, Notifiable, CanResetPassword;
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */


    protected $casts = [
        'email_verified_at' => 'datetime',

    ];
    protected $fillable = [
        'name', 'email', 'password', 'phone', 'api_token', 'role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'api_token', 'device_token', 'device_platform'
    ];

    protected $appends = ['rating_object', 'feedback_object'];


    public function isClient()
    {
        return $this->role == 'user' || $this->role == 'user_employee';
    }

    public function isHandyman()
    {
        return $this->role == 'employee' || $this->role == 'user_employee';
    }

    public function isAdmin()
    {
        return $this->role == 'admin';
    }

    public function services()
    {
        return $this->belongsToMany(Service::class);
    }

    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }

    public function invoices()
    {
        return $this->belongsToMany(Invoice::class);
    }


    public function employeeRequests()
    {
        return $this->belongsToMany(
            RequestService::class, null, 'employee_ids', 'employee_request_ids'
        );

    }

    public function clientRequests()
    {
        return $this->belongsToMany(
            RequestService::class, null, 'client_ids', 'client_request_ids'
        );
    }

    public function simplifiedArray()
    {
        return [
            '_id' => $this->_id,
            'name' => $this->name,
            'image' => $this->image,
        ];
    }

    /**
     * @inheritDoc
     */
    public function hasVerifiedEmail()
    {
        // TODO: Implement hasVerifiedEmail() method.
    }

    /**
     * @inheritDoc
     */
    public function markEmailAsVerified()
    {
        // TODO: Implement markEmailAsVerified() method.
    }

    /**
     * @inheritDoc
     */
    public function sendEmailVerificationNotification()
    {
        // TODO: Implement sendEmailVerificationNotification() method.
    }

    /**
     * @inheritDoc
     */
    public function getEmailForVerification()
    {
        // TODO: Implement getEmailForVerification() method.
    }


    public function getRatingObjectAttribute()
    {
        if ($this->service_ids == null)
            return [];
        $services = Service::query()->whereIn('_id', $this->service_ids)->get();
        $result = [];
        foreach ($services as $service) {
            $sum = 0;
            $count = 0;

            $counter1 = 0;
            $counter2 = 0;
            $counter3 = 0;
            $counter4 = 0;
            $counter5 = 0;
            $reqs = RequestService::query()->where("service_id", $service->id)
                ->where('rating', '!=', null)
                ->where('employee_ids', [$this->_id])
                ->get();
            foreach ($reqs as $req) {
                $l = $req->employee_ids;
                if (is_array($l) &&
                    end($l) == $this->_id) {
                    $count++;
                    $sum += $req->rating;
                    if ($req->rating <= 1) {
                        $counter1++;
                    } else if ($req->rating <= 2) {
                        $counter2++;
                    } else if ($req->rating <= 3) {
                        $counter3++;
                    } else if ($req->rating <= 4) {
                        $counter4++;
                    } else if ($req->rating <= 5) {
                        $counter5++;
                    }
                    {

                    }
                }

            }
            if ($count > 0) {
                $result[$service->_id][0] = $sum / $count;
                $result[$service->_id][1] = $counter1;
                $result[$service->_id][2] = $counter2;
                $result[$service->_id][3] = $counter3;
                $result[$service->_id][4] = $counter4;
                $result[$service->_id][5] = $counter5;
            } else
                $result[$service->_id] = 0;
        }
        return $result;
    }


    public function getFeedbackObjectAttribute()
    {
        if ($this->service_ids == null)
            return [];
        $services = Service::query()->whereIn('_id', $this->service_ids)->get();
        $feedback = [];
        foreach ($services as $service) {
            $reqs = RequestService::query()->where("service_id", $service->id)
                ->where('feedback', '!=', null)
                ->where('employee_ids', [$this->_id])
                ->get();
            $_feedback = [];
            foreach ($reqs as $req) {
                $object = [];
                $object['service_name'] = $service->name;
              $object['feedback'] =$req->feedback[0]['body'];
                $object['rating'] = $req->rating;
                $object['client'] = User::query()->find($req->client_ids[0])->simplifiedArray();

                array_push($_feedback, $object);

            }
            if ($_feedback == null) {
                $feedback[$service->id] = $service->name;
            } else {
                $feedback[$service->id] = $_feedback;
            }

        }


        return $feedback;
    }
}
