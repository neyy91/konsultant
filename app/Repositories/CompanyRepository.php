<?php 

namespace App\Repositories;

use App\Models\Company;

/**
* Репозиторий для компании.
*/
class CompanyRepository
{

    /**
     * @var integer
     */
    const DEFAULT_PAGE = 10;
    const DEFAULT_TAKE = 4;

    /**
     * With по умолчанию.
     * @var array
     */
    static $with = [
        'default' => ['lawyers', 'logo', 'lawyers.user', 'lawyers.user.photo'],
    ];


    public function paginateCompanies($active = true, $page = null, $with = null)
    {
        
        $page = $page ? $page : config('site.user.company.page', self::DEFAULT_PAGE);
        $with = $with ? $with : self::$with['default'];
        return Company::active($active)->orderBy('name', 'asc')->orderBy('id', 'desc')->with($with)->paginate($page);
    }
}