<?php
class algenfunc extends CI_Controller {
  public function __construct()
	{
		parent::__construct();
		$this->basearray=array("yogyakarta","surabaya","bandung","jakarta" );
    $this->jarak =array("semarang"=>  array("yogyakarta" => 123,
                                              "surabaya"  => 315,
                                              "bandung"   => 445,
                                              "jakarta"   => 455),
                        "yogyakarta"=>array(  "semarang"  => 123,
                                              "surabaya"  => 329,
                                              "bandung"   => 519,
                                              "jakarta"   => 525),
                        "surabaya"=> array(   "semarang"  => 315,
                                              "yogyakarta"=> 329,
                                              "bandung"   => 763,
                                              "jakarta"   => 767),
                        "bandung"=> array(    "semarang"  => 445,
                                              "yogyakarta"=> 519,
                                              "surabaya"  => 763,
                                              "jakarta"   => 154),
                        "jakarta"=> array(    "semarang"  => 455,
                                              "yogyakarta"=> 525,
                                              "surabaya"  => 767,
                                              "bandung"   => 154)
                      );
	}
  function index(){
    $this->load->view('home');
  }
  function tabelJarak(){

  }

  function algenStart(){
    $save = array('html' => "",
                      'jarak'=>0,
                      'fitness'=>0,
                      'weight'=>0,
                      'iter'=>0,
                      'array'=> array(''));

    $max_generasi=$this->input->post('max_generasi');
    $max_populasi=$this->input->post('max_populasi');
    $prob_mutasi=$this->input->post('prob_mutasi');
    $populasi=$this->generatePopulation($max_populasi);
    $this->iterasiGenetika($populasi,$max_populasi,$prob_mutasi,$max_generasi,1,$save);




  }

  function iterasiGenetika($populasi,$max_populasi,$prob_mutasi,$max_generasi,$iterasi,$save){
      if ($max_generasi==0){
        echo $save["html"];
        echo '<div class="alert alert-success" role="alert">';
        echo '<div><strong>Solusi Terbaik</strong></div>';
        echo '<div>semarang->';
        foreach ($save["array"] as $key => $value) {
          echo $value."->";
        };
        echo 'semarang = ';
        echo $save["jarak"].' KM  pada iterasi Ke - '.$save["iter"].'<div>';
        echo "</div>";

      }else{
        $new_populasi=$this->calculateFitness($populasi);
        $new_populasi=$this->elitismSelection($new_populasi,$max_populasi);
        $off=$this->orderCrossover($new_populasi);
        $mut=$this->genMutation($off[rand(0,1)],$prob_mutasi);
        $new_populasi[]=$off[0];
        $new_populasi[]=$off[1];
        $panjang=count($new_populasi);
        if($mut!=0){
          $new_populasi[]=$mut;
        }

        $new_populasi=$this->calculateFitness($new_populasi);
        $new_save = array('html' => "",
                          'jarak'=>0,
                          'fitness'=>0,
                          'weight'=>0,
                          'iter'=>0,
                          'array'=> array(''));
        $new_save["html"].=$save["html"];
        $new_save["jarak"]=$save["jarak"];
        $new_save["fitness"].=$save["fitness"];
        $new_save["weight"]=$save["weight"];
        $new_save["iter"]=$save["iter"];
        $new_save["array"]=$save["array"];

        $new_save["html"].="<table  ";
              if($iterasi>1){
                   $new_save["html"].="style='display: none;'";
              }
        $new_save["html"].= "  class='table hasil hsl".$iterasi."'>";
        $new_save["html"].= "<tr>
                <th class='col-sm-1'>Kode</th>
                <th class='col-sm-1'> 0 </th>
                <th class='col-sm-1'> 1 </th>
                <th class='col-sm-1'> 2 </th>
                <th class='col-sm-1'> 3 </th>
                <th class='col-sm-1'> Jarak </th>
                <th class='col-sm-3'> Fitness </th>
                <th class='col-sm-3'> Kumulatif </th>
              </tr>";

        foreach ($new_populasi as $key => $value) {
              $new_save["html"].= "<tr>";
          switch ($key) {
            case $panjang-2:
              $new_save["html"].= "<td>C0</td>";
              break;
            case $panjang-1:
              $new_save["html"].= "<td>C1</td>";
              break;
            case $panjang:
              $new_save["html"].= "<td>M0</td>";
              break;

            default:
              $new_save["html"].= "<td>P".$key."</td>";
              break;
          }
          //echo "semarang => ";
          foreach ($value["array"] as $key2 => $value2) {
            $new_save["html"].= "<td>".$value2."</td>";
          }
        //  echo "semarang ";
          $new_save["html"].= "<td>".$value["jarak"]."</td><td>".$value["fitness"]."</td><td>".$value["weight"]."</td>";
          $new_save["html"].= "</tr>";
          if($new_save["jarak"]==0 || $new_save["jarak"]>$value["jarak"]){
            $new_save["array"]=$value["array"];
            $new_save["jarak"]=$value["jarak"];
            $new_save["fitness"]=$value["fitness"];
            $new_save["weight"]=$value["weight"];
            $new_save["iter"]=$iterasi;
          }
        }
        //$new_save["html"].= "<tr><td>".$off['rand1']." / ".$off['rand2']."</td></tr>";
        $new_save["html"].= "</table>";

        $iterasi++;
        $max_generasi--;
        $this->iterasiGenetika($new_populasi,$max_populasi,$prob_mutasi,$max_generasi,$iterasi,$new_save);
      }

  }



  function generatePopulation($pop){
    for ($i=0; $i <=$pop-1 ; $i++) {
      shuffle($this->basearray);
      $populasi[$i]["array"]=$this->basearray;
      $populasi[$i]["jarak"]=$this->calculateDistance($populasi[$i]["array"]);
    }
    return $populasi;
  }
  function calculateDistance($array){
    $hasil = $this->jarak["semarang"][$array[0]] +
            $this->jarak[$array[0]][$array[1]] +
            $this->jarak[$array[1]][$array[2]] +
            $this->jarak[$array[2]][$array[3]] +
            $this->jarak[$array[3]]["semarang"];
    return $hasil;
  }
  function calculateFitness($array){
    $weight =0;
    $kum =0;
    $max =count($array)-1;
    for ($i=0; $i<= $max; $i++) {
        $array[$i]["fitness"]=10000/$array[$i]["jarak"];
        $kum+=$array[$i]["fitness"];
    }
    for ($i=0; $i<=$max; $i++){
        $array[$i]["weight"]=($array[$i]["fitness"]/$kum)+$weight;
        $weight=($array[$i]["fitness"]/$kum)+$weight;
    }

    return $array;
  }
  function orderCrossover($array){
    $maxpop =count($array)-1;
    $parent= $this->randomKey($maxpop);
    while($parent[0]==$parent[1]){
     $parent= $this->randomKey($maxpop);
    }
    $par1 = $array[$parent[0]];
    $par2 = $array[$parent[1]];

    $maxar =count($par1["array"])-1;
    $key= $this->randomKey($maxar);
    $key1 = $key[0];
    $key2 = $key[1];


    for ($i=0; $i <= $maxar; $i++){
      if($i<$key1 || $i>$key2 ){
        $off1["array"][$i]="0";
        $off2["array"][$i]="0";
      }else{
        $off1["array"][$i]=$par1["array"][$i];
        $off2["array"][$i]=$par2["array"][$i];
      }
    }
    $off1=$this->fillArray($off1,$par2,$key2+1,$maxar+1);
    $off2=$this->fillArray($off2,$par1,$key2+1,$maxar+1);
    $off1["jarak"]=$this->calculateDistance($off1["array"]);
    $off2["jarak"]=$this->calculateDistance($off2["array"]);
    //$off["rand1"]=$parent[0];
    //$off["rand2"]=$parent[1];
    $off[]=$off1;
    $off[]=$off2;


    return $off;
  }
  function genMutation($array,$prob){
    if (mt_rand(0,100)>$prob){
      return 0;
    }else{
      $key=$this->randomKey(count($array["array"])-1);
      while($key[0]==$key[1]){
        $key=$this->randomKey(count($array["array"])-1);
      }
      $temp= $array["array"][$key[0]];
      $array["array"][$key[0]]=$array["array"][$key[1]];
      $array["array"][$key[1]]=$temp;
      $array["jarak"]=$this->calculateDistance($array["array"]);
      return $array;
    }
  }

  function roulleteSelection($array,$max_populasi){
    $total_pop= count($array)-1;

    for ($i=0; $i<= $max_populasi-1; $i++){
      $rand=mt_rand(0,1000)/1000;
      $curwe =0;
      $curkey =0;
      for($j=0; $j<=$total_pop; $j++){
        if($array[$j]["weight"]<=$rand && $array[$j]["weight"]>$curwe){
          $curwe=$array[$j]["weight"];
          $curkey=$j;
        }
      }
      $newpop[]=$array[$curkey];
    }
    return $newpop;
  }

  function randomKey($length){
    $key1 = mt_rand(0,$length);
    $key2 = mt_rand(0,$length);
    if ($key1>$key2){
      $key[0]=$key2;
      $key[1]=$key1;
    }else{
      $key[0]=$key1;
      $key[1]=$key2;
    }
    return $key;
  }
  function fillArray($off,$par,$key,$jumlah_array){
    $key_off=$key%$jumlah_array;
    $key_par=$key%$jumlah_array;
    $i=0;
    while(in_array("0",$off["array"]) && $i<100){
      $key_par=$key_par%$jumlah_array;
      $key_off=$key_off%$jumlah_array;
      if(in_array($par["array"][$key_par],$off["array"])){

        $key_par++;

      }else{
        $off["array"][$key_off]=$par["array"][$key_par];
        $key_par++;
        $key_off++;


      }
      $i++;
    }
    if ($i==100){
      echo "<BR>".$i."ERRRRRRRRRRRRRRRRRRRRRRRRRRORRRRRRRRRRRRRRRR<BR>";
    }
    return $off;
  }
  function elitismSelection($arr,$pop){
    $maxar=count($arr);
    for($i=1;$i<=($maxar-$pop);$i++){
      $min=20000000;

      foreach ($arr as $key => $value) {
        if($value["fitness"]<$min){
          $min=$value["fitness"];
          $keynya=$key;
        }
      }
      array_splice($arr,$keynya,1);
    }
    //foreach ($delkey as $key => $value) {
    //  unset($arr[$value]);
    //}
    //echo $delkey;
    return $arr;
  }

}
?>
