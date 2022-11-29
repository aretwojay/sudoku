<?php

class Sudoku {

    public function __construct($size){
        //le nombre maximal qu'on peut afficher
        $this->size = $size  * $size;
    }

    public function isValid($grid, $row, $col, $val){
        $sqrt_size = sqrt($this->size);
        $square_X = $row - $row % $sqrt_size;
        $square_Y = $col - $col % $sqrt_size;

        //check si le chiffre n'est pas sur la meme ligne
        for ($i = 0; $i < $this->size; $i++){
            if ($grid[$row][$i] == $val){
                return false;
            }
        }

        //check si le chiffre n'est pas sur la meme colonne
        for ($i = 0; $i < $this->size; $i++){
            if ($grid[$i][$col] == $val){
                return false;
            }
        }

        //check si le chiffre n'est pas dans le meme carre
        for ($i = 0; $i < $sqrt_size; $i++){
            for ($j = 0; $j < $sqrt_size; $j++){
                if ($grid[$i + $square_X][$j + $square_Y] == $val){
                    return false;
                }
            }
        }
        return true;
    }

    public function solve($grid, $row, $col){

        //condition de fin
        if ($row === $this->size - 1 && $col === $this->size){
            $this->grid = $grid;
            return true;
        }

        //si est au dernier element de la ligne, on passe a la suivante
        if ($col === $this->size){
            $row++;
            $col = 0;
        }

        if ($grid[$row][$col] !== "."){
            return self::solve($grid, $row, $col + 1);
        }

        for ($val = 1; $val <= $this->size; $val++){
            if (self::isValid($grid, $row, $col, $val)){
                $grid[$row][$col] = $val;

                //check si on a fait le bon choix
                if (self::solve($grid, $row, $col + 1)){
                    return true;
                }
            }
            //sinon on reinitialise le choix effectue
            $grid[$row][$col] = ".";
        }
        return false;

    }


    public function print(){
        if (isset($this->grid)){
            echo "new grid : " . PHP_EOL. implode("\n", $this->grid) . PHP_EOL;
        }
        else {
            echo "Impossible à résoudre sah" . PHP_EOL;
        }
    }
}

if (isset($argv[1]) && isset($argv[2]) && is_file($argv[2])){
    $sudoku = new Sudoku($argv[1]);
    $grid = explode("\n", file_get_contents($argv[2]));
    $sudoku->solve($grid,0,0);
    $sudoku->print();
}