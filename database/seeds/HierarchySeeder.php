<?php

use App\Domain\Models\Classis;
use App\Domain\Models\Domain;
use App\Domain\Models\Genus;
use App\Domain\Models\Species;
use Illuminate\Database\Seeder;

class HierarchySeeder extends Seeder
{

    private const DATA = [

        'Bacteria' => [

            'Cyanophyceae' => [

                'Anabaena' => [
                    'Anabaena spiroides'
                ],

                'Mirocystis' => [
                    'Microcystis aeruginosa',
                    'Microcystis flos-aquae'
                ],

                'Woronichinia' => [
                    'Woronichinia naegeliana',
                ]
            ],


        ],

        'Eukaryota' => [

            'Chlorophyceae' => [
                'Botryococcus' => [
                    'Botryococcus braunii',
                ],

                'Dictyosphaerium' => [
                    'Dictyosphaerium pulchellum',
                ],

                'Pandorina' => [
                    'Pandorina morum',
                ],

                'Volvox' => [
                    'Volvox aureus',
                ],
            ],

            'Desmidiaceae' => [
                'Closterium' => [
                    'Closterium juncidum',
                ],

                'Staurastrum' => [
                    'Staurastrum tohopekaligense',
                ],
            ],

            'Euglenophyceae' => [
                'Lepocinclis' => [
                    'Lepocinclis oxyuris',
                ],

                'Phacus' => [
                    'Phacus tortus',
                ],

                'Trachelomonas' => [
                    'Trachelomonas volvocinopsis',
                ],
            ],

            'Bacillariophyceae' => [
                'Aulacoseira' => [
                    'Aulacoseira granulata',
                ],

                'Asterionella' => [
                    'Asterionella formosa',
                ],

                'Fragilaria' => [
                    'Fragilaria crotonensis',
                ],
            ],

            'Chrysophyceae' => [
                'Dinobryon' => [
                    'Dinobryon sp.'
                ]
            ],

            'Dinophyceae' => [
                'Ceratium' => [
                    'Ceratium hirundinella',
                ],

                'Peridinium' => [
                    'Peridinium sp.',
                ]

            ]

        ],
    ];


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        foreach (self::DATA as $domain => $classis) {
            $domain = Domain::create([
                'name' => $domain,
            ]);

            foreach ($classis as $class => $genera) {

                $class = Classis::create([
                    'name' => $class,
                    'domain_id' => $domain->getKey(),
                ]);


                foreach ($genera as $genus => $species) {

                    $genus = Genus::create([
                        'name' => $genus,
                        'classis_id' => $class->getKey(),
                    ]);

                    foreach ($species as $s) {
                        Species::create([
                            'name' => $s,
                            'genus_id' => $genus->getKey(),
                        ]);
                    }
                }

            }
        }

    }
}
