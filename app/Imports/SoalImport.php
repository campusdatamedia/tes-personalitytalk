<?php

namespace App\Imports;

use App\Soal;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class SoalImport implements ToModel, WithStartRow
{
    // Construct
    public function __construct(int $paket){
        $this->paket = $paket;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Get data
        $soal = Soal::where('id_paket','=',$this->paket)->orderBy('nomor','asc')->get();
        $no_soal = array();
        foreach($soal as $data){
            array_push($no_soal, $data->nomor);
        }

        // Cek apakah akan menginput / mengupdate data
        if(in_array($row[0], $no_soal)){
            // Mengupdate data
            $data = Soal::where('id_paket','=',$this->paket)->where('nomor','=',$row[0])->first();
            $data->soal =   json_encode(
                                array(
                                    array(
                                        'pilihan'=>array(
                                            'A' => $row[1],
                                            'B' => $row[2],
                                            'C' => $row[3],
                                            'D' => $row[4],
                                        ),
                                        'disc'=>array(
                                            'A' => $row[5],
                                            'B' => $row[6],
                                            'C' => $row[7],
                                            'D' => $row[8],
                                        ),
                                    )
                                )
                            );
            $data->save();
        }
        else{
            // Menambah data
            $data = new Soal();
            $data->id_paket = $this->paket;
            $data->nomor = $row[0];
            $data->soal =   json_encode(
                                array(
                                    array(
                                        'pilihan'=>array(
                                            'A' => $row[1],
                                            'B' => $row[2],
                                            'C' => $row[3],
                                            'D' => $row[4],
                                        ),
                                        'disc'=>array(
                                            'A' => $row[5],
                                            'B' => $row[6],
                                            'C' => $row[7],
                                            'D' => $row[8],
                                        ),
                                    )
                                )
                            );
            $data->save();
        }
    }

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }
}
