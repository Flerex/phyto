<?php


namespace App\Domain\Services;


use App\Domain\Models\Sample;
use Illuminate\Support\Collection;

interface SampleService
{

    /**
     * Returns a list with all the images that have been processed for a given sample.
     *
     * @param  Sample  $sample
     * @return Collection
     */
    public function get_processed_images(Sample $sample): Collection;

    /**
     * Returns the amount of images there are in a sample.
     * @param  Sample  $sample
     * @return int
     */
    public function get_total_images(Sample $sample): int;
}
