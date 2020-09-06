<?php


namespace App\Domain\Services;


use App\Domain\Models\Sample;
use Illuminate\Support\Collection;

class SampleServiceImpl implements SampleService
{

    public function get_processed_images(Sample $sample): Collection
    {
        return $sample->images()->processed()->get();
    }

    public function get_total_images(Sample $sample): int
    {
        return $sample->images()->count();
    }
}
