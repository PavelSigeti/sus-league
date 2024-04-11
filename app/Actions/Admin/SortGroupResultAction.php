<?php

namespace App\Actions\Admin;

class SortGroupResultAction
{
    public function __invoke($results, $drops)
    {

        $response = clone $results;

        $dnf = count($results) + 1;
        foreach ($results as $userId => $user) {
            foreach ($user as $key => $race) {
                if($race['place'] === null) {
                    $response[$userId][$key]['place'] = $dnf;
                }
            }
            $response[$userId] = $user->sort(function($a, $b){
                return ($a->place > $b->place) ? 1 : -1;
            })->values();
            if($drops > 0 && $response[$userId]->count() >= $drops) {
                for($i=1; $i<=$drops; $i++) {
                    $response[$userId] = $response[$userId]
                        ->splice(0, $response[$userId]->count() - 1);
                }
            }
            $response[$userId]['sum'] = $response[$userId]->sum(['place']);
        }

        $rawData = $response->sort(function($a, $b){
            if($a['sum'] < $b['sum']) {
                return -1;
            } elseif ($a['sum'] > $b['sum']) {
                return 1;
            }
            else {
                $raceAmount = $b->count() - 1;
                for($i=0; $i < $raceAmount; $i++) {
                    if($a[$i]->place < $b[$i]->place) {
                        return -1;
                    } elseif ($a[$i]->place > $b[$i]->place) {
                        return 1;
                    } elseif ($i == $raceAmount-1) {
                        $aRaceId = $a->max('race_id');
                        $bRaceId = $b->max('race_id');

                        $aPlace = $a->where('race_id', $aRaceId)->first()->place;
                        $bPlace = $b->where('race_id', $bRaceId)->first()->place;

                        if($aPlace < $bPlace) {
                            return -1;
                        } else {
                            return 1;
                        }
                    }
                }
            }
        });

        return $rawData->keys();
    }
}
