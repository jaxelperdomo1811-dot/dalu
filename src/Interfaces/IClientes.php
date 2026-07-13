<?php
    namespace Lenovo\Dalu\Interfaces;
    
    interface IClientes {
        public function insert();
        public function search();
        public function searchInactive();
        public function update();
        public function delete();
        public function activate();
        
    }