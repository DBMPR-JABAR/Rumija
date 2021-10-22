<?php

namespace App\Imports;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use phpDocumentor\Reflection\Types\Boolean;
use PHPUnit\Framework\Constraint\Count;

class SurveiKondisiJalanImport implements ToCollection
{

    private $idRuasJalan,$isDeleted;

    public function __construct(string $idRuasJalan, string $isDeleted)
    {
        $this->idRuasJalan = $idRuasJalan;
        $this->isDeleted = $isDeleted;
    }
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        $totalSpeed = 0;
        $averageSpeed = 0;
        $count = 0;
        foreach ($collection as $row) {
            if ($count > 1) {
                //dd($row[4]);
                $totalSpeed = $totalSpeed + $row[4];
            }
            $count = $count + 1;
        };
        $averageSpeed = $totalSpeed / $count;
        $masterRuasJalan = DB::table('master_ruas_jalan')->where('id_ruas_jalan', $this->idRuasJalan)->first();
        //$tempLat = (float)$masterRuasJalan->lat_awal;
        //$tempLong = (float)$masterRuasJalan->long_awal;
        $isFirst = 0;
        //$distance = 0;
        foreach ($collection as $row) {
            if ($isFirst > 1) {
                //$segmentExt = getDistanceBetweenLatLong($tempLat, $tempLong, (float)$row[1], (float)$row[2]);
                //$tempLat = $row[0];
                //$tempLong = $row[1];
                //$formatedDistance = number_format((float)$segmentExt, 2, '.', '');
                //$distance = $distance + $formatedDistance;
                //$tempSegment = number_format((float)$distance / 100, 2, '.', '');
                //$formatedSegment = str_pad($tempSegment, 6, 0, STR_PAD_LEFT);
                //dd($row[0]);
                $surveiKondisiJalan = [
                    'created_at' => Carbon::parse($row[0]),
                    'id_ruas_jalan' => $this->idRuasJalan,
                    //'id_segmen' => $this->idRuasJalan . $formatedSegment,
                    'id_segmen' => $this->idRuasJalan . str_pad(number_format((float)$row[3]/100, 2, '.', ''), 6, 0, STR_PAD_LEFT),
                    'latitude' => $row[1],
                    'longitude' => $row[2],
                    //'distance' => $distance,
                    'distance' => $row[3],
                    'speed' => $row[4],
                    'avg_speed' => $averageSpeed,
                    'altitude' => $row[5],
                    'grade' => $row[6],
                    'e_iri' => $row[7],
                    'c_iri' => $row[8],
                    'road_id' => $row[9],
                    'created_user' => Auth::user()->id,
                ];
                $skjTableInsert = DB::table('roadroid_trx_survey_kondisi_jalan');
                $skjTable = DB::table('roadroid_trx_survey_kondisi_jalan')->where(Arr::only($surveiKondisiJalan, ['id_ruas_jalan','latitude','longitude']));
                if($this->isDeleted == "Y")
                    $skjTable->delete();
                else if(Count($skjTable->get())) {
                $surveiKondisiJalanUpdate = array_merge($surveiKondisiJalan, ['updated_at' => Carbon::now(),'updated_user' =>  Auth::user()->id,]);
                //dd($surveiKondisiJalanUpdate);
                $skjTable->update($surveiKondisiJalanUpdate);
                }
                else
                    $skjTableInsert->insert($surveiKondisiJalan);
            }
            $isFirst = $isFirst + 1;
        }
    }
}
