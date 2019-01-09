<?php

namespace App\Console\Commands;

use \Date;
use Illuminate\Console\Command;

use App\Helper;
use App\Models\User;
use App\Models\Lawyer;
use App\Models\Like;


/**
 * Расчет рейтинга.
 */
class Rating extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rating:calc';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculation of lawyer ratings';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Дата с новго гоад
     * @return Jenssegers\Date
     */
    public function getNewYearDate()
    {
        return new Date(date('Y') . '-01-01');
    }

    protected function ratingLike(Lawyer $lawyer)
    {
        $query = $lawyer->liked()->where('likes.created_at', '>=', $this->getNewYearDate());
        $up = $query->where('likes.rating', '=', Like::RATING_LIKE)->count();
        $down = $query->where('likes.rating', '=', Like::RATING_DONT_LIKE)->count();
        // print 'up = ' . $up . '; $down = ' . $down . "\n";
        return $up + $down > 0 ? Helper::rating($up, $down) : 0;
    }

    protected function ratingSelected(Lawyer $lawyer)
    {
        $query = $lawyer->answers()->where('created_at', '>=', $this->getNewYearDate());
        $all = $query->count();
        $selected = $query->where('is', '=', 1)->count();
        return $all > 0 ? Helper::ratingSelected($selected, $all) : 0;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $lawyers = Lawyer::active()->get()->all();

        foreach ($lawyers as $lawyer) {
            $ratingLike = $this->ratingLike($lawyer);
            $ratingSelected = $this->ratingSelected($lawyer);
            $lawyer->rating = round($ratingLike + $ratingSelected, 1);
            $lawyer->save();
        }

    }
}
